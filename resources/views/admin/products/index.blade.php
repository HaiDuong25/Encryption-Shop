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
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm theo tên" class="form-control" style="width:200px;">

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

                <button class="btn btn-outline-primary" type="submit">
                    <i data-feather="search"></i> Tìm
                </button>
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
                                        {{ number_format($product->price,0,',','.') }} đ
                                    </span><br>
                                    <span class="text-danger fw-bold">
                                        {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                    </span>
                                @else
                                    <span class="text-danger fw-bold">
                                        {{ number_format($product->price,0,',','.') }} đ
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
    if (window.feather) feather.replace();

    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;

            if (confirm(`Bạn có chắc muốn xóa sản phẩm "${productName}"?`)) {
                // Show loading state
                const icon = this.querySelector('i');
                const originalContent = this.innerHTML;
                this.innerHTML = '<i data-feather="loader" class="rotating"></i>';
                this.disabled = true;

                fetch(`/admin/products/${productId}`, {
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
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));

                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm!');
                        // Restore button state
                        this.innerHTML = originalContent;
                        this.disabled = false;
                        feather.replace();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm!');
                    // Restore button state
                    this.innerHTML = originalContent;
                    this.disabled = false;
                    feather.replace();
                });
            }
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
