@extends('admin.layouts.main')

@section('title', 'Quản lý giao dịch ví')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Quản lý giao dịch ví</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Giao dịch ví</li>
                    </ol>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Tổng nạp tiền</h6>
                                <h4 class="mb-0">{{ number_format($stats['total_deposits'], 0, ',', '.') }} VND</h4>
                            </div>
                            <div class="icon-lg">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Tổng thanh toán</h6>
                                <h4 class="mb-0">{{ number_format($stats['total_payments'], 0, ',', '.') }} VND</h4>
                            </div>
                            <div class="icon-lg">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Số tiền đã rút</h6>
                                <h4 class="mb-0">{{ number_format($stats['withdraw_completed_amount'], 0, ',', '.') }} VND
                                </h4>
                            </div>
                            <div class="icon-lg">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Hôm nay</h6>
                                <h4 class="mb-0">{{ $stats['total_transactions_today'] }}</h4>
                            </div>
                            <div class="icon-lg">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.wallet-transactions.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Loại giao dịch</label>
                            <select name="type" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Nạp tiền
                                </option>
                                <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Thanh toán
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn
                                    thành</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý
                                </option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Phương thức</label>
                            <select name="payment_method_type" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="MOMO" {{ request('payment_method_type') === 'MOMO' ? 'selected' : '' }}>
                                    MoMo</option>
                                <option value="ZALOPAY"
                                    {{ request('payment_method_type') === 'ZALOPAY' ? 'selected' : '' }}>ZaloPay</option>
                                <option value="WALLET" {{ request('payment_method_type') === 'WALLET' ? 'selected' : '' }}>
                                    Ví</option>
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
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-filter me-1"></i>Lọc
                                </button>
                                <a href="{{ route('admin.wallet-transactions.index') }}"
                                    class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm theo tên, email, mã giao dịch..." value="{{ request('search') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Danh sách giao dịch
                    <span class="badge bg-secondary ms-2">{{ $transactions->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if ($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã giao dịch</th>
                                    <th>Người dùng</th>
                                    <th>Loại</th>
                                    <th>Số tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Phương thức</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <code>{{ $transaction->transaction_code }}</code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $transaction->user->avatar ? asset('storage/' . $transaction->user->avatar) : asset('assets/images/users/default.png') }}"
                                                    class="rounded-circle me-2" width="32" height="32"
                                                    alt="Avatar">
                                                <div>
                                                    <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                    <small class="text-muted">{{ $transaction->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($transaction->type === 'deposit')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-plus me-1"></i>Nạp tiền
                                                </span>
                                            @elseif($transaction->type === 'refund')
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-undo me-1"></i>Hoàn tiền
                                                </span>
                                            @elseif($transaction->type === 'withdraw')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-arrow-up me-1"></i>Rút tiền
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-minus me-1"></i>Thanh toán
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($transaction->type === 'deposit' || $transaction->type === 'refund')
                                                <span class="text-success fw-bold">
                                                    +{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                                </span>
                                            @else
                                                <span class="text-danger fw-bold">
                                                    -{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                                </span>
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
                                                        <i class="fas fa-clock me-1"></i>Chờ xử lý
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
                                            @if (strtolower($transaction->payment_method_type) === 'wallet')
                                                <span class="badge bg-primary"><i class="fas fa-wallet me-1"></i>Ví</span>
                                            @elseif(strtolower($transaction->payment_method_type) === 'momo')
                                                <span class="badge bg-pink"><i class="fab fa-mdb me-1"></i>MoMo</span>
                                            @elseif(strtolower($transaction->payment_method_type) === 'zalopay')
                                                <span class="badge bg-info text-dark"><i
                                                        class="fas fa-bolt me-1"></i>ZaloPay</span>
                                            @elseif($transaction->payment_method_type)
                                                <span
                                                    class="badge bg-secondary">{{ $transaction->payment_method_type }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog me-1"></i>Thao tác
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.wallet-transactions.show', $transaction->id) }}">
                                                            <i class="fas fa-eye me-2"></i>Chi tiết
                                                        </a>
                                                    </li>
                                                    @if ($transaction->status === 'pending')
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.wallet-transactions.update-status', $transaction->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" class="dropdown-item text-success"
                                                                    onclick="return confirm('Xác nhận hoàn thành giao dịch?')">
                                                                    <i class="fas fa-check me-2"></i>Đánh dấu hoàn thành
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.wallet-transactions.update-status', $transaction->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="failed">
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Xác nhận đánh dấu thất bại?')">
                                                                    <i class="fas fa-times me-2"></i>Đánh dấu thất bại
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($transaction->status === 'failed')
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if ($transaction->type === 'payment')
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.wallet-transactions.destroy', $transaction->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"
                                                                        onclick="return confirm('Bạn có chắc muốn xóa giao dịch thanh toán thất bại này?')">
                                                                        <i class="fas fa-trash me-2"></i>Xóa
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <span class="dropdown-item text-muted">
                                                                    <i class="fas fa-ban me-2"></i>Không thể xóa đơn nạp
                                                                    tiền
                                                                </span>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
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
                        <p class="text-muted">Chưa có giao dịch ví nào được thực hiện.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .icon-lg {
            font-size: 2rem;
            opacity: 0.8;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
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
    </style>
@endsection
