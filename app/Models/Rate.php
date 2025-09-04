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
        'status', // Giả sử status là TINYINT: 0 = Chờ duyệt, 1 = Hiện, 2 = Ẩn
    'likes_count',
    'dislikes_count',
    'reports_count',
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
                return 'Chờ duyệt';
            case 1:
                return 'Hiện';
            case 2:
                return 'Ẩn';
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
            case 0: // Chờ duyệt
                return 'bg-warning text-dark';
            case 1: // Hiện
                return 'bg-success';
            case 2: // Ẩn
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

    public function reports()
    {
        return $this->hasMany(RateReport::class);
    }

    // Convenience methods
    public function addLike(): void { $this->increment('likes_count'); }
    public function addDislike(): void { $this->increment('dislikes_count'); }
    public function addReport(): void { $this->increment('reports_count'); }

    // public function product() { ... } // Sẽ thêm sau
}
