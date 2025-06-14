@extends('admin.layouts.main')

@section('title', isset($product) ? 'Chỉnh sửa Sản phẩm' : 'Thêm Sản phẩm')

@section('content')
<div class="col-12 col-md-8 offset-md-2">
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
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <!-- Ảnh đại diện -->
                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh đại diện</label>
                    @if(isset($product) && $product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh hiện tại" width="120" class="img-thumbnail">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <!-- Ảnh mô tả nhiều -->
                <div class="mb-3">
                    <label for="description_images" class="form-label">Ảnh mô tả (có thể chọn nhiều ảnh)</label>
                    <input type="file" class="form-control" id="description_images" name="description_images[]" multiple accept="image/*">
                </div>

                @if(isset($product) && $product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Ảnh mô tả hiện tại</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Ảnh mô tả" width="80" height="80" class="img-thumbnail">
                            @endforeach
                        </div>
                        <small class="text-muted">Nếu bạn tải ảnh mới lên, ảnh mô tả mới sẽ được thêm vào danh sách hiện tại.</small>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng</label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="{{ old('quantity', $product->quantity ?? 0) }}" required>
                </div>

                <div class="mb-3">
                    <label for="material" class="form-label">Chất liệu</label>
                    <input type="text" class="form-control" id="material" name="material"
                        value="{{ old('material', $product->material ?? '') }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                            value="{{ old('price', $product->price ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price"
                            value="{{ old('sale_price', $product->sale_price ?? '') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" id="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', $product->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', $product->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-6 mb-3">
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
