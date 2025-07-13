<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUse extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'discount_amount'
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2'
    ];

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Coupon
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Relationship với Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
