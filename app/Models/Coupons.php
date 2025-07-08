<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $table = "coupons";

    protected $fillable = [
        'code',	
        'discount_amount',	
        'discount_percentage',	
        'usage_limit',	
        'minimum_order_amount',	
        'expires_at',	
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Coupon belongs to an order (where it was applied).
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
