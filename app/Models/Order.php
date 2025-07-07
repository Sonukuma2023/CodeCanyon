<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email',
        'address', 'city', 'zip', 'country',
        'payment_method', 'subtotal', 'discount', 'tax', 'total', 'status', 'payment_status','transaction_id'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupons::class);
    }



}
