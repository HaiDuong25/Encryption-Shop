@extends('client.layout.main')

@section('content')
    <style>
/* ===========================
   THEME TOKENS
   =========================== */
:root {
  --c-primary: #ee4d2d;
  --c-primary-600: #d94426;
  --c-primary-700: #c53a21;
  --c-danger: #dc3545;
  --c-muted: #6c757d;
  --c-border: #e9ecef;
  --c-bg-soft: #fafafa;
  --radius-lg: 16px;
  --radius-md: 12px;
  --radius-sm: 8px;
  --shadow-sm: 0 2px 10px rgba(0, 0, 0, .06);
  --shadow-md: 0 8px 24px rgba(0, 0, 0, .08);
  --shadow-lg: 0 18px 40px rgba(0, 0, 0, .10);
  --ring: 0 0 0 3px rgba(238, 77, 45, .15);
}

/* ===========================
   PRODUCT DETAIL WRAPPER
   =========================== */
.product-detail-page {
  background-color: #fff;
  padding: 28px;
  border-radius: var(--radius-lg);
  border: 1px solid var(--c-border);
  box-shadow: var(--shadow-sm);
}

/* Title */
.product-detail-page h4.fw-bold {
  font-size: 1.5rem;
  line-height: 1.2;
  margin-bottom: .25rem;
}

/* ===========================
   MAIN IMAGE + NAV BUTTONS
   =========================== */
.main-image {
  width: 100%;
  border-radius: var(--radius-md);
  margin-bottom: 12px;
  object-fit: contain;
  background: #fff;
  box-shadow: var(--shadow-sm);
}

#imageViewerWrapper .btn.btn-light {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  border: 1px solid rgba(0,0,0,.06);
  box-shadow: var(--shadow-sm);
  backdrop-filter: blur(6px);
  background: rgba(255,255,255,.9);
  transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
}
#imageViewerWrapper .btn.btn-light:hover {
  transform: scale(1.06);
  box-shadow: var(--shadow-md);
  background: #fff;
}

/* ===========================
   THUMBNAILS
   =========================== */
.thumbnail-list {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.thumbnail-list img {
  width: 64px;
  height: 64px;
  border: 1px solid var(--c-border);
  cursor: pointer;
  border-radius: var(--radius-sm);
  flex-shrink: 0;
  object-fit: cover;
  background: #fff;
  transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, opacity .18s ease;
}
.thumbnail-list img:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-sm);
  border-color: #ddd;
}
.thumbnail-list img.active {
  border: 2px solid var(--c-primary);
  box-shadow: var(--ring);
}

.more-thumbs {
  position: relative;
  width: 64px;
  height: 64px;
  border-radius: var(--radius-sm);
  overflow: hidden;
  cursor: pointer;
}
.more-thumbs .thumb {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ===========================
   RATING
   =========================== */
.existing-stars,
.rating .fill,
.rating .text-warning {
  color: #f5c518 !important;
}
.rating .text-muted {
  color: #c9c9c9 !important;
}

/* ===========================
   PRICE AREA
   =========================== */
.price-area .price {
  font-size: 1.875rem !important; /* ~30px */
  font-weight: 800;
  color: var(--c-primary) !important;
  letter-spacing: .2px;
}
.price-area del.old-price {
  font-size: .95rem;
  color: #9aa0a6 !important;
}

/* ===========================
   STOCK
   =========================== */
#stock-info {
  font-size: .95rem;
}
#stock-info strong {
  color: var(--c-primary);
}

/* ===========================
   VARIANT OPTIONS (chips)
   =========================== */
.variant-row {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 8px 12px;
}
.variant-row .option-label {
  margin-right: 6px;
  font-weight: 700;
  width: auto;
  min-width: 60px;
  color: #222;
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
  border: 1px solid #ddd;
  padding: 8px 14px;
  border-radius: 999px;
  cursor: pointer;
  background-color: #fff;
  font-weight: 600;
  font-size: .95rem;
  color: #333;
  transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease, color .18s ease, transform .12s ease;
}
.variant-options label:hover {
  border-color: var(--c-primary);
  box-shadow: var(--ring);
  transform: translateY(-1px);
}

.variant-options input[type="radio"]:checked + label {
  background-color: var(--c-primary);
  color: #fff;
  border-color: var(--c-primary);
  box-shadow: 0 8px 20px rgba(238, 77, 45, .25);
}

/* ===========================
   QUANTITY CONTROL
   =========================== */
#quantity::-webkit-inner-spin-button,
#quantity::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.mb-3 .border.rounded.px-2 {
  border-color: var(--c-border) !important;
  border-radius: 999px !important;
  background: #fff;
  box-shadow: var(--shadow-sm);
}

.mb-3 .btn.p-1.px-2.border-0.bg-white {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  line-height: 1;
  transition: background-color .15s ease, transform .12s ease;
}
.mb-3 .btn.p-1.px-2.border-0.bg-white:hover {
  background-color: var(--c-bg-soft);
}
.mb-3 .btn.p-1.px-2.border-0.bg-white:active {
  transform: scale(.96);
}

#quantity.form-control {
  width: 56px !important;
  height: 40px;
  font-weight: 700;
  font-size: 1rem;
  background: transparent;
}

/* ===========================
   CTA BUTTONS
   =========================== */
.btn-buy {
  background: linear-gradient(180deg, var(--c-primary) 0%, var(--c-primary-600) 100%);
  color: #fff !important;
  border: 0;
  margin-right: 10px;
  padding: 10px 18px;
  border-radius: 12px;
  font-weight: 700;
  letter-spacing: .2px;
  box-shadow: 0 10px 22px rgba(238, 77, 45, .28);
  transform: translateZ(0);
  transition: transform .18s ease, box-shadow .18s ease, filter .18s ease, opacity .18s ease;
}
.btn-buy:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 28px rgba(238, 77, 45, .32);
  filter: brightness(1.03);
}
.btn-buy:active {
  transform: translateY(0);
}

/* ===========================
   WISHLIST (DETAIL PAGE)
   =========================== */
.add-to-wishlist-form .wishlist-btn {
  position: relative;
  border: 1px solid var(--c-primary);
  color: var(--c-primary);
  background: #fff;
  padding: 8px 14px;
  border-radius: 999px;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: var(--shadow-sm);
  transition: background-color .18s ease, color .18s ease, box-shadow .18s ease, transform .12s ease, border-color .18s ease;
}

/* Heart beat just for the first character (the 💖 at the start) */
.add-to-wishlist-form .wishlist-btn::first-letter {
  display: inline-block;
  animation: heartbeat 1.2s infinite;
  transform-origin: center;
}

/* Hover → fill button */
.add-to-wishlist-form .wishlist-btn:hover {
  background: var(--c-primary);
  color: #fff;
  border-color: var(--c-primary);
  box-shadow: 0 10px 22px rgba(238, 77, 45, .25);
  transform: translateY(-1px);
}
.add-to-wishlist-form .wishlist-btn:active {
  transform: translateY(0);
}

/* Keyframes for heart beat */
@keyframes heartbeat {
  0%, 100% { transform: scale(1); filter: none; }
  25% { transform: scale(1.18); filter: drop-shadow(0 2px 6px rgba(238, 77, 45, .35)); }
  50% { transform: scale(0.98); }
  75% { transform: scale(1.12); }
}

/* ===========================
   INFO BLOCKS & TABLE
   =========================== */
.product-info-block {
  background-color: #fff;
  border: 1px solid var(--c-border);
  border-radius: var(--radius-md);
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: var(--shadow-sm);
}

.product-info-block h4,
.product-info-block h5 {
  font-size: 1.125rem;
  font-weight: 800;
  padding-bottom: 12px;
  margin-bottom: 18px;
  border-bottom: 1px dashed var(--c-border);
  display: flex;
  align-items: center;
  gap: 8px;
}
.product-info-block h4::before,
.product-info-block h5::before {
  content: '';
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--c-primary);
  box-shadow: 0 0 0 4px rgba(238, 77, 45, .15);
}

.product-description {
  background-color: #fff;
  border: 1px solid var(--c-border);
  border-radius: var(--radius-md);
  padding: 18px;
  font-size: 15px;
  line-height: 1.75;
  white-space: pre-line;
  word-break: break-word;
}

.table.table-bordered {
  font-size: 15px;
  width: 100%;
  margin-bottom: 0;
  border-color: var(--c-border);
}
.table.table-bordered th,
.table.table-bordered td {
  padding: 10px 14px;
  vertical-align: middle;
  white-space: nowrap;
  border-color: var(--c-border);
}
.table.table-bordered th {
  background-color: #fbfbfb;
  font-weight: 700;
  width: 200px;
}

/* ===========================
   RELATED PRODUCTS
   =========================== */
.related-products .card {
  border: 1px solid var(--c-border);
  border-radius: var(--radius-md);
  overflow: hidden;
  transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  box-shadow: var(--shadow-sm);
}
.related-products .card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
  border-color: #ddd;
}
.related-products .card img {
  aspect-ratio: 1 / 1;
  object-fit: cover;
}

/* ===========================
   MISC
   =========================== */
del { font-size: 14px; color: #999; }
.theme-color { color: var(--c-primary) !important; }

/* Alerts nicer spacing */
.alert { border-radius: 10px; }

/* ===========================
   RESPONSIVE
   =========================== */
@media (max-width: 991.98px) {
  .product-detail-page { padding: 20px; }
}

@media (max-width: 767.98px) {
  .product-detail-page { padding: 16px; }

  .main-image { max-height: 360px; }

  .thumbnail-list {
    overflow-x: auto;
    flex-wrap: nowrap;
    padding-bottom: 6px;
    -webkit-overflow-scrolling: touch;
  }
  .thumbnail-list img,
  .more-thumbs { width: 56px; height: 56px; }

  .variant-row { flex-direction: column; align-items: flex-start; gap: 8px; }
  .variant-row .option-label { margin-bottom: 0; }

  .price-area .price,
  .price-area .old-price { font-size: 1.25rem !important; }

  .product-description { font-size: 14px; }

  .btn-buy { width: 100%; text-align: center; }
  .mb-4.d-flex { flex-direction: column; gap: 10px; }
}
/* ==== IMAGE HOVER-ZOOM (DESKTOP) ==== */
#imageViewerWrapper {
    border-radius: 12px;
    overflow: hidden;           /* Giữ ảnh không tràn khi zoom */
    background: #fafafa;
    position: relative;
}
#imageViewerWrapper .main-image {
    transition: transform 0.15s ease-out;
    transform-origin: center center;
    cursor: zoom-in;
    will-change: transform;
}
#imageViewerWrapper.is-zooming .main-image {
    cursor: zoom-out;
}

/* ==== LIGHTBOX/MODAL ZOOM ==== */
#imageZoomModal .modal-content {
    background: #000;           /* nền tối cho ảnh nổi bật */
}
#zoomModalImage {
    max-height: 85vh;           /* vừa viewport */
    user-select: none;
    -webkit-user-drag: none;
    cursor: zoom-in;
    transition: transform .2s ease-out;
    will-change: transform;
}
#zoomModalImage.is-zoomed {
    cursor: grab;               /* kéo để pan */
}

/* Nút chuyển ảnh vẫn ở trên khi zoom */
#imageViewerWrapper .btn {
    z-index: 10;
}


    </style>

    <div class="container mt-4 mb-5">
        <div class="row product-detail-page">
            <div class="col-md-5">
                <div class="position-relative" id="imageViewerWrapper">
                    <button class="btn btn-light position-absolute top-50 start-0 translate-middle-y" style="z-index: 10;"
                        onclick="prevImage()">&#10094;</button>

                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="main-image img-fluid"
                        alt="{{ $product->name }}">

                    <button class="btn btn-light position-absolute top-50 end-0 translate-middle-y" style="z-index: 10;"
                        onclick="nextImage()">&#10095;</button>
                </div>
                <div class="thumbnail-list">
                    <img src="{{ asset('storage/' . $product->image) }}" class="thumb active" onclick="changeImage(this)">
                    @if ($product->gallery)
                        @php
                            $galleryImages = json_decode($product->gallery, true); // thêm `true` để trả về mảng
                        @endphp
                        @if (is_array($galleryImages) && count($galleryImages))
                            @php
                                $totalImages = count($galleryImages);
                            @endphp
                            @foreach ($galleryImages as $index => $img)
                                <img src="{{ asset('storage/' . $img) }}" class="thumb {{ $index > 4 ? 'd-none' : '' }}"
                                    onclick="changeImage(this)" data-index="{{ $index + 1 }}">
                            @endforeach

                            @if ($totalImages > 4)
                                <div class="position-relative more-thumbs" onclick="changeImageByIndex(5)">
                                    <img src="{{ asset('storage/' . $galleryImages[4]) }}" class="thumb"
                                        style="opacity: 0.6;">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="background-color: rgba(0,0,0,0.5); font-size: 14px;">
                                        +{{ $totalImages - 4 }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-md-7">
                <h4 class="fw-bold">{{ $product->name }}</h4>
                <div class="d-flex align-items-center mb-2">
                    @php
                        $avgRate = round($product->rates->where('status', 1)->avg('score') * 2) / 2;

                        $sizes = $product->variants
                            ->flatMap(function ($variant) {
                                return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Size');
                            })
                            ->unique('id');

                        $colors = $product->variants
                            ->flatMap(function ($variant) {
                                return $variant->attributeValues->filter(fn($val) => $val->attribute->name === 'Màu');
                            })
                            ->unique('id');
                    @endphp
                    <ul class="rating list-inline me-2 mb-0">
                        @for ($i = 1; $i <= 5; $i++)
                            <li class="list-inline-item">
                                @if ($avgRate >= $i)
                                    <i data-feather="star" class="fill text-warning"></i>
                                @elseif ($avgRate == $i - 0.5)
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
                <p>Thương hiệu: <strong> {{ $product->brand->name ?? 'Chưa rõ' }}
                    </strong></p>

                <div class="price-area mb-3" id="price-display">
                    @if ($product->sale_price && $product->sale_price < $product->price)
                        <span class="price fs-3 text-danger fw-bold"
                            id="current-price">{{ format_vnd($product->sale_price) }} đ</span>
                        <del class="old-price text-muted ms-2" id="original-price">{{ format_vnd($product->price) }}
                            đ</del>
                    @else
                        <span class="price fs-3 text-danger fw-bold"
                            id="current-price">{{ format_vnd($product->price) }} đ</span>
                        <del class="old-price text-muted ms-2 d-none"
                            id="original-price">{{ format_vnd($product->price) }} đ</del>
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
                        <button type="button" class="btn p-1 px-2 border-0 bg-white"
                            onclick="changeQty(-1)">&#8722;</button>
                        <input type="number" id="quantity" class="form-control text-center border-0" value="1"
                            min="1" style="width: 50px;">
                        <button type="button" class="btn p-1 px-2 border-0 bg-white" onclick="changeQty(1)">&#43;</button>
                    </div>
                </div>
                <div class="mb-2" id="expected-price-block">
                    <span class="fw-bold">Giá dự kiến:</span> <span id="expected-price"
                        class="text-danger fw-bold">{{ format_vnd($product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price) }}</span>
                    đ
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-4 d-flex flex-column align-items-start" style="gap:10px;">
                    <form id="buy-now-form" action="{{ route('cart.add', $product->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <input type="hidden" name="quantity" id="form-quantity" value="1">
                        <input type="hidden" name="variant_id" id="form-variant-id" value="">
                        <button type="submit" class="btn btn-buy px-4 py-2">
                            <i data-feather="shopping-cart" class="me-1"></i> Thêm vào giỏ hàng
                        </button>
                    </form>
                    <form method="POST" action="{{ route('wishlist.add', $product->id) }}"
                        class="mt-2 add-to-wishlist-form">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm wishlist-btn">💖 Thêm vào danh sách yêu thích</button>
                    </form>

                </div>
                <div id="add-to-cart-success" class="alert d-none mt-2"></div>
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
        <div class="product-info-block mt-5">
            <h4>Đánh giá sản phẩm</h4>

            {{-- Hiển thị các đánh giá đã có --}}
 @foreach ($product->rates->where('status', 1) as $rate)
    <div class="mb-3 border-bottom pb-2">
        <div class="d-flex align-items-center mb-1">
            <strong>{{ $rate->user->name }}</strong>

            <div class="ms-2 existing-stars">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($rate->score >= $i)
                        <i class="fas fa-star text-warning"></i>
                    @else
                        <i class="far fa-star text-warning"></i>
                    @endif
                @endfor
            </div>
        </div>

        {{-- Hiển thị biến thể nếu có --}}
        @if ($rate->orderDetail && $rate->orderDetail->variant)
            <div class="text-muted small">
                Biến thể:
                @foreach ($rate->orderDetail->variant->attributeValues as $attributeValue)
                    {{ $attributeValue->value }}@if (!$loop->last), @endif
                @endforeach
            </div>
        @endif

        <p class="mb-0">{{ $rate->content }}</p>

        {{-- ✅ Hiển thị phản hồi admin --}}
        @if ($rate->replies->count())
            @foreach ($rate->replies as $reply)
                <div class="mt-2 ms-4 p-2 bg-light border rounded">
                    <strong class="text-primary">{{ $reply->admin->name ?? 'Admin' }}:</strong>
                    <span>{{ $reply->reply_content }}</span>
                </div>
            @endforeach
        @endif
    </div>
@endforeach






            @if (isset($relatedProducts) && $relatedProducts->count())
                <div class="related-products mt-5">
                    <h4 class="fw-bold mb-3">Sản phẩm liên quan</h4>
                    <div class="row">
                        @foreach ($relatedProducts as $item)
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                        alt="{{ $item->name }}">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{ $item->name }}</h6>
                                        @if ($item->sale_price && $item->sale_price < $item->price)
                                            <p class="text-center mb-2">
                                                <span class="text-danger fw-bold">{{ format_vnd($item->sale_price) }}
                                                    đ</span>
                                                <del class="text-muted ms-1">{{ format_vnd($item->price) }} đ</del>
                                            </p>
                                        @else
                                            <p class="text-danger fw-bold">{{ format_vnd($item->price) }} đ</p>
                                        @endif

                                        <a href="{{ route('client.products.show', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
                <!-- Modal Zoom Ảnh -->
        <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="d-flex justify-content-center align-items-center" style="min-height:60vh; overflow:hidden;">
                <img id="zoomModalImage" src="" alt="Zoom image" class="img-fluid">
                </div>
            </div>
            </div>
        </div>
        </div>

    @endsection

    @push('scripts')
        <script>
            let imageList = [];
            let currentIndex = 0;

            // Dữ liệu variants cho JavaScript
            const variants = {!! $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'price' => $variant->price,
                        'sale_price' => $variant->sale_price,
                        'stock' => $variant->stock,
                        'size_id' => optional($variant->attributeValues->where('attribute.name', 'Size')->first())->id,
                        'color_id' => optional($variant->attributeValues->where('attribute.name', 'Màu')->first())->id,
                    ];
                })->values()->toJson() !!};

            const defaultPrice =
                {{ $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price }};
            const defaultOriginalPrice = {{ $product->price }};

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
                    input.addEventListener('change', function() {
                        fetchStock();
                        updatePriceByVariant();
                    });
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

            function updatePriceByVariant() {
                const {
                    sizeId,
                    colorId
                } = getSelectedVariant();

                if (!sizeId || !colorId) {
                    // Reset về giá mặc định của sản phẩm
                    updatePriceDisplay(defaultPrice, defaultOriginalPrice);
                    return;
                }

                // Tìm variant phù hợp
                const variant = variants.find(v => v.size_id == sizeId && v.color_id == colorId);

                if (variant) {
                    const currentPrice = variant.sale_price && variant.sale_price < variant.price ? variant.sale_price : variant
                        .price;
                    const originalPrice = variant.price;
                    updatePriceDisplay(currentPrice, originalPrice, variant.sale_price);
                } else {
                    // Nếu không tìm thấy variant, giữ nguyên giá mặc định
                    updatePriceDisplay(defaultPrice, defaultOriginalPrice);
                }
            }

            function updatePriceDisplay(currentPrice, originalPrice, salePrice = null) {
                const currentPriceEl = document.getElementById('current-price');
                const originalPriceEl = document.getElementById('original-price');

                currentPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(currentPrice) + ' đ';
                originalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(originalPrice) + ' đ';

                if (salePrice && salePrice < originalPrice) {
                    originalPriceEl.classList.remove('d-none');
                } else {
                    originalPriceEl.classList.add('d-none');
                }

                updateExpectedPrice();
            }

            function getUnitPrice() {
                const {
                    sizeId,
                    colorId
                } = getSelectedVariant();

                if (!sizeId || !colorId) {
                    return defaultPrice;
                }

                const variant = variants.find(v => v.size_id == sizeId && v.color_id == colorId);
                if (variant) {
                    return variant.sale_price && variant.sale_price < variant.price ? variant.sale_price : variant.price;
                }

                return defaultPrice;
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


            function getSelectedVariant() {
                const sizeId = document.querySelector('input[name="size"]:checked')?.id.replace('size-', '');
                const colorId = document.querySelector('input[name="color"]:checked')?.id.replace('color-', '');
                return {
                    sizeId,
                    colorId
                };
            }

            function fetchStock() {
                const {
                    sizeId,
                    colorId
                } = getSelectedVariant();
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
                const sizeId = document.querySelector('input[name="size"]:checked')?.id.replace('size-', '');
                const colorId = document.querySelector('input[name="color"]:checked')?.id.replace('color-', '');
                let variantId = '';

                const alertBox = document.getElementById('add-to-cart-success');

                if (!sizeId || !colorId) {
                    e.preventDefault(); // ngăn submit
                    alertBox.textContent = 'Vui lòng chọn đầy đủ Size và Màu sắc trước khi thêm vào giỏ hàng!';
                    alertBox.className = 'alert alert-danger mt-2';
                    alertBox.classList.remove('d-none');
                    setTimeout(() => alertBox.classList.add('d-none'), 3000);
                    return;
                }

                @foreach ($product->variants as $variant)
                    if ("{{ $variant->attributeValues->where('attribute.name', 'Size')->first()?->id }}" == sizeId &&
                        "{{ $variant->attributeValues->where('attribute.name', 'Màu')->first()?->id }}" == colorId) {
                        variantId = "{{ $variant->id }}";
                    }
                @endforeach

                document.getElementById('form-variant-id').value = variantId;
            });


            feather.replace();
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.add-to-wishlist-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const action = form.getAttribute('action');

                        fetch(action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                const alertBox = document.createElement('div');
                                alertBox.className = 'alert mt-3';
                                alertBox.innerText = data.message || (data.success ?
                                    'Đã thêm vào danh sách yêu thích!' : 'Thêm thất bại!');
                                alertBox.classList.add(data.success ? 'alert-success' :
                                    'alert-danger');
                                form.appendChild(alertBox);
                                setTimeout(() => alertBox.remove(), 3000);
                            })
                            .catch(error => {
                                console.error(error);
                                const alertBox = document.createElement('div');
                                alertBox.className = 'alert alert-danger mt-3';
                                alertBox.innerText = 'Có lỗi xảy ra!';
                                form.appendChild(alertBox);
                                setTimeout(() => alertBox.remove(), 3000);
                            });
                    });
                });
            });
        </script>
        <script>
document.addEventListener('DOMContentLoaded', function () {
  const wrapper = document.getElementById('imageViewerWrapper');
  const mainImg = document.getElementById('mainImage');
  const prefersHover = window.matchMedia('(hover: hover)').matches;
  const zoomScale = 2.2; // độ phóng khi hover

  /* ===== Hover-zoom (desktop) ===== */
  if (prefersHover && wrapper && mainImg) {
    wrapper.addEventListener('mouseenter', () => {
      wrapper.classList.add('is-zooming');
      mainImg.style.transform = `scale(${zoomScale})`;
    });
    wrapper.addEventListener('mouseleave', () => {
      wrapper.classList.remove('is-zooming');
      mainImg.style.transform = 'scale(1)';
      mainImg.style.transformOrigin = 'center center';
    });
    wrapper.addEventListener('mousemove', (e) => {
      const rect = mainImg.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      mainImg.style.transformOrigin = `${x}% ${y}%`; // pan theo chuột
    });
  }

  /* ===== Lightbox/Modal zoom (desktop + mobile) ===== */
  const modalEl  = document.getElementById('imageZoomModal');
  const modalImg = document.getElementById('zoomModalImage');

  function openZoomModal() {
    if (!modalEl || !modalImg) return;
    modalImg.src = mainImg.src;   // dùng ảnh đang hiển thị
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
    resetModalZoom();
  }

  // Cho phép click mở modal trên mọi thiết bị
  if (mainImg) mainImg.addEventListener('click', openZoomModal);

  // Trạng thái/pan trong modal
  let modalZoom = 1, isPanning = false, startX = 0, startY = 0, offsetX = 0, offsetY = 0;

  function resetModalZoom() {
    modalZoom = 1; offsetX = 0; offsetY = 0;
    modalImg.classList.remove('is-zoomed');
    applyModalTransform();
  }
  function applyModalTransform() {
    modalImg.style.transform = `translate(${offsetX}px, ${offsetY}px) scale(${modalZoom})`;
  }
  function toggleModalZoom() {
    if (modalZoom === 1) {
      modalZoom = 2.5;            // mức zoom khi double click
      modalImg.classList.add('is-zoomed');
    } else {
      modalZoom = 1;
      offsetX = offsetY = 0;
      modalImg.classList.remove('is-zoomed');
    }
    applyModalTransform();
  }
  function onPanStart(e) {
    if (modalZoom === 1) return;
    isPanning = true;
    startX = e.clientX; startY = e.clientY;
    modalImg.style.cursor = 'grabbing';
  }
  function onPanMove(e) {
    if (!isPanning) return;
    offsetX += (e.clientX - startX);
    offsetY += (e.clientY - startY);
    startX = e.clientX; startY = e.clientY;
    applyModalTransform();
  }
  function onPanEnd() {
    isPanning = false;
    modalImg.style.cursor = 'grab';
  }

  modalEl?.addEventListener('shown.bs.modal', () => {
    // Double-click / double-tap để zoom
    modalImg.addEventListener('dblclick', toggleModalZoom);

    // Cuộn để zoom (desktop)
    modalImg.addEventListener('wheel', (e) => {
      e.preventDefault();
      const delta = -Math.sign(e.deltaY) * 0.2;
      const nextZoom = Math.min(3.5, Math.max(1, modalZoom + delta));
      if (nextZoom !== modalZoom) {
        modalZoom = nextZoom;
        if (modalZoom === 1) { offsetX = 0; offsetY = 0; }
        modalImg.classList.toggle('is-zoomed', modalZoom > 1);
        applyModalTransform();
      }
    }, { passive: false });

    // Drag to pan (desktop)
    modalImg.addEventListener('mousedown', onPanStart);
    window.addEventListener('mousemove', onPanMove);
    window.addEventListener('mouseup', onPanEnd);

    // Touch pan (mobile)
    modalImg.addEventListener('touchstart', (e) => {
      if (e.touches.length !== 1 || modalZoom === 1) return;
      isPanning = true;
      startX = e.touches[0].clientX; startY = e.touches[0].clientY;
      modalImg.style.cursor = 'grabbing';
    }, { passive: true });

    modalImg.addEventListener('touchmove', (e) => {
      if (!isPanning) return;
      const x = e.touches[0].clientX, y = e.touches[0].clientY;
      offsetX += (x - startX);
      offsetY += (y - startY);
      startX = x; startY = y;
      applyModalTransform();
    }, { passive: true });

    modalImg.addEventListener('touchend', () => { isPanning = false; modalImg.style.cursor = 'grab'; });
  });

  // Cleanup listeners khi đóng modal để tránh nhân đôi
  modalEl?.addEventListener('hide.bs.modal', () => {
    const clone = modalImg.cloneNode(true);
    modalImg.parentNode.replaceChild(clone, modalImg);
  });
});
</script>

    @endpush
