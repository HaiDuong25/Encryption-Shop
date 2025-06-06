<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hóa đơn thanh toán</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Hóa đơn thanh toán</h2>
    <p><strong>Mã thanh toán:</strong> {{ $payment->id }}</p>
    <p><strong>Ngày xác nhận:</strong> {{ $payment->confirmed_at->format('d/m/Y H:i') }}</p>
    <p><strong>Trạng thái:</strong> {{ ucfirst($payment->status) }}</p>

    <table>
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
</body>
</html>
