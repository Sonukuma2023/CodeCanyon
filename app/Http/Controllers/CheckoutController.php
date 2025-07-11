<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\UserCoupons;
use App\Models\Coupons;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty. Please add products before checking out.'], 422);
        }

        // 2) Validate inputs
        $validatedData = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email',
            'name'           => 'nullable|string|max:255',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'zip'            => 'required|string',
            'country'        => 'required|string',
            'payment_method' => 'required|in:card,cod',
            'stripe_token'   => 'required_if:payment_method,card',
            'final_total'          => 'required|numeric|min:0',
            'applied_coupon_code'  => 'nullable|string|exists:coupons,code',
        ]);

        // 3) Calculate order totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $tax = round($subtotal * 0.18, 2);

        $couponCode = $request->input('applied_coupon_code');
        $coupon = null;
        $discount = 0;

        if ($couponCode) {
            $coupon = Coupons::where('code', $couponCode)
                            ->where('status', 'active')
                            ->first();
            if ($coupon) {
                $discount = $coupon->discount_percentage ?? 0;
            }
        }

        $total = $subtotal - $discount + $tax;

        $frontendTotal = round((float) $request->input('final_total'), 2);

        if ($validatedData['payment_method'] === 'card') {
            // 4) Process Stripe payment
            $amountInCents = round($total * 100);

            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $charge = Charge::create([
                    'amount'        => $amountInCents,
                    'currency'      => 'usd',
                    'source'        => $validatedData['stripe_token'],
                    'description'   => 'Order Payment',
                    'receipt_email' => $validatedData['email'],
                ]);
                $stripeTransactionId = $charge->id;
                $paymentStatus = 'paid';
            } catch (\Exception $e) {
                return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 422);
            }

            // 5) Create order + items within DB transaction
           DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id'        => Auth::id(),
                    'first_name'     => $validatedData['first_name'],
                    'last_name'      => $validatedData['last_name'],
                    'email'          => $validatedData['email'],
                    'name'           => $validatedData['name'] ?? null,
                    'address'        => $validatedData['address'],
                    'city'           => $validatedData['city'],
                    'zip'            => $validatedData['zip'],
                    'country'        => $validatedData['country'],
                    'subtotal'       => $subtotal,
                    'discount'       => $discount,
                    'tax'            => $tax,
                    'total'          => $frontendTotal,
                    'payment_method' => 'card',
                    'payment_status' => $paymentStatus,
                    'status'         => 'processing',
                    'transaction_id' => $stripeTransactionId,
                    'coupon_code' => $couponCode,
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'name'       => $item->product->name ?? '',
                        'quantity'   => $item->quantity,
                        'price'      => $item->price,
                    ]);
                }

                if ($coupon) {
                    if (!is_null($coupon->usage_limit) && $coupon->usage_limit > 0) {
                        $coupon->decrement('usage_limit');

                        if ($coupon->usage_limit <= 0) {
                            $coupon->status = 'expired';
                            $coupon->save();
                        }
                    }

                    UserCoupons::create([
                        'user_id'   => Auth::id(),
                        'coupon_id' => $coupon->id,
                        'order_id'  => $order->id,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Order creation failed after successful payment: ' . $e->getMessage());

                // Optionally refund payment here:
                // \Stripe\Refund::create(['charge' => $stripeTransactionId]);

                return response()->json(['error' => 'Order creation failed; your payment has been refunded.'], 500);
            }

            session()->forget('cart');
            Cart::where('user_id', auth()->id())->delete();

            return response()->json(['success' => true, 'redirect_url' => route('checkout.success', ['order' => $order->id])]);
        }

        // 7) Payment method = COD
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => Auth::id(),
                'first_name'     => $validatedData['first_name'],
                'last_name'      => $validatedData['last_name'],
                'email'          => $validatedData['email'],
                'name'           => $validatedData['name'] ?? null,
                'address'        => $validatedData['address'],
                'city'           => $validatedData['city'],
                'zip'            => $validatedData['zip'],
                'country'        => $validatedData['country'],
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total'          => $total,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status'         => 'processing',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'name'       => $item->product->name ?? '',
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('COD Order creation failed: ' . $e->getMessage());
           return response()->json(['error' => 'Error processing your order: ' . $e->getMessage()], 500);
        }

        session()->forget('cart');
        Cart::where('user_id', auth()->id())->delete();
        
        return response()->json(['success' => true, 'redirect_url' => route('checkout.success', ['order_id' => $order->id])]);

    }



}
