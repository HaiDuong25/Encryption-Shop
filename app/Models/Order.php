<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPING = 'shipping';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_COMPLETED = 'completed';

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_SHIPPING => 'Giao cho ĐVVC',
            self::STATUS_DELIVERING => 'Đang giao',
            self::STATUS_RECEIVED => 'Đã nhận',
            self::STATUS_COMPLETED => 'Hoàn thành'
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabels()[$this->status] ?? 'Không xác định';
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentMethod()
{
    return $this->belongsTo(PaymentMethod::class);
}


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    protected $fillable = [
        'user_id',
        'orderer_name',
        'orderer_email',
        'orderer_phone',
        'orderer_address',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'name',
        'phone',
        'address',
        'total_price',
        'subtotal',
        'status',
        'cancel_reason',
        'cancel_note',
        'discount_id',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'coupon_type',
        'payment_method_id',
        'shipping_address_id',
        'notes',
        'payment_status',
        'transaction_id',
        'total',
        'discount',
    ];
}
