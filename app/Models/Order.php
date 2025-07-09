<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPING = 'shipping';
    public const STATUS_DELIVERING = 'delivering'; 
    public const STATUS_RECEIVED = 'received';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_SHIPPING => 'Giao cho ĐVVC',
            self::STATUS_DELIVERING => 'Đang giao',
            self::STATUS_RECEIVED => 'Đã nhận',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy'
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabels()[$this->status] ?? 'Không xác định';
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'orderer_name',
        'orderer_phone',
        'orderer_email',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_email',
        'order_notes',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'total_price',
        'status',
        'discount_id',
        'payment_method_id',
    ];

    /**
     * Trả lại số lượng sản phẩm vào kho khi hủy đơn hàng
     */
    public function restoreStock()
    {
        foreach ($this->orderDetails as $detail) {
            if ($detail->variant_id) {
                // Trả lại stock cho variant
                $variant = $detail->variant;
                if ($variant) {
                    $variant->increment('stock', $detail->quantity);
                }
            } else {
                // Trả lại stock cho product
                $product = $detail->product;
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }
        }
    }

    /**
     * Kiểm tra xem đơn hàng có thể hủy được không (dành cho user)
     * User chỉ cho phép hủy khi đang ở trạng thái:
     * - Chờ xử lý (pending)
     * - Đã xác nhận (confirmed)
     * 
     * Từ trạng thái "Giao cho ĐVVC" trở đi không thể hủy
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,    // Chờ xử lý
            self::STATUS_CONFIRMED   // Đã xác nhận
        ]);
    }

    /**
     * Kiểm tra xem admin có thể hủy đơn hàng không
     * Admin có thể hủy đơn hàng ở bất kỳ trạng thái nào (trừ đã hủy)
     */
    public function canBeCancelledByAdmin(): bool
    {
        return $this->status !== self::STATUS_CANCELLED;
    }

    /**
     * Hủy đơn hàng và trả lại tồn kho
     */
    public function cancelOrder()
    {
        if (!$this->canBeCancelled()) {
            $statusLabels = self::getStatusLabels();
            $currentStatusLabel = $statusLabels[$this->status] ?? $this->status;
            throw new \Exception("Không thể hủy đơn hàng ở trạng thái '{$currentStatusLabel}'. Chỉ có thể hủy khi đơn hàng đang 'Chờ xử lý' hoặc 'Đã xác nhận'.");
        }

        // Trả lại tồn kho
        $this->restoreStock();
        
        // Cập nhật trạng thái
        $this->update(['status' => self::STATUS_CANCELLED]);
        
        return true;
    }

    /**
     * Admin hủy đơn hàng và trả lại tồn kho
     * Admin có thể hủy đơn hàng ở bất kỳ trạng thái nào
     */
    public function cancelOrderByAdmin()
    {
        if (!$this->canBeCancelledByAdmin()) {
            throw new \Exception('Đơn hàng đã được hủy trước đó.');
        }

        // Trả lại tồn kho
        $this->restoreStock();
        
        // Cập nhật trạng thái
        $this->update(['status' => self::STATUS_CANCELLED]);
        
        return true;
    }
}
