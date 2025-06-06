@extends('admin.layouts.main')

@section('title', 'Biến thể sản phẩm')

@section('content')

<div class="col-12"> <h3 class="mt-3 mb-3">Danh sách Biến thể Sản phẩm</h3> <div class="card"> <div class="card-header d-flex justify-content-between align-items-center"> <h5 class="card-title mb-0"><i class="fas fa-cubes me-1"></i> Tất cả Biến thể</h5> <a href="{{ route('product-variants.create') }}" class="btn btn-success btn-sm">+ Thêm mới</a> </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Màu</th>
                        <th>Kích cỡ</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Ảnh</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td>{{ $variant->product->name ?? '—' }}</td>
                        <td>{{ $variant->color->name ?? '—' }}</td>
                        <td>{{ $variant->size->name ?? '—' }}</td>
                        <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>
                            @if ($variant->image)
                                <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh" width="80">
                            @endif
                        </td>
                        <td>{{ $variant->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('product-variants.edit', $variant) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('product-variants.destroy', $variant) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có biến thể sản phẩm.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
