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
            <form action="{{ route('products.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm sản phẩm..." class="form-control" style="width:220px;">
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
                            <td class="text-danger fw-bold">{{ number_format($product->price,0,',','.') }} đ</td>
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
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Xoá sản phẩm này?')" class="btn btn-link p-0 text-danger" title="Xoá">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
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
</script>
@endpush
