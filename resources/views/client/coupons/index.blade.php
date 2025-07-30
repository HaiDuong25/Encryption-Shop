@extends('client.layout.main')

@section('content')
    <!-- Hero Section -->
    <section class="coupon-hero py-5"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden;">
        <div class="hero-decoration"
            style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; transform: rotate(45deg);">
        </div>
        <div class="hero-decoration"
            style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255, 255, 255, 0.05); border-radius: 50%;">
        </div>

        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <div class="hero-icon mb-4">
                        <i class="fa-solid fa-ticket fa-4x" style="opacity: 0.9;"></i>
                    </div>
                    <h1 class="fw-bold mb-3" style="font-size: 3rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                        Mã Giảm Giá Của Tôi
                    </h1>
                    <p class="lead mb-4" style="font-size: 1.3rem; opacity: 0.9;">
                        Quản lý và sử dụng các mã giảm giá đã lưu một cách dễ dàng
                    </p>
                    <div class="hero-stats d-flex justify-content-center gap-4">
                        <div class="stat-item">
                            <div class="stat-number fw-bold" style="font-size: 2rem;" id="totalCouponsCount">0</div>
                            <div class="stat-label">Mã đã lưu</div>
                        </div>
                        <div class="stat-divider" style="width: 1px; background: rgba(255,255,255,0.3);"></div>
                        <div class="stat-item">
                            <div class="stat-number fw-bold" style="font-size: 2rem;">💰</div>
                            <div class="stat-label">Tiết kiệm chi phí</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Action Bar -->
                    <div class="action-bar mb-5 p-4 rounded-4 shadow-sm"
                        style="background: white; border: 1px solid rgba(0,0,0,0.05);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <a href="/" class="btn btn-outline-primary rounded-pill px-4 py-2 hover-lift">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Về trang chủ
                                </a>
                                <div class="coupon-counter bg-light px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-bookmark text-primary me-2"></i>
                                    <span class="fw-semibold" id="currentCouponsCount">0 mã đã lưu</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button id="refreshCoupons" class="btn btn-outline-info rounded-pill px-4 py-2 hover-lift">
                                    <i class="fa-solid fa-refresh me-2"></i>Làm mới
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Coupons Container -->
                    <div id="savedCouponsContent" class="coupons-grid">
                        <!-- Loading spinner initially -->
                        <div class="loading-state text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                            <p class="mt-3 text-muted">Đang tải mã giảm giá...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="couponActionToast" class="toast align-items-center border-0 shadow-lg" role="alert" aria-live="assertive"
            aria-atomic="true" style="border-radius: 1rem;">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="toast-icon me-2 fa-lg"></i>
                    <span id="toastActionMessage" class="fw-semibold">Thao tác thành công!</span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }

            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        /* Hero Section */
        .coupon-hero {
            position: relative;
            background-attachment: fixed;
        }

        .hero-icon {
            animation: pulse 3s infinite;
        }

        .stat-item {
            text-align: center;
            min-width: 100px;
        }

        /* Action Bar */
        .action-bar {
            animation: fadeInUp 0.6s ease-out;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced Coupon Cards */
        .coupon-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2rem;
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.6s ease-out;
        }

        .coupon-card:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .coupon-card:nth-child(even) {
            animation-delay: 0.2s;
        }

        .coupon-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .coupon-card:hover::before {
            left: 100%;
        }

        .coupon-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .coupon-badge {
            animation: slideInRight 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .coupon-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.3s;
        }

        .coupon-card:hover .coupon-badge::before {
            left: 100%;
        }

        /* Enhanced Buttons */
        .btn-enhanced {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-enhanced:hover::before {
            left: 100%;
        }

        .btn-copy:hover {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }

        .btn-remove:hover {
            background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 2rem;
            border: 2px dashed #dee2e6;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: pulse 4s infinite;
        }

        .empty-state i {
            font-size: 5rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
            opacity: 0.6;
            animation: pulse 2s infinite;
        }

        /* Loading State */
        .loading-state {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Grid Layout */
        .coupons-grid .row>* {
            animation: fadeInUp 0.6s ease-out;
        }

        .coupons-grid .row>*:nth-child(1) {
            animation-delay: 0.1s;
        }

        .coupons-grid .row>*:nth-child(2) {
            animation-delay: 0.2s;
        }

        .coupons-grid .row>*:nth-child(3) {
            animation-delay: 0.3s;
        }

        .coupons-grid .row>*:nth-child(4) {
            animation-delay: 0.4s;
        }

        .coupons-grid .row>*:nth-child(5) {
            animation-delay: 0.5s;
        }

        .coupons-grid .row>*:nth-child(6) {
            animation-delay: 0.6s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .coupon-hero h1 {
                font-size: 2rem !important;
            }

            .action-bar {
                padding: 1rem !important;
            }

            .hero-stats {
                flex-direction: column !important;
                gap: 1rem !important;
            }

            .stat-divider {
                display: none !important;
            }
        }

        /* Enhanced Toast */
        .toast {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .toast.bg-success {
            background: linear-gradient(135deg, #198754 0%, #157347 100%) !important;
        }

        .toast.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important;
            color: #000 !important;
        }

        .toast.bg-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #3dd5f3 100%) !important;
        }

        .toast.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Coupon management functions
            function getSavedCoupons() {
                const saved = localStorage.getItem('savedCoupons');
                return saved ? JSON.parse(saved) : [];
            }

            function removeSavedCoupon(code) {
                let savedCoupons = getSavedCoupons();
                savedCoupons = savedCoupons.filter(c => c.code !== code);
                localStorage.setItem('savedCoupons', JSON.stringify(savedCoupons));
                loadSavedCoupons();
                updateHeaderCouponBadge();
                updateCounters();
                showToast(`Đã xóa mã ${code}!`, 'success');
                return true;
            }

            // Simple function to check if coupon exists
            function isCouponSaved(code) {
                const savedCoupons = getSavedCoupons();
                return savedCoupons.find(c => c.code === code) !== undefined;
            }

            // Enhanced expose functions globally for cart/checkout pages to use
            window.couponManager = {
                removeCouponAfterPayment: removeSavedCoupon,
                isCouponSaved: isCouponSaved,
                getSavedCoupons: getSavedCoupons,
                updateDisplay: loadSavedCoupons
            };

            function updateHeaderCouponBadge() {
                const savedCoupons = getSavedCoupons();
                const badge = document.getElementById('headerCouponCount');

                if (badge) {
                    if (savedCoupons.length > 0) {
                        badge.textContent = savedCoupons.length;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            function updateCounters() {
                const savedCoupons = getSavedCoupons();
                const totalCount = document.getElementById('totalCouponsCount');
                const currentCount = document.getElementById('currentCouponsCount');

                if (totalCount) totalCount.textContent = savedCoupons.length;
                if (currentCount) currentCount.textContent = `${savedCoupons.length} mã đã lưu`;
            }

            function showToast(message, type = 'success') {
                const toast = document.getElementById('couponActionToast');
                const toastMessage = document.getElementById('toastActionMessage');
                const toastIcon = toast.querySelector('.toast-icon');

                toastMessage.textContent = message;
                toast.className = `toast align-items-center text-white bg-${type} border-0 shadow-lg`;

                // Update icon based on type
                const icons = {
                    success: 'fa-solid fa-check-circle',
                    warning: 'fa-solid fa-exclamation-triangle',
                    info: 'fa-solid fa-info-circle',
                    danger: 'fa-solid fa-times-circle'
                };

                toastIcon.className = `toast-icon me-2 fa-lg ${icons[type] || icons.success}`;

                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }

            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast(`Đã sao chép mã ${text}! Hãy vào giỏ hàng để áp dụng mã này.`, 'success');

                    // Redirect to cart page after copying
                    setTimeout(() => {
                        window.location.href = '/cart';
                    }, 1500);
                }).catch(() => {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    showToast(`Đã sao chép mã ${text}! Hãy vào giỏ hàng để áp dụng mã này.`, 'success');

                    // Redirect to cart page after copying
                    setTimeout(() => {
                        window.location.href = '/cart';
                    }, 1500);
                });
            }

            function loadSavedCoupons() {
                const savedCoupons = getSavedCoupons();
                const content = document.getElementById('savedCouponsContent');

                // Add loading delay for better UX
                setTimeout(() => {
                    if (savedCoupons.length === 0) {
                        content.innerHTML = `
                                <div class="empty-state">
                                    <i class="fa-solid fa-ticket-simple"></i>
                                    <h3 class="text-muted mb-3">Chưa có mã giảm giá nào</h3>
                                    <p class="text-muted mb-4 lead">Hãy khám phá và lưu các mã giảm giá hấp dẫn từ trang chủ!</p>
                                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                                        <a href="/" class="btn btn-primary rounded-pill px-5 py-3 hover-lift">
                                            <i class="fa-solid fa-home me-2"></i>Về trang chủ
                                        </a>
                                        <a href="/#voucher-section" class="btn btn-outline-primary rounded-pill px-5 py-3 hover-lift">
                                            <i class="fa-solid fa-tags me-2"></i>Xem ưu đãi
                                        </a>
                                    </div>
                                </div>
                            `;
                        updateCounters();
                        return;
                    }

                    let html = `
                            <div class="section-header mb-5 text-center">
                                <h3 class="fw-bold text-dark mb-2">
                                    <i class="fa-solid fa-wallet me-2 text-primary"></i>
                                    Kho mã giảm giá của bạn
                                </h3>
                                <p class="text-muted">Sao chép mã để sử dụng khi thanh toán trong giỏ hàng</p>
                            </div>
                            <div class="row g-4">
                        `;

                    savedCoupons.forEach((coupon, index) => {
                        const colors = [
                            { bg: 'linear-gradient(135deg, #fff9e6 0%, #ffe0b3 100%)', border: 'border-warning', text: 'text-warning', icon: 'text-warning', accent: '#ffc107' },
                            { bg: 'linear-gradient(135deg, #e8f5e8 0%, #c3e6c3 100%)', border: 'border-success', text: 'text-success', icon: 'text-success', accent: '#198754' },
                            { bg: 'linear-gradient(135deg, #e6f3ff 0%, #b3d9ff 100%)', border: 'border-primary', text: 'text-primary', icon: 'text-primary', accent: '#0d6efd' },
                            { bg: 'linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%)', border: 'border-secondary', text: 'text-secondary', icon: 'text-secondary', accent: '#6f42c1' },
                            { bg: 'linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%)', border: 'border-danger', text: 'text-danger', icon: 'text-danger', accent: '#dc3545' },
                            { bg: 'linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%)', border: 'border-info', text: 'text-info', icon: 'text-info', accent: '#0dcaf0' }
                        ];
                        const colorScheme = colors[index % colors.length];

                        html += `
                                <div class="col-md-6 col-lg-4 coupon-item" data-code="${coupon.code}">
                                    <div class="card border-0 h-100 shadow-lg coupon-card" style="background: ${colorScheme.bg};">
                                        <div class="card-body p-4 text-center d-flex flex-column position-relative">
                                            <!-- Decorative Elements -->
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <div class="badge bg-white ${colorScheme.text} rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="coupon-icon-container mb-3" style="position: relative;">
                                                    <i class="fa-solid fa-gift fa-3x ${colorScheme.icon}" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));"></i>
                                                    <div class="icon-glow" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px; background: ${colorScheme.accent}; opacity: 0.1; border-radius: 50%; filter: blur(20px);"></div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <span class="badge coupon-badge ${colorScheme.border} ${colorScheme.text} bg-white px-4 py-3 rounded-pill fw-bold border-2 shadow-sm" style="font-size: 1.2rem; letter-spacing: 2px; position: relative;">
                                                    ${coupon.code}
                                                </span>
                                            </div>

                                            <h5 class="fw-bold ${colorScheme.text} mb-3" style="font-size: 1.3rem;">
                                                ${coupon.discount}
                                            </h5>

                                            <p class="text-muted mb-3 flex-grow-1" style="font-size: 0.95rem; line-height: 1.5;">
                                                ${coupon.description}
                                            </p>

                                            <div class="coupon-meta mb-4 p-3 rounded-3" style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.3);">
                                                <div class="d-flex align-items-center justify-content-center text-muted small">
                                                    <i class="fa-solid fa-calendar-plus me-2"></i>
                                                    <span>Lưu: ${new Date(coupon.savedAt).toLocaleDateString('vi-VN')}</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center text-success small mt-2">
                                                    <i class="fa-solid fa-check-circle me-2"></i>
                                                    <span class="fw-bold">Sẵn sàng sử dụng</span>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-primary btn-enhanced btn-copy-coupon rounded-pill px-4 py-2 flex-grow-1" 
                                                        data-code="${coupon.code}" 
                                                        style="font-weight: 600;">
                                                    <i class="fa-solid fa-copy me-2"></i>Sao chép
                                                </button>
                                                <button class="btn btn-outline-danger btn-enhanced btn-remove-coupon rounded-pill px-3 py-2" 
                                                        data-code="${coupon.code}" 
                                                        title="Xóa mã">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                    });
                    html += '</div>';

                    content.innerHTML = html;
                    updateCounters();

                    // Add event listeners
                    setTimeout(() => {
                        // Copy coupon buttons
                        document.querySelectorAll('.btn-copy-coupon').forEach(btn => {
                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();

                                const code = this.getAttribute('data-code');
                                if (!code) return;

                                // Copy to clipboard and redirect to cart
                                copyToClipboard(code);

                                // Visual feedback
                                const originalHTML = this.innerHTML;
                                this.innerHTML = '<i class="fa-solid fa-check me-2"></i>Đã sao chép!';
                                this.disabled = true;
                                this.style.background = 'linear-gradient(135deg, #198754 0%, #157347 100%)';

                                // Don't restore button since we're redirecting
                            });
                        });

                        // Remove coupon buttons
                        document.querySelectorAll('.btn-remove-coupon').forEach(btn => {
                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();

                                const code = this.getAttribute('data-code');
                                if (!code) return;

                                // Confirm deletion
                                if (confirm(`Bạn có chắc chắn muốn xóa mã ${code} khỏi danh sách đã lưu?`)) {
                                    removeSavedCoupon(code);
                                }
                            });
                        });
                    }, 100);

                }, 300);
            }

            // Enhanced refresh functionality
            document.getElementById('refreshCoupons').addEventListener('click', function (e) {
                e.preventDefault();

                const content = document.getElementById('savedCouponsContent');
                content.innerHTML = `
                                                    <div class="loading-state text-center py-5">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Đang làm mới...</span>
                                                        </div>
                                                        <p class="mt-3 text-muted">Đang làm mới danh sách...</p>
                                                    </div>
                                                `;

                // Add refresh animation to button
                this.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    this.style.transform = '';
                    loadSavedCoupons();
                }, 500);

                showToast('Đã làm mới danh sách mã giảm giá!', 'info');
            });

            // Initialize with loading state, then load coupons
            setTimeout(() => {
                loadSavedCoupons();
                updateCounters();
            }, 500);

            // Listen for payment success events to remove used coupons (optional - only if you want to remove after payment)
            window.addEventListener('paymentSuccess', function (event) {
                const couponCode = event.detail.couponCode;
                if (couponCode && window.couponManager) {
                    // Optional: Remove coupon after successful payment
                    // window.couponManager.removeCouponAfterPayment(couponCode);

                    // Just show a success message instead
                    showToast(`Thanh toán thành công với mã ${couponCode}!`, 'success');
                }
            });
        });
    </script>
@endsection