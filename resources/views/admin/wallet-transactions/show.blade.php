@extends('admin.layouts.main')

@section('title', 'Chi tiết giao dịch ví')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin-wallet-fix.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header with gradient background -->
    <div class="transaction-info-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="fas fa-receipt me-3"></i>Chi tiết giao dịch ví
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="background: transparent;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.wallet-transactions.index') }}" class="text-white-50">Giao dịch ví</a></li>
                        <li class="breadcrumb-item active text-white">Chi tiết</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-light btn-lg shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
        
        <!-- Transaction Overview -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="text-center">
                    <h6 class="text-white mb-2">Mã giao dịch</h6>
                    <code class="bg-white bg-opacity-90 text-dark p-2 rounded d-inline-block small">{{ $transaction->transaction_code }}</code>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h6 class="text-white mb-2">Trạng thái</h6>
                    @switch($transaction->status)
                        @case('completed')
                            <span class="badge bg-success bg-opacity-90 px-3 py-2">
                                <i class="fas fa-check me-1"></i>Hoàn thành
                            </span>
                            @break
                        @case('pending')
                            <span class="badge bg-warning bg-opacity-90 text-dark px-3 py-2">
                                <i class="fas fa-clock me-1"></i>Chờ xử lý
                            </span>
                            @break
                        @case('failed')
                            <span class="badge bg-danger bg-opacity-90 px-3 py-2">
                                <i class="fas fa-times me-1"></i>Thất bại
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h6 class="text-white mb-2">Loại giao dịch</h6>
                    @if($transaction->type === 'deposit')
                        <span class="badge bg-light text-success px-3 py-2">
                            <i class="fas fa-plus me-1"></i>Nạp tiền
                        </span>
                    @elseif($transaction->type === 'refund')
                        <span class="badge bg-light text-info px-3 py-2 text-dark">
                            <i class="fas fa-undo me-1"></i>Hoàn tiền
                        </span>
                    @else
                        <span class="badge bg-light text-warning px-3 py-2">
                            <i class="fas fa-minus me-1"></i>Thanh toán
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h6 class="text-white mb-2">Số tiền</h6>
                    @if($transaction->type === 'deposit' || $transaction->type === 'refund')
                        <div class="text-white fw-bold fs-5">
                            +{{ number_format($transaction->amount, 0, ',', '.') }} VND
                        </div>
                    @else
                        <div class="text-white fw-bold fs-5">
                            -{{ number_format($transaction->amount, 0, ',', '.') }} VND
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Transaction Details -->
        <div class="col-lg-8">
            <!-- Detailed Information -->
            <div class="card data-table">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Thông tin chi tiết
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-bold" style="width: 200px;">
                                    <i class="fas fa-hashtag me-2 text-primary"></i>Mã giao dịch
                                </td>
                                <td><code>{{ $transaction->transaction_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-exchange-alt me-2 text-info"></i>Loại giao dịch
                                </td>
                                <td>
                                    @if($transaction->type === 'deposit')
                                        <span class="badge bg-success">
                                            <i class="fas fa-plus me-1"></i>Nạp tiền vào ví
                                        </span>
                                    @elseif($transaction->type === 'refund')
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-undo me-1"></i>Hoàn tiền về ví
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-minus me-1"></i>Thanh toán đơn hàng
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>Số tiền giao dịch
                                </td>
                                <td>
                                    @if($transaction->type === 'deposit' || $transaction->type === 'refund')
                                        <span class="amount-positive fw-bold fs-5">
                                            +{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                        </span>
                                    @else
                                        <span class="amount-negative fw-bold fs-5">
                                            -{{ number_format($transaction->amount, 0, ',', '.') }} VND
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-wallet me-2 text-secondary"></i>Số dư trước GD
                                </td>
                                <td class="fw-bold">{{ number_format($transaction->balance_before, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-wallet me-2 text-primary"></i>Số dư sau GD
                                </td>
                                <td class="fw-bold text-primary">{{ number_format($transaction->balance_after, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-flag me-2 text-warning"></i>Trạng thái
                                </td>
                                <td>
                                    @switch($transaction->status)
                                        @case('completed')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>Hoàn thành
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-clock me-1"></i>Đang chờ xử lý
                                            </span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times me-1"></i>Giao dịch thất bại
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-credit-card me-2 text-info"></i>Phương thức thanh toán
                                </td>
                                <td>
                                    @if($transaction->payment_method_type)
                                        @switch($transaction->payment_method_type)
                                            @case('momo')
                                                <span class="badge bg-pink">
                                                    <i class="fas fa-mobile-alt me-1"></i>Ví MoMo
                                                </span>
                                                @break
                                            @case('zalopay')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-mobile-alt me-1"></i>ZaloPay
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-info">{{ $transaction->payment_method_type }}</span>
                                        @endswitch
                                    @else
                                        <span class="text-muted">Không xác định</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-clock me-2 text-secondary"></i>Thời gian tạo
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-edit me-2 text-secondary"></i>Cập nhật cuối
                                </td>
                                <td>{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-comment me-2 text-secondary"></i>Mô tả
                                </td>
                                <td>{{ $transaction->description ?? 'Không có mô tả' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Data -->
            @if($transaction->payment_data)
                <div class="payment-data-section">
                    <h5 class="mb-3">
                        <i class="fas fa-code me-2"></i>Dữ liệu thanh toán từ cổng thanh toán
                    </h5>
                    <pre class="language-json"><code>{{ json_encode($transaction->payment_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            @endif
        </div>

        <!-- Right Column - User Info & Actions -->
        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card user-info-card">
                <div class="card-body">
                    <div class="mb-4">
                        <img src="{{ $transaction->user->avatar ? asset('storage/' . $transaction->user->avatar) : asset('assets/images/users/default.png') }}" 
                             class="rounded-circle mb-3 shadow" width="100" height="100" alt="User Avatar">
                        
                        <h4 class="card-title text-primary">{{ $transaction->user->name }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope me-2"></i>{{ $transaction->user->email }}
                        </p>
                        
                        @if($transaction->user->phone)
                            <p class="text-muted mb-2">
                                <i class="fas fa-phone me-2"></i>{{ $transaction->user->phone }}
                            </p>
                        @endif

                        <p class="text-muted">
                            <i class="fas fa-calendar me-2"></i>Tham gia: {{ $transaction->user->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    @php
                        $userWallet = $transaction->user->wallet;
                    @endphp
                    @if($userWallet)
                        <div class="bg-light p-3 rounded mb-3">
                            <h6 class="text-secondary mb-2">
                                <i class="fas fa-wallet me-2"></i>Số dư ví hiện tại
                            </h6>
                            <h3 class="text-success mb-0">{{ number_format($userWallet->balance, 0, ',', '.') }} VND</h3>
                        </div>
                    @endif

                    <div class="action-buttons">
                        <a href="{{ route('users.show', $transaction->user->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-user me-2"></i>Xem hồ sơ người dùng
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Actions -->
            @if($transaction->status !== 'completed')
                <div class="card mt-3">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>Thao tác quản lý
                        </h5>
                    </div>
                    <div class="card-body action-buttons">
                        @if($transaction->status === 'pending')
                            <form action="{{ route('admin.wallet-transactions.update-status', $transaction->id) }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Bạn có chắc muốn đánh dấu giao dịch này là hoàn thành?')">
                                    <i class="fas fa-check me-2"></i>Xác nhận hoàn thành
                                </button>
                            </form>

                            <form action="{{ route('admin.wallet-transactions.update-status', $transaction->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Bạn có chắc muốn đánh dấu giao dịch này là thất bại?')">
                                    <i class="fas fa-times me-2"></i>Đánh dấu thất bại
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Delete Transaction (only for failed payment transactions) -->
            @if($transaction->status === 'failed' && $transaction->type === 'payment')
                <div class="card mt-3 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-trash me-2"></i>Xóa giao dịch thanh toán
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Giao dịch thanh toán thất bại có thể được xóa khỏi hệ thống. 
                            Hành động này không thể hoàn tác.
                        </p>
                        <form action="{{ route('admin.wallet-transactions.destroy', $transaction->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('BẠN CÓ CHẮC MUỐN XÓA GIAO DỊCH THANH TOÁN NÀY? Hành động này không thể hoàn tác và sẽ xóa vĩnh viễn dữ liệu.')">
                                <i class="fas fa-trash me-2"></i>Xóa vĩnh viễn
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($transaction->status === 'failed' && $transaction->type === 'deposit')
                <div class="card mt-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Giao dịch nạp tiền thất bại
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-ban me-2"></i>
                            Giao dịch nạp tiền thất bại không thể được xóa khỏi hệ thống để đảm bảo tính minh bạch và truy xuất nguồn gốc.
                        </p>
                        <button type="button" class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-ban me-2"></i>Không thể xóa giao dịch nạp tiền
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/admin-wallet-fix.js') }}"></script>
@endsection
