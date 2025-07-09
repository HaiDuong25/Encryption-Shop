@extends('admin.layouts.main')

@section('title', 'Quản lý Thanh toán')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Thanh toán</h3>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tất cả Thanh toán</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <table class="table table-bordered table-hover table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Đơn hàng</th>
                        <th>Số tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái</th>
                        <th>Ngày thanh toán</th>
                        <th>Hành động</th>
                        <th>Hóa đơn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->order->recipient_name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $orderDetails = $payment->order->orderDetails ?? collect();
                                    $thanhTien = $orderDetails->sum(fn($d) => $d->price * $d->quantity);
                                @endphp
                                <span class="text-success fw-bold">{{ number_format($thanhTien, 0, ',', '.') }} đ</span>
                            </td>
                            <td>{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</td>
                            <td>
                                @if($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                @elseif($payment->status === 'confirmed')
                                    <span class="badge bg-success">Đã xác nhận</span>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->confirmed_at)
                                    {{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') }}
                                @elseif($payment->rejected_at)
                                    {{ \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($payment->status === 'pending')
                                    <div class="d-flex gap-1 justify-content-center">
                                        <form action="{{ route('payments.confirm', $payment->id) }}" method="POST" onsubmit="return confirm('Xác nhận thanh toán cho đơn này?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('payments.reject', $payment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($payment->status === 'confirmed')
                                    <small class="text-success">Lúc {{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') }}</small>
                                @elseif($payment->status === 'rejected')
                                    <small class="text-danger">Lúc {{ \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                @if(in_array($payment->status, ['confirmed', 'rejected']))
                                    <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
