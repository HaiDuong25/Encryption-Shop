@extends('admin.layouts.main')

@section('title', 'Quản lý Sản phẩm')

@section('content')

<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Sản phẩm</h3>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-box me-1"></i> Tất cả Sản phẩm</h5>
            <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">+ Thêm mới</a>
        </div>

        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Ảnh đại diện</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80" class="img-thumbnail">
                                @else
                                —
                                @endif
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>{{ $product->brand->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $product->status ? 'success' : 'secondary' }}">
                                    {{ $product->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Không có sản phẩm.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
            <div class="mt-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
