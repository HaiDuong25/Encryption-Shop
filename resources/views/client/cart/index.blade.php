@extends('client.layout.main')
@section('content')

    <style>
        /* Shopee-style Cart Design */
        .product-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
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
                                    <button type="button" class="btn btn-link text-danger p-0 delete-selected"
                                        style="font-size: 14px;">
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
                                    $productTotalPrice = $productCarts->sum(function ($cart) {
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
                                                    id="product-{{ $productId }}" data-product-id="{{ $productId }}">
                                                <label class="form-check-label" for="product-{{ $productId }}"></label>
                                            </div>

                                            <div class="shop-info">
                                                <div class="product-image-wrapper me-3">
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                        class="product-image">
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-name mb-1 fw-bold">{{ $product->name }}</h6>
                                                    <div class="product-meta small text-muted">
                                                        <span class="brand-name">{{ $product->brand->name ?? 'Đang cập nhật' }}</span>
                                                        @if($productCarts->count() > 1)
                                                            <span class="mx-2">•</span>
                                                            <span class="variant-count text-primary">{{ $productCarts->count() }} phân
                                                                loại</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                                                                    @php
                                                                        $currentSize = $cart->variant ? $cart->variant->attributeValues->where('attribute.name', 'Size')->first() : null;
                                                                        $currentColor = $cart->variant ? $cart->variant->attributeValues->where('attribute.name', 'Màu')->first() : null;
                                                                        $variantText = '';
                                                                        if ($cart->variant) {
                                                                            $variantInfo = [];
                                                                            if ($currentSize && $currentSize->value) {
                                                                                $variantInfo[] = "Size: " . $currentSize->value;
                                                                            }
                                                                            if ($currentColor && $currentColor->value) {
                                                                                $variantInfo[] = "Màu: " . $currentColor->value;
                                                                            }
                                                                            $variantText = !empty($variantInfo) ? implode(', ', $variantInfo) : 'Biến thể #' . $cart->variant_id;
                                                                        } else {
                                                                            $variantText = 'Sản phẩm cơ bản';
                                                                        }
                                                                    @endphp
                                                                    <input type="checkbox" class="form-check-input cart-item-checkbox"
                                                                        id="cart-{{ $cart->id }}" value="{{ $cart->id }}" data-cart-id="{{ $cart->id }}"
                                                                        data-product-id="{{ $productId }}"
                                                                        data-product-name="{{ $cart->product->name }}"
                                                                        data-variant-text="{{ $variantText }}"
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
                                                                                        <!-- Size display -->
                                                                                        @if($currentSize)
                                                                                            <span class="variant-badge">
                                                                                                <i class="fa-solid fa-ruler me-1"></i>Size: {{ $currentSize->value }}
                                                                                            </span>
                                                                                        @endif

                                                                                        <!-- Color display -->
                                                                                        @if($currentColor)
                                                                                            <span class="variant-badge">
                                                                                                <i class="fa-solid fa-palette me-1"></i>Màu: {{ $currentColor->value }}
                                                                                            </span>
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
                                                                        <div class="sale-price">{{ format_vnd($salePrice) }} VNĐ</div>
                                                                        <div class="original-price">{{ format_vnd($originalPrice) }} VNĐ</div>
                                                                    @else
                                                                        <div class="current-price">{{ format_vnd($currentPrice) }} VNĐ</div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Quantity controls -->
                                                            <div class="col-auto quantity-col me-3">
                                                                <div class="qty-wrapper">
                                                                    <button type="button" class="qty-btn minus-btn"
                                                                        data-cart-id="{{ $cart->id }}"
                                                                        {{ $cart->quantity <= 1 ? 'disabled' : '' }}>
                                                                        <i class="fa-solid fa-minus"></i>
                                                                    </button>
                                                                    <input type="number" class="qty-input"
                                                                        value="{{ $cart->quantity }}"
                                                                        min="1" max="99"
                                                                        data-cart-id="{{ $cart->id }}"
                                                                        data-original-value="{{ $cart->quantity }}">
                                                                    <button type="button" class="qty-btn plus-btn"
                                                                        data-cart-id="{{ $cart->id }}">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <!-- Tổng tiền item -->
                                                            <div class="col-auto total-col me-3">
                                                                <div class="item-total text-end">
                                                                    {{ format_vnd($currentPrice * $cart->quantity) }} VNĐ
                                                                </div>
                                                            </div>

                                                            <!-- Nút xóa -->
                                                            <div class="col-auto delete-col">
                                                                <form action="{{ route('cart.delete', $cart->id) }}" method="POST"
                                                                    onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')" class="d-inline">
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

                        <!-- Phần chọn mã giảm giá -->
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="voucher-section">
                                <div class="voucher-header d-flex align-items-center mb-3">
                                    <i class="fa-solid fa-ticket text-warning me-2 fs-5"></i>
                                    <h5 class="fw-bold mb-0 text-dark">Mã giảm giá đã lưu</h5>
                                    <span class="ms-auto badge bg-info" id="available-coupons-count">
                                        0 mã khả dụng
                                    </span>
                                </div>

                                <div class="voucher-input-group">
                                    <!-- Select coupon tab -->
                                    <div id="select-coupon-tab" class="voucher-tab-content">
                                        <div class="input-group mb-2">
                                            <select class="form-select voucher-input" id="coupon-select">
                                                <option value="">-- Đang tải mã giảm giá... --</option>
                                            </select>
                                            <button class="btn voucher-btn" type="button" id="apply-coupon-select" disabled>
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
                                                        <script>
                                                        // Hiển thị % hoặc VNĐ cho chi tiết mã giảm giá
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const discountSpan = document.getElementById('detail-discount');
                                                            if (discountSpan && discountSpan.textContent !== '-') {
                                                                let val = discountSpan.textContent.trim();
                                                                if (!val.includes('%') && !val.toLowerCase().includes('vnđ') && !val.toLowerCase().includes('đ')) {
                                                                    if (parseInt(val) <= 100) {
                                                                        discountSpan.textContent = val + '%';
                                                                    } else {
                                                                        discountSpan.textContent = new Intl.NumberFormat('vi-VN').format(val) + ' VNĐ';
                                                                    }
                                                                }
                                                            }
                                                        });
                                                        </script>
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
                                        </div>
                                    </div>

                                    <!-- Input coupon tab -->
                                    <div id="input-coupon-tab" class="voucher-tab-content" style="display: none;">
                                        <div class="input-group">
                                            <input type="text" class="form-control voucher-input" id="coupon-input"
                                                placeholder="Nhập mã voucher..." value="">
                                            <button class="btn voucher-btn" type="button" id="apply-coupon-input">
                                                <i class="fa-solid fa-tag me-1"></i>ÁP DỤNG
                                            </button>
                                        </div>

                                        <div class="mt-2 text-center">
                                            <small class="text-muted">
                                                <i class="fa-solid fa-lightbulb me-1"></i>
                                                Chưa có mã giảm giá? <a href="{{ route('coupons.index') }}" class="text-primary">Xem mã khả dụng</a>
                                            </small>
                                        </div>
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
                                        <div class="cart-summary-details mb-3" id="cart-summary-details">
                                            <div id="no-selected-items" class="text-center text-muted py-3">
                                                <i class="fa-solid fa-info-circle me-1"></i>
                                                Chưa chọn sản phẩm nào
                                            </div>
                                        </div>

                                        <hr class="my-3">

                                        @php
                                            $grandTotal = $carts->sum(function ($cart) {
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
                                            <span class="text-success"><i class="fa-solid fa-ticket me-2"></i>Tổng cộng Voucher giảm
                                                giá:</span>
                                            <span class="text-success fw-semibold" id="payment-discount">-VND0</span>
                                        </div>

                                        <div class="summary-total">
                                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                                <span class="fs-5 fw-bold"><i class="fa-solid fa-calculator me-2"></i>Tổng thanh
                                                    toán:</span>
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
                                        <button type="button" class="btn w-100 checkout-btn" id="checkout-button"
                                            onclick="proceedToCheckout()" disabled>
                                            <i class="fa-solid fa-shopping-cart me-2"></i>
                                            Mua hàng (<span id="selected-items-count">0</span>)
                                        </button>

                                        <form id="checkout-form" action="{{ route('cart.checkout') }}" method="GET"
                                            style="display: none;">
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


    @push('scripts')
    <script>
        // Global variables
        window.voucherDiscount = 0;
        window.appliedCouponCode = '';
        window.appliedCouponInfo = null;

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize functions
            initializeCouponManager();
            setupVoucherHandling();
            setupQuantityControls(); // Add this

            // Load saved selections first
            loadSavedSelections();

            // Then calculate total (this will update button state)
            calculateTotal();

            // Checkbox management
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');

            if (selectAllCheckbox && itemCheckboxes.length > 0) {
                selectAllCheckbox.addEventListener('change', function() {
                    productCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });

                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });

                    calculateTotal();
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
                        calculateTotal();
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
                        calculateTotal();
                        saveSelections();
                    });
                });
            }

            // Delete selected functionality
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
                    } else {
                        selectTab.style.display = 'none';
                        inputTab.style.display = 'block';
                        hideCouponDetails();
                    }

                    // Reset any applied coupons when switching tabs
                    resetAllCoupons();
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

            // Define all functions within the DOMContentLoaded scope
            // Định nghĩa function loadSavedCouponsToDropdown để tránh lỗi ReferenceError
            function loadSavedCouponsToDropdown() {
                const savedCouponsPromise = window.couponManager ? window.couponManager.getSavedCoupons() : Promise.resolve([]);
                const couponSelect = document.getElementById('coupon-select');
                const couponCountBadge = document.getElementById('available-coupons-count');
                const statusMessage = document.getElementById('coupon-status-message');

                if (!couponSelect) return;

                savedCouponsPromise.then(savedCoupons => {
                    // Update count badge
                    if (couponCountBadge) {
                        couponCountBadge.textContent = `${savedCoupons.length} mã khả dụng`;
                    }

                    // Clear existing options
                    couponSelect.innerHTML = '<option value="">-- Chọn mã giảm giá --</option>';

                    if (savedCoupons.length === 0) {
                        couponSelect.innerHTML += '<option value="" disabled>Không có mã giảm giá đã lưu</option>';
                        if (statusMessage) {
                            statusMessage.innerHTML = `
                                <small class="text-muted">
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    Bạn chưa lưu mã giảm giá nào.
                                    <a href="#" class="text-primary" onclick="alert('Tính năng đang phát triển')">Xem mã khả dụng</a>
                                </small>
                            `;
                        }
                        return;
                    }

                    // Add saved coupons to dropdown
                    savedCoupons.forEach(coupon => {
                        const option = document.createElement('option');
                        option.value = coupon.code;

                        // Hiển thị % hoặc VNĐ cho discount
                        let discountText = '';
                        if (coupon.discount !== undefined && coupon.discount !== null && coupon.discount !== '') {
                            const discountVal = parseInt(coupon.discount);
                            if (!isNaN(discountVal)) {
                                if (discountVal <= 100) {
                                    discountText = discountVal + '%';
                                } else {
                                    discountText = new Intl.NumberFormat('vi-VN').format(discountVal) + ' VNĐ';
                                }
                            } else {
                                discountText = coupon.discount;
                            }
                        }

                        let displayText = `${coupon.code}`;
                        if (discountText) {
                            displayText += ` - ${discountText}`;
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
                }).catch(error => {
                    console.error('Error loading saved coupons:', error);
                    if (couponCountBadge) {
                        couponCountBadge.textContent = '0 mã khả dụng';
                    }
                });
            }
            function initializeCouponManager() {

                // Ensure coupon manager exists
                if (!window.couponManager) {
                    window.couponManager = {
                        getSavedCoupons: function() {
                            @auth
                            return fetch('/api/saved-coupons', {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    return data.coupons || [];
                                }
                                return [];
                            })
                            .catch(error => {
                                console.error('Error loading saved coupons:', error);
                                return [];
                            });
                            @else
                            return Promise.resolve([]);
                            @endauth
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

                // Đảm bảo chỉ gọi loadSavedCouponsToDropdown sau khi window.couponManager đã được khởi tạo
                loadSavedCouponsToDropdown();
                const savedCouponsPromise = window.couponManager ? window.couponManager.getSavedCoupons() : Promise.resolve([]);
                const couponSelect = document.getElementById('coupon-select');
                const couponCountBadge = document.getElementById('available-coupons-count');
                const statusMessage = document.getElementById('coupon-status-message');

                if (!couponSelect) return;

                savedCouponsPromise.then(savedCoupons => {
                    // Update count badge
                    if (couponCountBadge) {
                        couponCountBadge.textContent = `${savedCoupons.length} mã khả dụng`;
                }

                // Clear existing options
                couponSelect.innerHTML = '<option value="">-- Chọn mã giảm giá --</option>';

                if (savedCoupons.length === 0) {
                    couponSelect.innerHTML += '<option value="" disabled>Không có mã giảm giá đã lưu</option>';
                    if (statusMessage) {
                        statusMessage.innerHTML = `
                            <small class="text-muted">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Bạn chưa lưu mã giảm giá nào.
                                <a href="#" class="text-primary" onclick="alert('Tính năng đang phát triển')">Xem mã khả dụng</a>
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

                const detailsDiv = document.getElementById('coupon-details');
                if (!detailsDiv) return;

                document.getElementById('detail-code').textContent = code;
                // Hiển thị % hoặc VNĐ cho giảm giá
                let discountText = discount || 'Mã đã lưu';
                if (discountText !== '-' && discountText !== 'Mã đã lưu') {
                    if (!discountText.includes('%') && !discountText.toLowerCase().includes('vnđ') && !discountText.toLowerCase().includes('đ')) {
                        if (parseInt(discountText) <= 100) {
                            discountText = discountText + '%';
                        } else {
                            discountText = new Intl.NumberFormat('vi-VN').format(discountText) + ' VNĐ';
                        }
                    }
                }
                document.getElementById('detail-discount').textContent = discountText;
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

            function setupVoucherHandling() {
                const applyCouponSelectBtn = document.getElementById('apply-coupon-select');
                const applyCouponInputBtn = document.getElementById('apply-coupon-input');
                const couponSelect = document.getElementById('coupon-select');
                const couponInput = document.getElementById('coupon-input');
                const couponResult = document.getElementById('coupon-result');

                let isInCancelMode = false;

                // Apply coupon from select
                if (applyCouponSelectBtn) {
                    applyCouponSelectBtn.addEventListener('click', function() {
                        if (isInCancelMode) {
                            handleCancelCoupon();
                        } else {
                            const couponCode = couponSelect ? couponSelect.value : '';
                            if (couponCode) {
                                handleApplyCoupon(couponCode, applyCouponSelectBtn);
                            } else {
                                showCouponMessage('Vui lòng chọn mã voucher', 'error');
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
                                handleApplyCoupon(couponCode, applyCouponInputBtn);
                            } else {
                                showCouponMessage('Vui lòng nhập mã voucher', 'error');
                            }
                        }
                    });
                }

                function handleApplyCoupon(couponCode, buttonElement) {
                    const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                    if (checkedItems.length === 0) {
                        showCouponMessage('Vui lòng chọn ít nhất một sản phẩm trước khi áp dụng mã giảm giá', 'error');
                        return;
                    }

                    let selectedSubtotal = 0;
                    checkedItems.forEach(checkbox => {
                        const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
                        const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
                        selectedSubtotal += price * quantity;
                    });

                    buttonElement.disabled = true;
                    buttonElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Đang xử lý...';

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        showCouponMessage('Lỗi xác thực. Vui lòng tải lại trang.', 'error');
                        resetCouponButton(buttonElement);
                        return;
                    }

                    fetch('/cart/validate-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode,
                            subtotal: selectedSubtotal
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.appliedCouponCode = couponCode;
                            window.appliedCouponInfo = {
                                code: couponCode,
                                type: data.coupon_info?.discount_type || 'fixed',
                                value: data.coupon_info?.discount || data.discount_amount || 0,
                                max_discount_amount: data.coupon_info?.max_discount_amount || 0
                            };

                            window.voucherDiscount = data.discount_amount || 0;

                            showCouponSuccess(data.message || 'Áp dụng mã giảm giá thành công!', window.appliedCouponInfo);
                            switchToCancelMode(buttonElement);
                            calculateTotal();

                            showToast(`Áp dụng mã giảm giá thành công! Tiết kiệm ${window.voucherDiscount.toLocaleString()} VNĐ`, 'success');
                        } else {
                            showCouponMessage(data.message || 'Mã voucher không hợp lệ', 'error');
                            resetCouponButton(buttonElement);
                        }
                    })
                    .catch(error => {
                        console.error('Error applying coupon:', error);
                        showCouponMessage('Có lỗi xảy ra khi áp dụng voucher: ' + error.message, 'error');
                        resetCouponButton(buttonElement);
                    });
                }

                function handleCancelCoupon() {
                    window.appliedCouponCode = '';
                    window.appliedCouponInfo = null;
                    window.voucherDiscount = 0;

                    // Reset UI
                    if (couponSelect) couponSelect.value = '';
                    if (couponInput) couponInput.value = '';
                    if (couponResult) couponResult.innerHTML = '';
                    hideCouponDetails();

                    // Reset buttons
                    resetCouponButton(applyCouponSelectBtn);
                    resetCouponButton(applyCouponInputBtn);

                    calculateTotal();
                    isInCancelMode = false;

                    showToast('Đã hủy mã giảm giá', 'success');
                }

                function switchToCancelMode(buttonElement) {
                    // Disable both selects/inputs
                    if (couponSelect) {
                        couponSelect.disabled = true;
                        couponSelect.style.backgroundColor = '#f8fff9';
                        couponSelect.style.borderColor = '#28a745';
                    }

                    if (couponInput) {
                        couponInput.readOnly = true;
                        couponInput.style.backgroundColor = '#f8fff9';
                        couponInput.style.borderColor = '#28a745';
                    }

                    // Update the clicked button to cancel mode
                    buttonElement.innerHTML = '<i class="fa-solid fa-times me-1"></i>HỦY';
                    buttonElement.className = 'btn voucher-btn-cancel';
                    buttonElement.disabled = false;

                    // Disable the other button
                    const otherButton = buttonElement === applyCouponSelectBtn ? applyCouponInputBtn : applyCouponSelectBtn;
                    if (otherButton) {
                        otherButton.disabled = true;
                        otherButton.style.opacity = '0.5';
                    }

                    isInCancelMode = true;
                }

                function resetCouponButton(buttonElement) {
                    // Re-enable selects/inputs
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

                    // Reset both buttons
                    if (applyCouponSelectBtn) {
                        applyCouponSelectBtn.disabled = !couponSelect?.value;
                        applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
                        applyCouponSelectBtn.className = 'btn voucher-btn';
                        applyCouponSelectBtn.style.opacity = '';
                    }

                    if (applyCouponInputBtn) {
                        applyCouponInputBtn.disabled = false;
                        applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
                        applyCouponInputBtn.className = 'btn voucher-btn';
                        applyCouponInputBtn.style.opacity = '';
                    }
                }

                function showCouponMessage(message, type) {
                    if (!couponResult) return;

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
                    if (!couponResult) return;

                    const successHtml = `
                        <div class="applied-voucher">
                            <i class="fa-solid fa-check-circle text-success me-1"></i>
                            <span class="text-success">Đã áp dụng: <strong>${couponInfo.code}</strong></span>
                            <div class="mt-1 small text-muted">
                                ${couponInfo.type === 'percentage' ?
                                    `Giảm ${couponInfo.value}%${couponInfo.max_discount_amount > 0 ? ' (tối đa ' + couponInfo.max_discount_amount.toLocaleString() + 'đ)' : ''}` :
                                    `Giảm ${couponInfo.value.toLocaleString()}đ`
                                }
                            </div>
                        </div>
                    `;
                    couponResult.innerHTML = successHtml;
                }
            }

            function resetAllCoupons() {
                if (window.appliedCouponCode) {
                    window.appliedCouponCode = '';
                    window.appliedCouponInfo = null;
                    window.voucherDiscount = 0;

                    const couponSelect = document.getElementById('coupon-select');
                    const couponInput = document.getElementById('coupon-input');
                    const couponResult = document.getElementById('coupon-result');

                    if (couponSelect) couponSelect.value = '';
                    if (couponInput) couponInput.value = '';
                    if (couponResult) couponResult.innerHTML = '';
                    hideCouponDetails();

                    calculateTotal();
                }
            }

            function saveSelections() {
                const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                const selectedIds = Array.from(checkedItems).map(checkbox => checkbox.getAttribute('data-cart-id'));
                const userId = {{ Auth::id() ?? 'guest' }};
                localStorage.setItem(`cart_selections_${userId}`, JSON.stringify(selectedIds));
            }

            function loadSavedSelections() {
                const userId = {{ Auth::id() ?? 'guest' }};
                const savedSelections = localStorage.getItem(`cart_selections_${userId}`);

                if (savedSelections) {
                    try {
                        const selectedIds = JSON.parse(savedSelections);

                        selectedIds.forEach(cartId => {
                            const checkbox = document.querySelector(`.cart-item-checkbox[data-cart-id="${cartId}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                                const productId = checkbox.getAttribute('data-product-id');
                                updateProductCheckboxState(productId);
                            }
                        });

                        updateSelectAllState();
                        // Cập nhật lại tổng tiền và trạng thái nút mua hàng sau khi khôi phục selections
                        calculateTotal();
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

            function updateSelectAllState() {
                const selectAllCheckbox = document.getElementById('select-all');
                if (selectAllCheckbox) {
                    const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                    const totalItems = document.querySelectorAll('.cart-item-checkbox');

                    selectAllCheckbox.checked = checkedItems.length === totalItems.length && totalItems.length > 0;
                    selectAllCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < totalItems.length;
                }
            }

            function deleteSelectedItems(cartIds) {
                const deleteBtn = document.querySelector('.delete-selected');
                const originalText = deleteBtn.innerHTML;

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

            function calculateTotal() {
                const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                const allItems = document.querySelectorAll('.cart-item-checkbox');
                let subtotal = 0;

                if (allItems.length === 0) {
                    return;
                }

                checkedItems.forEach(checkbox => {
                    const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
                    const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
                    subtotal += price * quantity;
                });

                // Update UI elements
                const cartSubtotalEl = document.getElementById('cart-subtotal');
                const selectedItemsCountEl = document.getElementById('selected-items-count');
                const totalItemsEl = document.getElementById('total-items');
                const finalTotalEl = document.getElementById('final-total');
                const paymentDiscountEl = document.getElementById('payment-discount');
                const paymentDiscountRowEl = document.getElementById('payment-discount-row');

                if (cartSubtotalEl) cartSubtotalEl.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VNĐ';
                if (selectedItemsCountEl) selectedItemsCountEl.textContent = checkedItems.length;
                if (totalItemsEl) totalItemsEl.textContent = allItems.length;

                // Auto remove coupon if no items selected
                if (checkedItems.length === 0 && window.voucherDiscount > 0) {
                    autoRemoveCoupon();
                    return;
                }

                // Calculate discount
                let discount = 0;
                if (window.voucherDiscount > 0 && subtotal > 0) {
                    discount = Math.min(window.voucherDiscount, subtotal);
                }

                const finalTotal = Math.max(0, subtotal - discount);

                if (paymentDiscountEl) {
                    paymentDiscountEl.textContent = discount > 0 ? '-' + new Intl.NumberFormat('vi-VN').format(discount) + ' VNĐ' : '-0 VNĐ';
                }

                if (paymentDiscountRowEl) {
                    paymentDiscountRowEl.style.display = discount > 0 ? 'flex' : 'none';
                }

                if (finalTotalEl) {
                    finalTotalEl.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' VNĐ';
                }

                // Update checkout button
                updateCheckoutButton(checkedItems.length > 0);

                // Update cart summary details
                updateCartSummaryDetails();
            }

            function updateCheckoutButton(hasSelectedItems) {
                const checkoutBtn = document.getElementById('checkout-button');
                if (checkoutBtn) {
                    const totalCartItems = document.querySelectorAll('.cart-item-checkbox').length;

                    if (totalCartItems === 0) {
                        checkoutBtn.style.display = 'none';
                        return;
                    }

                    checkoutBtn.style.display = 'block';
                    checkoutBtn.disabled = !hasSelectedItems;

                    if (hasSelectedItems) {
                        checkoutBtn.classList.remove('btn-secondary');
                        checkoutBtn.classList.add('checkout-btn');
                        checkoutBtn.style.backgroundColor = '';
                        checkoutBtn.style.borderColor = '';
                        checkoutBtn.style.opacity = '1';
                    } else {
                        checkoutBtn.classList.remove('checkout-btn');
                        checkoutBtn.classList.add('btn-secondary');
                        checkoutBtn.style.backgroundColor = '#6c757d';
                        checkoutBtn.style.borderColor = '#6c757d';
                        checkoutBtn.style.opacity = '0.6';
                    }
                }
            }

            function updateCartSummaryDetails() {
                const cartSummaryDetailsEl = document.getElementById('cart-summary-details');
                if (!cartSummaryDetailsEl) return;

                const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');

                if (checkedItems.length === 0) {
                    cartSummaryDetailsEl.innerHTML = `
                        <div id="no-selected-items" class="text-center text-muted py-3">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Chưa chọn sản phẩm nào
                        </div>
                    `;
                    return;
                }

                // Group checked items by product
                const productGroups = {};
                checkedItems.forEach(checkbox => {
                    const productId = checkbox.getAttribute('data-product-id');
                    const productName = checkbox.getAttribute('data-product-name');
                    const variantText = checkbox.getAttribute('data-variant-text');
                    const quantity = checkbox.getAttribute('data-quantity');

                    if (!productGroups[productId]) {
                        productGroups[productId] = {
                            name: productName,
                            items: []
                        };
                    }

                    productGroups[productId].items.push({
                        variantText: variantText,
                        quantity: quantity
                    });
                });

                let summaryHTML = '';
                let orderIndex = 1;

                Object.keys(productGroups).forEach(productId => {
                    const group = productGroups[productId];
                    const productName = group.name.length > 30 ? group.name.substring(0, 30) + '...' : group.name;

                    summaryHTML += `
                        <div class="order-item mb-2">
                            <div class="fw-semibold text-dark mb-1" style="font-size: 13px;">
                                <i class="fa-solid fa-box me-1 text-primary"></i>
                                Đơn thứ ${orderIndex}: ${productName}
                            </div>
                    `;

                    group.items.forEach(item => {
                        summaryHTML += `
                            <div class="variant-item ms-3 mb-1" style="font-size: 12px; color: #666;">
                                <i class="fa-solid fa-angle-right me-1"></i>
                                ${item.variantText}:
                                <span class="text-primary fw-semibold">${item.quantity} sp</span>
                            </div>
                        `;
                    });

                    summaryHTML += '</div>';
                    orderIndex++;
                });

                cartSummaryDetailsEl.innerHTML = summaryHTML;
            }

            function autoRemoveCoupon() {
                window.appliedCouponCode = '';
                window.appliedCouponInfo = null;
                window.voucherDiscount = 0;

                const couponInput = document.getElementById('coupon-input');
                const couponResult = document.getElementById('coupon-result');
                const applyCouponSelectBtn = document.getElementById('apply-coupon-select');
                const applyCouponInputBtn = document.getElementById('apply-coupon-input');
                const couponSelect = document.getElementById('coupon-select');

                if (couponInput) {
                    couponInput.value = '';
                    couponInput.readOnly = false;
                    couponInput.style.backgroundColor = '';
                    couponInput.style.borderColor = '';
                }

                if (couponSelect) {
                    couponSelect.value = '';
                    couponSelect.disabled = false;
                    couponSelect.style.backgroundColor = '';
                    couponSelect.style.borderColor = '';
                }

                if (couponResult) {
                    couponResult.innerHTML = '';
                }

                if (applyCouponSelectBtn) {
                    applyCouponSelectBtn.disabled = true;
                    applyCouponSelectBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
                    applyCouponSelectBtn.className = 'btn voucher-btn';
                    applyCouponSelectBtn.style.opacity = '';
                }

                if (applyCouponInputBtn) {
                    applyCouponInputBtn.disabled = false;
                    applyCouponInputBtn.innerHTML = '<i class="fa-solid fa-tag me-1"></i>ÁP DỤNG';
                    applyCouponInputBtn.className = 'btn voucher-btn';
                    applyCouponInputBtn.style.opacity = '';
                }

                hideCouponDetails();
                calculateTotal();
                showToast('Đã tự động hủy mã giảm giá do không có sản phẩm nào được chọn', 'info');
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

            // Quantity control functions
            function setupQuantityControls() {
                const minusButtons = document.querySelectorAll('.minus-btn');
                const plusButtons = document.querySelectorAll('.plus-btn');
                const qtyInputs = document.querySelectorAll('.qty-input');

                // Minus button click
                minusButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
                        if (input && parseInt(input.value) > 1) {
                            const newQty = parseInt(input.value) - 1;
                            updateQuantity(cartId, newQty);
                        }
                    });
                });

                // Plus button click
                plusButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
                        if (input && parseInt(input.value) < 99) {
                            const newQty = parseInt(input.value) + 1;
                            updateQuantity(cartId, newQty);
                        }
                    });
                });

                // Input change
                qtyInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        let value = parseInt(this.value);

                        // Validate input
                        if (isNaN(value) || value < 1) {
                            value = 1;
                        } else if (value > 99) {
                            value = 99;
                        }

                        this.value = value;
                        updateQuantity(cartId, value);
                    });

                    input.addEventListener('blur', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        let value = parseInt(this.value);

                        // Validate input
                        if (isNaN(value) || value < 1) {
                            value = 1;
                            this.value = value;
                            updateQuantity(cartId, value);
                        } else if (value > 99) {
                            value = 99;
                            this.value = value;
                            updateQuantity(cartId, value);
                        }
                    });
                });
            }

            // Function to update quantity via AJAX
            function updateQuantity(cartId, quantity) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    alert('Có lỗi xảy ra. Vui lòng tải lại trang.');
                    return;
                }

                // Disable buttons while updating
                const minusBtn = document.querySelector(`.minus-btn[data-cart-id="${cartId}"]`);
                const plusBtn = document.querySelector(`.plus-btn[data-cart-id="${cartId}"]`);
                const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);

                if (minusBtn) minusBtn.disabled = true;
                if (plusBtn) plusBtn.disabled = true;

                fetch(`/cart/update-quantity/${cartId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update input value
                        if (input) {
                            input.value = quantity;
                            input.setAttribute('data-original-value', quantity);
                        }

                        // Update checkbox data-quantity
                        const checkbox = document.querySelector(`.cart-item-checkbox[data-cart-id="${cartId}"]`);
                        if (checkbox) {
                            checkbox.setAttribute('data-quantity', quantity);
                        }

                        // Update item total price
                        const itemTotal = document.querySelector(`[data-cart-id="${cartId}"] .item-total`);
                        if (itemTotal && data.data && data.data.item_total) {
                            itemTotal.textContent = data.data.item_total + ' VNĐ';
                        }

                        // Update minus button state
                        if (minusBtn) {
                            minusBtn.disabled = quantity <= 1;
                        }

                        // Recalculate cart total and update summary details
                        calculateTotal();

                        // Explicitly update cart summary details in case calculateTotal doesn't do it
                        updateCartSummaryDetails();

                    } else {
                        // Reset to original value if error
                        const originalValue = input ? input.getAttribute('data-original-value') : quantity;
                        if (input) input.value = originalValue;
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật số lượng.');
                    }
                })
                .catch(error => {
                    console.error('Error updating quantity:', error);
                    // Reset to original value
                    const originalValue = input ? input.getAttribute('data-original-value') : quantity;
                    if (input) input.value = originalValue;
                    alert('Có lỗi xảy ra khi cập nhật số lượng.');
                })
                .finally(() => {
                    // Re-enable buttons
                    if (minusBtn) minusBtn.disabled = false;
                    if (plusBtn) plusBtn.disabled = false;
                });
            }
        });

        function proceedToCheckout() {
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
            const allItems = document.querySelectorAll('.cart-item-checkbox');

            if (allItems.length === 0) {
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

            if (window.appliedCouponCode && window.voucherDiscount > 0) {
                let selectedSubtotal = 0;
                checkedItems.forEach(checkbox => {
                    const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 1;
                    const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
                    selectedSubtotal += price * quantity;
                });

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
                    submitCheckoutForm(selectedItems);
                })
                .catch(error => {
                    console.error('Error saving coupon:', error);
                    submitCheckoutForm(selectedItems);
                });
            } else {
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

        // Update select all state
        function updateSelectAllState() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedItems.length === itemCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < itemCheckboxes.length;
            }
        }

        // Save selections to localStorage
        function saveSelections() {
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
            const selectedIds = Array.from(checkedItems).map(checkbox => checkbox.getAttribute('data-cart-id'));
            localStorage.setItem('cart_selected_items', JSON.stringify(selectedIds));
        }

        // Load saved selections from localStorage
        function loadSavedSelections() {
            try {
                const savedSelections = JSON.parse(localStorage.getItem('cart_selected_items') || '[]');
                savedSelections.forEach(cartId => {
                    const checkbox = document.querySelector(`.cart-item-checkbox[data-cart-id="${cartId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });

                // Update product checkboxes based on item selections
                const productCheckboxes = document.querySelectorAll('.product-checkbox');
                productCheckboxes.forEach(productCheckbox => {
                    const productId = productCheckbox.getAttribute('data-product-id');
                    const productItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]`);
                    const checkedItems = document.querySelectorAll(`.cart-item-checkbox[data-product-id="${productId}"]:checked`);

                    if (checkedItems.length === productItems.length) {
                        productCheckbox.checked = true;
                    } else if (checkedItems.length > 0) {
                        productCheckbox.indeterminate = true;
                    }
                });

                updateSelectAllState();
            } catch (error) {
                console.error('Error loading saved selections:', error);
            }
        }

        // Format currency helper
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }
    </script>
    @endpush
@endsection
