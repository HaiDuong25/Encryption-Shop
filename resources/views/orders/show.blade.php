@extends('admin.layouts.main')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<style>
    .order-info-card {
        background: #f8fafc;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 24px 32px;
        margin-bottom: 32px;
    }
    .order-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #2563eb;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-info-list {
        list-style: none;
        padding: 0;
        margin-bottom: 18px;
    }
    .order-info-list li {
        margin-bottom: 7px;
        font-size: 1rem;
    }
    .order-table th, .order-table td {
        vertical-align: middle !important;
    }
    .order-table th {
        background: #e0e7ef;
        color: #1e293b;
    }
    .order-status-badge {
        padding: 2px 10px;
        border-radius: 8px;
        font-size: 0.95em;
        font-weight: 500;
        color: #fff;
        display: inline-block;
    }
    .status-0 { background: #f59e42; }
    .status-1 { background: #3b82f6; }
    .status-2 { background: #22c55e; }
    .status-3 { background: #ef4444; }
    .order-history-list {
        list-style: none;
        padding: 0;
    }
    .order-history-list li {
        margin-bottom: 6px;
        font-size: 0.98rem;
    }
    .order-back-btn {
        margin-top: 18px;
    }
</style>
<div class="order-info-card">
    <div class="row">
        <div class="col-md-6">
            <div class="order-section-title">
                <i class="fas fa-user"></i> Thông tin khách hàng
            </div>
            <ul class="order-info-list">
                <li><strong>Tên:</strong> {{ $order->name }}</li>
                <li><strong>SĐT:</strong> {{ $order->phone }}</li>
                <li><strong>Địa chỉ:</strong> {{ $order->address }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="order-section-title">
                <i class="fas fa-info-circle"></i> Thông tin đơn hàng
            </div>
            <ul class="order-info-list">
                <li><strong>Ngày tạo:</strong>
                    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Không xác định' }}
                </li>
                <li>
                    <strong>Trạng thái:</strong>
                    @php
                        $statusArr = ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'];
                        $statusClass = 'status-' . ($order->status ?? 0);
                    @endphp
                    <span class="order-status-badge {{ $statusClass }}">
                        <i class="fas fa-circle"></i> {{ $statusArr[$order->status] ?? 'Không xác định' }}
                    </span>
                </li>
                <li><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->payment_type ?? 'N/A' }}</li>
                <li><strong>Mã giảm giá:</strong> {{ $order->coupon->code ?? 'Không áp dụng' }}</li>
                <li><strong>Tổng tiền:</strong> <span style="color:#e11d48;font-weight:600">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></li>
            </ul>
        </div>
    </div>
    <div class="order-section-title" style="margin-top:28px;">
        <i class="fas fa-box"></i> Sản phẩm trong đơn hàng
    </div>
    <div class="table-responsive">
        <table class="table table-bordered order-table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>
                            @if ($detail->image)
                                <img src="{{ asset('storage/' . $detail->image) }}" width="60" style="border-radius:8px;">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $detail->product->name ?? 'Sản phẩm đã xóa' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-primary mt-2">
        Theo dõi đơn hàng
    </a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary order-back-btn">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
@endsection
