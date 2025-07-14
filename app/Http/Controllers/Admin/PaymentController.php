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
        $payment->status = 'confirmed';
        $payment->confirmed_at = now();
        $payment->save();


        // Cập nhật trạng thái đơn hàng
        $order = $payment->order;
        if ($order) {
            // Nếu là COD thì chuyển sang hoàn thành, còn lại thì xác nhận
            if ($payment->paymentMethod && strtolower($payment->paymentMethod->payment_type) === 'cod') {
                $order->status = 'completed';
            } else {
                $order->status = 1; // 1 = Xác nhận
            }
            $order->save();
        }

        return redirect()->back()->with('success', 'Đã xác nhận thanh toán và cập nhật trạng thái đơn hàng!');
    }

    public function invoice($id)
    {
        $payment = Payment::with(['order', 'paymentMethod'])->findOrFail($id);

        // Cho phép cả confirmed và rejected xem hóa đơn
        if (!in_array($payment->status, ['confirmed', 'rejected'])) {
            return redirect()->back()->with('error', 'Chỉ có thể xem hóa đơn sau khi đã xác nhận hoặc bị hủy.');
        }

        return view('admin.payments.invoice', compact('payment'));
    }
    public function reject($id)
{
    $payment = Payment::findOrFail($id);
    if ($payment->status !== 'rejected') {
        $payment->status = 'rejected';
        $payment->rejected_at = now();
        $payment->save();
        return redirect()->route('payments.index')->with('success', 'Đã hủy đơn thành công!');
    }
    return redirect()->route('payments.index')->with('error', 'Đơn này đã bị hủy trước đó.');
}
public function exportPdf($id)
{
    $payment = Payment::with(['order', 'paymentMethod', 'order.orderDetails.product'])->findOrFail($id);
    $pdf = \PDF::loadView('admin.payments.invoice_pdf', compact('payment'));
    $fileName = 'hoa-don-thanh-toan-' . $payment->id . '.pdf';
    return $pdf->download($fileName);
}
}
