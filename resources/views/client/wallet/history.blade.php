@extends('client.layout.main')

@section('title', 'Lịch sử giao dịch')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets-front/css/wallet-custom.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets-front/js/wallet-fix.js') }}"></script>
@endpush

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('wallet.index') }}">Ví của tôi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lịch sử giao dịch</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử giao dịch ví</h5>
                <div class="btn-group" role="group" aria-label="Navigation">
                    <a href="{{ route('wallet.history') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-wallet me-1"></i>Chỉ ví
                    </a>
                    <a href="{{ route('wallet.payment-history') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-receipt me-1"></i>Tổng hợp
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lịch sử ví</strong> chỉ hiển thị các giao dịch liên quan đến ví điện tử (nạp tiền, rút tiền,
                    thanh toán bằng ví, hoàn tiền).
                    <a href="{{ route('wallet.payment-history') }}" class="alert-link">Xem tất cả giao dịch thanh toán</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Filter Form -->
                <form method="GET" action="{{ route('wallet.history') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Loại giao dịch</label>
                            <select name="type" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Nạp tiền
                                </option>
                                <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>Rút tiền
                                </option>
                                <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Thanh toán
                                </option>
                                <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Hoàn tiền
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn
                                    thành</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Đang xử lý
                                </option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-1 btn-sm">
                                <i class="fas fa-filter me-1"></i>Lọc
                            </button>
                            <a href="{{ route('wallet.history') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>

                @if ($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã giao dịch</th>
                                    <th>Thời gian</th>
                                    <th>Loại</th>
                                    <th>Số tiền</th>
                                    <th>Số dư sau GD</th>
                                    <th>Mô tả</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <code>{{ $transaction->transaction_code }}</code>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            @if ($transaction->type === 'deposit')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-plus me-1"></i>Nạp tiền
                                                </span>
                                            @elseif($transaction->type === 'withdraw')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-arrow-up me-1"></i>Rút tiền
                                                </span>
                                            @elseif($transaction->type === 'refund')
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
                                            @if (in_array($transaction->type, ['deposit', 'refund']))
                                                <span class="text-success fw-bold">
                                                    +{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                                </span>
                                            @else
                                                <span class="text-danger fw-bold">
                                                    -{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ number_format($transaction->balance_after, 0, ',', '.') }}
                                            VND</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>
                                            @if (in_array($transaction->type, ['withdraw', 'refund', 'payment']))
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-wallet me-1"></i>Số dư ví
                                                </span>
                                            @elseif($transaction->type === 'deposit' && $transaction->payment_method_type)
                                                @switch($transaction->payment_method_type)
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
                                                        <span
                                                            class="badge bg-secondary">{{ $transaction->payment_method_type }}</span>
                                                @endswitch
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            </td>


                                            <td>
                                                @switch($transaction->status)
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }}
                            trong tổng số {{ $transactions->total() }} giao dịch
                        </div>
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có giao dịch nào</h5>
                        <p class="text-muted">Chưa có giao dịch nào phù hợp với bộ lọc của bạn</p>
                        <div class="mt-3">
                            <a href="{{ route('wallet.topup') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-2"></i>Nạp tiền ngay
                            </a>
                            <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại ví
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background: #fff;
            border-bottom: 2px solid #f8f9fa;
            border-radius: 10px 10px 0 0 !important;
        }

        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85em;
        }

        .badge {
            font-size: 0.75em;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, .02);
        }
    </style>
@endsection
