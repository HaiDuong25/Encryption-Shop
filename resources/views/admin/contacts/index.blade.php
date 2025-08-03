@extends('admin.layouts.main')

@section('title', 'Quản lý Liên hệ Khách hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">

                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Danh sách Liên hệ Khách hàng</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            {{-- Form tìm kiếm theo tên, email hoặc nội dung --}}
                            <form method="GET" action="{{ route('contacts.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên, email hoặc nội dung..." 
                                       value="{{ request('search') }}" style="width: 280px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line"></i> Tìm
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    @foreach (['success', 'error'] as $msg)
                        @if(session($msg))
                            <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }} alert-dismissible fade show mt-2">
                                {{ session($msg) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    @endforeach

                    <div class="table-responsive mt-3">
                        <table class="table theme-table text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người gửi</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th style="min-width: 250px;">Nội dung (tóm tắt)</th>
                                    <th>Ngày gửi</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts as $contact)
                                    <tr>
                                        <td>{{ $contact->id }}</td>
                                        <td>
                                            {{ $contact->name }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $contact->user_id && $contact->user ? "(User: {$contact->user->name} - ID: {$contact->user_id})" : '(Khách)' }}
                                            </small>
                                        </td>
                                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                        <td>{{ $contact->phone ?: 'N/A' }}</td>
                                        <td>{{ Str::limit($contact->content, 100) }}</td>
                                        <td>{{ optional($contact->created_at)->format('d/m/Y H:i') ?? 'Không rõ' }}</td>
                                        <td>
                                            <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                <li>
                                                    <a href="{{ route('contacts.show', $contact->id) }}" title="Xem chi tiết">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn btn-link p-0 text-danger delete-contact-btn" 
                                                            data-id="{{ $contact->id }}" data-name="liên hệ #{{ $contact->id }}" title="Xóa">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">Chưa có liên hệ nào.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($contacts->hasPages())
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $contacts->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
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
    document.querySelectorAll('.delete-contact-btn').forEach(button => {
        button.addEventListener('click', function() {
            const contactId = this.dataset.id;
            const contactName = this.dataset.name;
            
            showConfirmModal(
                `Bạn có chắc chắn muốn xóa ${contactName}?`,
                () => {
                    // Show loading state
                    this.innerHTML = '<i class="ri-loader-4-line"></i>';
                    this.disabled = true;
                    
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
