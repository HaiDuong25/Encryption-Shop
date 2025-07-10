@extends('client.layout.main')

@section('title', 'Chi tiết đơn hàng')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-5">

    {{-- Trạng thái đơn hàng --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-2">Đơn hàng {{ $order->id }}</h4>

        @php
            $statuses = [
                0 => 'Chờ xử lý',
                1 => 'Đã xác nhận',
                2 => 'Đã giao cho ĐVVC',
                3 => 'Đang giao',
                4 => 'Đã nhận',
                5 => 'Hoàn thành',
            ];
            
            // Chuyển đổi trạng thái sang chuẩn để xử lý
            $statusValue = $order->status;
            if (is_numeric($statusValue)) {
                $statusMap = [
                    '0' => 'pending',
                    '1' => 'confirmed',
                    '2' => 'shipping',
                    '3' => 'delivering',
                    '4' => 'received',
                    '5' => 'completed',
                    '6' => 'cancelled',
                ];
                $statusValue = $statusMap[(string)$statusValue] ?? 'pending';
            }
            
            $orderStatus = (int) $order->status;
            $isCancelled = $statusValue === 'cancelled' || $orderStatus === 6;
        @endphp

        @if ($isCancelled)
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-ban me-2"></i>
                <div>
                    <strong>Đơn hàng đã bị hủy</strong>
                    @if($order->cancel_reason)
                        <br><small>Lý do: {{ $order->cancel_reason }}</small>
                    @endif
                    @if($order->cancel_note)
                        <br><small>Ghi chú: {{ $order->cancel_note }}</small>
                    @endif
                </div>
            </div>
        @else
            <div class="progress" style="height: 10px; background: #eee;">
                @foreach ($statuses as $index => $status)
                    <div class="progress-bar {{ $index <= $orderStatus ? 'bg-success' : 'bg-secondary' }}"
                         role="progressbar"
                         style="width: {{ 100 / count($statuses) }}%">
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between mt-2 small text-muted">
                @foreach ($statuses as $status)
                    <div>{{ $status }}</div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Địa chỉ nhận hàng --}}
    <div class="mb-4 p-3 bg-light rounded shadow-sm">
        <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ nhận hàng</h5>
        <p class="mb-1"><strong>{{ $order->orderer_name }}</strong> | {{ $order->orderer_phone }}</p>
        <p class="mb-0">{{ $order->recipient_address }}</p>
    </div>

    {{-- Sản phẩm --}}
    <div class="mb-4 p-3 bg-white rounded shadow-sm">
        <h5 class="mb-3"><i class="fas fa-box me-2"></i>Sản phẩm</h5>
        @foreach ($order->orderDetails as $item)
            <div class="d-flex border-bottom py-3">
                @php
                    $image = $item->variant->product->image ?? null;
                    $isExternal = Str::startsWith($image, ['http://', 'https://']);
                    $imageUrl = $image
                        ? ($isExternal ? $image : asset('storage/' . $image))
                        : 'https://via.placeholder.com/80?text=No+Image';
                @endphp

                <img src="{{ $imageUrl }}" width="80" height="80" class="me-3 rounded" alt="Ảnh sản phẩm">

                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $item->variant->product->name ?? 'Sản phẩm đã xóa' }}</h6>
                    <div class="text-muted small">Phân loại: {{ $item->variant->name ?? 'Mặc định' }}</div>
                    <div class="text-muted small">Giá: {{ number_format($item->price) }}₫ x {{ $item->quantity }}</div>
                </div>
                <div class="text-end fw-bold">
                    {{ number_format($item->total_price) }}₫
                </div>
            </div>
        @endforeach
    </div>

    {{-- Thông tin thanh toán --}}
    <div class="p-3 bg-white rounded shadow-sm">
        <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán</h5>
        <p class="mb-1">Tạm tính: {{ number_format($order->total_price) }}₫</p>
        @if (session('coupon'))
            <p class="mb-1">Mã giảm giá: <span class="text-success">{{ session('coupon.code') }} ({{ session('coupon.discount') }}%)</span></p>
        @endif
        <p class="mb-1">Phương thức: {{ $order->paymentMethod->payment_type ?? 'Chưa chọn' }}</p>
        <p class="mb-1">Vận chuyển: {{ $order->shipping_method ?? 'Miễn phí' }}</p>
        <h5 class="text-danger mt-2">Tổng tiền: {{ number_format($order->total_price) }}₫</h5>
    </div>

    {{-- Hủy đơn hàng nếu đang ở trạng thái pending hoặc confirmed và chưa bị hủy --}}
    @if (!$isCancelled && in_array($orderStatus, [0, 1]))
        <button class="btn btn-outline-danger mt-3" onclick="cancelOrder({{ $order->id }})">
            Hủy đơn hàng
        </button>
    @endif

    {{-- Quay lại --}}
    <div class="mt-4">
        <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
            ← Quay lại đơn hàng
        </a>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Số lượng sản phẩm sẽ được trả lại kho.')) {
        return;
    }

    // Disable button to prevent double click
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';

    fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cancel_reason: 'Khách hàng hủy đơn',
                note: null
            })
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
@endsection
