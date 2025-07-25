@extends('client.layout.main')

@section('title', 'Chi tiết đơn hàng')
<style>
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-stars input[type="radio"] {
        display: none;
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
</style>
@section('content')
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
            @if ($isCancelled)
                <div class="alert alert-danger text-center mb-2"><i class="fas fa-times-circle me-1"></i> Đơn hàng đã bị hủy
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
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-shipping-fast me-2"></i>Tiến trình vận chuyển</h5>
                    <ul class="list-unstyled">
                        @foreach ($order->statusHistories->sortByDesc('created_at') as $history)
                            <li class="mb-3 d-flex">
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
                                        {{ $history->created_at->format('H:i d/m/Y') }}
                                        @if ($history->description)
                                            <br><span>{{ $history->description }}</span>
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
                        <div class="mb-2"><strong>Trạng thái thanh toán:</strong>
                            @if ($isPaid)
                                <span class="badge bg-success">Đã thanh toán</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                            @endif
                        </div>
                        <div class="mb-2"><strong>Phương thức thanh toán:</strong>
                            {{ $order->paymentMethod->payment_type ?? 'Chưa chọn' }}
                        </div>
                        <div class="mb-2"><strong>Phương thức vận chuyển:</strong>
                            {{ $order->shipping_method ?? 'Giao hàng tận nơi' }}
                        </div>
                        <div class="mb-2"><strong>Ngày giao dự kiến:</strong>
                            {{ $order->created_at->addDays(3)->format('d/m/Y') }}
                        </div>
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
                        <div class="mb-2">Tạm tính: <strong>{{ number_format($order->subtotal) }}₫</strong></div>
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
                                        {{ number_format($discountValue, 0, ',', '.') }}₫
                                    @endif
                                @endif
                            </div>
                            <div class="mb-2">Số tiền giảm:
                                <strong>-{{ number_format($order->coupon_discount, 0, ',', '.') }}₫</strong>
                            </div>
                        @endif
                        <div class="alert alert-warning fw-bold mt-3 mb-2">
                            Tổng tiền: {{ number_format($order->total_price) }}₫
                        </div>
                    </div>

                </div>

                <div class="d-flex gap-2 justify-content-between justify-content-lg-end mt-3 flex-wrap">
                    @if (!$isCancelled && in_array($statusValue, ['pending', 'confirmed']))
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
                                            $image = $item->variant->product->image ?? null;
                                            $imageUrl = $image
                                                ? (Str::startsWith($image, ['http://', 'https://'])
                                                    ? $image
                                                    : asset('storage/' . $image))
                                                : 'https://via.placeholder.com/80?text=No+Image';

                                            // Kiểm tra xem người dùng đã đánh giá cho order_detail_id này chưa
                                            $hasRated = $item->variant->product
                                                ->rates()
                                                ->where('user_id', auth()->id())
                                                ->where('order_detail_id', $item->id)
                                                ->exists();
                                        @endphp
                                        <tr>
                                            <td><img src="{{ $imageUrl }}" width="80" class="rounded"></td>
                                            <td>
                                                <strong>{{ $item->variant->product->name ?? 'Sản phẩm đã xóa' }}</strong><br>
                                                <small>Phân loại: {{ $item->variant->name ?? 'Mặc định' }}</small><br>
                                                <small>Giá: {{ number_format($item->price) }}₫ x
                                                    {{ $item->quantity }}</small>

                                                @if ($statusValue == 'completed')
                                                    @if (!$hasRated)
                                                        {{-- Form đánh giá --}}
                                                        <form
                                                            action="{{ route('client.rates.store', [$item->variant->product->id, $item->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <label class="small">Đánh giá của bạn:</label>
                                                                <div class="rating-stars">
                                                                    @for ($i = 5; $i >= 1; $i--)
                                                                        <input type="radio" name="score"
                                                                            id="star{{ $i }}-{{ $item->id }}"
                                                                            value="{{ $i }}" required>
                                                                        <label
                                                                            for="star{{ $i }}-{{ $item->id }}"
                                                                            title="{{ $i }} sao">★</label>
                                                                    @endfor
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
                                             @if ($statusValue === 'received' && !$item->returnRequest)
    <a href="{{ route('client.returns.create', ['order_detail_id' => $item->id]) }}"
        class="btn btn-warning btn-sm mt-1">
        Trả hàng
    </a>
@endif

                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($item->total_price) }}₫</td>
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

@endsection
