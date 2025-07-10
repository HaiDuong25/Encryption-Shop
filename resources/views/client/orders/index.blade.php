@extends('client.layout.main')

@section('content')
    <style>
        .status-badge {
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 4px;
        }

        .bg-purple {
            background-color: #8b5cf6 !important;
        }

        .bg-cyan {
            background-color: #06b6d4 !important;
        }

        .card {
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            border: none;
            padding: 0.875rem;
        }

        .table td {
            padding: 0.875rem;
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .btn-outline-primary {
            border-color: #3b82f6;
            color: #3b82f6;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .btn-outline-primary:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
    </style>
    <div class="container-fluid-lg py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="mb-0">Đơn hàng của tôi</h3>
            </div>
            <div class="card-body">
                @if ($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Người nhận</th>
                                    <th>SĐT</th>
                                    <th>Địa chỉ</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->orderer_name }}</td>
                                        <td>{{ $order->orderer_phone }}</td>
                                        <td>{{ $order->recipient_address }}</td>
                                        <td>{{ number_format($order->total_price) }} đ</td>
                                        <td>
                                            @php
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
                                                    $statusValue = $statusMap[$statusValue] ?? 'pending';
                                                }
                                            @endphp



                                            @if ($statusValue == 'pending')
                                                <span class="badge bg-warning status-badge">Chờ xử lý</span>
                                            @elseif($statusValue == 'confirmed')
                                                <span class="badge bg-primary status-badge">Đã xác nhận</span>
                                            @elseif($statusValue == 'shipping')
                                                <span class="badge bg-info status-badge">Đã giao cho ĐVVC</span>
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
                                            <a href="{{ route('client.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">Xem</a>
                                            @if ($statusValue == 'received')
                                                <form action="{{ route('orders.confirm', $order->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm ms-1">Xác nhận hoàn
                                                        thành
                                                    </button>
                                                </form>
                                            @endif

                                            @if (in_array($statusValue, ['completed', 'cancelled']))
                                                <a href="{{ route('client.products.index') }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    🔁 Mua lại
                                                </a>
                                            @endif


                                            {{-- Nếu muốn cho phép hủy ở trạng thái chờ xử lý, thêm nút hủy ở đây --}}
                                            {{-- @if ($statusValue == 'pending')
                                                <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm ms-1">Hủy đơn</button>
                                                </form>
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center mb-0">Bạn chưa có đơn hàng nào.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function cancelOrder(orderId) {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
                return;
            }

            // Disable button to prevent double click
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang hủy...';

            fetch(`/orders/${orderId}/cancel`, {
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
@endpush
