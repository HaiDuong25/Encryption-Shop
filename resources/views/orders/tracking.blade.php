@extends('admin.layouts.main')

@section('title', 'Theo dõi đơn hàng #' . $order->id)

@section('content')
@php
    $steps = ['Đã đặt', 'Xác nhận', 'Giao cho ĐVVC', 'Đang giao', 'Đã nhận', 'Hoàn thành'];
@endphp

<div class="card">
    <div class="card-header">
        <h4>Theo dõi đơn hàng #{{ $order->id }}</h4>
    </div>
    <div class="card-body">
        {{-- Dropdown chuyển trạng thái --}}
        <div class="mb-4">
            <label for="order-status"><strong>Chuyển trạng thái:</strong></label>
            <select id="order-status" class="form-select w-25">
                @foreach ($steps as $index => $step)
                    <option value="{{ $index }}" {{ $index == $order->status ? 'selected' : '' }}>
                        {{ $step }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tiến trình trạng thái --}}
        <div class="mb-4">
            <div class="progress-tracker d-flex justify-content-between align-items-center" id="progress-tracker">
                @foreach ($steps as $index => $step)
                    <div class="text-center step-item" data-step="{{ $index }}">
                        <div class="rounded-circle text-white step-circle {{ $index <= $order->status ? 'bg-success' : 'bg-secondary' }}"
                             style="width:40px;height:40px;line-height:40px;">
                            {{ $index + 1 }}
                        </div>
                        <div class="step-label">{{ $step }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Thông tin giao hàng --}}
        <h5>Thông tin giao hàng</h5>
        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item"><strong>Người nhận:</strong> {{ $order->name }}</li>
            <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $order->phone }}</li>
            <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $order->address }}</li>
            <li class="list-group-item"><strong>Vị trí hiện tại:</strong>
                <span id="current-location">{{ $steps[$order->status] ?? 'Không xác định' }}</span>
            </li>
        </ul>

        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#order-status').on('change', function () {
        let newStatus = parseInt($(this).val());

        // Cập nhật UI tiến trình
        $('.step-item').each(function () {
            let step = parseInt($(this).data('step'));
            if (step <= newStatus) {
                $(this).find('.step-circle').removeClass('bg-secondary').addClass('bg-success');
            } else {
                $(this).find('.step-circle').removeClass('bg-success').addClass('bg-secondary');
            }
        });

        // Cập nhật vị trí hiện tại
        let newLabel = $('.step-item[data-step="' + newStatus + '"] .step-label').text();
        $('#current-location').text(newLabel);

        // Gửi AJAX cập nhật trạng thái
        $.ajax({
            url: '{{ route('admin.orders.updateStatus', $order->id) }}',
            method: 'POST',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                console.log("Cập nhật trạng thái thành công.");
            },
            error: function () {
                alert("Lỗi khi cập nhật trạng thái!");
            }
        });
    });
</script>
@endpush
