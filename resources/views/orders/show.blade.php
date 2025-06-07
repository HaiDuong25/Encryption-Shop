@extends('admin.layouts.main')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Đơn hàng #{{ $order->id }}</h5>
    </div>
    <div class="card-body">
        <h6>Thông tin khách hàng</h6>
        <ul>
            <li><strong>Tên:</strong> {{ $order->name }}</li>
            <li><strong>SĐT:</strong> {{ $order->phone }}</li>
            <li><strong>Địa chỉ:</strong> {{ $order->address }}</li>
        </ul>
        <h6>Thông tin đơn hàng</h6>
        <ul>
<li><strong>Ngày tạo:</strong>
    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Không có' }}
</li>
            <li><strong>Trạng thái:</strong>
                @php
                    $statusArr = ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'];
                @endphp
                {{ $statusArr[$order->status] ?? 'Không xác định' }}
            </li>
            <li><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->payment_type ?? 'N/A' }}</li>
            <li><strong>Mã giảm giá:</strong> {{ $order->coupon->code ?? 'Không áp dụng' }}</li>
            <li><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} đ</li>
        </ul>
        <h6>Sản phẩm trong đơn hàng</h6>
        <table class="table table-bordered">
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
                            @if ($detail->product && $detail->product->image)
                                <img src="{{ asset('storage/' . $detail->product->image) }}" width="60">
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
        <h6>Lịch sử thanh toán</h6>
        <ul>
            @forelse ($order->payments as $payment)
                <li>
                    {{ $payment->created_at->format('d/m/Y H:i') }} -
                    {{ number_format($payment->amount, 0, ',', '.') }} đ
                    ({{ $payment->note }})
                </li>
            @empty
                <li>Chưa có thanh toán</li>
            @endforelse
        </ul>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection
