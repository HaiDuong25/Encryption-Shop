@extends('admin.layouts.main')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="col-12">
    <h3 class="mb-3">Chi tiết sản phẩm: {{ $product->name }}</h3>
    <div class="card p-3">
        <div class="row">
            <div class="col-md-4">
                <h5>Ảnh đại diện</h5>
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3 rounded">
                @else
                <p>Không có ảnh đại diện</p>
                @endif

                <h5>Ảnh mô tả</h5>
                @if ($product->images && $product->images->count() > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($product->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="Ảnh mô tả" class="img-thumbnail" width="100" height="100">
                    @endforeach
                </div>
                @else
                <p>Không có ảnh mô tả</p>
                @endif
            </div>

            <div class="col-md-8">
                <h4>Thông tin sản phẩm</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Tên:</strong> {{ $product->name }}</li>
                    <li class="list-group-item"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</li>
                    <li class="list-group-item"><strong>Giá khuyến mãi:</strong> {{ $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . ' đ' : '—' }}</li>
                    <li class="list-group-item"><strong>Số lượng:</strong> {{ $product->quantity }}</li>
                    <li class="list-group-item"><strong>Chất liệu:</strong> {{ $product->material ?? '—' }}</li>
                    <li class="list-group-item"><strong>Danh mục:</strong> {{ $product->category->name ?? '—' }}</li>
                    <li class="list-group-item"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? '—' }}</li>
                    @php $variant = $product->variants->first(); @endphp
                    <li class="list-group-item"><strong>Màu sắc:</strong> {{ $variant?->color->name ?? '—' }}</li>
                    <li class="list-group-item"><strong>Kích cỡ:</strong> {{ $variant?->size->name ?? '—' }}</li>
                    <li class="list-group-item"><strong>Mô tả:</strong> {!! nl2br(e($product->description)) ?: '—' !!}</li>
                    <li class="list-group-item"><strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $product->status ? 'success' : 'secondary' }}">
                            {{ $product->status ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </li>
                    <li class="list-group-item"><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</li>
                </ul>
                <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary mt-3">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection
