@extends('admin.layouts.main')

@section('title', 'Thông tin người dùng')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Thông tin người dùng</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Người dùng</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Basic Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/users/default.png') }}" 
                         class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                    
                    <h4 class="card-title text-primary">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        @switch($user->status)
                            @case('active')
                                <span class="badge bg-success">Hoạt động</span>
                                @break
                            @case('inactive')
                                <span class="badge bg-danger">Không hoạt động</span>
                                @break
                            @default
                                <span class="badge bg-warning">{{ $user->status }}</span>
                        @endswitch
                        
                        @switch($user->role)
                            @case('admin')
                                <span class="badge bg-primary">Quản trị viên</span>
                                @break
                            @case('staff')
                                <span class="badge bg-info">Nhân viên</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Khách hàng</span>
                        @endswitch
                    </div>

                    @if($user->phone)
                        <p><i class="fas fa-phone me-2"></i>{{ $user->phone }}</p>
                    @endif

                    @if($user->address)
                        <p><i class="fas fa-map-marker-alt me-2"></i>{{ $user->address }}</p>
                    @endif

                    <p class="text-muted">
                        <i class="fas fa-calendar me-2"></i>Tham gia: {{ $user->created_at->format('d/m/Y') }}
                    </p>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm flex-fill">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details & Wallet Info -->
        <div class="col-lg-8">
            <!-- Wallet Information -->
            @if($user->wallet)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-wallet me-2"></i>Thông tin ví
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Số dư hiện tại:</h6>
                                <h3 class="text-success">{{ number_format($user->wallet->balance, 0, ',', '.') }} VND</h3>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ngày tạo ví:</strong> {{ $user->wallet->created_at->format('d/m/Y H:i:s') }}</p>
                                <p><strong>Cập nhật cuối:</strong> {{ $user->wallet->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Wallet Transactions -->
            @if($user->walletTransactions && $user->walletTransactions->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt me-2"></i>Giao dịch ví gần đây
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Mã GD</th>
                                        <th>Loại</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->walletTransactions as $transaction)
                                        <tr>
                                            <td><code>{{ $transaction->transaction_code }}</code></td>
                                            <td>
                                                @if($transaction->type === 'deposit')
                                                    <span class="badge bg-success">Nạp tiền</span>
                                                @elseif($transaction->type === 'refund')
                                                    <span class="badge bg-info text-dark">Hoàn tiền</span>
                                                @else
                                                    <span class="badge bg-warning">Thanh toán</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->type === 'deposit' || $transaction->type === 'refund')
                                                    <span class="text-success">+{{ number_format($transaction->amount, 0, ',', '.') }} VND</span>
                                                @else
                                                    <span class="text-danger">-{{ number_format($transaction->amount, 0, ',', '.') }} VND</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($transaction->status)
                                                    @case('completed')
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">Chờ xử lý</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">Thất bại</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.wallet-transactions.show', $transaction->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.wallet-transactions.index', ['user_id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i>Xem tất cả giao dịch
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <h5>Chưa có giao dịch ví</h5>
                        <p class="text-muted">Người dùng này chưa có giao dịch ví nào.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
