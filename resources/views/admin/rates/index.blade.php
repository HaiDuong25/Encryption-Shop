@extends('admin.layouts.main')

@section('title', 'Quản lý Đánh giá Khách hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách Đánh giá Khách hàng</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            {{-- Form tìm kiếm theo tên người dùng hoặc nội dung đánh giá --}}
                            <form method="GET" action="{{ route('rates.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên người dùng hoặc nội dung..." 
                                       value="{{ request('search') }}" style="width: 300px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line"></i> Tìm
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('rates.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    {{-- Thông báo Session --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm ID</th>
                                    <th>Điểm</th>
                                    <th style="min-width: 200px;">Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $rate)
                                <tr style="border-bottom: none !important;">
                                    <td>{{ $rate->id }}</td>
                                    <td>
                                        @if ($rate->user)
                                        {{ $rate->user->name }}
                                        <br><small class="text-muted">(ID: {{ $rate->user->id }})</small>
                                        @else
                                        <span class="text-muted">Không xác định</span>
                                        @endif
                                    </td>
                                    <td>{{ $rate->product_id ?? 'N/A' }}</td>
                                    <td>
                                        @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-star {{ $i <= $rate->score ? 'fas text-warning' : 'far text-muted' }}"></i>
                                        @endfor
                                        ({{ $rate->score }})
                                    </td>
                                    <td>{{ Str::limit($rate->content, 100) }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $rate->status_class }}">
                                            {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
                                        </span>
                                    </td>
                                    <td>{{ $rate->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="{{ route('rates.show', $rate->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('rates.edit', $rate->id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-link p-0 text-danger delete-rate-btn" data-id="{{ $rate->id }}" data-name="đánh giá #{{ $rate->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không có đánh giá nào để hiển thị.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang --}}
                    @if ($rates->hasPages())
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $rates->withQueryString()->links() }}
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

// AJAX Delete functionality for rates
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-rate-btn').forEach(button => {
        button.addEventListener('click', function() {
            const rateId = this.dataset.id;
            const rateName = this.dataset.name;
            
            showConfirmModal(
                `Bạn có chắc chắn muốn xóa ${rateName} không?`,
                () => {
                    // Show loading state
                    this.innerHTML = '<i class="ri-loader-4-line"></i>';
                    this.disabled = true;
                    
                    fetch(`/admin/rates/${rateId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from table
                            const row = this.closest('tr');
                            row.remove();
                            
                            // Show success message
                            showAlert(data.message, 'success');
                        } else {
                            showAlert(data.message || 'Có lỗi xảy ra khi xóa đánh giá!', 'danger');
                            // Restore button state
                            this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi xóa đánh giá!', 'danger');
                        // Restore button state
                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                        this.disabled = false;
                    });
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
