<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['payment_type', 'description'];
    public function returnRequests()
{
    return $this->hasMany(ReturnRequest::class);
}

}

