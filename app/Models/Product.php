<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category_id',
        'regular_license_price',
        'extended_license_price',
        'thumbnail',
        'inline_preview',
        'main_files',
        'preview',
        'live_preview',
        'status',
    ];

    protected $casts = [
        'main_files' => 'array',
        'preview' => 'array',
        'live_preview' => 'array',
    ];
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wishlistedBy()
	{
		return $this->belongsToMany(User::class, 'wishlists', 'product_id', 'user_id')->withTimestamps();;
	}

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
