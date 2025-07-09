@extends('client.layout.main')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="fa-solid fa-cart-shopping me-2"></i>Thanh toán</h2>

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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                                        <td class="fw-bold text-info fs-6" id="subtotal-amount">{{ number_format($totals['subtotal']) }} đ</td>
                                    </tr>
                                    <tr id="discount-row" style="{{ $totals['discount'] > 0 ? '' : 'display: none;' }}">
                                        <td colspan="7" class="text-end fw-bold fs-6 text-success">Giảm giá:</td>
                                        <td class="fw-bold text-success fs-6" id="discount-amount">-{{ number_format($totals['discount']) }} đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-end fw-bold fs-5">Tổng thanh toán:</td>
                                        <td class="fw-bold text-warning fs-5" id="total-amount">{{ number_format($totals['total']) }} đ</td>
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
                                <div class="mb-4" id="coupon-section">
                                    <label class="form-label fw-bold fs-6"><i class="fa-solid fa-ticket me-2 text-warning"></i>Mã giảm giá</label>
                                    
                                    @php $appliedCoupon = session('applied_coupon'); @endphp
                                    
                                    <div id="coupon-content">
                                        @if($appliedCoupon)
                                            <div class="alert alert-success d-flex justify-content-between align-items-center" id="applied-coupon-alert">
                                                <span><i class="fa-solid fa-check-circle me-1"></i> <strong id="applied-coupon-code">{{ $appliedCoupon['code'] }}</strong> đã được áp dụng</span>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">Hủy</button>
                                            </div>
                                            <small class="text-success" id="coupon-savings">Tiết kiệm: {{ number_format($totals['discount']) }} đ</small>
                                        @else
                                            <div class="input-group mb-2" id="coupon-input-group">
                                                <input type="text" id="coupon_code_input" class="form-control" placeholder="Nhập mã giảm giá..." maxlength="50" onkeypress="if(event.key==='Enter') applyCoupon()">
                                                <button type="button" class="btn btn-outline-primary" onclick="applyCoupon()">
                                                    <i class="fa-solid fa-plus me-1"></i>Áp dụng
                                                </button>
                                            </div>
                                            <small class="text-muted" id="coupon-hint">Nhập mã giảm giá để tiết kiệm thêm!</small>
                                        @endif
                                    </div>
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
                            <div class="mb-3" id="bottom-totals">
                                <div class="mb-2" id="bottom-subtotal-discount" style="{{ $totals['discount'] > 0 ? '' : 'display: none;' }}">
                                    <span class="fs-6 text-muted" id="bottom-subtotal">Tạm tính: {{ number_format($totals['subtotal']) }} đ</span>
                                    <br>
                                    <span class="fs-6 text-success" id="bottom-discount">Giảm giá: -{{ number_format($totals['discount']) }} đ</span>
                                </div>
                                <span class="fs-4 fw-bold text-danger" id="bottom-total">Tổng thanh toán: {{ number_format($totals['total']) }} đ</span>
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

// Function để format số tiền
function formatCurrency(amount) {
    // Đảm bảo amount là số
    const numAmount = parseInt(amount) || 0;
    return new Intl.NumberFormat('vi-VN').format(numAmount) + ' đ';
}

// Function để cập nhật UI từ totals data
function updateTotalsUI(totals) {
    // Cập nhật các giá trị trong bảng
    document.getElementById('subtotal-amount').textContent = formatCurrency(totals.subtotal);
    document.getElementById('total-amount').textContent = formatCurrency(totals.total);
    
    // Cập nhật giảm giá trong bảng
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('discount-amount');
    
    if (totals.discount > 0) {
        discountRow.style.display = '';
        discountAmount.textContent = '-' + formatCurrency(totals.discount);
    } else {
        discountRow.style.display = 'none';
    }
    
    // Cập nhật tổng tiền ở cuối trang
    const bottomSubtotalDiscount = document.getElementById('bottom-subtotal-discount');
    const bottomSubtotal = document.getElementById('bottom-subtotal');
    const bottomDiscount = document.getElementById('bottom-discount');
    const bottomTotal = document.getElementById('bottom-total');
    
    // Cập nhật tổng thanh toán
    bottomTotal.textContent = 'Tổng thanh toán: ' + formatCurrency(totals.total);
    
    // Cập nhật tạm tính và giảm giá
    if (totals.discount > 0) {
        bottomSubtotalDiscount.style.display = '';
        bottomSubtotal.textContent = 'Tạm tính: ' + formatCurrency(totals.subtotal);
        bottomDiscount.textContent = 'Giảm giá: -' + formatCurrency(totals.discount);
    } else {
        bottomSubtotalDiscount.style.display = 'none';
    }
}

// Function để cập nhật UI coupon section
function updateCouponUI(isApplied, couponData = null, discount = 0) {
    const couponContent = document.getElementById('coupon-content');
    
    if (isApplied && couponData) {
        // Hiển thị coupon đã áp dụng
        couponContent.innerHTML = `
            <div class="alert alert-success d-flex justify-content-between align-items-center" id="applied-coupon-alert">
                <span><i class="fa-solid fa-check-circle me-1"></i> <strong id="applied-coupon-code">${couponData.code}</strong> đã được áp dụng</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">Hủy</button>
            </div>
            <small class="text-success" id="coupon-savings">Tiết kiệm: ${formatCurrency(discount)}</small>
        `;
    } else {
        // Hiển thị form nhập coupon
        couponContent.innerHTML = `
            <div class="input-group mb-2" id="coupon-input-group">
                <input type="text" id="coupon_code_input" class="form-control" placeholder="Nhập mã giảm giá..." maxlength="50" onkeypress="if(event.key==='Enter') applyCoupon()">
                <button type="button" class="btn btn-outline-primary" onclick="applyCoupon()">
                    <i class="fa-solid fa-plus me-1"></i>Áp dụng
                </button>
            </div>
            <small class="text-muted" id="coupon-hint">Nhập mã giảm giá để tiết kiệm thêm!</small>
        `;
    }
}

// Function để hiển thị thông báo
function showMessage(message, type = 'success') {
    // Kiểm tra nếu Bootstrap Toast có sẵn
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // Tạo toast notification
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Tạo toast container nếu chưa có
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Thêm toast mới
        const toastDiv = document.createElement('div');
        toastDiv.innerHTML = toastHtml;
        const toast = toastDiv.firstElementChild;
        toastContainer.appendChild(toast);
        
        // Hiển thị toast
        const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
        bsToast.show();
        
        // Xóa toast sau khi ẩn
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    } else {
        // Fallback với alert đơn giản
        alert(message);
    }
}

// Function để áp dụng mã giảm giá bằng AJAX
function applyCoupon() {
    const couponCode = document.getElementById('coupon_code_input').value.trim();
    
    if (!couponCode) {
        showMessage('Vui lòng nhập mã giảm giá!', 'error');
        return;
    }

    // Disable button để tránh click nhiều lần
    const button = document.querySelector('button[onclick="applyCoupon()"]');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Đang xử lý...';

    // Tạo form data
    const formData = new FormData();
    formData.append('coupon_code', couponCode);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Gửi AJAX request
    fetch('{{ route("cart.applyCoupon") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Apply coupon response:', data); // Debug log
        if (data.success) {
            // Cập nhật UI mà không reload trang
            updateTotalsUI(data.totals);
            updateCouponUI(true, data.coupon, data.totals.discount);
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi áp dụng mã giảm giá!', 'error');
    })
    .finally(() => {
        // Restore button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Function để hủy mã giảm giá bằng AJAX
function removeCoupon() {
    // Disable button để tránh click nhiều lần
    const button = document.querySelector('button[onclick="removeCoupon()"]');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

    // Tạo form data
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Gửi AJAX request
    fetch('{{ route("cart.removeCoupon") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Remove coupon response:', data); // Debug log
        if (data.success) {
            // Cập nhật UI mà không reload trang
            updateTotalsUI(data.totals);
            updateCouponUI(false);
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message || 'Có lỗi xảy ra khi hủy mã giảm giá!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi hủy mã giảm giá!', 'error');
    })
    .finally(() => {
        // Restore button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>
@endsection
