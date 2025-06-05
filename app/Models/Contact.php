<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(Account::class, 'user_id')->withDefault([
            'name' => 'Khách vãng lai',
            'email' => '',
        ]);
    }

    // Bỏ các accessor getStatusTextAttribute và getStatusClassAttribute nếu bạn đã thêm trước đó
}
