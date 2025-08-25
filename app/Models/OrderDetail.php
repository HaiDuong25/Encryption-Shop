<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;

class OrderDetail extends Model
{
    // Luôn trả về sale_price khi truy cập $orderDetail->price
    public function getPriceAttribute($value)
    {
        if ($this->product && $this->product->sale_price) {
            return $this->product->sale_price;
        }
        return $value;
    }

    // Luôn trả về sale_price * quantity khi truy cập $orderDetail->total_price
    public function getTotalPriceAttribute($value)
    {
        if ($this->product && $this->product->sale_price) {
            return $this->product->sale_price * $this->quantity;
        }
        return $value;
    }

protected $fillable = [
    'order_id',
    'product_id',
    'variant_id',
    'quantity',
    'price',
    'total_price',
    'image',
    'return_status',
    'status',         // thêm
    'cancel_reason',  // thêm
    'cancel_note',    // thêm
];


    // Nếu muốn lấy đường dẫn đầy đủ
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }
   public function cancelAndRefund($reason = null, $note = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancel_reason' => $reason,
            'cancel_note' => $note,
        ]);

        // Hoàn tiền vào ví
        if ($this->order && $this->order->user) {
            $user = $this->order->user;
            $user->addToWallet($this->total_price, "Hoàn tiền do hủy sản phẩm #{$this->id}");
        }
    }
}
