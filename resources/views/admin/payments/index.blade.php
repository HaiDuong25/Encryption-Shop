@extends('admin.layouts.main')

@section('title', 'Quản lý Thanh Toán')

@section('content')
    @php use Carbon\Carbon; @endphp

<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                <h5>Quản lý Thanh Toán</h5>
                <div class="right-options d-flex gap-2 align-items-center">
                    {{-- Form tìm kiếm --}}
                    <form method="GET" action="{{ route('payments.index') }}" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" 
                               placeholder="Tìm theo tên người nhận hoặc ID đơn hàng..." style="width: 280px;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        @if(request('search'))
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive table-product mt-3">
                <table class="table theme-table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Đơn hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Dữ liệu giao dịch</th>
                            <th>Trạng thái</th>
                            <th>Ngày thanh toán</th>
                            <th>Hành động</th>
                            <th>Xem hóa đơn</th>
                        </tr>
                    </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td class="fw-bold">{{ $payment->id }}</td>
                        <td>
                            <span class="badge text-dark" style="font-size: 1rem;">Đơn hàng {{ $payment->order->id ?? 'N/A' }}</span><br>
                            <small class="text-muted">{{ $payment->order->recipient_name ?? '' }}</small>
                        </td>
                        <td class="text-end">{{ format_vnd($payment->order->total_price ?? 0) }} <span class="text-secondary">VND</span></td>
                        <td>
                            <span class="badge bg-light text-dark border border-1 border-secondary">{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</span>
                        </td>
                        <td>
                            @if($payment->payment_method_type && in_array($payment->payment_method_type, ['MoMo', 'ZaloPay']))
                                <div class="text-center">
                                    <span class="badge bg-info text-white mb-1">{{ $payment->payment_method_type }}</span><br>
                                    @if($payment->transaction_code)
                                        <small class="text-dark fw-bold">Mã GD: {{ $payment->transaction_code }}</small><br>
                                    @endif
                                    @if($payment->order && $payment->order->transaction_id)
                                        <small class="text-muted">Mã ĐH: {{ $payment->order->transaction_id }}</small><br>
                                    @endif
                                    @if($payment->confirmed_at)
                                        <small class="text-success">{{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i') }}</small>
                                    @endif
                                </div>
                            @elseif($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD')
                                <div class="text-center">
                                    <span class="badge bg-success text-white mb-1">COD</span><br>
                                    <small class="text-muted">Thanh toán khi nhận hàng</small>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $statusText = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'completed' => 'Đã thanh toán',
    'rejected' => 'Đã hủy',
    'refunded' => 'Đã hoàn tiền'
];

$statusColor = [
    'pending' => 'warning',
    'confirmed' => 'info',
    'completed' => 'success',
    'rejected' => 'danger',
    'refunded' => 'info'
];
                            @endphp
                            <span class="badge bg-{{ $statusColor[$payment->status] ?? 'secondary' }}" style="font-size: 1rem;">
                                {{ $statusText[$payment->status] ?? ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            @if($payment->confirmed_at)
                                <span class="text-success"><i class="fa-solid fa-check-circle me-1"></i>{{ \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') }}</span>
                            @elseif($payment->rejected_at)
                                <span class="text-danger"><i class="fa-solid fa-times-circle me-1"></i>{{ \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD')
                                {{-- Flow COD: pending → confirmed → completed --}}
                                @if($payment->status === 'pending')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-info btn-xs px-2 py-1 confirm-payment-btn"
                                            data-action="confirm" data-id="{{ $payment->id }}" data-message="Xác nhận đơn hàng COD này?"
                                            style="font-size: 0.85rem;">
                                            <i class="fa-solid fa-check me-1"></i> Xác nhận đơn
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs px-2 py-1 confirm-payment-btn"
                                            data-action="reject" data-id="{{ $payment->id }}" data-message="Bạn có chắc muốn hủy đơn này?"
                                            style="font-size: 0.85rem;">
                                            <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                        </button>
                                    </div>
                                @elseif($payment->status === 'confirmed')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-success btn-xs px-2 py-1 confirm-payment-btn"
                                            data-action="complete" data-id="{{ $payment->id }}" data-message="Hoàn thành đơn hàng COD này? (Khách đã thanh toán)"
                                            style="font-size: 0.85rem;">
                                            <i class="fa-solid fa-check-double me-1"></i> Hoàn thành
                                        </button>
                                        <span class="badge bg-info text-white" style="font-size: 0.85rem;">
                                            Đã xác nhận lúc
                                            {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i') : '' }}
                                        </span>
                                    </div>
                                @elseif($payment->status === 'completed')
                                    <span class="badge bg-success text-white">
                                        Đã hoàn thành lúc
                                        {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger text-white">
                                        Đã hủy lúc
                                        {{ $payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @endif
                            @else
                                {{-- Flow Online: pending → completed (giữ nguyên) --}}
                                @if($payment->status === 'pending')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-success btn-xs px-2 py-1 confirm-payment-btn"
                                            data-action="confirm" data-id="{{ $payment->id }}" data-message="Xác nhận thanh toán cho đơn này?"
                                            style="font-size: 0.85rem; background-color: #28a745; border-color: #28a745;">
                                            <i class="fa-solid fa-check me-1"></i> Xác nhận
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs px-2 py-1 confirm-payment-btn"
                                            data-action="reject" data-id="{{ $payment->id }}" data-message="Bạn có chắc muốn hủy đơn này?"
                                            style="font-size: 0.85rem; background-color: #dc3545; border-color: #dc3545;">
                                            <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                        </button>
                                    </div>
                                @elseif($payment->status === 'completed')
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <span class="badge bg-success text-white" style="background-color: #28a745;">
                                            Đã xác nhận lúc
                                            {{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : '' }}
                                        </span>
                                    </div>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger text-white" style="background-color: #dc3545;">
                                        Đã hủy lúc
                                        {{ $payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : '' }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                        @if(in_array($payment->status, ['completed']))
                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                    <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-primary btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                    </a>
                                    <a href="{{ route('admin.payments.download-invoice', $payment->id) }}" class="btn btn-success btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-download me-1"></i> Tải PDF
                                    </a>
                                </div>
                            @elseif($payment->status === 'rejected')
                                <a href="{{ route('admin.payments.invoice', $payment->id) }}" class="btn btn-primary btn-xs px-2 py-1"
                                    style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                </a>
                            @else
                                <span class="text-muted">Chưa có hóa đơn</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $payments->links() }}
    </div>

<script>
// Function để hiển thị alert
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.card');
    container.parentNode.insertBefore(alertDiv, container);
    
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

// Payment action functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.confirm-payment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const paymentId = this.dataset.id;
            const message = this.dataset.message;
            
            showConfirmModal(
                message,
                () => {
                    // Show loading state
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Đang xử lý...';
                    this.disabled = true;
                    
                    // Submit form programmatically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/payments/${paymentId}/${action}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                'warning'
            );
        });
    });
});
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
