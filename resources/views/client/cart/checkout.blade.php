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
    .coupon-applied .form-control {
        background-color: #f8fff9;
        border-color: #28a745;
    }

    .coupon-applied {
        border: 1px solid #28a745;
        border-radius: 4px;
        background-color: #f8fff9;
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
                                    <div class="fw-semibold text-primary">{{ number_format($salePrice) }} VNĐ</div>
                            <div class="text-muted small text-decoration-line-through">{{ number_format($originalPrice) }} VNĐ</div>
                            @else
                            <div class="fw-semibold text-primary">{{ number_format($price) }} VNĐ</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="fw-bold fs-5" style="color: #ee4d2d;">{{ number_format($itemTotal) }} VNĐ</div>
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
                    
                    <div class="voucher-section" style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e5e5e5;">
                        <div class="voucher-input-group">
                            <!-- Tab buttons -->
                         

                            <!-- Select coupon tab -->
                            <div id="select-coupon-tab" class="voucher-tab-content">
                                <div class="input-group mb-2">
                                    <select class="form-select" id="coupon-select" style="border-radius: 4px 0 0 4px;">
                                        <option value="">-- Đang tải mã giảm giá... --</option>
                                    </select>
                                    <button class="btn btn-primary" type="button" id="apply-coupon-select" 
                                            style="border-radius: 0 4px 4px 0; min-width: 100px;" disabled>
                                        <i class="fa-solid fa-tag me-1"></i>ÁP DỤNG
                                    </button>
                                </div>

                                <!-- Coupon details -->
                                <div id="coupon-details" class="mt-2" style="display: none;">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-primary mb-2">
                                                <i class="fa-solid fa-info-circle me-1"></i>
                                                Chi tiết mã giảm giá
                                            </h6>
                                            <div class="row g-2 small">
                                                <div class="col-6">
                                                    <strong>Mã:</strong> <span id="detail-code">-</span>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Giảm giá:</strong> <span id="detail-discount">-</span>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Đơn tối thiểu:</strong> <span id="detail-min-order">-</span>
                                                </div>
                                                <div class="col-6">
                                                    <strong>Trạng thái:</strong> <span id="detail-status">-</span>
                                                </div>
                                                <div class="col-12">
                                                    <strong>Mô tả:</strong> <span id="detail-description">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status message -->
                                <div class="mt-2 text-center" id="coupon-status-message">
                                    <small class="text-muted">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Đang tải danh sách mã giảm giá...
                                    </small>
                                    <!-- DEBUG BUTTON - Remove after testing -->
                                    <br>
                                    <button type="button" class="btn btn-sm btn-outline-info mt-1" onclick="window.debugCoupons()">
                                        Debug Coupons
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning mt-1" onclick="loadSavedCouponsToDropdown()">
                                        Reload Coupons
                                    </button>
                                </div>
                            </div>

                            <!-- Input coupon tab -->
                            <div id="input-coupon-tab" class="voucher-tab-content" style="display: none;">
                                <div class="input-group {{ $appliedCoupon ? 'coupon-applied' : '' }}" id="coupon-input-group">
                                    <input type="text" class="form-control" id="coupon-input" name="coupon_code"
                                        value="{{ $appliedCoupon ?? '' }}"
                                        placeholder="Nhập mã voucher..."
                                        {{ $appliedCoupon ? 'readonly' : '' }}>
                                    <button class="btn {{ $appliedCoupon ? 'btn-outline-danger' : 'btn-outline-secondary' }}" 
                                            type="button" id="apply-coupon-input">
                                        @if($appliedCoupon)
                                        <i class="fa-solid fa-times me-1"></i>Hủy
                                        @else
                                        <i class="fa-solid fa-tags me-1"></i>Áp dụng
                                        @endif
                                    </button>
                                </div>
                                
                                <div class="mt-2 text-center">
                                    <small class="text-muted">
                                        <i class="fa-solid fa-lightbulb me-1"></i>
                                        Chưa có mã giảm giá? <a href="{{ route('coupons.index') }}" class="text-primary">Xem mã khả dụng</a>
                                    </small>
                                </div>
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
                    </div>
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
                            <span class="fw-semibold" id="subtotal">{{ number_format($subtotal) }} VNĐ</span>
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
                            <span class="text-success fw-semibold" id="discount-amount">-{{ number_format($couponDiscount) }} VNĐ</span>
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
                            <span id="total-amount">{{ number_format($total) }} VNĐ</span>
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
        // Initialize coupon manager FIRST
        window.couponManager = {
            getSavedCoupons: function() {
                try {
                    const saved = localStorage.getItem('savedCoupons');
                    console.log('Raw saved coupons:', saved);
                    return saved ? JSON.parse(saved) : [];
                } catch (error) {
                    console.error('Error loading saved coupons:', error);
                    return [];
                }
            },
            isCouponSaved: function(code) {
                const savedCoupons = this.getSavedCoupons();
                return savedCoupons.find(c => c.code === code) !== undefined;
            }
        };

        // Test localStorage immediately
        console.log('Testing localStorage:', window.couponManager.getSavedCoupons());

        // Initialize functions in correct order
        initializeCouponManager();
        setupVoucherHandling();

        function initializeCouponManager() {
            console.log('Initializing coupon manager...');
            
            // Force immediate load
            setTimeout(() => {
                loadSavedCouponsToDropdown();
            }, 100);

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
            const savedCoupons = window.couponManager.getSavedCoupons();
            const couponSelect = document.getElementById('coupon-select');
            const statusMessage = document.getElementById('coupon-status-message');
            
            console.log('Loading saved coupons to dropdown:', savedCoupons);
            
            if (!couponSelect) {
                console.error('Coupon select element not found');
                return;
            }

            // Clear existing options
            couponSelect.innerHTML = '<option value="">-- Chọn mã giảm giá --</option>';

            if (savedCoupons.length === 0) {
                console.log('No saved coupons found');
                couponSelect.innerHTML += '<option value="" disabled>Không có mã giảm giá đã lưu</option>';
                if (statusMessage) {
                    statusMessage.innerHTML = `
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Bạn chưa lưu mã giảm giá nào. 
                            <a href="{{ route('coupons.index') }}" class="text-primary">Xem mã khả dụng</a>
                        </small>
                    `;
                }
                return;
            }

            console.log('Adding coupons to dropdown:', savedCoupons.length);

            // Add saved coupons to dropdown
            savedCoupons.forEach((coupon, index) => {
                console.log(`Adding coupon ${index + 1}:`, coupon);
                
                const option = document.createElement('option');
                option.value = coupon.code;
                
                let displayText = `${coupon.code}`;
                if (coupon.discount) {
                    displayText += ` - ${coupon.discount}`;
                }
                option.textContent = displayText;
                
                option.setAttribute('data-code', coupon.code);
                option.setAttribute('data-discount', coupon.discount || '');
                option.setAttribute('data-description', coupon.description || '');
                option.setAttribute('data-saved-at', coupon.savedAt || '');
                option.setAttribute('data-type', 'saved');
                
                couponSelect.appendChild(option);
            });

            // Update status message
            if (statusMessage) {
                statusMessage.innerHTML = `
                    <small class="text-success">
                        <i class="fa-solid fa-check-circle me-1"></i>
                        Đã tải ${savedCoupons.length} mã giảm giá từ danh sách đã lưu
                    </small>
                `;
            }

            console.log('Dropdown updated successfully');
        }

        // Add debug function to test localStorage
        window.debugCoupons = function() {
            console.log('=== COUPON DEBUG ===');
            console.log('localStorage savedCoupons:', localStorage.getItem('savedCoupons'));
            console.log('Parsed coupons:', window.couponManager.getSavedCoupons());
            console.log('Coupon select element:', document.getElementById('coupon-select'));
            console.log('Current options:', document.getElementById('coupon-select')?.innerHTML);
        };

        // Auto-debug on load
        setTimeout(() => {
            console.log('=== AUTO DEBUG AFTER 2 SECONDS ===');
            window.debugCoupons();
        }, 2000);

        // Enhanced expose functions globally for cart/checkout pages to use
        window.couponManager = {
            getSavedCoupons: function() {
                try {
                    const saved = localStorage.getItem('savedCoupons');
                    return saved ? JSON.parse(saved) : [];
                } catch (error) {
                    console.error('Error loading saved coupons:', error);
                    return [];
                }
            },
            isCouponSaved: function(code) {
                const savedCoupons = this.getSavedCoupons();
                return savedCoupons.find(c => c.code === code) !== undefined;
            }
        };

        function initializeCouponManager() {
            console.log('Initializing coupon manager...');
            
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
            const savedCoupons = window.couponManager ? window.couponManager.getSavedCoupons() : [];
            const couponSelect = document.getElementById('coupon-select');
            const statusMessage = document.getElementById('coupon-status-message');
            
            console.log('Loading saved coupons:', savedCoupons.length);
            
            if (!couponSelect) return;

            // Clear existing options
            couponSelect.innerHTML = '<option value="">-- Chọn mã giảm giá --</option>';

            if (savedCoupons.length === 0) {
                couponSelect.innerHTML += '<option value="" disabled>Không có mã giảm giá đã lưu</option>';
                if (statusMessage) {
                    statusMessage.innerHTML = `
                        <small class="text-muted">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Bạn chưa lưu mã giảm giá nào. 
                            <a href="{{ route('coupons.index') }}" class="text-primary">Xem mã khả dụng</a>
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
                option.textContent = displayText;
                
                option.setAttribute('data-code', coupon.code);
                option.setAttribute('data-discount', coupon.discount || '');
                option.setAttribute('data-description', coupon.description || '');
                option.setAttribute('data-saved-at', coupon.savedAt || '');
                option.setAttribute('data-type', 'saved');
                
                couponSelect.appendChild(option);
            });

            // Update status message
            if (statusMessage) {
                statusMessage.innerHTML = `
                    <small class="text-success">
                        <i class="fa-solid fa-check-circle me-1"></i>
                        Đã tải ${savedCoupons.length} mã giảm giá từ danh sách đã lưu
                    </small>
                `;
            }
        }

        function setupVoucherHandling() {
            console.log('Setting up voucher handling...');
            
            // Tab switching
            const voucherTabs = document.querySelectorAll('.voucher-tab');
            const selectTab = document.getElementById('select-coupon-tab');
            const inputTab = document.getElementById('input-coupon-tab');

            voucherTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabType = this.getAttribute('data-tab');
                    
                    // Update active tab
                    voucherTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show/hide tab content
                    if (tabType === 'select') {
                        selectTab.style.display = 'block';
                        inputTab.style.display = 'none';
                        
                        // Update button styles
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-outline-primary');
                        document.getElementById('input-tab').classList.remove('btn-outline-primary');
                        document.getElementById('input-tab').classList.add('btn-outline-secondary');
                    } else {
                        selectTab.style.display = 'none';
                        inputTab.style.display = 'block';
                        hideCouponDetails();
                        
                        // Update button styles
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-outline-primary');
                        document.getElementById('select-tab').classList.remove('btn-outline-primary');
                        document.getElementById('select-tab').classList.add('btn-outline-secondary');
                    }
                });
            });
            
            // Coupon select change handler
            const couponSelect = document.getElementById('coupon-select');
            if (couponSelect) {
                couponSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const applyBtn = document.getElementById('apply-coupon-select');
                    
                    if (this.value && selectedOption) {
                        showCouponDetails(selectedOption);
                        if (applyBtn) applyBtn.disabled = false;
                    } else {
                        hideCouponDetails();
                        if (applyBtn) applyBtn.disabled = true;
                    }
                });
            }

            // Apply coupon from select
            const applyCouponSelectBtn = document.getElementById('apply-coupon-select');
            const applyCouponInputBtn = document.getElementById('apply-coupon-input');
            const couponInput = document.getElementById('coupon-input');
            const couponResult = document.getElementById('coupon-result');

            let appliedCoupon = null;
            let isInCancelMode = false;

            // Check if there's already an applied coupon
            const hasAppliedCoupon = couponInput && couponInput.value.trim() !== '';
            if (hasAppliedCoupon) {
                isInCancelMode = true;
                appliedCoupon = {
                    code: couponInput.value,
                };

                // Update hidden input
                const hiddenCouponInput = document.getElementById('hidden-coupon-code');
                if (hiddenCouponInput) {
                    hiddenCouponInput.value = couponInput.value;
                }
            }

            if (applyCouponSelectBtn) {
                applyCouponSelectBtn.addEventListener('click', function() {
                    if (isInCancelMode) {
                        handleCancelCoupon();
                    } else {
                        const couponCode = couponSelect ? couponSelect.value : '';
                        if (couponCode) {
                            handleApplyCoupon(couponCode);
                        } else {
                            showCouponMessage('Vui lòng chọn mã voucher', 'warning');
                        }
                    }
                });
            }

            // Apply coupon from input
            if (applyCouponInputBtn) {
                applyCouponInputBtn.addEventListener('click', function() {
                    if (isInCancelMode) {
                        handleCancelCoupon();
                    } else {
                        const couponCode = couponInput ? couponInput.value.trim() : '';
                        if (couponCode) {
                            handleApplyCoupon(couponCode);
                        } else {
                            showCouponMessage('Vui lòng nhập mã voucher', 'warning');
                        }
                    }
                });
            }

            function handleApplyCoupon(couponCode) {
                console.log('Applying coupon:', couponCode);

                // Disable button and show loading
                const activeBtn = document.querySelector('.voucher-tab.active').getAttribute('data-tab') === 'select' 
                    ? applyCouponSelectBtn : applyCouponInputBtn;
                
                if (activeBtn) {
                    activeBtn.disabled = true;
                    activeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
                }

                // Send AJAX request
                fetch('{{ route("cart.apply-coupon") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode
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
                if (inputGroup) inputGroup.classList.add('coupon-applied');
                
                if (couponInput) couponInput.readOnly = true;
                if (couponSelect) couponSelect.disabled = true;

                if (applyCouponInputBtn) {
                    applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Hủy';
                    applyCouponInputBtn.className = 'btn btn-outline-danger';
                    applyCouponInputBtn.disabled = false;
                }

                if (applyCouponSelectBtn) {
                    applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>HỦY';
                    applyCouponSelectBtn.className = 'btn btn-danger';
                    applyCouponSelectBtn.disabled = false;
                }

                // Update hidden input
                const hiddenCouponInput = document.getElementById('hidden-coupon-code');
                if (hiddenCouponInput) {
                    const activeTab = document.querySelector('.voucher-tab.active').getAttribute('data-tab');
                    if (activeTab === 'select' && couponSelect) {
                        hiddenCouponInput.value = couponSelect.value;
                    } else if (activeTab === 'input' && couponInput) {
                        hiddenCouponInput.value = couponInput.value;
                    }
                }

                isInCancelMode = true;
            }

            function handleCancelCoupon() {
                appliedCoupon = null;
                if (couponInput) couponInput.value = '';
                if (couponSelect) couponSelect.value = '';
                if (couponResult) couponResult.innerHTML = '';

                const inputGroup = document.getElementById('coupon-input-group');
                if (inputGroup) inputGroup.classList.remove('coupon-applied');
                
                if (couponInput) couponInput.readOnly = false;
                if (couponSelect) couponSelect.disabled = false;

                const discountRow = document.getElementById('discount-row');
                if (discountRow) discountRow.style.display = 'none';

                const subtotalText = document.getElementById('subtotal') ? document.getElementById('subtotal').textContent : '';
                const totalAmount = document.getElementById('total-amount');
                if (totalAmount) totalAmount.textContent = subtotalText;

                resetCouponButton();

                // Clear hidden input
                const hiddenCouponInput = document.getElementById('hidden-coupon-code');
                if (hiddenCouponInput) {
                    hiddenCouponInput.value = '';
                }

                // Call API to remove coupon from session
                fetch('{{ route("cart.remove-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).catch(error => console.log('Error removing coupon:', error));

                isInCancelMode = false;
                hideCouponDetails();
            }

            function resetCouponButton() {
                if (couponSelect) {
                    couponSelect.disabled = false;
                    couponSelect.style.backgroundColor = '';
                    couponSelect.style.borderColor = '';
                }
                
                if (couponInput) {
                    couponInput.readOnly = false;
                    couponInput.style.backgroundColor = '';
                    couponInput.style.borderColor = '';
                }

                if (applyCouponInputBtn) {
                    applyCouponInputBtn.disabled = false;
                    applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-tags me-1"></i>Áp dụng';
                    applyCouponInputBtn.className = 'btn btn-outline-secondary';
                }

                if (applyCouponSelectBtn) {
                    applyCouponSelectBtn.disabled = !couponSelect?.value;
                    applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
                    applyCouponSelectBtn.className = 'btn btn-primary';
                }
            }
        }

        function showCouponDetails(option) {
            const code = option.value;
            const discount = option.getAttribute('data-discount');
            const description = option.getAttribute('data-description');
            const type = option.getAttribute('data-type');
            
            const detailsDiv = document.getElementById('coupon-details');
            if (!detailsDiv) return;
            
            document.getElementById('detail-code').textContent = code;
            document.getElementById('detail-discount').textContent = discount || 'Mã đã lưu';
            document.getElementById('detail-description').textContent = description || 'Mã giảm giá đã lưu từ danh sách';
            document.getElementById('detail-min-order').textContent = 'Kiểm tra khi áp dụng';
            
            if (type === 'saved') {
                document.getElementById('detail-status').innerHTML = '<span class="text-success">Đã lưu - Sẵn sàng sử dụng</span>';
            } else {
                document.getElementById('detail-status').innerHTML = '<span class="text-primary">Khả dụng</span>';
            }
            
            detailsDiv.style.display = 'block';
        }

        function hideCouponDetails() {
            const detailsDiv = document.getElementById('coupon-details');
            if (detailsDiv) {
                detailsDiv.style.display = 'none';
            }
        }

        function showCouponMessage(message, type) {
            const iconMap = {
                'success': 'check-circle',
                'danger': 'exclamation-triangle',
                'warning': 'info-circle'
            };
            const couponResult = document.getElementById('coupon-result');
            if (couponResult) {
                couponResult.innerHTML = `
                <div class="alert alert-${type} py-2 px-3 mb-0 rounded-3">
                    <small>
                        <i class="fa-solid fa-${iconMap[type]} me-1"></i>
                        ${message}
                    </small>
                </div>
            `;
            }
        }

        function showCouponSuccess(message, couponInfo) {
            const discountText = couponInfo.type === 'percentage' ? `${couponInfo.value}%` : `${parseInt(couponInfo.value).toLocaleString('vi-VN')}đ`;
            const couponResult = document.getElementById('coupon-result');
            if (couponResult) {
                couponResult.innerHTML = `
                <div class="alert alert-success py-2 px-3 mb-0 rounded-3">
                    <small>
                        <i class="fa-solid fa-check-circle me-1"></i>
                        <strong class="text-success">${couponInfo.code}</strong> - Giảm <span class="fw-bold">${discountText}</span>
                    </small>
                </div>
            `;
            }
        }

        function updateOrderSummary(discountAmount, newTotal, couponInfo = null) {
            const discountRow = document.getElementById('discount-row');
            const discountAmountSpan = document.getElementById('discount-amount');
            const totalAmountSpan = document.getElementById('total-amount');

            if (discountAmount > 0) {
                if (discountRow) discountRow.style.display = 'flex';

                let discountText = '';
                if (couponInfo && couponInfo.type === 'percentage') {
                    discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')} VNĐ (${couponInfo.value}%)`;
                } else {
                    discountText = `-${parseInt(discountAmount).toLocaleString('vi-VN')} VNĐ`;
                }

                if (discountAmountSpan) discountAmountSpan.textContent = discountText;
                if (totalAmountSpan) totalAmountSpan.textContent = `${parseInt(newTotal).toLocaleString('vi-VN')} VNĐ`;
            } else {
                if (discountRow) discountRow.style.display = 'none';
                // When no discount, total = subtotal
                const subtotalText = document.getElementById('subtotal') ? document.getElementById('subtotal').textContent : '';
                if (totalAmountSpan) totalAmountSpan.textContent = subtotalText;
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
                isCheckoutCompleted = true;
            }
        });

        // Clear voucher khi rời khỏi trang mà không hoàn tất checkout
        window.addEventListener('beforeunload', function(e) {
            if (!isCheckoutCompleted) {
                // Gọi API để clear voucher session
                fetch('/cart/clear-checkout-voucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    keepalive: true // Đảm bảo request được gửi kể cả khi trang đang đóng
                }).catch(error => {
                    console.error('Error clearing coupon on page unload:', error);
                });
            }
        });

        // Clear voucher khi nhấn nút "Quay lại giỏ hàng" hoặc navigate về cart
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a[href*="cart"]');
            if (target && !isCheckoutCompleted) {
                // Gọi API để clear voucher session
                fetch('/cart/clear-checkout-voucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).catch(error => {
                    console.error('Error clearing coupon on back to cart:', error);
                });
            }
        });
    });
</script>
@endpush
