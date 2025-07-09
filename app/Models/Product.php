<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'sku',
        'image',
        'gallery',
        'short_description',
        'description',
        'price',
        'compare_price',
        'stock',
        'is_featured',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
