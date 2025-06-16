<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        // Thêm các trường khác nếu cần
    ];

public function variant()
{
    return $this->belongsTo(ProductVariant::class, 'variant_id');
}



    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
