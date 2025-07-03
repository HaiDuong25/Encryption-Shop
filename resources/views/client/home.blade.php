@extends('client.layout.main')

@section('content')
    <!-- Banner Section Start -->
    <section class="banner-section banner-large ratio_65 mb-5">
        <div class="container-fluid-lg">
            <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner rounded-4 shadow-lg">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 1">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">BST Mùa Hè 2025</h2>
                            <p class="lead mb-3">Khám phá phong cách mới, trẻ trung &amp; năng động</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 2">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">Ưu đãi Đặc Biệt</h2>
                            <p class="lead mb-3">Giảm giá lên đến 50% cho các sản phẩm hot trend</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 3">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">BST Đầm Dạ Hội</h2>
                            <p class="lead mb-3">Sang trọng, quyến rũ cho mọi sự kiện</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 4">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">Áo Sơ Mi Nam Cao Cấp</h2>
                            <p class="lead mb-3">Lịch lãm, trẻ trung cho phái mạnh</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a>
                        </div> --}}
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>
    <style>
        .banner-img-large {
            width: 100%;
            aspect-ratio: 16/5;
            object-fit: cover;
            border-radius: 24px;
            min-height: 700px;
            max-height: 1300px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.32);
            border-radius: 16px;
            padding: 2rem 2.5rem;
            left: 3%;
            right: auto;
            bottom: 10%;
            max-width: 500px;
        }

        @media (max-width: 768px) {
            .banner-img-large {
                min-height: 180px;
                max-height: 260px;
            }

            .carousel-caption {
                padding: 1rem 1.2rem;
                max-width: 90vw;
            }
        }
    </style>
    <!-- Banner Section End -->

    <!-- Category Section Start -->
    <section class="category-section-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Danh mục nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-4 justify-content-center">
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Vest Nam Cao Cấp</h4>
                            <h6>12 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Vest Nam">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Đầm Dạ Hội Nữ</h4>
                            <h6>15 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=300&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Đầm Nữ">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Áo Sơ Mi Nam</h4>
                            <h6>18 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?auto=format&fit=crop&w=300&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Vest Nam">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Phụ kiện cao cấp</h4>
                            <h6>10 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=300&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Phụ kiện">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Quần Tây Nam</h4>
                            <h6>14 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=300&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Quần Tây Nam">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-list text-center w-100">
                        <a href="#" class="category-name">
                            <h4>Áo Khoác Nữ</h4>
                            <h6>11 sản phẩm</h6>
                        </a>
                        <div class="category-box-view">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1524253482453-3fed8d2fe12b?auto=format&fit=crop&w=300&q=80"
                                    class="img-fluid category-img-equal mx-auto" alt="Áo Khoác Nữ">
                            </a>
                            <button onclick="location.href = '{{ route('client.products.index') }}';"
                                class="btn shop-button mt-2">
                                <span>Mua ngay</span>
                                <i class="fas fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Đảm bảo ảnh vuông, cùng kích thước và không bị méo */
        .category-img-equal {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 10px;
            display: block;
        }

        .category-box-list {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
    <!-- Category Section End -->
<!-- Voucher Section Start -->
<section class="voucher-section section-b-space">
    <div class="container-fluid-lg">
        <div class="title mb-4 text-center">
            <h2 class="fw-bold mb-2">Ưu đãi & Mã giảm giá hot</h2>
            <div class="text-muted mb-2">Chọn mã phù hợp để nhận ưu đãi khi thanh toán!</div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <!-- Voucher 1 -->
            <div class="col">
                <div class="p-2" style="background: #f0f1f3; border-radius: 14px;">
                    <div class="bg-white rounded-3 shadow-sm h-100 px-3 py-3 d-flex flex-column align-items-center justify-content-between"
                        style="min-height: 170px; max-width: 350px; margin:auto;">
                        <div class="mb-2">
                            <i class="fa-solid fa-ticket fa-lg text-primary mb-1"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-primary text-white fs-6 px-3 py-1 mb-2 rounded-pill">VOUCHER</span>
                        </div>
                        <h5 class="fw-bold text-primary mb-1" style="font-size: 1.15rem;">Giảm 10% (tối đa 10K)</h5>
                        <div class="text-secondary mb-1" style="font-size: 1rem;">Cho đơn từ 200.000₫</div>
                        <div class="text-muted mb-2" style="font-size: 1rem;">Nhập mã: <span class="voucher-code fw-bold">JUL10</span></div>
                        <button class="btn btn-primary btn-sm fw-bold px-3 py-1 rounded-pill mt-2 copy-btn" data-code="JUL10" style="font-size: 1rem;">
                            <i class="fa-solid fa-copy me-1"></i> Sao chép
                        </button>
                        <div class="mt-2">
                            <span class="badge bg-light text-primary border border-primary fs-6 px-2 py-1">Hot</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Voucher 2 -->
            <div class="col">
                <div class="p-2" style="background: #f0f1f3; border-radius: 14px;">
                    <div class="bg-white rounded-3 shadow-sm h-100 px-3 py-3 d-flex flex-column align-items-center justify-content-between"
                        style="min-height: 170px; max-width: 350px; margin:auto;">
                        <div class="mb-2">
                            <i class="fa-solid fa-percent fa-lg text-success mb-1"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success text-white fs-6 px-3 py-1 mb-2 rounded-pill">HOT20</span>
                        </div>
                        <h5 class="fw-bold text-success mb-1" style="font-size: 1.15rem;">Giảm 20%</h5>
                        <div class="text-secondary mb-1" style="font-size: 1rem;">Cho đơn từ 1.000.000₫</div>
                        <div class="text-muted mb-2" style="font-size: 1rem;">Nhập mã: <span class="voucher-code fw-bold">HOT20</span></div>
                        <button class="btn btn-success btn-sm fw-bold px-3 py-1 rounded-pill mt-2 copy-btn" data-code="HOT20" style="font-size: 1rem;">
                            <i class="fa-solid fa-copy me-1"></i> Sao chép
                        </button>
                        <div class="mt-2">
                            <span class="badge bg-light text-success border border-success fs-6 px-2 py-1">Giới hạn</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Voucher 3 -->
            <div class="col">
                <div class="p-2" style="background: #f0f1f3; border-radius: 14px;">
                    <div class="bg-white rounded-3 shadow-sm h-100 px-3 py-3 d-flex flex-column align-items-center justify-content-between"
                        style="min-height: 170px; max-width: 350px; margin:auto;">
                        <div class="mb-2">
                            <i class="fa-solid fa-truck-fast fa-lg text-warning mb-1"></i>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-1 mb-2 rounded-pill">FREESHIP</span>
                        </div>
                        <h5 class="fw-bold text-warning mb-1" style="font-size: 1.15rem;">Freeship toàn quốc</h5>
                        <div class="text-secondary mb-1" style="font-size: 1rem;">Không giới hạn giá trị đơn</div>
                        <div class="text-muted mb-2" style="font-size: 1rem;">Nhập mã: <span class="voucher-code fw-bold">FREESHIP</span></div>
                        <button class="btn btn-warning btn-sm fw-bold px-3 py-1 rounded-pill mt-2 copy-btn" data-code="FREESHIP" style="font-size: 1rem;">
                            <i class="fa-solid fa-copy me-1"></i> Sao chép
                        </button>
                        <div class="mt-2">
                            <span class="badge bg-light text-warning border border-warning fs-6 px-2 py-1">Freeship</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const code = btn.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(function() {
                    btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Đã sao chép';
                    btn.disabled = true;
                    setTimeout(function() {
                        btn.innerHTML = '<i class="fa-solid fa-copy me-1"></i> Sao chép';
                        btn.disabled = false;
                    }, 2000);
                });
            });
        });
    });
</script>
    <!-- Product Section Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title text-center mb-4">
                <h2 class="fw-bold display-4">Sản phẩm nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">
                @php
                    $products = [
                        ['name' => 'Vest nam cao cấp', 'old' => '4.200.000₫', 'new' => '3.500.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Đầm dạ hội nữ', 'old' => '3.200.000₫', 'new' => '2.800.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo sơ mi nam', 'old' => '1.500.000₫', 'new' => '1.200.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo khoác nam', 'old' => '2.500.000₫', 'new' => '2.000.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Đầm dự tiệc', 'old' => '3.800.000₫', 'new' => '3.000.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Quần tây nam', 'old' => '1.400.000₫', 'new' => '1.000.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo thun nữ basic', 'old' => '700.000₫', 'new' => '490.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Váy công sở', 'old' => '2.000.000₫', 'new' => '1.500.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo hoodie nam', 'old' => '1.800.000₫', 'new' => '1.400.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Chân váy nữ', 'old' => '900.000₫', 'new' => '700.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo vest nữ công sở', 'old' => '2.500.000₫', 'new' => '1.900.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Quần jean nam', 'old' => '1.600.000₫', 'new' => '1.200.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                    ];
                @endphp

                @foreach ($products as $product)
                    <div class="col">
                        <div
                            class="product-box-4 wow fadeInUp h-100 d-flex flex-column align-items-center text-center p-2 border rounded shadow-sm">
                            <!-- Ảnh sản phẩm -->
                            <div class="product-image product-image-2 d-flex justify-content-center align-items-center mb-3"
                                style="height: 200px; overflow: hidden;">
                                <a href="#">
                                    <img src="{{ $product['img'] }}" class="img-fluid rounded object-fit-cover"
                                        style="max-height: 100%; width: auto;" alt="{{ $product['name'] }}">
                                </a>
                            </div>

                            <!-- Chi tiết -->
                            <div class="product-detail d-flex flex-column justify-content-between w-100 flex-grow-1">
                                <!-- Đánh giá -->
                                <ul class="rating d-flex justify-content-center mb-2 list-unstyled">
                                    @for ($i = 0; $i < 5; $i++)
                                        <li><i data-feather="star"
                                                class="{{ $i < $product['rating'] ? 'fill text-warning' : '' }}"></i></li>
                                    @endfor
                                </ul>

                                <!-- Tên sản phẩm -->
                                <a href="#">
                                    <h6 class="fw-semibold text-dark mb-2">{{ $product['name'] }}</h6>
                                </a>

                                <!-- Giá -->
                                <div>
                                    <h6 class="text-muted mb-1"><del>{{ $product['old'] }}</del></h6>
                                    <h5 class="text-danger fw-bold mb-2">{{ $product['new'] }}</h5>
                                </div>

                                <!-- Nút -->
                                <button class="btn btn-outline-primary w-100 mt-auto">Thêm vào giỏ</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Nút xem tất cả sản phẩm -->
 <div class="text-center mt-4">
    <a href="{{ route('client.products.index') }}"
        class="btn btn-primary fw-semibold rounded-pill shadow-sm d-inline-flex align-items-center px-3 py-2"
        style="min-width: 140px;">
        <span>Xem tất cả</span>
        <i class="fa-solid fa-arrow-right ms-2"></i>
    </a>
</div>


        </div>
    </section>
    <style>
        .product-img-square {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 10px;
            display: block;
        }

        .product-box-4 {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 12px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-detail {
            padding-top: 10px;
        }
    </style>
    <!-- Product Section End -->

    <!-- News Section Start -->
    <section class="news-section section-b-space">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Tin tức mới nhất</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=500&q=80"
                            class="card-img-top news-img-equal" alt="Tin tức 1">
                        <div class="card-body">
                            <h5 class="card-title">BST Thu Đông 2025: Xu hướng mới lên ngôi</h5>
                            <p class="card-text">Khám phá những mẫu thiết kế mới nhất cho mùa thu đông, mang phong cách sang
                                trọng và hiện đại.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Đọc tiếp</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=500&q=80"
                            class="card-img-top news-img-equal" alt="Tin tức 2">
                        <div class="card-body">
                            <h5 class="card-title">Bí quyết phối đồ công sở thanh lịch</h5>
                            <p class="card-text">Gợi ý cách phối đồ công sở giúp bạn tự tin và nổi bật mỗi ngày tại nơi làm
                                việc.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Đọc tiếp</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=500&q=80"
                            class="card-img-top news-img-equal" alt="Tin tức 3">
                        <div class="card-body">
                            <h5 class="card-title">Ưu đãi tháng 7: Giảm giá lên đến 50%</h5>
                            <p class="card-text">Đừng bỏ lỡ cơ hội mua sắm với mức giá ưu đãi hấp dẫn chỉ có trong tháng
                                này!</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Đọc tiếp</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Section Start -->
    <section class="service-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-3 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
                <div>
                    <div class="service-contain-2">
                        <i class="fa-solid fa-truck-fast fa-2x mb-2"></i>
                        <div class="service-detail">
                            <h3>Miễn phí giao hàng</h3>
                            <h6 class="text-content">Áp dụng cho đơn từ 1.000.000₫</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <i class="fa-solid fa-headset fa-2x mb-2"></i>
                        <div class="service-detail">
                            <h3>Hỗ trợ 24/7</h3>
                            <h6 class="text-content">Tư vấn tận tình, chuyên nghiệp</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <i class="fa-solid fa-credit-card fa-2x mb-2"></i>
                        <div class="service-detail">
                            <h3>Thanh toán linh hoạt</h3>
                            <h6 class="text-content">Chấp nhận nhiều hình thức thanh toán</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <i class="fa-solid fa-gift fa-2x mb-2"></i>
                        <div class="service-detail">
                            <h3>Ưu đãi thành viên</h3>
                            <h6 class="text-content">Nhiều chương trình hấp dẫn</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="service-contain-2">
                        <i class="fa-solid fa-shield-halved fa-2x mb-2"></i>
                        <div class="service-detail">
                            <h3>Cam kết chính hãng</h3>
                            <h6 class="text-content">Sản phẩm 100% chính hãng, cao cấp</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section End -->
    <!-- News Section End -->

@endsection