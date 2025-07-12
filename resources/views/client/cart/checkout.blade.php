@extends('client.layout.main')

@section('content')
<style>
.coupon-applied .form-control {
    background-color: #f8fff9;
    border-color: #28a745;
}
.coupon-applied {
    border: 2px solid #28a745;
    border-radius: 0.375rem;
    background-color: #f8fff9;
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
.address-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}
.address-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}
.address-card.selected {
    border-color: #667eea;
    background-color: #f8f9ff;
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
            
            <!-- Hidden input để gửi coupon code -->
            <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="{{ session('applied_coupon') ?? '' }}">
            
            <div class="row">
                <!-- Chọn địa chỉ giao hàng -->
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="section-title mb-0"><i class="fa-solid fa-truck me-2"></i>Địa chỉ giao hàng</h4>
                        @auth
                        <div class="d-flex gap-2">
                            <a href="{{ route('client.addresses.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-plus me-1"></i>Thêm địa chỉ mới
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fa-solid fa-lightning me-1"></i>Thêm nhanh
                            </button>
                        </div>
                        @endauth
                    </div>
                    
                    @auth
                        @if($addresses->count() > 0)
                            <div class="row">
                                @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="card address-card h-100 {{ $address->is_default ? 'border-primary' : '' }}" 
                                         style="cursor: pointer;" onclick="selectAddress({{ $address->id }})">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="shipping_address_id" value="{{ $address->id }}"
                                                           id="address_{{ $address->id }}" 
                                                           {{ ($defaultAddress && $defaultAddress->id == $address->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="address_{{ $address->id }}">
                                                        {{ $address->name }}
                                                    </label>
                                                </div>
                                                @if($address->is_default)
                                                    <span class="badge bg-primary">Mặc định</span>
                                                @endif
                                            </div>
                                            <p class="text-muted small mb-2">
                                                <i class="fa-solid fa-phone me-1"></i>{{ $address->phone }}
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i class="fa-solid fa-map-marker-alt me-1"></i>
                                                {{ $address->address_detail }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                            </p>
                                            @if($address->note)
                                            <p class="text-muted small mt-2 mb-0">
                                                <i class="fa-solid fa-sticky-note me-1"></i>{{ $address->note }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @error('shipping_address_id')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        @else
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-exclamation-triangle me-3 fs-4"></i>
                                    <div>
                                        <strong>Chưa có địa chỉ giao hàng!</strong>
                                        <p class="mb-0">Bạn cần thêm ít nhất một địa chỉ giao hàng để tiếp tục đặt hàng.</p>
                                    </div>
                                </div>
                                <a href="{{ route('client.addresses.create') }}" class="btn btn-warning mt-3 me-2">
                                    <i class="fa-solid fa-plus me-1"></i>Thêm địa chỉ ngay
                                </a>
                                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="fa-solid fa-lightning me-1"></i>Thêm nhanh
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            Vui lòng <a href="{{ route('auth') }}">đăng nhập</a> để sử dụng địa chỉ đã lưu.
                        </div>
                    @endauth
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
                    <div class="input-group {{ $appliedCoupon ? 'coupon-applied' : '' }}" id="coupon-input-group">
                        <input type="text" class="form-control" id="coupon-input" name="coupon_code" 
                               value="{{ $appliedCoupon ?? '' }}"
                               placeholder="Nhập mã voucher..."
                               {{ $appliedCoupon ? 'readonly' : '' }}>
                        <button class="btn {{ $appliedCoupon ? 'btn-outline-danger' : 'btn-outline-secondary' }}" type="button" id="apply-coupon">
                            @if($appliedCoupon)
                                <i class="fa-solid fa-times me-1"></i>Hủy
                            @else
                                <i class="fa-solid fa-tags me-1"></i>Áp dụng
                            @endif
                        </button>
                    </div>
                    <div id="coupon-result" class="mt-2">
                        @if($appliedCoupon)
                        <div class="alert alert-success py-2 px-3 mb-0 rounded-3">
                            <small>
                                <i class="fa-solid fa-check-circle me-1"></i>
                                <strong class="text-success">{{ $appliedCoupon }}</strong> - 
                                @if(isset($couponInfo))
                                    Giảm <span class="fw-bold">
                                        @if($couponInfo['type'] === 'percentage')
                                            {{ $couponInfo['value'] }}%
                                        @else
                                            {{ number_format($couponInfo['value']) }}đ
                                        @endif
                                    </span>
                                @else
                                    Mã giảm giá đã được áp dụng
                                @endif
                            </small>
                        </div>
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
                @auth
                    @if($addresses->count() > 0)
                        <button type="submit" class="btn btn-primary-gradient btn-lg px-5 py-3 fw-bold">
                            <i class="fa-solid fa-lock me-2"></i>Đặt hàng ngay
                        </button>
                    @else
                        <a href="{{ route('client.addresses.create') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold">
                            <i class="fa-solid fa-plus me-2"></i>Thêm địa chỉ để đặt hàng
                        </a>
                    @endif
                @else
                    <a href="{{ route('auth') }}" class="btn btn-primary btn-lg px-5 py-3 fw-bold">
                        <i class="fa-solid fa-sign-in-alt me-2"></i>Đăng nhập để đặt hàng
                    </a>
                @endauth
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

<!-- Modal thêm địa chỉ nhanh -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Thêm địa chỉ mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.addresses.store') }}" method="POST" id="quick-address-form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_name" name="name" 
                                   value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_phone" name="phone" 
                                   value="{{ Auth::user()->phone }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="modal_province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <select class="form-select" id="modal_province" name="province" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                @foreach($provinces ?? [] as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                            <select class="form-select" id="modal_district" name="district" required disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                            <select class="form-select" id="modal_ward" name="ward" required disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_address_detail" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="modal_address_detail" name="address_detail" 
                                  rows="3" placeholder="Số nhà, tên đường..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modal_is_default" 
                                   name="is_default" value="1" checked>
                            <label class="form-check-label" for="modal_is_default">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Lưu địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global functions for address selection and modal
function selectAddress(addressId) {
    // Remove selected class from all cards
    document.querySelectorAll('.address-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    const selectedCard = document.querySelector(`#address_${addressId}`).closest('.address-card');
    if (selectedCard) {
        selectedCard.classList.add('selected');
    }
    
    // Check the radio button
    const radioButton = document.getElementById(`address_${addressId}`);
    if (radioButton) {
        radioButton.checked = true;
    }
}

function loadModalDistricts() {
    const province = document.getElementById('modal_province').value;
    const districtSelect = document.getElementById('modal_district');
    const wardSelect = document.getElementById('modal_ward');
    
    // Reset districts and wards
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    
    if (province) {
        fetch(`/api/districts?province=${encodeURIComponent(province)}`)
            .then(response => response.json())
            .then(districts => {
                if (Array.isArray(districts) && districts.length > 0) {
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                    districtSelect.disabled = false;
                }
            })
            .catch(error => console.error('Error loading districts:', error));
    }
}

function loadModalWards() {
    const district = document.getElementById('modal_district').value;
    const province = document.getElementById('modal_province').value;
    const wardSelect = document.getElementById('modal_ward');
    
    // Reset wards
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    if (district) {
        const url = `/api/wards?district=${encodeURIComponent(district)}&province=${encodeURIComponent(province)}`;
        fetch(url)
            .then(response => response.json())
            .then(wards => {
                if (Array.isArray(wards) && wards.length > 0) {
                    wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward;
                        option.textContent = ward;
                        wardSelect.appendChild(option);
                    });
                    wardSelect.disabled = false;
                }
            })
            .catch(error => console.error('Error loading wards:', error));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý áp dụng mã giảm giá
    const applyCouponBtn = document.getElementById('apply-coupon');
    const couponInput = document.getElementById('coupon-input');
    const couponResult = document.getElementById('coupon-result');
    
    // Kiểm tra xem các elements có tồn tại không
    if (!applyCouponBtn || !couponInput || !couponResult) {
        console.error('Coupon elements not found');
        return;
    }
    
    let appliedCoupon = null;
    let isInCancelMode = false;
    
    // Kiểm tra xem đã có mã giảm giá được áp dụng từ trước chưa
    const hasAppliedCoupon = couponInput.value.trim() !== '';
    if (hasAppliedCoupon) {
        isInCancelMode = true;
        appliedCoupon = {
            code: couponInput.value,
            // Lấy thông tin từ server nếu có
        };
        
        // Cập nhật hidden input
        const hiddenCouponInput = document.getElementById('hidden-coupon-code');
        if (hiddenCouponInput) {
            hiddenCouponInput.value = couponInput.value;
        }
    }

    // Function xử lý chung cho button
    function handleCouponButton() {
        if (isInCancelMode) {
            handleCancelCoupon();
        } else {
            handleApplyCoupon();
        }
    }

    // Gán event listener duy nhất
    applyCouponBtn.addEventListener('click', handleCouponButton);

    function handleApplyCoupon() {
        const couponCode = couponInput.value.trim();
        console.log('Applying coupon:', couponCode);
        
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
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                appliedCoupon = data;
                showCouponSuccess(data.message, data.coupon_info);
                updateOrderSummary(data.discount_amount, data.total, data.coupon_info);
                switchToCancelMode();
            } else {
                showCouponMessage(data.message, 'danger');
                resetCouponButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
            resetCouponButton();
        });
    }

    function switchToCancelMode() {
        const inputGroup = document.getElementById('coupon-input-group');
        inputGroup.classList.add('coupon-applied');
        couponInput.readOnly = true;
        
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Hủy';
        applyCouponBtn.className = 'btn btn-outline-danger';
        applyCouponBtn.disabled = false;
        
        // Cập nhật hidden input
        const hiddenCouponInput = document.getElementById('hidden-coupon-code');
        if (hiddenCouponInput) {
            hiddenCouponInput.value = couponInput.value;
        }
        
        isInCancelMode = true;
    }

    function handleCancelCoupon() {
        appliedCoupon = null;
        couponInput.value = '';
        couponResult.innerHTML = '';
        
        const inputGroup = document.getElementById('coupon-input-group');
        inputGroup.classList.remove('coupon-applied');
        couponInput.readOnly = false;
        
        const discountRow = document.getElementById('discount-row');
        discountRow.style.display = 'none';
        
        const subtotalText = document.getElementById('subtotal').textContent;
        document.getElementById('total-amount').textContent = subtotalText;
        
        applyCouponBtn.innerHTML = '<i class="fa-solid fa-tags me-1"></i>Áp dụng';
        applyCouponBtn.className = 'btn btn-outline-secondary';
        applyCouponBtn.disabled = false;
        
        // Xóa hidden input
        const hiddenCouponInput = document.getElementById('hidden-coupon-code');
        if (hiddenCouponInput) {
            hiddenCouponInput.value = '';
        }
        
        // Gọi API để xóa coupon khỏi session
        fetch('{{ route("cart.remove-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).catch(error => console.log('Error removing coupon:', error));
        
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
    
    // Auto-select default address on page load
    const defaultAddressRadio = document.querySelector('input[name="shipping_address_id"]:checked');
    if (defaultAddressRadio) {
        const addressId = defaultAddressRadio.value;
        selectAddress(addressId);
    }
    
    // Add event listeners for modal selects
    const modalProvince = document.getElementById('modal_province');
    const modalDistrict = document.getElementById('modal_district');
    
    if (modalProvince) {
        modalProvince.addEventListener('change', loadModalDistricts);
    }
    if (modalDistrict) {
        modalDistrict.addEventListener('change', loadModalWards);
    }
    
    // Handle quick address form submission
    const quickAddressForm = document.getElementById('quick-address-form');
    if (quickAddressForm) {
        quickAddressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang lưu...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi lưu địa chỉ. Vui lòng thử lại.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
@endpush
