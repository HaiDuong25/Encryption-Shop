<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'start_date',
        'end_date',
        'expires_at',
        'is_active',
        'discount_type',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count'
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Kiểm tra xem coupon có thể sử dụng được hay không
     */
    public function canBeUsed()
    {
        // Kiểm tra trạng thái active
        if (!$this->is_active) {
            return false;
        }

        // Kiểm tra thời gian hết hạn
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Kiểm tra thời gian start_date và end_date
        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        // Kiểm tra giới hạn sử dụng
        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Tăng số lần sử dụng coupon
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
        return $this;
    }

    /**
     * Kiểm tra xem còn bao nhiêu lần sử dụng
     */
    public function remainingUsage()
    {
        if ($this->usage_limit <= 0) {
            return -1; // Không giới hạn
        }

        return max(0, $this->usage_limit - $this->used_count);
    }

    /**
     * Scope để lấy các coupon còn có thể sử dụng
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->whereRaw('(usage_limit = 0 OR used_count < usage_limit)');
    }

    /**
     * Relationship với CouponUse
     */
    public function couponUses()
    {
        return $this->hasMany(CouponUse::class);
    }

    /**
     * Kiểm tra user đã sử dụng coupon này chưa
     */
    public function hasBeenUsedByUser($userId)
    {
        return $this->couponUses()->where('user_id', $userId)->exists();
    }

    /**
     * Scope để lấy coupon khả dụng cho user cụ thể
     */
    public function scopeAvailableForUser($query, $userId)
    {
        return $query->available()
            ->whereDoesntHave('couponUses', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
    }
}