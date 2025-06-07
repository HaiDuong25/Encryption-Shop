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
            <strong>Ngày xác nhận:</strong> {{ $payment->confirmed_at ? $payment->confirmed_at->format('d/m/Y H:i') : '-' }}
        </div>
        <div class="mb-3">
            <strong>Trạng thái:</strong> {{ ucfirst($payment->status) }}
        </div>
        <table class="table table-bordered">
            <tr>
                <th>Đơn hàng</th>
                <td>{{ $payment->order->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td>{{ number_format($payment->order->total_price ?? 0, 0, ',', '.') }} VND</td>
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