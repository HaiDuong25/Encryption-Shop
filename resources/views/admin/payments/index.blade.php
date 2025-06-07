@extends('admin.layouts.main')

@section('content')
    @php use Carbon\Carbon; @endphp

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
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('payments.confirm', $payment->id) }}" method="POST" onsubmit="return confirm('Xác nhận thanh toán cho đơn này?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" style="background-color: #28a745; border-color: #28a745;">Xác nhận</button>
                            </form>
                            <form action="{{ route('payments.reject', $payment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border-color: #dc3545;">Hủy đơn</button>
                            </form>
                        </div>
                    @elseif($payment->status === 'confirmed')
                        <span class="badge bg-success text-white" style="background-color: #28a745;">
                            Đã xác nhận lúc
                            {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '' }}
                        </span>
                    @elseif($payment->status === 'rejected')
                        <span class="badge bg-danger text-white" style="background-color: #dc3545;">
                            Đã hủy lúc 
                            {{ $payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : '' }}
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $payments->links() }}
@endsection
