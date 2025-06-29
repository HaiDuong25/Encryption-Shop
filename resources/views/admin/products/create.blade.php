@extends('admin.layouts.main')

@section('title', 'Thêm Sản phẩm')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-table shadow-sm">
        <div class="card-body">
            <div class="title-header option-title mb-3">
                <h4 class="mb-0">Thêm sản phẩm mới</h4>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left"></i> Danh sách sản phẩm
                </a>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- THÔNG TIN SẢN PHẨM --}}
                <div class="row g-3 mb-2">
                    <div class="col-lg-7">
                        <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Mã SKU</label>
                        <input type="text" class="form-control" name="sku" value="{{ old('sku') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold">Số lượng</label>
                        <input type="number" class="form-control" name="stock" value="{{ old('stock', 0) }}">
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id" class="form-select">
                            <option value="">--- Chọn ---</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected':'' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Thương hiệu</label>
                        <select name="brand_id" class="form-select">
                            <option value="">--- Chọn ---</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected':'' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Chất liệu</label>
                        <input type="text" class="form-control" name="material" value="{{ old('material') }}">
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Giá mặc định</label>
                        <input type="number" class="form-control" name="price" step="0.01" value="{{ old('price') }}">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="compare_price" step="0.01" value="{{ old('compare_price') }}">
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Ảnh đại diện</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Thư viện ảnh (gallery)</label>
                        <input type="file" class="form-control" name="gallery[]" accept="image/*" multiple>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Mô tả ngắn</label>
                    <textarea class="form-control" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Mô tả chi tiết</label>
                    <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Hiển thị</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="mb-2">
                    <h5 class="text-primary">Thuộc tính & Biến thể</h5>
                    <div class="alert alert-light border mb-3 py-2 small">
                        Chọn <b>nhiều size và màu</b> bằng cách giữ <b>Ctrl</b> (hoặc Command trên Mac) rồi click!<br>
                        Thêm mới size/màu bằng cách nhập rồi bấm nút tương ứng.
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
                    <div class="mt-2">
                        <button type="button" class="btn btn-theme" onclick="renderVariants()">
                            <i data-feather="git-branch"></i> Tạo biến thể
                        </button>
                    </div>
                </div>
                <div id="variant-area" class="mt-3"></div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="x"></i> Huỷ
                    </a>
                    <button type="submit" class="btn btn-success ms-2">
                        <i data-feather="save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function addNewSize() {
    let val = document.getElementById('new-size').value.trim();
    if (!val) return;
    fetch('{{ url("admin/attributes/$sizeAttributeId/values") }}', {
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
    fetch('{{ url("admin/attributes/$colorAttributeId/values") }}', {
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
// Render biến thể ra bảng nhập giá/tồn kho/SKU/ảnh
function renderVariants() {
    let sizes = Array.from(document.getElementById('size-select').selectedOptions).map(o => ({id:o.value, text:o.text}));
    let colors = Array.from(document.getElementById('color-select').selectedOptions).map(o => ({id:o.value, text:o.text}));
    if (sizes.length == 0 || colors.length == 0) {
        document.getElementById('variant-area').innerHTML = '<p class="text-danger">Hãy chọn size và màu!</p>'; return;
    }
    let combos = [];
    sizes.forEach(s => { colors.forEach(c => combos.push([s, c])); });
    let html = `<div class="table-responsive"><table class="table theme-table table-bordered mt-2"><tr class="table-primary">
        <th>STT</th><th>Size</th><th>Màu</th><th>SKU</th><th>Giá</th><th>Tồn kho</th><th>Ảnh</th></tr>`;
    combos.forEach((arr, idx) => {
        html += `<tr>
            <td>${idx+1}</td>
            <td><input type="hidden" name="variant_sizes[${idx}]" value="${arr[0].id}">${arr[0].text}</td>
            <td><input type="hidden" name="variant_colors[${idx}]" value="${arr[1].id}">${arr[1].text}</td>
            <td><input type="text" name="variant_sku[${idx}]" class="form-control form-control-sm" placeholder="SKU"></td>
            <td><input type="number"  name="variant_price[${idx}]" class="form-control form-control-sm" placeholder="Giá"></td>
            <td><input type="number" name="variant_stock[${idx}]" class="form-control form-control-sm" value="0" min="0"></td>
            <td><input type="file" name="variant_image[${idx}]" accept="image/*" class="form-control form-control-sm"></td>
        </tr>`;
    });
    html += `</table>
    <p class="text-muted"><i>Giá để trống sẽ lấy giá mặc định của sản phẩm.</i></p></div>`;
    document.getElementById('variant-area').innerHTML = html;
}

if(window.feather) feather.replace();
</script>
@endsection
