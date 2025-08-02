@extends('client.layout.main')

@section('content')
<style>
    /* Shopee-style Checkout Design - đồng màu với Cart */
    .checkout-header {
        background: #fff;
        border-bottom: 1px solid #e5e5e5;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
    }

    .checkout-header h1 {
        color: #333;
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    .checkout-header p {
        color: #666;
        margin: 0;
    }

    .product-item {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .product-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        color: #333;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 16px;
    }

    .form-control,
    .form-select {
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ee4d2d;
        box-shadow: 0 0 0 0.2rem rgba(238, 77, 45, 0.15);
    }

    .address-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .address-card:hover {
        border-color: #ee4d2d;
        box-shadow: 0 2px 8px rgba(238, 77, 45, 0.15);
    }

    .address-card.selected {
        border-color: #ee4d2d;
        background-color: #fff5f5;
    }

    .payment-method-item {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .payment-method-item:hover {
        border-color: #ee4d2d;
        background-color: #fff5f5;
    }

    .payment-method-item:has(.form-check-input:checked) {
        border-color: #ee4d2d;
        background-color: #fff5f5;
    }

    .form-check-input:checked {
        background-color: #ee4d2d;
        border-color: #ee4d2d;
    }

    .form-check-input:focus {
        border-color: #ee4d2d;
        box-shadow: 0 0 0 0.2rem rgba(238, 77, 45, 0.25);
    }

    /* Voucher Section */
    .coupon-applied .form-control,
    .coupon-applied .form-select {
        background-color: #f8fff9;
        border-color: #28a745;
    }

    .coupon-applied {
        border: 1px solid #28a745;
        border-radius: 4px;
        background-color: #f8fff9;
    }

    .voucher-section-checkout {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #e5e5e5;
    }

    .voucher-section-checkout .form-select,
    .voucher-section-checkout .form-control {
        font-size: 14px;
        height: 38px;
    }

    .voucher-section-checkout .btn {
        font-size: 14px;
        height: 38px;
        min-width: 80px;
    }

    /* Applied coupon display */
    #applied-coupon-display {
        background: #f8fff9;
        border: 1px solid #28a745;
        border-radius: 6px;
        padding: 12px;
    }

    #applied-coupon-display .btn-sm {
        font-size: 12px;
        padding: 4px 8px;
    }

    /* Order Summary */
    .order-summary {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .order-summary h5 {
        color: #333;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .summary-row span:first-child {
        color: #666;
    }

    .summary-row span:last-child {
        color: #333;
        font-weight: 500;
    }

    .total-amount-row {
        border-top: 1px solid #e5e5e5;
        padding-top: 12px;
        margin-top: 12px;
    }

    .total-amount-row span:first-child {
        color: #333;
        font-size: 16px;
        font-weight: 700;
    }

    .total-amount-row span:last-child {
        color: #ee4d2d;
        font-size: 18px;
        font-weight: 700;
    }

    /* Buttons */
    .btn-primary-gradient {
        background: #ee4d2d;
        border: none;
        border-radius: 4px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .btn-primary-gradient:hover {
        background: #d73527;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(238, 77, 45, 0.3);
    }

    .btn-outline-primary {
        border: 1px solid #ee4d2d;
        color: #ee4d2d;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: #ee4d2d;
        color: white;
    }

    .btn-outline-secondary {
        border: 1px solid #e5e5e5;
        color: #666;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: #f5f5f5;
        color: #333;
    }

    .btn-outline-danger {
        border: 1px solid #dc3545;
        color: #dc3545;
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
    }

    /* Badge */
    .badge {
        font-size: 12px;
        padding: 4px 8px;
    }

    .badge.bg-light {
        background-color: #f5f5f5 !important;
        color: #666 !important;
        border: 1px solid #e5e5e5;
    }

    /* Alert */
    .alert {
        border-radius: 8px;
        border: 1px solid;
        font-size: 14px;
    }

    .alert-success {
        background-color: #f8fff9;
        border-color: #28a745;
        color: #155724;
    }

    .alert-danger {
        background-color: #fff5f5;
        border-color: #dc3545;
        color: #721c24;
    }

    .alert-warning {
        background-color: #fffbf0;
        border-color: #ffc107;
        color: #856404;
    }

    .alert-info {
        background-color: #f0f8ff;
        border-color: #17a2b8;
        color: #0c5460;
    }

    /* Product Image */
    .product-image {
        max-height: 80px;
        max-width: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e5e5e5;
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e5e5e5;
        padding: 16px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #e5e5e5;
        padding: 16px 20px;
    }

    /* Momo Logo */
    .momo-logo {
        display: inline-block;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #d82d8b, #aa1467);
        border-radius: 4px;
        color: white;
        text-align: center;
        line-height: 20px;
        font-weight: bold;
        font-size: 10px;
        margin-right: 8px;
    }

    .momo-logo-small {
        width: 16px;
        height: 16px;
        line-height: 16px;
        font-size: 8px;
        margin-right: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 16px;
            margin-bottom: 12px;
        }

        .order-summary {
            padding: 16px;
        }

        .checkout-header {
            padding: 1rem 0;
        }

        .checkout-header h1 {
            font-size: 20px;
        }
    }
</style>

<div class="checkout-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-shopping-cart me-3 text-primary" style="font-size: 28px; color: #ee4d2d !important;"></i>
                    <div>
                        <h1 class="mb-1">Thanh toán</h1>
                        <p class="mb-0">Vui lòng kiểm tra thông tin và hoàn tất đơn hàng của bạn</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="badge bg-light text-dark fs-6 px-3 py-2">
                    <i class="fa-solid fa-box me-2" style="color: #ee4d2d;"></i>{{ $carts->count() }} sản phẩm
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- Thông báo lỗi và thành công -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($carts->count() > 0)

    <!-- Danh sách sản phẩm -->
    <div class="form-section">
        <h3 class="section-title"><i class="fa-solid fa-box-open me-2 text-primary"></i>Sản phẩm đã chọn</h3>
        <div class="row">
            @php $subtotal = 0; @endphp
            @foreach($carts as $cart)
            @php
            $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
            $originalPrice = $cart->variant->price ?? $cart->product->price;
            $salePrice = $cart->variant->sale_price ?? $cart->product->sale_price;
            $itemTotal = $price * $cart->quantity;
            $subtotal += $itemTotal;
            @endphp
            <div class="col-12">
                <div class="product-item">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}"
                                class="product-image">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1 fw-bold text-dark">{{ $cart->product->name }}</h6>
                            @if($cart->variant)
                            @php
                            $currentSize = $cart->variant->attributeValues->where('attribute.name', 'Size')->first();
                            $currentColor = $cart->variant->attributeValues->where('attribute.name', 'Màu')->first();
                            $variantInfo = [];
                            if($currentSize) $variantInfo[] = "Size: {$currentSize->value}";
                            if($currentColor) $variantInfo[] = "Màu: {$currentColor->value}";
                            @endphp
                            @if(!empty($variantInfo))
                            <small class="text-muted"><i class="fa-solid fa-tag me-1"></i>{{ implode(', ', $variantInfo) }}</small>
                            @endif
                            @endif
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="quantity-display">
                                <span class="badge bg-light text-dark fs-6 px-2 py-1" style="border: 1px solid #e5e5e5;">
                                    x{{ $cart->quantity }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="price-display">
                                @if($salePrice && $salePrice < $originalPrice)
                                    <div class="fw-semibold text-primary">{{ format_vnd($salePrice) }} VNĐ</div>
                            <div class="text-muted small text-decoration-line-through">{{ format_vnd($originalPrice) }} VNĐ</div>
                            @else
                            <div class="fw-semibold text-primary">{{ format_vnd($price) }} VNĐ</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="fw-bold fs-5" style="color: #ee4d2d;">{{ format_vnd($itemTotal) }} VNĐ</div>
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

        <!-- Hidden input để gửi selected cart items -->
        @if(session('selected_cart_items'))
        @foreach(session('selected_cart_items') as $cartId)
        <input type="hidden" name="selected_cart_items[]" value="{{ $cartId }}">
        @endforeach
        @endif

        <!-- Hidden input để gửi coupon code -->
        <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="{{ session('applied_coupon') ?? '' }}">

        <div class="row">
            <!-- Chọn địa chỉ giao hàng -->
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="section-title mb-0"><i class="fa-solid fa-truck me-2"></i>Địa chỉ giao hàng</h4>
                    @auth
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fa-solid fa-lightning me-1"></i>Thêm địa chỉ
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
                    
                    <!-- Voucher selection -->
                    <div class="voucher-section-checkout" id="voucher-selection-section" style="{{ $appliedCoupon ? 'display: none;' : '' }}">
                        <div class="voucher-header d-flex align-items-center justify-content-between mb-2" id="voucher-header-checkout">
                            <small class="text-muted">Chọn mã đã lưu:</small>
                            <span class="badge bg-info" id="available-coupons-count-checkout">
                                0 mã khả dụng
                            </span>
                        </div>

                        <!-- Select coupon dropdown -->
                        <div class="input-group mb-2 {{ $appliedCoupon ? 'coupon-applied' : '' }}" id="coupon-select-group">
                            <select class="form-select" id="coupon-select-checkout" {{ $appliedCoupon ? 'disabled' : '' }}>
                                <option value="">-- Đang tải mã giảm giá... --</option>
                            </select>
                            <button class="btn {{ $appliedCoupon ? 'btn-outline-danger' : 'btn-outline-secondary' }}" type="button" id="apply-coupon-select" {{ $appliedCoupon ? '' : 'disabled' }}>
                                @if($appliedCoupon)
                                <i class="fa-solid fa-times me-1"></i>Hủy
                                @else
                                <i class="fa-solid fa-tag me-1"></i>Áp dụng
                                @endif
                            </button>
                        </div>

                        <!-- Coupon details -->
                        <div id="coupon-details-checkout" class="mt-2" style="display: none;">
                            <div class="card bg-light border-0">
                                <div class="card-body p-2">
                                    <h6 class="card-title text-primary mb-2 small">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Chi tiết mã giảm giá
                                    </h6>
                                    <div class="row g-1 small">
                                        <div class="col-6">
                                            <strong>Mã:</strong> <span id="detail-code-checkout">-</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Giảm giá:</strong> <span id="detail-discount-checkout">-</span>
                                        </div>
                                        <div class="col-12">
                                            <strong>Mô tả:</strong> <span id="detail-description-checkout">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual input option -->
                        <div class="mt-2" id="manual-input-toggle" style="{{ $appliedCoupon ? 'display: none;' : '' }}">
                            <small class="text-muted">
                                <a href="#" id="toggle-manual-input" class="text-primary">Hoặc nhập mã thủ công</a>
                            </small>
                        </div>

                        <!-- Manual input section (hidden by default) -->
                        <div id="manual-input-section" class="mt-2" style="display: none;">
                            <div class="input-group {{ $appliedCoupon ? 'coupon-applied' : '' }}" id="coupon-input-group">
                                <input type="text" class="form-control" id="coupon-input" name="coupon_code"
                                    value="{{ $appliedCoupon ?? '' }}"
                                    placeholder="Nhập mã voucher..."
                                    {{ $appliedCoupon ? 'readonly' : '' }}>
                                <button class="btn {{ $appliedCoupon ? 'btn-outline-danger' : 'btn-outline-secondary' }}" type="button" id="apply-coupon-input">
                                    @if($appliedCoupon)
                                    <i class="fa-solid fa-times me-1"></i>Hủy
                                    @else
                                    <i class="fa-solid fa-tags me-1"></i>Áp dụng
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="coupon-result" class="mt-2" style="{{ $appliedCoupon ? 'display: none;' : '' }}">
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
                                    {{ format_vnd($couponInfo['value']) }}đ
                                    @endif
                                </span>
                                @else
                                Mã giảm giá đã được áp dụng
                                @endif
                            </small>
                        </div>
                        @endif
                    </div>

                    <!-- Status message -->
                    <div class="mt-2" id="coupon-status-message-checkout" style="{{ $appliedCoupon ? 'display: none;' : '' }}">
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Đang tải danh sách mã giảm giá...
                        </small>
                    </div>

                    <!-- Applied coupon display -->
                    @if($appliedCoupon)
                    <div class="mt-2" id="applied-coupon-display">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-success fw-semibold">
                                <i class="fa-solid fa-check-circle me-1"></i>
                                Đã áp dụng mã: <strong>{{ $appliedCoupon }}</strong>
                            </small>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="cancel-applied-coupon">
                                <i class="fa-solid fa-times me-1"></i>Hủy
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fa-solid fa-credit-card me-1"></i>Phương thức thanh toán
                    </label>
                    <div class="payment-methods">
                        @foreach($payment_methods as $method)
                        <div class="form-check payment-method-item mb-2">
                            <input class="form-check-input" type="radio" name="payment_method_id"
                                id="payment_{{ $method->id }}" value="{{ $method->id }}"
                                data-type="{{ strtolower($method->payment_type) }}" required>
                            <label class="form-check-label w-100" for="payment_{{ $method->id }}">
                                <div class="d-flex align-items-center">
                                    @if($method->payment_type == 'COD')
                                    <i class="fa-solid fa-money-bill-wave me-2 text-success"></i>
                                    @elseif($method->payment_type == 'Ví Điện Tử MOMO')
                                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png"
                                        alt="MoMo" class="me-2" style="width: 20px; height: 20px;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <span class="momo-logo" style="display: none;">M</span>
                                    @elseif($method->payment_type == 'Ví Điện Tử ZALOPAY')
                                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png"
                                        alt="ZaloPay" class="me-2" style="width: 20px; height: 20px;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <span class="zalopay-logo" style="display: none;">Z</span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $method->payment_type }}</div>
                                        <small class="text-muted">
                                            @if($method->payment_type == 'COD')
                                            Thanh toán khi nhận hàng
                                            @elseif($method->payment_type == 'Ví Điện Tử MOMO')
                                            Thanh toán online tiện lợi bằng ví MOMO
                                            @elseif($method->payment_type == 'Ví Điện Tử ZALOPAY')
                                            Thanh toán nhanh chóng qua ví ZaloPay
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Chọn phương thức thanh toán phù hợp với bạn
                        </small>
                    </div>
                </div>

            </div>

            <!-- Tóm tắt thanh toán -->
            <div class="order-summary mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h5 class="section-title mb-3"><i class="fa-solid fa-receipt me-2 text-primary"></i>Tóm tắt đơn hàng</h5>

                        <!-- Tổng tiền hàng -->
                        <div class="summary-row">
                            <span><i class="fa-solid fa-box me-2 text-muted"></i>Tổng tiền hàng:</span>
                            <span class="fw-semibold" id="subtotal">{{ format_vnd($subtotal) }} VNĐ</span>
                        </div>

                        <!-- Tổng tiền phí vận chuyển -->
                        <div class="summary-row">
                            <span><i class="fa-solid fa-truck me-2 text-muted"></i>Tổng tiền phí vận chuyển:</span>
                            <span class="text-success fw-semibold" id="shipping-fee">
                                <i class="fa-solid fa-gift me-1"></i>Miễn phí
                            </span>
                        </div>

                        <!-- Tổng cộng Voucher giảm giá -->
                        @if($appliedCoupon && $couponDiscount > 0)
                        <div class="summary-row" id="discount-row">
                            <span class="text-success">
                                <i class="fa-solid fa-ticket me-2"></i>Tổng cộng Voucher giảm giá:
                            </span>
                            <span class="text-success fw-semibold" id="discount-amount">-{{ format_vnd($couponDiscount) }} VNĐ</span>
                        </div>
                        @else
                        <div class="summary-row" id="discount-row" style="display: none;">
                            <span class="text-success">
                                <i class="fa-solid fa-ticket me-2"></i>Tổng cộng Voucher giảm giá:
                            </span>
                            <span class="text-success fw-semibold" id="discount-amount">-0 VNĐ</span>
                        </div>
                        @endif

                        <!-- Tổng thanh toán -->
                        <div class="summary-row total-amount-row">
                            <span><i class="fa-solid fa-calculator me-2"></i>Tổng thanh toán:</span>
                            <span id="total-amount">{{ format_vnd($total) }} VNĐ</span>
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
                <button type="button" class="btn btn-warning btn-lg px-5 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fa-solid fa-plus me-2"></i>Thêm địa chỉ để đặt hàng
                </button>
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
            <div class="text-center mt-4">
                <p class="text-muted mb-2 small">Chúng tôi chấp nhận:</p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="badge bg-light text-dark px-2 py-1">
                        <i class="fa-solid fa-money-bill-wave me-1 text-success"></i>COD
                    </span>


                    <span class="badge bg-light text-dark px-2 py-1">
                        <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png"
                            alt="MoMo" style="width: 16px; height: 16px;" class="me-1"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                        <span class="momo-logo momo-logo-small" style="display: none;">M</span>MoMo
                    </span>
                    <span class="badge bg-light text-dark px-2 py-1">
                        <i class="fa-solid fa-wallet me-1 text-info"></i>ZaloPay
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
                <div class="d-flex gap-2">
                    @auth
                    @if(Auth::user()->address)
                    <button type="button" class="btn btn-outline-primary btn-sm" id="import-account-address" title="Import họ tên, số điện thoại và tự động chọn vùng miền từ tài khoản">
                        <i class="fa-solid fa-user me-1"></i>Import thông tin cơ bản
                    </button>
                    @endif
                    @endauth
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
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
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            // Try internal API first, fallback to external
            fetch(`/api/districts?province=${encodeURIComponent(province)}`)
                .then(response => {
                    if (!response.ok) {
                        // Fallback to external API
                        return fetch('https://provinces.open-api.vn/api/?depth=2')
                            .then(res => res.json())
                            .then(provincesData => {
                                const targetProvince = provincesData.find(p => p.name === province);
                                return targetProvince ? targetProvince.districts.map(d => d.name) : [];
                            });
                    }
                    return response.json();
                })
                .then(districts => {
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    if (Array.isArray(districts) && districts.length > 0) {
                        districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="">Không có dữ liệu Quận/Huyện</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading districts:', error);
                    districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
        }
    }

    function loadModalWards() {
        const district = document.getElementById('modal_district').value;
        const province = document.getElementById('modal_province').value;
        const wardSelect = document.getElementById('modal_ward');

        // Reset wards
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wardSelect.disabled = true;

        if (district && province) {
            wardSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            const url = `/api/wards?district=${encodeURIComponent(district)}&province=${encodeURIComponent(province)}`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        // Fallback to external API
                        return fetch('https://provinces.open-api.vn/api/?depth=3')
                            .then(res => res.json())
                            .then(provincesData => {
                                const targetProvince = provincesData.find(p => p.name === province);
                                if (!targetProvince) return [];
                                const targetDistrict = targetProvince.districts?.find(d => d.name === district);
                                return targetDistrict ? targetDistrict.wards.map(w => w.name) : [];
                            });
                    }
                    return response.json();
                })
                .then(wards => {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    if (Array.isArray(wards) && wards.length > 0) {
                        wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward;
                            option.textContent = ward;
                            wardSelect.appendChild(option);
                        });
                        wardSelect.disabled = false;
                    } else {
                        wardSelect.innerHTML = '<option value="">Không có dữ liệu Phường/Xã</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading wards:', error);
                    wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize coupon management
        initializeCouponManager();
        
        // Handle import account address button
        const importAccountAddressBtn = document.getElementById('import-account-address');
        if (importAccountAddressBtn) {
            importAccountAddressBtn.addEventListener('click', function() {
                importAccountAddress();
            });
        }
        
        // Xử lý áp dụng mã giảm giá
        const applyCouponSelectBtn = document.getElementById('apply-coupon-select');
        const applyCouponInputBtn = document.getElementById('apply-coupon-input');
        const couponSelect = document.getElementById('coupon-select-checkout');
        const couponInput = document.getElementById('coupon-input');
        const couponResult = document.getElementById('coupon-result');

        // Variables to track state
        let appliedCoupon = null;
        let isInCancelMode = false;

        // Check if coupon is already applied from server
        const hasAppliedCoupon = couponInput && couponInput.value.trim() !== '';
        if (hasAppliedCoupon) {
            isInCancelMode = true;
            appliedCoupon = {
                code: couponInput.value,
            };
            updateHiddenCouponInput(couponInput.value);
            hideSelectionUI();
            showAppliedCouponUI();
        } else {
            showSelectionUI();
            hideAppliedCouponUI();
        }

        // Handle cancel applied coupon button (for pre-applied coupons)
        const cancelAppliedCouponBtn = document.getElementById('cancel-applied-coupon');
        if (cancelAppliedCouponBtn) {
            cancelAppliedCouponBtn.addEventListener('click', function() {
                handleCancelCoupon();
            });
        }

        // Toggle manual input section
        const toggleManualInput = document.getElementById('toggle-manual-input');
        const manualInputSection = document.getElementById('manual-input-section');
        
        if (toggleManualInput && manualInputSection) {
            toggleManualInput.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = manualInputSection.style.display === 'none';
                manualInputSection.style.display = isHidden ? 'block' : 'none';
                this.textContent = isHidden ? 'Ẩn nhập thủ công' : 'Hoặc nhập mã thủ công';
            });
        }

        // Coupon select change handler
        if (couponSelect) {
            couponSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value && selectedOption) {
                    showCouponDetails(selectedOption);
                    if (applyCouponSelectBtn) applyCouponSelectBtn.disabled = false;
                } else {
                    hideCouponDetails();
                    if (applyCouponSelectBtn) applyCouponSelectBtn.disabled = true;
                }
            });
        }

        // Apply coupon from select dropdown
        if (applyCouponSelectBtn) {
            applyCouponSelectBtn.addEventListener('click', function() {
                if (isInCancelMode) {
                    handleCancelCoupon();
                } else {
                    const couponCode = couponSelect ? couponSelect.value : '';
                    if (couponCode) {
                        handleApplyCoupon(couponCode, 'select');
                    } else {
                        showCouponMessage('Vui lòng chọn mã voucher', 'warning');
                    }
                }
            });
        }

        // Apply coupon from manual input
        if (applyCouponInputBtn) {
            applyCouponInputBtn.addEventListener('click', function() {
                if (isInCancelMode) {
                    handleCancelCoupon();
                } else {
                    const couponCode = couponInput ? couponInput.value.trim() : '';
                    if (couponCode) {
                        handleApplyCoupon(couponCode, 'input');
                    } else {
                        showCouponMessage('Vui lòng nhập mã voucher', 'warning');
                    }
                }
            });
        }

        function handleApplyCoupon(couponCode, source) {
            console.log('Applying coupon:', couponCode, 'from:', source);

            // Disable button and show loading
            const activeBtn = source === 'select' ? applyCouponSelectBtn : applyCouponInputBtn;
            activeBtn.disabled = true;
            activeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';

            // Calculate selected subtotal
            const subtotalText = document.getElementById('subtotal').textContent;
            const subtotal = parseFloat(subtotalText.replace(/[^\d]/g, ''));

            // Send AJAX request
            fetch('{{ route("cart.apply-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode,
                        subtotal: subtotal
                    })
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
                        switchToCancelMode(source);
                        
                        // Remove applied coupon from saved list to prevent reuse
                        removeAppliedCouponFromSavedList(couponCode);
                        
                        showToast('Áp dụng mã giảm giá thành công!', 'success');
                    } else {
                        // Hiển thị thông báo lỗi chi tiết
                        let errorMessage = data.message;
                        if (data.message.includes('đã sử dụng')) {
                            errorMessage += ' Mã này đã được áp dụng cho tài khoản của bạn.';
                        } else if (data.message.includes('hết lượt')) {
                            errorMessage += ' Mã giảm giá này đã hết lượt sử dụng.';
                        } else if (data.message.includes('hết hạn')) {
                            errorMessage += ' Mã giảm giá này đã hết thời hạn sử dụng.';
                        } else if (data.message.includes('tối thiểu')) {
                            errorMessage += ' Vui lòng thêm sản phẩm để đạt giá trị đơn hàng tối thiểu.';
                        } else if (data.message.includes('không thể sử dụng')) {
                            errorMessage += ' Mã này hiện không khả dụng.';
                        }
                        
                        showCouponMessage(errorMessage, 'danger');
                        resetCouponButton(activeBtn, source);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
                    resetCouponButton(activeBtn, source);
                });
        }

        function switchToCancelMode(source) {
            const selectGroup = document.getElementById('coupon-select-group');
            const inputGroup = document.getElementById('coupon-input-group');
            const couponResult = document.getElementById('coupon-result');
            
            // Apply visual state to both groups
            if (selectGroup) selectGroup.classList.add('coupon-applied');
            if (inputGroup) inputGroup.classList.add('coupon-applied');
            
            // Disable inputs
            if (couponSelect) couponSelect.disabled = true;
            if (couponInput) couponInput.readOnly = true;

            // Update both buttons to cancel mode
            if (applyCouponSelectBtn) {
                applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Hủy';
                applyCouponSelectBtn.className = 'btn btn-outline-danger';
                applyCouponSelectBtn.disabled = false;
            }
            
            if (applyCouponInputBtn) {
                applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Hủy';
                applyCouponInputBtn.className = 'btn btn-outline-danger';
                applyCouponInputBtn.disabled = false;
            }

            // Update hidden input
            const couponCode = source === 'select' ? couponSelect.value : couponInput.value;
            updateHiddenCouponInput(couponCode);

            // Clear coupon result content but keep it for new messages
            if (couponResult) couponResult.innerHTML = '';

            // Hide selection UI and show applied coupon UI
            hideSelectionUI();
            showAppliedCouponUI();

            isInCancelMode = true;
        }

        function handleCancelCoupon() {
            // Store coupon info before clearing
            const canceledCouponCode = appliedCoupon ? 
                (appliedCoupon.coupon_info?.code || appliedCoupon.code) : 
                (couponInput ? couponInput.value : '');
            
            appliedCoupon = null;
            
            // Reset UI elements
            if (couponSelect) couponSelect.value = '';
            if (couponInput) couponInput.value = '';
            if (couponResult) couponResult.innerHTML = '';
            
            const selectGroup = document.getElementById('coupon-select-group');
            const inputGroup = document.getElementById('coupon-input-group');
            
            // Remove applied state
            if (selectGroup) selectGroup.classList.remove('coupon-applied');
            if (inputGroup) inputGroup.classList.remove('coupon-applied');
            
            // Re-enable inputs
            if (couponSelect) couponSelect.disabled = false;
            if (couponInput) couponInput.readOnly = false;

            // Reset discount in order summary
            const discountRow = document.getElementById('discount-row');
            if (discountRow) discountRow.style.display = 'none';

            const subtotalText = document.getElementById('subtotal').textContent;
            const totalElement = document.getElementById('total-amount');
            if (totalElement) totalElement.textContent = subtotalText;

            // Reset buttons
            if (applyCouponSelectBtn) {
                applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>Áp dụng';
                applyCouponSelectBtn.className = 'btn btn-outline-secondary';
                applyCouponSelectBtn.disabled = true;
            }
            
            if (applyCouponInputBtn) {
                applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-tags me-1"></i>Áp dụng';
                applyCouponInputBtn.className = 'btn btn-outline-secondary';
                applyCouponInputBtn.disabled = false;
            }

            // Clear hidden input
            updateHiddenCouponInput('');

            // Hide coupon details
            hideCouponDetails();

            // Show selection UI and hide applied coupon UI
            showSelectionUI();
            hideAppliedCouponUI();

            // Call API to remove coupon from session
            fetch('{{ route("cart.remove-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).catch(error => console.log('Error removing coupon:', error));

            // Restore canceled coupon back to saved list if it was from saved coupons
            if (canceledCouponCode) {
                restoreCouponToSavedList(canceledCouponCode);
            }

            isInCancelMode = false;
            showToast('Đã hủy mã giảm giá và trả về danh sách đã lưu', 'info');
        }

        function restoreCouponToSavedList(couponCode) {
            if (!couponCode) return;
            
            @auth
            // Call API to restore coupon to database
            fetch('{{ route("client.coupons.restore") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    coupon_code: couponCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Restored coupon ${couponCode} to database`);
                    
                    // Reload saved coupons to reflect the change
                    loadSavedCouponsToDropdown();
                    
                    // Show success message
                    showToast(data.message, 'success');
                    
                    // Dispatch event to notify other parts of the app
                    window.dispatchEvent(new CustomEvent('couponsUpdated'));
                } else {
                    console.warn(`Failed to restore coupon ${couponCode}:`, data.message);
                    showToast(data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error restoring coupon to saved list:', error);
                
                // Fallback: add to dropdown temporarily if API fails
                const couponSelect = document.getElementById('coupon-select-checkout');
                if (couponSelect) {
                    // Check if coupon already exists in dropdown
                    let alreadyExists = false;
                    for (const option of couponSelect.options) {
                        if (option.value === couponCode) {
                            alreadyExists = true;
                            break;
                        }
                    }
                    
                    if (!alreadyExists) {
                        // Add coupon back to dropdown temporarily
                        const option = document.createElement('option');
                        option.value = couponCode;
                        option.textContent = couponCode;
                        option.setAttribute('data-code', couponCode);
                        option.setAttribute('data-type', 'restored');
                        couponSelect.appendChild(option);
                        
                        // Update count badge
                        const couponCountBadge = document.getElementById('available-coupons-count-checkout');
                        if (couponCountBadge) {
                            const currentCount = parseInt(couponCountBadge.textContent.match(/\d+/)[0]) || 0;
                            couponCountBadge.textContent = `${currentCount + 1} mã khả dụng`;
                        }
                        
                        showToast('Đã trả mã về danh sách tạm thời', 'info');
                    }
                }
            });
            @else
            console.log('User not authenticated, cannot restore coupon to database');
            @endauth
        }

        function removeAppliedCouponFromSavedList(couponCode) {
            if (!couponCode) return;
            
            @auth
            // Find coupon ID by code from the current dropdown
            let couponId = null;
            const couponSelect = document.getElementById('coupon-select-checkout');
            if (couponSelect) {
                for (const option of couponSelect.options) {
                    if (option.value === couponCode && option.getAttribute('data-id')) {
                        couponId = option.getAttribute('data-id');
                        break;
                    }
                }
            }
            
            // If couponId not found in dropdown, try to find it via API
            if (!couponId) {
                // For now, we'll use the couponManager's method
                if (window.couponManager && window.couponManager.removeSavedCoupon) {
                    window.couponManager.removeSavedCoupon(couponCode)
                        .then(removed => {
                            if (removed) {
                                console.log(`Removed applied coupon ${couponCode} from saved list`);
                                // Reload the dropdown to reflect changes
                                loadSavedCouponsToDropdown();
                            }
                        })
                        .catch(error => {
                            console.error('Error removing applied coupon from saved list:', error);
                        });
                }
                return;
            }
            
            // Remove from database using couponId
            fetch('{{ route("client.coupons.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    coupon_id: couponId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Removed applied coupon ${couponCode} from saved list`);
                    // Reload the dropdown to reflect changes
                    loadSavedCouponsToDropdown();
                } else {
                    console.warn(`Failed to remove applied coupon ${couponCode}:`, data.message);
                }
            })
            .catch(error => {
                console.error('Error removing applied coupon from saved list:', error);
            });
            @endauth
        }

        function resetCouponButton(buttonElement, source) {
            buttonElement.disabled = source === 'select' ? true : false;
            const icon = source === 'select' ? 'fa-tag' : 'fa-tags';
            buttonElement.innerHTML = `<i class="fa-solid ${icon} me-1"></i>Áp dụng`;
            buttonElement.className = 'btn btn-outline-secondary';
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

        function updateOrderSummary(discountAmount, newTotal, couponInfo = null) {
            const discountRow = document.getElementById('discount-row');
            const discountAmountSpan = document.getElementById('discount-amount');
            const totalAmountSpan = document.getElementById('total-amount');

            if (discountAmount > 0) {
                discountRow.style.display = 'flex';

                let discountText = '';
                if (couponInfo && couponInfo.type === 'percentage') {
                    discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')} VNĐ (${couponInfo.value}%)`;
                } else {
                    discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')} VNĐ`;
                }

                discountAmountSpan.textContent = discountText;
                totalAmountSpan.textContent = `${parseInt(newTotal).toLocaleString('vi-VN')} VNĐ`;
            } else {
                discountRow.style.display = 'none';
                const subtotalText = document.getElementById('subtotal').textContent;
                totalAmountSpan.textContent = subtotalText;
            }
        }

        function updateHiddenCouponInput(value) {
            const hiddenCouponInput = document.getElementById('hidden-coupon-code');
            if (hiddenCouponInput) {
                hiddenCouponInput.value = value;
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            
            let bgColor = '#6c757d';
            if (type === 'success') bgColor = '#28a745';
            else if (type === 'error') bgColor = '#dc3545';
            else if (type === 'info') bgColor = '#17a2b8';
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 12px 20px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 500;
                z-index: 9999;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
                max-width: 350px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            else if (type === 'error') icon = 'fa-exclamation-triangle';
            
            toast.innerHTML = `<i class="fa-solid ${icon} me-2"></i>${message}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Initialize coupon manager
        function initializeCouponManager() {
            console.log('Initializing checkout coupon manager...');
            
            // Initialize global coupon manager if not exists
            if (!window.couponManager) {
                window.couponManager = {
                    getSavedCoupons: function() {
                        // For authenticated users, get from database via AJAX
                        @auth
                        return fetch('{{ route("client.coupons.api.saved") }}', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Filter out expired coupons on client side
                                const validCoupons = (data.coupons || []).filter(coupon => {
                                    if (coupon.expiry_date) {
                                        const expiryDate = new Date(coupon.expiry_date);
                                        const today = new Date();
                                        today.setHours(0, 0, 0, 0); // Set to start of day for comparison
                                        return expiryDate >= today;
                                    }
                                    return true; // If no expiry date, consider it valid
                                });
                                return validCoupons;
                            }
                            return [];
                        })
                        .catch(error => {
                            console.error('Error loading saved coupons:', error);
                            return [];
                        });
                        @else
                        // For guests, return empty array
                        return Promise.resolve([]);
                        @endauth
                    },
                    
                    removeSavedCoupon: function(code) {
                        @auth
                        // Find coupon ID by code
                        const couponId = this.findCouponIdByCode(code);
                        if (!couponId) {
                            console.error('Coupon ID not found for code:', code);
                            return Promise.resolve(false);
                        }

                        return fetch('{{ route("client.coupons.remove") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                coupon_id: couponId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Dispatch event to notify other parts of the app
                                window.dispatchEvent(new CustomEvent('couponsUpdated'));
                                console.log(`Removed coupon ${code} from saved list`);
                                return true;
                            } else {
                                console.error('Error removing coupon:', data.message);
                                return false;
                            }
                        })
                        .catch(error => {
                            console.error('Error removing saved coupon:', error);
                            return false;
                        });
                        @else
                        return Promise.resolve(false);
                        @endauth
                    },
                    
                    findCouponIdByCode: function(code) {
                        // This should be populated from the coupon list data
                        if (window.couponIdMap && window.couponIdMap[code]) {
                            return window.couponIdMap[code];
                        }
                        return null;
                    },
                    
                    isCouponSaved: function(code) {
                        @auth
                        return this.getSavedCoupons().then(savedCoupons => {
                            return savedCoupons.find(c => c.code === code) !== undefined;
                        });
                        @else
                        return Promise.resolve(false);
                        @endauth
                    }
                };
            }

            // Load saved coupons into dropdown
            loadSavedCouponsToDropdown();

            // Listen for storage changes
            window.addEventListener('storage', function(e) {
                if (e.key === 'savedCoupons') {
                    loadSavedCouponsToDropdown();
                }
            });

            // Listen for custom events
            window.addEventListener('couponsUpdated', function() {
                loadSavedCouponsToDropdown();
            });
        }

        function loadSavedCouponsToDropdown() {
            const savedCouponsPromise = window.couponManager ? window.couponManager.getSavedCoupons() : Promise.resolve([]);
            const couponSelect = document.getElementById('coupon-select-checkout');
            const couponCountBadge = document.getElementById('available-coupons-count-checkout');
            const statusMessage = document.getElementById('coupon-status-message-checkout');
            
            if (!couponSelect) return;

            savedCouponsPromise.then(savedCoupons => {
                console.log('Loading saved coupons to checkout:', savedCoupons.length);
                
                // Update count badge
                if (couponCountBadge) {
                    couponCountBadge.textContent = `${savedCoupons.length} mã khả dụng`;
                }

                // Clear existing options
                couponSelect.innerHTML = '<option value="">-- Chọn mã giảm giá --</option>';

                if (savedCoupons.length === 0) {
                    couponSelect.innerHTML += '<option value="" disabled>Không có mã giảm giá khả dụng</option>';
                    if (statusMessage) {
                        statusMessage.innerHTML = `
                            <small class="text-muted">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Không có mã giảm giá khả dụng. Mã hết hạn đã được ẩn. 
                                <a href="/coupons" class="text-primary">Xem thêm mã mới</a>
                            </small>
                        `;
                    }
                    return;
                }

                // Add saved coupons to dropdown
                savedCoupons.forEach(coupon => {
                    const option = document.createElement('option');
                    option.value = coupon.code;
                    
                    let displayText = `${coupon.code}`;
                    if (coupon.discount) {
                        displayText += ` - ${coupon.discount}`;
                    }
                    
                    // Add expiry info if available
                    if (coupon.expiry_date) {
                        const expiryDate = new Date(coupon.expiry_date);
                        const today = new Date();
                        const diffTime = expiryDate - today;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        
                        if (diffDays <= 3 && diffDays > 0) {
                            displayText += ` (còn ${diffDays} ngày)`;
                        }
                    }
                    
                    option.textContent = displayText;
                    
                    option.setAttribute('data-id', coupon.id);
                    option.setAttribute('data-code', coupon.code);
                    option.setAttribute('data-discount', coupon.discount || '');
                    option.setAttribute('data-description', coupon.description || '');
                    option.setAttribute('data-saved-at', coupon.savedAt || '');
                    option.setAttribute('data-expiry', coupon.expiry_date || '');
                    option.setAttribute('data-type', 'saved');
                    
                    couponSelect.appendChild(option);
                });

                // Update status message
                if (statusMessage) {
                    statusMessage.innerHTML = `
                        <small class="text-success">
                            <i class="fa-solid fa-check-circle me-1"></i>
                            Đã tải ${savedCoupons.length} mã giảm giá khả dụng (đã lọc mã hết hạn)
                        </small>
                    `;
                }
            }).catch(error => {
                console.error('Error loading saved coupons:', error);
                if (couponCountBadge) {
                    couponCountBadge.textContent = '0 mã khả dụng';
                }
            });
        }

        function showCouponDetails(option) {
            const code = option.value;
            const discount = option.getAttribute('data-discount');
            const description = option.getAttribute('data-description');
            const type = option.getAttribute('data-type');
            
            const detailsDiv = document.getElementById('coupon-details-checkout');
            if (!detailsDiv) return;
            
            document.getElementById('detail-code-checkout').textContent = code;
            document.getElementById('detail-discount-checkout').textContent = discount || 'Mã đã lưu';
            document.getElementById('detail-description-checkout').textContent = description || 'Mã giảm giá đã lưu từ danh sách';
            
            detailsDiv.style.display = 'block';
        }

        function hideCouponDetails() {
            const detailsDiv = document.getElementById('coupon-details-checkout');
            if (detailsDiv) {
                detailsDiv.style.display = 'none';
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

        // Handle payment method selection
        const paymentMethodItems = document.querySelectorAll('.payment-method-item');
        paymentMethodItems.forEach(item => {
            item.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    // Remove active class from all items
                    paymentMethodItems.forEach(i => i.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                }
            });
        });

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

        // Thêm logic clear voucher khi rời khỏi trang checkout mà không hoàn tất đơn hàng
        let isCheckoutCompleted = false;

        // Đánh dấu checkout hoàn tất khi submit form
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'checkout-form') {
                // Validate coupon one more time before submitting
                if (appliedCoupon && appliedCoupon.coupon_info) {
                    const subtotalText = document.getElementById('subtotal').textContent;
                    const subtotal = parseFloat(subtotalText.replace(/[^\d]/g, ''));
                    
                    // Check if coupon still valid for current subtotal
                    const couponCode = appliedCoupon.coupon_info.code;
                    if (couponCode) {
                        e.preventDefault(); // Prevent form submission temporarily
                        
                        // Validate coupon one final time
                        fetch('{{ route("cart.validate-coupon") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                coupon_code: couponCode,
                                subtotal: subtotal
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Coupon still valid, proceed with checkout
                                isCheckoutCompleted = true;
                                e.target.submit();
                            } else {
                                // Coupon no longer valid, show error and reset
                                showCouponMessage(`Mã giảm giá không còn hợp lệ: ${data.message}`, 'danger');
                                handleCancelCoupon();
                                showToast('Vui lòng kiểm tra lại mã giảm giá và thử lại', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error validating coupon on submit:', error);
                            // Allow form submission even if validation fails
                            isCheckoutCompleted = true;
                            e.target.submit();
                        });
                    } else {
                        isCheckoutCompleted = true;
                    }
                } else {
                    isCheckoutCompleted = true;
                }
            }
        });

        // Clear voucher khi rời khỏi trang mà không hoàn tất checkout
        window.addEventListener('beforeunload', function(e) {
            if (!isCheckoutCompleted && appliedCoupon) {
                // Store coupon info before clearing
                const canceledCouponCode = appliedCoupon.coupon_info?.code || appliedCoupon.code;
                
                // Gọi API để clear voucher session và restore coupon
                fetch('/cart/clear-checkout-voucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        restore_coupon: canceledCouponCode
                    }),
                    keepalive: true // Đảm bảo request được gửi kể cả khi trang đang đóng
                }).catch(error => {
                    console.error('Error clearing coupon on page unload:', error);
                });
            }
        });

        // Clear voucher khi nhấn nút "Quay lại giỏ hàng" hoặc navigate về cart
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a[href*="cart"]');
            if (target && !isCheckoutCompleted && appliedCoupon) {
                // Store coupon info before clearing
                const canceledCouponCode = appliedCoupon.coupon_info?.code || appliedCoupon.code;
                
                // Gọi API để clear voucher session và restore coupon
                fetch('/cart/clear-checkout-voucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        restore_coupon: canceledCouponCode
                    })
                }).catch(error => {
                    console.error('Error clearing coupon on back to cart:', error);
                });
            }
        });

        function hideSelectionUI() {
            // Hide the entire voucher selection section
            const voucherSelectionSection = document.getElementById('voucher-selection-section');
            const manualInputToggle = document.getElementById('manual-input-toggle');
            const manualInputSection = document.getElementById('manual-input-section');
            const statusMessage = document.getElementById('coupon-status-message-checkout');
            const couponResult = document.getElementById('coupon-result');
            
            if (voucherSelectionSection) voucherSelectionSection.style.display = 'none';
            if (manualInputToggle) manualInputToggle.style.display = 'none';
            if (manualInputSection) manualInputSection.style.display = 'none';
            if (statusMessage) statusMessage.style.display = 'none';
            if (couponResult) couponResult.style.display = 'none';
            
            // Hide coupon details
            hideCouponDetails();
        }

        function showSelectionUI() {
            // Show the entire voucher selection section
            const voucherSelectionSection = document.getElementById('voucher-selection-section');
            const manualInputToggle = document.getElementById('manual-input-toggle');
            const statusMessage = document.getElementById('coupon-status-message-checkout');
            const couponResult = document.getElementById('coupon-result');
            
            if (voucherSelectionSection) voucherSelectionSection.style.display = 'block';
            if (manualInputToggle) manualInputToggle.style.display = 'block';
            if (statusMessage) statusMessage.style.display = 'block';
            if (couponResult) couponResult.style.display = 'block';
        }

        function showAppliedCouponUI() {
            const appliedDisplay = document.getElementById('applied-coupon-display');
            if (appliedDisplay) {
                appliedDisplay.style.display = 'block';
            } else {
                // Create applied coupon display if it doesn't exist
                const appliedCouponCode = appliedCoupon ? appliedCoupon.coupon_info?.code : (couponInput ? couponInput.value : '');
                if (appliedCouponCode) {
                    const couponStatusContainer = document.getElementById('coupon-status-message-checkout').parentNode;
                    const appliedDiv = document.createElement('div');
                    appliedDiv.id = 'applied-coupon-display';
                    appliedDiv.className = 'mt-2';
                    appliedDiv.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-success fw-semibold">
                                <i class="fa-solid fa-check-circle me-1"></i>
                                Đã áp dụng mã: <strong>${appliedCouponCode}</strong>
                            </small>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="cancel-applied-coupon-dynamic">
                                <i class="fa-solid fa-times me-1"></i>Hủy
                            </button>
                        </div>
                    `;
                    couponStatusContainer.appendChild(appliedDiv);
                    
                    // Add event listener for the new button
                    const cancelBtn = document.getElementById('cancel-applied-coupon-dynamic');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', function() {
                            handleCancelCoupon();
                        });
                    }
                }
            }
        }

        function hideAppliedCouponUI() {
            const appliedDisplay = document.getElementById('applied-coupon-display');
            if (appliedDisplay) {
                appliedDisplay.style.display = 'none';
            }
        }

        // Import account address function
        function importAccountAddress() {
            @auth
            const accountAddress = @json(Auth::user()->address);
            const accountName = @json(Auth::user()->name);
            const accountPhone = @json(Auth::user()->phone);
            
            if (!accountAddress) {
                showToast('Tài khoản chưa có địa chỉ để import', 'warning');
                return;
            }

            // Import name, phone, and clean address detail
            const nameInput = document.getElementById('modal_name');
            const phoneInput = document.getElementById('modal_phone');
            const addressDetailInput = document.getElementById('modal_address_detail');
            
            // Fill the basic info (name and phone)
            if (nameInput && accountName) {
                nameInput.value = accountName;
            }
            if (phoneInput && accountPhone) {
                phoneInput.value = accountPhone;
            }

            // Extract clean address detail (remove administrative divisions)
            const cleanAddressDetail = extractCleanAddressDetail(accountAddress);
            if (addressDetailInput && cleanAddressDetail) {
                addressDetailInput.value = cleanAddressDetail;
            }

            // Show info message
            showToast('đang nhập thông tin từ tài khoản!', 'success');
            
            // Try to parse and auto-select province/district/ward from address string
            tryParseAddress(accountAddress);
            @else
            showToast('Vui lòng đăng nhập để sử dụng tính năng này', 'warning');
            @endauth
        }

        function extractCleanAddressDetail(fullAddress) {
            if (!fullAddress) return '';
            
            // Remove common administrative divisions patterns
            let cleanAddress = fullAddress;
            
            // List of administrative patterns to remove
            const adminPatterns = [
                // Provinces/Cities
                /,?\s*(Tỉnh|Thành phố|TP\.?)\s+[^,]*/gi,
                /,?\s*[^,]*\s+(Tỉnh|Thành phố|TP\.?)/gi,
                
                // Districts
                /,?\s*(Quận|Huyện|Thành phố|Thị xã|TP\.?)\s+[^,]*/gi,
                /,?\s*[^,]*\s+(Quận|Huyện|Thành phố|Thị xã)/gi,
                
                // Wards
                /,?\s*(Phường|Xã|Thị trấn)\s+[^,]*/gi,
                /,?\s*[^,]*\s+(Phường|Xã|Thị trấn)/gi,
                
                // Specific city names (common ones)
                /,?\s*(Hà Nội|TP\.?\s*Hồ Chí Minh|TPHCM|Đà Nẵng|Hải Phòng|Cần Thơ)/gi,
                /,?\s*(An Giang|Bà Rịa - Vũng Tàu|Bắc Giang|Bắc Kạn|Bạc Liêu|Bắc Ninh|Bến Tre)/gi,
                /,?\s*(Bình Định|Bình Dương|Bình Phước|Bình Thuận|Cà Mau|Cao Bằng)/gi,
                /,?\s*(Đắk Lắk|Đắk Nông|Điện Biên|Đồng Nai|Đồng Tháp|Gia Lai)/gi,
                /,?\s*(Hà Giang|Hà Nam|Hà Tĩnh|Hải Dương|Hậu Giang|Hòa Bình|Hưng Yên)/gi,
                /,?\s*(Khánh Hòa|Kiên Giang|Kon Tum|Lai Châu|Lâm Đồng|Lạng Sơn|Lào Cai)/gi,
                /,?\s*(Long An|Nam Định|Nghệ An|Ninh Bình|Ninh Thuận|Phú Thọ|Phú Yên)/gi,
                /,?\s*(Quảng Bình|Quảng Nam|Quảng Ngãi|Quảng Ninh|Quảng Trị|Sóc Trăng|Sơn La)/gi,
                /,?\s*(Tây Ninh|Thái Bình|Thái Nguyên|Thanh Hóa|Thừa Thiên Huế|Tiền Giang)/gi,
                /,?\s*(Trà Vinh|Tuyên Quang|Vĩnh Long|Vĩnh Phúc|Yên Bái)/gi
            ];
            
            // Remove each pattern
            adminPatterns.forEach(pattern => {
                cleanAddress = cleanAddress.replace(pattern, '');
            });
            
            // Clean up extra commas and spaces
            cleanAddress = cleanAddress
                .replace(/^[,\s]+|[,\s]+$/g, '') // Remove leading/trailing commas and spaces
                .replace(/,+/g, ',') // Replace multiple commas with single comma
                .replace(/\s+/g, ' ') // Replace multiple spaces with single space
                .replace(/,\s*,/g, ',') // Remove empty segments
                .trim();
            
            return cleanAddress;
        }

        function tryParseAddress(addressString) {
            // Enhanced address parsing to extract province, district, ward
            if (!addressString) return;
            
            const provinceSelect = document.getElementById('modal_province');
            if (!provinceSelect) return;
            
            // Common province patterns
            const provincePatterns = [
                'Hà Nội', 'TP. Hồ Chí Minh', 'TP Hồ Chí Minh', 'Hồ Chí Minh', 'TPHCM',
                'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'An Giang', 'Bà Rịa - Vũng Tàu',
                'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu', 'Bắc Ninh', 'Bến Tre',
                'Bình Định', 'Bình Dương', 'Bình Phước', 'Bình Thuận', 'Cà Mau',
                'Cao Bằng', 'Đắk Lắk', 'Đắk Nông', 'Điện Biên', 'Đồng Nai',
                'Đồng Tháp', 'Gia Lai', 'Hà Giang', 'Hà Nam', 'Hà Tĩnh',
                'Hải Dương', 'Hậu Giang', 'Hòa Bình', 'Hưng Yên', 'Khánh Hòa',
                'Kiên Giang', 'Kon Tum', 'Lai Châu', 'Lâm Đồng', 'Lạng Sơn',
                'Lào Cai', 'Long An', 'Nam Định', 'Nghệ An', 'Ninh Bình',
                'Ninh Thuận', 'Phú Thọ', 'Phú Yên', 'Quảng Bình', 'Quảng Nam',
                'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị', 'Sóc Trăng', 'Sơn La',
                'Tây Ninh', 'Thái Bình', 'Thái Nguyên', 'Thanh Hóa', 'Thừa Thiên Huế',
                'Tiền Giang', 'Trà Vinh', 'Tuyên Quang', 'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
            ];
            
            // Find matching province
            let matchedProvince = null;
            for (const province of provincePatterns) {
                if (addressString.toLowerCase().includes(province.toLowerCase())) {
                    matchedProvince = province;
                    break;
                }
            }
            
            if (matchedProvince) {
                // Select province in dropdown
                for (const option of provinceSelect.options) {
                    if (option.text.toLowerCase().includes(matchedProvince.toLowerCase()) || 
                        option.value.toLowerCase().includes(matchedProvince.toLowerCase())) {
                        option.selected = true;
                        
                        // Trigger province change and wait for districts to load
                        loadModalDistricts();
                        
                        // Wait a bit for districts to load, then try to parse district
                        setTimeout(() => {
                            tryParseDistrict(addressString, matchedProvince);
                        }, 1000);
                        
                        break;
                    }
                }
            }
        }
        
        function tryParseDistrict(addressString, province) {
            const districtSelect = document.getElementById('modal_district');
            if (!districtSelect || districtSelect.options.length <= 1) return;
            
            // Common district/ward patterns
            const districtPatterns = [
                'Quận', 'Huyện', 'Thành phố', 'Thị xã', 'TP'
            ];
            
            let matchedDistrict = null;
            
            // Try to find district in the loaded options
            for (const option of districtSelect.options) {
                if (option.value && addressString.toLowerCase().includes(option.value.toLowerCase())) {
                    matchedDistrict = option.value;
                    option.selected = true;
                    
                    // Trigger district change and load wards
                    loadModalWards();
                    
                    // Wait for wards to load, then try to parse ward
                    setTimeout(() => {
                        tryParseWard(addressString, province, matchedDistrict);
                    }, 1000);
                    
                    break;
                }
            }
            
            // If no exact match, try partial matching
            if (!matchedDistrict) {
                for (const option of districtSelect.options) {
                    if (option.value) {
                        // Remove common prefixes for comparison
                        const cleanDistrict = option.value.replace(/^(Quận|Huyện|Thành phố|Thị xã|TP)\s*/i, '');
                        if (addressString.toLowerCase().includes(cleanDistrict.toLowerCase())) {
                            matchedDistrict = option.value;
                            option.selected = true;
                            loadModalWards();
                            
                            setTimeout(() => {
                                tryParseWard(addressString, province, matchedDistrict);
                            }, 1000);
                            
                            break;
                        }
                    }
                }
            }
        }
        
        function tryParseWard(addressString, province, district) {
            const wardSelect = document.getElementById('modal_ward');
            if (!wardSelect || wardSelect.options.length <= 1) return;
            
            let matchedWard = null;
            
            // Try to find ward in the loaded options
            for (const option of wardSelect.options) {
                if (option.value && addressString.toLowerCase().includes(option.value.toLowerCase())) {
                    matchedWard = option.value;
                    option.selected = true;
                    break;
                }
            }
            
            // If no exact match, try partial matching
            if (!matchedWard) {
                for (const option of wardSelect.options) {
                    if (option.value) {
                        // Remove common prefixes for comparison
                        const cleanWard = option.value.replace(/^(Phường|Xã|Thị trấn)\s*/i, '');
                        if (addressString.toLowerCase().includes(cleanWard.toLowerCase())) {
                            matchedWard = option.value;
                            option.selected = true;
                            break;
                        }
                    }
                }
            }
            
            // Show completion message
            if (matchedWard) {
                showToast(`Đã tự động chọn vị trí: ${province} → ${district} → ${matchedWard}.`, 'success');
            } else if (district) {
                showToast(`Đã tự động chọn: ${province} → ${district}. Vui lòng chọn Phường/Xã và nhập địa chỉ chi tiết`, 'info');
            } else {
                showToast(`Đã tự động chọn: ${province}. Vui lòng chọn Quận/Huyện, Phường/Xã và nhập địa chỉ chi tiết`, 'info');
            }
        }
    });
</script>
@endpush
