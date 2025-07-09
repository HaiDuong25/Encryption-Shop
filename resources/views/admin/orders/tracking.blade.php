@extends('admin.layouts.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Tracking đơn hàng</h4>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Chi tiết đơn hàng</h5>
            <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
            <p><strong>Người đặt:</strong> {{ $order->orderer_name }} - {{ $order->orderer_phone }}</p>
            <p><strong>Người nhận:</strong> {{ $order->recipient_name }} - {{ $order->recipient_phone }}</p>
            <p><strong>Địa chỉ nhận:</strong> {{ $order->recipient_address }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->name ?? 'N/A' }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p><strong>Trạng thái hiện tại:</strong>
                <span class="badge bg-success">
                    {{ $order->status_text ?? 'Chưa xác định' }}
                </span>
            </p>

            <h6 class="mt-4">Sản phẩm</h6>
            <ul>
                @foreach($order->orderDetails as $detail)
                    <li>{{ $detail->variant->product->name ?? 'Sản phẩm đã xóa' }} x {{ $detail->quantity }}</li>
                @endforeach
            </ul>

            <div class="d-flex justify-content-between px-5 mt-4">
                @php
                    $steps = [
                        'Đã đặt',
                        'Xác nhận',
                        'Giao cho ĐVVC',
                        'Đang giao',
                        'Đã nhận',
                        'Hoàn thành',
                    ];
                @endphp

                @foreach ($steps as $index => $label)
                    <div class="text-center step-item" data-status="{{ $index + 1 }}" style="cursor: pointer;">
                        <div class="step-circle {{ $order->status >= $index + 1 ? 'bg-success text-white' : 'bg-light' }}">
                            {{ $index + 1 }}
                        </div>
                        <div>{{ $label }}</div>
                    </div>
                @endforeach
            </div>

            <h6 class="mt-4">Lịch sử vận chuyển</h6>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Mô tả</th>
                        <th>Địa điểm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->history ?? [] as $log)
                        @php
                            $time = \Carbon\Carbon::parse($log['date']);
                        @endphp
                        <tr>
                            <td>{{ $time->format('d/m/Y') }}</td>
                            <td>{{ $time->format('H:i') }}</td>
                            <td>{{ $log['desc'] }}</td>
                            <td>{{ $log['location'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('orders.index') }}" class="btn btn-danger">← Quay lại</a>
        </div>
    </div>
</div>

<style>
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        margin: auto;
    }
</style>

<script>
    document.querySelectorAll('.step-item').forEach(function (el) {
        el.addEventListener('click', function () {
            const status = el.dataset.status;
            const orderId = {{ $order->id }};

            if (!confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng đến bước này?')) return;

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ status: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== undefined) {
                    alert('Cập nhật trạng thái thành công!');
                    window.location.reload();
                } else {
                    alert(data.message || 'Cập nhật thất bại!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            });
        });
    });
</script>
@endsection
