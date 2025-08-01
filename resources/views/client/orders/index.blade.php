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

        .status-badge {
            font-weight: bold;
            padding: 0.5em 0.75em;
            border-radius: 0.5em;
        }

        .badge-refunded {
            background-color: #17a2b8;
            color: #fff;
        }

        .badge-returning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-refunded-approved {
            background-color: #fd7e14;
            color: #fff;
        }

        .badge-paid {
            background-color: #28a745;
            color: #fff;
        }

        .badge-unpaid {
            background-color: #6c757d;
            color: #fff;
        }
    </style>

    <div class="container-fluid-lg py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="mb-0">Đơn hàng của tôi</h3>
            </div>
            <div class="card-body">
                {{-- Hiển thị thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

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
                                    <th>Trạng thái đơn</th>
                                    <th>Thanh toán</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->recipient_name }}</td>
                                        <td>{{ $order->recipient_phone }}</td>
                                        <td class="text-start" style="max-width: 200px;">{{ Str::limit($order->recipient_address, 50) }}</td>
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
                                                        '7' => 'returning',
                                                        '8' => 'approved',
                                                        '9' => 'refunded',
                                                        '10' => 'pending',
                                                    ];
                                                    $statusValue = $statusMap[$statusValue] ?? 'pending';
                                                }
                                            @endphp

                                            @switch($statusValue)
                                                @case('pending')
                                                    <span class="badge bg-warning status-badge">Chờ xử lý</span>
                                                @break

                                                @case('confirmed')
                                                    <span class="badge bg-primary status-badge">Đã xác nhận</span>
                                                @break

                                                @case('shipping')
                                                    <span class="badge bg-info status-badge">Đã giao cho ĐVVC</span>
                                                @break

                                                @case('delivering')
                                                    <span class="badge bg-purple status-badge">Đang giao</span>
                                                @break

                                                @case('received')
                                                    <span class="badge bg-cyan status-badge">Đã nhận</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-success status-badge">Hoàn thành</span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge bg-danger status-badge">Đã hủy</span>
                                                @break

                                                @case('returning')
                                                    <span class="badge bg-warning text-dark status-badge">Đang trả hàng</span>
                                                @break

                                                @case('approved')
                                                    <span class="badge bg-info text-dark status-badge">Đã trả hàng</span>
                                                @break

                                                @case('rejected')
                                                    <span class="badge bg-danger status-badge">Bị từ chối</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary status-badge">Không rõ</span>
                                            @endswitch
                                        </td>

                                        <td>
                                            @php
                                                $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                                                $isCOD = optional($order->paymentMethod)->payment_type === 'COD';
                                                $isMomo = optional($order->paymentMethod)->payment_type === 'Ví Điện Tử MOMO';
                                            @endphp

                                            @switch($statusValue)
                                                @case('refunded')
                                                @case('returned')
                                                @case('approved')
                                                    <span class="badge status-badge {{ $isMomo ? 'badge-refunded' : 'badge-unpaid' }}">
                                                        {{ $isMomo ? 'Đã hoàn tiền' : 'Chưa thanh toán' }}
                                                    </span>
                                                @break

                                                @case('returning')
                                                    <span class="badge status-badge {{ $isMomo ? 'badge-returning' : 'badge-unpaid' }}">
                                                        {{ $isMomo ? 'Đang hoàn tiền' : 'Chưa thanh toán' }}
                                                    </span>
                                                @break

                                                @default
                                                    <span class="badge status-badge {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                                                        {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                    </span>
                                            @endswitch
                                        </td>

                                        <td>
                                            <a href="{{ route('client.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">Xem</a>

                                            @if ($statusValue == 'received')
                                                <form action="{{ route('orders.confirm', $order->id) }}" method="POST"
                                                    style="display:inline;" class="mt-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Xác nhận hoàn thành</button>
                                                </form>
                                            @endif

                                            @if (in_array($statusValue, ['completed', 'cancelled']))
                                                <a href="{{ route('client.products.index') }}"
                                                    class="btn btn-outline-primary btn-sm mt-1">
                                                    🔁 Mua lại
                                                </a>
                                            @endif
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
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Số lượng sản phẩm sẽ được trả lại kho.')) {
                return;
            }

            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang hủy...';

            fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cancel_reason: 'Khách hàng hủy đơn',
                    note: null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                    button.disabled = false;
                    button.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi hủy đơn hàng');
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        }
    </script>
@endpush
