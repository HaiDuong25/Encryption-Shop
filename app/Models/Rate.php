<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', // Vẫn giữ lại product_id ở đây
        'user_id',
        'score',
        'content',
        'status',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(Account::class, 'user_id'); // Giả sử model người dùng là Account
    // }

    /*
    // TẠM THỜI BÌNH LUẬN HOẶC XÓA MỐI QUAN HỆ NÀY
    // SẼ THÊM LẠI KHI CÓ MODEL Product
    public function product()
    {
        // return $this->belongsTo(Product::class, 'product_id');
    }
    */

    public function replies()
    {
        return $this->hasMany(RateReply::class, 'rate_id');
    }
}
