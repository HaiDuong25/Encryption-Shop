<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnStatus extends Model
{
    protected $fillable = [
        'order_id',
        'overall_status',
        'notes',
    ];

    // Quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Các trạng thái có thể có
    public static function getStatuses()
    {
        return [
            'none' => 'Chưa có yêu cầu trả hàng',
            'partial' => 'Trả hàng một phần', 
            'full' => 'Trả hàng toàn bộ',
            'completed' => 'Hoàn tất trả hàng',
        ];
    }

    // Lấy text hiển thị của trạng thái
    public function getStatusTextAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->overall_status] ?? $this->overall_status;
    }
}
