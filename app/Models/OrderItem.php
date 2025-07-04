<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product; 

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'quantity',
        'price'
    ];

    /**
     * Each OrderItem belongs to a Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
