<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Category;

class OrderController extends Controller
{
    /**
     * Display a listing of the authenticated user’s orders.
     */
    public function index()
    {
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();

        // 1) Retrieve all orders for the currently authenticated user, newest first
        $orders = Order::where('user_id', Auth::id())
               ->with('items.product')
               ->orderBy('created_at', 'desc')
               ->get();


        // 2) Pass them to the Blade view
        return view('user.orders', compact('orders', 'navbarCategories'));
    }

    public function checkoutSuccess(Order $order)
    {
        $navbarCategories = Category::orderBy('created_at', 'asc')->get();
        return view('user.checkout-success', compact('order', 'navbarCategories'));
    }

}
