<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'position',
        'is_active',
    ];

    // Nếu muốn đảm bảo luôn là kiểu boolean khi lấy từ DB
    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];
}