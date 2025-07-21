<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_detail_id',
        'score',
        'content',
        'status', // Giả sử status là TINYINT: 0 = pending, 1 = approved, 2 = rejected
    ];

    // --- THÊM CÁC PHƯƠNG THỨC ACCESSOR DƯỚI ĐÂY ---

    /**
     * Accessor để lấy tên trạng thái dưới dạng chuỗi.
     * Sẽ được gọi qua $rate->status_text
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->attributes['status']) { // Truy cập giá trị gốc của status
            case 0:
                return 'pending';
            case 1:
                return 'approved';
            case 2:
                return 'rejected';
            default:
                return 'unknown';
        }
    }

    /**
     * Accessor để lấy class CSS cho badge trạng thái.
     * Sẽ được gọi qua $rate->status_class
     */
    public function getStatusClassAttribute(): string
    {
        switch ($this->attributes['status']) { // Truy cập giá trị gốc của status
            case 0: // pending
                return 'bg-warning text-dark';
            case 1: // approved
                return 'bg-success';
            case 2: // rejected
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    // --- KẾT THÚC PHẦN THÊM ACCESSOR ---


    // Các relationships của bạn
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(RateReply::class, 'rate_id')->orderBy('created_at', 'desc');
    }
    public function orderDetail()
{
    return $this->belongsTo(OrderDetail::class);
}

    // public function product() { ... } // Sẽ thêm sau
}
