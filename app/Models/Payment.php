<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_method_id', 'status', 'transaction_code', 'confirmed_at'];

    // ✅ Khai báo để Laravel tự convert thành Carbon instance
    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function invoice($id)
{
    $payment = \App\Models\Payment::with(['order', 'paymentMethod'])->findOrFail($id);

    if ($payment->status !== 'confirmed') {
        return redirect()->back()->with('error', 'Chỉ có thể xuất hóa đơn sau khi đã xác nhận');
    }

    $pdf = Pdf::loadView('admin.payments.invoice', compact('payment'));

    return $pdf->download('hoa-don-thanh-toan-' . $payment->id . '.pdf');
}
}
