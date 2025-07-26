@extends('admin.layouts.main')

@section('content')
    @php use Carbon\Carbon; @endphp


    <h1>Quản lý Thanh Toán</h1>

    <form method="GET" action="" class="mb-3 d-flex align-items-center" style="gap: 8px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="max-width: 220px;" placeholder="Tìm theo tên người nhận hoặc ID đơn hàng...">
        <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('payments.index') }}" class="btn btn-link btn-sm">Xóa lọc</a>
        @endif
    </form>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <style>
        th,
        td {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center" style="background: #fff; border-radius: 8px; overflow: hidden;">
            <thead class="table-light">
                <tr>
                    <th style="min-width: 60px;">ID</th>
                    <th style="min-width: 120px;">Đơn hàng</th>
                    <th style="min-width: 120px;">Số tiền</th>
                    <th style="min-width: 120px;">Phương thức</th>
                    <th style="min-width: 120px;">Trạng thái</th>
                    <th style="min-width: 150px;">Ngày thanh toán</th>
                    <th style="min-width: 120px;">Hành động</th>
                    <th style="min-width: 120px;">Xem hóa đơn</th>
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
                        <td class="text-end">{{ number_format($payment->order->total_price ?? 0, 0, ',', '.') }} <span class="text-secondary">VND</span></td>
                        <td>
                            <span class="badge bg-light text-dark border border-1 border-secondary">{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</span>
                        </td>
                        <td>
                            @php
                            $statusText = [
    'pending' => 'Chờ xác nhận',
    'completed' => 'Đã thanh toán',
    'rejected' => 'Đã hủy',
    'refunded' => 'Đã hoàn tiền'
];

$statusColor = [
    'pending' => 'warning',
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
                        </td>
                        <td>
                        @if(in_array($payment->status, ['completed', 'rejected']))

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
                                <span class="text-muted">---</span>
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
