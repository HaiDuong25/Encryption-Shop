<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     * Xử lý logic khi trạng thái đơn hàng thay đổi
     */
    public function updating(Order $order)
    {
        // Kiểm tra xem trạng thái có thay đổi không
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
            
            $this->handleStatusChange($order, $oldStatus, $newStatus);
        }
    }

    /**
     * Xử lý logic khi trạng thái đơn hàng thay đổi
     */
    private function handleStatusChange(Order $order, $oldStatus, $newStatus)
    {
        try {
            // Khi đơn hàng chuyển sang "completed"
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $payment = $order->payments()->first();
                
                if ($payment && $payment->status !== 'completed') {
                    $paymentMethod = $order->paymentMethod;
                    
                    // Nếu là COD: Tự động confirm payment và tạo invoice
                    if ($paymentMethod && strtolower($paymentMethod->payment_type) === 'cod') {
                        $payment->update([
                            'status' => 'completed',
                            'confirmed_at' => now(),
                            'transaction_code' => $payment->transaction_code ?: 'COD-' . $order->id . '-' . time()
                        ]);
                        
                        // Tạo hóa đơn tự động cho COD
                        $payment->generateInvoice();
                        
                        Log::info("Order {$order->id} completed: COD payment auto-confirmed and invoice generated");
                    } else {
                        // Các phương thức khác: Chỉ cập nhật trạng thái
                        $payment->update(['status' => 'completed']);
                        
                        Log::info("Order {$order->id} completed: Payment status updated");
                    }
                }
            }
            
            // Khi đơn hàng chuyển từ "completed" về trạng thái khác
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                $payment = $order->payments()->first();
                
                if ($payment && $payment->status === 'completed') {
                    // Revert payment status (không xóa invoice đã tạo)
                    $payment->update(['status' => 'pending']);
                    
                    Log::info("Order {$order->id} status reverted: Payment status reset to pending");
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Error in OrderObserver for order {$order->id}: " . $e->getMessage());
        }
    }
}