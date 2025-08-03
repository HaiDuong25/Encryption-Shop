@extends('admin.layouts.main')

@section('title', 'Quản lý sản phẩm')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title">
                <h5>Danh sách sản phẩm</h5>
                <a href="{{ route('products.create') }}" class="btn btn-theme">
                    <i data-feather="plus"></i> Thêm sản phẩm
                </a>
            </div>
            <form action="{{ route('products.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                <div class="search-box" style="width:250px;">
                    <input type="text" name="search" value="{{ request('search') ?? request('keyword') }}" placeholder="Tìm kiếm theo tên sản phẩm..." class="form-control">
                </div>

                <select name="category_id" class="form-select" style="width:180px;">
                    <option value="">-- Danh mục --</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>

                <input type="number" name="price_from" value="{{ request('price_from') }}" placeholder="Giá từ" class="form-control" style="width:120px;">
                <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="Giá đến" class="form-control" style="width:120px;">

                <select name="status" class="form-select" style="width:150px;">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                </select>

                <button class="btn btn-primary me-2" type="submit">
                    <i class="ri-search-line"></i> Tìm
                </button>
                @if(request()->hasAny(['search', 'keyword', 'category_id', 'price_from', 'price_to', 'status']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2 bg-dark">
                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                    </a>
                @endif
            </form>


            <div class="table-responsive table-product">
                <table class="table theme-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="text-center">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80" class="rounded border">
                                @else
                                <span class="text-secondary small fst-italic">Không có</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $product->name }}</span>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>
                                @if($product->sale_price)
                                    <span class="text-muted text-decoration-line-through small">
                                        {{ format_vnd($product->price) }} đ
                                    </span><br>
                                    <span class="text-danger fw-bold">
                                        {{ format_vnd($product->sale_price) }} đ
                                    </span>
                                @else
                                    <span class="text-danger fw-bold">
                                        {{ format_vnd($product->price) }} đ
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($product->status == 'active')
                                <span class="badge bg-success">Hiển thị</span>
                                @else
                                <span class="badge bg-danger">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <ul class="d-flex flex-wrap gap-2 mb-0" style="list-style:none; padding-left:0;">
                                    <li>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-link p-0" title="Xem chi tiết">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-link p-0" title="Sửa">
                                            <i data-feather="edit"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <button class="btn btn-link p-0 text-danger delete-btn"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                title="Xoá">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $products->links() }}
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

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const productId = this.dataset.id;
        const productName = this.dataset.name;

        // Sử dụng modal xác nhận thay vì confirm()
        showConfirmModal(
            `Bạn có chắc muốn xóa sản phẩm "${productName}"?`,
            async () => {
                const icon = this.querySelector('i');
                const originalContent = this.innerHTML;
                this.innerHTML = '<i data-feather="loader" class="rotating"></i>';
                this.disabled = true;

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch(`/admin/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.closest('tr').remove();
                        showAlert(data.message || 'Xóa thành công!', 'success');
                    } else if (data.requiresConfirmation) {
                        showConfirmModal(
                            data.message,
                            async () => {
                                // Gửi lại DELETE request với param set_inactive=true
                                const response2 = await fetch(`/admin/products/${productId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ set_inactive: true })
                                });

                                const data2 = await response2.json();

                                if (response2.ok && data2.success) {
                                    const row = this.closest('tr');
                                    const statusCell = row.querySelector('td span.badge');
                                    if (statusCell) {
                                        statusCell.className = 'badge bg-danger';
                                        statusCell.textContent = 'Ẩn';
                                    }

                                    this.closest('li').remove(); // Ẩn nút xóa
                                    showAlert(data2.message || 'Đã chuyển sang trạng thái ẩn.', 'success');
                                } else {
                                    showAlert(data2.message || 'Không thể ẩn sản phẩm.', 'danger');
                                }
                            },
                            'warning'
                        );
                    } else {
                        showAlert(data.message || 'Không thể xóa sản phẩm.', 'danger');
                    }
                } catch (error) {
                    console.error('Lỗi khi xóa:', error);
                    showAlert('Có lỗi xảy ra trong quá trình xử lý.', 'danger');
                } finally {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                    feather.replace();
                }
            },
            'danger'
        );
    });
});
</script>

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

