@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa Sản phẩm')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Chỉnh sửa sản phẩm</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Thông tin sản phẩm --}}
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Tên sản phẩm *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Thương hiệu</label>
                        <select name="brand_id" class="form-select">
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- ... Các trường khác nếu có ... --}}

                <hr>
                <!-- BIẾN THỂ SẢN PHẨM -->
                <h5 class="mt-4 mb-2 text-primary">Thuộc tính & Biến thể</h5>
                <div class="alert alert-info small">
                    Nếu bạn <b>thay đổi thuộc tính size/màu và bấm "Sinh lại biến thể"</b> thì các biến thể cũ sẽ bị ghi đè.<br>
                    Nếu chỉ muốn sửa nhanh các giá trị (giá, tồn kho, SKU, ảnh) của biến thể hiện có thì sửa ở bảng bên dưới và bấm "Cập nhật".
                </div>
                <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Size:</label>
                            <div class="input-group mb-2">
                                <select name="sizes[]" id="size-select" class="form-select" multiple>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" id="new-size" class="form-control" placeholder="Thêm size mới">
                                <button type="button" class="btn btn-outline-primary" onclick="addNewSize()">Thêm size</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Màu:</label>
                            <div class="input-group mb-2">
                                <select name="colors[]" id="color-select" class="form-select" multiple>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="text" id="new-color" class="form-control" placeholder="Thêm màu mới">
                                <button type="button" class="btn btn-outline-primary" onclick="addNewColor()">Thêm màu</button>
                            </div>
                        </div>
                    </div>
                <div class="mb-2">
                    <button type="button" class="btn btn-secondary" onclick="renderVariants()">Sinh lại biến thể</button>
                </div>

                {{-- BẢNG BIẾN THỂ HIỆN CÓ --}}
                <div id="variant-area">
                    @if($product->variants && count($product->variants))
                    <table class="table table-bordered mt-3">
                        <tr class="table-primary">
                            <th>STT</th>
                            <th>Size</th>
                            <th>Màu</th>
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Ảnh</th>
                        </tr>
                        @foreach($product->variants as $idx => $variant)
                        @php
                        $sizeValue = $variant->attributeValues->first(function($attrVal) {
                        return $attrVal->attribute->name === 'Size';
                        });
                        $colorValue = $variant->attributeValues->first(function($attrVal) {
                        return $attrVal->attribute->name === 'Màu';
                        });
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


                    </table>
                    <p class="text-muted"><i>Nếu bạn bấm "Sinh lại biến thể", các biến thể này sẽ bị ghi đè theo tổ hợp size-màu mới.</i></p>
                    @else
                    <p class="text-warning">Chưa có biến thể. Hãy chọn size và màu rồi bấm "Sinh biến thể".</p>
                    @endif
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Huỷ</a>
                    <button type="submit" class="btn btn-primary ms-2">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script JS ngay trong file, không dùng @push --}}
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
                body: JSON.stringify({
                    value: val
                })
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
                body: JSON.stringify({
                    value: val
                })
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
        let sizes = Array.from(document.getElementById('size-select').selectedOptions).map(o => ({
            id: o.value,
            text: o.text
        }));
        let colors = Array.from(document.getElementById('color-select').selectedOptions).map(o => ({
            id: o.value,
            text: o.text
        }));
        if (sizes.length == 0 || colors.length == 0) {
            document.getElementById('variant-area').innerHTML = '<p class="text-danger">Hãy chọn size và màu!</p>';
            return;
        }
        let combos = [];
        sizes.forEach(s => {
            colors.forEach(c => combos.push([s, c]));
        });
        let html = `<table class="table table-bordered mt-3"><tr class="table-primary"><th>STT</th><th>Size</th><th>Màu</th><th>SKU</th><th>Giá</th><th>Tồn kho</th><th>Ảnh</th></tr>`;
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
        html += `</table>
    <p class="text-muted"><i>Giá để trống sẽ lấy giá mặc định của sản phẩm.</i></p>`;
        document.getElementById('variant-area').innerHTML = html;
    }
</script>
@endsection
