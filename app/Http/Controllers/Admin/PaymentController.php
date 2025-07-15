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
        
        $payment->status = 'completed';
        $payment->confirmed_at = now();
        
        // Tạo transaction code nếu chưa có (đối với COD)
        if (!$payment->transaction_code) {
            $payment->transaction_code = 'COD-' . $payment->order_id . '-' . time();
        }
        
        $payment->save();

        // Cập nhật trạng thái đơn hàng
        $order = $payment->order;
        if ($order) {
            // Nếu là COD thì chuyển sang hoàn thành, còn lại thì xác nhận
            if ($payment->paymentMethod && strtolower($payment->paymentMethod->payment_type) === 'cod') {
                $order->status = 'completed';
                
                // Tự động tạo hóa đơn cho đơn COD khi confirm
                try {
                    $payment->generateInvoice();
                    Log::info("Invoice generated for COD payment {$payment->id} upon confirmation");
                } catch (\Exception $e) {
                    Log::error("Error generating invoice for COD payment {$payment->id}: " . $e->getMessage());
                }
            } else {
                $order->status = 'confirmed'; // Xác nhận
            }
            $order->save();
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận thanh toán và cập nhật trạng thái đơn hàng!'
            ]);
        }

        return redirect()->back()->with('success', 'Đã xác nhận thanh toán và cập nhật trạng thái đơn hàng!');
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
        
        // Kiểm tra quyền và trạng thái
        if ($payment->status !== 'completed') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể tải hóa đơn sau khi thanh toán được xác nhận'
                ]);
            }
            return redirect()->back()->with('error', 'Chỉ có thể tải hóa đơn sau khi thanh toán được xác nhận');
        }
        
        // Kiểm tra xem file hóa đơn có tồn tại không
        if ($payment->invoice_path && file_exists(storage_path('app/' . $payment->invoice_path))) {
            // File đã tồn tại, tải xuống
            return response()->download(storage_path('app/' . $payment->invoice_path));
        } else {
            // File không tồn tại, tạo mới
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
