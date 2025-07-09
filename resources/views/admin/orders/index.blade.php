@extends('admin.layouts.main')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<style>
.status-badge {
    font-size: 0.85rem;
    padding: 0.3em 0.6em;
    font-weight: 500;
    border-radius: 4px;
}
.bg-purple {
    background-color: #8b5cf6 !important;
}
.bg-cyan {
    background-color: #06b6d4 !important;
}
.price-info {
    min-width: 120px;
    font-size: 0.95rem;
}
.price-info .subtotal {
    font-size: 0.85em;
}
.price-info .discount {
    font-size: 0.85em;
}
.price-info .total {
    color: #2563eb;
    font-size: 1em;
}
.compact-table th,
.compact-table td {
    padding: 0.4rem 0.3rem;
    vertical-align: middle;
    line-height: 1.3;
    font-size: 0.95rem;
}
.compact-table th {
    font-size: 1rem;
    font-weight: 600;
}
.compact-table {
    margin-bottom: 0;
}
.action-buttons {
    display: flex;
    gap: 2px;
    justify-content: center;
}
.action-buttons li {
    list-style: none;
}
.action-buttons ul {
    margin: 0;
    padding: 0;
    display: flex;
    gap: 2px;
}
</style>
<div class="card card-table">
    <div class="card-body">
        <div class="title-header option-title">
            <h5>Order List</h5>
            <!-- <a href="{{ route('orders.create') }}" class="btn btn-solid">Tạo đơn hàng</a> -->
        </div>
        <div>
            <div class="table-responsive">
                <table class="table all-package order-table theme-table compact-table" id="table_id">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Người nhận</th>
                            <th width="20%">Địa chỉ</th>
                            <th width="10%">Ngày đặt</th>
                            <th width="10%">Thanh toán</th>
                            <th width="12%">Trạng thái</th>
                            <th width="15%">Tổng tiền</th>
                            <th width="13%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                <div class="text-truncate" style="max-width: 170px;" title="{{ $order->recipient_name ?? $order->orderer_name ?? 'N/A' }}">
                                    {{ $order->recipient_name ?? $order->orderer_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 170px;" title="{{ $order->recipient_address ?? 'N/A' }}">
                                    {{ Str::limit($order->recipient_address ?? 'N/A', 30) }}
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                {{ $order->paymentMethod->payment_type ?? 'N/A' }}
                            </td>
                            <td>
                                @php
                                    // Convert numeric status to string for compatibility
                                    $statusValue = $order->status;
                                    if (is_numeric($statusValue)) {
                                        $statusMap = [
                                            '0' => 'pending',
                                            '1' => 'confirmed', 
                                            '2' => 'shipping',
                                            '3' => 'delivering',
                                            '4' => 'received',
                                            '5' => 'completed'
                                        ];
                                        $statusValue = $statusMap[$statusValue] ?? 'pending';
                                    }
                                @endphp
                                
                                @if($statusValue == 'pending')
                                    <span class="badge bg-warning status-badge">Chờ xử lý</span>
                                @elseif($statusValue == 'confirmed')
                                    <span class="badge bg-primary status-badge">Đã xác nhận</span>
                                @elseif($statusValue == 'shipping')
                                    <span class="badge bg-info status-badge">ĐVVC</span>
                                @elseif($statusValue == 'delivering')
                                    <span class="badge bg-purple status-badge">Đang giao</span>
                                @elseif($statusValue == 'received')
                                    <span class="badge bg-cyan status-badge">Đã nhận</span>
                                @elseif($statusValue == 'completed')
                                    <span class="badge bg-success status-badge">Hoàn thành</span>
                                @elseif($statusValue == 'cancelled')
                                    <span class="badge bg-danger status-badge">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary status-badge">{{ $statusValue }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $subtotal = $order->subtotal ?? $order->orderDetails->sum(fn($d) => $d->price * $d->quantity);
                                    
                                    // Tính số tiền giảm thực tế
                                    $actualDiscountAmount = 0;
                                    if ($order->coupon_code && $order->coupon_discount > 0) {
                                        if ($order->coupon_type == 'percentage') {
                                            $actualDiscountAmount = ($subtotal * $order->coupon_discount) / 100;
                                        } else {
                                            $actualDiscountAmount = min($order->coupon_discount, $subtotal);
                                        }
                                    }
                                    
                                    $total = $order->total_price;
                                @endphp
                                
                                <div class="price-info">
                                    @if($actualDiscountAmount > 0)
                                        <div class="subtotal text-muted">
                                            {{ number_format($subtotal, 0, ',', '.') }}đ
                                        </div>
                                        <div class="discount text-danger">
                                            -{{ number_format($actualDiscountAmount, 0, ',', '.') }}đ
                                            @if($order->coupon_code)
                                                <span class="badge bg-primary ms-1" style="font-size: 0.65rem;">{{ $order->coupon_code }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="total fw-bold">
                                        {{ number_format($total, 0, ',', '.') }}đ
                                    </div>
                                </div>
                            </td>
                            <td>
                                <ul class="action-buttons">
                                    <li>
                                        <a href="{{ route('orders.show', $order->id) }}" title="Xem chi tiết">
                                            <i class="ri-eye-line" style="font-size: 1.1rem;"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('orders.edit', $order->id) }}" title="Chỉnh sửa">
                                            <i class="ri-pencil-line" style="font-size: 1.1rem;"></i>
                                        </a>
                                    </li>
                                    @if($order->status !== 'cancelled')
                                    <li>
                                        <button type="button" onclick="cancelOrder({{ $order->id }})" 
                                                style="border:none; background:none; padding:0; color:#ffc107; font-size: 1.1rem;" 
                                                title="Hủy đơn hàng">
                                            <i class="ri-close-circle-line"></i>
                                        </button>
                                    </li>
                                    @endif
                                    <li>
                                        <button type="button" onclick="deleteOrder({{ $order->id }})" 
                                                style="border:none; background:none; padding:0; color:#dc3545; font-size: 1.1rem;" 
                                                title="Xóa đơn hàng">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Số lượng sản phẩm sẽ được trả lại.')) {
        return;
    }

    fetch(`/admin/orders/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi hủy đơn hàng');
    });
}

function deleteOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này không? Hành động này không thể hoàn tác và số lượng sản phẩm sẽ được trả lại nếu đơn hàng chưa bị hủy.')) {
        return;
    }

    fetch(`/admin/orders/${orderId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa đơn hàng');
    });
}
</script>
@endsection
