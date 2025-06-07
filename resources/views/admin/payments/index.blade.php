@extends('admin.layouts.main')

@section('content')
    <h1>Quản lý Thanh Toán</h1>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Đơn hàng</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->order->name ?? 'N/A' }}</td>
<td>{{ number_format($payment->order->total_price ?? 0, 0, ',', '.') }} VND</td>
                <td>{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
                <td>{{ $payment->confirmed_at ? $payment->confirmed_at->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    @if($payment->status === 'pending')
                    <form action="{{ route('payments.confirm', $payment->id) }}" method="POST" onsubmit="return confirm('Xác nhận thanh toán cho đơn này?');">
                        @csrf
                        <button type="submit">Xác nhận</button>
                    </form>
                    @else
                        Đã xác nhận
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $payments->links() }}
@endsection
