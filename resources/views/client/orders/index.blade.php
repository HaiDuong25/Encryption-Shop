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

        /* Buy again modal & button tweaks */
        /* Center layout for products inside buy-again modal */
        .buy-again-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            padding: 1rem .85rem;
            text-align: center;
        }

        .buy-again-item+.buy-again-item {
            border-top: 1px solid #f1f5f9;
        }

        /* Force thumbnail size inside buy-again modal */
        #buyAgainModal .buy-again-item img {
            width: 80px !important;
            height: 80px !important;
            max-height: 80px !important;
            object-fit: cover;
            border-radius: 8px;
            background: #f3f4f6;
            aspect-ratio: 1/1;
            margin: 0 auto;
        }

        .buy-again-item .product-name {
            width: 100%;
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.3;
        }

        .buy-again-item .product-name small {
            display: block;
            font-weight: 400;
            color: #64748b;
        }

        .buy-again-item .buy-btn {
            position: static;
            width: auto;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .buy-again-item .buy-btn a {
            width: auto;
            display: inline-block;
        }

        .buy-again-trigger-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Larger action buttons */
        .btn-action-md {
            padding: .65rem 1.05rem;
            font-size: .95rem;
            line-height: 1.25;
        }

        .btn-action-xs {
            padding: .4rem .7rem;
            font-size: .75rem;
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
                                    <th>Trạng thái giao hàng</th>
                                    <th>Trạng thái trả hàng</th>
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
                                        <td class="text-start" style="max-width: 200px;">
                                            {{ Str::limit($order->recipient_address, 50) }}</td>
                                        <td>{{ format_vnd($order->total_price) }} đ</td>
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

                                                @default
                                                    <span class="badge bg-secondary status-badge">Không rõ</span>
                                            @endswitch
                                        </td>

                                        <td>
                                            @if ($order->returnStatus)
                                                <span
                                                    class="badge
                                                    @switch($order->returnStatus->status)
                                                        @case('pending')
                                                            bg-warning
                                                            @break
                                                        @case('approved')
                                                            bg-success
                                                            @break
                                                        @case('rejected')
                                                            bg-danger
                                                            @break
                                                        @default
                                                            bg-secondary
                                                    @endswitch
                                                ">
                                                    {{ $order->returnStatus->statusText }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted">Không trả hàng</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php
                                                // Thanh toán thành công nếu có bản ghi payment completed HOẶC (COD và đơn đã hoàn thành)
                                                $rawPaid =
                                                    $order->payments &&
                                                    $order->payments->where('status', 'completed')->count() > 0;
                                                $isCOD = optional($order->paymentMethod)->payment_type === 'COD';
                                                $isMomo =
                                                    optional($order->paymentMethod)->payment_type === 'Ví Điện Tử MOMO';
                                                $isPaid = $rawPaid || ($isCOD && $statusValue === 'completed');
                                            @endphp

                                            @switch($statusValue)
                                                @case('refunded')
                                                @case('returned')

                                                @case('cancelled')
                                                    {{-- ✅ thêm cancelled --}}
                                                    <span
                                                        class="badge status-badge {{ $isMomo ? 'badge-refunded' : 'badge-unpaid' }}">
                                                        {{ $isMomo ? 'Đã hoàn tiền' : 'Chưa thanh toán' }}
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

                                            @if ($order->canCompleteInIndex())
                                                <button type="button"
                                                    class="btn btn-success btn-action-md mt-1 confirm-complete-btn"
                                                    data-order-id="{{ $order->id }}"
                                                    data-message="Bạn có chắc chắn muốn xác nhận hoàn thành đơn hàng #{{ $order->id }}?">
                                                    <i class="fas fa-check-circle me-1"></i> Xác nhận hoàn thành
                                                </button>
                                            @endif

                                            @if (in_array($statusValue, ['completed', 'cancelled']))
                                                @php
                                                    $details = $order->orderDetails;
                                                    $detailsCount = $details->count();
                                                    $firstDetail = $details->first();
                                                    $firstProduct = $firstDetail?->product;
                                                @endphp
                                                <div class="buy-again-trigger-wrapper">
                                                    @if ($detailsCount <= 1)
                                                        @if ($firstProduct)
                                                            <a href="{{ route('client.products.show', $firstProduct->id) }}"
                                                                class="btn btn-outline-primary btn-action-md mt-1 px-3"
                                                                title="Mua lại sản phẩm: {{ $firstProduct->name }}">
                                                                🔁 Mua lại
                                                            </a>
                                                        @else
                                                            <a href="{{ route('client.products.index') }}"
                                                                class="btn btn-outline-secondary btn-action-md mt-1 px-3"
                                                                title="Không tìm thấy sản phẩm trong đơn, quay lại danh sách sản phẩm">
                                                                🔁 Mua lại
                                                            </a>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-action-md mt-1 px-3 buy-again-multi-btn"
                                                            data-order-id="{{ $order->id }}"
                                                            title="Chọn sản phẩm để mua lại">
                                                            🔁 Mua lại
                                                        </button>
                                                        <div class="d-none buy-again-products"
                                                            id="buy-again-products-{{ $order->id }}">
                                                            @foreach ($details as $d)
                                                                @php
                                                                    $p = $d->product;
                                                                    $img = $d->image
                                                                        ? asset('storage/' . $d->image)
                                                                        : ($p
                                                                            ? ($p->image
                                                                                ? asset('storage/' . $p->image)
                                                                                : asset('images/placeholder.png'))
                                                                            : asset('images/placeholder.png'));
                                                                @endphp
                                                                @if ($p)
                                                                    <div class="buy-again-item"
                                                                        data-product-id="{{ $p->id }}"
                                                                        data-has-variants="{{ $p->variants->count() > 0 ? '1' : '0' }}">
                                                                        <img class="img-thumbnail"
                                                                            style="width: 300px; object:fit;"
                                                                            src="{{ $img }}"
                                                                            alt="{{ $p->name }}">
                                                                        <div class="product-name"
                                                                            title="{{ $p->name }}">
                                                                            {{ Str::limit($p->name, 45) }}
                                                                            @if ($p->variants->count() > 0)
                                                                                <small>(Có phân loại)</small>
                                                                            @endif
                                                                        </div>
                                                                        <div
                                                                            class="d-flex flex-column align-items-end gap-1 buy-btn">
                                                                            <a class="btn btn-outline-primary btn-action-md"
                                                                                href="{{ route('client.products.show', $p->id) }}"
                                                                                title="Xem">Xem sản phẩm</a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="buy-again-item text-muted">(Sản phẩm đã bị
                                                                        xóa)</div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
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
                .then(r => r.json())
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
                .catch(err => {
                    console.error(err);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                    button.disabled = false;
                    button.innerHTML = originalContent;
                });
        }

        // ===== Modal xác nhận hoàn thành (dùng chung với show) =====
        function showConfirmCompleteModal(message, onConfirm) {
            const modal = new bootstrap.Modal(document.getElementById('confirmCompleteModal'));
            const confirmMessage = document.getElementById('confirmCompleteMessage');
            const confirmButton = document.getElementById('confirmCompleteButton');
            confirmMessage.textContent = message;
            const newBtn = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newBtn, confirmButton);
            newBtn.addEventListener('click', function() {
                modal.hide();
                onConfirm();
            });
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('.confirm-complete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const message = this.dataset.message ||
                        `Bạn có chắc chắn muốn xác nhận hoàn thành đơn hàng #${orderId}?`;
                    showConfirmCompleteModal(message, () => {
                        const originalHtml = this.innerHTML;
                        this.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                        this.disabled = true;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/lich-su-don-hang/${orderId}/confirm`;
                        form.style.display = 'none';
                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = csrfToken;
                        form.appendChild(tokenInput);
                        document.body.appendChild(form);
                        form.submit();
                    });
                });
            });
        });
    </script>
@endpush

<!-- Modal xác nhận hoàn thành (giống show) -->
<div class="modal fade" id="confirmCompleteModal" tabindex="-1" aria-labelledby="confirmCompleteModalLabel"
    aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered"
        style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCompleteModalLabel">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Xác nhận hoàn thành đơn hàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="confirmCompleteIcon" class="mb-3">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745;"></i>
                </div>
                <p id="confirmCompleteMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-success" id="confirmCompleteButton">
                    <i class="fas fa-check-circle me-1"></i>Xác nhận
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal chọn sản phẩm mua lại -->
<div class="modal fade" id="buyAgainModal" tabindex="-1" aria-labelledby="buyAgainModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyAgainModalLabel"><i class="fas fa-redo me-2 text-primary"></i>Chọn sản
                    phẩm để mua lại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="buyAgainProductsContainer" class="list-group small"></div>
                <div id="buyAgainEmpty" class="text-center text-muted d-none">Không tìm thấy sản phẩm hợp lệ.</div>
            </div>
            <!-- Đã loại bỏ tính năng thêm nhanh vào giỏ -->
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary btn-action-md" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mua lại nhiều sản phẩm
            const buyAgainModalEl = document.getElementById('buyAgainModal');
            const buyAgainModal = buyAgainModalEl ? new bootstrap.Modal(buyAgainModalEl) : null;
            const productsContainer = document.getElementById('buyAgainProductsContainer');
            const emptyEl = document.getElementById('buyAgainEmpty');

            document.querySelectorAll('.buy-again-multi-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const source = document.getElementById(`buy-again-products-${orderId}`);
                    if (!source) return;
                    productsContainer.innerHTML = '';
                    emptyEl.classList.add('d-none');
                    const items = Array.from(source.children);
                    if (items.length === 0) {
                        emptyEl.classList.remove('d-none');
                    } else {
                        items.forEach(node => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'list-group-item buy-again-item';
                            wrapper.innerHTML = node.innerHTML; // copy inner
                            productsContainer.appendChild(wrapper);
                        });
                    }
                    buyAgainModal && buyAgainModal.show();
                });
            });
        });
    </script>
@endpush
