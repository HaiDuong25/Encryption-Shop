@extends('admin.layouts.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Tracking đơn hàng</h4>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Chi tiết đơn hàng</h5>
            <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
            <p><strong>Khách hàng:</strong> {{ $order->name }} - {{ $order->phone }}</p>
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

            showConfirmModal(
                'Bạn có chắc muốn cập nhật trạng thái đơn hàng đến bước này?',
                () => {
                    fetch(`/admin/orders/${orderId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ status: status })
            })
            })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showAlert('Có lỗi xảy ra khi cập nhật trạng thái!', 'danger');
                    });
                },
                'warning'
            );
        });
    });

// Function để hiển thị alert
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    const firstChild = container.firstElementChild;
    container.insertBefore(alertDiv, firstChild);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Function để hiển thị modal xác nhận
function showConfirmModal(message, onConfirm, type = 'warning') {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmButton = document.getElementById('confirmButton');
    const confirmIcon = document.getElementById('confirmIcon');
    
    // Cập nhật nội dung modal
    confirmMessage.textContent = message;
    
    // Cập nhật icon và màu sắc dựa trên type
    if (type === 'danger') {
        confirmIcon.innerHTML = '<i class="ri-delete-bin-line" style="font-size: 48px; color: #dc3545;"></i>';
        confirmButton.className = 'btn btn-danger';
        confirmButton.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Xóa';
    } else if (type === 'warning') {
        confirmIcon.innerHTML = '<i class="ri-alert-line" style="font-size: 48px; color: #ffc107;"></i>';
        confirmButton.className = 'btn btn-warning';
        confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
    } else {
        confirmIcon.innerHTML = '<i class="ri-question-line" style="font-size: 48px; color: #0d6efd;"></i>';
        confirmButton.className = 'btn btn-primary';
        confirmButton.innerHTML = '<i class="ri-check-line me-1"></i>Xác nhận';
    }
    
    // Xóa event listener cũ và thêm mới
    const newConfirmButton = confirmButton.cloneNode(true);
    confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
    
    // Thêm event listener cho nút xác nhận
    newConfirmButton.addEventListener('click', function() {
        modal.hide();
        onConfirm();
    });
    
    // Hiển thị modal
    modal.show();
}
</script>

<!-- Modal xác nhận -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="ri-question-line text-warning me-2"></i>
                    Xác nhận hành động
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="confirmIcon" class="mb-3">
                    <i class="ri-question-line" style="font-size: 48px; color: #ffc107;"></i>
                </div>
                <p id="confirmMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmButton">
                    <i class="ri-check-line me-1"></i>Xác nhận
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
