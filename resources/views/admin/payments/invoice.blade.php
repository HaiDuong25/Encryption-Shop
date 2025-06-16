{{-- filepath: resources/views/admin/payments/invoice.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Hóa đơn thanh toán')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Hóa đơn thanh toán</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Mã thanh toán:</strong> {{ $payment->id }}
            </div>
            <div class="mb-3">
                <strong>Ngày xác nhận:</strong>
                {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="mb-3">
                <strong>Trạng thái:</strong>
                @if ($payment->status === 'confirmed')
                    <span class="badge bg-success text-white" style="background-color: #28a745;">Đã xác nhận đơn hàng</span>
                @elseif($payment->status === 'rejected')
                    <span class="badge bg-danger text-white" style="background-color: #dc3545;">Đã hủy đơn hàng</span>
                @else
                    <span class="badge bg-secondary">Pending</span>
                @endif
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>Đơn hàng</th>
                    <td>{{ $payment->order->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Số tiền</th>
                    <td>
                        @php
                            $orderDetails = $payment->order->orderDetails ?? collect();
                            $thanhTien = $orderDetails->sum(fn($d) => $d->price * $d->quantity);
                        @endphp
                        <span class="text-success fw-bold">{{ number_format($thanhTien, 0, ',', '.') }} đ</span>
                    </td>
                </tr>
                <tr>
                    <th>Phương thức</th>
                    <td>{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</td>
                </tr>
            </table>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách thanh toán</a>
        </div>
    </div>
@endsection
