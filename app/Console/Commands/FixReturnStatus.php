<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class FixReturnStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:fix-return-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sửa lại trạng thái đơn hàng để cho phép trả từng sản phẩm riêng biệt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang sửa lại trạng thái đơn hàng...');
        
        // Tìm các đơn hàng có status returning nhưng vẫn có sản phẩm chưa được yêu cầu trả hàng
        $orders = Order::where('status', 'returning')
                      ->whereHas('orderDetails', function($query) {
                          $query->where('return_status', 'none');
                      })
                      ->get();
        
        foreach ($orders as $order) {
            $totalItems = $order->orderDetails->count();
            $pendingReturns = $order->orderDetails->where('return_status', 'pending')->count();
            $approvedReturns = $order->orderDetails->where('return_status', 'approved')->count();
            $noneReturns = $order->orderDetails->where('return_status', 'none')->count();
            
            // Nếu không phải tất cả sản phẩm đều được yêu cầu trả hàng
            if (($pendingReturns + $approvedReturns) < $totalItems) {
                $order->status = 'received';
                $order->save();
                
                $this->line("Đơn hàng #{$order->id}: {$noneReturns}/{$totalItems} sản phẩm chưa trả -> Chuyển về 'received'");
            }
        }
        
        $this->info('Hoàn thành sửa lại trạng thái đơn hàng!');
        
        return 0;
    }
}
