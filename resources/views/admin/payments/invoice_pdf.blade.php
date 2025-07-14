<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn thanh toán #{{ $payment->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 24px; }
        .header h2 { color: #007bff; margin-bottom: 0; }
        .info-table, .order-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .info-table th, .info-table td, .order-table th, .order-table td { border: 1px solid #ccc; padding: 8px; }
        .info-table th { width: 30%; background: #f5f5f5; text-align: left; }
        .order-table th { background: #f5f5f5; }
        .total-row td { font-weight: bold; font-size: 1.1em; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>HÓA ĐƠN THANH TOÁN</h2>
        <div>Mã thanh toán: <strong>#{{ $payment->id }}</strong></div>
        <div>Đơn hàng: <strong>#{{ $payment->order->id ?? 'N/A' }}</strong></div>
        <div>Ngày xác nhận: <strong>{{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '-' }}</strong></div>
        <div>Trạng thái:
            @if ($payment->status === 'confirmed')
                Đã xác nhận
            @elseif($payment->status === 'rejected')
                Đã hủy
            @else
                Chờ xác nhận
            @endif
        </div>
    </div>
    <table class="info-table">
        <tr>
            <th>Người nhận</th>
            <td>{{ $payment->order->recipient_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Địa chỉ giao hàng</th>
            <td>{{ $payment->order->recipient_address ?? '-' }}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{{ $payment->order->recipient_phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Phương thức thanh toán</th>
            <td>{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</td>
        </tr>
        <tr>
            <th>Mã giảm giá</th>
            <td>
                @if($payment->order->coupon_code)
                    <span class="text-success fw-bold">{{ $payment->order->coupon_code }}</span>
                    @if($payment->order->coupon_discount > 0)
                        (Giảm {{ number_format($payment->order->coupon_discount, 0, ',', '.') }} đ)
                    @endif
                @else
                    -
                @endif
            </td>
        </tr>
    </table>
    <h4>Chi tiết đơn hàng</h4>
    <table class="order-table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $orderDetails = $payment->order->orderDetails ?? collect(); @endphp
            @foreach($orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">Tạm tính:</td>
                <td class="text-right">{{ number_format($orderDetails->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }} đ</td>
            </tr>
            @if($payment->order->coupon_discount > 0)
            <tr>
                <td colspan="3" class="text-right">Giảm giá:</td>
                <td class="text-right text-danger">-{{ number_format($payment->order->coupon_discount, 0, ',', '.') }} đ</td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" class="text-right">Tổng cộng:</td>
                <td class="text-right">{{ number_format(($orderDetails->sum(fn($d) => $d->price * $d->quantity)) - ($payment->order->coupon_discount ?? 0), 0, ',', '.') }} đ</td>
            </tr>
        </tfoot>
    </table>
    <div class="mb-2">Cảm ơn quý khách đã mua hàng!</div>
</body>
</html>
