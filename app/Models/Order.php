<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Mối quan hệ ngược lại nếu cần
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
