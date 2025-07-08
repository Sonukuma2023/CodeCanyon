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

    //     $cart = session()->get('cart', []);
    //     $cartItems = collect($cart); // Convert to collection for easy manipulation
        
    //     $subtotal = $cartItems->sum(function ($item) {
    //         return $item['price'] * $item['quantity'];
    //     });

    //     $navbarCategories = Category::orderBy('created_at', 'asc')->get();

    //     return view('user.cart', [
    //         'cartItems' => $cartItems,
    //         'subtotal' => $subtotal,
    //         'discount' => 0,
    //         'tax' => $subtotal * 0.1,
    //         'total' => $subtotal * 1.1,
    //         'totalItems' => $cartItems->sum('quantity')
    //     ],
    //     compact('navbarCategories')
    // );

        $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;
        $totalItems = $cartItems->sum('quantity');

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        return view('user.cart', compact('cartItems','subtotal','discount','tax','total','totalItems','navbarCategories'));
    
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
         $item = Cart::find($id);

        if ($item && $item->user_id == auth()->id()) {
            $item->delete();

            $cartCount = Cart::where('user_id', auth()->id())->count();

            return response()->json(['success' => true, 'message' => 'Item removed from cart', 'cartCount' => $cartCount, 'summary' => $this->getCartSummary()]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found or unauthorized'], 404);
    }

    public function increase($id)
    {
        $item = Cart::find($id);

        if ($item && $item->user_id == auth()->id()) {
            $item->quantity += 1;
            $item->save();

            return response()->json([
                'success' => true,
                'quantity' => $item->quantity,
                'summary' => $this->getCartSummary()
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    
    public function decrease($id)
    {
        $item = Cart::find($id);

        if ($item && $item->user_id == auth()->id()) {
            $item->quantity -= 1;
            if ($item->quantity <= 0) {
                $item->delete();
            } else {
                $item->save();
            }

            return response()->json([
                'success' => true,
                'quantity' => $item->quantity ?? 0,
                'cartCount' => Cart::where('user_id', auth()->id())->count(),
                'summary' => $this->getCartSummary()
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    private function getCartSummary()
    {
        $userId = auth()->id();
        $cartItems = Cart::where('user_id', $userId)->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->price * $item->quantity);
        $discount = 0;
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal - $discount + $tax;

        return [
            'totalItems' => $cartItems->count(),
            'subtotal' => number_format($subtotal, 2),
            'discount' => number_format($discount, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2)
        ];
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
        $userId = auth()->id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $tax = round($subtotal * 0.1, 2);
        $total = $subtotal - $discount + $tax;
        $totalItems = $cartItems->sum('quantity');

        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        return view('user.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'totalItems' => $totalItems,
            'navbarCategories' => $navbarCategories
        ]);

    }



    public function somePage() {
        $cartCount = Cart::count();         
        return view('user.cart', compact('cartCount'));
    }

    public function savecart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric',
        ]);

        $userId = Auth::id();
        $productId = $id;
        $quantityToAdd = $request->input('quantity', 1);

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $price = $request->input('price', $product->regular_license_price);
        $cleanPrice = floatval(str_replace(',', '', $price));

        $existingCartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        $currentQty = $existingCartItem?->quantity ?? 0;
        $totalQty = $currentQty + $quantityToAdd;

        if (isset($product->product_quantity) && $totalQty > $product->product_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Requested quantity exceeds available stock'
            ], 200);
        }

        if ($existingCartItem) {
            $existingCartItem->quantity = $totalQty;
            $existingCartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantityToAdd,
                'price' => $cleanPrice,
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'cartCount' => $cartCount
        ]);
    }


    public function userCartCount() 
	{
		$user = auth()->user();
        $cartCount = 0;

        if ($user) {
            $cartCount = Cart::where('user_id', $user->id)->count();
        }

		return response()->json(['cartCount' => $cartCount]);
	}


}

