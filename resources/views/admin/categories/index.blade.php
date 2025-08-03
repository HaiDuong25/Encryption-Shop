@extends('admin.layouts.main')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách danh mục</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            <a class="btn btn-solid btn-sm" href="{{ route('admin.categories.create') }}">Thêm danh mục</a>
                        </div>
                    </div>

                    <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                        <div class="search-box" style="width:250px;">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên..." value="{{ request('search') }}">
                        </div>
                        <select name="parent_id" class="form-select" style="width:200px;">
                            <option value="">-- Danh mục cha --</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="status" class="form-select" style="width:150px;">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        <button class="btn btn-primary me-2" type="submit">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        @if(request()->hasAny(['search', 'parent_id', 'status']))
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </form>

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

                    <div class="table-responsive mt-3">
                        <table class="table theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Danh mục cha</th>
                                    <th>Ngày tạo</th>
                                    <th>Ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = $categories->groupBy('parent_id');
                                    $parents = $grouped[null] ?? collect();
                                @endphp

                                @forelse ($parents as $parent)
                                    <tr class="parent-row" data-id="{{ $parent->id }}">
                                        <td class="text-start">
                                            <a href="javascript:void(0);" class="toggle-children fw-bold text-dark text-decoration-none">
                                                <i class="ri-arrow-down-s-line me-1"></i> {{ $parent->name }}
                                            </a>
                                        </td>
                                        <td>{{ $parent->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                        <td>
                                            @if ($parent->image)
                                                <img src="{{ asset('storage/' . $parent->image) }}" width="60" alt="{{ $parent->name }}">
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="{{ $parent->status ? 'status-close' : 'status-danger' }}">
                                            <span>{{ $parent->status ? 'Hiển thị' : 'Ẩn' }}</span>
                                        </td>
                                        <td>
                                            <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                <li><a href="{{ route('admin.categories.edit', $parent) }}"><i class="ri-pencil-line"></i></a></li>
                                                <li>
                                                    <button type="button" class="btn btn-link p-0 text-danger delete-btn" data-id="{{ $parent->id }}" data-name="{{ $parent->name }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                    @foreach ($grouped[$parent->id] ?? [] as $child)
                                        <tr class="child-row d-none" data-parent-id="{{ $parent->id }}">
                                            <td class="text-start">└── {{ $child->name }}</td>
                                            <td>{{ $child->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                            <td>
                                                @if ($child->image)
                                                    <img src="{{ asset('storage/' . $child->image) }}" width="60" alt="{{ $child->name }}">
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="{{ $child->status ? 'status-close' : 'status-danger' }}">
                                                <span>{{ $child->status ? 'Hiển thị' : 'Ẩn' }}</span>
                                            </td>
                                            <td>
                                                <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                    <li><a href="{{ route('admin.categories.edit', $child) }}"><i class="ri-pencil-line"></i></a></li>
                                                    <li>
                                                        <button type="button" class="btn btn-link p-0 text-danger delete-btn" data-id="{{ $child->id }}" data-name="{{ $child->name }}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr><td colspan="5" class="text-center">Không có danh mục.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-children').forEach(button => {
            button.addEventListener('click', function () {
                const parentRow = button.closest('tr');
                const parentId = parentRow.dataset.id;
                const icon = button.querySelector('i');

                document.querySelectorAll(`tr[data-parent-id='${parentId}']`).forEach(row => {
                    row.classList.toggle('d-none');
                });

                if (icon) {
                    icon.classList.toggle('ri-arrow-down-s-line');
                    icon.classList.toggle('ri-arrow-up-s-line');
                }
            });
        });

        const selectedParentId = '{{ request("parent_id") }}';
        if (selectedParentId) {
            const parentRow = document.querySelector(`tr[data-id='${selectedParentId}']`);
            if (parentRow) {
                document.querySelectorAll(`tr[data-parent-id='${selectedParentId}']`).forEach(row => {
                    row.classList.remove('d-none');
                });

                const icon = parentRow.querySelector('i');
                if (icon) {
                    icon.classList.remove('ri-arrow-down-s-line');
                    icon.classList.add('ri-arrow-up-s-line');
                }
            }
        }

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
                const categoryId = this.dataset.id;
                const categoryName = this.dataset.name;

                // Sử dụng modal xác nhận thay vì confirm()
                showConfirmModal(
                    `Bạn có chắc muốn xóa danh mục "${categoryName}"?`,
                    () => {
                        // Show loading state
                        this.innerHTML = '<i class="ri-loader-4-line"></i>';
                        this.disabled = true;

                        fetch(`{{ route('admin.categories.destroy', ':id') }}`.replace(':id', categoryId), {
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
                                showAlert(data.message || 'Có lỗi xảy ra khi xóa danh mục!', 'danger');
                                // Restore button state
                                this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                                this.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Có lỗi xảy ra khi xóa danh mục!', 'danger');
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
@endpush

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
