<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_method_id', 'amount', 'status', 'transaction_code', 'confirmed_at'];

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
    }    /**
     * Tự động tạo hóa đơn PDF cho payment đã được xác nhận
     */
    public function generateInvoice()
    {
        try {
            if ($this->status !== 'completed') {
                Log::warning("Cannot generate invoice for payment {$this->id} - status is not completed");
                return false;
            }

            // Load relationships cần thiết cho invoice
            $this->load(['order.orderDetails.product', 'order.orderDetails.variant', 'paymentMethod', 'order.user']);

            // Tạo PDF từ view
            $pdf = Pdf::loadView('admin.payments.invoice', ['payment' => $this]);

            // Tạo thư mục lưu trữ nếu chưa có
            $invoiceDir = storage_path('app/invoices');
            if (!file_exists($invoiceDir)) {
                mkdir($invoiceDir, 0755, true);
            }

            // Tên file invoice
            $filename = "invoice_payment_{$this->id}_order_{$this->order_id}_" . date('Y-m-d_H-i-s') . ".pdf";
            $filepath = $invoiceDir . '/' . $filename;

            // Lưu PDF vào storage
            $pdf->save($filepath);

            // Cập nhật đường dẫn invoice vào database (nếu có column)
            if (Schema::hasColumn('payments', 'invoice_path')) {
                $this->update(['invoice_path' => 'invoices/' . $filename]);
            }

            Log::info("Invoice generated successfully for payment {$this->id}: {$filename}");

            return $filepath;

        } catch (\Exception $e) {
            Log::error("Error generating invoice for payment {$this->id}: " . $e->getMessage());
            return false;
        }
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
public function refund($id)
{
    $payment = Payment::findOrFail($id);

    if ($payment->status !== 'completed') {
        return redirect()->route('payments.index')->with('error', 'Chỉ có thể hoàn tiền đơn đã thanh toán.');
    }

    $payment->status = 'refunded';
    $payment->refunded_at = now();
    $payment->save();

    // Cập nhật trạng thái đơn hàng nếu cần
    $order = $payment->order;
    if ($order) {
        $order->status = 'refunded';
        $order->save();
    }

    return redirect()->route('payments.index')->with('success', 'Hoàn tiền thành công!');
}

}
