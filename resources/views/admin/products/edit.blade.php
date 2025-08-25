@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa Sản phẩm')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Chỉnh sửa sản phẩm</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="productEditForm" action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Đã có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div id="alert-container"></div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Tên sản phẩm *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Thương hiệu</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Giá sản phẩm</label>
                        <input type="number" step="1" name="price" class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', (int)$product->price) }}">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Giá khuyến mãi</label>
                        <input type="number" step="1" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror"
                            value="{{ old('sale_price', (int)$product->sale_price) }}">
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ảnh đại diện</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$product->image) }}" width="100">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Thư viện ảnh (gallery)</label>
                        <div class="row mb-2">
                    @if($product->gallery && is_array($product->gallery))
                        @foreach($product->gallery as $imgIdx => $img)
                        <div class="col-auto position-relative mb-2">
                            <img src="{{ asset('storage/'.$img) }}" class="border rounded" width="100" style="height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-gallery-btn"
                                data-img="{{ $img }}" style="padding:2px 6px; font-size:12px;">&times;</button>
                            <input type="hidden" name="old_gallery[]" value="{{ $img }}">
                        </div>
                        @endforeach
                    @endif
                </div>

                        <input type="file" class="form-control @error('gallery.*') is-invalid @enderror" name="gallery[]" accept="image/*" multiple>
                        @error('gallery.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="remove_gallery" id="remove_gallery">
                    </div>
                </div>

                <hr>
                <h5 class="mt-4 mb-2 text-primary">Thuộc tính & Biến thể</h5>
                <div class="alert alert-info small">
                    Nếu bạn <b>thay đổi thuộc tính size/màu và bấm "Tạo lại biến thể"</b> thì các biến thể cũ sẽ bị ghi đè.<br>
                    Nếu chỉ muốn sửa nhanh các giá trị (giá, tồn kho, SKU, ảnh) của biến thể hiện có thì sửa ở bảng bên dưới và bấm "Cập nhật".
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Size:</label>
                        <div class="input-group mb-2">
                            <select name="sizes[]" id="size-select" class="form-select @error('sizes') is-invalid @enderror" multiple>
                                @foreach($sizes as $size)
                                @php
                                    $isSelected = $product->variants->contains(function($variant) use ($size) {
                                        return $variant->attributeValues->contains('id', $size->id);
                                    });
                                @endphp
                                <option value="{{ $size->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $size->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('sizes')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="input-group">
                            <input type="text" id="new-size" class="form-control" placeholder="Thêm size mới">
                            <button type="button" class="btn btn-outline-primary" onclick="addNewSize()">Thêm size</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Màu:</label>
                        <div class="input-group mb-2">
                            <select name="colors[]" id="color-select" class="form-select @error('colors') is-invalid @enderror" multiple>
                                @foreach($colors as $color)
                                @php
                                    $isSelected = $product->variants->contains(function($variant) use ($color) {
                                        return $variant->attributeValues->contains('id', $color->id);
                                    });
                                @endphp
                                <option value="{{ $color->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $color->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('colors')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="input-group">
                            <input type="text" id="new-color" class="form-control" placeholder="Thêm màu mới">
                            <button type="button" class="btn btn-outline-primary" onclick="addNewColor()">Thêm màu</button>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <button type="button" class="btn btn-secondary mt-2" onclick="renderVariants()">Tạo lại biến thể</button>
                </div>

                <div id="variant-area">
                    @if($product->variants && count($product->variants))
                    <table class="table table-bordered mt-3">
                        <tr class="table-primary">
                            <th>STT</th>
                            <th>Size</th>
                            <th>Màu</th>
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th>Tồn kho</th>
                            <th>Ảnh</th>
                        </tr>
                        @foreach($product->variants as $idx => $variant)
                        @php
                        $sizeValue = $variant->attributeValues->first(fn($v) => $v->attribute->name === 'Size');
                        $colorValue = $variant->attributeValues->first(fn($v) => $v->attribute->name === 'Màu');
                        @endphp
                        <tr>
                            <td>{{ $idx+1 }}</td>
                            <td>
                                <input type="hidden" name="old_variant_ids[]" value="{{ $variant->id }}">
                                {{ $sizeValue?->value }}
                            </td>
                            <td>{{ $colorValue?->value }}</td>
                            <td>
                                <input type="text" name="old_variant_sku[{{ $idx }}]" class="form-control" value="{{ $variant->sku }}">
                            </td>
                            <td>
                                <input type="number" step="1" name="old_variant_price[{{ $idx }}]" class="form-control" value="{{ (int)($variant->price ?: $product->price) }}">
                            </td>
                            <td>
                                <input type="number" step="1" name="old_variant_sale_price[{{ $idx }}]" class="form-control" value="{{ (int)$variant->sale_price }}">
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
                    <p class="text-muted"><i>Nếu bạn bấm "Tạo lại biến thể", các biến thể này sẽ bị ghi đè theo tổ hợp size-màu mới.</i></p>
                    @else
                    <p class="text-warning">Chưa có biến thể. Hãy chọn size và màu rồi bấm "Tạo biến thể".</p>
                    @endif
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary ms-2 col-md-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary ms-2 col-md-2" id="submitBtn">Cập nhật</button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>
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
        let html = `<table class="table table-bordered mt-3"><tr class="table-primary"><th>STT</th><th>Size</th><th>Màu</th><th>SKU</th><th>Giá</th><th>Giá KM</th><th>Tồn kho</th><th>Ảnh</th></tr>`;
        combos.forEach((arr, idx) => {
            html += `<tr>
            <td>${idx+1}</td>
            <td><input type="hidden" name="variant_sizes[${idx}]" value="${arr[0].id}">${arr[0].text}</td>
            <td><input type="hidden" name="variant_colors[${idx}]" value="${arr[1].id}">${arr[1].text}</td>
            <td><input type="text" name="variant_sku[${idx}]" class="form-control" placeholder="SKU"></td>
            <td><input type="number" step="1" name="variant_price[${idx}]" class="form-control" placeholder="Giá"></td>
            <td><input type="number" step="1" name="variant_sale_price[${idx}]" class="form-control" placeholder="Giá KM"></td>
            <td><input type="number" name="variant_stock[${idx}]" class="form-control" value="0" min="0"></td>
            <td><input type="file" name="variant_image[${idx}]" accept="image/*"></td>
        </tr>`;
        });
        html += `</table>
    <p class="text-muted"><i>Giá để trống sẽ lấy giá mặc định của sản phẩm.</i></p>`;
        document.getElementById('variant-area').innerHTML = html;
    }

    // Xóa ảnh gallery khỏi giao diện và lưu tên ảnh vào input hidden
    document.addEventListener('DOMContentLoaded', function() {
        let removeGallery = [];
        document.querySelectorAll('.delete-gallery-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let img = this.getAttribute('data-img');
                removeGallery.push(img);
                document.getElementById('remove_gallery').value = removeGallery.join(',');
                this.parentElement.remove();
            });
        });
    });

    // AJAX form submission
    document.getElementById('productEditForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('alert-container');

        // Show loading state
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i data-feather="loader" class="rotating"></i> Đang cập nhật...';
        submitBtn.disabled = true;

        // Clear previous alerts
        alertContainer.innerHTML = '';

        // Create FormData for file upload
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                // Redirect after success
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("products.index") }}';
                }, 1500);
            } else {
                // Show error messages
                if (data.errors) {
                    let errorHtml = '<div class="alert alert-danger alert-dismissible fade show"><strong>Đã có lỗi xảy ra:</strong><ul class="mb-0 mt-2">';
                    Object.values(data.errors).flat().forEach(error => {
                        errorHtml += `<li>${error}</li>`;
                    });
                    errorHtml += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                    alertContainer.innerHTML = errorHtml;
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            ${data.message || 'Có lỗi xảy ra khi cập nhật sản phẩm!'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }

                // Restore button state
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
                feather.replace();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show">
                    Có lỗi xảy ra khi cập nhật sản phẩm!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Restore button state
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
            feather.replace();
        });
    });
</script>
<style>
    .rotating {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
