@extends('client.layout.main')

@section('content')
    <!-- Success Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="mb-4">
                        <i class="fa-solid fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">
                        Thanh Toán Thành Công!
                    </h1>
                    <p class="lead mb-4" style="font-size: 1.2rem;">
                        Cảm ơn bạn đã đặt hàng. Mã giảm giá của bạn đã được áp dụng.
                    </p>
                    <div class="mt-4">
                        <a href="/" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="fa-solid fa-home me-2"></i>Về trang chủ
                        </a>
                        <a href="/#voucher-section" class="btn btn-outline-primary rounded-pill px-4 py-2">
                            <i class="fa-solid fa-tags me-2"></i>Xem ưu đãi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Get coupon code from session or order data
                const usedCouponCode = '{{ session("used_coupon_code") ?? ($order->coupon_code ?? "") }}';

                if (usedCouponCode && window.couponManager) {
                    // Dispatch payment success event to remove coupon permanently
                    window.dispatchEvent(new CustomEvent('paymentSuccess', {
                        detail: { couponCode: usedCouponCode }
                    }));

                    // Also call directly if couponManager is available
                    window.couponManager.removeCouponAfterPayment(usedCouponCode);
                }
            });
        </script>
    @endpush
@endsection