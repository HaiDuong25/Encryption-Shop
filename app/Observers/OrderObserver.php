<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "deleting" event.
     * Trả lại tồn kho khi xóa đơn hàng
     */
    public function deleting(Order $order)
    {
        // Trả lại tồn kho trước khi xóa đơn hàng
        $order->restoreStock();
    }

    /**
     * Handle the Order "updating" event.
     * Trả lại tồn kho khi chuyển đơn hàng sang trạng thái đã hủy
     */
    public function updating(Order $order)
    {
        // Kiểm tra nếu trạng thái thay đổi sang 'cancelled'
        if ($order->isDirty('status') && $order->status === Order::STATUS_CANCELLED) {
            // Chỉ trả lại tồn kho nếu trạng thái cũ không phải 'cancelled'
            $originalStatus = $order->getOriginal('status');
            if ($originalStatus !== Order::STATUS_CANCELLED) {
                // Không gọi restoreStock() ở đây vì đã được gọi trong cancelOrder()
                // Observer chỉ để log hoặc thực hiện các tác vụ khác
            }
        }
    }
}
