<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderReturnStatus;

class MigrateReturnStatusData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:migrate-return-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chuyển đổi dữ liệu trả hàng từ hệ thống cũ sang hệ thống mới';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang chuyển đổi dữ liệu trạng thái trả hàng...');
        
        // Lấy tất cả đơn hàng có sản phẩm được yêu cầu trả hàng
        $orders = Order::whereHas('orderDetails', function($query) {
            $query->whereIn('return_status', ['pending', 'approved', 'rejected']);
        })->get();
        
        foreach ($orders as $order) {
            // Tạo hoặc cập nhật OrderReturnStatus
            $returnStatus = $order->updateReturnStatus();
            
            $this->line("Đơn hàng #{$order->id}: {$returnStatus->overall_status}");
        }
        
        // Đặt lại trạng thái giao hàng về received cho các đơn hàng bị ảnh hưởng
        $affectedOrders = Order::whereIn('status', ['returning', 'approved'])
                              ->where('status', '!=', 'completed')
                              ->get();
        
        foreach ($affectedOrders as $order) {
            // Chỉ chuyển về received nếu đơn hàng đã được giao
            if (in_array($order->status, ['returning', 'approved'])) {
                $order->status = 'received';
                $order->save();
                $this->line("Đơn hàng #{$order->id}: Chuyển về trạng thái 'received'");
            }
        }
        
        $this->info('Hoàn thành chuyển đổi dữ liệu trạng thái trả hàng!');
        
        return 0;
    }
}
