<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionProduction extends Model
{
    protected $table = 'collection_product';

    protected $fillable = [
        'collection_id',
        'product_id',
    ];

    /**
     * Relationship: Belongs to Collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Relationship: Belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
