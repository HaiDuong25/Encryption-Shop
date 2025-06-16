<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateReply extends Model
{
    use HasFactory;
    protected $fillable = ['rate_id', 'user_id', 'reply_content'];
    public function rate() {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'user_id');
    }


}
