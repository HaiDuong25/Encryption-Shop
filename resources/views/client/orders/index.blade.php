@extends('client.layout.main')

@section('content')
<style>
.status-badge {
    font-size: 0.875rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
    border-radius: 4px;
}
.bg-purple {
    background-color: #8b5cf6 !important;
}
.bg-cyan {
    background-color: #06b6d4 !important;
}
.card {
    border: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}
.card-header {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.25rem;
}
.table {
    margin-bottom: 0;
}
.table th {
    background-color: #f1f5f9;
    color: #1e293b;
    font-weight: 600;
    border: none;
    padding: 0.875rem;
}
.table td {
    padding: 0.875rem;
    border-color: #e2e8f0;
    vertical-align: middle;
}
.btn-outline-primary {
    border-color: #3b82f6;
    color: #3b82f6;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}
.btn-outline-primary:hover {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
.price-info {
    min-width: 150px;
}
.price-info .subtotal {
    font-size: 0.85em;
}
.price-info .discount {
    font-size: 0.85em;
}
.price-info .total {
    font-size: 1.05em;
}
</style>
<div class="container-fluid-lg py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="mb-0">Đơn hàng của tôi</h3>
            <!-- Lưu ý về trạng thái đơn hàng -->
            <p class="text-muted small mt-2">Lưu ý: Đơn hàng chỉ được hủy khi đang trong trạng thái "Chờ xác nhận" hoặc "Đã xác nhận".</p>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Người nhận</th>
                            <th>SĐT</th>
                            <th>Địa chỉ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->recipient_name ?? $order->orderer_name ?? 'N/A' }}</td>
                            <td>{{ $order->recipient_phone ?? $order->orderer_phone ?? 'N/A' }}</td>
                            <td>{{ $order->recipient_address ?? 'N/A' }}</td>
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
                                        <div class="subtotal text-muted small">
                                            Tạm tính: {{ number_format($subtotal, 0, ',', '.') }}đ
                                        </div>
                                        <div class="discount text-danger small">
                                            Giảm giá: -{{ number_format($actualDiscountAmount, 0, ',', '.') }}đ
                                            @if($order->coupon_code)
                                                <span class="badge bg-primary ms-1">{{ $order->coupon_code }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="total fw-bold text-primary">
                                        {{ number_format($total, 0, ',', '.') }}đ
                                    </div>
                                </div>
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
                                    <span class="badge bg-info status-badge">Đã giao cho ĐVVC</span>
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
                                    $canCancel = in_array($statusValue, ['pending', 'confirmed']);
                                @endphp
                                
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye me-1"></i>Xem
                                    </a>
                                    
                                    @if($canCancel)
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="cancelOrder({{ $order->id }})">
                                            <i class="fa-solid fa-times me-1"></i>Hủy
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info text-center mb-0">Bạn chưa có đơn hàng nào.</div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
        return;
    }

    // Disable button to prevent double click
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang hủy...';

    fetch(`/orders/${orderId}/cancel`, {
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
            location.reload(); // Refresh page to show updated status
        } else {
            alert('Lỗi: ' + data.message);
            // Re-enable button on error
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi hủy đơn hàng');
        // Re-enable button on error
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}
</script>
@endpush
