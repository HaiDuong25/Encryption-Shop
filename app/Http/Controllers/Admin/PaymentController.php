<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order', 'paymentMethod'])->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function confirm($id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Thanh toán đã được xác nhận hoặc không thể xác nhận');
        }

        $payment->status = 'confirmed';
        $payment->confirmed_at = now();
        $payment->save();

        return redirect()->route('admin.payments.invoice', $payment->id);
    }

    public function invoice($id)
    {
        $payment = Payment::with(['order', 'paymentMethod'])->findOrFail($id);

        if ($payment->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Chỉ có thể xuất hóa đơn sau khi đã xác nhận.');
        }

        // Hiển thị view hóa đơn trên web thay vì xuất PDF
        return view('admin.payments.invoice', compact('payment'));
    }
    public function reject($id)
{
    $payment = Payment::findOrFail($id);
    if ($payment->status === 'confirmed') {
        $payment->status = 'rejected';
        $payment->rejected_at = now();
        $payment->save();
        return redirect()->route('payments.index')->with('success', 'Đã hủy đơn thành công!');
    }
    return redirect()->route('payments.index')->with('error', 'Chỉ có thể hủy đơn đã xác nhận.');
}
}
