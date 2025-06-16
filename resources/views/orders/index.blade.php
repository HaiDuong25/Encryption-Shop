@extends('admin.layouts.main')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="card card-table">
    <div class="card-body">
        <div class="title-header option-title">
            <h5>Order List</h5>
            <!-- <a href="{{ route('orders.create') }}" class="btn btn-solid">Tạo đơn hàng</a> -->
        </div>
        <div>
            <div class="table-responsive">
                <table class="table all-package order-table theme-table" id="table_id">
                    <thead>
                        <tr>
                            <!-- <th>Hình ảnh sản phẩm</th> -->
                            <th>Mã vận đơn</th>
                            <th>Ngày</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái giao hàng</th>
                            <th>Giá sản phẩm</th>
                            <th>Tùy chỉnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <!-- <td>
                                <a class="d-block">
                                    <span class="order-image">
                                        {{-- Hiển thị hình ảnh sản phẩm đầu tiên trong đơn hàng --}}
                                        @php
                                            $productImage = null;
                                            if ($order->orderDetails && $order->orderDetails->count()) {
                                                $firstDetail = $order->orderDetails->first();
                                                $productImage = optional($firstDetail->product ?? null)->image;
                                            }
                                        @endphp
                                        @if ($productImage)
                                            <img src="{{ asset('storage/' . $productImage) }}" class="img-fluid" alt="order">
                                        @endif
                                    </span>
                                </a>
                            </td> -->
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                {{-- Hiển thị phương thức thanh toán nếu có --}}
                                {{ $order->paymentMethod->payment_type ?? 'N/A' }}
                            </td>
                            <td class="order-success">
                                @php
                                    $statusArr = [
                                        0 => 'Chờ xử lí',
                                        1 => 'Xác nhận',
                                        2 => 'Giao cho ĐVVC',
                                        3 => 'Đang giao',
                                        4 => 'Đã nhận',
                                        5 => 'Hoàn thành'
                                    ];
                                @endphp
                                <span>{{ $statusArr[$order->status] ?? 'Không xác định' }}</span>
                            </td>
                            <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                            <td>
                                <ul>
                                    <li>
                                        <a href="{{ route('orders.show', $order->id) }}">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('orders.edit', $order->id) }}">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="border:none; background:none; padding:0; color:#dc3545;">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                    <!-- <li>
                                        <a class="btn btn-sm btn-solid text-white" href="#">
                                            Tracking
                                        </a>
                                    </li> -->
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
@endsection
