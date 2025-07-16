<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Relationship: A collection belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A collection can have many products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }
}
