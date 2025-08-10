@extends('client.layout.main')

@section('title', 'Nạp tiền thành công')

@push('style')
<link rel="stylesheet" href="{{ asset('assets-front/css/wallet-custom.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets-front/js/wallet-fix.js') }}"></script>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h4 class="mb-0">Nạp tiền thành công!</h4>
                </div>
                <div class="card-body text-center">
                    @if(isset($transaction))
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-muted">Mã giao dịch:</div>
                                    <div class="fw-bold">{{ $transaction->transaction_code }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Số tiền:</div>
                                    <div class="fw-bold text-success">
                                        +{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-muted">Thời gian:</div>
                                    <div class="fw-bold">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Phương thức:</div>
                                    <div class="fw-bold">{{ $transaction->payment_method_type }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Số dư trong ví của bạn đã được cập nhật. Bạn có thể sử dụng số dư này để thanh toán các đơn hàng.
                    </p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('wallet.index') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-wallet me-2"></i>Xem ví của tôi
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
