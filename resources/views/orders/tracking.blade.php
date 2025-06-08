@extends('admin.layouts.main')

@section('title', 'Theo dõi đơn hàng #' . $order->id)

@section('content')
@php
    $steps = ['Đã đặt', 'Xác nhận', 'Giao cho ĐVVC', 'Đang giao', 'Đã nhận', 'Hoàn thành'];
@endphp

<div class="card">
    <div class="card-body">
        <div class="row align-items-center mb-4">
            <div class="col-md-2">
                <img src="{{ $order->product_image ?? 'https://via.placeholder.com/120' }}" alt="Product" class="img-fluid rounded">
            </div>
            <div class="col-md-10">
                <h5>{{ $order->product_name ?? 'Tên sản phẩm' }}</h5>
                <div><strong>Mã đơn:</strong> {{ $order->code ?? $order->id }}</div>
                <div><strong>Thương hiệu:</strong> {{ $order->brand ?? 'Không rõ' }}</div>
                <div><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
                <div class="text-success mt-2">Đơn hàng của bạn đang được xử lý. Thông tin tracking sẽ cập nhật trong 24h.</div>
            </div>
        </div>

        {{-- Tiến trình trạng thái --}}
        <div class="mb-4">
            <div id="order-steps" class="d-flex justify-content-between align-items-center position-relative" style="margin-bottom:30px;">
                @foreach ($steps as $index => $step)
                    <div class="text-center flex-fill position-relative" style="z-index:1; cursor:pointer;" onclick="setOrderStatus({{ $index }})">
                        <div class="mx-auto mb-2" style="width:36px;height:36px;">
                            <span id="step-icon-{{ $index }}" class="{{ $index <= $order->status ? 'bg-success text-white' : 'bg-light border border-secondary text-secondary' }} rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                {!! $index <= $order->status ? '<i class=\'fa fa-check\'></i>' : $index+1 !!}
                            </span>
                        </div>
                        <div style="font-size:13px;">{{ $step }}</div>
                    </div>
                    @if ($index < count($steps) - 1)
                        <div id="step-bar-{{ $index }}" class="flex-grow-1" style="height:4px; background: {{ $index < $order->status ? '#198754' : '#dee2e6' }}; margin:0 -8px;"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Bảng lịch sử trạng thái --}}
        <div class="mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Mô tả</th>
                        <th>Địa điểm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->history ?? [] as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item['date'])->format('H:i') }}</td>
                            <td>{{ $item['desc'] }}</td>
                            <td>{{ $item['location'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@endpush

@push('scripts')
<script>
    let currentStatus = {{ $order->status }};
    function setOrderStatus(status) {
        currentStatus = status;
        const steps = @json($steps);
        for(let i=0; i<steps.length; i++) {
            // Update icon
            let icon = document.getElementById('step-icon-' + i);
            if(i <= status) {
                icon.className = 'bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center';
                icon.innerHTML = '<i class="fa fa-check"></i>';
            } else {
                icon.className = 'bg-light border border-secondary text-secondary rounded-circle d-inline-flex align-items-center justify-content-center';
                icon.innerHTML = (i+1);
            }
            // Update bar
            if(i < steps.length-1) {
                let bar = document.getElementById('step-bar-' + i);
                bar.style.background = (i < status) ? '#198754' : '#dee2e6';
            }
        }
    }
</script>
@endpush
