<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Mối quan hệ ngược lại nếu cần
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'discount_id');
    }
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'total_price',
        'status',
        'discount_id',
        'payment_method_id',
    ];
}
