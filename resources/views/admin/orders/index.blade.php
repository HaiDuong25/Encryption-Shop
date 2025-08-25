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
            <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                <h5>Danh sách đơn hàng</h5>
                <div class="right-options d-flex gap-2 align-items-center">
                    {{-- Form tìm kiếm theo ID đơn hàng hoặc tên người nhận --}}
                    <form method="GET" action="{{ route('orders.index') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo ID, tên người nhận hoặc tên user..."
                               value="{{ request('search') }}" style="width: 320px;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        @if(request('search'))
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
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
                                <th width="8%">Giao hàng</th>
                                <th width="8%">Trả hàng</th>
                                <th width="15%">Tổng tiền</th>
                                <th width="14%">Thao tác</th>
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
                                        @php
                                            $rawPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                                            $paymentType = optional($order->paymentMethod)->payment_type;
                                            // Chuẩn hóa statusValue nếu chưa có (dùng lại biến bên dưới nếu cần)
                                            $tmpStatus = $order->status;
                                            if (is_numeric($tmpStatus)) {
                                                $tmpMap = [
                                                    '0' => 'pending',
                                                    '1' => 'confirmed',
                                                    '2' => 'shipping',
                                                    '3' => 'delivering',
                                                    '4' => 'received',
                                                    '5' => 'completed',
                                                    '9' => 'cancelled',
                                                ];
                                                $tmpStatus = $tmpMap[$tmpStatus] ?? 'pending';
                                            }
                                            $isPaidCol = $rawPaid || ($paymentType === 'COD' && $tmpStatus === 'completed');
                                        @endphp
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="small text-muted mb-1">{{ $paymentType ?? 'N/A' }}</span>
                                            <span class="badge {{ $isPaidCol ? 'bg-success' : 'bg-secondary' }} status-badge">
                                                {{ $isPaidCol ? 'Đã thanh toán' : 'Chưa' }}
                                            </span>
                                        </div>
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
                                        @else
                                            <span class="badge bg-secondary status-badge">{{ $statusValue }}</span>
                                        @endif

                                    </td>
                                    <td>
                                        @php
                                            $returnStatus = $order->returnStatus;
                                        @endphp
                                        @if($returnStatus && $returnStatus->overall_status !== 'none')
                                            @switch($returnStatus->overall_status)
                                                @case('partial')
                                                    <span class="badge bg-warning text-dark">Một phần</span>
                                                    @break
                                                @case('full')
                                                    <span class="badge bg-info">Toàn bộ</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Hoàn tất</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $returnStatus->overall_status }}</span>
                                            @endswitch
                                        @else
                                            <span class="text-muted">-</span>
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
                                                <a href="{{ route('orders.show', $order->id) }}" title="Xem chi tiết & Cập nhật trạng thái">
                                                    <i class="ri-eye-line" style="font-size: 1.1rem;"></i>
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

            {{-- Thêm phân trang --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <script>
        // Function để hiển thị alert
        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            const container = document.querySelector('.card');
            container.parentNode.insertBefore(alertDiv, container);

            // Auto hide after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Function để hiển thị modal xác nhận
        function showConfirmModal(message, onConfirm, type = 'warning') {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const confirmMessage = document.getElementById('confirmMessage');
            const confirmButton = document.getElementById('confirmButton');
            const confirmIcon = document.getElementById('confirmIcon');

            // Cập nhật nội dung modal
            confirmMessage.textContent = message;

            // Cập nhật icon và màu sắc dựa trên type
            if (type === 'danger') {
                confirmIcon.innerHTML = '<i class="ri-delete-bin-line" style="font-size: 48px; color: #dc3545;"></i>';
                confirmButton.className = 'btn btn-danger';
                confirmButton.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Xóa';
            } else if (type === 'warning') {
                confirmIcon.innerHTML = '<i class="ri-alert-line" style="font-size: 48px; color: #ffc107;"></i>';
                confirmButton.className = 'btn btn-warning';
                confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
            } else {
                confirmIcon.innerHTML = '<i class="ri-question-line" style="font-size: 48px; color: #0d6efd;"></i>';
                confirmButton.className = 'btn btn-primary';
                confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
            }

            // Xóa event listener cũ và thêm mới
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            // Thêm event listener cho nút xác nhận
            newConfirmButton.addEventListener('click', function() {
                modal.hide();
                onConfirm();
            });

            // Hiển thị modal
            modal.show();
        }

        function cancelOrder(orderId) {
            showConfirmModal(
                'Bạn có chắc chắn muốn hủy đơn hàng này không? Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xử lý" hoặc "Đã xác nhận". Số lượng sản phẩm sẽ được trả lại.',
                () => {
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
                            showAlert(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('Lỗi: ' + data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi hủy đơn hàng', 'danger');
                    });
                },
                'warning'
            );
        }

        function deleteOrder(orderId) {
            showConfirmModal(
                'Bạn có chắc chắn muốn xóa đơn hàng này không? CHỈ có thể xóa đơn hàng ở trạng thái "Đã hủy". Các đơn hàng đang trả hàng KHÔNG được phép xóa.',
                () => {
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
                            showAlert(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('Lỗi: ' + data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi xóa đơn hàng', 'danger');
                    });
                },
                'danger'
            );
        }
    </script>

    <!-- Modal xác nhận -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        <i class="ri-question-line text-warning me-2"></i>
                        Xác nhận hành động
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="confirmIcon" class="mb-3">
                        <i class="ri-question-line" style="font-size: 48px; color: #ffc107;"></i>
                    </div>
                    <p id="confirmMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmButton">
                        <i class="ri-check-line me-1"></i>Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
