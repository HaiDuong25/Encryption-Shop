@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="fa-solid fa-cart-shopping me-2"></i>Thanh toán</h2>

    @if($carts->count() > 0)
    <form action="{{ route('cart.processCheckout') }}" method="POST">
        @csrf
        
        <!-- Sản phẩm thanh toán - Di chuyển lên đầu -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-box-open me-2"></i>Sản phẩm thanh toán</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Thương hiệu</th>
                                        <th>SKU</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carts as $cart)
                                    <tr>
                                        <td><img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}" width="60" class="rounded"></td>
                                        <td class="text-start">{{ $cart->product->name }}</td>
                                        <td>{{ $cart->product->category->name ?? $cart->product->category_id }}</td>
                                        <td>{{ $cart->product->brand->name ?? $cart->product->brand_id }}</td>
                                        <td><code>{{ $cart->variant->sku ?? $cart->product->sku ?? '-' }}</code></td>
                                        <td><span class="badge bg-secondary">{{ $cart->quantity }}</span></td>
                                        <td class="text-success fw-semibold">{{ number_format($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) }} đ</td>
                                        <td class="text-danger fw-bold">{{ number_format(($cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity) }} đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="7" class="text-end fw-bold fs-6">Tạm tính:</td>
                                        <td class="fw-bold text-info fs-6">{{ number_format($totals['subtotal']) }} đ</td>
                                    </tr>
                                    @if($totals['discount'] > 0)
                                    <tr>
                                        <td colspan="7" class="text-end fw-bold fs-6 text-success">Giảm giá:</td>
                                        <td class="fw-bold text-success fs-6">-{{ number_format($totals['discount']) }} đ</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="7" class="text-end fw-bold fs-5">Tổng thanh toán:</td>
                                        <td class="fw-bold text-warning fs-5">{{ number_format($totals['total']) }} đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin người đặt hàng -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-user me-2"></i>Thông tin người đặt hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và Tên</label>
                            <input type="text" name="orderer_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            <small class="text-muted">Thông tin tài khoản đăng nhập</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="orderer_email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="orderer_phone" class="form-control" value="{{ Auth::user()->phone ?? '' }}" placeholder="Cập nhật số điện thoại">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin người nhận hàng -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>Thông tin người nhận hàng</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sameAsOrderer">
                            <label class="form-check-label text-white" for="sameAsOrderer">
                                Giống người đặt
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và Tên người nhận</label>
                            <input type="text" name="recipient_name" class="form-control" id="recipient_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số điện thoại người nhận</label>
                            <input type="text" name="recipient_phone" class="form-control" id="recipient_phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email người nhận</label>
                            <input type="email" name="recipient_email" class="form-control" id="recipient_email" placeholder="Email người nhận (tùy chọn)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Địa chỉ nhận hàng</label>
                            <textarea name="recipient_address" class="form-control" id="recipient_address" rows="2" required placeholder="Nhập địa chỉ chi tiết..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú đặc biệt</label>
                            <textarea name="order_notes" class="form-control" rows="2" placeholder="Ghi chú cho đơn hàng (tùy chọn)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Phương thức thanh toán - Mở rộng toàn bộ chiều rộng -->
            <div class="col-12 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <h4 class="mb-0 text-center"><i class="fa-solid fa-credit-card me-2"></i>Phương thức thanh toán & Đặt hàng</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Voucher -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold fs-6"><i class="fa-solid fa-ticket me-2 text-warning"></i>Mã giảm giá</label>
                                    
                                    @php $appliedCoupon = session('applied_coupon'); @endphp
                                    
                                    @if($appliedCoupon)
                                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                                            <span><i class="fa-solid fa-check-circle me-1"></i> <strong>{{ $appliedCoupon['code'] }}</strong> đã được áp dụng</span>
                                            <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hủy</button>
                                            </form>
                                        </div>
                                        <small class="text-success">Tiết kiệm: {{ number_format($totals['discount']) }} đ</small>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fa-solid fa-info-circle me-1"></i>
                                            Bạn có thể nhập mã giảm giá ở <a href="{{ route('cart.index') }}" class="text-decoration-none">trang giỏ hàng</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Phương thức vận chuyển -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold fs-6"><i class="fa-solid fa-truck me-2 text-primary"></i>Phương thức vận chuyển</label>
                                    <select name="payment_method_id" class="form-select form-select-lg" required>
                                        <option value="">-- Chọn phương thức vận chuyển --</option>
                                        @foreach($payment_methods as $method)
                                        <option value="{{ $method->id }}">
                                            <i class="fa-solid fa-check-circle me-1"></i>{{ $method->payment_type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tổng tiền và nút đặt hàng -->
                        <div class="text-center mt-4">
                            <div class="mb-3">
                                @if($totals['discount'] > 0)
                                    <div class="mb-2">
                                        <span class="fs-6 text-muted">Tạm tính: {{ number_format($totals['subtotal']) }} đ</span>
                                        <br>
                                        <span class="fs-6 text-success">Giảm giá: -{{ number_format($totals['discount']) }} đ</span>
                                    </div>
                                @endif
                                <span class="fs-4 fw-bold text-danger">Tổng thanh toán: {{ number_format($totals['total']) }} đ</span>
                            </div>
                            <button type="submit" class="btn btn-lg px-5 py-3 fw-bold fs-5 shadow-lg" 
                                    style="background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; border: none; border-radius: 15px; min-width: 280px;">
                                <i class="fa-solid fa-shopping-cart me-2"></i>
                                ĐẶT HÀNG NGAY
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    @else
    <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sameAsOrdererCheckbox = document.getElementById('sameAsOrderer');
    const ordererName = document.querySelector('input[name="orderer_name"]');
    const ordererPhone = document.querySelector('input[name="orderer_phone"]');
    const ordererEmail = document.querySelector('input[name="orderer_email"]');
    
    const recipientName = document.getElementById('recipient_name');
    const recipientPhone = document.getElementById('recipient_phone');
    const recipientEmail = document.getElementById('recipient_email');

    sameAsOrdererCheckbox.addEventListener('change', function() {
        if (this.checked) {
            recipientName.value = ordererName.value;
            recipientPhone.value = ordererPhone.value;
            recipientEmail.value = ordererEmail.value;
            
            // Disable recipient fields
            recipientName.readOnly = true;
            recipientPhone.readOnly = true;
            recipientEmail.readOnly = true;
        } else {
            recipientName.value = '';
            recipientPhone.value = '';
            recipientEmail.value = '';
            
            // Enable recipient fields
            recipientName.readOnly = false;
            recipientPhone.readOnly = false;
            recipientEmail.readOnly = false;
        }
    });

    // Update recipient fields when orderer phone changes
    ordererPhone.addEventListener('input', function() {
        if (sameAsOrdererCheckbox.checked) {
            recipientPhone.value = this.value;
        }
    });
});
</script>
@endsection
