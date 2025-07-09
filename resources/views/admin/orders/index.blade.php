@extends('admin.layouts.main')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<style>
.status-badge {
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
    font-weight: 500;
    border-radius: 4px;
}
.bg-purple {
    background-color: #8b5cf6 !important;
}
.bg-cyan {
    background-color: #06b6d4 !important;
}
.compact-table {
    font-size: 0.875rem;
}
.compact-table th,
.compact-table td {
    padding: 0.5rem 0.3rem;
    vertical-align: middle;
}
.compact-table th {
    font-size: 0.8rem;
    font-weight: 600;
}
.price-info {
    font-size: 0.8rem;
    line-height: 1.2;
}
.price-info .small {
    font-size: 0.7rem;
}
.action-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 0.25rem;
}
.action-list li {
    display: inline-block;
}
.action-list a,
.action-list button {
    padding: 0.25rem;
    font-size: 0.875rem;
    border-radius: 3px;
    color: #6c757d;
    transition: color 0.15s ease-in-out;
}
.action-list a:hover,
.action-list button:hover {
    color: #495057;
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
                            <th style="width: 60px;">ID</th>
                            <th style="width: 130px;">Người đặt</th>
                            <th style="width: 130px;">Người nhận</th>
                            <th style="width: 150px;">Địa chỉ nhận</th>
                            <th style="width: 90px;">Ngày đặt</th>
                            <th style="width: 100px;">PT thanh toán</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 140px;">Tổng tiền</th>
                            <th style="width: 90px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                <div style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $order->orderer_name ?? 'N/A' }}">
                                    {{ $order->orderer_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $order->recipient_name ?? $order->name ?? 'N/A' }}">
                                    {{ $order->recipient_name ?? $order->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $order->recipient_address ?? $order->address ?? 'N/A' }}">
                                    {{ $order->recipient_address ?? $order->address ?? 'N/A' }}
                                </div>
                            </td>
                            <td><small>{{ $order->created_at->format('d/m/Y') }}</small></td>
                            <td>
                                <small>{{ $order->paymentMethod->payment_type ?? 'N/A' }}</small>
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
                            <td>
                                <div class="price-info">
                                    @if($order->subtotal && $order->discount_amount > 0)
                                        <div style="font-size: 0.7rem; color: #6c757d;">
                                            Tạm tính: {{ number_format($order->subtotal, 0, ',', '.') }}đ
                                        </div>
                                        @if($order->coupon_code)
                                            <div style="font-size: 0.7rem; color: #28a745;">
                                                {{ $order->coupon_code }}: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                                            </div>
                                        @else
                                            <div style="font-size: 0.7rem; color: #28a745;">
                                                Giảm: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                                            </div>
                                        @endif
                                        <strong style="color: #007bff; font-size: 0.85rem;">
                                            {{ number_format($order->total_price, 0, ',', '.') }}đ
                                        </strong>
                                    @else
                                        <strong style="color: #007bff; font-size: 0.85rem;">
                                            {{ number_format($order->total_price, 0, ',', '.') }}đ
                                        </strong>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <ul class="action-list">
                                    <li>
                                        <a href="{{ route('orders.show', $order->id) }}" title="Xem chi tiết">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('orders.edit', $order->id) }}" title="Chỉnh sửa">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </li>
                                    @if($statusValue !== 'cancelled')
                                    <li>
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?\n\nAdmin có thể hủy đơn hàng ở bất kỳ trạng thái nào.\nSố lượng sản phẩm sẽ được trả lại kho.')">
                                            @csrf
                                            <button type="submit" style="border:none; background:none; padding:0.25rem; color:#ffc107;" title="Hủy đơn hàng">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này? Số lượng sản phẩm sẽ được trả lại kho.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="border:none; background:none; padding:0.25rem; color:#dc3545;" title="Xóa">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
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
@endsection
