@extends('admin.layouts.main')

@section('title', isset($productVariant) ? 'Chỉnh sửa Biến thể' : 'Thêm Biến thể')

@section('content')

<div class="col-12 col-md-8 offset-md-2"> <h3 class="mt-3 mb-3">{{ isset($productVariant) ? 'Chỉnh sửa' : 'Thêm mới' }} Biến thể Sản phẩm</h3> <div class="card"> <div class="card-body"> <form action="{{ isset($productVariant) ? route('product-variants.update', $productVariant) : route('product-variants.store') }}" method="POST" enctype="multipart/form-data"> @csrf @if(isset($productVariant)) @method('PUT') @endif
            <div class="mb-3">
                <label for="product_id" class="form-label">Sản phẩm</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $productVariant->product_id ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="color_id" class="form-label">Màu</label>
                    <select name="color_id" id="color_id" class="form-select" required>
                        <option value="">-- Chọn màu --</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ old('color_id', $productVariant->color_id ?? '') == $color->id ? 'selected' : '' }}>
                                {{ $color->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="size_id" class="form-label">Kích cỡ</label>
                    <select name="size_id" id="size_id" class="form-select" required>
                        <option value="">-- Chọn kích cỡ --</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ old('size_id', $productVariant->size_id ?? '') == $size->id ? 'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $productVariant->price ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Số lượng</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $productVariant->quantity ?? 0) }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh</label>
                @if(isset($productVariant) && $productVariant->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $productVariant->image) }}" alt="Ảnh hiện tại" width="100">
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('product-variants.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                <button type="submit" class="btn btn-primary">{{ isset($productVariant) ? 'Cập nhật' : 'Thêm' }}</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
