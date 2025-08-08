@forelse($recentOrders as $order)
<tr>
    <td>
        <div class="best-product-box">
            <div class="product-name">
                <h5>{{ $order->user->name ?? 'Khách' }}</h5>
                <h6>#{{ $order->id }}</h6>
            </div>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Ngày đặt</h6>
            <h5>{{ $order->created_at->format('d/m/Y') }}</h5>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Giá trị</h6>
            <h5>{{ format_vnd($order->total_price) }} đ</h5>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Trạng thái</h6>
            @php
                $statusLabels = [
                    'pending' => 'Chờ xử lý',
                    'approved' => 'Đã duyệt',
                    'confirmed' => 'Đã xác nhận', 
                    'shipping' => 'Giao cho ĐVVC',
                    'delivering' => 'Đang giao',
                    'received' => 'Đã nhận',
                    'completed' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy',
                    'canceled' => 'Đã hủy'
                ];
                $statusLabel = $statusLabels[$order->status] ?? 'Không xác định';
                $statusColor = match($order->status) {
                    'pending' => 'warning',
                    'approved' => 'info',
                    'confirmed' => 'info',
                    'shipping' => 'primary', 
                    'delivering' => 'primary',
                    'received' => 'success',
                    'completed' => 'success',
                    'cancelled', 'canceled' => 'danger',
                    default => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
        </div>
    </td>
    <td>
        <div class="product-detail-box">
            <h6>Thanh toán</h6>
            @php
            $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
            @endphp
            @if ($isPaid)
            <span class="badge bg-success status-badge">Đã thanh toán</span>
            @else
            <span class="badge bg-warning text-dark status-badge">Chưa thanh toán</span>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">Chưa có đơn hàng.</td>
</tr>
@endforelse
