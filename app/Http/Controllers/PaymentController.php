<?php

namespace App\Http\Controllers;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
class PaymentController extends Controller
{

      public function index()
    {
        return  'payment page';
    }

    public function payment(Request $request)
    {     
        
        // dd($request);
           $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'final_total' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'paymentMethod' => 'required|string|in:paypal',  // Adjust this if you have multiple payment methods
            ]);

            // If validation passes, store the data in session
            session([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'zip' => $request->zip,
                'country' => $request->country,
                'final_total' => $request->final_total,
                'paymentMethod' => $request->paymentMethod,
                'tax' => $request->tax,
                'applied_coupon_code'=>$request->applied_coupon_code,
            ]);


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
  
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $validated['final_total'],
                    ]
                ]
            ]
        ]);
  
        if (isset($response['id']) & $response['id'] != null) {
  
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return response()->json(['approval_url' => $link['href']]);
                }
            }

  
            return redirect()
                ->route('cancel.payment')
                ->with('error', 'Something went wrong.');
  
        } else {
            return redirect()
                ->route('create.payment')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    
    }


      public function paymentCancel()
    {
        return redirect()
              ->route('paypal')
              ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentSuccess(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty. Please add products before checking out.'], 422);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->input('token'));
        

        if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
            return response()->json(['error' => 'Payment was not completed.'], 400);
        }

        $first_name   = session('first_name');
        $last_name    = session('last_name');
        $email        = session('email');
        $address      = session('address');
        $city         = session('city');
        $zip          = session('zip');
        $country      = session('country');
        $final_total  = session('final_total');
        $tax  = session('tax');
        $applied_coupon_code  = session('applied_coupon_code');

        DB::beginTransaction();

        try {

            $order = Order::create([

                'user_id'        => Auth()->id(),
                'first_name'     => $first_name,
                'last_name'      => $last_name,
                'email'          => $email,
                'address'        => $address,
                'city'           => $city,
                'zip'            => $zip,
                'tax'            => $tax,
                'country'        => $country,
                'subtotal'       => $final_total,
                'payment_method' => 'paypal',
                'payment_status' => $response['status'],
                'status'         => $response['status'],
                'transaction_id' => $response['id'],
                'coupon_code'    => $applied_coupon_code ?? '', 
                'discount' =>  0,
                'total'=>$final_total,
                'product_id' => ''
                 

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

            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

             

            return redirect()->route('checkout.success', ['order' => $order->id]);


        //    return route('checkout.success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());

            return response()->json(['error' => 'Error processing your order.'], 500);
        }
    }

}
















