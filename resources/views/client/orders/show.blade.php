@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary"><i class="fa-solid fa-receipt me-2"></i>Chi tiết đơn hàng #{{ $order->id }}</h2>
                <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Thông tin đơn hàng -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6"><strong>Mã đơn hàng:</strong></div>
                                <div class="col-6">#{{ $order->id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6"><strong>Ngày đặt:</strong></div>
                                <div class="col-6">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6"><strong>Trạng thái:</strong></div>
                                <div class="col-6">
                                    @php
                                        $statusValue = $order->status;
                                        if (is_numeric($statusValue)) {
                                            $statusMap = [
                                                '0' => 'pending', '1' => 'confirmed', '2' => 'shipping',
                                                '3' => 'delivering', '4' => 'received', '5' => 'completed'
                                            ];
                                            $statusValue = $statusMap[$statusValue] ?? 'pending';
                                        }
                                    @endphp
                                    
                                    @if($statusValue == 'pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    @elseif($statusValue == 'confirmed')
                                        <span class="badge bg-primary">Đã xác nhận</span>
                                    @elseif($statusValue == 'shipping')
                                        <span class="badge bg-info">Đã giao cho ĐVVC</span>
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
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6"><strong>Phương thức thanh toán:</strong></div>
                                <div class="col-6">{{ $order->paymentMethod->payment_type ?? 'N/A' }}</div>
                            </div>
                            @if($order->coupon_code)
                            <hr>
                            <div class="row">
                                <div class="col-6"><strong>Mã giảm giá:</strong></div>
                                <div class="col-6"><span class="badge bg-success">{{ $order->coupon_code }}</span></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Thông tin giao hàng -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-truck me-2"></i>Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4"><strong>Người nhận:</strong></div>
                                <div class="col-8">{{ $order->recipient_name }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4"><strong>Điện thoại:</strong></div>
                                <div class="col-8">{{ $order->recipient_phone }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4"><strong>Email:</strong></div>
                                <div class="col-8">{{ $order->recipient_email ?: 'Không có' }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4"><strong>Địa chỉ:</strong></div>
                                <div class="col-8">{{ $order->recipient_address }}</div>
                            </div>
                            @if($order->order_notes)
                            <hr>
                            <div class="row">
                                <div class="col-4"><strong>Ghi chú:</strong></div>
                                <div class="col-8">{{ $order->order_notes }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-box me-2"></i>Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>SKU</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        <img src="{{ asset($detail->variant && $detail->variant->image ? 'storage/' . $detail->variant->image : 'storage/' . $detail->product->image) }}" 
                                             width="60" class="rounded">
                                    </td>
                                    <td>
                                        <strong>{{ $detail->product->name }}</strong>
                                        @if($detail->variant)
                                            <br><small class="text-muted">{{ $detail->variant->attributes ?? '' }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $detail->variant->sku ?? $detail->product->sku ?? '-' }}</code></td>
                                    <td><span class="badge bg-secondary">{{ $detail->quantity }}</span></td>
                                    <td class="text-success">{{ number_format($detail->price) }} đ</td>
                                    <td class="text-danger fw-bold">{{ number_format($detail->price * $detail->quantity) }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                @if($order->subtotal && $order->discount_amount > 0)
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Tạm tính:</td>
                                    <td class="fw-bold text-info">{{ number_format($order->subtotal) }} đ</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold text-success">Giảm giá:</td>
                                    <td class="fw-bold text-success">-{{ number_format($order->discount_amount) }} đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="5" class="text-end fw-bold fs-5">Tổng thanh toán:</td>
                                    <td class="fw-bold text-warning fs-5">{{ number_format($order->total_price) }} đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="text-end mt-4">
                @if(in_array($statusValue, ['pending', 'confirmed']))
                <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" style="display:inline;" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?\n\nLưu ý: Chỉ có thể hủy khi đơn hàng đang ở trạng thái \'Chờ xử lý\' hoặc \'Đã xác nhận\'.')">
                    @csrf
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="fa-solid fa-times me-1"></i>Hủy đơn hàng
                    </button>
                </form>
                @endif
                <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-list me-1"></i>Danh sách đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #8b5cf6 !important;
}
.bg-cyan {
    background-color: #06b6d4 !important;
}
</style>
@endsection
