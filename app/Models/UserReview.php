<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'users_reviews';

    // Allow mass assignment on these fields
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
    ];

    // Relationships

    // Reviewer (user who wrote the review)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Related product (optional)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Related order (optional)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
