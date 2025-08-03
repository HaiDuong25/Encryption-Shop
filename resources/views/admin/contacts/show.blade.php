@extends('admin.layouts.main')

@section('title', 'Chi tiết Liên hệ #' . $contact->id)

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết Liên hệ #{{ $contact->id }}</h1>
        </div>
        <div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại Danh sách
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">Thông tin Liên hệ</h5>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>ID:</strong> <span class="text-muted">{{ $contact->id }}</span></p>
                    <p class="mb-2"><strong>Người gửi:</strong> <span class="text-muted">{{ $contact->name }}</span></p>
                    <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a></p>
                    <p class="mb-2"><strong>Điện thoại:</strong> <span class="text-muted">{{ $contact->phone ?: 'N/A' }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Ngày gửi:</strong> <span class="text-muted">{{ $contact->created_at->format('d/m/Y H:i:s') }}</span></p>
                    @if($contact->user_id && $contact->user)
                    <p class="mb-2"><strong>Tài khoản liên kết:</strong> <span class="text-muted">{{ $contact->user->name }} (ID: {{ $contact->user_id }})</span></p>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 font-weight-bold text-dark">Nội dung:</h5>
            <div class="p-3 border rounded bg-light text-muted">
                {!! nl2br(e($contact->content)) !!}
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="button" class="btn btn-danger delete-contact-btn" data-id="{{ $contact->id }}" data-name="liên hệ #{{ $contact->id }}">
                <i class="fas fa-trash me-2"></i> Xóa liên hệ này
            </button>
        </div>
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
    
    const container = document.querySelector('.container-fluid');
    const card = document.querySelector('.card');
    container.insertBefore(alertDiv, card);
    
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

// Delete contact functionality
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.querySelector('.delete-contact-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const contactId = this.dataset.id;
            const contactName = this.dataset.name;
            
            showConfirmModal(
                `Bạn có chắc chắn muốn xóa ${contactName}? Thao tác này không thể hoàn tác!`,
                () => {
                    // Submit form programmatically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/contacts/${contactId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                'danger'
            );
        });
    }
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
</div>
@endsection
