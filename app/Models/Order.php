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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'orderer_name',
        'orderer_phone',
        'orderer_email',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_email',
        'order_notes',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'total_price',
        'status',
        'discount_id',
        'payment_method_id',
    ];
}
