@extends('client.layout.main')

@section('content')
<style>
    .order-info-card, .payment-details-card {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .order-info-card:hover, .payment-details-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .action-buttons .btn {
        transition: all 0.3s ease;
    }
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
    }
    .alert {
        border: none;
        border-radius: 6px;
    }
    @media (max-width: 768px) {
        .order-info-card, .payment-details-card {
            margin-left: -15px;
            margin-right: -15px;
        }
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain breadcrumb-order">
                        <div class="order-box">
                            <div class="order-image">
                                <div class="checkmark">
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="star" height="19" viewBox="0 0 19 19" width="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.296.747c.532-.972 1.393-.973 1.925 0l2.665 4.872 4.876 2.66c.974.532.975 1.393 0 1.926l-4.875 2.666-2.664 4.876c-.53.972-1.39.973-1.924 0l-2.664-4.876L.76 10.206c-.972-.532-.973-1.393 0-1.925l4.872-2.66L8.296.746z">
                                        </path>
                                    </svg>
                                    <svg class="checkmark__check" height="36" viewBox="0 0 48 36" width="48" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M47.248 3.9L43.906.667a2.428 2.428 0 0 0-3.344 0l-23.63 23.09-9.554-9.338a2.432 2.432 0 0 0-3.345 0L.692 17.654a2.236 2.236 0 0 0 .002 3.233l14.567 14.175c.926.894 2.42.894 3.342.01L47.248 7.128c.922-.89.922-2.34 0-3.23">
                                        </path>
                                    </svg>
                                    <svg class="checkmark__background" height="115" viewBox="0 0 120 115" width="120" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M107.332 72.938c-1.798 5.557 4.564 15.334 1.21 19.96-3.387 4.674-14.646 1.605-19.298 5.003-4.61 3.368-5.163 15.074-10.695 16.878-5.344 1.743-12.628-7.35-18.545-7.35-5.922 0-13.206 9.088-18.543 7.345-5.538-1.804-6.09-13.515-10.696-16.877-4.657-3.398-15.91-.334-19.297-5.002-3.356-4.627 3.006-14.404 1.208-19.962C10.93 67.576 0 63.442 0 57.5c0-5.943 10.93-10.076 12.668-15.438 1.798-5.557-4.564-15.334-1.21-19.96 3.387-4.674 14.646-1.605 19.298-5.003C35.366 13.73 35.92 2.025 41.45.22c5.344-1.743 12.628 7.35 18.545 7.35 5.922 0 13.206-9.088 18.543-7.345 5.538 1.804 6.09 13.515 10.696 16.877 4.657 3.398 15.91.334 19.297 5.002 3.356 4.627-3.006 14.404-1.208 19.962C109.07 47.424 120 51.562 120 57.5c0 5.943-10.93 10.076-12.668 15.438z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="order-contain">
                                <h3 class="theme-color">Đơn hàng của bạn đã được đặt thành công!</h3>
                                
                                @php
                                    $paymentMethod = $order->paymentMethod ?? null;
                                    $latestPayment = $order->payments->where('status', 'completed')->first() ?? null;
                                    $isOnlinePayment = $latestPayment && $latestPayment->payment_method_type && in_array($latestPayment->payment_method_type, ['MoMo', 'ZaloPay']);
                                    $isCOD = $paymentMethod && $paymentMethod->payment_type === 'COD';
                                @endphp
                                
                                <h5 class="text-content">
                                    @if($isOnlinePayment)
                                        @if($latestPayment->payment_method_type === 'MoMo')
                                            <i class="fas fa-mobile-alt text-info me-2"></i>Thanh toán MoMo thành công! Đơn hàng sẽ được xử lý ngay.
                                        @elseif($latestPayment->payment_method_type === 'ZaloPay')
                                            <i class="fas fa-wallet text-primary me-2"></i>Thanh toán ZaloPay thành công! Đơn hàng sẽ được xử lý ngay.
                                        @endif
                                    @elseif($isCOD)
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>Đơn hàng COD đã được tạo! Bạn sẽ thanh toán khi nhận hàng.
                                    @else
                                        Đơn hàng của bạn sẽ sớm được giao, vui lòng kiểm tra tại mục Đơn hàng.
                                    @endif
                                </h5>
                                
                                <!-- Thông tin cơ bản đơn hàng -->
                                <div class="order-info-card mt-3 p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                                    <h6 class="mb-2"><i class="fas fa-receipt me-2"></i>Thông tin đơn hàng</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Mã đơn hàng:</strong> <span class="text-primary">#{{ $order->id }}</span></p>
                                            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="mb-1"><strong>Phương thức:</strong> {{ $paymentMethod->payment_type ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            
                                            <!-- Sản phẩm trong đơn hàng -->
                                            @if($order->orderDetails && $order->orderDetails->count() > 0)
                                            <div class="mb-1">
                                                <strong>Sản phẩm:</strong><br>
                                                @foreach($order->orderDetails as $detail)
                                                <div class="text-muted small mb-1">
                                                    • {{ $detail->product->name ?? 'Sản phẩm không tìm thấy' }}
                                                    @if($detail->variant && $detail->variant->attributeValues && $detail->variant->attributeValues->count() > 0)
                                                    ({{ $detail->variant->attributeValues->pluck('value')->implode(', ') }})
                                                    @endif
                                                    - SL: {{ $detail->quantity }}
                                                </div>
                                                <p class="mb-1"><strong>Tổng tiền:</strong> <span class="text-success fs-5 fw-bold">{{ number_format($order->total_price ?? $order->total, 0, ',', '.') }}đ</span></p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Chi tiết thanh toán theo loại -->
                                @if($isOnlinePayment)
                                    <!-- Thanh toán online (MoMo/ZaloPay) -->
                                    <div class="payment-details-card mt-2 p-3" style="background-color: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                                        <h6 class="mb-2 text-success"><i class="fas fa-check-circle me-2"></i>Chi tiết giao dịch thành công</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <small class="text-muted">Ví điện tử:</small><br>
                                                    <span class="badge bg-info fs-6">{{ $latestPayment->payment_method_type }}</span>
                                                </div>
                                                @if($latestPayment->transaction_code)
                                                    <div class="mb-1">
                                                        <small class="text-muted">Mã giao dịch:</small><br>
                                                        <strong class="text-primary">{{ $latestPayment->transaction_code }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if($order->transaction_id)
                                                    <div class="mb-1">
                                                        <small class="text-muted">Mã đơn hàng ví:</small><br>
                                                        <strong class="text-success">{{ $order->transaction_id }}</strong>
                                                    </div>
                                                @endif
                                                @if($latestPayment->confirmed_at)
                                                    <div class="mb-1">
                                                        <small class="text-muted">Thời gian thanh toán:</small><br>
                                                        <strong class="text-dark">{{ \Carbon\Carbon::parse($latestPayment->confirmed_at)->format('d/m/Y H:i:s') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="alert alert-success mt-2 mb-0">
                                            <small><i class="fas fa-info-circle me-1"></i>
                                            Giao dịch đã được xác nhận thành công. Đơn hàng sẽ được chuẩn bị và giao trong 1-2 ngày làm việc.</small>
                                        </div>
                                    </div>
                                @elseif($isCOD)
                                    <!-- Thanh toán COD -->
                                    <div class="payment-details-card mt-2 p-3" style="background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                                        <h6 class="mb-2 text-warning"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <small class="text-muted">Mã đơn hàng:</small><br>
                                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                                </div>
                                                <div class="mb-1">
                                                    <small class="text-muted">Số tiền cần thanh toán:</small><br>
                                                    <strong class="text-danger fs-5">{{ number_format($order->total_price ?? $order->total, 0, ',', '.') }}đ</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <small class="text-muted">Thời gian giao dự kiến:</small><br>
                                                    <strong class="text-success">{{ $order->created_at->addDays(2)->format('d/m/Y') }} - {{ $order->created_at->addDays(4)->format('d/m/Y') }}</strong>
                                                </div>
                                                <div class="mb-1">
                                                    <small class="text-muted">Trạng thái:</small><br>
                                                    <span class="badge bg-warning">Chờ xử lý</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-warning mt-2 mb-0">
                                            <small><i class="fas fa-info-circle me-1"></i>
                                            Vui lòng chuẩn bị đúng số tiền khi nhận hàng. Shipper sẽ liên hệ bạn trước khi giao hàng.</small>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Nút hành động -->
                                <div class="action-buttons mt-3 text-center">
                                    <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-primary btn-lg me-3 px-4">
                                        <i class="fas fa-eye me-2"></i>Chi tiết đơn hàng
                                    </a>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-home me-2"></i>Tiếp tục mua sắm
                                    </a>
                                </div>
                                
                                <!-- Hướng dẫn thêm -->
                                <div class="additional-info mt-4 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>
                                        Cần hỗ trợ? Liên hệ hotline: <strong class="text-primary">1900 123 456</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>@endsection
