<?php

namespace App\Observers;

use App\Models\OrderDetail;

class OrderDetailObserver
{
    /**
     * Handle the OrderDetail "created" event.
     */
    public function created(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "updated" event.
     */
    public function updated(OrderDetail $orderDetail): void
    {
        // Chỉ xử lý khi return_status thay đổi
        if ($orderDetail->isDirty('return_status')) {
            $this->updateOrderReturnStatus($orderDetail);
        }
    }

    /**
     * Handle the OrderDetail "deleted" event.
     */
    public function deleted(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "restored" event.
     */
    public function restored(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "force deleted" event.
     */
    public function forceDeleted(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Cập nhật trạng thái trả hàng của đơn hàng (không ảnh hưởng đến trạng thái giao hàng)
     */
    private function updateOrderReturnStatus(OrderDetail $orderDetail): void
    {
        $order = $orderDetail->order;
        if (!$order) return;

        // Chỉ cập nhật trạng thái trả hàng, không động vào trạng thái giao hàng
        $order->updateReturnStatus();
    }
}
