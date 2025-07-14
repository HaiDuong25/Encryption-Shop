{{-- filepath: resources/views/admin/payments/invoice.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Hóa đơn thanh toán')

@section('content')
    <div class="w-100 min-vh-100 bg-white p-0 m-0" style="overflow-x:auto;">
        <div class="w-100" style="max-width: 1200px; margin: 0 auto;">
            <div class="d-flex justify-content-between align-items-center py-4 border-bottom mb-4">
                <h2 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-file-invoice-dollar me-2"></i>HÓA ĐƠN THANH TOÁN</h2>
                <form action="{{ route('admin.payments.export-pdf', $payment->id) }}" method="GET">
                    <button type="submit" class="btn btn-danger btn-lg fw-bold"><i class="fa-solid fa-file-pdf me-1"></i> Xuất PDF</button>
                </form>
            </div>
            <div class="bg-white p-4 shadow rounded-4">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 mb-2">
                        <div><strong>Mã thanh toán:</strong> <span class="text-primary">#{{ $payment->id }}</span></div>
                        <div><strong>Đơn hàng:</strong> <span class="text-dark">#{{ $payment->order->id ?? 'N/A' }}</span></div>
                    </div>
                    <div class="col-lg-6 col-12 mb-2 text-lg-end">
                        <div><strong>Ngày xác nhận:</strong> <span>{{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '-' }}</span></div>
                        <div><strong>Trạng thái:</strong>
                            @if ($payment->status === 'confirmed')
                                <span class="badge bg-success text-white">Đã xác nhận đơn hàng</span>
                            @elseif($payment->status === 'rejected')
                                <span class="badge bg-danger text-white">Đã hủy đơn hàng</span>
                            @else
                                <span class="badge bg-secondary">Chờ xác nhận</span>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered mb-4">
                    <tr>
                        <th style="width: 40%">Người nhận</th>
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
                <h5 class="mb-3">Chi tiết đơn hàng</h5>
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
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
                                <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <div class="text-end w-100">
                        <div class="fw-bold">Tạm tính: {{ number_format($orderDetails->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }} đ</div>
                        @if($payment->order->coupon_discount > 0)
                            <div class="fw-bold text-danger">Giảm giá: -{{ number_format($payment->order->coupon_discount, 0, ',', '.') }} đ</div>
                        @endif
                        <h5 class="mb-0 mt-2">Tổng cộng:&nbsp;<span class="fs-4 fw-bold text-primary">{{ number_format(($orderDetails->sum(fn($d) => $d->price * $d->quantity)) - ($payment->order->coupon_discount ?? 0), 0, ',', '.') }} đ</span></h5>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-lg"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách thanh toán</a>
                </div>
            </div>
        </div>
    </div>
@endsection
