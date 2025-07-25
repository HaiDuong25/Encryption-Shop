<?php

namespace App\Models;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
  protected $fillable = [
    'user_id',
    'order_id',
    'order_detail_id',
    'reason',
    'description',
    'image',
    'status',
];


    public function user()       { return $this->belongsTo(User::class); }
    public function order()      { return $this->belongsTo(Order::class); }
    public function orderDetail(){ return $this->belongsTo(OrderDetail::class); }
}
