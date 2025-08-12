@extends('client.layout.main')

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Mã giảm giá của tôi</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Mã giảm giá</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- Hero Stats Section -->
<section class="py-4">
    <div class="container-fluid-lg">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 p-4 rounded-4 shadow-sm">
                    <!-- Tiêu đề -->
                    <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <i class="fa-solid fa-list me-2"></i> Danh sách mã giảm giá
                    </h4>
                    <!-- Nút -->
                    <a href="{{ route('client.coupons.index') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                        <i class="fa-solid fa-plus me-2"></i> Tìm thêm mã
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Main Content -->
  
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                    <!-- Action Bar --> 
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        </div>
                    </div>
                    <!-- Saved Coupons Grid -->
                    @if($savedCoupons->count() > 0)
                                <div class="row">
                                    @foreach($savedCoupons as $index => $savedCoupon)
                                                    @php
                                                        $coupon = $savedCoupon->coupon;
                                                    @endphp

                                                    @if($coupon)
                                                                @php
                                                                    $colors = [
                                                                        ['bg' => 'linear-gradient(135deg, #fff9e6 0%, #ffe0b3 100%)', 'shadow' => 'rgba(255, 193, 7, 0.2)', 'shadow_hover' => 'rgba(255, 193, 7, 0.3)', 'decoration' => 'rgba(255, 193, 7, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #fff3cd 0%, #ffe082 100%)', 'icon_shadow' => 'rgba(255, 193, 7, 0.3)', 'icon_color' => 'text-warning', 'badge_class' => 'bg-warning text-dark', 'text_color' => 'text-warning', 'btn_class' => 'btn-warning'],
                                                                        ['bg' => 'linear-gradient(135deg, #e8f5e8 0%, #c3e6c3 100%)', 'shadow' => 'rgba(40, 167, 69, 0.2)', 'shadow_hover' => 'rgba(40, 167, 69, 0.3)', 'decoration' => 'rgba(40, 167, 69, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)', 'icon_shadow' => 'rgba(40, 167, 69, 0.3)', 'icon_color' => 'text-success', 'badge_class' => 'bg-success text-white', 'text_color' => 'text-success', 'btn_class' => 'btn-success'],
                                                                        ['bg' => 'linear-gradient(135deg, #e6f3ff 0%, #b3d9ff 100%)', 'shadow' => 'rgba(0, 123, 255, 0.2)', 'shadow_hover' => 'rgba(0, 123, 255, 0.3)', 'decoration' => 'rgba(0, 123, 255, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%)', 'icon_shadow' => 'rgba(0, 123, 255, 0.3)', 'icon_color' => 'text-primary', 'badge_class' => 'bg-primary text-white', 'text_color' => 'text-primary', 'btn_class' => 'btn-primary'],
                                                                        ['bg' => 'linear-gradient(135deg, #f3e8ff 0%, #d9b3ff 100%)', 'shadow' => 'rgba(123, 31, 162, 0.2)', 'shadow_hover' => 'rgba(123, 31, 162, 0.3)', 'decoration' => 'rgba(123, 31, 162, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #e8d5ff 0%, #c084fc 100%)', 'icon_shadow' => 'rgba(123, 31, 162, 0.3)', 'icon_color' => 'text-purple', 'badge_class' => 'bg-purple text-white', 'text_color' => 'text-purple', 'btn_class' => 'btn-purple'],
                                                                        ['bg' => 'linear-gradient(135deg, #ffe8f0 0%, #ffb3d1 100%)', 'shadow' => 'rgba(219, 39, 119, 0.2)', 'shadow_hover' => 'rgba(219, 39, 119, 0.3)', 'decoration' => 'rgba(219, 39, 119, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%)', 'icon_shadow' => 'rgba(219, 39, 119, 0.3)', 'icon_color' => 'text-pink', 'badge_class' => 'bg-pink text-white', 'text_color' => 'text-pink', 'btn_class' => 'btn-pink']
                                                                    ];
                                                                    $colorScheme = $colors[$index % 5];

                                                                    // Format discount value
                                                                    $discountText = '';
                                                                    if ($coupon->discount_type === 'percentage') {
                                                                        $discountText = "Giảm {$coupon->discount}%";
                                                                        if ($coupon->max_discount_amount) {
                                                                            $discountText .= " (tối đa " . format_vnd($coupon->max_discount_amount) . "₫)";
                                                                        }
                                                                    } else {
                                                                        $discountText = "Giảm " . format_vnd($coupon->discount) . "₫";
                                                                    }

                                                                    // Check if coupon has been used
                                                                    $hasUsed = $coupon->hasBeenUsedByUser(Auth::id());
                                                                    $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                                                                    $isValid = $coupon->canBeUsed() && !$hasUsed && !$isExpired;
                                                                @endphp

                                                                <div class="col-lg-4 col-md-6 mb-4">
                                                                    <div class="coupon-card h-100 {{ !$isValid ? 'opacity-75' : '' }}"
                                                                        style="background: {{ $colorScheme['bg'] }}; border-radius: 1.5rem; box-shadow: 0 8px 25px {{ $colorScheme['shadow'] }}; transition: all 0.3s ease; overflow: hidden; position: relative; border: none;"
                                                                        onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 35px {{ $colorScheme['shadow_hover'] }}';"
                                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px {{ $colorScheme['shadow'] }}';">

                                                                        <!-- Decoration -->
                                                                        <div class="coupon-decoration"
                                                                            style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: {{ $colorScheme['decoration'] }}; border-radius: 50%; transform: rotate(45deg);">
                                                                        </div>

                                                                        <!-- Saved Badge -->
                                                                        <div class="position-absolute top-0 start-0 m-3">
                                                                            <span class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                                                <i class="fa-solid fa-bookmark me-1"></i>Đã lưu
                                                                            </span>
                                                                        </div>

                                                                        <div class="card-body p-4" style="position: relative; z-index: 1;">
                                                                            <!-- Top Section -->
                                                                            <div class="text-center mb-3">
                                                                                <div class="coupon-icon mb-3">
                                                                                    <i class="fa-solid fa-ticket fa-2x {{ $colorScheme['icon_color'] }}"
                                                                                        style="background: {{ $colorScheme['icon_bg'] }}; border-radius: 50%; padding: 15px; box-shadow: 0 6px 15px {{ $colorScheme['icon_shadow'] }};"></i>
                                                                                </div>
                                                                                <span class="badge {{ $colorScheme['badge_class'] }} px-3 py-2 rounded-pill fw-bold"
                                                                                    style="font-size: 1rem; letter-spacing: 1px;">{{ $coupon->code }}</span>
                                                                            </div>

                                                                            <!-- Discount Amount -->
                                                                            <h4 class="fw-bold {{ $colorScheme['text_color'] }} text-center mb-3"
                                                                                style="font-size: 1.3rem;">
                                                                                {{ $discountText }}
                                                                            </h4>

                                                                            <!-- Description -->
                                                                            @if($coupon->description)
                                                                                <p class="text-muted text-center mb-3" style="font-size: 0.95rem;">
                                                                                    {{ $coupon->description }}</p>
                                                                            @endif

                                                                            <!-- Conditions -->
                                                                            <div class="conditions mb-3">
                                                                                @if($coupon->min_order_amount)
                                                                                    <div class="condition-item d-flex align-items-center mb-2">
                                                                                        <i class="fa-solid fa-shopping-cart me-2 text-info"
                                                                                            style="font-size: 0.9rem;"></i>
                                                                                        <small class="text-muted">Đơn tối thiểu:
                                                                                            {{ format_vnd($coupon->min_order_amount) }}₫</small>
                                                                                    </div>
                                                                                @endif

                                                                                @if($coupon->start_date && $coupon->end_date)
                                                                                    <div class="condition-item d-flex align-items-center mb-2">
                                                                                        <i class="fa-solid fa-calendar me-2 text-info" style="font-size: 0.9rem;"></i>
                                                                                        <small class="text-muted">{{ $coupon->start_date->format('d/m/Y') }} -
                                                                                            {{ $coupon->end_date->format('d/m/Y') }}</small>
                                                                                    </div>
                                                                                @elseif($coupon->expires_at)
                                                                                    <div class="condition-item d-flex align-items-center mb-2">
                                                                                        <i class="fa-solid fa-calendar me-2 text-info" style="font-size: 0.9rem;"></i>
                                                                                        <small class="text-muted">Hết hạn:
                                                                                            {{ $coupon->expires_at->format('d/m/Y') }}</small>
                                                                                    </div>
                                                                                @endif

                                                                                <!-- Saved Date -->
                                                                                <div class="condition-item d-flex align-items-center mb-2">
                                                                                    <i class="fa-solid fa-clock me-2 text-info" style="font-size: 0.9rem;"></i>
                                                                                    <small class="text-muted">Đã lưu:
                                                                                        {{ $savedCoupon->saved_at->format('d/m/Y H:i') }}</small>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Status -->
                                                                            @if($hasUsed)
                                                                                <div class="alert alert-info py-2 px-3 mb-3 text-center"
                                                                                    style="border-radius: 10px; font-size: 0.85rem;">
                                                                                    <i class="fa-solid fa-check-circle me-1"></i> Đã sử dụng
                                                                                </div>
                                                                            @elseif($isExpired)
                                                                                <div class="alert alert-warning py-2 px-3 mb-3 text-center"
                                                                                    style="border-radius: 10px; font-size: 0.85rem;">
                                                                                    <i class="fa-solid fa-exclamation-triangle me-1"></i> Đã hết hạn
                                                                                </div>
                                                                            @elseif(!$coupon->canBeUsed())
                                                                                <div class="alert alert-danger py-2 px-3 mb-3 text-center"
                                                                                    style="border-radius: 10px; font-size: 0.85rem;">
                                                                                    <i class="fa-solid fa-times-circle me-1"></i> Không khả dụng
                                                                                </div>
                                                                            @else
                                                                                <div class="alert alert-success py-2 px-3 mb-3 text-center"
                                                                                    style="border-radius: 10px; font-size: 0.85rem;">
                                                                                    <i class="fa-solid fa-check-circle me-1"></i> Sẵn sàng sử dụng
                                                                                </div>
                                                                            @endif

                                                                            <!-- Hiển thị mã - chỉ để xem, không có action buttons -->
                                                                            <div class="text-center">
                                                                                <div class="alert alert-light border py-3 px-4 mb-0"
                                                                                    style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                                                                    <div class="d-flex align-items-center justify-content-center">
                                                                                        <i class="fa-solid fa-ticket {{ $colorScheme['icon_color'] }} me-2"
                                                                                            style="font-size: 1.2rem;"></i>
                                                                                        <span class="fw-bold text-dark"
                                                                                            style="font-size: 1.1rem; letter-spacing: 1px;">{{ $coupon->code }}</span>
                                                                                    </div>
                                                                                    <small class="text-muted mt-2 d-block">Mã giảm giá đã lưu</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    @else
                                                        <!-- Coupon đã bị xóa hoặc không tồn tại -->
                                                        <div class="col-lg-4 col-md-6 mb-4">
                                                            <div class="coupon-card h-100 opacity-50"
                                                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 1.5rem; box-shadow: 0 8px 25px rgba(0,0,0,0.1); overflow: hidden; position: relative; border: 2px dashed #dee2e6;">
                                                                <div class="card-body p-4 text-center">
                                                                    <div class="mb-3">
                                                                        <i class="fa-solid fa-exclamation-triangle fa-3x text-muted"></i>
                                                                    </div>
                                                                    <h5 class="text-muted mb-3">Mã giảm giá không khả dụng</h5>
                                                                    <p class="text-muted mb-3">Mã giảm giá này có thể đã bị xóa hoặc không còn tồn tại trong
                                                                        hệ thống.</p>
                                                                    <button class="btn btn-outline-danger btn-sm px-3 py-2 rounded-pill remove-coupon-btn"
                                                                        data-coupon-id="0" data-code="unknown">
                                                                        <i class="fa-solid fa-trash me-1"></i>Xóa khỏi danh sách
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if($savedCoupons->hasPages())
                                    <div class="row mt-5">
                                        <div class="col-12 d-flex justify-content-center">
                                            <nav aria-label="Saved coupons pagination">
                                                {{ $savedCoupons->links('pagination::bootstrap-4') }}
                                            </nav>
                                        </div>
                                    </div>
                                @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fa-solid fa-bookmark fa-5x text-muted opacity-50"></i>
                            </div>
                            <h3 class="text-muted mb-3">Chưa có mã giảm giá nào được lưu</h3>
                            <p class="text-muted mb-4">Hãy khám phá và lưu các mã giảm giá hấp dẫn để tiết kiệm chi phí mua sắm!
                            </p>
                            <a href="{{ route('client.coupons.index') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="fa-solid fa-search me-2"></i>Tìm mã giảm giá
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="couponToast" class="toast align-items-center text-white bg-success border-0" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Thông báo
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

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

        // Simple message for view-only mode
        console.log('Trang mã giảm giá đã lưu - chỉ xem (không có action buttons)');
    });
</script>