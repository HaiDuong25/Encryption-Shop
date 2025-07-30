@extends('client.layout.main')
@section('content')

<style>
/* Shopee-style Cart Design */
.product-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.card-header-custom {
    background: #fff;
    border-bottom: 1px solid #e5e5e5;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.shop-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e5e5e5;
}

.variant-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e5e5e5;
}

.product-name {
    font-weight: 600;
    color: #333;
    font-size: 16px;
    line-height: 1.4;
    margin: 0;
}

.variant-badge {
    background: #f5f5f5;
    color: #666;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    border: 1px solid #e5e5e5;
}

.variant-selector {
    font-size: 11px;
    padding: 2px 6px;
    border: 1px solid #e5e5e5;
    max-width: 100px;
}

.variant-select {
    font-size: 11px;
    padding: 4px 8px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    max-width: 100px;
    height: 30px;
}

.qty-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    background: #fff;
    width: 90px;
}

.qty-btn {
    background: #f5f5f5;
    border: none;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.qty-btn:hover:not(:disabled) {
    background: #e5e5e5;
    color: #333;
}

.qty-btn:disabled {
    background: #f5f5f5;
    color: #ccc;
    cursor: not-allowed;
}

.qty-input {
    border: none;
    width: 34px;
    height: 28px;
    text-align: center;
    font-size: 12px;
    font-weight: 500;
    background: transparent;
    outline: none;
}

.qty-input:focus {
    outline: none;
    box-shadow: none;
    background: #f8f9fa;
}

.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.qty-input[type=number] {
    -moz-appearance: textfield;
}

.price-display {
    min-width: 80px;
}

.sale-price {
    color: #ee4d2d;
    font-size: 14px;
    font-weight: 700;
}

.current-price {
    color: #333;
    font-size: 14px;
    font-weight: 700;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 12px;
}

.item-total {
    color: #ee4d2d;
    font-size: 14px;
    font-weight: 700;
    min-width: 80px;
}

.delete-btn {
    background: none;
    border: none;
    color: #999;
    font-size: 14px;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.delete-btn:hover {
    background: #fee;
    color: #e74c3c;
}

/* Summary Section */
.summary-sticky {
    position: sticky;
    top: 20px;
}

.summary-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 14px;
}

.summary-total {
    background: #f8f9fa;
    padding: 16px;
    margin: -16px -16px 16px -16px;
    border-radius: 8px;
}

.payment-summary {
    background: #f8f9fa !important;
    border: 1px solid #e5e5e5 !important;
    border-radius: 8px !important;
    padding: 16px !important;
    margin-top: 16px;
}

.payment-summary .summary-row {
    margin-bottom: 8px;
    font-size: 14px;
}

.payment-summary .summary-row span:first-child {
    color: #666;
}

.payment-summary .summary-row span:last-child {
    color: #333;
    font-weight: 500;
}

.payment-summary .summary-total {
    background: transparent;
    padding: 0;
    margin: 0;
    border-radius: 0;
}

.payment-summary h6 {
    color: #333;
    font-weight: 700;
    margin-bottom: 12px;
}

.total-price {
    color: #ee4d2d !important;
    font-size: 24px !important;
    font-weight: 700 !important;
}

.original-total {
    color: #999 !important;
    font-size: 16px !important;
    font-weight: 500 !important;
}

/* Cart Summary Details */
.cart-summary-details {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 12px;
    max-height: 200px;
    overflow-y: auto;
}

.order-item {
    border-left: 3px solid #ee4d2d;
    padding-left: 8px;
    margin-bottom: 8px;
}

.order-item:last-child {
    margin-bottom: 0;
}

.variant-item {
    background: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #e5e5e5;
    margin-bottom: 4px;
}

.variant-item:last-child {
    margin-bottom: 0;
}

/* Voucher Section */
.voucher-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
}

.voucher-section .voucher-header h5 {
    color: #333;
    font-weight: 700;
    margin: 0;
}

.voucher-input {
    border: 1px solid #e5e5e5;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
    height: 45px;
    padding: 0 15px;
}

.voucher-input:focus {
    border-color: #ee4d2d;
    box-shadow: 0 0 0 0.2rem rgba(238, 77, 45, 0.15);
}

.voucher-input[readonly] {
    background-color: #f8fff9;
    border-color: #28a745;
}

.voucher-btn {
    background: #ee4d2d;
    border: 1px solid #ee4d2d;
    border-radius: 0 4px 4px 0;
    color: white;
    font-weight: 600;
    font-size: 14px;
    height: 45px;
    min-width: 100px;
    transition: all 0.3s ease;
}

.voucher-btn:hover {
    background: #d73527;
    border-color: #d73527;
    color: white;
}

.voucher-btn-cancel {
    background: #dc3545;
    border: 1px solid #dc3545;
    border-radius: 0 4px 4px 0;
    color: white;
    font-weight: 600;
    font-size: 14px;
    height: 45px;
    min-width: 100px;
    transition: all 0.3s ease;
}

.voucher-btn-cancel:hover {
    background: #c82333;
    border-color: #c82333;
    color: white;
}

.applied-voucher {
    font-size: 12px;
    padding: 8px 12px;
    background: #e8f5e8;
    border: 1px solid #4caf50;
    border-radius: 4px;
    color: #2e7d32;
}

/* Action Buttons */
.address-btn {
    border: 1px solid #ee4d2d;
    color: #ee4d2d;
    font-weight: 500;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.address-btn:hover {
    background: #ee4d2d;
    color: white;
}

.checkout-btn {
    background: linear-gradient(45deg, #ee4d2d, #ff6b35);
    border: none;
    color: white;
    font-weight: 600;
    height: 48px;
    font-size: 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.checkout-btn:hover {
    background: linear-gradient(45deg, #d73527, #e55a2b);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(238, 77, 45, 0.3);
}

.checkout-btn:disabled,
.btn-secondary:disabled {
    background: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
    opacity: 0.6;
}

.checkout-btn:disabled:hover,
.btn-secondary:disabled:hover {
    background: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Form check custom */
.form-check-input:checked {
    background-color: #ee4d2d;
    border-color: #ee4d2d;
}

.form-check-input:focus {
    border-color: #ee4d2d;
    box-shadow: 0 0 0 0.2rem rgba(238, 77, 45, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .variant-row .row {
        flex-direction: column;
        gap: 12px;
    }

    .summary-sticky {
        position: static;
    }

    .total-price {
        font-size: 20px !important;
    }
}
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-info-circle me-2"></i>{{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-exclamation-triangle me-2"></i>{{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="fa-solid fa-cart-shopping me-3 text-primary" style="font-size: 28px;"></i>
        <h2 class="mb-0 text-dark fw-bold">Giỏ hàng của tôi</h2>
        <span class="ms-3 badge bg-primary fs-6">{{ $carts->count() }} sản phẩm</span>
    </div>

    @if($carts->count() > 0)
    <div class="row g-4">
        <!-- Danh sách sản phẩm -->
        <div class="col-lg-8">
            <!-- Header điều khiển chọn tất cả và xóa -->
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" id="select-all" class="form-check-input">
                            <label class="form-check-label fw-bold" for="select-all">
                                Chọn tất cả (<span id="total-items">{{ $carts->count() }}</span>)
                            </label>
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 delete-selected" style="font-size: 14px;">
                            <i class="fa-solid fa-trash me-1"></i>Xóa
                        </button>
                    </div>
                </div>
            </div>

            @php
                $groupedCarts = $carts->groupBy('product_id');
            @endphp

            @foreach($groupedCarts as $productId => $productCarts)
                @php
                    $firstCart = $productCarts->first();
                    $product = $firstCart->product;
                    $totalProductQuantity = $productCarts->sum('quantity');
                    $productTotalPrice = $productCarts->sum(function($cart) {
                        return ($cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price) * $cart->quantity;
                    });
                @endphp

                <!-- Card Shopee-style cho từng sản phẩm -->
                <div class="card product-card mb-3 shadow-sm border-0" data-product-id="{{ $productId }}">
                    <!-- Header sản phẩm với checkbox và thông tin -->
                    <div class="card-header-custom">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input type="checkbox" class="form-check-input product-checkbox"
                                       id="product-{{ $productId }}"
                                       data-product-id="{{ $productId }}">
                                <label class="form-check-label" for="product-{{ $productId }}"></label>
                            </div>

                            <div class="shop-info">
                                <div class="product-image-wrapper me-3">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="product-image">
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name mb-1 fw-bold">{{ $product->name }}</h6>

                                </div>
                            </div>
                        </div>

                        <div class="product-summary text-end">

                            <small class="product-total-qty text-muted">{{ $totalProductQuantity }} sản phẩm</small>
                        </div>
                    </div>

                    <!-- Body chứa các variants -->
                    <div class="card-body p-0">
                        @foreach($productCarts as $index => $cart)
                        <div class="variant-row {{ $index < $productCarts->count() - 1 ? 'border-bottom' : '' }}"
                             data-cart-id="{{ $cart->id }}">
                            <div class="row align-items-center g-0 p-3">
                                <!-- Checkbox variant -->
                                <div class="col-auto me-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input cart-item-checkbox"
                                               id="cart-{{ $cart->id }}"
                                               value="{{ $cart->id }}"
                                               data-cart-id="{{ $cart->id }}"
                                               data-product-id="{{ $productId }}"
                                               data-price="{{ $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price }}"
                                               data-quantity="{{ $cart->quantity }}">
                                        <label class="form-check-label" for="cart-{{ $cart->id }}"></label>
                                    </div>
                                </div>

                                <!-- Hình ảnh variant -->
                                <div class="col-auto me-3">
                                    <div class="variant-image-wrapper">
                                        <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}"
                                             class="variant-image" alt="Variant">
                                    </div>
                                </div>

                                <!-- Thông tin variant -->
                                <div class="col variant-info-col">
                                    @if($cart->variant)
                                        @php
                                            $currentSize = $cart->variant->attributeValues->where('attribute.name', 'Size')->first();
                                            $currentColor = $cart->variant->attributeValues->where('attribute.name', 'Màu')->first();
                                            $productVariants = $cart->product->variants;
                                        @endphp

                                        <div class="variant-info d-flex flex-wrap gap-1">
                                            <!-- Size dropdown -->
                                            @if($currentSize)
                                                <select class="form-select variant-select"
                                                        data-cart-id="{{ $cart->id }}"
                                                        data-original-value="{{ $cart->variant_id }}">
                                                    @foreach($productVariants->flatMap(function($v) { return $v->attributeValues->filter(fn($val) => $val->attribute->name === 'Size'); })->unique('id') as $size)
                                                        <option value="{{ $productVariants->filter(function($v) use ($size, $currentColor) {
                                                            $hasSize = $v->attributeValues->contains('id', $size->id);
                                                            $hasColor = !$currentColor || $v->attributeValues->contains('id', $currentColor->id);
                                                            return $hasSize && $hasColor;
                                                        })->first()->id ?? '' }}"
                                                        {{ $currentSize->id == $size->id ? 'selected' : '' }}>
                                                            Size: {{ $size->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            <!-- Color dropdown -->
                                            @if($currentColor)
                                                <select class="form-select variant-select"
                                                        data-cart-id="{{ $cart->id }}"
                                                        data-original-value="{{ $cart->variant_id }}">
                                                    @foreach($productVariants->flatMap(function($v) { return $v->attributeValues->filter(fn($val) => $val->attribute->name === 'Màu'); })->unique('id') as $color)
                                                        <option value="{{ $productVariants->filter(function($v) use ($color, $currentSize) {
                                                            $hasColor = $v->attributeValues->contains('id', $color->id);
                                                            $hasSize = !$currentSize || $v->attributeValues->contains('id', $currentSize->id);
                                                            return $hasColor && $hasSize;
                                                        })->first()->id ?? '' }}"
                                                        {{ $currentColor->id == $color->id ? 'selected' : '' }}>
                                                            Màu: {{ $color->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    @else
                                        <span class="variant-badge">
                                            <i class="fa-solid fa-box me-1"></i>Sản phẩm cơ bản
                                        </span>
                                    @endif
                                </div>

                                <!-- Giá -->
                                <div class="col-auto price-col me-4">
                                    @php
                                        $currentPrice = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                                        $originalPrice = $cart->variant->price ?? $cart->product->price;
                                        $salePrice = $cart->variant->sale_price ?? $cart->product->sale_price;
                                    @endphp

                                    <div class="price-display text-end">
                                        @if($salePrice && $salePrice < $originalPrice)
                                            <div class="sale-price">{{ number_format($salePrice) }} VNĐ</div>
                                            <div class="original-price">{{ number_format($originalPrice) }} VNĐ</div>
                                        @else
                                            <div class="current-price">{{ number_format($currentPrice) }} VNĐ</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quantity controls -->
                                <div class="col-auto quantity-col me-3">
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="quantity-form">
                                        @csrf
                                        <div class="qty-wrapper">
                                            <button type="button" class="qty-btn qty-minus" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                                   min="1" max="999" class="qty-input">
                                            <button type="button" class="qty-btn qty-plus">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Tổng tiền item -->
                                <div class="col-auto total-col me-3">
                                    <div class="item-total text-end">
                                        {{ number_format($currentPrice * $cart->quantity) }} VNĐ
                                    </div>
                                </div>

                                <!-- Nút xóa -->
                                <div class="col-auto delete-col">
                                    <form action="{{ route('cart.delete', $cart->id) }}" method="POST"
                                          onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" title="Xóa sản phẩm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Phần nhập mã giảm giá -->
            <div class="card mb-3 shadow-sm border-0">
                    <div class="voucher-section">
                        <div class="voucher-header d-flex align-items-center mb-3">
                            <i class="fa-solid fa-ticket text-warning me-2 fs-5"></i>
                            <h5 class="fw-bold mb-0 text-dark">Mã giảm giá</h5>
                        </div>

                        <div class="voucher-input-group">
                            <div class="input-group">
                                <input type="text" class="form-control voucher-input"
                                       id="coupon-input"
                                       placeholder="Chọn hoặc nhập mã voucher..."
                                       value="">
                                <button class="btn voucher-btn"
                                        type="button" id="apply-coupon">
                                    <i class="fa-solid fa-tag me-1"></i>ÁP DỤNG
                                </button>
                            </div>
                            <div id="coupon-result" class="voucher-result mt-2"></div>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="summary-sticky">
                <div class="card summary-card mb-3 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="order-summary">
                            <h5 class="text-dark mb-3">
                                <i class="fa-solid fa-receipt me-2 text-primary"></i>
                                Tóm tắt đơn hàng
                            </h5>

                            <!-- Chi tiết sản phẩm trong giỏ hàng -->
                            <div class="cart-summary-details mb-3">
                                @php
                                    $orderIndex = 1;
                                @endphp
                                @foreach($groupedCarts as $productId => $productCarts)
                                    @php
                                        $product = $productCarts->first()->product;
                                    @endphp
                                    <div class="order-item mb-2">
                                        <div class="fw-semibold text-dark mb-1" style="font-size: 13px;">
                                            <i class="fa-solid fa-box me-1 text-primary"></i>
                                            Đơn thứ {{ $orderIndex }}: {{ Str::limit($product->name, 30) }}
                                        </div>
                                        @foreach($productCarts as $cart)
                                            <div class="variant-item ms-3 mb-1" style="font-size: 12px; color: #666;">
                                                <i class="fa-solid fa-angle-right me-1"></i>
                                                @if($cart->variant)
                                                    @php
                                                        $currentSize = $cart->variant->attributeValues->where('attribute.name', 'Size')->first();
                                                        $currentColor = $cart->variant->attributeValues->where('attribute.name', 'Màu')->first();
                                                        $variantInfo = [];
                                                        if($currentSize) $variantInfo[] = "Size: {$currentSize->value}";
                                                        if($currentColor) $variantInfo[] = "Màu: {$currentColor->value}";
                                                    @endphp
                                                    {{ implode(', ', $variantInfo) }}:
                                                    <span class="text-primary fw-semibold">{{ $cart->quantity }} sp</span>
                                                @else
                                                    Sản phẩm cơ bản:
                                                    <span class="text-primary fw-semibold">{{ $cart->quantity }} sp</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    @php $orderIndex++; @endphp
                                @endforeach
                            </div>

                            <hr class="my-3">

                            @php
                                $grandTotal = $carts->sum(function($cart){
                                    $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                                    return $price * $cart->quantity;
                                });
                                // Không lưu voucher discount từ session - chỉ tính real-time
                                $voucherDiscount = 0;
                                $finalTotal = $grandTotal;
                            @endphp
                        </div>

                        <!-- Tổng thanh toán - riêng biệt -->
                        <div class="payment-summary bg-light p-3 rounded">
                            <h6 class="mb-3 fw-bold text-dark">
                                <i class="fa-solid fa-calculator me-2 text-primary"></i>
                                Chi tiết thanh toán
                            </h6>

                            <div class="summary-row">
                                <span><i class="fa-solid fa-box me-2 text-muted"></i>Tổng tiền hàng:</span>
                                <span class="fw-semibold" id="cart-subtotal">VND0</span>
                            </div>

                            <div class="summary-row">
                                <span><i class="fa-solid fa-truck me-2 text-muted"></i>Tổng tiền phí vận chuyển:</span>
                                <span class="text-success fw-semibold">
                                    <i class="fa-solid fa-gift me-1"></i>Miễn phí
                                </span>
                            </div>

                            <div class="summary-row" id="payment-discount-row" style="display: none;">
                                <span class="text-success"><i class="fa-solid fa-ticket me-2"></i>Tổng cộng Voucher giảm giá:</span>
                                <span class="text-success fw-semibold" id="payment-discount">-VND0</span>
                            </div>

                            <div class="summary-total">
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                    <span class="fs-5 fw-bold"><i class="fa-solid fa-calculator me-2"></i>Tổng thanh toán:</span>
                                    <div class="text-end">
                                        <div class="total-price" id="final-total">
                                            VND0
                                        </div>
                                        <small class="text-muted">(Đã bao gồm VAT nếu có)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mt-4">
                            @auth
                            <a href="{{ route('client.addresses.index') }}"
                               class="btn btn-outline-primary w-100 mb-3 address-btn">
                                <i class="fa-solid fa-map-marker-alt me-2"></i>
                                Quản lý địa chỉ giao hàng
                            </a>
                            @endauth

                            @if($carts->count() > 0)
                            <button type="button"
                                    class="btn w-100 checkout-btn"
                                    id="checkout-button"
                                    onclick="proceedToCheckout()"
                                    disabled>
                                <i class="fa-solid fa-shopping-cart me-2"></i>
                                Mua hàng (<span id="selected-items-count">0</span>)
                            </button>

                            <form id="checkout-form" action="{{ route('cart.checkout') }}" method="GET" style="display: none;">
                                <input type="hidden" name="selected_items" id="selected-items-input">
                            </form>
                            @else
                            <div class="text-center text-muted py-3">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Không có sản phẩm nào trong giỏ hàng
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="empty-cart text-center py-5">
        <div class="empty-cart-icon mb-4">
            <i class="fa-solid fa-cart-shopping" style="font-size: 80px; color: #ddd;"></i>
        </div>
        <h4 class="text-muted mb-3">Giỏ hàng của bạn còn trống</h4>
        <p class="text-muted mb-4">Hãy chọn thêm sản phẩm để mua sắm nhé</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Global discount variable - chỉ lưu tạm thời trong trang cart
window.voucherDiscount = 0;
window.appliedCouponCode = '';
window.appliedCouponInfo = null;

document.addEventListener("DOMContentLoaded", function() {
    // Xử lý số lượng với AJAX
    document.querySelectorAll('.quantity-form').forEach(form => {
        const minus = form.querySelector('.qty-minus');
        const plus = form.querySelector('.qty-plus');
        const input = form.querySelector('input[name="quantity"]');
        const cartId = form.closest('.variant-row').getAttribute('data-cart-id');

        minus.addEventListener('click', function () {
            const currentVal = parseInt(input.value) || 1;
            if (currentVal > 1) {
                updateQuantityAjax(cartId, currentVal - 1, input, this);
            }
        });

        plus.addEventListener('click', function () {
            const currentVal = parseInt(input.value) || 1;
            updateQuantityAjax(cartId, currentVal + 1, input, this);
        });

        input.addEventListener('blur', function() {
            const newVal = parseInt(this.value) || 1;
            const currentVal = parseInt(this.getAttribute('data-original-value')) || 1;

            if (newVal !== currentVal && newVal >= 1) {
                updateQuantityAjax(cartId, newVal, this);
            }
        });

        input.addEventListener('focus', function() {
            this.setAttribute('data-original-value', this.value);
        });
    });

    function updateQuantityAjax(cartId, newQuantity, inputElement, buttonElement = null) {
        // Disable buttons to prevent spam clicks
        const form = inputElement.closest('.quantity-form');
        const buttons = form.querySelectorAll('.qty-btn');
        const variantRow = form.closest('.variant-row');

        buttons.forEach(btn => btn.disabled = true);
        if (buttonElement) {
            buttonElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        }

        // Add loading state
        variantRow.style.opacity = '0.7';

        fetch(`/cart/update-quantity/${cartId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity input
                inputElement.value = data.data.quantity;

                // Update item total display
                const itemTotalElement = variantRow.querySelector('.item-total');
                if (itemTotalElement) {
                    itemTotalElement.textContent = new Intl.NumberFormat('vi-VN').format(data.data.item_total) + ' VNĐ';
                }

                // Update checkbox data attributes với giá trị mới
                const checkbox = variantRow.querySelector('.cart-item-checkbox');
                if (checkbox) {
                    checkbox.setAttribute('data-quantity', data.data.quantity);
                    checkbox.setAttribute('data-price', data.data.price);

                    // Nếu checkbox đang được chọn, cập nhật voucher discount ngay lập tức
                    if (checkbox.checked && (window.appliedCouponCode || window.voucherDiscount > 0)) {
                        console.log('Quantity changed for selected item, updating voucher discount');
                        checkVoucherValidityOnSelection();
                    }
                }

                // Update minus button state
                const minusBtn = form.querySelector('.qty-minus');
                if (minusBtn) {
                    minusBtn.disabled = data.data.quantity <= 1;
                }

                // Recalculate totals - voucher sẽ được cập nhật trước
                calculateTotal();
                updateProductTotals();

                // Hiển thị thông báo cập nhật với thông tin voucher nếu có
                let successMessage = 'Đã cập nhật số lượng!';
                if (window.voucherDiscount > 0 && checkbox && checkbox.checked) {
                    successMessage += ` Mã giảm giá đã được điều chỉnh: ${window.voucherDiscount.toLocaleString()} VNĐ`;
                }
                showToast(successMessage, 'success');

            } else {
                // Revert input value on error
                inputElement.value = inputElement.getAttribute('data-original-value') || 1;
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            inputElement.value = inputElement.getAttribute('data-original-value') || 1;
            showToast('Có lỗi xảy ra khi cập nhật số lượng!', 'error');
        })
        .finally(() => {
            // Re-enable buttons
            buttons.forEach(btn => btn.disabled = false);

            // Restore button icons
            if (buttonElement) {
                const icon = buttonElement.classList.contains('qty-minus') ? 'fa-minus' : 'fa-plus';
                buttonElement.innerHTML = `<i class="fa-solid ${icon}"></i>`;
            }

            // Update minus button state based on current value
            const minusBtn = form.querySelector('.qty-minus');
            if (minusBtn) {
                minusBtn.disabled = parseInt(inputElement.value) <= 1;
            }

            // Remove loading state
            variantRow.style.opacity = '1';
        });
    }

    function updateProductTotals() {
        document.querySelectorAll('.product-card').forEach(productCard => {
            const productId = productCard.getAttribute('data-product-id');
            const variantRows = productCard.querySelectorAll('.variant-row');

            let totalQuantity = 0;
            let totalPrice = 0;

            variantRows.forEach(row => {
                const checkbox = row.querySelector('.cart-item-checkbox');
                if (checkbox) {
                    const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 0;
                    const price = parseFloat(checkbox.getAttribute('data-price')) || 0;

                    totalQuantity += quantity;
                    totalPrice += price * quantity;
                }
            });

            // Update product header totals
            const productTotalPrice = productCard.querySelector('.product-total-price');
            const productTotalQty = productCard.querySelector('.product-total-qty');

            if (productTotalPrice) {
                productTotalPrice.textContent = new Intl.NumberFormat('vi-VN').format(totalPrice) + ' VNĐ';
            }

            if (productTotalQty) {
                productTotalQty.textContent = totalQuantity + ' sản phẩm';
            }
        });
    }

    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;

        let bgColor = '#6c757d'; // Default info color
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
        else if (type === 'info') icon = 'fa-info-circle';

        toast.innerHTML = `<i class="fa-solid ${icon} me-2"></i>${message}`;

        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Hide toast after 4 seconds for info messages, 3 seconds for others
        const hideDelay = type === 'info' ? 4000 : 3000;
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, hideDelay);
    }

    // Xử lý checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');

    // Gọi calculateTotal ngay khi load trang để hiển thị đúng
    calculateTotal();

    // Load trạng thái checkbox đã lưu
    loadSavedSelections();

    if (selectAllCheckbox && itemCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            // Kiểm tra và cập nhật voucher trước khi tính toán total
            checkVoucherValidityOnSelection();

            calculateTotal();

            // Lưu trạng thái chọn
            saveSelections();
        });

        productCheckboxes.forEach(productCheckbox => {
            productCheckbox.addEventListener('change', function() {
                const productId = this.getAttribute('data-product-id');
                const productItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]`);

                productItems.forEach(item => {
                    item.checked = this.checked;
                });

                updateSelectAllState();

                // Kiểm tra và cập nhật voucher trước khi tính toán total
                checkVoucherValidityOnSelection();

                calculateTotal();

                // Lưu trạng thái chọn
                saveSelections();
            });
        });

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const productId = this.getAttribute('data-product-id');
                const productCheckbox = document.querySelector(`.product-checkbox[data-product-id="${productId}"]`);
                const productItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]`);
                const checkedItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]:checked`);

                if (productCheckbox) {
                    productCheckbox.checked = checkedItems.length === productItems.length;
                    productCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < productItems.length;
                }

                updateSelectAllState();

                // Kiểm tra và cập nhật voucher trước khi tính toán total
                checkVoucherValidityOnSelection();

                calculateTotal();

                // Lưu trạng thái chọn
                saveSelections();
            });
        });
    }

    // Functions để lưu và load trạng thái checkbox
    function saveSelections() {
        const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
        const selectedIds = Array.from(checkedItems).map(checkbox => checkbox.getAttribute('data-cart-id'));

        // Lưu vào localStorage với key dựa trên user
        const userId = {{ Auth::id() ?? 'guest' }};
        localStorage.setItem(`cart_selections_${userId}`, JSON.stringify(selectedIds));
    }

    function loadSavedSelections() {
        const userId = {{ Auth::id() ?? 'guest' }};
        const savedSelections = localStorage.getItem(`cart_selections_${userId}`);

        if (savedSelections) {
            try {
                const selectedIds = JSON.parse(savedSelections);

                // Khôi phục trạng thái checkbox
                selectedIds.forEach(cartId => {
                    const checkbox = document.querySelector(`.cart-item-checkbox[data-cart-id="${cartId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;

                        // Cập nhật checkbox sản phẩm tương ứng
                        const productId = checkbox.getAttribute('data-product-id');
                        updateProductCheckboxState(productId);
                    }
                });

                // Cập nhật trạng thái select all
                updateSelectAllState();

            } catch (error) {
                console.error('Error loading saved selections:', error);
            }
        }
    }

    function updateProductCheckboxState(productId) {
        const productCheckbox = document.querySelector(`.product-checkbox[data-product-id="${productId}"]`);
        const productItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]`);
        const checkedItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]:checked`);

        if (productCheckbox) {
            productCheckbox.checked = checkedItems.length === productItems.length;
            productCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < productItems.length;
        }
    }

    function clearDeletedSelections(deletedCartIds) {
        const userId = {{ Auth::id() ?? 'guest' }};
        const savedSelections = localStorage.getItem(`cart_selections_${userId}`);

        if (savedSelections) {
            try {
                let selectedIds = JSON.parse(savedSelections);
                // Loại bỏ các ID đã bị xóa
                selectedIds = selectedIds.filter(id => !deletedCartIds.includes(id));
                localStorage.setItem(`cart_selections_${userId}`, JSON.stringify(selectedIds));
            } catch (error) {
                console.error('Error clearing deleted selections:', error);
            }
        }
    }

    // Thêm event handler cho nút xóa
    const deleteSelectedBtn = document.querySelector('.delete-selected');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function() {
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');

            if (checkedItems.length === 0) {
                alert('Vui lòng chọn sản phẩm cần xóa!');
                return;
            }

            const selectedIds = Array.from(checkedItems).map(checkbox => checkbox.getAttribute('data-cart-id'));
            const confirmMessage = `Bạn có chắc chắn muốn xóa ${checkedItems.length} sản phẩm đã chọn?`;

            if (confirm(confirmMessage)) {
                deleteSelectedItems(selectedIds);
            }
        });
    }

    function deleteSelectedItems(cartIds) {
        const deleteBtn = document.querySelector('.delete-selected');
        const originalText = deleteBtn.innerHTML;

        // Disable button và hiển thị loading
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Đang xóa...';

        fetch('/cart/delete-selected', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                selected_items: cartIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear saved selections for deleted items
                clearDeletedSelections(cartIds);

                // Reload trang để cập nhật danh sách
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm!');
        })
        .finally(() => {
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
        });
    }

    function updateSelectAllState() {
        if (selectAllCheckbox) {
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
            const totalItems = document.querySelectorAll('.cart-item-checkbox');

            selectAllCheckbox.checked = checkedItems.length === totalItems.length;
            selectAllCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < totalItems.length;
        }
    }

    function calculateTotal() {
        const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
        const allItems = document.querySelectorAll('.cart-item-checkbox');
        let totalQuantity = 0;
        let subtotal = 0;

        // Nếu không có cart items nào, có thể cart đã bị xóa hết
        if (allItems.length === 0) {
            console.log('No cart items found - cart may be empty');
            // Có thể reload trang hoặc redirect về home
            return;
        }

        checkedItems.forEach(checkbox => {
            const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            totalQuantity += quantity;
            subtotal += price * quantity;
        });

        const selectedQuantityEl = document.getElementById('selected-quantity');
        const subtotalAmountEl = document.getElementById('subtotal-amount');
        const cartSubtotalEl = document.getElementById('cart-subtotal');
        const selectedItemsCountEl = document.getElementById('selected-items-count');
        const totalItemsEl = document.getElementById('total-items');

        if (selectedQuantityEl) selectedQuantityEl.textContent = totalQuantity;
        if (subtotalAmountEl) subtotalAmountEl.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VNĐ';
        if (cartSubtotalEl) cartSubtotalEl.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VNĐ';
        if (selectedItemsCountEl) selectedItemsCountEl.textContent = checkedItems.length;
        if (totalItemsEl) totalItemsEl.textContent = document.querySelectorAll('.cart-item-checkbox').length;

        // Update cart summary details
        updateCartSummaryDetails();

        // Lấy giá trị discount từ session/backend và chỉ áp dụng cho sản phẩm được chọn
        const discountAmountEl = document.getElementById('discount-amount');
        const paymentDiscountEl = document.getElementById('payment-discount');
        const paymentDiscountRowEl = document.getElementById('payment-discount-row');
        const finalTotalEl = document.getElementById('final-total');
        const originalTotalEl = document.getElementById('original-total');

        // Tự động hủy mã giảm giá nếu không có sản phẩm nào được chọn
        if (checkedItems.length === 0 && window.voucherDiscount > 0) {
            autoRemoveCoupon();
            return; // Dừng tính toán để tránh conflict
        }

        // Tính toán discount biến động theo sản phẩm được chọn
        let discount = 0;
        if (window.voucherDiscount > 0 && subtotal > 0) {
            // Đảm bảo discount không vượt quá subtotal
            discount = Math.min(window.voucherDiscount, subtotal);
            console.log('Discount calculation in calculateTotal:', {
                voucherDiscount: window.voucherDiscount,
                subtotal: subtotal,
                finalDiscount: discount,
                appliedCouponInfo: window.appliedCouponInfo
            });
        }

        const finalTotal = Math.max(0, subtotal - discount);

        console.log('Total calculation:', {
            subtotal: subtotal,
            discount: discount,
            finalTotal: finalTotal,
            hasVoucher: window.voucherDiscount > 0
        });

        // Cập nhật discount amount trong phần payment summary
        if (paymentDiscountEl) {
            paymentDiscountEl.textContent = discount > 0 && subtotal > 0 ? '-' + new Intl.NumberFormat('vi-VN').format(discount) + ' VNĐ' : '-0 VNĐ';
            console.log('Updated payment discount element:', paymentDiscountEl.textContent);
        }

        // Hiển thị/ẩn dòng discount trong payment summary
        if (paymentDiscountRowEl) {
            paymentDiscountRowEl.style.display = discount > 0 && subtotal > 0 ? 'flex' : 'none';
            console.log('Payment discount row display:', paymentDiscountRowEl.style.display);
        }

        // Cập nhật tổng cuối
        if (finalTotalEl) {
            finalTotalEl.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' VNĐ';
        }

        // Hiển thị/ẩn giá gốc và thông tin tiết kiệm
        updatePricingDisplay(subtotal, discount, finalTotal);

        // Enable/disable checkout button
        updateCheckoutButton(checkedItems.length > 0);
    }

    function updatePricingDisplay(subtotal, discount, finalTotal) {
        const summaryTotalContainer = document.querySelector('.summary-total .text-end');
        if (!summaryTotalContainer) return;

        let priceHTML = '';

        if (discount > 0 && subtotal > 0) {
            // Có voucher và có sản phẩm được chọn - hiển thị giá gốc bị gạch và giá sau giảm
            priceHTML = `
                <div class="original-total text-decoration-line-through text-muted mb-1" id="original-total">
                    ${new Intl.NumberFormat('vi-VN').format(subtotal)} VNĐ
                </div>
                <div class="total-price" id="final-total">
                    ${new Intl.NumberFormat('vi-VN').format(finalTotal)} VNĐ
                </div>
                <small class="text-success fw-semibold">Tiết kiệm: ${new Intl.NumberFormat('vi-VN').format(discount)} VNĐ</small>
            `;
        } else {
            // Không có voucher hoặc chưa chọn sản phẩm - chỉ hiển thị tổng tiền bình thường
            priceHTML = `
                <div class="total-price" id="final-total">
                    ${new Intl.NumberFormat('vi-VN').format(finalTotal)} VNĐ
                </div>
                <small class="text-muted">(Đã bao gồm VAT nếu có)</small>
            `;
        }

        summaryTotalContainer.innerHTML = priceHTML;
    }

    function updateCheckoutButton(hasSelectedItems) {
        const checkoutBtn = document.getElementById('checkout-button');
        if (checkoutBtn) {
            // Kiểm tra xem còn cart items không
            const totalCartItems = document.querySelectorAll('.cart-item-checkbox').length;

            if (totalCartItems === 0) {
                // Nếu cart trống, ẩn button hoặc disable hoàn toàn
                checkoutBtn.style.display = 'none';
                return;
            }

            checkoutBtn.disabled = !hasSelectedItems;
            if (hasSelectedItems) {
                checkoutBtn.classList.remove('btn-secondary');
                checkoutBtn.classList.add('checkout-btn');
            } else {
                checkoutBtn.classList.remove('checkout-btn');
                checkoutBtn.classList.add('btn-secondary');
            }
        }
    }

    function updateCartSummaryDetails() {
        const cartSummaryDetails = document.querySelector('.cart-summary-details');
        if (!cartSummaryDetails) return;

        let summaryHTML = '';
        let orderIndex = 1;

        document.querySelectorAll('.product-card').forEach(productCard => {
            const productName = productCard.querySelector('.product-name')?.textContent || 'Sản phẩm';
            const variantRows = productCard.querySelectorAll('.variant-row');

            // Check if any variant in this product is selected
            const hasSelectedVariants = Array.from(variantRows).some(row => {
                const checkbox = row.querySelector('.cart-item-checkbox');
                return checkbox && checkbox.checked;
            });

            if (hasSelectedVariants) {
                summaryHTML += `
                    <div class="order-item mb-2">
                        <div class="fw-semibold text-dark mb-1" style="font-size: 13px;">
                            <i class="fa-solid fa-box me-1 text-primary"></i>
                            Đơn thứ ${orderIndex}: ${productName.length > 30 ? productName.substring(0, 30) + '...' : productName}
                        </div>
                `;

                variantRows.forEach(row => {
                    const checkbox = row.querySelector('.cart-item-checkbox');
                    if (checkbox && checkbox.checked) {
                        const quantity = checkbox.getAttribute('data-quantity') || '0';
                        const sizeSelect = row.querySelector('select[data-cart-id] option:checked');
                        const colorSelects = row.querySelectorAll('select[data-cart-id]');

                        let variantInfo = [];

                        // Get variant info from selects
                        colorSelects.forEach(select => {
                            const selectedOption = select.options[select.selectedIndex];
                            if (selectedOption && selectedOption.textContent) {
                                const text = selectedOption.textContent.trim();
                                if (text && text !== '') {
                                    variantInfo.push(text);
                                }
                            }
                        });

                        const variantText = variantInfo.length > 0 ? variantInfo.join(', ') : 'Sản phẩm cơ bản';

                        summaryHTML += `
                            <div class="variant-item ms-3 mb-1" style="font-size: 12px; color: #666;">
                                <i class="fa-solid fa-angle-right me-1"></i>
                                ${variantText}:
                                <span class="text-primary fw-semibold">${quantity} sp</span>
                            </div>
                        `;
                    }
                });

                summaryHTML += '</div>';
                orderIndex++;
            }
        });

        if (summaryHTML === '') {
            summaryHTML = `
                <div class="text-center text-muted py-2">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Chưa có sản phẩm nào được chọn
                </div>
            `;
        }

        cartSummaryDetails.innerHTML = summaryHTML;
    }

    // Xử lý thay đổi variant
    document.querySelectorAll('.variant-select').forEach(select => {
        select.addEventListener('change', function() {
            const cartId = this.getAttribute('data-cart-id');
            const variantId = this.value;

            if (!cartId || !variantId) return;

            const variantItem = this.closest('.variant-row');
            if (variantItem) {
                variantItem.classList.add('loading');
            }

            fetch(`/cart/switch-variant/${cartId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    variant_id: variantId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi đổi variant');
                    this.value = this.getAttribute('data-original-value');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đổi variant');
                this.value = this.getAttribute('data-original-value');
            })
            .finally(() => {
                if (variantItem) {
                    variantItem.classList.remove('loading');
                }
            });
        });
    });

    // Xử lý voucher
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

    // Không load từ session - luôn bắt đầu với trạng thái mặc định

    // Function xử lý chung cho button
    function handleCouponButton() {
        if (isInCancelMode) {
            handleCancelCoupon();
        } else {
            handleApplyCoupon();
        }
    }

    // Gán event listener
    applyCouponBtn.addEventListener('click', handleCouponButton);

    couponInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !isInCancelMode) {
            handleCouponButton();
        }
    });

    function handleApplyCoupon() {
        const couponCode = couponInput.value.trim();

        if (!couponCode) {
            showCouponMessage('Vui lòng nhập mã voucher', 'error');
            return;
        }

        // Kiểm tra xem có sản phẩm nào được chọn không
        const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
        if (checkedItems.length === 0) {
            showCouponMessage('Vui lòng chọn ít nhất một sản phẩm trước khi áp dụng mã giảm giá', 'error');
            return;
        }

        // Disable button và hiển thị loading
        applyCouponBtn.disabled = true;
        applyCouponBtn.textContent = 'Đang xử lý...';

        // Tính subtotal hiện tại của sản phẩm được chọn
        let selectedSubtotal = 0;
        checkedItems.forEach(checkbox => {
            const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            selectedSubtotal += price * quantity;
        });

        // Gọi API validate coupon nhưng không lưu session
        fetch('/cart/validate-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                subtotal: selectedSubtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Validate coupon response:', data);

            if (data.success) {
                // Chỉ lưu thông tin voucher tạm thời trong JavaScript
                appliedCoupon = data;
                window.appliedCouponCode = couponCode;

                // Lưu đầy đủ thông tin coupon để tính toán lại sau này
                window.appliedCouponInfo = {
                    code: couponCode,
                    type: data.coupon_info?.type || data.coupon_info?.discount_type || 'fixed',
                    discount_type: data.coupon_info?.discount_type || 'fixed',
                    value: data.coupon_info?.value || data.coupon_info?.discount || data.discount_amount || 0,
                    discount: data.coupon_info?.discount || data.discount_amount || 0,
                    max_discount_amount: data.coupon_info?.max_discount_amount || 0,
                    max_discount: data.coupon_info?.max_discount || 0,
                    original_subtotal: selectedSubtotal // Lưu subtotal ban đầu để tham khảo
                };

                console.log('Saved coupon info:', window.appliedCouponInfo);

                // Cập nhật discount amount trong biến global
                if (data.discount_amount) {
                    window.voucherDiscount = data.discount_amount;
                    console.log('Set initial voucher discount to:', window.voucherDiscount);
                } else {
                    window.voucherDiscount = 0;
                }

                // Hiển thị UI success
                showCouponSuccess(data.message, window.appliedCouponInfo);
                switchToCancelMode();

                // Tính toán lại total với discount
                calculateTotal();

                // Hiển thị thông báo thành công với thông tin chi tiết
                const savingsAmount = data.discount_amount || 0;
                const discountPercent = selectedSubtotal > 0 ? Math.round((savingsAmount / selectedSubtotal) * 100) : 0;

                showToast(`Áp dụng mã giảm giá thành công! Tiết kiệm ${savingsAmount.toLocaleString()} VNĐ (${discountPercent}%)`, 'success');
            } else {
                showCouponMessage(data.message || 'Mã voucher không hợp lệ', 'error');
                resetCouponButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('Có lỗi xảy ra khi áp dụng voucher', 'error');
            resetCouponButton();
        });
    }

    function handleCancelCoupon() {
        // Chỉ reset local variables, không gọi API
        appliedCoupon = null;
        window.appliedCouponCode = '';
        window.appliedCouponInfo = null;
        window.voucherDiscount = 0;

        // Reset UI elements
        couponInput.value = '';
        couponResult.innerHTML = '';

        // Tính toán lại total
        calculateTotal();
        resetCouponButton();
        isInCancelMode = false;

        showToast('Đã hủy mã giảm giá', 'success');
    }

    function switchToCancelMode() {
        couponInput.readOnly = true;
        couponInput.style.backgroundColor = '#f8fff9';
        couponInput.style.borderColor = '#28a745';

        applyCouponBtn.innerHTML = '<i class="fa-solid fa-times me-1"></i>HỦY';
        applyCouponBtn.className = 'btn voucher-btn-cancel';
        applyCouponBtn.disabled = false;

        isInCancelMode = true;
    }

    function resetCouponButton() {
        couponInput.readOnly = false;
        couponInput.style.backgroundColor = '';
        couponInput.style.borderColor = '';

        applyCouponBtn.disabled = false;
        applyCouponBtn.innerHTML = 'ÁP DỤNG';
        applyCouponBtn.className = 'btn voucher-btn';
    }

    function showCouponMessage(message, type) {
        const className = type === 'success' ? 'applied-voucher' : 'text-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';

        couponResult.innerHTML = `
            <div class="${className}">
                <i class="fa-solid ${icon} me-1"></i>
                <span>${message}</span>
            </div>
        `;
    }

    function showCouponSuccess(message, couponInfo) {
        const successHtml = `
            <div class="applied-voucher">
                <i class="fa-solid fa-check-circle text-success me-1"></i>
                <span class="text-success">Đã áp dụng: <strong>${couponInfo.code}</strong></span>
            </div>
        `;
        couponResult.innerHTML = successHtml;
    }

    // Hàm tự động hủy mã giảm giá khi không có sản phẩm nào được chọn
    function autoRemoveCoupon() {
        console.log('Auto removing coupon - no products selected');

        // Chỉ reset local variables, không gọi API
        appliedCoupon = null;
        window.appliedCouponCode = '';
        window.appliedCouponInfo = null;
        window.voucherDiscount = 0;

        // Reset UI elements
        const couponInput = document.getElementById('coupon-input');
        const couponResult = document.getElementById('coupon-result');
        const applyCouponBtn = document.getElementById('apply-coupon');

        if (couponInput) {
            couponInput.value = '';
            couponInput.readOnly = false;
            couponInput.style.backgroundColor = '';
            couponInput.style.borderColor = '';
        }

        if (couponResult) {
            couponResult.innerHTML = '';
        }

        if (applyCouponBtn) {
            applyCouponBtn.disabled = false;
            applyCouponBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
            applyCouponBtn.className = 'btn voucher-btn';
        }

        // Reset global variables
        isInCancelMode = false;

        // Tính toán lại tổng tiền
        calculateTotal();

        showToast('Đã tự động hủy mã giảm giá do không có sản phẩm nào được chọn', 'info');
    }

    // Hàm kiểm tra và cập nhật voucher discount khi có thay đổi trong selection
    function checkVoucherValidityOnSelection() {
        console.log('checkVoucherValidityOnSelection called');

        // Chỉ kiểm tra nếu có voucher được áp dụng
        if (!window.appliedCouponCode || !window.appliedCouponInfo) {
            console.log('No coupon applied, skipping recalculation');
            return;
        }

        const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');

        // Nếu không có sản phẩm nào được chọn, hàm autoRemoveCoupon đã xử lý trong calculateTotal
        if (checkedItems.length === 0) {
            console.log('No items selected, auto remove will handle this');
            return;
        }

        // Tính subtotal của sản phẩm được chọn
        let currentSubtotal = 0;
        checkedItems.forEach(checkbox => {
            const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            currentSubtotal += price * quantity;
        });

        console.log('Current selection subtotal:', currentSubtotal);

        // Tính toán lại discount dựa trên coupon info và subtotal hiện tại
        if (currentSubtotal > 0 && window.appliedCouponInfo) {
            let newDiscount = 0;
            const couponInfo = window.appliedCouponInfo;

            console.log('Recalculating discount with:', {
                couponInfo: couponInfo,
                currentSubtotal: currentSubtotal,
                oldDiscount: window.voucherDiscount
            });

            // Tính toán discount mới dựa trên loại coupon
            if (couponInfo.type === 'percentage' || couponInfo.discount_type === 'percentage') {
                const percentage = couponInfo.value || couponInfo.discount || 0;
                newDiscount = (currentSubtotal * percentage) / 100;

                // Áp dụng max discount nếu có
                const maxDiscount = couponInfo.max_discount_amount || couponInfo.max_discount || 0;
                if (maxDiscount > 0) {
                    newDiscount = Math.min(newDiscount, maxDiscount);
                }

                console.log('Percentage calculation:', {
                    percentage: percentage,
                    calculated: (currentSubtotal * percentage) / 100,
                    maxDiscount: maxDiscount,
                    finalDiscount: newDiscount
                });
            } else {
                // Fixed amount - giữ nguyên giá trị nhưng không vượt quá subtotal
                const fixedAmount = couponInfo.value || couponInfo.discount || 0;
                newDiscount = Math.min(fixedAmount, currentSubtotal);

                console.log('Fixed amount calculation:', {
                    fixedAmount: fixedAmount,
                    currentSubtotal: currentSubtotal,
                    finalDiscount: newDiscount
                });
            }

            // Cập nhật global discount
            const oldDiscount = window.voucherDiscount;
            window.voucherDiscount = newDiscount;

            console.log('Updated voucher discount from', oldDiscount, 'to', newDiscount);

            // Hiển thị thông báo nếu discount thay đổi
            if (Math.abs(newDiscount - oldDiscount) > 1) {
                const changeType = newDiscount > oldDiscount ? 'tăng' : 'giảm';
                const percentChange = oldDiscount > 0 ? Math.round(((newDiscount - oldDiscount) / oldDiscount) * 100) : 100;
                showToast(`Mã giảm giá đã ${changeType}: ${newDiscount.toLocaleString()} VNĐ cho ${checkedItems.length} sản phẩm được chọn`, 'info');
            }
        }
    }

    calculateTotal();
});

function proceedToCheckout() {
    console.log('proceedToCheckout called');

    const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
    const allItems = document.querySelectorAll('.cart-item-checkbox');

    console.log('Found checkboxes:', {
        total: allItems.length,
        checked: checkedItems.length
    });

    // Fallback: nếu không có checkbox nào, có thể là đã thanh toán rồi
    if (allItems.length === 0) {
        console.log('No cart items found - possibly after successful payment');
        // Redirect to orders page or home
        window.location.href = '/orders';
        return;
    }

    if (checkedItems.length === 0) {
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán');
        return;
    }

    const selectedItems = Array.from(checkedItems).map(checkbox => {
        return checkbox.getAttribute('data-cart-id');
    }).filter(id => id);

    console.log('Selected items:', selectedItems);

    // Nếu có voucher được áp dụng, lưu vào session trước khi checkout
    if (window.appliedCouponCode && window.voucherDiscount > 0) {
        // Tính subtotal của sản phẩm được chọn để gửi đến backend
        let selectedSubtotal = 0;
        checkedItems.forEach(checkbox => {
            const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            selectedSubtotal += price * quantity;
        });

        // Gọi API để lưu voucher vào session
        fetch('/cart/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                coupon_code: window.appliedCouponCode,
                subtotal: selectedSubtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Coupon saved to session:', data);
            // Sau khi lưu voucher thành công, tiến hành checkout
            submitCheckoutForm(selectedItems);
        })
        .catch(error => {
            console.error('Error saving coupon:', error);
            // Nếu có lỗi khi lưu voucher, vẫn tiến hành checkout nhưng hiển thị warning
            console.warn('Proceeding to checkout without coupon due to error');
            submitCheckoutForm(selectedItems);
        });
    } else {
        // Không có voucher, tiến hành checkout trực tiếp
        submitCheckoutForm(selectedItems);
    }
}

function submitCheckoutForm(selectedItems) {
    const checkoutForm = document.getElementById('checkout-form');
    const selectedItemsInput = document.getElementById('selected-items-input');

    if (checkoutForm && selectedItemsInput) {
        selectedItemsInput.value = selectedItems.join(',');
        checkoutForm.submit();
    }
}
</script>
@endpush
