@extends('client.layout.main')

@section('title', 'Lịch sử đơn hàng')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-5">
    {{-- ✅ Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3 class="mb-4 fw-bold">🛒 Lịch sử đơn hàng</h3>

    @forelse ($orders as $order)
        <div class="card border-0 shadow mb-4 rounded-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center rounded-top-4">
                <div>
                    <h6 class="mb-1 fw-bold">Đơn hàng #{{ $order->id }}</h6>

                    @php
                        $statusText = [
                            0 => ['Đã đặt', 'warning text-dark'],
                            1 => ['Xác nhận', 'info text-white'],
                            2 => ['Giao cho ĐVVC', 'primary text-white'],
                            3 => ['Đang giao', 'secondary text-white'],
                            4 => ['Đã nhận', 'success text-white'],
                            5 => ['Hoàn thành', 'dark text-white'],
                            6 => ['Đã huỷ', 'danger text-white'],
                        ];
                        $status = $statusText[$order->status] ?? ['Không rõ', 'secondary'];
                    @endphp

                    <span class="badge bg-{{ explode(' ', $status[1])[0] }} {{ explode(' ', $status[1])[1] }} px-3 py-1 mt-1">
                        {{ $status[0] }}
                    </span>
                </div>
                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
            </div>

            <div class="card-body">
                {{-- ✅ Danh sách sản phẩm --}}
                @foreach ($order->orderDetails as $item)
                    @php
                        $image = $item->variant->product->image ?? null;
                        $isExternal = Str::startsWith($image, ['http://', 'https://']);
                        $imageUrl = $image
                            ? ($isExternal ? $image : asset('storage/' . $image))
                            : 'https://via.placeholder.com/80?text=No+Image';
                    @endphp

                    <div class="d-flex mb-3 border-bottom pb-3 align-items-center">
                        <img src="{{ $imageUrl }}"
                             alt="Ảnh sản phẩm" width="80" height="80" class="me-3 rounded shadow-sm">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->variant->product->name ?? 'Sản phẩm đã xóa' }}</h6>
                            <div class="text-muted small">Phân loại: {{ $item->variant->name ?? 'Mặc định' }}</div>
                            <div class="text-muted small">
                                {{ $item->quantity }} x {{ number_format($item->price) }}₫
                            </div>
                        </div>
                        <div class="text-end fw-bold text-danger">
                            {{ number_format($item->total_price) }}₫
                        </div>
                    </div>
                @endforeach

                {{-- ✅ Tổng tiền + Hành động --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="fw-bold fs-6">
                        Tổng tiền: <span class="text-danger">{{ number_format($order->total_price) }}₫</span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                        {{-- Xác nhận đã nhận hàng --}}
                        @if ($order->status === 4)
                            <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    ✅ Đã nhận hàng
                                </button>
                            </form>
                        @endif

                        {{-- Mua lại --}}
                        @if ($order->status === 5)
                            <a href="{{ route('client.products.index') }}" class="btn btn-outline-primary btn-sm">
                                🔁 Mua lại
                            </a>
                        @endif

                        {{-- Xem chi tiết --}}
                        <a href="{{ url('lich-su-don-hang/' . $order->id) }}" class="btn btn-outline-secondary btn-sm">
                            👁️ Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @endforelse
</div>
@endsection
