@extends('client.layout.main')

@section('title', 'Tất cả mã giảm giá')

@section('content')
<div class="container-fluid-lg py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3" style="font-size: 2.5rem; color: #2c3e50;">
                <i class="fa-solid fa-ticket me-3 text-warning"></i>
                Tất cả mã giảm giá
            </h1>
            <p class="text-muted fs-5">Khám phá tất cả các ưu đãi hấp dẫn đang có sẵn trên hệ thống!</p>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="stat-card text-center p-4 rounded-4 shadow-sm h-100" 
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fa-solid fa-gift fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $totalCoupons }}</h3>
                <p class="mb-0">Tổng số mã giảm giá</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card text-center p-4 rounded-4 shadow-sm h-100" 
                 style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <i class="fa-solid fa-clock fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $expiringSoon }}</h3>
                <p class="mb-0">Sắp hết hạn (7 ngày)</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card text-center p-4 rounded-4 shadow-sm h-100" 
                 style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <i class="fa-solid fa-infinity fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $unlimitedCoupons }}</h3>
                <p class="mb-0">Không giới hạn lượt dùng</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-section bg-white p-4 rounded-4 shadow-sm">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-search me-2 text-primary"></i>Tìm kiếm
                            </label>
                            <input type="text" name="search" class="form-control rounded-pill" 
                                   placeholder="Tìm theo mã hoặc mô tả..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-filter me-2 text-success"></i>Loại giảm giá
                            </label>
                            <select name="type" class="form-select rounded-pill">
                                <option value="">Tất cả</option>
                                <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>
                                    Theo %
                                </option>
                                <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>
                                    Số tiền cố định
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-shopping-cart me-2 text-warning"></i>Đơn tối thiểu
                            </label>
                            <select name="min_order" class="form-select rounded-pill">
                                <option value="">Tất cả</option>
                                <option value="100000" {{ request('min_order') == '100000' ? 'selected' : '' }}>
                                    ≤ 100,000₫
                                </option>
                                <option value="300000" {{ request('min_order') == '300000' ? 'selected' : '' }}>
                                    ≤ 300,000₫
                                </option>
                                <option value="500000" {{ request('min_order') == '500000' ? 'selected' : '' }}>
                                    ≤ 500,000₫
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-sort me-2 text-info"></i>Sắp xếp
                            </label>
                            <select name="sort" class="form-select rounded-pill">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                    Mới nhất
                                </option>
                                <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>
                                    Giá trị giảm
                                </option>
                                <option value="expires" {{ request('sort') == 'expires' ? 'selected' : '' }}>
                                    Hạn sử dụng
                                </option>
                                <option value="usage" {{ request('sort') == 'usage' ? 'selected' : '' }}>
                                    Lượt sử dụng
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Thứ tự</label>
                            <select name="order" class="form-select rounded-pill">
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary rounded-pill me-2 flex-fill">
                                <i class="fa-solid fa-search me-2"></i>Lọc
                            </button>
                            <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="fa-solid fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coupons Grid -->
    <div class="row">
        @if($coupons->count() > 0)
            @foreach($coupons as $index => $coupon)
                <div class="col-lg-4 col-md-6 mb-4">
                    @php
                        $colors = [
                            ['bg' => 'linear-gradient(135deg, #fff9e6 0%, #ffe0b3 100%)', 'shadow' => 'rgba(255, 193, 7, 0.2)', 'shadow_hover' => 'rgba(255, 193, 7, 0.3)', 'decoration' => 'rgba(255, 193, 7, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #fff3cd 0%, #ffe082 100%)', 'icon_shadow' => 'rgba(255, 193, 7, 0.3)', 'icon_color' => 'text-warning', 'badge_class' => 'bg-warning text-dark', 'text_color' => 'text-danger', 'btn_class' => 'btn-warning', 'border_class' => 'border-warning text-warning'],
                            ['bg' => 'linear-gradient(135deg, #e8f5e8 0%, #c3e6c3 100%)', 'shadow' => 'rgba(40, 167, 69, 0.2)', 'shadow_hover' => 'rgba(40, 167, 69, 0.3)', 'decoration' => 'rgba(40, 167, 69, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)', 'icon_shadow' => 'rgba(40, 167, 69, 0.3)', 'icon_color' => 'text-success', 'badge_class' => 'bg-success text-white', 'text_color' => 'text-success', 'btn_class' => 'btn-success', 'border_class' => 'border-success text-success'],
                            ['bg' => 'linear-gradient(135deg, #e6f3ff 0%, #b3d9ff 100%)', 'shadow' => 'rgba(0, 123, 255, 0.2)', 'shadow_hover' => 'rgba(0, 123, 255, 0.3)', 'decoration' => 'rgba(0, 123, 255, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%)', 'icon_shadow' => 'rgba(0, 123, 255, 0.3)', 'icon_color' => 'text-primary', 'badge_class' => 'bg-primary text-white', 'text_color' => 'text-primary', 'btn_class' => 'btn-primary', 'border_class' => 'border-primary text-primary'],
                            ['bg' => 'linear-gradient(135deg, #f3e8ff 0%, #d9b3ff 100%)', 'shadow' => 'rgba(123, 31, 162, 0.2)', 'shadow_hover' => 'rgba(123, 31, 162, 0.3)', 'decoration' => 'rgba(123, 31, 162, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #e8d5ff 0%, #c084fc 100%)', 'icon_shadow' => 'rgba(123, 31, 162, 0.3)', 'icon_color' => 'text-purple', 'badge_class' => 'bg-purple text-white', 'text_color' => 'text-purple', 'btn_class' => 'btn-purple', 'border_class' => 'border-purple text-purple'],
                            ['bg' => 'linear-gradient(135deg, #ffe8f0 0%, #ffb3d1 100%)', 'shadow' => 'rgba(219, 39, 119, 0.2)', 'shadow_hover' => 'rgba(219, 39, 119, 0.3)', 'decoration' => 'rgba(219, 39, 119, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%)', 'icon_shadow' => 'rgba(219, 39, 119, 0.3)', 'icon_color' => 'text-pink', 'badge_class' => 'bg-pink text-white', 'text_color' => 'text-pink', 'btn_class' => 'btn-pink', 'border_class' => 'border-pink text-pink']
                        ];
                        $colorScheme = $colors[$index % 5];

                        // Format discount value with max discount info
                        $discountText = '';
                        if ($coupon->discount_type === 'percentage') {
                            $discountText = "Giảm {$coupon->discount}%";
                            if ($coupon->max_discount_amount) {
                                $discountText .= " (tối đa " . format_vnd($coupon->max_discount_amount) . "₫)";
                            }
                        } else {
                            $discountText = "Giảm " . format_vnd($coupon->discount) . "₫";
                        }

                        // Generate badge labels
                        $badges = ['🔥 Hot', '⚡ Giới hạn', '🚚 Freeship', '💎 VIP', '⭐ Đặc biệt', '🎁 Khuyến mãi'];
                    @endphp

                    <div class="coupon-card h-100"
                        style="background: {{ $colorScheme['bg'] }}; border-radius: 1.5rem; box-shadow: 0 8px 25px {{ $colorScheme['shadow'] }}; transition: all 0.3s ease; overflow: hidden; position: relative; border: none;"
                        onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 35px {{ $colorScheme['shadow_hover'] }}';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px {{ $colorScheme['shadow'] }}';">
                        
                        <!-- Decoration -->
                        <div class="coupon-decoration"
                            style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: {{ $colorScheme['decoration'] }}; border-radius: 50%; transform: rotate(45deg);">
                        </div>

                        <div class="card-body p-4" style="position: relative; z-index: 1;">
                            <!-- Top Section -->
                            <div class="text-center mb-3">
                                <div class="coupon-icon mb-3">
                                    <i class="fa-solid fa-gift fa-2x {{ $colorScheme['icon_color'] }}"
                                        style="background: {{ $colorScheme['icon_bg'] }}; border-radius: 50%; padding: 15px; box-shadow: 0 6px 15px {{ $colorScheme['icon_shadow'] }};"></i>
                                </div>
                                <span class="badge {{ $colorScheme['badge_class'] }} px-3 py-2 rounded-pill fw-bold"
                                      style="font-size: 1rem; letter-spacing: 1px;">{{ $coupon->code }}</span>
                            </div>

                            <!-- Discount Amount -->
                            <h4 class="fw-bold {{ $colorScheme['text_color'] }} text-center mb-3" style="font-size: 1.3rem;">
                                {{ $discountText }}
                            </h4>

                            <!-- Description -->
                            @if($coupon->description)
                                <p class="text-muted text-center mb-3" style="font-size: 0.95rem;">{{ $coupon->description }}</p>
                            @endif

                            <!-- Conditions -->
                            <div class="conditions mb-3">
                                @if($coupon->min_order_amount)
                                    <div class="condition-item d-flex align-items-center mb-2">
                                        <i class="fa-solid fa-shopping-cart me-2 text-info" style="font-size: 0.9rem;"></i>
                                        <small class="text-muted">Đơn tối thiểu: {{ format_vnd($coupon->min_order_amount) }}₫</small>
                                    </div>
                                @endif
                                
                                @if($coupon->start_date && $coupon->end_date)
                                    <div class="condition-item d-flex align-items-center mb-2">
                                        <i class="fa-solid fa-calendar me-2 text-info" style="font-size: 0.9rem;"></i>
                                        <small class="text-muted">{{ $coupon->start_date->format('d/m/Y') }} - {{ $coupon->end_date->format('d/m/Y') }}</small>
                                    </div>
                                @elseif($coupon->expires_at)
                                    <div class="condition-item d-flex align-items-center mb-2">
                                        <i class="fa-solid fa-calendar me-2 text-info" style="font-size: 0.9rem;"></i>
                                        <small class="text-muted">Hết hạn: {{ $coupon->expires_at->format('d/m/Y') }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Usage Stats -->
                            @if($coupon->usage_limit > 0)
                                @php
                                    $remaining = $coupon->remainingUsage();
                                    $usagePercent = ($coupon->used_count / $coupon->usage_limit) * 100;
                                    $hasUsed = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                                @endphp
                                <div class="usage-stats mb-3">
                                    @if($hasUsed)
                                        <div class="alert alert-info py-2 px-3 mb-2 text-center" style="border-radius: 10px; font-size: 0.85rem;">
                                            <i class="fa-solid fa-check-circle me-1"></i> Bạn đã sử dụng mã này
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Còn lại: <strong>{{ $remaining }}/{{ $coupon->usage_limit }}</strong></small>
                                        <small class="text-muted">{{ round($usagePercent) }}% đã dùng</small>
                                    </div>
                                    <div class="progress mb-2" style="height: 5px; border-radius: 2.5px;">
                                        <div class="progress-bar {{ $remaining <= 5 ? 'bg-danger' : ($remaining <= 15 ? 'bg-warning' : 'bg-success') }}"
                                             style="width: {{ $usagePercent }}%;"></div>
                                    </div>
                                    @if($remaining <= 5)
                                        <small class="text-danger fw-bold d-block text-center">
                                            <i class="fa-solid fa-exclamation-triangle me-1"></i>Sắp hết!
                                        </small>
                                    @elseif($remaining <= 15)
                                        <small class="text-warning fw-bold d-block text-center">
                                            <i class="fa-solid fa-clock me-1"></i>Còn ít!
                                        </small>
                                    @endif
                                </div>
                            @else
                                @php
                                    $hasUsed = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                                @endphp
                                @if($hasUsed)
                                    <div class="alert alert-info py-2 px-3 mb-3 text-center" style="border-radius: 10px; font-size: 0.85rem;">
                                        <i class="fa-solid fa-check-circle me-1"></i> Bạn đã sử dụng mã này
                                    </div>
                                @else
                                    <p class="text-success mb-3 text-center" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-infinity me-1"></i> Không giới hạn lượt dùng
                                    </p>
                                @endif
                            @endif

                            <!-- Action Button -->
                            @php
                                $isDisabled = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                            @endphp
                            <div class="d-flex flex-column align-items-center justify-content-center text-center">
                                <button class="btn {{ $colorScheme['btn_class'] }} btn-lg fw-bold px-4 py-2 rounded-pill save-coupon-btn shadow {{ $isDisabled ? 'disabled' : '' }}"
                                        data-code="{{ $coupon->code }}" 
                                        data-discount="{{ $discountText }}"
                                        data-description="{{ $coupon->description ?? 'Mã giảm giá đặc biệt' }}"
                                        data-discount-type="{{ $coupon->discount_type ?? 'percentage' }}"
                                        data-discount-value="{{ $coupon->discount }}"
                                        data-max-discount="{{ $coupon->max_discount_amount ?? '' }}"
                                        data-min-order="{{ $coupon->min_order_amount ?? '' }}"
                                        style="min-width: 160px; transition: all 0.3s ease; {{ $isDisabled ? 'opacity: 0.6; cursor: not-allowed;' : '' }}"
                                        {{ $isDisabled ? 'disabled' : '' }}
                                        onmouseover="{{ $isDisabled ? '' : 'this.style.transform=\'scale(1.05)\';' }}"
                                        onmouseout="{{ $isDisabled ? '' : 'this.style.transform=\'scale(1)\';' }}">
                                    <i class="fa-solid fa-{{ $isDisabled ? 'check' : 'bookmark' }} me-2"></i>
                                    {{ $isDisabled ? 'Đã sử dụng' : 'Lưu mã' }}
                                </button>
                                <div class="mt-2">
                                    <span class="badge bg-white {{ $colorScheme['border_class'] }} border px-2 py-1 shadow-sm"
                                          style="font-size: 0.8rem; font-weight: 600;">{{ $badges[$index % count($badges)] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fa-solid fa-search fa-5x text-muted opacity-50"></i>
                    </div>
                    <h3 class="text-muted mb-3">Không tìm thấy mã giảm giá nào</h3>
                    <p class="text-muted mb-4">Hãy thử điều chỉnh bộ lọc hoặc quay lại sau để xem thêm ưu đãi mới!</p>
                    <a href="{{ route('client.coupons.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-refresh me-2"></i>Xem tất cả
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($coupons->hasPages())
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Coupon pagination">
                    {{ $coupons->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="couponToast" class="toast align-items-center text-white bg-success border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa-solid fa-check-circle me-2"></i>
                <span id="toastMessage">Đã lưu mã vào tài khoản!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .filter-section {
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .coupon-card {
        border: none !important;
    }
    
    .condition-item {
        padding: 0.25rem 0;
    }
    
    .usage-stats .progress {
        background-color: rgba(0,0,0,0.1);
    }
    
    /* Custom colors for additional schemes */
    .text-purple { color: #7b1fa2 !important; }
    .text-pink { color: #db2777 !important; }
    .bg-purple { background-color: #7b1fa2 !important; }
    .bg-pink { background-color: #db2777 !important; }
    .btn-purple { 
        background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%);
        border: none;
        color: white;
    }
    .btn-pink { 
        background: linear-gradient(135deg, #db2777 0%, #ec4899 100%);
        border: none;
        color: white;
    }
    .border-purple { border-color: #7b1fa2 !important; }
    .border-pink { border-color: #db2777 !important; }
    
    @media (max-width: 768px) {
        .coupon-card {
            margin-bottom: 1rem;
        }
        
        .filter-section .row {
            row-gap: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('couponToast');
        const toastMessage = document.getElementById('toastMessage');

        toastMessage.textContent = message;
        toast.className = `toast align-items-center text-white bg-${type} border-0`;

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Save coupon via AJAX
    function saveCoupon(couponId) {
        return fetch('{{ route("client.coupons.save") }}', {
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
                // Update header badge
                updateHeaderCouponBadge(data.saved_count);
                return true;
            } else {
                showToast(data.message, 'warning');
                return false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra khi lưu mã giảm giá', 'danger');
            return false;
        });
    }

    // Update header coupon badge
    function updateHeaderCouponBadge(count = null) {
        const badge = document.getElementById('headerCouponCount');
        if (badge && count !== null) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
                
                // Add pulse animation
                badge.style.animation = 'pulse 0.5s ease-in-out';
                setTimeout(() => {
                    badge.style.animation = '';
                }, 500);
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Handle save coupon buttons
    document.querySelectorAll('.save-coupon-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Check if button is disabled
            if (btn.disabled || btn.classList.contains('disabled')) {
                return;
            }

            @guest
                showToast('Vui lòng đăng nhập để lưu mã giảm giá', 'warning');
                return;
            @endguest

            const couponCode = btn.getAttribute('data-code');
            // Find coupon ID from the current coupon list
            const couponId = @json($coupons->pluck('id', 'code'));
            
            if (!couponId[couponCode]) {
                showToast('Không tìm thấy mã giảm giá', 'danger');
                return;
            }

            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang lưu...';
            btn.disabled = true;

            saveCoupon(couponId[couponCode]).then(success => {
                if (success) {
                    btn.innerHTML = '<i class="fa-solid fa-check me-2"></i> Đã lưu thành công';
                    showToast(`Đã lưu mã ${couponCode} thành công! Xem tại trang Mã giảm giá của tôi.`, 'success');
                } else {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }
            });
        });
    });

    // Auto-submit form when filters change
    document.querySelectorAll('#filterForm select').forEach(function(select) {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endsection
