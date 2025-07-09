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
                    <div class="card shadow-lg border-0 h-100 text-center"
                        style="border-radius: 2rem; background: linear-gradient(135deg, #fffbe7 60%, #ffe7e7 100%);">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4"
                            style="border-radius: 2rem;">
                            <div class="mb-2">
                                <i class="fa-solid fa-gift fa-3x text-warning"
                                    style="background: #fff3cd; border-radius: 50%; padding: 18px 22px; box-shadow: 0 2px 12px #ffe082;"></i>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-warning text-dark fs-6 px-4 py-2 rounded-pill shadow-sm"
                                    style="font-size:1.1rem;">JUL10</span>
                            </div>
                            <h5 class="fw-bold text-danger mb-2" style="font-size:1.3rem;">Giảm 10% (tối đa 10K)</h5>
                            <div class="mb-2 text-secondary" style="font-size:1.05rem;">Cho đơn từ 200.000₫</div>
                            <button class="btn btn-danger btn-sm fw-bold px-4 py-2 rounded-pill copy-btn mb-2 shadow"
                                data-code="JUL10" style="font-size:1.05rem;">
                                <i class="fa-solid fa-copy me-1"></i> Sao chép mã
                            </button>
                            <span
                                class="badge bg-white text-warning border border-warning fs-6 px-3 py-1 mt-1 shadow-sm">Hot</span>
                        </div>
                    </div>
                </div>
                <!-- Voucher 2 -->
                <div class="col">
                    <div class="card shadow-lg border-0 h-100 text-center"
                        style="border-radius: 2rem; background: linear-gradient(135deg, #e7fff6 60%, #e7f3ff 100%);">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4"
                            style="border-radius: 2rem;">
                            <div class="mb-2">
                                <i class="fa-solid fa-gift fa-3x text-success"
                                    style="background: #d1fae5; border-radius: 50%; padding: 18px 22px; box-shadow: 0 2px 12px #b9f6ca;"></i>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-success text-white fs-6 px-4 py-2 rounded-pill shadow-sm"
                                    style="font-size:1.1rem;">HOT20</span>
                            </div>
                            <h5 class="fw-bold text-success mb-2" style="font-size:1.3rem;">Giảm 20%</h5>
                            <div class="mb-2 text-secondary" style="font-size:1.05rem;">Cho đơn từ 1.000.000₫</div>
                            <button class="btn btn-success btn-sm fw-bold px-4 py-2 rounded-pill copy-btn mb-2 shadow"
                                data-code="HOT20" style="font-size:1.05rem;">
                                <i class="fa-solid fa-copy me-1"></i> Sao chép mã
                            </button>
                            <span
                                class="badge bg-white text-success border border-success fs-6 px-3 py-1 mt-1 shadow-sm">Giới
                                hạn</span>
                        </div>
                    </div>
                </div>
                <!-- Voucher 3 -->
                <div class="col">
                    <div class="card shadow-lg border-0 h-100 text-center"
                        style="border-radius: 2rem; background: linear-gradient(135deg, #e7f0ff 60%, #e7faff 100%);">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4"
                            style="border-radius: 2rem;">
                            <div class="mb-2">
                                <i class="fa-solid fa-gift fa-3x text-primary"
                                    style="background: #e3f2fd; border-radius: 50%; padding: 18px 22px; box-shadow: 0 2px 12px #90caf9;"></i>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-primary text-white fs-6 px-4 py-2 rounded-pill shadow-sm"
                                    style="font-size:1.1rem;">FREESHIP</span>
                            </div>
                            <h5 class="fw-bold text-primary mb-2" style="font-size:1.3rem;">Freeship toàn quốc</h5>
                            <div class="mb-2 text-secondary" style="font-size:1.05rem;">Không giới hạn giá trị đơn
                            </div>
                            <button class="btn btn-primary btn-sm fw-bold px-4 py-2 rounded-pill copy-btn mb-2 shadow"
                                data-code="FREESHIP" style="font-size:1.05rem;">
                                <i class="fa-solid fa-copy me-1"></i> Sao chép mã
                            </button>
                            <span
                                class="badge bg-white text-primary border border-primary fs-6 px-3 py-1 mt-1 shadow-sm">Freeship</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const code = btn.getAttribute('data-code');
                    navigator.clipboard.writeText(code).then(function () {
                        btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Đã sao chép';
                        btn.disabled = true;
                        setTimeout(function () {
                            btn.innerHTML = '<i class="fa-solid fa-copy me-1"></i> Sao chép mã';
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
            <div class="title text-center mb-2">
                <h2 class="fw-bold" style="font-size: 2rem;">Sản phẩm nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
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
                        <div class="card shadow-lg border-0 h-100 d-flex flex-column align-items-center"
                            style="border-radius: 1.5rem; overflow: hidden;">
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 260px; width: 100%; overflow: hidden;">
                                <a href="#" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ $product['img'] }}" alt="{{ $product['name'] }}" class="img-fluid"
                                        style="max-height: 240px; max-width: 100%; object-fit: cover; border-radius: 1.5rem 1.5rem 0 0;">
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between w-100 flex-grow-1 px-3 py-3">
                                <ul class="rating d-flex justify-content-center mb-2 list-unstyled">
                                    @for ($i = 0; $i < 5; $i++)
                                        <li>
                                            <i data-feather="star"
                                                class="{{ $i < $product['rating'] ? 'fill text-warning' : 'text-secondary' }}"></i>
                                        </li>
                                    @endfor
                                </ul>
                                <a href="#" class="text-decoration-none">
                                    <h5 class="fw-bold text-dark text-center mb-2" style="font-size: 1.15rem;">
                                        {{ $product['name'] }}
                                    </h5>
                                </a>
                                <div class="d-flex flex-column align-items-center gap-1 mb-2">
                                    <span class="text-muted" style="font-size: 1rem;">
                                        <del>{{ $product['old'] }}</del>
                                    </span>
                                    <span class="fw-bold text-danger" style="font-size: 1.2rem;">
                                        {{ $product['new'] }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-primary btn-sm rounded-pill" title="Thêm vào giỏ">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                  <a href="#" 
   class="btn btn-warning btn-sm fw-bold rounded-pill px-3 ms-1"
   style="color: #fff; font-weight: 600; background-color: #ffc107; box-shadow: 0 2px 8px #ffe082; border: none;">
   Mua ngay
</a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('client.products.index') }}"
                    class="btn btn-primary fw-semibold rounded-pill shadow-sm d-inline-flex align-items-center px-3 py-2"
                    style="min-width: 140px;">
                    <span>Xem tất cả</span>
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- News Section Start -->
    <section class="news-section section-b-space">
        <div class="container-fluid-lg">
            <div class="title mb-4 text-center">
                <h2 class="fw-bold mb-2" style="font-size:2rem;">Tin tức mới nhất</h2>
                <div class="text-muted mb-3">Cập nhật xu hướng, ưu đãi và bí quyết thời trang mỗi ngày!</div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
                <!-- News 1 -->
                <div class="col">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 1.5rem; overflow: hidden;">
                        <div style="overflow: hidden; border-radius: 1.5rem 1.5rem 0 0;">
                            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=600&q=80"
                                class="card-img-top img-fluid" alt="Tin tức 1"
                                style="object-fit:cover; height:220px; transition: transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size:1.15rem;">BST Thu Đông 2025: Xu
                                hướng mới lên ngôi</h5>
                            <p class="card-text text-secondary mb-3" style="font-size:1rem;">Khám phá những mẫu thiết kế mới
                                nhất cho mùa thu đông, mang phong cách sang trọng và hiện đại.</p>
                            <a href="#"
                class="btn btn-warning rounded-pill fw-semibold text-white align-self-start px-4 py-2"
                style="background-color: #f9a825; border: none; box-shadow: 0 4px 12px rgba(249, 168, 37, 0.4);">
                Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end px-3 pb-3">
                            <span class="badge bg-light text-warning border border-warning">Xu hướng</span>
                        </div>
                    </div>
                </div>
                <!-- News 2 -->
                <div class="col">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 1.5rem; overflow: hidden;">
                        <div style="overflow: hidden; border-radius: 1.5rem 1.5rem 0 0;">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=600&q=80"
                                class="card-img-top img-fluid" alt="Tin tức 2"
                                style="object-fit:cover; height:220px; transition: transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size:1.15rem;">Bí quyết phối đồ công
                                sở thanh lịch</h5>
                            <p class="card-text text-secondary mb-3" style="font-size:1rem;">Gợi ý cách phối đồ công sở giúp
                                bạn tự tin và nổi bật mỗi ngày tại nơi làm việc.</p>
                            <a href="#"
                class="btn btn-warning rounded-pill fw-semibold text-white align-self-start px-4 py-2"
                style="background-color: #f9a825; border: none; box-shadow: 0 4px 12px rgba(249, 168, 37, 0.4);">
                Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end px-3 pb-3">
                            <span class="badge bg-light text-success border border-success">Bí quyết</span>
                        </div>
                    </div>
                </div>
            <!-- News 3 -->
    <div class="col">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 1.5rem; overflow: hidden;">
            <div style="overflow: hidden; border-radius: 1.5rem 1.5rem 0 0;">
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80"
                    class="card-img-top img-fluid" alt="Tin tức 3"
                    style="object-fit:cover; height:220px; transition: transform 0.3s;"
                    onmouseover="this.style.transform='scale(1.05)';"
                    onmouseout="this.style.transform='scale(1)';">
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title fw-bold text-dark mb-2" style="font-size:1.15rem;">
                    Phong cách hè 2025: Đơn giản mà nổi bật
                </h5>
                <p class="card-text text-secondary mb-3" style="font-size:1rem;">
                    Cập nhật các xu hướng phối đồ đơn giản, trẻ trung cho mùa hè năm nay.
                </p>
                <a href="#"
                class="btn btn-warning rounded-pill fw-semibold text-white align-self-start px-4 py-2"
                style="background-color: #f9a825; border: none; box-shadow: 0 4px 12px rgba(249, 168, 37, 0.4);">
                Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-footer bg-transparent border-0 text-end px-3 pb-3">
                <span class="badge bg-light text-primary border border-primary">Hot</span>
            </div>
        </div>
    </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-rounded mb-0">
                        <li class="page-item active"><span
                                class="page-link border-0 rounded-pill bg-warning text-white">1</span></li>
                        <li class="page-item"><a class="page-link border-0 rounded-pill text-dark" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-pill text-dark" href="#">3</a></li>
                    </ul>
                </nav>
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