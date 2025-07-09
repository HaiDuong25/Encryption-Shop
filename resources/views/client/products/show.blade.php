@extends('client.layout.main')

@section('content')
<style>
    .product-detail-page {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
    }

    .main-image {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 10px;
        object-fit: contain;
    }

    .thumbnail-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .thumbnail-list img {
        width: 60px;
        height: 60px;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 5px;
        flex-shrink: 0;
    }

    .thumbnail-list img.active {
        border: 2px solid #ee4d2d;
    }

    .variant-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .variant-row .option-label {
        margin-right: 10px;
        font-weight: bold;
        width: 80px;
    }

    .variant-row .variant-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .variant-options input[type="radio"] {
        display: none;
    }

    .variant-options label {
        border: 1px solid #ccc;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
        background-color: #f8f8f8;
    }

    .variant-options input[type="radio"]:checked+label {
        background-color: #ee4d2d;
        color: white;
        border-color: #ee4d2d;
    }

    .btn-buy {
        background-color: #ee4d2d;
        color: white;
        border: none;
        margin-right: 10px;
    }

    .related-products .card {
        border: none;
        transition: 0.3s;
    }

    .related-products .card:hover {
        transform: translateY(-5px);
    }

    #quantity::-webkit-inner-spin-button,
    #quantity::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .product-info-block {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .product-info-block h4,
    .product-info-block h5 {
        font-size: 18px;
        font-weight: 600;
        padding-bottom: 10px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .product-description {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        font-size: 15px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .table.table-bordered {
        font-size: 15px;
        width: 100%;
        margin-bottom: 0;
    }

    .table.table-bordered th,
    .table.table-bordered td {
        padding: 6px 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table.table-bordered th {
        background-color: #f9f9f9;
        font-weight: 600;
        width: 180px;
    }

    del {
        font-size: 14px;
        color: #999;
    }

    .theme-color {
        color: #ee4d2d !important;
    }

    @media (max-width: 767.98px) {
        .product-detail-page {
            padding: 15px;
        }

        .main-image {
            max-height: 350px;
        }

        .thumbnail-list {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 5px;
        }

        .thumbnail-list img {
            width: 50px;
            height: 50px;
        }

        .variant-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .variant-row .option-label {
            margin-bottom: 5px;
            width: auto;
        }

        .price-area .price,
        .price-area .old-price {
            font-size: 20px !important;
        }

        .product-description {
            font-size: 14px;
        }

        .btn-buy {
            flex: 1;
            text-align: center;
        }

        .mb-4.d-flex {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<div class="container mt-4 mb-5">
    <div class="row product-detail-page">
        <div class="col-md-5">
            <div class="position-relative" id="imageViewerWrapper">
                <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y" style="z-index: 10;" onclick="prevImage()">&#10094;</button>

                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="main-image img-fluid" alt="{{ $product->name }}">

                <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y" style="z-index: 10;" onclick="nextImage()">&#10095;</button>
            </div>
            <div class="thumbnail-list">
                <img src="{{ asset('storage/' . $product->image) }}" class="thumb active" onclick="changeImage(this)">
                @if($product->gallery)
                @php
                $galleryImages = json_decode($product->gallery);
                $totalImages = count($galleryImages);
                @endphp

                @foreach ($galleryImages as $index => $img)
                <img src="{{ asset('storage/' . $img) }}"
                    class="thumb {{ $index > 4 ? 'd-none' : '' }}"
                    onclick="changeImage(this)"
                    data-index="{{ $index + 1 }}"> {{-- +1 vì ảnh chính là index 0 --}}
                @endforeach

                @if ($totalImages > 4)
                <div class="position-relative more-thumbs" onclick="changeImageByIndex(5)">
                    <img src="{{ asset('storage/' . $galleryImages[4]) }}" class="thumb" style="opacity: 0.6;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold"
                        style="background-color: rgba(0,0,0,0.5); font-size: 14px;">
                        +{{ $totalImages - 4 }}
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        <div class="col-md-7">
            <h4 class="fw-bold">{{ $product->name }}</h4>
            <div class="d-flex align-items-center mb-2">
                @php
                $avgRate = round($product->rates->where('status',1)->avg('score') * 2) / 2;

                $sizes = $product->variants->flatMap(function ($variant) {
                return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Size');
                })->unique('id');

                $colors = $product->variants->flatMap(function ($variant) {
                return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Màu');
                })->unique('id');
                @endphp
                <ul class="rating list-inline me-2 mb-0">
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="list-inline-item">
                        @if ($avgRate >= $i)
                        <i data-feather="star" class="fill text-warning"></i>
                        @elseif ($avgRate == ($i - 0.5))
                        <i data-feather="star-half" class="text-warning"></i>
                        @else
                        <i data-feather="star" class="text-muted"></i>
                        @endif
                        </li>
                        @endfor
                </ul>
                <span class="text-muted">({{ number_format($avgRate, 1) }}/5)</span>
            </div>

            <p>Danh mục: <strong>{{ $product->category->name ?? 'Chưa phân loại' }}</strong></p>

            <div class="price-area mb-3">
                @if ($product->compare_price && $product->compare_price < $product->price)
                    <span class="price fs-3 text-danger fw-bold">{{ number_format($product->compare_price) }} đ</span>
                    <del class="old-price text-muted ms-2">{{ number_format($product->price) }} đ</del>
                    @else
                    <span class="price fs-3 text-danger fw-bold">{{ number_format($product->price) }} đ</span>
                    @endif
            </div>

                @php
        $totalStock = $product->variants->sum('stock');
    @endphp
    <p class="mb-2 text-muted" id="stock-info" data-stock="{{ $totalStock }}">
        Số lượng còn lại: <strong>{{ $totalStock }}</strong>
    </p>

            @if ($sizes->count())
            <div class="variant-row mb-3 d-flex align-items-center">
                <span class="option-label">Size:</span>
                <div class="variant-options">
                    @foreach ($sizes as $size)
                    <div>
                        <input type="radio" name="size" id="size-{{ $size->id }}">
                        <label for="size-{{ $size->id }}">{{ $size->value }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($colors->count())
            <div class="variant-row mb-3 d-flex align-items-center">
                <span class="option-label">Màu sắc:</span>
                <div class="variant-options">
                    @foreach ($colors as $color)
                    <div>
                        <input type="radio" name="color" id="color-{{ $color->id }}">
                        <label for="color-{{ $color->id }}">{{ $color->value }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-3 d-flex align-items-center">
                <label class="me-2 fw-bold">Số lượng:</label>
                <div class="d-flex align-items-center border rounded px-2" style="width: fit-content;">
                    <button type="button" class="btn p-1 px-2 border-0 bg-white" onclick="changeQty(-1)">&#8722;</button>
                    <input type="number" id="quantity" class="form-control text-center border-0" value="1" min="1" style="width: 50px;">
                    <button type="button" class="btn p-1 px-2 border-0 bg-white" onclick="changeQty(1)">&#43;</button>
                </div>
            </div>
            <div class="mb-2" id="expected-price-block">
                <span class="fw-bold">Giá dự kiến:</span> <span id="expected-price" class="text-danger fw-bold">{{ number_format($product->compare_price && $product->compare_price < $product->price ? $product->compare_price : $product->price) }}</span> đ
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="mb-4 d-flex" style="gap:10px;">
                <form id="buy-now-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="quantity" id="form-quantity" value="1">
                    <input type="hidden" name="variant_id" id="form-variant-id" value="">
                    <button type="submit" class="btn btn-buy px-4 py-2">Thêm vào giỏ hàng</button>
                </form>
                <button class="btn btn-buy px-4 py-2" type="button" id="buy-now-btn">Mua ngay</button>
            </div>
        </div>
    </div>

    <div class="product-info-block mt-5">
        <h4>Chi tiết sản phẩm</h4>
        <div class="table-responsive mb-3">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Danh mục</th>
                        <td>{{ $product->category->name ?? 'Chưa phân loại' }}</td>
                    </tr>
                    <tr>
                        <th>Thương hiệu</th>
                        <td>{{ $product->brand->name ?? 'Chưa rõ' }}</td>
                    </tr>
                    <tr>
                        <th>Xuất xứ</th>
                        <td>Việt Nam</td>
                    </tr>
                    <tr>
                        <th>Chất liệu</th>
                        <td>{{ $product->material ?? 'Đang cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <td>{{ explode('-', $product->variants->first()->sku)[0] ?? 'Không rõ' }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div class="product-info-block">
        <h5>Mô tả sản phẩm</h5>
        <div class="product-description">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    @if (isset($relatedProducts) && $relatedProducts->count())
    <div class="related-products mt-5">
        <h4 class="fw-bold mb-3">Sản phẩm liên quan</h4>
        <div class="row">
            @foreach ($relatedProducts as $item)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h6 class="card-title">{{ $item->name }}</h6>
                        @if($item->compare_price && $item->compare_price < $item->price)
                            <p>
                                <span class="text-danger fw-bold">{{ number_format($item->compare_price) }} đ</span>
                                <del class="text-muted ms-1">{{ number_format($item->price) }} đ</del>
                            </p>
                            @else
                            <p class="text-danger fw-bold">{{ number_format($item->price) }} đ</p>
                            @endif

                            <a href="{{ route('client.products.show', $item->id) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let imageList = [];
    let currentIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo danh sách ảnh từ thumbnails
        imageList = Array.from(document.querySelectorAll('.thumbnail-list img')).map(img => img.src);
        updateMainImage();

        // Gán lại sự kiện thumbnail
        document.querySelectorAll('.thumbnail-list img').forEach((img, index) => {
            img.addEventListener('click', function() {
                currentIndex = index;
                updateMainImage();
            });
        });

        document.querySelectorAll('input[name="size"], input[name="color"]').forEach(input => {
            input.addEventListener('change', fetchStock);
        });
    });

    function updateMainImage() {
        const main = document.getElementById('mainImage');
        const thumbs = document.querySelectorAll('.thumbnail-list img');
        main.src = imageList[currentIndex];

        thumbs.forEach(img => img.classList.remove('active'));
        if (thumbs[currentIndex]) thumbs[currentIndex].classList.add('active');
    }

    function nextImage() {
        if (currentIndex < imageList.length - 1) {
            currentIndex++;
            updateMainImage();
        }
    }

    function prevImage() {
        if (currentIndex > 0) {
            currentIndex--;
            updateMainImage();
        }
    }

    function getUnitPrice() {
        // Lấy giá ưu tiên compare_price nếu có, không thì lấy price
        return {{ $product->compare_price && $product->compare_price < $product->price ? $product->compare_price : $product->price }};
    }
    function updateExpectedPrice() {
        const qty = parseInt(document.getElementById('quantity').value) || 1;
        const price = getUnitPrice() * qty;
        document.getElementById('expected-price').textContent = price.toLocaleString('vi-VN');
    }
    function changeQty(delta) {
        const input = document.getElementById('quantity');
        let current = parseInt(input.value) || 1;
        current += delta;
        if (current < 1) current = 1;
        input.value = current;
        document.getElementById('form-quantity').value = current;
        updateExpectedPrice();
    }
    document.getElementById('quantity').addEventListener('input', function() {
        let val = parseInt(this.value) || 1;
        if (val < 1) val = 1;
        this.value = val;
        document.getElementById('form-quantity').value = val;
        updateExpectedPrice();
    });
    // Khởi tạo giá dự kiến khi load trang
    document.addEventListener('DOMContentLoaded', updateExpectedPrice);
    function addToCart() {
        const form = document.getElementById('buy-now-form');
        const formData = new FormData(form);
        const alertBox = document.getElementById('add-to-cart-success');
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.status === 'success') {
                alertBox.textContent = 'Thêm vào giỏ hàng thành công!';
                alertBox.className = 'alert alert-success mt-2';
            } else {
                alertBox.textContent = data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
                alertBox.className = 'alert alert-danger mt-2';
            }
            alertBox.classList.remove('d-none');
            setTimeout(() => alertBox.classList.add('d-none'), 2000);
        })
        .catch(() => {
            alertBox.textContent = 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
            alertBox.className = 'alert alert-danger mt-2';
            alertBox.classList.remove('d-none');
            setTimeout(() => alertBox.classList.add('d-none'), 2000);
        });
    }

    // Tạm thời: Nút "Mua ngay" chỉ hiển thị thông báo, sau này bạn có thể bổ sung logic chuyển hướng hoặc xử lý khác
    document.getElementById('buy-now-btn').addEventListener('click', function() {
        const alertBox = document.getElementById('add-to-cart-success');
        alertBox.textContent = 'Chức năng Mua ngay sẽ được cập nhật sau!';
        alertBox.className = 'alert alert-info mt-2';
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 2000);
    });

    function getSelectedVariant() {
        const sizeId = document.querySelector('input[name="size"]:checked')?.id.replace('size-', '');
        const colorId = document.querySelector('input[name="color"]:checked')?.id.replace('color-', '');
        return {
            sizeId,
            colorId
        };
    }

    function fetchStock() {
    const { sizeId, colorId } = getSelectedVariant();
    const productId = {{ $product->id }};

    if (!sizeId || !colorId) return;

    fetch(`/get-stock?product_id=${productId}&size_id=${sizeId}&color_id=${colorId}`)
        .then(res => res.json())
        .then(data => {
            const stockEl = document.getElementById('stock-info');
            stockEl.innerHTML = `Số lượng còn lại: <strong>${data.stock}</strong>`;
            stockEl.dataset.stock = data.stock;
        })
        .catch(error => {
            console.error('Lỗi lấy tồn kho:', error);
        });
}

    function changeImageByIndex(index) {
        currentIndex = index;
        updateMainImage();
    }

    document.getElementById('buy-now-form').addEventListener('submit', function(e) {
        // Lấy variant_id dựa trên lựa chọn size và color
        const sizeId = document.querySelector('input[name="size"]:checked')?.id.replace('size-', '');
        const colorId = document.querySelector('input[name="color"]:checked')?.id.replace('color-', '');
        let variantId = '';

        @foreach($product->variants as $variant)
            if ("{{ $variant->attributeValues->where('attribute.name', 'Size')->first()?->id }}" == sizeId
                && "{{ $variant->attributeValues->where('attribute.name', 'Màu')->first()?->id }}" == colorId) {
                variantId = "{{ $variant->id }}";
            }
        @endforeach

        document.getElementById('form-variant-id').value = variantId;
        // Không ngăn reload, để form POST truyền thống
    });

    feather.replace();
</script>
@endpush