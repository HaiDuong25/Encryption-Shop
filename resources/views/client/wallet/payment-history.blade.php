@extends('client.layout.main')

@section('title', 'Lịch sử thanh toán')

@push('style')
<link rel="stylesheet" href="{{ asset('assets-front/css/wallet-custom.css') }}">
<style>
.source-badge {
    font-size: 0.75rem;
    margin-left: 5px;
}
.transaction-row:hover {
    background-color: #f8f9fa;
}
.amount-positive {
    color: #28a745 !important;
    font-weight: 600;
}
.amount-negative {
    color: #dc3545 !important;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wallet.index') }}">Ví của tôi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lịch sử thanh toán tổng hợp</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Lịch sử thanh toán tổng hợp
            </h5>
            <div class="btn-group" role="group" aria-label="Navigation">
                <a href="{{ route('wallet.history') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-wallet me-1"></i>Chỉ ví
                </a>
                <a href="{{ route('wallet.payment-history') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-receipt me-1"></i>Tổng hợp
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Lịch sử tổng hợp</strong> hiển thị tất cả các giao dịch thanh toán của bạn, bao gồm:
                <ul class="mb-0 mt-2">
                    <li><span class="badge bg-primary">Wallet</span> Giao dịch ví (nạp tiền, thanh toán bằng ví, hoàn tiền)</li>
                    <li><span class="badge bg-success">Payment</span> Thanh toán trực tiếp (MoMo, ZaloPay, COD)</li>
                </ul>
            </div>

            @if($paginator->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Loại</th>
                                <th>Số tiền</th>
                                <th>Mô tả</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Nguồn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginator as $transaction)
                                <tr class="transaction-row">
                                    <td class="text-muted">
                                        {{ $transaction['created_at']->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        @if($transaction['type'] === 'deposit')
                                            <span class="badge bg-success">
                                                <i class="fas fa-plus me-1"></i>Nạp tiền
                                            </span>
                                        @elseif($transaction['type'] === 'refund')
                                            <span class="badge bg-info text-dark">
                                                <i class="fas fa-undo me-1"></i>Hoàn tiền
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-minus me-1"></i>Thanh toán
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($transaction['type'], ['deposit','refund']))
                                            <span class="amount-positive">
                                                +{{ number_format($transaction['amount'], 0, ',', '.') }} VND
                                            </span>
                                        @else
                                            <span class="amount-negative">
                                                -{{ number_format($transaction['amount'], 0, ',', '.') }} VND
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transaction['description'] }}
                                        @if($transaction['order_id'])
                                            <a href="{{ route('client.orders.show', $transaction['order_id']) }}" class="text-primary ms-2" title="Xem đơn hàng">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction['payment_method_type'])
                                            @switch($transaction['payment_method_type'])
                                                @case('WALLET')
                                                @case('Số dư ví')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-wallet me-1"></i>Số dư ví
                                                    </span>
                                                    @break
                                                @case('MOMO')
                                                @case('Ví Điện Tử MOMO')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-mobile-alt me-1"></i>MoMo
                                                    </span>
                                                    @break
                                                @case('ZALOPAY')
                                                @case('Ví Điện Tử ZALOPAY')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-credit-card me-1"></i>ZaloPay
                                                    </span>
                                                    @break
                                                @case('COD')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-money-bill me-1"></i>COD
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $transaction['payment_method_type'] }}</span>
                                            @endswitch
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($transaction['status'])
                                            @case('completed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Hoàn thành
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Đang xử lý
                                                </span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Thất bại
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($transaction['source_type'] === 'wallet')
                                            <span class="badge bg-primary source-badge">
                                                <i class="fas fa-wallet me-1"></i>Wallet
                                            </span>
                                        @else
                                            <span class="badge bg-success source-badge">
                                                <i class="fas fa-credit-card me-1"></i>Payment
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $paginator->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Chưa có giao dịch nào</h5>
                    <p class="text-muted">Bạn chưa thực hiện giao dịch thanh toán nào.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
