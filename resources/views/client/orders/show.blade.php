@extends('client.layout.main')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<style>
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-stars input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .rating-stars label {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-stars input[type="radio"]:checked~label,
    .rating-stars label:hover,
    .rating-stars label:hover~label {
        color: #f5c518;
        /* vàng sao */
    }

    .existing-stars {
        color: #f5c518;
        font-size: 18px;
    }

    .status-badge {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 4px;
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
        }    .badge-paid {
        background-color: #28a745;
        color: #fff;
    }

    .badge-unpaid {
        background-color: #6c757d;
        color: #fff;
    }

    .bg-purple {
        background-color: #8b5cf6 !important;
    }

    .bg-cyan {
        background-color: #06b6d4 !important;
    }

    /* Timeline toggle styles */
    .timeline-item {
        display: flex;
        margin-bottom: 1rem;
    }
    .timeline-hidden {
        display: none !important;
    }
</style>

    <div class="container py-4">
        @php
            $statuses = [
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đã giao cho ĐVVC',
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
            $statusValue = is_numeric($order->status)
                ? $statusMap[(string) $order->status] ?? 'pending'
                : $order->status;
            $statusKeys = array_keys($statuses);
            $currentStatusIndex = array_search($statusValue, $statusKeys);
            $isCancelled = $statusValue === 'cancelled';
            $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
        @endphp
        <div class="mb-4">
            @php
                $trackerSteps = [
                    'pending' => 'Chờ xử lý',
                    'confirmed' => 'Đã xác nhận',
                    'shipping' => 'Giao cho ĐVVC',
                    'delivering' => 'Đang giao',
                    'received' => 'Đã nhận',
                    'completed' => 'Hoàn thành',

                ];
                $trackerKeys = array_keys($trackerSteps);
                $currentStep = array_search($statusValue, $trackerKeys);
            @endphp
            @if ($statusValue === 'cancelled')
                <div class="alert alert-danger text-center mb-2">
                    <i class="fas fa-times-circle me-1"></i> Đơn hàng đã bị hủy
                </div>
            @else
                <div class="progress" style="height: 10px;">
                    @foreach ($trackerSteps as $key => $label)
                        <div class="progress-bar {{ array_search($key, $trackerKeys) <= $currentStep ? 'bg-success' : 'bg-secondary' }}"
                            style="width: {{ 100 / count($trackerSteps) }}%"></div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between mt-2 small">
                    @foreach ($trackerSteps as $key => $label)
                        <div class="text-center {{ array_search($key, $trackerKeys) <= $currentStep ? 'text-success fw-bold' : 'text-muted' }}"
                            style="width: {{ 100 / count($trackerSteps) }}%">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    {{-- Timeline chi tiết lịch sử trạng thái --}}
    @if (!$isCancelled && $order->statusHistories && $order->statusHistories->count())
        @php
            $clientHist = $order->statusHistories->sortByDesc('created_at')->values();
        @endphp
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Tiến trình vận chuyển</h5>
                    @if ($clientHist->count() > 2)
                        <button class="btn btn-sm btn-outline-primary" id="toggleTimelineBtn" type="button">
                            <i class="fas fa-eye me-1"></i>
                            <span id="toggleTimelineText">Xem thêm</span>
                        </button>
                    @endif
                </div>
                <ul class="list-unstyled" id="timelineList">
                    @foreach ($clientHist as $i => $history)
                        <li class="mb-3 d-flex timeline-item {{ $i >= 2 ? 'timeline-hidden' : '' }}">
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
                                        <br><i class="fas fa-user me-1"></i>Thực hiện bởi: <strong>{{ $history->user->name ?? 'N/A' }}</strong>
                                    @elseif($history->changed_by)
                                        <br><i class="fas fa-user me-1"></i>Thực hiện bởi: <strong>User ID {{ $history->changed_by }}</strong>
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
        <div class="row g-4">
            <div class="col-lg-5 col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fw-bold mb-0 flex-grow-1">Đơn hàng #{{ $order->id }}</h4>
                            @if ($isCancelled)
                                <span class="badge bg-danger">Đã hủy</span>
                            @elseif($statusValue == 'pending')
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
                            @else
                                <span class="badge bg-secondary">{{ $statusValue }}</span>
                            @endif
                        </div>
                        <div class="mb-2 text-muted small"><i class="fas fa-calendar-alt me-1"></i> Ngày đặt:
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>

                        {{-- Hiển thị trạng thái trả hàng --}}
                        @if ($order->returnStatus)
                            <div class="mb-2">
                                <strong>Trạng thái trả hàng:</strong>
                                <span class="badge 
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
                                            bg-secondary
                                    @endswitch
                                ">
                                    {{ $order->returnStatus->statusText }}
                                </span>
                                @if ($order->returnStatus->admin_note)
                                    <div class="mt-1">
                                        <small class="text-muted">Ghi chú: {{ $order->returnStatus->admin_note }}</small>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="mb-2"><strong>Trạng thái thanh toán:</strong>
                            @php
                                $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                                $isCOD = optional($order->paymentMethod)->payment_type === 'COD';
                                $isMomo = optional($order->paymentMethod)->payment_type === 'Ví Điện Tử MOMO';
                            @endphp
                            @switch($statusValue)
                                @case('refunded')
                                @case('returned')
                                    <span class="badge status-badge {{ $isMomo ? 'badge-refunded' : 'badge-unpaid' }}">
                                        {{ $isMomo ? 'Đã hoàn tiền' : 'Chưa thanh toán' }}
                                    </span>
                                @break

                                @default
                                    <span class="badge status-badge {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                                        {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                    </span>
                            @endswitch
                        </div>
                        <div class="mb-2"><strong>Phương thức thanh toán:</strong>
                            {{ optional($order->paymentMethod)->payment_type ?? 'Chưa chọn' }}
                        </div>
                        <div class="mb-2"><strong>Phương thức vận chuyển:</strong>
                            {{ $order->shipping_method ?? 'Giao hàng tận nơi' }}
                        </div>
                        <div class="mb-2"><strong>Ngày giao dự kiến:</strong>
                            {{ $order->created_at->addDays(3)->format('d/m/Y') }}
                        </div>
                        @php
                            $returnStatus = $order->returnStatus;
                        @endphp
                        @if($returnStatus && $returnStatus->overall_status !== 'none')
                            <div class="mb-2"><strong>Trạng thái trả hàng:</strong>
                                @switch($returnStatus->overall_status)
                                    @case('partial')
                                        <span class="badge bg-warning text-dark">Trả hàng một phần</span>
                                        @break
                                    @case('full')
                                        <span class="badge bg-info">Trả hàng toàn bộ</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn tất trả hàng</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $returnStatus->status_text }}</span>
                                @endswitch
                            </div>
                        @endif
                        <hr>
                        <div class="mb-2"><strong>Người nhận:</strong>
                            {{ $order->recipient_name ?? $order->orderer_name }}
                        </div>
                        <div class="mb-2"><strong>SĐT:</strong> {{ $order->recipient_phone ?? $order->orderer_phone }}
                        </div>
                        <div class="mb-2"><strong>Địa chỉ:</strong> {{ $order->recipient_address }}</div>
                        <div class="mb-2"><strong>Email:</strong>
                            {{ $order->orderer_email ?? ($order->user->email ?? 'N/A') }}
                        </div>
                        @if ($isCancelled)
                            <div class="alert alert-danger mt-3 mb-0 p-2 small">
                                <div><strong>Lý do hủy:</strong> {{ $order->cancel_reason ?? 'Không có' }}</div>
                                <div><strong>Ghi chú:</strong> {{ $order->cancel_note ?? 'Không có' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Thông tin thanh toán</h5>
                        <div class="mb-2">Tạm tính: <strong>{{ format_vnd($order->subtotal) }}₫</strong></div>
                        @if ($order->coupon || $order->coupon_code)
                            <div class="mb-2">
                                Mã giảm giá:
                                <span class="badge bg-success">{{ $order->coupon->code ?? $order->coupon_code }}</span>
                                @php
                                    $discountValue = $order->coupon->discount ?? $order->coupon_discount;
                                    $discountType = $order->coupon->discount_type ?? ($order->coupon_type ?? null);
                                @endphp
                                @if (!empty($discountValue))
                                    -
                                    @if ($discountType === 'percentage')
                                        {{ rtrim(rtrim($discountValue, '0'), '.') }}%
                                    @else
                                        {{ format_vnd($discountValue) }}₫
                                    @endif
                                @endif
                            </div>
                            <div class="mb-2">Số tiền giảm:
                                <strong>-{{ format_vnd($order->coupon_discount) }}₫</strong>
                            </div>
                        @endif
                        <div class="alert alert-warning fw-bold mt-3 mb-2">
                            Tổng tiền: {{ format_vnd($order->total_price) }}₫
                        </div>
                    </div>

                </div>

                <div class="d-flex gap-2 justify-content-between justify-content-lg-end mt-3 flex-wrap">
                    @if (!$isCancelled && in_array($statusValue, ['pending', 'confirmed', 'shipping']))
                        <button class="btn btn-outline-danger flex-grow-1 flex-lg-grow-0" data-bs-toggle="modal"
                            data-bs-target="#cancelModal">
                            <i class="fas fa-times-circle me-1"></i> Hủy đơn hàng
                        </button>
                        <!-- Modal Hủy đơn hàng -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST"
                                    class="w-100">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger bg-opacity-10 border-0">
                                            <h5 class="modal-title w-100 text-center text-danger fw-bold d-flex align-items-center justify-content-center gap-2"
                                                id="cancelModalLabel">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Hủy đơn hàng
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body px-4 py-3">
                                            <div class="mb-3">
                                                <label for="reason" class="form-label fw-semibold">Lý do hủy đơn hàng
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select" name="cancel_reason" id="reason" required>
                                                    <option value="">-- Chọn lý do --</option>
                                                    <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                                                    <option value="Muốn thay đổi địa chỉ">Muốn thay đổi địa chỉ</option>
                                                    <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                                                    <option value="Lý do khác">Lý do khác</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="note" class="form-label fw-semibold">Ghi chú thêm <span
                                                        class="text-muted">(không bắt buộc)</span></label>
                                                <textarea class="form-control" name="note" id="note" rows="2" placeholder="Ghi chú nếu có..."></textarea>
                                            </div>
                                            <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mb-0"
                                                role="alert">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Bạn chỉ có thể hủy đơn khi đơn hàng chưa được giao cho đơn vị vận
                                                    chuyển.</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 justify-content-between px-4 pb-4">
                                            <button type="button" class="btn btn-outline-secondary px-4"
                                                data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-danger px-4 fw-bold">
                                                <i class="fas fa-times-circle me-1"></i> Xác nhận hủy
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Nút xác nhận hoàn thành đơn hàng trong view show --}}
                    @if ($order->canComplete())
                        <button type="button" class="btn btn-success flex-grow-1 flex-lg-grow-0 me-2 confirm-complete-btn"
                            data-order-id="{{ $order->id }}"
                            data-message="Bạn có chắc chắn muốn xác nhận hoàn thành đơn hàng này không?">
                            <i class="fas fa-check-circle me-1"></i> Xác nhận hoàn thành
                        </button>
                    @endif
                    
                    <a href="{{ route('client.orders.index') }}"
                        class="btn btn-outline-secondary flex-grow-1 flex-lg-grow-0">
                        ← Quay lại danh sách đơn hàng
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-box me-2"></i>Sản phẩm trong đơn hàng</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $item)
                                        @php
                                            $product = $item->variant->product ?? $item->product;
                                            $image = $product->image ?? null;
                                            $imageUrl = $image
                                                ? (Str::startsWith($image, ['http://', 'https://'])
                                                    ? $image
                                                    : asset('storage/' . $image))
                                                : 'https://via.placeholder.com/80?text=No+Image';

                                            // Kiểm tra xem người dùng đã đánh giá cho order_detail_id này chưa
                                            $hasRated = $product
                                                ->rates()
                                                ->where('user_id', auth()->id())
                                                ->where('order_detail_id', $item->id)
                                                ->exists();
                                        @endphp
                                        <tr>
                                            <td><img src="{{ $imageUrl }}" width="80" class="rounded"></td>

                                            <td class="align-top">
                                                {{-- Tên và danh mục sản phẩm --}}
                                                <div class="fw-bold text-dark fs-6">
                                                    {{ $product->name ?? 'Sản phẩm đã xóa' }}
                                                </div>
                                                <div class="text-muted small mb-1">
                                                    {{ optional($product->category)->name ?? 'Danh mục đã xóa' }}
                                                </div>

                                                {{-- Mã SKU --}}
                                                @if ($item->variant && $item->variant->sku)
                                                    <div class="text-muted small">Mã SKU: <span
                                                            class="fw-semibold">{{ $item->variant->sku }}</span></div>
                                                @endif

                                                {{-- Hiển thị các thuộc tính biến thể (Size, Màu, Khác) --}}
                                                @if ($item->variant && $item->variant->attributeValues && $item->variant->attributeValues->count())
                                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                                        @foreach ($item->variant->attributeValues as $attrValue)
                                                            @php
                                                                $attrName = strtolower($attrValue->attribute->name);
                                                            @endphp
                                                            @if ($attrName === 'màu')
                                                                <span class="badge text-dark border"
                                                                    style="background-color: #e3f2fd; border-color: #2196f3;">
                                                                    🎨 Màu: {{ $attrValue->value }}
                                                                </span>
                                                            @elseif ($attrName === 'size')
                                                                <span class="badge text-dark border"
                                                                    style="background-color: #fff3e0; border-color: #fb8c00;">
                                                                    📏 Size: {{ $attrValue->value }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-dark border">
                                                                    {{ $attrValue->attribute->name }}:
                                                                    {{ $attrValue->value }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Giá và số lượng --}}
                                                <div class="mt-2 small">
                                                    Giá: <strong>{{ format_vnd($item->price) }}₫</strong> x
                                                    {{ $item->quantity }}
                                                </div>

                                                {{-- Đánh giá nếu đơn đã hoàn thành --}}
                                                @php
                                                    // Chỉ hiển thị đánh giá khi:
                                                    // 1. Đơn hàng đã hoàn thành
                                                    // 2. Sản phẩm không được duyệt trả hàng (approved)
                                                    // 3. Nếu bị từ chối trả hàng thì phải đợi đơn hoàn thành mới cho đánh giá
                                                    $canRate = $statusValue === 'completed' && 
                                                               $item->return_status !== 'approved' && 
                                                               Auth::check();
                                                @endphp
                                                @if ($canRate)
                                                    @if (!$hasRated)
                                                        <form
                                                            action="{{ route('client.rates.store', [$product->id, $item->id]) }}"
                                                            method="POST" class="mt-2 rating-form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <label class="small">Đánh giá của bạn:</label>
                                                                <div class="rating-stars">
                                                                    @for ($i = 5; $i >= 1; $i--)
                                                                        <input type="radio" name="score"
                                                                            id="star{{ $i }}-{{ $item->id }}"
                                                                            value="{{ $i }}">
                                                                        <label
                                                                            for="star{{ $i }}-{{ $item->id }}"
                                                                            title="{{ $i }} sao">★</label>
                                                                    @endfor
                                                                </div>
                                                                <div class="rating-error text-danger small" style="display: none;">
                                                                    Vui lòng chọn số sao đánh giá
                                                                </div>
                                                                @error('score')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-2">
                                                                <label class="small">Nội dung đánh giá:</label>
                                                                <textarea name="content" class="form-control form-control-sm" rows="2" placeholder="Nhận xét của bạn..."></textarea>
                                                                @error('content')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <button type="submit" class="btn btn-primary btn-sm">Gửi đánh
                                                                giá</button>
                                                        </form>
                                                    @else
                                                        <div class="mt-2 alert alert-success p-2 small">
                                                            <i class="fas fa-check-circle me-1"></i> Bạn đã đánh giá sản
                                                            phẩm này.
                                                        </div>
                                                    @endif
                                                @endif

                                                {{-- Trả hàng nếu đơn hàng đã được nhận và sản phẩm chưa có yêu cầu trả hàng --}}
                                                @php
                                                    // Kiểm tra xem đơn hàng có thể trả hàng chưa (dựa trên trạng thái giao hàng)
                                                    // Không thể trả hàng nếu đơn hàng đã hoàn thành
                                                    $canReturn = $order->canReturn() && $statusValue !== 'completed';
                                                @endphp
                                                @if ($canReturn && Auth::check() && $item->return_status === 'none')
                                                    <a href="{{ route('client.returns.create', ['order_detail_id' => $item->id]) }}"
                                                        class="btn btn-sm bg-secondary text-white mt-1">Trả hàng</a>
                                                @elseif($item->return_status === 'pending')
                                                    <div class="mt-2 alert alert-warning p-2 small">
                                                        <i class="fas fa-clock me-1"></i> Đang chờ duyệt trả hàng
                                                    </div>
                                                @elseif($item->return_status === 'approved')
                                                    <div class="mt-2 alert alert-success p-2 small">
                                                        <i class="fas fa-check-circle me-1"></i> Đã được duyệt trả hàng
                                                    </div>
                                                @elseif($item->return_status === 'rejected')
                                                    {{-- Chỉ hiển thị thông báo từ chối nếu đơn hàng chưa hoàn thành --}}
                                                    @if($statusValue !== 'completed')
                                                        <div class="mt-2 alert alert-danger p-2 small">
                                                            <i class="fas fa-times-circle me-1"></i> Yêu cầu trả hàng bị từ chối
                                                        </div>
                                                        {{-- Nút xác nhận hoàn thành cho sản phẩm bị từ chối trả hàng --}}
                                                        @if($statusValue === 'received' && $order->canComplete())
                                                            <button type="button" class="btn btn-success btn-sm mt-1 confirm-complete-btn"
                                                                data-order-id="{{ $order->id }}"
                                                                data-message="Xác nhận hoàn thành đơn hàng này? Sản phẩm bị từ chối trả hàng sẽ được coi là hoàn thành.">
                                                                <i class="fas fa-check-circle me-1"></i> Xác nhận hoàn thành
                                                            </button>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-end fw-bold">{{ format_vnd($item->total_price) }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal xác nhận hoàn thành -->
<div class="modal fade" id="confirmCompleteModal" tabindex="-1" aria-labelledby="confirmCompleteModalLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;">
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

<script>
// Function để hiển thị alert
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Function để hiển thị modal xác nhận hoàn thành
function showConfirmCompleteModal(message, onConfirm) {
    const modal = new bootstrap.Modal(document.getElementById('confirmCompleteModal'));
    const confirmMessage = document.getElementById('confirmCompleteMessage');
    const confirmButton = document.getElementById('confirmCompleteButton');
    
    // Cập nhật nội dung modal
    confirmMessage.textContent = message;
    
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

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleTimelineBtn');
    const toggleText = document.getElementById('toggleTimelineText');
    const extra = Array.from(document.querySelectorAll('#timelineList .timeline-item')).slice(2);
    if (toggleBtn && extra.length) {
        toggleBtn.dataset.expanded = 'false';
        extra.forEach(el => el.classList.add('timeline-hidden'));
        toggleBtn.addEventListener('click', function() {
            const isExp = this.dataset.expanded === 'true';
            extra.forEach(el => el.classList.toggle('timeline-hidden'));
            if (isExp) {
                toggleText.textContent = 'Xem thêm'; this.querySelector('i').className = 'fas fa-eye me-1'; this.dataset.expanded = 'false';
            } else {
                toggleText.textContent = 'Thu gọn'; this.querySelector('i').className = 'fas fa-eye-slash me-1'; this.dataset.expanded = 'true';
            }
        });
    }
    
    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Xử lý validation cho form đánh giá
    document.querySelectorAll('.rating-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const ratingInputs = this.querySelectorAll('input[name="score"]');
            const ratingError = this.querySelector('.rating-error');
            let isRatingSelected = false;
            
            // Kiểm tra xem có rating nào được chọn không
            ratingInputs.forEach(input => {
                if (input.checked) {
                    isRatingSelected = true;
                }
            });
            
            // Nếu không có rating nào được chọn, hiển thị lỗi và ngăn submit
            if (!isRatingSelected) {
                e.preventDefault();
                ratingError.style.display = 'block';
                return false;
            } else {
                ratingError.style.display = 'none';
            }
        });
        
        // Ẩn thông báo lỗi khi người dùng chọn rating
        const ratingInputs = form.querySelectorAll('input[name="score"]');
        const ratingError = form.querySelector('.rating-error');
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                ratingError.style.display = 'none';
            });
        });
    });
    
    // Xử lý nút xác nhận hoàn thành
    document.querySelectorAll('.confirm-complete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const message = this.dataset.message;
            
            showConfirmCompleteModal(message, () => {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                this.disabled = true;
                
                if (!csrfToken) {
                    showAlert('Lỗi CSRF token không tìm thấy!', 'danger');
                    this.innerHTML = originalText;
                    this.disabled = false;
                    return;
                }
                
                // Tạo form ẩn để submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/lich-su-don-hang/${orderId}/confirm`;
                form.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            });
        });
    });
});
</script>
@endsection
