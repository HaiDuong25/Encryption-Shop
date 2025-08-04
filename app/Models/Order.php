<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
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
            self::STATUS_APPROVED => 'Đã duyệt',
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
    return $this->belongsTo(PaymentMethod::class);
}


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'discount_id');
    }
public function statusHistories()
{
    return $this->hasMany(OrderStatusHistory::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    protected $fillable = [
        'user_id',
        'orderer_name',
        'orderer_email',
        'orderer_phone',
        'orderer_address',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'name',
        'phone',
        'address',
        'total_price',
        'subtotal',
        'status',
        'cancel_reason',
        'cancel_note',
        'discount_id',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'coupon_type',
        'payment_method_id',
        'shipping_address_id',
        'notes',
        'payment_status',
        'transaction_id',
        'total',
        'discount',
    ];

    // Quan hệ với OrderReturnStatus
    public function returnStatus()
    {
        return $this->hasOne(OrderReturnStatus::class);
    }

    // Lấy hoặc tạo trạng thái trả hàng
    public function getOrCreateReturnStatus()
    {
        if (!$this->returnStatus) {
            $this->returnStatus()->create([
                'overall_status' => 'none'
            ]);
            $this->load('returnStatus');
        }
        return $this->returnStatus;
    }

    // Kiểm tra xem đơn hàng có thể trả hàng không
    public function canReturn()
    {
        return in_array($this->status, ['received', 'completed']);
    }

    // Kiểm tra xem đơn hàng có thể hoàn thành không
    public function canComplete()
    {
        // Phải ở trạng thái 'received' mới có thể hoàn thành
        if ($this->status !== 'received') {
            return false;
        }

        // Kiểm tra trạng thái trả hàng của các sản phẩm
        $orderDetails = $this->orderDetails;
        
        // Nếu tất cả sản phẩm đều được duyệt trả hàng thì không thể hoàn thành đơn
        $approvedReturns = $orderDetails->where('return_status', 'approved')->count();
        $totalItems = $orderDetails->count();
        
        if ($approvedReturns == $totalItems) {
            return false; // Tất cả sản phẩm đều trả hàng -> không thể hoàn thành
        }

        // Nếu có sản phẩm đang chờ duyệt trả hàng thì không thể hoàn thành
        $pendingReturns = $orderDetails->where('return_status', 'pending')->count();
        if ($pendingReturns > 0) {
            return false; // Có sản phẩm đang chờ duyệt -> không thể hoàn thành
        }

        return true;
    }

    // Kiểm tra xem có thể xác nhận hoàn thành trong view index không
    public function canCompleteInIndex()
    {
        // Chỉ được xác nhận ở index khi tất cả sản phẩm đều ở trạng thái 'none' (không trả hàng)
        if ($this->status !== 'received') {
            return false;
        }

        $orderDetails = $this->orderDetails;
        $nonReturnItems = $orderDetails->where('return_status', 'none')->count();
        $rejectedReturnItems = $orderDetails->where('return_status', 'rejected')->count();
        $totalItems = $orderDetails->count();

        // Có thể hoàn thành nếu tất cả sản phẩm đều không có yêu cầu trả hàng HOẶC bị từ chối trả hàng
        return ($nonReturnItems + $rejectedReturnItems) == $totalItems;
    }

    // Cập nhật trạng thái trả hàng dựa trên các sản phẩm
    public function updateReturnStatus()
    {
        $returnStatus = $this->getOrCreateReturnStatus();
        
        $totalItems = $this->orderDetails->count();
        $pendingReturns = $this->orderDetails->where('return_status', 'pending')->count();
        $approvedReturns = $this->orderDetails->where('return_status', 'approved')->count();
        $rejectedReturns = $this->orderDetails->where('return_status', 'rejected')->count();
        
        if ($approvedReturns == $totalItems) {
            $returnStatus->overall_status = 'completed';
        } elseif ($approvedReturns > 0 || $pendingReturns > 0) {
            if (($approvedReturns + $pendingReturns) == $totalItems) {
                $returnStatus->overall_status = 'full';
            } else {
                $returnStatus->overall_status = 'partial';
            }
        } else {
            $returnStatus->overall_status = 'none';
        }
        
        $returnStatus->save();
        return $returnStatus;
    }
}
