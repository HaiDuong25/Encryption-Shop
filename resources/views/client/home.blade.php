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

    <!-- Ưu đãi vé giảm giá Start -->
    <section class="voucher-section section-b-space">
        <div class="container-fluid-lg">
            <div class="title mb-4 text-center">
                <h2 class="fw-bold mb-2">Ưu đãi &amp; Mã giảm giá hot</h2>
                <div class="text-muted mb-2">Nhanh tay nhận ngay ưu đãi hấp dẫn dành cho bạn!</div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div class="col">
                    <div
                        class="voucher-banner-card bg-gradient-primary position-relative overflow-hidden shadow-lg rounded-4 p-0 h-100">
                        <div class="d-flex align-items-center h-100 px-3 py-4">
                            <div
                                class="gift-icon-box bg-white rounded-circle shadow d-flex align-items-center justify-content-center me-4 flex-shrink-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/3469/3469100.png" alt="gift"
                                    style="width:48px;height:48px;">
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1 text-primary">Tặng 10% cho đơn từ 500K</h4>
                                <div class="mb-2 text-secondary small">Áp dụng toàn bộ sản phẩm</div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">SALE10</span>
                                    <button class="btn btn-primary btn-sm fw-bold px-3 py-1 rounded-pill shadow-sm">Sao chép
                                        mã</button>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 80%"></div>
                                </div>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/616/616489.png" alt="gift"
                                class="voucher-bg-gift">
                        </div>
                        <span class="voucher-ribbon bg-primary text-white">Hot</span>
                    </div>
                </div>
                <div class="col">
                    <div
                        class="voucher-banner-card bg-gradient-success position-relative overflow-hidden shadow-lg rounded-4 p-0 h-100">
                        <div class="d-flex align-items-center h-100 px-3 py-4">
                            <div
                                class="gift-icon-box bg-white rounded-circle shadow d-flex align-items-center justify-content-center me-4 flex-shrink-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/3469/3469100.png" alt="gift"
                                    style="width:48px;height:48px;">
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1 text-success">Giảm 20% cho đơn từ 1 triệu</h4>
                                <div class="mb-2 text-secondary small">Số lượng có hạn</div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fs-6">HOT20</span>
                                    <button class="btn btn-success btn-sm fw-bold px-3 py-1 rounded-pill shadow-sm">Sao chép
                                        mã</button>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 60%"></div>
                                </div>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/616/616490.png" alt="gift"
                                class="voucher-bg-gift">
                        </div>
                        <span class="voucher-ribbon bg-success text-white">Giới hạn</span>
                    </div>
                </div>
                <div class="col">
                    <div
                        class="voucher-banner-card bg-gradient-warning position-relative overflow-hidden shadow-lg rounded-4 p-0 h-100">
                        <div class="d-flex align-items-center h-100 px-3 py-4">
                            <div
                                class="gift-icon-box bg-white rounded-circle shadow d-flex align-items-center justify-content-center me-4 flex-shrink-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/3469/3469100.png" alt="gift"
                                    style="width:48px;height:48px;">
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1 text-warning">Freeship toàn quốc</h4>
                                <div class="mb-2 text-secondary small">Không giới hạn giá trị đơn</div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 fs-6">FREESHIP</span>
                                    <button class="btn btn-warning btn-sm fw-bold px-3 py-1 rounded-pill shadow-sm">Sao chép
                                        mã</button>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                                </div>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/512/616/616491.png" alt="gift"
                                class="voucher-bg-gift">
                        </div>
                        <span class="voucher-ribbon bg-warning text-dark">Freeship</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .voucher-banner-card {
            min-height: 180px;
            background: linear-gradient(120deg, #f8fafc 60%, #e9ecef 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            transition: box-shadow 0.2s, transform 0.2s;
            border: none;
        }

        .voucher-banner-card:hover {
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15);
            transform: translateY(-6px) scale(1.025);
        }

        .gift-icon-box {
            width: 80px;
            height: 80px;
            margin-right: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.09);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .voucher-bg-gift {
            position: absolute;
            right: -10px;
            bottom: -10px;
            width: 100px;
            opacity: 0.12;
            pointer-events: none;
            user-select: none;
            z-index: 1;
        }

        .voucher-ribbon {
            position: absolute;
            top: 18px;
            left: -32px;
            padding: 4px 36px;
            font-size: 1rem;
            font-weight: 700;
            transform: rotate(-25deg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            z-index: 2;
            letter-spacing: 1px;
        }

        .bg-gradient-primary {
            background: linear-gradient(120deg, #e3f0ff 60%, #c9e7ff 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(120deg, #e6f9ed 60%, #c7f5dd 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(120deg, #fff7e0 60%, #ffe9b8 100%) !important;
        }

        .progress {
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-bar {
            border-radius: 6px;
        }
    </style>
    <!-- Ưu đãi vé giảm giá End -->
    <!-- Product Section Start -->
    <section class="product-section-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>Sản phẩm nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">
                @php
                    $products = [
                        ['name' => 'Vest nam cao cấp', 'old' => '4.200.000₫', 'new' => '3.500.000₫', 'img' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Đầm dạ hội nữ', 'old' => '3.200.000₫', 'new' => '2.800.000₫', 'img' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo sơ mi nam', 'old' => '1.500.000₫', 'new' => '1.200.000₫', 'img' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80', 'rating' => 3],
                        ['name' => 'Áo khoác nam', 'old' => '2.500.000₫', 'new' => '2.000.000₫', 'img' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=600&q=80', 'rating' => 2],
                        ['name' => 'Đầm dự tiệc', 'old' => '3.800.000₫', 'new' => '3.000.000₫', 'img' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Quần tây nam', 'old' => '1.400.000₫', 'new' => '1.000.000₫', 'img' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=600&q=80', 'rating' => 3],
                        ['name' => 'Áo thun nữ basic', 'old' => '700.000₫', 'new' => '490.000₫', 'img' => 'https://images.unsplash.com/photo-1593032465171-8fb7c8f7d61f?auto=format&fit=crop&w=600&q=80', 'rating' => 5],
                        ['name' => 'Váy công sở', 'old' => '2.000.000₫', 'new' => '1.500.000₫', 'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Áo hoodie nam', 'old' => '1.800.000₫', 'new' => '1.400.000₫', 'img' => 'https://images.unsplash.com/photo-1552374196-c4e7ffc6e126?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
                        ['name' => 'Chân váy nữ', 'old' => '900.000₫', 'new' => '700.000₫', 'img' => 'https://images.unsplash.com/photo-1551854838-593f0f46aa53?auto=format&fit=crop&w=600&q=80', 'rating' => 3],
                        ['name' => 'Áo vest nữ công sở', 'old' => '2.500.000₫', 'new' => '1.900.000₫', 'img' => 'https://images.unsplash.com/photo-1570784091703-0ba1dcac6c6d?auto=format&fit=crop&w=600&q=80', 'rating' => 5],
                        ['name' => 'Quần jean nam', 'old' => '1.600.000₫', 'new' => '1.200.000₫', 'img' => 'https://images.unsplash.com/photo-1585386959984-a4155224c7f4?auto=format&fit=crop&w=600&q=80', 'rating' => 4],
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

            <!-- Thêm các sản phẩm khác tương tự nếu muốn -->
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
                        <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=500&q=80"
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

    <style>
        .news-img-equal {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            display: block;
        }
    </style>
    <!-- News Section End -->

@endsection