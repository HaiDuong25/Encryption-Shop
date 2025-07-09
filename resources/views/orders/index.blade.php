@extends('admin.layouts.main')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<style>
.status-badge {
    font-size: 0.875rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
    border-radius: 4px;
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
                <table class="table all-package order-table theme-table" id="table_id">
                    <thead>
                        <tr>
                            <!-- <th>Hình ảnh sản phẩm</th> -->
                            <th>ID</th>

                            <th>Tên người nhận</th> <!-- Thêm -->
                            <th>Địa chỉ giao hàng</th> <!-- Thêm -->
                            <th>Ngày đặt</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái giao hàng</th>
                            <th>Giá sản phẩm</th>
                            <th>Tùy chỉnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>

                            <td>{{ $order->id }}</td>
                             <td>{{ $order->name }}</td> <!-- Thêm -->
                                <td>{{ $order->address }}</td> <!-- Thêm -->
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                {{-- Hiển thị phương thức thanh toán nếu có --}}
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
                                    <span class="badge bg-info status-badge">Giao cho ĐVVC</span>
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
                            <td> {{ number_format($order->orderDetails->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }} đ</td>
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
