<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Kế thừa từ Authenticatable cho việc đăng nhập
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Bỏ comment nếu bạn dùng Sanctum cho API

class Account extends Authenticatable
{
    use HasFactory, Notifiable; // Thêm HasApiTokens nếu cần

    /**
     * Tên bảng liên kết với model.
     * Laravel thường tự suy ra tên bảng là 'accounts' từ tên model 'Account',
     * nhưng khai báo rõ ràng cũng không sao.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * Các thuộc tính có thể được gán hàng loạt.
     * Dựa trên các cột bạn đã cung cấp: id name email role address password status created_at updated_at
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'address',
        'password',
        'status',
    ];

    /**
     * Các thuộc tính nên được ẩn khi trả về dưới dạng array hoặc JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Thêm cột này vào bảng 'accounts' nếu bạn dùng chức năng "remember me"
    ];

    /**
     * Các thuộc tính nên được cast về kiểu dữ liệu gốc.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Thêm cột này nếu bạn có chức năng xác thực email
        'password' => 'hashed', // Laravel 10+ tự động hash password khi set
    ];

    /**
     * Một người dùng (account) có thể có nhiều đánh giá (rates).
     */
    public function rates()
    {
        // 'user_id' là khóa ngoại trong bảng 'rates' trỏ về 'id' của bảng 'accounts'
        return $this->hasMany(Rate::class, 'user_id');
    }

    // Bạn có thể thêm các phương thức hoặc relationships khác ở đây sau này
    // Ví dụ: kiểm tra vai trò admin
    // public function isAdmin()
    // {
    // return $this->role === 'admin'; // Hoặc giá trị role admin của bạn
    // }
}
