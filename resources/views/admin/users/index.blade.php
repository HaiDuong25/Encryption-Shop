@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa Sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-flex align-items-center justify-content-between">
                <h5>Chỉnh sửa sản phẩm</h5>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left"></i> Quay lại
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                @method('PUT')

                {{-- Thông tin sản phẩm --}}
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Tên sản phẩm *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name',$product->name) }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Mã SKU</label>
                        <input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brand_id" class="form-select">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Số lượng tổng</label>
                        <input type="number" class="form-control" name="stock" value="{{ old('stock', $product->stock) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">Giá mặc định</label>
                        <input type="number" class="form-control" name="price" value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">Ảnh đại diện</label>
                        @if($product->image)
                            <div class="mb-1"><img src="{{ asset('storage/'.$product->image) }}" width="70"></div>
                        @endif
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Thư viện ảnh (gallery)</label>
                        <input type="file" class="form-control" name="gallery[]" multiple accept="image/*">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-lg-12">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-lg-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $product->status)=='active'?'selected':'' }}>Hiển thị</option>
                            <option value="inactive" {{ old('status', $product->status)=='inactive'?'selected':'' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-lg-3 align-self-end">
                        <input type="checkbox" class="form-check-input me-1" name="is_featured" value="1" {{ old('is_featured', $product->is_featured)?'checked':'' }}>
                        <label class="form-check-label">Nổi bật</label>
                    </div>
                </div>

                <hr>
                <!-- BIẾN THỂ SẢN PHẨM -->
                <div class="alert alert-theme mb-3 small">
                    Nếu bạn <b>thay đổi thuộc tính size/màu và bấm "Sinh lại biến thể"</b> thì các biến thể cũ sẽ bị ghi đè.<br>
                    Nếu chỉ muốn sửa nhanh các giá trị (giá, tồn kho, SKU, ảnh) của biến thể hiện có thì sửa ở bảng bên dưới và bấm <b>Cập nhật</b>.
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6 mb-2">
                        <label class="form-label">Chọn Size:</label>
                        <div class="input-group mb-2">
                            <select name="sizes[]" id="size-select" class="form-select" multiple>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}"
                                    {{ collect($product->variants)->pluck('size_id')->contains($size->id) ? 'selected' : '' }}>
                                        {{ $size->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" id="new-size" class="form-control" placeholder="Thêm size mới">
                            <button type="button" class="btn btn-outline-primary" onclick="addNewSize()">Thêm size</button>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label class="form-label">Chọn Màu:</label>
                        <div class="input-group mb-2">
                            <select name="colors[]" id="color-select" class="form-select" multiple>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}"
                                    {{ collect($product->variants)->pluck('color_id')->contains($color->id) ? 'selected' : '' }}>
                                        {{ $color->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" id="new-color" class="form-control" placeholder="Thêm màu mới">
                            <button type="button" class="btn btn-outline-primary" onclick="addNewColor()">Thêm màu</button>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-theme" onclick="renderVariants()">Sinh lại biến thể</button>
                </div>

                {{-- BẢNG BIẾN THỂ --}}
                <div id="variant-area">
                    @if($product->variants && count($product->variants))
                        <div class="table-responsive">
                        <table class="table theme-table">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Ảnh</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->variants as $idx => $variant)
                                @php
                                    $sizeValue = $variant->attributeValues->first(function($av){ return $av->attribute->name === 'Size'; });
                                    $colorValue = $variant->attributeValues->first(function($av){ return $av->attribute->name === 'Màu'; });
                                @endphp
                                <tr>
                                    <td>{{ $idx+1 }}</td>
                                    <td>
                                        <input type="hidden" name="old_variant_ids[]" value="{{ $variant->id }}">
                                        {{ $sizeValue ? $sizeValue->value : '' }}
                                    </td>
                                    <td>
                                        {{ $colorValue ? $colorValue->value : '' }}
                                    </td>
                                    <td>
                                        <input type="text" name="old_variant_sku[{{ $idx }}]" class="form-control" value="{{ $variant->sku }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="old_variant_price[{{ $idx }}]" class="form-control" value="{{ $variant->display_price }}">
                                    </td>
                                    <td>
                                        <input type="number" name="old_variant_stock[{{ $idx }}]" class="form-control" value="{{ $variant->stock }}">
                                    </td>
                                    <td>
                                        @if($variant->image)
                                            <img src="{{ asset('storage/'.$variant->image) }}" width="50"><br>
                                        @endif
                                        <input type="file" name="old_variant_image[{{ $idx }}]" accept="image/*">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">Chưa có biến thể. Hãy chọn size và màu rồi bấm <b>Sinh biến thể</b>.</div>
                    @endif
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-theme ms-2"><i data-feather="save"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Biến thể sản phẩm -->
<script>
function addNewSize() {
    let val = document.getElementById('new-size').value.trim();
    if (!val) return;
    fetch('/admin/attributes/{{ $sizeAttributeId }}/values', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({value: val})
    })
    .then(res => res.json())
    .then(data => {
        let option = document.createElement('option');
        option.value = data.id;
        option.text = data.value;
        option.selected = true;
        document.getElementById('size-select').appendChild(option);
        document.getElementById('new-size').value = '';
    });
}
function addNewColor() {
    let val = document.getElementById('new-color').value.trim();
    if (!val) return;
    fetch('/admin/attributes/{{ $colorAttributeId }}/values', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({value: val})
    })
    .then(res => res.json())
    .then(data => {
        let option = document.createElement('option');
        option.value = data.id;
        option.text = data.value;
        option.selected = true;
        document.getElementById('color-select').appendChild(option);
        document.getElementById('new-color').value = '';
    });
}
function renderVariants() {
    let sizes = Array.from(document.getElementById('size-select').selectedOptions).map(o => ({id:o.value, text:o.text}));
    let colors = Array.from(document.getElementById('color-select').selectedOptions).map(o => ({id:o.value, text:o.text}));
    if (sizes.length == 0 || colors.length == 0) {
        document.getElementById('variant-area').innerHTML = '<div class="alert alert-danger mt-3">Hãy chọn size và màu!</div>'; return;
    }
    let combos = [];
    sizes.forEach(s => { colors.forEach(c => combos.push([s, c])); });
    let html = `<div class="table-responsive"><table class="table theme-table"><thead>
    <tr><th>STT</th><th>Size</th><th>Màu</th><th>SKU</th><th>Giá</th><th>Tồn kho</th><th>Ảnh</th></tr>
    </thead><tbody>`;
    combos.forEach((arr, idx) => {
        html += `<tr>
            <td>${idx+1}</td>
            <td><input type="hidden" name="variant_sizes[${idx}]" value="${arr[0].id}">${arr[0].text}</td>
            <td><input type="hidden" name="variant_colors[${idx}]" value="${arr[1].id}">${arr[1].text}</td>
            <td><input type="text" name="variant_sku[${idx}]" class="form-control" placeholder="SKU"></td>
            <td><input type="number" step="0.01" name="variant_price[${idx}]" class="form-control" placeholder="Giá"></td>
            <td><input type="number" name="variant_stock[${idx}]" class="form-control" value="0" min="0"></td>
            <td><input type="file" name="variant_image[${idx}]" accept="image/*"></td>
        </tr>`;
    });
    html += `</tbody></table></div>
    <div class="text-muted"><i>Giá để trống sẽ lấy giá mặc định của sản phẩm.</i></div>`;
    document.getElementById('variant-area').innerHTML = html;
}
</script>
@endsection
