<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupons extends Model
{
    protected $table = 'user_coupons';

    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
    ];

    /**
     * Get the user that used the coupon.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coupon used by the user.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupons::class, 'coupon_id');
    }

    /**
     * Get the order associated with the coupon usage.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
