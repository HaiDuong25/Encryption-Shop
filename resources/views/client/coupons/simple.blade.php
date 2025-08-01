<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Coupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Tất cả mã giảm giá</h1>
        
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3>{{ $totalCoupons }}</h3>
                        <p>Tổng số mã</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3>{{ $expiringSoon }}</h3>
                        <p>Sắp hết hạn</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h3>{{ $unlimitedCoupons }}</h3>
                        <p>Không giới hạn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupons Grid -->
        <div class="row">
            @if($coupons->count() > 0)
                @foreach($coupons as $coupon)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $coupon->code }}</h5>
                                @if($coupon->discount_type === 'percentage')
                                    <h4 class="text-primary">Giảm {{ $coupon->discount }}%</h4>
                                @else
                                    <h4 class="text-primary">Giảm {{ format_vnd($coupon->discount) }}₫</h4>
                                @endif
                                
                                @if($coupon->description)
                                    <p class="card-text">{{ $coupon->description }}</p>
                                @endif

                                @if($coupon->min_order_amount)
                                    <small class="text-muted d-block">
                                        Đơn tối thiểu: {{ format_vnd($coupon->min_order_amount) }}₫
                                    </small>
                                @endif

                                @if($coupon->usage_limit > 0)
                                    <small class="text-info d-block">
                                        Còn lại: {{ $coupon->remainingUsage() }}/{{ $coupon->usage_limit }}
                                    </small>
                                @else
                                    <small class="text-success d-block">Không giới hạn</small>
                                @endif

                                @if($coupon->expires_at)
                                    <small class="text-warning d-block">
                                        Hết hạn: {{ $coupon->expires_at->format('d/m/Y') }}
                                    </small>
                                @endif

                                <button class="btn btn-primary mt-2" 
                                        onclick="saveCoupon('{{ $coupon->code }}', '{{ $coupon->discount_type === 'percentage' ? 'Giảm ' . $coupon->discount . '%' : 'Giảm ' . format_vnd($coupon->discount) . '₫' }}')">
                                    <i class="fa-solid fa-bookmark me-1"></i>
                                    Lưu mã
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Không có mã giảm giá nào</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($coupons->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveCoupon(code, discount) {
            let savedCoupons = JSON.parse(localStorage.getItem('savedCoupons') || '[]');
            
            if (!savedCoupons.find(c => c.code === code)) {
                savedCoupons.push({
                    code: code,
                    discount_text: discount,
                    savedAt: new Date().toISOString()
                });
                localStorage.setItem('savedCoupons', JSON.stringify(savedCoupons));
                alert('Đã lưu mã ' + code + ' thành công!');
            } else {
                alert('Mã ' + code + ' đã được lưu trước đó!');
            }
        }
    </script>
</body>
</html>
