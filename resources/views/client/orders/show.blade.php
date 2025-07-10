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
            $orderStatus = (int) $order->status;
            $isCancelled = $orderStatus === 6;
        @endphp

        @if ($isCancelled)
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-ban me-2"></i>
                <strong>Đơn hàng đã bị huỷ</strong>
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
<<<<<<< HEAD
        <p class="mb-1">Phương thức: {{ $order->paymentMethod->payment_type ?? 'Chưa chọn' }}</p>
=======
        <p class="mb-1">Phương thức: {{ $order->paymentMethod->name ?? 'Chưa chọn' }}</p>
>>>>>>> 66e22db (tạo lịch sử mua hàng)
        <p class="mb-1">Vận chuyển: {{ $order->shipping_method ?? 'Miễn phí' }}</p>
        <h5 class="text-danger mt-2">Tổng tiền: {{ number_format($order->total_price) }}₫</h5>
    </div>

    {{-- Hủy đơn hàng nếu đang chờ xác nhận --}}
    @if ($orderStatus === 0)
        <button class="btn btn-outline-danger mt-3" data-bs-toggle="modal" data-bs-target="#cancelModal">
            Hủy đơn hàng
        </button>

        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
<form action="{{ route('client.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelModalLabel">Lý do hủy đơn hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <label for="reason" class="form-label">Vui lòng chọn lý do:</label>
                            <select class="form-select" name="cancel_reason" id="reason" required>
                                <option value="">-- Chọn lý do --</option>
                                <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                                <option value="Muốn thay đổi địa chỉ">Muốn thay đổi địa chỉ</option>
                                <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                                <option value="Lý do khác">Lý do khác</option>
                            </select>
                            <div class="mt-3">
                                <label for="note" class="form-label">Ghi chú thêm (không bắt buộc):</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Ghi chú nếu có..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Quay lại --}}
    <div class="mt-4">
        <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
            ← Quay lại đơn hàng
        </a>
    </div>
</div>
@endsection
