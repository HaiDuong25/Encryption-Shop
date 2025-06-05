@extends('admin.layouts.main')

@section('title', isset($product) ? 'Chỉnh sửa Sản phẩm' : 'Thêm Sản phẩm')

@section('content')
<div class="col-12 col-lg-8">
    <h3 class="my-3">{{ isset($product) ? 'Sửa Sản phẩm' : 'Thêm Sản phẩm' }}</h3>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($product))
        @method('PUT')
        @endif

        {{-- Tên --}}
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        {{-- Ảnh --}}
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if(isset($product) && $product->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh sản phẩm" style="max-width: 150px;">
            </div>
            @endif
        </div>

        {{-- Số lượng --}}
        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" id="quantity" name="quantity"
                value="{{ old('quantity', $product->quantity ?? 0) }}" required>
        </div>

        {{-- Chất liệu --}}
        <div class="mb-3">
            <label for="material" class="form-label">Chất liệu</label>
            <input type="text" class="form-control" id="material" name="material"
                value="{{ old('material', $product->material ?? '') }}">
        </div>

        {{-- Giá --}}
        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price"
                value="{{ old('price', $product->price ?? '') }}" required>
        </div>

        {{-- Giá KM --}}
        <div class="mb-3">
            <label for="sale_price" class="form-label">Giá khuyến mãi</label>
            <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price"
                value="{{ old('sale_price', $product->sale_price ?? '') }}">
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" name="description" id="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        {{-- Trạng thái --}}
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="1" {{ old('status', $product->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $product->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        {{-- Danh mục --}}
        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục</label>
            <select name="category_id" class="form-select" required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Thương hiệu --}}
        <div class="mb-3">
            <label for="brand_id" class="form-label">Thương hiệu</label>
            <select name="brand_id" class="form-select" required>
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}"
                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection
