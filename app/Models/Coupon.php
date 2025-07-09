<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount', 'start_date', 'end_date', 'expires_at', 'is_active'
    ];
protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'expires_at' => 'date',
];
}