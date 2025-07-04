<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        // 1) Prevent empty cart checkout
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Your cart is empty. Please add products before checking out.');
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
        ]);

        // 3) Calculate order totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $discount = 0;
        $tax      = $subtotal * 0.18;
        $total    = $subtotal - $discount + $tax;

        if ($validatedData['payment_method'] === 'card') {
            // 4) Process Stripe payment
            $amountInCents = round($total * 100); // in cents

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
                return back()->with('error', 'Payment failed: ' . $e->getMessage());
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
                    'total'          => $total,
                    'payment_method' => 'card',
                    'payment_status' => $paymentStatus,
                    'status'         => 'processing',
                    'transaction_id' => $stripeTransactionId,
                ]);

                foreach ($cartItems as $id => $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $id,
                        'name'       => $item['name'] ?? 'N/A',
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Order creation failed after successful payment: ' . $e->getMessage());

                // Optionally refund payment here:
                // \Stripe\Refund::create(['charge' => $stripeTransactionId]);

                return back()->with('error', 'Order creation failed; your payment has been refunded.');
            }

            // 6) Clear cart and redirect with order ID in URL
            session()->forget('cart');

            return redirect()
                ->route('checkout.success', ['order' => $order->id]);
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

            foreach ($cartItems as $id => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'name'       => $item['name'] ?? 'N/A',
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('COD Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Error processing your order: ' . $e->getMessage());
        }

        session()->forget('cart');
        
        return redirect()->route('checkout.success', ['order_id' => $order->id]);

    }



}
