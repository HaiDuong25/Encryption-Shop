<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate_id','user_id','reason','note','status'
    ];

    public function rate(){ return $this->belongsTo(Rate::class); }
    public function user(){ return $this->belongsTo(User::class); }

    public function getReasonTextAttribute(): string
    {
        $map = [
            'inappropriate' => 'Nội dung không phù hợp',
            'adult' => '18+/Nhạy cảm',
            'false_info' => 'Sai sự thật / Gây hiểu lầm',
            'spam' => 'Spam / Quảng cáo',
            'abuse' => 'Lăng mạ / Thù ghét',
            'other' => 'Khác'
        ];
        return $map[$this->reason] ?? $this->reason;
    }
}