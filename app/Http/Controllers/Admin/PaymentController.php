<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order', 'paymentMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($q2) use ($search) {
                    $q2->where('recipient_name', 'like', "%$search%")
                        ->orWhere('id', $search);
                })
                ->orWhere('id', $search);
            });
        }

        $payments = $query->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function confirm($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'completed') {
            return redirect()->back()->with('info', 'Thanh toán đã được xác nhận trước đó');
        }

        // Xác định flow dựa trên loại thanh toán
        $isCOD = $payment->paymentMethod && strtolower($payment->paymentMethod->payment_type) === 'cod';
        
        if ($isCOD) {
            // Flow COD: pending → confirmed
            $payment->status = 'confirmed';
            $payment->confirmed_at = now();
            
            // Cập nhật trạng thái đơn hàng thành confirmed
            $order = $payment->order;
            if ($order) {
                $order->status = 'confirmed';
                $order->save();
            }
            
            $message = 'Đã xác nhận đơn hàng COD!';
        } else {
            // Flow Online: pending → completed (giữ nguyên)
            $payment->status = 'completed';
            $payment->confirmed_at = now();

            // Tạo transaction code nếu chưa có
            if (!$payment->transaction_code) {
                $payment->transaction_code = 'MANUAL-' . $payment->order_id . '-' . time();
            }

            // Cập nhật trạng thái đơn hàng
            $order = $payment->order;
            if ($order) {
                $order->status = 'confirmed';
                $order->save();
            }

            // Tự động tạo hóa đơn cho đơn online
            try {
                $payment->generateInvoice();
                Log::info("Invoice generated for online payment {$payment->id} upon confirmation");
            } catch (\Exception $e) {
                Log::error("Error generating invoice for online payment {$payment->id}: " . $e->getMessage());
            }
            
            $message = 'Đã xác nhận thanh toán và cập nhật trạng thái đơn hàng!';
        }

        $payment->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function complete($id)
    {
        $payment = Payment::findOrFail($id);

        // Chỉ áp dụng cho COD và trạng thái confirmed
        if (!$payment->paymentMethod || strtolower($payment->paymentMethod->payment_type) !== 'cod') {
            return redirect()->back()->with('error', 'Chức năng này chỉ áp dụng cho đơn hàng COD');
        }

        if ($payment->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Đơn hàng COD phải được xác nhận trước khi hoàn thành');
        }

        // COD: confirmed → completed
        $payment->status = 'completed';
        $payment->confirmed_at = now(); // Cập nhật thời gian hoàn thành

        // Tạo transaction code cho COD
        if (!$payment->transaction_code) {
            $payment->transaction_code = 'COD-' . $payment->order_id . '-' . time();
        }

        $payment->save();

        // Cập nhật trạng thái đơn hàng thành completed
        $order = $payment->order;
        if ($order) {
            $order->status = 'completed';
            $order->save();
        }

        // Tự động tạo hóa đơn cho đơn COD khi hoàn thành
        try {
            $payment->generateInvoice();
            Log::info("Invoice generated for COD payment {$payment->id} upon completion");
        } catch (\Exception $e) {
            Log::error("Error generating invoice for COD payment {$payment->id}: " . $e->getMessage());
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã hoàn thành đơn hàng COD!'
            ]);
        }

        return redirect()->back()->with('success', 'Đã hoàn thành đơn hàng COD!');
    }

    public function invoice($id)
    {
        $payment = Payment::with([
            'order',
            'paymentMethod',
            'order.orderDetails.product.category',
            'order.orderDetails.variant.attributeValues.attribute'
        ])->findOrFail($id);

        // Cho phép cả completed và rejected xem hóa đơn
        if (!in_array($payment->status, ['completed', 'rejected'])) {
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
    $payment = Payment::with([
        'order',
        'paymentMethod',
        'order.orderDetails.product.category',
        'order.orderDetails.variant.attributeValues.attribute'
    ])->findOrFail($id);
    $pdf = Pdf::loadView('admin.payments.invoice_pdf', compact('payment'));
    $fileName = 'hoa-don-thanh-toan-' . $payment->id . '.pdf';
    return $pdf->stream($fileName);
}

/**
     * Tải hóa đơn đã được tạo tự động
     */
  public function downloadInvoice($id)
{
    $payment = Payment::findOrFail($id);

    // ✅ Cho phép tải khi status là completed hoặc rejected
    if (!in_array($payment->status, ['completed', 'rejected'])) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể tải hóa đơn sau khi thanh toán được xác nhận hoặc bị hủy'
            ]);
        }
        return redirect()->back()->with('error', 'Chỉ có thể tải hóa đơn sau khi thanh toán được xác nhận hoặc bị hủy');
    }

    if ($payment->invoice_path && file_exists(storage_path('app/' . $payment->invoice_path))) {
        return response()->download(storage_path('app/' . $payment->invoice_path));
    } else {
        $filePath = $payment->generateInvoice();

        if ($filePath && file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tạo hóa đơn PDF. Vui lòng thử lại.'
                ]);
            }
            return redirect()->back()->with('error', 'Không thể tạo hóa đơn PDF. Vui lòng thử lại.');
        }
    }
}

}
