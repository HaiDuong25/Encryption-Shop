<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount', 'discount_type', 'start_date', 'end_date', 'expires_at', 'is_active'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Kiểm tra mã giảm giá có hợp lệ không
     */
    public function isValid()
    {
        $now = Carbon::now();
        
        // Kiểm tra mã có active không
        if (!$this->is_active) {
            return false;
        }
        
        // Kiểm tra ngày bắt đầu
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        
        // Kiểm tra ngày kết thúc
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }
        
        // Kiểm tra expires_at
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }
        
        return true;
    }

    /**
     * Tính toán số tiền giảm giá
     */
    public function calculateDiscount($total)
    {
        if ($this->discount_type === 'percentage') {
            // Giảm giá theo phần trăm
            $discountAmount = $total * ($this->discount / 100);
            // Đảm bảo không vượt quá tổng tiền
            return min($discountAmount, $total);
        } else {
            // Giảm giá theo số tiền cố định
            return min($this->discount, $total);
        }
    }

    /**
     * Lấy text mô tả mã giảm giá
     */
    public function getDiscountText()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount . '%';
        } else {
            return number_format($this->discount) . ' đ';
        }
    }
}