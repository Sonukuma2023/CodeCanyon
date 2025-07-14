<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReview;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
     
public function store(Request $request)
{
    $request->validate([
        
        'order_id' => 'required|exists:orders,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'required|string|max:1000',
    ]);
     
      

    $order = Order::findOrFail($request->order_id);
          
        
        $product_id = $request->product_id;
        
        $review = UserReview::where('user_id', auth()->id())
        ->where('order_id', $order->id)
        ->where('product_id', $product_id)
        ->first();

    if ($review) {
        // Update existing review
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->review,
        ]);
        $message = 'Review updated successfully.';
    } else {
        // Create new review
        UserReview::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'product_id' => $product_id,
            'rating' => $request->rating,
            'comment' => $request->review,
        ]);
        $message = 'Review submitted successfully.';
    }

        return response()->json([
            'message' => 'Thank you! Your review has been submitted.',
            'redirect_url' => route('orders')
        ]);
}

public function init(Request $request)
{

    $productId = $request->input('product_id');
    
    $product = Product::find($productId);
     
    $product_id = $product->id;

    $userId =  auth()->id();
    
    $item = OrderItem::where('product_id', $product->id)
        ->whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })

        ->first();

        $order_id =  $item->order_id;
         
        
        $userreviewrecord = UserReview::where('product_id', $product_id )->first();

        $user_view_id = $userreviewrecord->id;
        $user_view_id = $userreviewrecord?->id;
        $rating = $userreviewrecord?->rating;
        $review = $userreviewrecord?->review;

        return response()->json([
            'status' => 'success',
            'message' => 'Ready to show modal.',
            'order_id' => $order_id,
            'product_id' => $product_id,
            'user_view_id' => $user_view_id,
            'rating' => $rating,
            'review' => $review,
        ]);
        
         
    if (!$order_id) {
         
        return response()->json(['status' => 'error', 'message' => 'Product not found.'], 404);
    }


    
}



}
