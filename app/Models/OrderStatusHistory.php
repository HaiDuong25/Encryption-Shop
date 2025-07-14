<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'old_status',
        'new_status',
        'description',
        'changed_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user() // Nếu bạn lưu `changed_by`
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
