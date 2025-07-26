<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock',
        'image',
        'gallery'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($variant) {
            // If price is null or 0, use product price as fallback
            if (empty($variant->price) && $variant->product) {
                $variant->price = $variant->product->price;
            }
            
            // If sale_price is null and product has sale_price, use it
            if (empty($variant->sale_price) && $variant->product && $variant->product->sale_price) {
                $variant->sale_price = $variant->product->sale_price;
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values');
    }
    public function getDisplayPriceAttribute()
    {
        return $this->price ?? $this->product->price;
    }
}
