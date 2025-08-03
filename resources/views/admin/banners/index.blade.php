@extends('admin.layouts.main')

@section('title', 'Quản lý banner')

@section('content')
    <div class="container-fluid">
        <div class="card card-table">
            <div class="card-body">
                <div class="title-header option-title d-flex justify-content-between align-items-center">
                    <h5>Danh sách banner</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('banners.create') }}" class="btn btn-theme">
                            <i data-feather="plus"></i> Thêm Banner
                        </a>
                    </div>
                </div>

                {{-- Form tìm kiếm --}}
                <form action="{{ route('banners.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                    <div class="search-box" style="width:250px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tiêu đề, mô tả..."
                            class="form-control">
                    </div>
                    <button class="btn btn-primary me-2" type="submit">
                        <i class="ri-search-line"></i> Tìm
                    </button>
                    @if(request('search'))
                        <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                            <i class="ri-refresh-line"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>

                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                <div class="table-responsive table-product mt-3">
                    <table class="table theme-table align-middle">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Link</th>
                                <th>Vị trí</th>
                                <th>Kích hoạt</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banners as $banner)
                                                    <tr>
                                                        <td>
                                                            @php
                                                                $images = [];
                                                                if ($banner->image) {
                                                                    $images = json_decode($banner->image, true) ?: [];
                                                                }
                                                            @endphp
                                                            @if($images && is_array($images))
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($images as $img)
                                                                        <a href="{{ asset('storage/' . $img) }}" target="_blank" title="Xem ảnh lớn">
                                                                            <img src="{{ asset('storage/' . $img) }}" width="60" height="60"
                                                                                style="object-fit:contain; aspect-ratio:1/1; border-radius: 6px; border:1px solid #eee; background:#fafafa;">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có ảnh</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>{{ $banner->title }}</strong></td>
                                                        <td style="max-width: 200px;">
                                                            @if($banner->description)
                                                                <span class="text-truncate d-block" title="{{ $banner->description }}">
                                                                    {{ Str::limit($banner->description, 50) }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có mô tả</span>
                                                            @endif
                                                        </td>
                                                        <td style="max-width: 200px;">
                                                            @if($banner->link)
                                                                <a href="{{ $banner->link }}" target="_blank" class="text-truncate d-block"
                                                                    style="max-width: 200px;">
                                                                    {{ $banner->link }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có link</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $banner->position }}</td>
                                                        <td>
                                                            @if($banner->is_active)
                                                                <span class="badge bg-success">Hiện</span>
                                                            @else
                                                                <span class="badge bg-danger">Ẩn</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <ul class="d-flex gap-2">
                                                                <li>
                                                                    <a href="{{ route('banners.edit', $banner->id) }}" class="text-warning">
                                                                        <i class="ri-pencil-line"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('banners.show', $banner->id) }}" class="text-info"
                                                                        title="Xem chi tiết">
                                                                        <i class="ri-eye-line"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button class="btn btn-link p-0 text-danger delete-btn"
                                                                            data-id="{{ $banner->id }}"
                                                                            data-name="{{ $banner->title }}"
                                                                            title="Xoá">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có banner nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if (method_exists($banners, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $banners->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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

    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bannerId = this.dataset.id;
            const bannerTitle = this.dataset.name;
            
            showConfirmModal(
                `Bạn có chắc muốn xóa banner "${bannerTitle}"?`,
                () => {
                    // Show loading state
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="ri-loader-line rotating"></i>';
                    this.disabled = true;
                    
                    fetch(`/admin/banners/${bannerId}`, {
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
                            showAlert(data.message || 'Có lỗi xảy ra khi xóa banner!', 'danger');
                            // Restore button state
                            this.innerHTML = originalContent;
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi xóa banner!', 'danger');
                        // Restore button state
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    });
                },
                'danger'
            );
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

<style>
    .rotating {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush