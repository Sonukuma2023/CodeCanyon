<?php

namespace App\Http\Controllers\cart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
     public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = collect($cart); // Convert to collection for easy manipulation
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        return view('user.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => $subtotal * 0.1,
            'total' => $subtotal * 1.1,
            'totalItems' => $cartItems->sum('quantity')
        ],
        compact('navbarCategories')
    );
    }

    public function add(Product $product)
    {
        // Retrieve cart from session
        $cart = session()->get('cart', []);

        // Add or update item by product ID
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->regular_license_price,
                'quantity' => 1,
                'description' => $product->description,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');

    }




    public function remove($id)
        {
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }

            // Redirect back with success message
            return back()->with('success', 'Product removed from cart!');
        }

    public function increase($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product quantity increased!');
    }

    
    public function decrease($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product quantity decreased!');
    }

    
    public function applyCoupon(Request $request)
    {
        return back()->with('success', 'Coupon applied!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        //dd(session()->get('cart')); // Dump and die to check the cart structure

        $cartItems = collect($cart);
        $totalItems = $cartItems->sum('quantity');
        $subtotal = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0; 
        $tax = 0.18 * ($subtotal - $discount);
        $total = ($subtotal - $discount) + $tax;

        return view('user.cart', compact(
            'cartItems',
            'totalItems',
            'subtotal',
            'discount',
            'tax',
            'total'
        ));
    }

    public function index1()
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0;
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal - $discount + $tax;

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.checkout', [
            'cartItems' => $cart,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'totalItems' => collect($cart)->sum('quantity'),
        ],
        compact('navbarCategories')
    );
    }



    public function somePage() {
        $cartCount = Cart::count();         
        return view('user.cart', compact('cartCount'));
    }

}

