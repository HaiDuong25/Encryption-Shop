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
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const productId = this.dataset.id;
        const productName = this.dataset.name;

        if (!confirm(`Bạn có chắc muốn xóa sản phẩm "${productName}"?`)) return;

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
                alert(data.message || 'Xóa thành công!');
            } else if (data.requiresConfirmation) {
                const confirmHide = confirm(data.message);
                if (confirmHide) {
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
                        alert(data2.message || 'Đã chuyển sang trạng thái ẩn.');
                    } else {
                        alert(data2.message || 'Không thể ẩn sản phẩm.');
                    }
                } else {
                    alert('Đã hủy thao tác.');
                }
            } else {
                alert(data.message || 'Không thể xóa sản phẩm.');
            }
        } catch (error) {
            console.error('Lỗi khi xóa:', error);
            alert('Có lỗi xảy ra trong quá trình xử lý.');
        } finally {
            this.innerHTML = originalContent;
            this.disabled = false;
            feather.replace();
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

