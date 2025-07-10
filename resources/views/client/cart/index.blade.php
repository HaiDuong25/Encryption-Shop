@extends('client.layout.main')
@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="fa-solid fa-cart-shopping me-2"></i>Giỏ hàng của bạn</h2>

    @if($carts->count() > 0)
    <div class="row">
        <!-- Bảng giỏ hàng -->
        <div class="col-lg-8 mb-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center shadow-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $cart)
                        <tr>
                            <td>
                                <img src="{{ asset($cart->variant && $cart->variant->image ? 'storage/' . $cart->variant->image : 'storage/' . $cart->product->image) }}" width="70" class="img-thumbnail">
                            </td>
                            <td>
                                <strong>{{ $cart->product->name }}</strong>
                                @if($cart->variant)
                                <br>
                                <small class="text-muted">Biến thể: {{ $cart->variant->sku }}</small>
                                
                                @php
                                    // Lấy các attribute values của variant hiện tại
                                    $currentSize = $cart->variant->attributeValues->where('attribute.name', 'Size')->first();
                                    $currentColor = $cart->variant->attributeValues->where('attribute.name', 'Màu')->first();
                                    
                                    // Lấy tất cả variants của sản phẩm này
                                    $productVariants = $cart->product->variants;
                                    
                                    // Lấy tất cả sizes và colors available
                                    $availableSizes = $productVariants->flatMap(function ($variant) {
                                        return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Size');
                                    })->unique('id');
                                    
                                    $availableColors = $productVariants->flatMap(function ($variant) {
                                        return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Màu');
                                    })->unique('id');
                                @endphp
                                
                                @if($availableSizes->count() > 1 || $availableColors->count() > 1)
                                <div class="variant-selector mt-2" data-cart-id="{{ $cart->id }}" data-product-id="{{ $cart->product->id }}">
                                    @if($availableSizes->count() > 1)
                                    <div class="mb-2">
                                        <small class="text-muted">Size:</small>
                                        <select class="form-select form-select-sm variant-size" style="width: auto; display: inline-block;">
                                            @foreach($availableSizes as $size)
                                            <option value="{{ $size->id }}" {{ $currentSize && $currentSize->id == $size->id ? 'selected' : '' }}>
                                                {{ $size->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    
                                    @if($availableColors->count() > 1)
                                    <div class="mb-2">
                                        <small class="text-muted">Màu:</small>
                                        <select class="form-select form-select-sm variant-color" style="width: auto; display: inline-block;">
                                            @foreach($availableColors as $color)
                                            <option value="{{ $color->id }}" {{ $currentColor && $currentColor->id == $color->id ? 'selected' : '' }}>
                                                {{ $color->value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    
                                    <button type="button" class="btn btn-outline-primary btn-sm update-variant-btn">Cập nhật</button>
                                </div>
                                
                                {{-- Hidden data for JavaScript --}}
                                <script type="application/json" class="variant-data">
                                    {!! $productVariants->map(function($variant) {
                                        return [
                                            'id' => $variant->id,
                                            'price' => $variant->price,
                                            'sale_price' => $variant->sale_price,
                                            'stock' => $variant->stock,
                                            'sku' => $variant->sku,
                                            'size_id' => optional($variant->attributeValues->where('attribute.name', 'Size')->first())->id,
                                            'color_id' => optional($variant->attributeValues->where('attribute.name', 'Màu')->first())->id,
                                        ];
                                    })->values()->toJson() !!}
                                </script>
                                @endif
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline-flex align-items-center gap-1 quantity-form">
                                    @csrf
                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                                    <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="form-control form-control-sm text-center" style="width: 60px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                                    <button type="submit" class="btn btn-primary btn-sm ms-2">✔️</button>
                                </form>
                            </td>
                            <td>
                                @php
                                    // Ưu tiên sale_price trước, fallback về price
                                    $currentPrice = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                                    $originalPrice = $cart->variant->price ?? $cart->product->price;
                                    $salePrice = $cart->variant->sale_price ?? $cart->product->sale_price;
                                @endphp
                                
                                @if($salePrice && $salePrice < $originalPrice)
                                    <div class="price-display">
                                        <span class="text-primary fw-bold">{{ number_format($salePrice) }} đ</span>
                                        <small class="text-muted text-decoration-line-through d-block">{{ number_format($originalPrice) }} đ</small>
                                    </div>
                                @else
                                    <span class="fw-bold">{{ number_format($currentPrice) }} đ</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $currentPrice = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                                @endphp
                                <span class="fw-bold text-primary">{{ number_format($currentPrice * $cart->quantity) }} đ</span>
                            </td>
                            <td>
                                <form action="{{ route('cart.delete', $cart->id) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑️ Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-receipt me-2"></i> Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p class="d-flex justify-content-between mb-2">
                        <span>Tổng sản phẩm:</span>
                        <span class="fw-semibold">{{ $carts->sum('quantity') }}</span>
                    </p>

                    @php
                        $grandTotal = $carts->sum(function($cart){
                            $price = $cart->variant->sale_price ?? $cart->variant->price ?? $cart->product->sale_price ?? $cart->product->price;
                            return $price * $cart->quantity;
                        });
                        
                        // Lấy thông tin voucher đã áp dụng từ session
                        $appliedCoupon = session('applied_coupon');
                        $voucherDiscount = session('coupon_discount', 0);
                        $couponInfo = session('coupon_info', null);
                        
                        $finalTotal = max(0, $grandTotal - $voucherDiscount);
                    @endphp

                    <p class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span class="fw-semibold" id="subtotal-amount">{{ number_format($grandTotal) }} đ</span>
                    </p>

                    <!-- Divider -->
                    <hr class="my-3">

                    <!-- Voucher -->
                    <div class="mb-3">
                        <label for="voucher" class="form-label fw-semibold"><i class="fa-solid fa-ticket me-1 text-warning"></i> Mã giảm giá</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="voucher" placeholder="Nhập mã voucher..." value="{{ $appliedCoupon }}">
                            <button class="btn btn-primary" type="button" id="apply-coupon-btn">
                                <span class="btn-text">Áp dụng</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                        <div id="coupon-message" class="mt-2"></div>
                        
                        @if($appliedCoupon)
                        <div class="coupon-applied mt-2 p-2 bg-light rounded">
                            <small class="text-success">
                                <i class="fa-solid fa-check-circle me-1"></i>
                                Đã áp dụng mã: <strong>{{ $appliedCoupon }}</strong>
                                @if($couponInfo)
                                    @if($couponInfo['type'] === 'percentage')
                                        (Giảm {{ $couponInfo['value'] }}%)
                                    @else
                                        (Giảm {{ number_format($couponInfo['value']) }}đ)
                                    @endif
                                @endif
                            </small>
                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" id="remove-coupon-btn">Bỏ mã</button>
                        </div>
                        @endif
                    </div>

                    <p class="d-flex justify-content-between text-success mb-2">
                        <span>Giảm giá voucher:</span>
                        <span id="discount-amount">-{{ number_format($voucherDiscount) }} đ</span>
                    </p>

                    <!-- Divider -->
                    <hr class="my-3">

                    <p class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Tổng thanh toán:</span>
                        <span class="text-primary" id="final-total">{{ number_format($finalTotal) }} đ</span>
                    </p>

                    <a href="{{ route('cart.checkout') }}" class="btn w-100 py-2 mt-3" style="background:#222;color:#fff;">
                        <i class="fa-solid fa-credit-card me-1"></i> Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="alert alert-warning text-center">Giỏ hàng trống.</div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý số lượng
    document.querySelectorAll('.quantity-form').forEach(form => {
        const minus = form.querySelector('.qty-minus');
        const plus = form.querySelector('.qty-plus');
        const input = form.querySelector('input[name="quantity"]');

                minus.addEventListener('click', function () {
                    let val = parseInt(input.value) || 1;
                    if (val > 1) input.value = val - 1;
                });

                plus.addEventListener('click', function () {
                    let val = parseInt(input.value) || 1;
                    input.value = val + 1;
                });
            });
        });
    });

    // Xử lý cập nhật biến thể
    document.querySelectorAll('.update-variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const variantSelector = this.closest('.variant-selector');
            const cartId = variantSelector.dataset.cartId;
            const productId = variantSelector.dataset.productId;
            const sizeId = variantSelector.querySelector('.variant-size')?.value;
            const colorId = variantSelector.querySelector('.variant-color')?.value;
            
            // Lấy dữ liệu variants
            const variantDataScript = variantSelector.parentElement.querySelector('.variant-data');
            const variants = JSON.parse(variantDataScript.textContent);
            
            // Tìm variant phù hợp
            const selectedVariant = variants.find(v => {
                return (!sizeId || v.size_id == sizeId) && (!colorId || v.color_id == colorId);
            });
            
            if (!selectedVariant) {
                alert('Không tìm thấy biến thể phù hợp!');
                return;
            }
            
            // Disable button
            btn.disabled = true;
            btn.textContent = 'Đang cập nhật...';
            
            // Gửi AJAX request
            fetch(`/cart/update-variant/${cartId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    variant_id: selectedVariant.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload trang để cập nhật thông tin
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Cập nhật';
            });
        });
    });

    // Xử lý áp dụng voucher
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const voucherInput = document.getElementById('voucher');
    const couponMessage = document.getElementById('coupon-message');
    const removeCouponBtn = document.getElementById('remove-coupon-btn');

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const couponCode = voucherInput.value.trim();
            
            if (!couponCode) {
                showCouponMessage('Vui lòng nhập mã giảm giá', 'error');
                return;
            }

            // Hiển thị loading
            const btnText = applyCouponBtn.querySelector('.btn-text');
            const spinner = applyCouponBtn.querySelector('.spinner-border');
            btnText.textContent = 'Đang xử lý...';
            spinner.classList.remove('d-none');
            applyCouponBtn.disabled = true;

            // Gửi AJAX request
            fetch('{{ route("cart.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    coupon_code: couponCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCouponMessage(data.message, 'success');
                    updateOrderSummary(data.discount_amount, data.total);
                    
                    // Lưu thông tin coupon vào session (client-side)
                    sessionStorage.setItem('applied_coupon', couponCode);
                    sessionStorage.setItem('coupon_discount', data.discount_amount);
                    sessionStorage.setItem('coupon_info', JSON.stringify(data.coupon_info));
                    
                    // Reload trang để hiển thị coupon đã áp dụng
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showCouponMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCouponMessage('Có lỗi xảy ra, vui lòng thử lại', 'error');
            })
            .finally(() => {
                // Ẩn loading
                btnText.textContent = 'Áp dụng';
                spinner.classList.add('d-none');
                applyCouponBtn.disabled = false;
            });
        });
    }

    // Xử lý bỏ mã giảm giá
    if (removeCouponBtn) {
        removeCouponBtn.addEventListener('click', function() {
            // Xóa session
            fetch('{{ route("cart.remove-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Xóa client-side storage
                    sessionStorage.removeItem('applied_coupon');
                    sessionStorage.removeItem('coupon_discount');
                    sessionStorage.removeItem('coupon_info');
                    
                    // Reload trang
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }

    function showCouponMessage(message, type) {
        couponMessage.innerHTML = `<small class="text-${type === 'success' ? 'success' : 'danger'}">${message}</small>`;
        
        if (type === 'success') {
            setTimeout(() => {
                couponMessage.innerHTML = '';
            }, 3000);
        }
    }

    function updateOrderSummary(discountAmount, finalTotal) {
        const discountEl = document.getElementById('discount-amount');
        const finalTotalEl = document.getElementById('final-total');
        
        if (discountEl) {
            discountEl.textContent = `-${new Intl.NumberFormat('vi-VN').format(discountAmount)} đ`;
        }
        
        if (finalTotalEl) {
            finalTotalEl.textContent = `${new Intl.NumberFormat('vi-VN').format(finalTotal)} đ`;
        }
    }
});
</script>
@endpush

@push('styles')
<style>
    .quantity-form button.qty-minus,
    .quantity-form button.qty-plus {
        width: 32px;
        height: 32px;
        padding: 0;
        font-weight: bold;
    }
    .card-header h5 i {
        opacity: 0.9;
    }
    .input-group input#voucher::placeholder {
        font-style: italic;
        color: #999;
    }
    .variant-selector {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 10px;
        margin-top: 8px;
    }
    .variant-selector .form-select {
        font-size: 12px;
        margin-left: 5px;
        margin-right: 10px;
    }
    .variant-selector .update-variant-btn {
        font-size: 11px;
        padding: 2px 8px;
    }
    .price-display {
        text-align: center;
    }
    .price-display .text-muted {
        font-size: 12px;
    }
    .coupon-applied {
        font-size: 13px;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
