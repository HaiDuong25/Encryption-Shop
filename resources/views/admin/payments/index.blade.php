@extends('admin.layouts.main')

@section('title', 'Quản lý Thanh Toán')

@section('content')
    @php use Carbon\Carbon; @endphp

<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                <h5>Quản lý Thanh Toán</h5>
                <div class="right-options d-flex gap-2 align-items-center">
                    {{-- Form tìm kiếm --}}
                    <form method="GET" action="{{ route('payments.index') }}" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" 
                               placeholder="Tìm theo tên người nhận hoặc ID đơn hàng..." style="width: 280px;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        @if(request('search'))
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive table-product mt-3">
                <table class="table theme-table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Đơn hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Dữ liệu giao dịch</th>
                            <th>Trạng thái</th>
                            <th>Ngày thanh toán</th>
                            <th>Hành động</th>
                            <th>Xem hóa đơn</th>
                        </tr>
                    </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td class="fw-bold">{{ $payment->id }}</td>
                        <td>
                            <span class="badge text-dark" style="font-size: 1rem;">Đơn hàng {{ $payment->order->id ?? 'N/A' }}</span><br>
                            <small class="text-muted">{{ $payment->order->recipient_name ?? '' }}</small>
                        </td>
                        <td class="text-end">{{ format_vnd($payment->order->total_price ?? 0) }} <span class="text-secondary">VND</span></td>
                        <td>
                            <span class="badge bg-light text-dark border border-1 border-secondary">{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</span>
                        </td>
                        <td>
                            @if($payment->payment_method_type && in_array($payment->payment_method_type, ['MoMo', 'ZaloPay']))
                                <div class="text-center">
                                    <span class="badge bg-info text-white mb-1">{{ $payment->payment_method_type }}</span><br>
                                    @if($payment->transaction_code)
                                        <small class="text-dark fw-bold">Mã GD: {{ $payment->transaction_code }}</small><br>
                                    @endif
                                    @if($payment->order && $payment->order->transaction_id)
                                        <small class="text-muted">Mã ĐH: {{ $payment->order->transaction_id }}</small><br>
                                    @endif
                                    @if($payment->confirmed_at)
                                        <small class="text-success">{{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i') }}</small>
                                    @endif
                                </div>
                            @elseif($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD')
                                <div class="text-center">
                                    <span class="badge bg-success text-white mb-1">COD</span><br>
                                    <small class="text-muted">Thanh toán khi nhận hàng</small>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $statusText = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'completed' => 'Đã thanh toán',
    'rejected' => 'Đã hủy',
    'refunded' => 'Đã hoàn tiền'
];

$statusColor = [
    'pending' => 'warning',
    'confirmed' => 'info',
    'completed' => 'success',
    'rejected' => 'danger',
    'refunded' => 'info'
];
                            @endphp
                            <span class="badge bg-{{ $statusColor[$payment->status] ?? 'secondary' }}" style="font-size: 1rem;">
                                {{ $statusText[$payment->status] ?? ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            @if($payment->confirmed_at)
                                <span class="text-success"><i class="fa-solid fa-check-circle me-1"></i>{{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') }}</span>
                            @elseif($payment->rejected_at)
                                <span class="text-danger"><i class="fa-solid fa-times-circle me-1"></i>{{ \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD')
                                {{-- Flow COD: pending → confirmed → completed --}}
                                @if($payment->status === 'pending')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="{{ route('payments.confirm', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('Xác nhận đơn hàng COD này?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-check me-1"></i> Xác nhận đơn
                                            </button>
                                        </form>
                                        <form action="{{ route('payments.reject', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                    </div>
                                @elseif($payment->status === 'confirmed')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="{{ route('payments.complete', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('Hoàn thành đơn hàng COD này? (Khách đã thanh toán)');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-check-double me-1"></i> Hoàn thành
                                            </button>
                                        </form>
                                        <span class="badge bg-info text-white" style="font-size: 0.85rem;">
                                            Đã xác nhận lúc
                                            {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i') : '' }}
                                        </span>
                                    </div>
                                @elseif($payment->status === 'completed')
                                    <span class="badge bg-success text-white">
                                        Đã hoàn thành lúc
                                        {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger text-white">
                                        Đã hủy lúc
                                        {{ $payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @endif
                            @else
                                {{-- Flow Online: pending → completed (giữ nguyên) --}}
                                @if($payment->status === 'pending')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="{{ route('payments.confirm', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('Xác nhận thanh toán cho đơn này?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs px-2 py-1"
                                                style="font-size: 0.85rem; background-color: #28a745; border-color: #28a745;">
                                                <i class="fa-solid fa-check me-1"></i> Xác nhận
                                            </button>
                                        </form>
                                        <form action="{{ route('payments.reject', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs px-2 py-1"
                                                style="font-size: 0.85rem; background-color: #dc3545; border-color: #dc3545;">
                                                <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                    </div>
                                @elseif($payment->status === 'completed')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <span class="badge bg-success text-white" style="background-color: #28a745;">
                                            Đã xác nhận lúc
                                            {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '' }}
                                        </span>
                                    </div>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger text-white" style="background-color: #dc3545;">
                                        Đã hủy lúc
                                        {{ $payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                        @if(in_array($payment->status, ['completed']))
                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                    <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-primary btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                    </a>
                                    <a href="{{ route('admin.payments.download-invoice', $payment->id) }}" class="btn btn-success btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-download me-1"></i> Tải PDF
                                    </a>
                                </div>
                            @elseif($payment->status === 'rejected')
                                <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-primary btn-xs px-2 py-1"
                                    style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                </a>
                            @else
                                <span class="text-muted">Chưa có hóa đơn</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $payments->links() }}
    </div>
@endsection
