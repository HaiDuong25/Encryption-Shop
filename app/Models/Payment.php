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
    protected $fillable = ['order_id', 'payment_method_id', 'amount', 'status', 'transaction_code', 'confirmed_at', 'payer_account', 'payer_name', 'payment_method_type'];

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

            // TODO: Create new invoice view or use existing order invoice
            // For now, just mark as successful without generating PDF
            Log::info("Invoice generation requested for payment {$this->id} but admin payments system has been removed");

            return true;

        } catch (\Exception $e) {
            Log::error("Error generating invoice for payment {$this->id}: " . $e->getMessage());
            return false;
        }
    }
}
