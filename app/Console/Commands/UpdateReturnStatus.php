<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderDetail;
use App\Models\ReturnRequest;

class UpdateReturnStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update-return-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái trả hàng cho các OrderDetail dựa trên ReturnRequest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang cập nhật trạng thái trả hàng...');
        
        // Cập nhật return_status dựa trên ReturnRequest
        $returnRequests = ReturnRequest::with('orderDetail')->get();
        
        foreach ($returnRequests as $return) {
            if ($return->orderDetail) {
                $newStatus = 'none';
                
                switch ($return->status) {
                    case 'pending':
                        $newStatus = 'pending';
                        break;
                    case 'approved':
                        $newStatus = 'approved';
                        break;
                    case 'rejected':
                        $newStatus = 'rejected';
                        break;
                }
                
                $return->orderDetail->return_status = $newStatus;
                $return->orderDetail->save();
                
                $this->line("Cập nhật OrderDetail ID {$return->orderDetail->id} -> {$newStatus}");
            }
        }
        
        // Đảm bảo tất cả OrderDetail không có ReturnRequest đều có return_status = 'none'
        OrderDetail::whereDoesntHave('returnRequest')
            ->where(function($query) {
                $query->whereNull('return_status')
                      ->orWhere('return_status', '');
            })
            ->update(['return_status' => 'none']);
        
        $this->info('Hoàn thành cập nhật trạng thái trả hàng!');
        
        return 0;
    }
}
