@extends('admin.layouts.main')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách sản phẩm</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            <a class="btn btn-solid" href="{{ route('products.create') }}">Thêm sản phẩm</a>
                        </div>
                    </div>

                  <form action="{{ route('products.index') }}" method="GET" class="d-flex">
    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm"
        placeholder="Tìm kiếm sản phẩm...">
    <button type="submit" class="btn btn-sm btn-primary ms-2">Tìm</button>
</form>

                    <div class="table-responsive">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                <tr style="border-bottom: none !important;">
                                    <td>
                                        <div class="table-image">
                                            @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" width="60" alt="{{ $product->name }}">
                                            @else
                                            —
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '—' }}</td>
                                    <td>{{ $product->quantity ?? 0 }}</td>
                                    <td class="td-price">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                    <td class="{{ $product->status ? 'status-close' : 'status-danger' }}">
                                        <span>{{ $product->status ? 'Hiển thị' : 'Ẩn' }}</span>
                                    </td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="{{ route('products.show', $product) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('products.edit', $product) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có sản phẩm.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($products->hasPages())
                        <div class="mt-3">
                           {{ $products->appends(['keyword' => request('keyword')])->links() }}

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
