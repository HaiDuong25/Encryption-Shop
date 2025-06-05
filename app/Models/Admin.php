<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Nếu bạn muốn admin có thể đăng nhập bằng Laravel's built-in auth,
// bạn có thể extends Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable; // Ví dụ
use Illuminate\Notifications\Notifiable; // Nếu cần notification

class Admin extends Authenticatable // Hoặc extends Model nếu không cần auth phức tạp
{
    use HasFactory, Notifiable; // Thêm Notifiable nếu cần

    protected $table = 'admins'; // Khai báo rõ tên bảng nếu cần thiết

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token', // Nếu có
    ];

    public function rateReplies() {
        return $this->hasMany(RateReply::class, 'admin_id');
    }

}
