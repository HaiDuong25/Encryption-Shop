@extends('client.layout.main')

@section('content')
<style>
.coupon-applied .form-control {
    background-color: #f8fff9;
    border-color: #28a745;
}
.coupon-applied .input-group {
    border: 2px solid #28a745;
    border-radius: 0.375rem;
}
.checkout-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}
.product-item {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}
.product-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 25px rgba(0,0,0,0.12);
}
.form-section {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 2rem;
}
.section-title {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.order-summary {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
}
.btn-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 15px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}
.btn-primary-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
.custom-checkbox {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 6px;
    padding: 8px 15px;
    color: white;
    font-size: 0.9rem;
}
</style>

<div class="checkout-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2"><i class="fa-solid fa-shopping-cart me-3"></i>Thanh toán đơn hàng</h1>
                <p class="mb-0 opacity-75">Vui lòng kiểm tra thông tin và hoàn tất đơn hàng của bạn</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="badge bg-light text-dark fs-6 px-3 py-2">
                    <i class="fa-solid fa-box me-2"></i>{{ $carts->count() }} sản phẩm
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    @if($carts->count() > 0)
    
    <!-- Danh sách sản phẩm -->
    <div class="form-section">
        <h3 class="section-title"><i class="fa-solid fa-box-open me-2"></i>Chi tiết đơn hàng</h3>
        <div class="row">
            @php $subtotal = 0; @endphp
            @foreach($carts as $cart)
            @php
                $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                $itemTotal = $price * $cart->quantity;
                $subtotal += $itemTotal;
            @endphp
            <div class="col-12">
                <div class="product-item">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}" 
                                 class="img-fluid rounded-3" style="max-height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1 fw-bold text-dark">{{ $cart->product->name }}</h6>
                            @if($cart->variant)
                                <small class="text-muted"><i class="fa-solid fa-tag me-1"></i>{{ $cart->variant->sku }}</small>
                            @endif
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge bg-primary rounded-pill fs-6">{{ $cart->quantity }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold text-primary">{{ number_format($price) }}đ</div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-bold text-success fs-5">{{ number_format($itemTotal) }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Form thông tin -->
    <div class="form-section">
        <form action="{{ route('cart.processCheckout') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="row">
                <!-- Thông tin người đặt -->
                <div class="col-md-6 mb-4">
                    <h4 class="section-title"><i class="fa-solid fa-user-tie me-2"></i>Thông tin người đặt hàng</h4>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="orderer_name" class="form-control" 
                               value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="orderer_email" class="form-control" 
                               value="{{ auth()->user()->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="orderer_phone" class="form-control" 
                               value="{{ auth()->user()->phone ?? '' }}" required>
                    </div>
                </div>

                <!-- Thông tin người nhận -->
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="section-title mb-0"><i class="fa-solid fa-truck me-2"></i>Thông tin người nhận hàng</h4>
                        <div class="custom-checkbox rounded">
                            <input class="form-check-input me-2" type="checkbox" id="same-as-orderer">
                            <label class="form-check-label" for="same-as-orderer">
                                <i class="fa-solid fa-copy me-1"></i>Trùng với người đặt
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="recipient_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                        <textarea name="recipient_address" class="form-control" rows="3" 
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Ghi chú và Voucher -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-sticky-note me-1"></i>Ghi chú đơn hàng</label>
                    <textarea name="notes" class="form-control" rows="3" 
                              placeholder="Ghi chú thêm về đơn hàng (tùy chọn)"></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-ticket me-1 text-warning"></i>Mã giảm giá</label>
                    <div class="input-group" id="coupon-input-group">
                        <input type="text" class="form-control" id="coupon-input" name="coupon_code" 
                               value="{{ $appliedCoupon ?? '' }}"
                               placeholder="Nhập mã voucher...">
                        <button class="btn btn-outline-secondary" type="button" id="apply-coupon">
                            <i class="fa-solid fa-tags me-1"></i>Áp dụng
                        </button>
                    </div>
                    <div id="coupon-result" class="mt-2">
                        @if($appliedCoupon)
                        <small class="text-success">
                            <i class="fa-solid fa-check-circle me-1"></i>
                            Đã áp dụng mã: <strong>{{ $appliedCoupon }}</strong>
                        </small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-credit-card me-1"></i>Phương thức thanh toán</label>
                    <select name="payment_method_id" class="form-select" required>
                        <option value="">-- Chọn phương thức thanh toán --</option>
                        @foreach($payment_methods as $method)
                        <option value="{{ $method->id }}">
                            {{ $method->payment_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tóm tắt thanh toán -->
            <div class="order-summary">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <h5 class="section-title mb-4"><i class="fa-solid fa-receipt me-2"></i>Tóm tắt thanh toán</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold" id="subtotal">{{ number_format($subtotal) }}đ</span>
                        </div>
                        @if($appliedCoupon && $couponDiscount > 0)
                        <div class="d-flex justify-content-between mb-2" id="discount-row">
                            <span class="text-success">
                                <i class="fa-solid fa-ticket me-1"></i>Giảm giá:
                            </span>
                            <span class="text-success fw-semibold" id="discount-amount">-{{ number_format($couponDiscount) }}đ</span>
                        </div>
                        @else
                        <div class="d-flex justify-content-between mb-2" id="discount-row" style="display: none !important;">
                            <span class="text-success">
                                <i class="fa-solid fa-ticket me-1"></i>Giảm giá:
                            </span>
                            <span class="text-success fw-semibold" id="discount-amount">0đ</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success fw-semibold">
                                <i class="fa-solid fa-shipping-fast me-1"></i>Miễn phí
                            </span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Tổng cộng:</span>
                            <span class="h4 mb-0 text-primary fw-bold" id="total-amount">{{ number_format($total) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nút đặt hàng -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary-gradient btn-lg px-5 py-3 fw-bold">
                    <i class="fa-solid fa-lock me-2"></i>Đặt hàng ngay
                </button>
            </div>
                
            <!-- Thông tin bảo mật -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fa-solid fa-shield-alt me-1"></i>
                    Thông tin của bạn được bảo mật tuyệt đối
                </small>
            </div>
            
            <!-- Các phương thức thanh toán -->
            <div class="text-center mt-3">
                <p class="text-muted mb-2">Chúng tôi chấp nhận:</p>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-light text-dark px-2 py-1">
                        <i class="fa-solid fa-money-bill-wave me-1"></i>COD
                    </span>
                    <span class="badge bg-light text-dark px-2 py-1">
                        <i class="fa-brands fa-cc-visa me-1"></i>Visa
                    </span>
                    <span class="badge bg-light text-dark px-2 py-1">
                        <i class="fa-brands fa-cc-mastercard me-1"></i>Mastercard
                    </span>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý checkbox "Trùng với người đặt"
    const sameAsOrdererCheckbox = document.getElementById('same-as-orderer');
    const ordererFields = {
        name: document.querySelector('input[name="orderer_name"]'),
        email: document.querySelector('input[name="orderer_email"]'),
        phone: document.querySelector('input[name="orderer_phone"]')
    };
    const recipientFields = {
        name: document.querySelector('input[name="recipient_name"]'),
        phone: document.querySelector('input[name="recipient_phone"]'),
        email: document.querySelector('input[name="recipient_email"]')
    };

    sameAsOrdererCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Copy thông tin từ người đặt sang người nhận
            recipientFields.name.value = ordererFields.name.value;
            recipientFields.phone.value = ordererFields.phone.value;
            recipientFields.email.value = ordererFields.email.value;
            
            // Disable các field người nhận (trừ địa chỉ)
            recipientFields.name.readOnly = true;
            recipientFields.phone.readOnly = true;
            recipientFields.email.readOnly = true;
        } else {
            // Enable lại các field người nhận
            recipientFields.name.readOnly = false;
            recipientFields.phone.readOnly = false;
            recipientFields.email.readOnly = false;
        }
    });

    // Đồng bộ thông tin khi người đặt thay đổi (nếu checkbox được check)
    Object.values(ordererFields).forEach(field => {
        field.addEventListener('input', function() {
            if (sameAsOrdererCheckbox.checked) {
                const fieldName = this.name.replace('orderer_', 'recipient_');
                const recipientField = document.querySelector(`input[name="${fieldName}"]`);
                if (recipientField) {
                    recipientField.value = this.value;
                }
            }
        });
    });

    // Xử lý áp dụng mã giảm giá
    const applyCouponBtn = document.getElementById('apply-coupon');
    const couponInput = document.getElementById('coupon-input');
    const couponResult = document.getElementById('coupon-result');
    let appliedCoupon = null; // Lưu thông tin coupon đã áp dụng

    let isInCancelMode = false; // Flag để track trạng thái

    // Function xử lý chung cho button
    function handleCouponButton() {
        if (isInCancelMode) {
            // Đang ở chế độ hủy
            handleCancelCoupon();
        } else {
            // Đang ở chế độ áp dụng
            handleApplyCoupon();
        }
    }

    // Gán event listener duy nhất
    applyCouponBtn.addEventListener('click', handleCouponButton);

    function handleApplyCoupon() {
        const couponCode = couponInput.value.trim();
        if (!couponCode) {
            showCouponMessage('Vui lòng nhập mã giảm giá', 'warning');
            return;
        }

        // Disable button và hiển thị loading
        applyCouponBtn.disabled = true;
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';

        // Gửi AJAX request
        fetch('{{ route("cart.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ coupon_code: couponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                appliedCoupon = data; // Lưu thông tin coupon
                showCouponSuccess(data.message, data.coupon_info);
                updateOrderSummary(data.discount_amount, data.total, data.coupon_info);
                switchToCancelMode();
            } else {
                showCouponMessage(data.message, 'danger');
                resetCouponButton();
            }
        })
        .catch(error => {
            showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
            resetCouponButton();
        });
    }

    function switchToCancelMode() {
        // Đổi input group thành màu xanh
        const inputGroup = document.getElementById('coupon-input-group');
        inputGroup.classList.add('coupon-applied');
        couponInput.readOnly = true;
        
        // Đổi button thành "Hủy" màu đỏ
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Hủy';
        applyCouponBtn.className = 'btn btn-outline-danger';
        applyCouponBtn.disabled = false;
        
        // Chuyển flag
        isInCancelMode = true;
    }

    // Xử lý hủy coupon
    function handleCancelCoupon() {
        appliedCoupon = null;
        couponInput.value = '';
        couponResult.innerHTML = '';
        
        // Reset về trạng thái ban đầu
        const inputGroup = document.getElementById('coupon-input-group');
        inputGroup.classList.remove('coupon-applied');
        couponInput.readOnly = false;
        
        // Ẩn discount row
        const discountRow = document.getElementById('discount-row');
        discountRow.style.display = 'none';
        
        // Reset tổng tiền về subtotal
        const subtotalText = document.getElementById('subtotal').textContent;
        document.getElementById('total-amount').textContent = subtotalText;
        
        // Đổi button về "Áp dụng"
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-tags me-1"></i>Áp dụng';
        applyCouponBtn.className = 'btn btn-outline-secondary';
        applyCouponBtn.disabled = false;
        
        // Chuyển flag về trạng thái ban đầu
        isInCancelMode = false;
    }

    function showCouponMessage(message, type) {
        const iconMap = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'info-circle'
        };
        couponResult.innerHTML = `
            <div class="alert alert-${type} py-2 px-3 mb-0 rounded-3">
                <small>
                    <i class="fa-solid fa-${iconMap[type]} me-1"></i>
                    ${message}
                </small>
            </div>
        `;
    }

    function showCouponSuccess(message, couponInfo) {
        const discountText = couponInfo.type === 'percentage' ? `${couponInfo.value}%` : `${parseInt(couponInfo.value).toLocaleString('vi-VN')}đ`;
        couponResult.innerHTML = `
            <div class="alert alert-success py-2 px-3 mb-0 rounded-3">
                <small>
                    <i class="fa-solid fa-check-circle me-1"></i>
                    <strong class="text-success">${couponInfo.code}</strong> - Giảm <span class="fw-bold">${discountText}</span>
                </small>
            </div>
        `;
    }

    function resetCouponButton() {
        applyCouponBtn.disabled = false;
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-tags me-1"></i>Áp dụng';
        applyCouponBtn.className = 'btn btn-outline-secondary';
    }

    function updateOrderSummary(discountAmount, newTotal, couponInfo = null) {
        const discountRow = document.getElementById('discount-row');
        const discountAmountSpan = document.getElementById('discount-amount');
        const totalAmountSpan = document.getElementById('total-amount');

        if (discountAmount > 0) {
            discountRow.style.display = 'flex';
            
            // Hiển thị giảm giá theo loại coupon
            let discountText = '';
            if (couponInfo && couponInfo.type === 'percentage') {
                discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')}đ (${couponInfo.value}%)`;
            } else {
                discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')}đ`;
            }
            
            discountAmountSpan.textContent = discountText;
            totalAmountSpan.textContent = `${parseInt(newTotal).toLocaleString('vi-VN')}đ`;
        } else {
            discountRow.style.display = 'none';
        }
    }
});
</script>
@endpush
