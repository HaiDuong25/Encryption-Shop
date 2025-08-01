@extends('admin.layouts.main')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    <style>
        .status-badge {
            font-size: 0.85rem;
            padding: 0.3em 0.6em;
            font-weight: 500;
            border-radius: 4px;
        }

        .bg-purple {
            background-color: #8b5cf6 !important;
        }

        .bg-cyan {
            background-color: #06b6d4 !important;
        }

        .price-info {
            min-width: 120px;
            font-size: 0.95rem;
        }

        .price-info .subtotal {
            font-size: 0.85em;
        }

        .price-info .discount {
            font-size: 0.85em;
        }

        .price-info .total {
            color: #2563eb;
            font-size: 1em;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.4rem 0.3rem;
            vertical-align: middle;
            line-height: 1.3;
            font-size: 0.95rem;
        }

        .compact-table th {
            font-size: 1rem;
            font-weight: 600;
        }

        .compact-table {
            margin-bottom: 0;
        }

        .action-buttons {
            display: flex;
            gap: 2px;
            justify-content: center;
        }

        .bg-returning {
            background-color: #f7941d;
            /* cam đậm */
            color: white;
        }

        .action-buttons li {
            list-style: none;
        }

        .action-buttons ul {
            margin: 0;
            padding: 0;
            display: flex;
            gap: 2px;
        }

        .address-cell {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .address-cell:hover {
            white-space: normal;
            overflow: visible;
            position: relative;
            z-index: 10;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            padding: 0.4rem 0.3rem;
            border-radius: 4px;
        }
    </style>
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title">
                <h5>Order List</h5>
            </div>
            <div>
                <div class="table-responsive">
                    <table class="table all-package order-table theme-table compact-table" id="table_id">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Người nhận</th>
                                <th width="15%">Địa chỉ</th>
                                <th width="10%">Ngày đặt</th>
                                <th width="10%">Thanh toán</th>
                                <th width="10%">Trạng thái</th>
                                <th width="20%">Tổng tiền</th>
                                <th width="15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>

                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->recipient_name ?? ($order->orderer_name ?? 'N/A') }}</td>
                                    <!-- Sửa: dùng recipient_name -->
                                    <td class="address-cell" title="{{ $order->recipient_address ?? 'N/A' }}">
                                        {{ $order->recipient_address ?? 'N/A' }}</td> <!-- Sửa: dùng recipient_address -->
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
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
                                                    '5' => 'completed',
                                                    '6' => 'returning', // ✅ trạng thái đang trả hàng
                                                    '7' => 'approved', // ✅ trạng thái đã trả hàng
                                                    '8' => 'rejected', // ✅ trạng thái từ chối trả
                                                    '9' => 'cancelled',
                                                ];

                                                $statusValue = $statusMap[$statusValue] ?? 'pending';
                                            }
                                        @endphp
                                        @if ($statusValue == 'pending')
                                            <span class="badge bg-warning status-badge">Chờ xử lý</span>
                                        @elseif($statusValue == 'confirmed')
                                            <span class="badge bg-primary status-badge">Đã xác nhận</span>
                                        @elseif($statusValue == 'shipping')
                                            <span class="badge bg-info status-badge">ĐVVC</span>
                                        @elseif($statusValue == 'delivering')
                                            <span class="badge bg-purple status-badge">Đang giao</span>
                                        @elseif($statusValue == 'received')
                                            <span class="badge bg-cyan status-badge">Đã nhận</span>
                                        @elseif($statusValue == 'completed')
                                            <span class="badge bg-success status-badge">Hoàn thành</span>
                                        @elseif($statusValue == 'cancelled')
                                            <span class="badge bg-danger status-badge">Đã hủy</span>
                                        @elseif($statusValue == 'returning')
                                            <span class="badge bg-returning status-badge">Đang trả hàng</span>
                                        @elseif($statusValue == 'approved')
                                            <span class="badge bg-info status-badge">Đã trả hàng</span>
                                        @elseif($statusValue == 'rejected')
                                            <span class="badge bg-dark status-badge">Từ chối trả</span>
                                        @else
                                            <span class="badge bg-secondary status-badge">{{ $statusValue }}</span>
                                        @endif

                                    </td>
                                    <td>
                                        @php
                                            $subtotal =
                                                $order->subtotal ??
                                                $order->orderDetails->sum(fn($d) => $d->price * $d->quantity);

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
                                            @if ($actualDiscountAmount > 0)
                                                <div class="subtotal text-muted">
                                                    {{ format_vnd($subtotal) }}đ
                                                </div>
                                                <div class="discount text-danger">
                                                    -{{ format_vnd($actualDiscountAmount) }}đ
                                                    @if ($order->coupon_code)
                                                        <span class="badge bg-primary ms-1"
                                                            style="font-size: 0.65rem;">{{ $order->coupon_code }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="total fw-bold">
                                                {{ format_vnd($total) }}đ
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <ul class="action-buttons">
                                            <li>
                                                <a href="{{ route('orders.show', $order->id) }}" title="Xem chi tiết">
                                                    <i class="ri-eye-line" style="font-size: 1.1rem;"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('orders.edit', $order->id) }}" title="Chỉnh sửa">
                                                    <i class="ri-pencil-line" style="font-size: 1.1rem;"></i>
                                                </a>
                                            </li>
                                            @php
                                                // Chỉ cho phép hủy đơn hàng ở trạng thái "Chờ xử lý" và "Đã xác nhận"
                                                $canCancel = false;
                                                $cancelStatusValue = $order->status;
                                                if (is_numeric($cancelStatusValue)) {
                                                    $cancelStatusMap = [
                                                        '0' => 'pending',
                                                        '1' => 'confirmed', 
                                                        '2' => 'shipping',
                                                        '3' => 'delivering',
                                                        '4' => 'received',
                                                        '5' => 'completed',
                                                        '6' => 'returning',
                                                        '7' => 'approved',
                                                        '8' => 'rejected',
                                                        '9' => 'cancelled',
                                                    ];
                                                    $cancelStatusValue = $cancelStatusMap[$cancelStatusValue] ?? 'pending';
                                                }
                                                $canCancel = in_array($cancelStatusValue, ['pending', 'confirmed']);
                                            @endphp
                                            @if ($canCancel)
                                                <li>
                                                    <button type="button" onclick="cancelOrder({{ $order->id }})"
                                                        style="border:none; background:none; padding:0; color:#ffc107; font-size: 1.1rem;"
                                                        title="Hủy đơn hàng">
                                                        <i class="ri-close-circle-line"></i>
                                                    </button>
                                                </li>
                                            @endif
                                            @php
                                                // Chỉ cho phép xóa đơn hàng đã hủy (không bao gồm trạng thái trả hàng)
                                                $canDelete = false;
                                                $deleteStatusValue = $order->status;
                                                if (is_numeric($deleteStatusValue)) {
                                                    $deleteStatusMap = [
                                                        '0' => 'pending',
                                                        '1' => 'confirmed', 
                                                        '2' => 'shipping',
                                                        '3' => 'delivering',
                                                        '4' => 'received',
                                                        '5' => 'completed',
                                                        '6' => 'returning',
                                                        '7' => 'approved',
                                                        '8' => 'rejected',
                                                        '9' => 'cancelled',
                                                    ];
                                                    $deleteStatusValue = $deleteStatusMap[$deleteStatusValue] ?? 'pending';
                                                }
                                                // CHỈ cho phép xóa đơn hàng đã hủy, KHÔNG bao gồm trạng thái trả hàng
                                                $canDelete = ($deleteStatusValue === 'cancelled');
                                            @endphp
                                            @if ($canDelete)
                                                <li>
                                                    <button type="button" onclick="deleteOrder({{ $order->id }})"
                                                        style="border:none; background:none; padding:0; color:#dc3545; font-size: 1.1rem;"
                                                        title="Xóa đơn hàng (chỉ đơn đã hủy)">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </li>
                                            @endif
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

    <script>
        function cancelOrder(orderId) {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã xác nhận". Số lượng sản phẩm sẽ được trả lại.')) {
                return;
            }

            fetch(`/admin/orders/${orderId}/cancel`, {
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
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                });
        }

        function deleteOrder(orderId) {
            if (!confirm(
                    'Bạn có chắc chắn muốn xóa đơn hàng này không? CHỈ có thể xóa đơn hàng ở trạng thái "Đã hủy". Các đơn hàng đang trả hàng KHÔNG được phép xóa.'
                )) {
                return;
            }

            fetch(`/admin/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa đơn hàng');
                });
        }
    </script>
@endsection
