@extends('admin.layouts.main')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        .order-header-bar {
            background: #fff;
            padding: 20px 28px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-size: 16px;
            font-weight: 500;
        }

        .order-header-bar span {
            margin-right: 16px;
            color: #334155;
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

        .badge-return-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-return-approved {
            background-color: #28a745;
            color: #fff;
        }

        .badge-return-rejected {
            background-color: #dc3545;
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

        .table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
        }

        .badge-status {
            padding: 4px 12px;
            border-radius: 8px;
            color: #fff;
            font-size: 0.9rem;
        }

        .status-0 {
            background-color: #f59e0b;
        }

        .status-1 {
            background-color: #3b82f6;
        }

        .status-2 {
            background-color: #10b981;
        }

        .status-3 {
            background-color: #ef4444;
        }

        .summary-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .summary-card p {
            margin-bottom: 10px;
        }

        .summary-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .timeline-admin {
            position: relative;
            padding-left: 0;
        }

        .timeline-admin::before {
            content: '';
            position: absolute;
            left: 23px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
            z-index: 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 1rem;
        }

        .timeline-item .me-3 {
            margin-right: 1rem;
        }

        .timeline-hidden {
            display: none !important;
        }
    </style>

    {{-- Progress bar tiến trình giao hàng --}}
    @php
        $statuses = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Giao cho ĐVVC',
            'delivering' => 'Đang giao',
            'received' => 'Đã nhận',
            'completed' => 'Hoàn thành',
        ];
        $statusMap = [
            '0' => 'pending',
            '1' => 'confirmed',
            '2' => 'shipping',
            '3' => 'delivering',
            '4' => 'received',
            '5' => 'completed',
            '6' => 'cancelled',
        ];
        $statusValue = is_numeric($order->status) ? $statusMap[(string) $order->status] ?? 'pending' : $order->status;

        $statusValue = is_numeric($order->status)
            ? $statusMap[(string) $order->status] ?? 'pending'
            : $order->status;
        $statusKeys = array_keys($statuses);
        $currentStatusIndex = array_search($statusValue, $statusKeys);
        $isCancelled = $statusValue === 'cancelled';
    @endphp

    <div class="mb-4">
        @if ($isCancelled)
            <div class="alert alert-danger text-center mb-2">
                <i class="fas fa-times-circle me-1"></i> Đơn hàng đã bị hủy
            </div>
        @else
            {{-- Progress bar giao hàng --}}
            <div class="progress" style="height: 10px;">
                @foreach ($statuses as $key => $label)
                    <div class="progress-bar {{ array_search($key, $statusKeys) <= $currentStatusIndex ? 'bg-primary' : 'bg-secondary' }}"
                        style="width: {{ 100 / count($statuses) }}%"></div>
                @endforeach
            </div>
            <div class="d-flex justify-content-between mt-2 small">
                @foreach ($statuses as $key => $label)
                    <div class="text-center {{ array_search($key, $statusKeys) <= $currentStatusIndex ? 'text-primary fw-bold' : 'text-muted' }}"
                        style="width: {{ 100 / count($statuses) }}%">
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- Timeline chi tiết lịch sử trạng thái --}}
    @if (!$isCancelled && $order->statusHistories && $order->statusHistories->count())
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Tiến trình vận chuyển</h5>
                    @if ($order->statusHistories->count() > 2)
                        <button class="btn btn-sm btn-outline-primary" id="toggleTimelineBtn" type="button">
                            <i class="fas fa-eye me-1"></i>
                            <span id="toggleTimelineText">Xem thêm</span>
                        </button>
                    @endif
                </div>
                <ul class="list-unstyled" id="timelineList">
                    @php
                        $sortedHistories = $order->statusHistories->sortByDesc('created_at')->values();
                    @endphp
                    @foreach ($sortedHistories as $index => $history)
                        @php $shouldHide = $index >= 2; @endphp
                        <li class="mb-3 d-flex timeline-item {{ $shouldHide ? 'timeline-hidden' : '' }}">
                            <div class="me-3">
                                @if ($history->new_status === $statusValue)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="far fa-circle text-muted"></i>
                                @endif
                            </div>
                            <div>
                                <strong>{{ $statuses[$history->new_status] ?? ucfirst($history->new_status) }}</strong>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>{{ $history->created_at->format('H:i d/m/Y') }}
                                    @if ($history->user)
                                        <br><i class="fas fa-user me-1"></i>Thực hiện bởi:
                                        <strong>{{ $history->user->name ?? 'N/A' }}</strong>
                                    @elseif($history->changed_by)
                                        <br><i class="fas fa-user me-1"></i>Thực hiện bởi: <strong>User ID
                                            {{ $history->changed_by }}</strong>
                                    @else
                                        <br><i class="fas fa-robot me-1"></i>Thực hiện bởi: <strong>Hệ thống</strong>
                                    @endif
                                    @if ($history->description)
                                        <br><i class="fas fa-comment me-1"></i>Ghi chú: {{ $history->description }}
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    {{-- Phần còn lại giữ nguyên --}}
    <div class="order-header-bar">
        <div>
            <span><i class="fas fa-calendar-alt text-info"></i> {{ $order->created_at->format('d/m/Y H:i') }}</span>
            <span><i class="fas fa-box text-primary"></i> {{ $order->orderDetails->sum('quantity') }} sản phẩm</span>
        </div>
        <div>
            @php
                $subtotal = $order->subtotal ?? $order->orderDetails->sum(fn($d) => $d->price * $d->quantity);

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

            @if ($actualDiscountAmount > 0)
                <span><i class="fas fa-calculator text-info"></i> Tạm tính:
                    <strong>{{ format_vnd($subtotal) }}
                        đ</strong></span>
                <span><i class="fas fa-tag text-success"></i> Giảm giá: <strong
                        style="color:#10b981">-{{ format_vnd($actualDiscountAmount) }} đ</strong>
                    @if ($order->coupon_code)
                        <span class="badge bg-primary ms-1">{{ $order->coupon_code }}</span>
                    @endif
                </span>
            @endif
            <span><i class="fas fa-money-bill-wave text-danger"></i> Tổng: <strong
                    style="color:#e11d48">{{ format_vnd($total) }} đ</strong></span>
        </div>
    </div>

    {{-- Hiển thị trạng thái trả hàng --}}
    @if ($order->returnStatus)
        <div class="alert alert-info mb-4">
            <h6 class="mb-2"><i class="fas fa-undo me-2"></i>Trạng thái trả hàng</h6>
            <span
                class="badge
                @switch($order->returnStatus->status)
                    @case('pending')
                        badge-return-pending
                        @break
                    @case('approved')
                        badge-return-approved
                        @break
                    @case('rejected')
                        badge-return-rejected
                        @break
                    @default
                        badge-secondary
                @endswitch
            ">
                {{ $order->returnStatus->statusText }}
            </span>
            @if ($order->returnStatus->admin_note)
                <div class="mt-2">
                    <small class="text-muted">Ghi chú: {{ $order->returnStatus->admin_note }}</small>
                </div>
            @endif
        </div>
    @endif



    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 p-3">
                <h5 class="mb-3"><i class="fas fa-shopping-bag me-2 text-success"></i> Sản phẩm trong đơn hàng</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Phân loại</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        @php
                                            $product = null;
                                            $productImages = collect();

                                            // Ưu tiên lấy từ variant trước
                                            if ($detail->variant && $detail->variant->product) {
                                                $product = $detail->variant->product;
                                                $productImages = $product->productImages ?? collect();
                                            }
                                            // Nếu không có variant, lấy trực tiếp từ product (và product_id > 0)
                                            elseif ($detail->product_id > 0 && $detail->product) {
                                                $product = $detail->product;
                                                $productImages = $product->productImages ?? collect();
                                            }
                                        @endphp

                                        @if ($product && $productImages->isNotEmpty())
                                            <img src="{{ asset('storage/' . $productImages->first()->image_path) }}"
                                                width="60" height="60" style="border-radius:8px; object-fit:cover;">
                                        @elseif($product && $product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" width="60"
                                                height="60" style="border-radius:8px; object-fit:cover;">
                                        @elseif($product)
                                            <div class="no-image"
                                                style="width:60px; height:60px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @else
                                            <div class="no-image"
                                                style="width:60px; height:60px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product)
                                            <div><strong>{{ $product->name }}</strong></div>
                                        @else
                                            <div class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Sản phẩm không còn tồn tại
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Hiển thị các biến thể (attribute values) --}}
                                        @if ($detail->variant && $detail->variant->attributeValues->count() > 0)
                                            @foreach ($detail->variant->attributeValues as $attributeValue)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $attributeValue->attribute->name }}: {{ $attributeValue->value }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ format_vnd($detail->product->sale_price) }} đ</td>
                                    <td>{{ format_vnd($detail->product->sale_price * $detail->quantity) }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4 p-3">
                <h5 class="mb-3"><i class="fas fa-receipt me-2 text-primary"></i> Lịch sử thanh toán</h5>
                <ul class="list-unstyled">
                    @php
                        $subtotal = $order->subtotal ?? $order->orderDetails->sum(fn($d) => $d->price * $d->quantity);
                        $actualDiscountAmount = 0;
                        if ($order->coupon_code && $order->coupon_discount > 0) {
                            if ($order->coupon_type == 'percentage') {
                                $actualDiscountAmount = ($subtotal * $order->coupon_discount) / 100;
                            } else {
                                $actualDiscountAmount = min($order->coupon_discount, $subtotal);
                            }
                        }
                        $total = $order->total_price;
                        // Thanh toán thành công nếu có payment completed hoặc COD & đơn completed
                        $isPaid =
                            $order->payments->where('status', 'completed')->count() > 0 ||
                            (optional($order->paymentMethod)->payment_type === 'COD' &&
                                ($order->status === 'completed' ||
                                    (is_numeric($order->status) && (string) $order->status === '5')));
                    @endphp

                    @if ($actualDiscountAmount > 0)
                        <li>
                            <i class="fas fa-calculator text-info"></i>
                            Tạm tính:
                            <span class="fw-bold">{{ format_vnd($subtotal) }} đ</span>
                        </li>
                        <li>
                            <i class="fas fa-tag text-success"></i>
                            Giảm giá
                            @if ($order->coupon_code)
                                ({{ $order->coupon_code }})
                            @endif
                            :
                            <span class="text-success fw-bold">-{{ format_vnd($actualDiscountAmount) }}
                                đ</span>
                        </li>
                    @endif

                    <li>
                        <i class="fas fa-money-bill-wave text-success"></i>
                        Tổng tiền thanh toán:
                        <span class="text-success fw-bold">{{ format_vnd($total) }} đ</span>
                        @php
                            $rawPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                            $isCOD = optional($order->paymentMethod)->payment_type === 'COD';
                            $isMomo = optional($order->paymentMethod)->payment_type === 'Ví Điện Tử MOMO';
                            $statusValue = is_numeric($order->status)
                                ? $statusMap[$order->status] ?? 'pending'
                                : $order->status;
                            $isPaid = $rawPaid || ($isCOD && $statusValue === 'completed');
                        @endphp

                        @switch($statusValue)
                            @case('refunded')
                            @case('returned')
                                {{-- Đơn đã trả hàng/hoàn tiền --}}
                                <span class="badge status-badge badge-refunded">
                                    Đã hoàn tiền vào ví
                                </span>
                            @break

                            @case('cancelled')
                                {{-- Đơn đã bị hủy --}}
                                <span class="badge status-badge {{ $isMomo ? 'badge-refunded' : 'badge-unpaid' }}">
                                    {{ $isMomo ? 'Đã hoàn tiền vào ví' : 'Chưa thanh toán' }}
                                </span>
                            @break

                            @default
                                <span class="badge status-badge {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                                    {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                        @endswitch

                    </li>
                    @php
                        $validPayments = $order->payments->whereNotNull('created_at');
                    @endphp
                    @forelse ($validPayments as $payment)
                        <li class="mb-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $payment->note ?? '' }})</small>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
            {{--
            <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-primary mb-3">
                <i class="fas fa-truck"></i> Theo dõi đơn hàng
            </a> --}}

            {{-- Cập nhật trạng thái đơn hàng --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-edit me-2"></i>Cập nhật trạng thái đơn hàng
                    </h5>

                    @php
                        // Define finalized statuses and check if order is finalized
                        $finalStatuses = ['completed', 'cancelled', 'approved', 'rejected'];
                        $isOrderFinalized = in_array($statusValue, $finalStatuses);
                        $isReturned = isset($order->returnStatus) && $order->returnStatus !== null;
                    @endphp

                    @if($isOrderFinalized || $isReturned)
                    @if ($isOrderFinalized)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            @if($isOrderFinalized)
                                Đơn hàng đã ở trạng thái cuối và không thể chỉnh sửa.
                            @elseif($isReturned)
                                Đơn hàng đã có yêu cầu trả hàng, không thể chỉnh sửa trạng thái.
                            @endif
                        </div>
                    @else
                        {{-- Alert container for AJAX responses --}}
                        <div id="alert-container"></div>

                        <form id="orderStatusForm" action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-light border mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Quy tắc cập nhật trạng thái:</strong><br>
                                    • <strong>Tiến:</strong> Chuyển sang trạng thái tiếp theo<br>
                                    • <strong>Lùi:</strong> Quay lại 1 trạng thái trước (xử lý sự cố như giao nhầm đơn)<br>
                                    • <strong>Hủy:</strong> Chỉ được hủy khi người dùng có yêu cầu hủy trước khi đang giao
                                    hàng (trước trạng thái "Đang giao")
                                </small>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="status" class="form-label">Chọn trạng thái mới</label>
                                    <select class="form-select" id="status" name="status" required>
                                        @php
                                            $statusesEdit = [
                                                'pending' => 'Chờ xử lý',
                                                'confirmed' => 'Đã xác nhận',
                                                'shipping' => 'Đã giao cho ĐVVC',
                                                'delivering' => 'Đang giao',
                                                'received' => 'Đã nhận',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy',
                                                'returning' => 'Đang trả hàng',
                                                'approved' => 'Đã trả hàng',
                                                'rejected' => 'Từ chối trả',
                                            ];

                                            $statusKeysEdit = array_keys($statusesEdit);
                                            $currentIndex = array_search($statusValue, $statusKeysEdit);
                                        @endphp

                                        {{-- Chỉ cho phép chọn trạng thái tiếp theo, hiện tại hoặc quay lại 1 trạng thái --}}
                                        @foreach ($statusesEdit as $value => $label)
                                            @php
                                                $optionIndex = array_search($value, $statusKeysEdit);
                                                $canSelect = false;

                                                // Chỉ cho phép chọn:
                                                // 1. Trạng thái hiện tại
                                                // 2. Trạng thái tiếp theo (currentIndex + 1)
                                                // 3. Trạng thái trước đó (currentIndex - 1) - để xử lý lỗi/sự cố
                                                // 4. Trạng thái hủy (nếu chưa completed)

                                                if ($optionIndex == $currentIndex) {
                                                    $canSelect = true; // Trạng thái hiện tại
                                                } elseif (
                                                    $optionIndex == $currentIndex + 1 &&
                                                    !in_array($value, $finalStatuses)
                                                ) {
                                                    $canSelect = true; // Trạng thái tiếp theo
                                                } elseif (
                                                    $optionIndex == $currentIndex - 1 &&
                                                    $currentIndex > 0 &&
                                                    !in_array($statusValue, ['pending', 'completed', 'cancelled'])
                                                ) {
                                                    $canSelect = true; // Cho phép quay lại 1 trạng thái (trừ pending và final statuses)
                                                } elseif (
                                                    $value === 'cancelled' &&
                                                    !in_array($statusValue, [
                                                        'delivering',
                                                        'received',
                                                        'completed',
                                                        'cancelled',
                                                        'approved',
                                                        'rejected',
                                                    ])
                                                ) {
                                                    $canSelect = true; // Cho phép hủy nếu chưa đang giao
                                                }
                                            @endphp

                                            @if ($canSelect)
                                                <option value="{{ $value }}"
                                                    {{ $statusValue == $value ? 'selected' : '' }}>
                                                    @if ($optionIndex == $currentIndex - 1 && $optionIndex >= 0)
                                                        {{ $label }} (Quay lại)
                                                    @else
                                                        {{ $label }}
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="updateStatusBtn">
                                        <i class="fas fa-save me-1"></i>Cập nhật trạng thái
                                    </button>
                                </div>
                            </div>

                            {{-- Trường hủy đơn (ẩn mặc định) --}}
                            <div id="cancelFields" style="display: none;" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="cancel_reason" class="form-label">Lý do hủy</label>
                                        <input type="text" class="form-control" id="cancel_reason"
                                            name="cancel_reason" maxlength="255"
                                            value="{{ old('cancel_reason', $order->cancel_reason) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cancel_note" class="form-label">Ghi chú hủy đơn</label>
                                        <textarea class="form-control" id="cancel_note" name="cancel_note" rows="2">{{ old('cancel_note', $order->cancel_note) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-title">Tóm tắt đơn hàng</div>
                <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
                <p><strong>Ngày dặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p><strong>Trạng thái:</strong>
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
                            ];
                            $statusValue = $statusMap[$statusValue] ?? 'pending';
                        }
                    @endphp

                    @if ($statusValue == 'pending')
                        <span class="badge bg-warning">Chờ xử lý</span>
                    @elseif($statusValue == 'confirmed')
                        <span class="badge bg-primary">Đã xác nhận</span>
                    @elseif($statusValue == 'shipping')
                        <span class="badge bg-info">Giao cho ĐVVC</span>
                    @elseif($statusValue == 'delivering')
                        <span class="badge bg-purple">Đang giao</span>
                    @elseif($statusValue == 'received')
                        <span class="badge bg-cyan">Đã nhận</span>
                    @elseif($statusValue == 'completed')
                        <span class="badge bg-success">Hoàn thành</span>
                    @elseif($statusValue == 'cancelled')
                        <span class="badge bg-danger">Đã hủy</span>
                    @else
                        <span class="badge bg-secondary">{{ $statusValue }}</span>
                    @endif
                </p>
                <hr>
                <div class="summary-title">Thông tin người đặt hàng</div>
                <p><strong>Người đặt:</strong><br>{{ $order->orderer_name ?? ($order->user->name ?? 'N/A') }}</p>
                <p><strong>Email:</strong> {{ $order->orderer_email ?? ($order->user->email ?? 'N/A') }}</p>
                <p><strong>SĐT:</strong> {{ $order->recipient_phone ?? 'N/A' }}</p>

                <hr>
                <div class="summary-title">Thông tin người nhận hàng</div>
                <p><strong>Người
                        nhận:</strong><br>{{ $order->recipient_name ?? ($order->orderer_name ?? ($order->user->name ?? 'N/A')) }}
                </p>
                <p><strong>SĐT:</strong> {{ $order->recipient_phone ?? ($order->orderer_phone ?? 'N/A') }}</p>
                <p><strong>Địa chỉ:</strong><br>{{ $order->recipient_address ?? ($order->address ?? 'N/A') }}</p>

                <hr>
                <p><strong>Phương thức thanh toán:</strong><br>{{ $order->paymentMethod->payment_type ?? 'N/A' }}</p>

                @php
                    $latestPayment = $order->payments->where('status', 'completed')->first();
                @endphp
                @if (
                    $latestPayment &&
                        $latestPayment->payment_method_type &&
                        in_array($latestPayment->payment_method_type, ['MoMo', 'ZaloPay']))
                    <div class="transaction-info mt-3 p-3"
                        style="background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #007bff;">
                        <p class="mb-2"><strong><i class="fas fa-receipt me-2"></i>Dữ liệu giao dịch:</strong></p>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><span
                                        class="badge bg-info">{{ $latestPayment->payment_method_type }}</span></p>
                                @if ($latestPayment->transaction_code)
                                    <p class="mb-1"><small class="text-muted">Mã giao dịch:</small><br>
                                        <strong class="text-primary">{{ $latestPayment->transaction_code }}</strong>
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($order->transaction_id)
                                    <p class="mb-1"><small class="text-muted">Mã đơn hàng:</small><br>
                                        <strong class="text-success">{{ $order->transaction_id }}</strong>
                                    </p>
                                @endif
                                @if ($latestPayment->confirmed_at)
                                    <p class="mb-0"><small class="text-muted">Thời gian GD:</small><br>
                                        <strong
                                            class="text-dark">{{ \Carbon\Carbon::parse($latestPayment->confirmed_at)->format('d/m/Y H:i:s') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($order->paymentMethod && $order->paymentMethod->payment_type == 'COD')
                    <div class="transaction-info mt-3 p-3"
                        style="background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #28a745;">
                        <p class="mb-2"><strong><i class="fas fa-money-bill-wave me-2"></i>Thông tin thanh
                                toán:</strong></p>
                        <span class="badge bg-success">Thanh toán khi nhận hàng (COD)</span>
                        <p class="mb-0 mt-2"><small class="text-muted">Khách hàng sẽ thanh toán trực tiếp cho
                                shipper</small></p>
                    </div>
                @endif

                @if ($order->coupon_code)
                    <p><strong>Mã giảm giá:</strong>
                        <span class="badge bg-success">{{ $order->coupon_code }}</span>
                        @if ($order->coupon_type == 'percentage')
                            ({{ $order->coupon_discount }}%)
                        @else
                            (-{{ format_vnd($order->coupon_discount) }} đ)
                        @endif
                    </p>
                @else
                    <p><strong>Mã giảm giá:</strong> Không áp dụng</p>
                @endif

                <p><strong>Ngày giao dự kiến:</strong> {{ $order->created_at->addDays(3)->format('d/m/Y') }}</p>
            </div>

            <div class="summary-card mt-3">
                <div class="summary-title">Chi tiết giao hàng</div>
                <p><strong>Người nhận:</strong>
                    {{ $order->recipient_name ?? ($order->orderer_name ?? ($order->user->name ?? 'N/A')) }}</p>
                <p><strong>Địa chỉ giao hàng:</strong><br>
                    {{ $order->recipient_address ?? ($order->address ?? 'N/A') }}
                </p>
                <p><strong>Số điện thoại:</strong> {{ $order->recipient_phone ?? ($order->orderer_phone ?? 'N/A') }}</p>
                <p><strong>Email liên hệ:</strong> {{ $order->orderer_email ?? ($order->user->email ?? 'N/A') }}</p>
                <p><strong>Phương thức vận chuyển:</strong> Giao hàng tận nơi</p>
                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Ngày giao dự kiến:</strong> {{ $order->created_at->addDays(3)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Tiến trình vận chuyển (timeline trạng thái) --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('orderStatusForm');
            const statusSelect = document.getElementById('status');
            const cancelFields = document.getElementById('cancelFields');
            const alertContainer = document.getElementById('alert-container');
            const updateBtn = document.getElementById('updateStatusBtn');

            if (!form || !statusSelect || !cancelFields || !alertContainer || !updateBtn) {
                return; // Exit if elements don't exist (order is finalized)
            }

            // Show/hide cancel fields based on status selection
            statusSelect.addEventListener('change', function() {
                const showCancelFields = this.value === 'cancelled';
                cancelFields.style.display = showCancelFields ? 'block' : 'none';

                // Make cancel fields required/optional based on visibility
                const cancelInputs = cancelFields.querySelectorAll('input, textarea');
                cancelInputs.forEach(input => {
                    input.required = showCancelFields;
                });
            });

            // AJAX form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const originalContent = updateBtn.innerHTML;
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang cập nhật...';
                updateBtn.disabled = true;

                // Clear previous alerts
                alertContainer.innerHTML = '';

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alertContainer.innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-1"></i>
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;

                            // Reload page after success to show updated status
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            // Show error messages
                            if (data.errors) {
                                let errorHtml =
                                    '<div class="alert alert-danger alert-dismissible fade show"><strong>Đã có lỗi xảy ra:</strong><ul class="mb-0 mt-2">';
                                Object.values(data.errors).flat().forEach(error => {
                                    errorHtml += `<li>${error}</li>`;
                                });
                                errorHtml +=
                                    '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                                alertContainer.innerHTML = errorHtml;
                            } else {
                                alertContainer.innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    ${data.message || 'Có lỗi xảy ra khi cập nhật đơn hàng!'}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                            }

                            // Restore button state
                            updateBtn.innerHTML = originalContent;
                            updateBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Có lỗi xảy ra khi cập nhật đơn hàng!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                        // Restore button state
                        updateBtn.innerHTML = originalContent;
                        updateBtn.disabled = false;
                    });
            });

            // Initialize cancel fields visibility
            statusSelect.dispatchEvent(new Event('change'));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleTimelineBtn');
            const toggleText = document.getElementById('toggleTimelineText');
            const extraItems = Array.from(document.querySelectorAll('#timelineList .timeline-item')).slice(2);
            if (toggleBtn && extraItems.length) {
                // set initial state as collapsed
                toggleBtn.dataset.expanded = 'false';
                // hide extra items
                extraItems.forEach(el => el.classList.add('timeline-hidden'));
                toggleBtn.addEventListener('click', function() {
                    const isExpanded = this.dataset.expanded === 'true';
                    extraItems.forEach(el => el.classList.toggle('timeline-hidden'));
                    if (isExpanded) {
                        toggleText.textContent = 'Xem thêm';
                        this.querySelector('i').className = 'fas fa-eye me-1';
                        this.dataset.expanded = 'false';
                    } else {
                        toggleText.textContent = 'Thu gọn';
                        this.querySelector('i').className = 'fas fa-eye-slash me-1';
                        this.dataset.expanded = 'true';
                    }
                });
            }
        });
    </script>

    <style>
        .rotating {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #cancelFields {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
        }

        .bg-purple {
            background-color: #8b5cf6 !important;
        }

        .bg-cyan {
            background-color: #06b6d4 !important;
        }
    </style>

@endsection
