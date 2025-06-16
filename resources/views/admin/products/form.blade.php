@extends('admin.layouts.main')

@section('title', isset($product) ? 'Chỉnh sửa Sản phẩm' : 'Thêm Sản phẩm')

@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-3">{{ isset($product) ? 'Chỉnh sửa' : 'Thêm mới' }} Sản phẩm</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($product))
                @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $product->name ?? '') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh đại diện</label>
                    @if(isset($product) && $product->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh hiện tại" width="120" class="img-thumbnail">
                    </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="description_images" class="form-label">Ảnh mô tả</label>
                    <input type="file" class="form-control @error('description_images') is-invalid @enderror" id="description_images" name="description_images[]" multiple accept="image/*">
                    @error('description_images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('description_images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @if(isset($product) && $product->images->count())
                <div class="mb-3">
                    <label class="form-label">Ảnh mô tả hiện tại</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Ảnh mô tả" width="80" height="80" class="img-thumbnail">
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng</label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="material" class="form-label">Chất liệu</label>
                    <input type="text" class="form-control @error('material') is-invalid @enderror" id="material" name="material" value="{{ old('material', $product->material ?? '') }}">
                    @error('material')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price ?? '') }}">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                        <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}">
                        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $product->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', $product->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="brand_id" class="form-label">Thương hiệu</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr>
                <h3 class="mt-4">Biến thể sản phẩm</h3><br>

                <div id="variant-container">
                    @php
                    $variantsData = isset($product) && $product->variants->count() ? $product->variants : [null];
                    @endphp

                    @foreach($variantsData as $index => $variant)
                    <div class="row mb-3 variant-item">
                        <div class="col-md-3">
                            <label>Màu sắc</label>
                            <select name="variants[{{ $index }}][color_id]" class="form-select @error("variants.$index.color_id") is-invalid @enderror">
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}" {{ isset($variant) && $variant->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                @endforeach
                            </select>
                            @error("variants.$index.color_id")<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label>Kích cỡ</label>
                            <select name="variants[{{ $index }}][size_id]" class="form-select @error("variants.$index.size_id") is-invalid @enderror">
                                @foreach($sizes as $size)
                                <option value="{{ $size->id }}" {{ isset($variant) && $variant->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                @endforeach
                            </select>
                            @error("variants.$index.size_id")<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
