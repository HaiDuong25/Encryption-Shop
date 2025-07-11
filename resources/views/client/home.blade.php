@extends('client.layout.main')

@section('content')
    <!-- Banner Section Start -->
    <section class="banner-section banner-large ratio_65 mb-5">
        <div class="container-fluid-lg">
            <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner rounded-4 shadow-lg">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 1">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">BST Mùa Hè 2025</h2>
                            <p class="lead mb-3">Khám phá phong cách mới, trẻ trung &amp; năng động</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 2">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">Ưu đãi Đặc Biệt</h2>
                            <p class="lead mb-3">Giảm giá lên đến 50% cho các sản phẩm hot trend</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1600&q=80"
                            class="d-block w-100 banner-img-large" alt="Banner 3">
                        <div class="carousel-caption d-none d-md-block text-start">
                            <h2 class="fw-bold display-5 mb-2">BST Đầm Dạ Hội</h2>
                            <p class="lead mb-3">Sang trọng, quyến rũ cho mọi sự kiện</p>
                            {{-- <a href="{{ route('client.products.index') }}" class="btn btn-lg btn-primary px-4 py-2">Mua
                                ngay</a> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=1600&q=80"
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
    <section class="category-section-3 py-5">
        <div class="container-fluid-lg">
            <div class="title text-center mb-5">
                <h2 class="fw-bold" style="font-size: 2rem;">Danh mục nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-4 justify-content-center">
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Vest Nam">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Vest Nam Cao Cấp</h4>
                                    <span class="category-count">12 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Đầm Nữ">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Đầm Dạ Hội Nữ</h4>
                                    <span class="category-count">15 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Áo Sơ Mi Nam">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Áo Sơ Mi Nam</h4>
                                    <span class="category-count">18 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Phụ kiện">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Phụ kiện cao cấp</h4>
                                    <span class="category-count">10 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Quần Tây Nam">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Quần Tây Nam</h4>
                                    <span class="category-count">14 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="col d-flex">
                    <div class="category-box-modern text-center w-100 h-100">
                        <div class="category-image-container">
                            <a href="#">
                                <img src="https://images.unsplash.com/photo-1524253482453-3fed8d2fe12b?auto=format&fit=crop&w=600&q=80"
                                    class="img-fluid category-img-modern mx-auto" alt="Áo Khoác Nữ">
                            </a>
                            <div class="category-overlay">
                                <div class="category-info">
                                    <h4 class="category-title">Áo Khoác Nữ</h4>
                                    <span class="category-count">11 sản phẩm</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="location.href = '{{ route('client.products.index') }}';"
                            class="btn btn-category-modern mt-3">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Modern Category Section Styles */
        .category-box-modern {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .category-box-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .category-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .category-img-modern {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.4s ease;
        }

        .category-box-modern:hover .category-img-modern {
            transform: scale(1.1);
        }

        .category-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 15px;
        }

        .category-box-modern:hover .category-overlay {
            opacity: 1;
        }

        .category-info {
            text-align: center;
            color: white;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .category-box-modern:hover .category-info {
            transform: translateY(0);
        }

        .category-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .category-count {
            font-size: 0.9rem;
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .btn-category-modern {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-category-modern:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            color: white;
        }

        .btn-category-modern:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .category-img-modern {
                height: 150px;
            }

            .category-box-modern {
                padding: 1rem;
            }

            .category-title {
                font-size: 1rem;
            }

            .btn-category-modern {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>
    <!-- Category Section End -->
    <!-- Voucher Section Start -->
    <section class="voucher-section section-b-space py-5">
        <div class="container-fluid-lg">
            <div class="title mb-5 text-center">
                <h2 class="fw-bold" style="font-size: 2rem;">Ưu đãi & Mã giảm giá hot</h2>
                <p class="text-muted" style="font-size: 1.1rem;">Chọn mã phù hợp để nhận ưu đãi khi thanh toán!</p>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-5 justify-content-center">
                <!-- Voucher 1 -->
                <div class="col">
                    <div class="voucher-card h-100 text-center"
                        style="background: linear-gradient(135deg, #fff9e6 0%, #ffe0b3 100%); border-radius: 2rem; box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2); transition: all 0.3s ease; overflow: hidden; position: relative;"
                        onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(255, 193, 7, 0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(255, 193, 7, 0.2)';">
                        <div class="voucher-decoration"
                            style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255, 193, 7, 0.1); border-radius: 50%; transform: rotate(45deg);">
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4 px-4"
                            style="position: relative; z-index: 1;">
                            <div class="voucher-icon mb-3">
                                <i class="fa-solid fa-gift fa-3x text-warning"
                                    style="background: linear-gradient(135deg, #fff3cd 0%, #ffe082 100%); border-radius: 50%; padding: 20px; box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);"></i>
                            </div>
                            <div class="voucher-code mb-3">
                                <span class="badge bg-warning text-dark px-4 py-2 rounded-pill shadow-sm fw-bold"
                                    style="font-size:1.2rem; letter-spacing: 1px;">JUL10</span>
                            </div>
                            <h4 class="fw-bold text-danger mb-3" style="font-size:1.4rem;">Giảm 10% (tối đa 10K)</h4>
                            <p class="text-muted mb-3" style="font-size:1.1rem;">Cho đơn từ 200.000₫</p>
                            <button class="btn btn-danger btn-lg fw-bold px-4 py-2 rounded-pill copy-btn mb-3 shadow-lg"
                                data-code="JUL10" style="font-size:1.1rem; min-width: 180px; transition: all 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                                <i class="fa-solid fa-copy me-2"></i> Sao chép mã
                            </button>
                            <span class="badge bg-white text-warning border border-warning px-3 py-1 shadow-sm"
                                style="font-size: 0.9rem; font-weight: 600;">🔥 Hot</span>
                        </div>
                    </div>
                </div>
                <!-- Voucher 2 -->
                <div class="col">
                    <div class="voucher-card h-100 text-center"
                        style="background: linear-gradient(135deg, #e8f5e8 0%, #c3e6c3 100%); border-radius: 2rem; box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2); transition: all 0.3s ease; overflow: hidden; position: relative;"
                        onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(40, 167, 69, 0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(40, 167, 69, 0.2)';">
                        <div class="voucher-decoration"
                            style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); border-radius: 50%; transform: rotate(45deg);">
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4 px-4"
                            style="position: relative; z-index: 1;">
                            <div class="voucher-icon mb-3">
                                <i class="fa-solid fa-gift fa-3x text-success"
                                    style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 50%; padding: 20px; box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);"></i>
                            </div>
                            <div class="voucher-code mb-3">
                                <span class="badge bg-success text-white px-4 py-2 rounded-pill shadow-sm fw-bold"
                                    style="font-size:1.2rem; letter-spacing: 1px;">HOT20</span>
                            </div>
                            <h4 class="fw-bold text-success mb-3" style="font-size:1.4rem;">Giảm 20%</h4>
                            <p class="text-muted mb-3" style="font-size:1.1rem;">Cho đơn từ 1.000.000₫</p>
                            <button class="btn btn-success btn-lg fw-bold px-4 py-2 rounded-pill copy-btn mb-3 shadow-lg"
                                data-code="HOT20" style="font-size:1.1rem; min-width: 180px; transition: all 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                                <i class="fa-solid fa-copy me-2"></i> Sao chép mã
                            </button>
                            <span class="badge bg-white text-success border border-success px-3 py-1 shadow-sm"
                                style="font-size: 0.9rem; font-weight: 600;">⚡ Giới hạn</span>
                        </div>
                    </div>
                </div>
                <!-- Voucher 3 -->
                <div class="col">
                    <div class="voucher-card h-100 text-center"
                        style="background: linear-gradient(135deg, #e6f3ff 0%, #b3d9ff 100%); border-radius: 2rem; box-shadow: 0 10px 30px rgba(0, 123, 255, 0.2); transition: all 0.3s ease; overflow: hidden; position: relative;"
                        onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0, 123, 255, 0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0, 123, 255, 0.2)';">
                        <div class="voucher-decoration"
                            style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(0, 123, 255, 0.1); border-radius: 50%; transform: rotate(45deg);">
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4 px-4"
                            style="position: relative; z-index: 1;">
                            <div class="voucher-icon mb-3">
                                <i class="fa-solid fa-gift fa-3x text-primary"
                                    style="background: linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%); border-radius: 50%; padding: 20px; box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);"></i>
                            </div>
                            <div class="voucher-code mb-3">
                                <span class="badge bg-primary text-white px-4 py-2 rounded-pill shadow-sm fw-bold"
                                    style="font-size:1.2rem; letter-spacing: 1px;">FREESHIP</span>
                            </div>
                            <h4 class="fw-bold text-primary mb-3" style="font-size:1.4rem;">Freeship toàn quốc</h4>
                            <p class="text-muted mb-3" style="font-size:1.1rem;">Không giới hạn giá trị đơn</p>
                            <button class="btn btn-primary btn-lg fw-bold px-4 py-2 rounded-pill copy-btn mb-3 shadow-lg"
                                data-code="FREESHIP" style="font-size:1.1rem; min-width: 180px; transition: all 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                                <i class="fa-solid fa-copy me-2"></i> Sao chép mã
                            </button>
                            <span class="badge bg-white text-primary border border-primary px-3 py-1 shadow-sm"
                                style="font-size: 0.9rem; font-weight: 600;">🚚 Freeship</span>
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
    <section class="product-section-3 py-5">
        <div class="container-fluid-lg">
            <div class="title mb-5">
                <h2 class="fw-bold" style="font-size: 2rem;">Sản phẩm nổi bật</h2>
                <p class="text-muted" style="font-size: 1.1rem;">Khám phá những sản phẩm hot nhất được yêu thích nhất</p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                            <div class="col">
                                <div class="product-card-modern shadow-lg border-0 h-100 d-flex flex-column"
                                    style="border-radius: 1.5rem; overflow: hidden; transition: all 0.3s ease; position: relative; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);"
                                    onmouseover="this.style.transform='translateY(-15px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.2)'; this.querySelector('.product-image').style.transform='scale(1.1)';"
                                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 40px rgba(0, 0, 0, 0.1)'; this.querySelector('.product-image').style.transform='scale(1)';">

                                    <!-- Product Image Container -->
                                    <div class="product-image-container bg-light d-flex align-items-center justify-content-center position-relative"
                                        style="height: 280px; width: 100%; overflow: hidden; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">

                                        <a href="{{ route('client.products.show', $product->id) }}"
                                            class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="img-fluid product-image"
                                                style="max-height: 260px; max-width: 100%; object-fit: cover; transition: all 0.4s ease; border-radius: 8px;">
                                        </a>

                                        <!-- Quick Action Icons -->
                                        <div class="product-quick-actions position-absolute"
                                            style="top: 15px; right: 15px; display: flex; flex-direction: column; gap: 8px; z-index: 10;">
                                            <a href="{{ route('client.products.show', $product->id) }}"
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#007bff'; this.style.color='white'; this.style.transform='scale(1.1)';"
                                                onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.9)'; this.style.color='#333'; this.style.transform='scale(1)';"
                                                title="Xem chi tiết">
                                                <i class="fa-solid fa-eye" style="font-size: 14px;"></i>
                                            </a>
                                            <button
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: all 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='white'; this.style.transform='scale(1.1)';"
                                                onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.9)'; this.style.color='#333'; this.style.transform='scale(1)';"
                                                title="Thêm vào yêu thích">
                                                <i class="fa-solid fa-heart" style="font-size: 14px;"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="card-body d-flex flex-column justify-content-between w-100 flex-grow-1 p-4"
                                        style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">

                                        <!-- Product Name -->
                                        <a href="{{ route('client.products.show', $product->id) }}" class="text-decoration-none">
                                            <h5 class="fw-bold text-dark text-center mb-3 product-name"
                                                style="font-size: 1.1rem; line-height: 1.4; min-height: 2.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s ease;"
                                                onmouseover="this.style.color='#007bff';" onmouseout="this.style.color='#212529';">
                                                {{ $product->name }}
                                            </h5>
                                        </a>

                                        <!-- Rating -->
                                        <div class="product-rating d-flex justify-content-center align-items-center mb-3">
                                            @php
                                                $avgRate = $product->rates->where('status', 1)->avg('score');
                                                $avgRate = round($avgRate * 2) / 2;
                                            @endphp
                                            <div class="stars d-flex">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fa-solid fa-star {{ $i < $avgRate ? 'text-warning' : 'text-muted' }}"
                                                        style="font-size: 1rem; margin: 0 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.1);"></i>
                                                @endfor
                                            </div>
                                            <span class="text-muted ms-2 fw-medium" style="font-size: 0.9rem;">
                                                ({{ number_format($avgRate, 1) }}) • {{ $product->rates->where('status', 1)->count() }}
                                                đánh giá
                                            </span>
                                        </div>

                                        <!-- Price -->
                                        <div class="product-price d-flex flex-column align-items-center gap-1 mb-4">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold text-danger"
                                                        style="font-size: 1.4rem; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);">
                                                        {{ number_format($product->sale_price) }}₫
                                                    </span>
                                                    <span class="text-muted text-decoration-line-through" style="font-size: 1rem;">
                                                        {{ number_format($product->price) }}₫
                                                    </span>
                                                </div>
                                                <div class="text-success fw-semibold" style="font-size: 0.9rem;">
                                                    💰 Tiết kiệm {{ number_format($product->price - $product->sale_price) }}₫
                                                </div>
                                            @else
                                                <span class="fw-bold text-danger"
                                                    style="font-size: 1.4rem; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);">
                                                    {{ number_format($product->price) }}₫
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        <div class="product-actions d-flex justify-content-center">
                                            <a href="{{ route('client.products.show', $product->id) }}"
                                                class="btn btn-warning rounded-pill fw-bold px-5 py-3 position-relative overflow-hidden w-100"
                                                style="color: #fff; background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); border: none; box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4); transition: all 0.3s ease; font-size: 1.1rem;"
                                                onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 15px 35px rgba(255, 193, 7, 0.5)'; this.style.background='linear-gradient(135deg, #ff8f00 0%, #ff6f00 100%)';"
                                                onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 25px rgba(255, 193, 7, 0.4)'; this.style.background='linear-gradient(135deg, #ffc107 0%, #ff8f00 100%)';">
                                                <i class="fa-solid fa-bolt me-2"></i>Mua ngay
                                                <i class="fa-solid fa-arrow-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-5">
                <a href="{{ route('client.products.index') }}"
                    class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3 fw-semibold"
                    style="border: 2px solid #007bff; transition: all 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#007bff'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';">
                    <i class="fa-solid fa-shopping-bag me-2"></i>
                    Xem tất cả sản phẩm
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <style>
        /* Product Card Modern Styles */
        .product-card-modern {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-card-modern:hover .product-image {
            transform: scale(1.05);
        }

        .product-card-modern:hover .product-quick-actions {
            opacity: 1 !important;
            visibility: visible;
        }

        .product-image-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Enhanced Product Card Animation */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .product-card {
            animation: slideInUp 0.6s ease-out;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            animation: float 3s ease-in-out infinite;
        }

        .product-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .product-card .badge {
            animation: pulse 3s infinite;
        }

        .product-card .stars i {
            transition: all 0.3s ease;
        }

        .product-card:hover .stars i {
            transform: scale(1.1);
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }

        .product-price {
            position: relative;
            z-index: 1;
        }

        .product-price::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(220, 53, 69, 0.1) 0%, transparent 70%);
            transition: all 0.3s ease;
            border-radius: 50%;
            z-index: -1;
        }

        .product-card:hover .product-price::before {
            width: 120px;
            height: 120px;
        }

        /* Glass morphism effect */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Improved button styles */
        .btn-glass {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced z-index for proper layering */
        .z-index-1 {
            z-index: 1;
        }

        .z-index-2 {
            z-index: 2;
        }

        .z-index-3 {
            z-index: 3;
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 1.5rem;
            }

            .product-image-container {
                height: 250px !important;
            }

            .product-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .product-actions a {
                min-width: 100% !important;
            }
        }

        @media (max-width: 576px) {
            .product-image-container {
                height: 220px !important;
            }

            .product-actions {
                gap: 0.75rem;
            }

            .product-actions button,
            .product-actions a {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Loading animation for images */
        .product-image {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image {
            filter: brightness(1.1) saturate(1.1);
        }

        /* Enhanced shadow effects */
        .shadow-custom {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .shadow-custom-hover {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        /* Gradient text effects */
        .text-gradient {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient-price {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Quick Action Icons Animation */
        .product-quick-actions {
            visibility: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .product-card:hover .product-quick-actions {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        .product-quick-actions .btn {
            transition: all 0.3s ease;
            animation: fadeInRight 0.3s ease forwards;
        }

        .product-quick-actions .btn:nth-child(1) {
            animation-delay: 0.1s;
        }

        .product-quick-actions .btn:nth-child(2) {
            animation-delay: 0.2s;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>


    <!-- News Section Start -->
    <section class="news-section section-b-space py-5">
        <div class="container-fluid-lg">
            <div class="title mb-5 text-center">
                <h2 class="fw-bold" style="font-size: 2rem;">Tin tức nổi bật</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5 justify-content-center">
                <!-- News 1 -->
                <div class="col">
                    <div class="card border-0 shadow-lg h-100"
                        style="border-radius: 2rem; overflow: hidden; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-10px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=80"
                                class="card-img-top img-fluid" alt="Tin tức 1"
                                style="object-fit:cover; height:300px; transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                            <div class="position-absolute top-0 start-0 p-3">
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm fw-bold">Xu
                                    hướng</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="mb-3">
                                <div class="text-muted small mb-2">
                                    <i class="fa-solid fa-calendar-days me-2"></i>10 Tháng 7, 2025
                                </div>
                                <h4 class="card-title fw-bold text-dark mb-3" style="font-size:1.4rem; line-height: 1.4;">
                                    BST Thu Đông 2025: Xu hướng mới lên ngôi
                                </h4>
                                <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                    Khám phá những mẫu thiết kế mới nhất cho mùa thu đông, mang phong cách sang trọng và
                                    hiện đại cùng những gam màu ấm áp.
                                </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-warning rounded-pill fw-semibold text-white px-4 py-2"
                                    style="background: linear-gradient(135deg, #f9a825 0%, #f57c00 100%); border: none; box-shadow: 0 4px 15px rgba(249, 168, 37, 0.4);">
                                    Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    <span>1.2k</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- News 2 -->
                <div class="col">
                    <div class="card border-0 shadow-lg h-100"
                        style="border-radius: 2rem; overflow: hidden; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-10px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80"
                                class="card-img-top img-fluid" alt="Tin tức 2"
                                style="object-fit:cover; height:300px; transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                            <div class="position-absolute top-0 start-0 p-3">
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm fw-bold">Bí
                                    quyết</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="mb-3">
                                <div class="text-muted small mb-2">
                                    <i class="fa-solid fa-calendar-days me-2"></i>8 Tháng 7, 2025
                                </div>
                                <h4 class="card-title fw-bold text-dark mb-3" style="font-size:1.4rem; line-height: 1.4;">
                                    Bí quyết phối đồ công sở thanh lịch
                                </h4>
                                <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                    Gợi ý cách phối đồ công sở giúp bạn tự tin và nổi bật mỗi ngày tại nơi làm việc với
                                    phong cách chuyên nghiệp.
                                </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-warning rounded-pill fw-semibold text-white px-4 py-2"
                                    style="background: linear-gradient(135deg, #f9a825 0%, #f57c00 100%); border: none; box-shadow: 0 4px 15px rgba(249, 168, 37, 0.4);">
                                    Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    <span>856</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- News 3 -->
                <div class="col">
                    <div class="card border-0 shadow-lg h-100"
                        style="border-radius: 2rem; overflow: hidden; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-10px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80"
                                class="card-img-top img-fluid" alt="Tin tức 3"
                                style="object-fit:cover; height:300px; transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                            <div class="position-absolute top-0 start-0 p-3">
                                <span
                                    class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm fw-bold">Hot</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="mb-3">
                                <div class="text-muted small mb-2">
                                    <i class="fa-solid fa-calendar-days me-2"></i>5 Tháng 7, 2025
                                </div>
                                <h4 class="card-title fw-bold text-dark mb-3" style="font-size:1.4rem; line-height: 1.4;">
                                    Phong cách hè 2025: Đơn giản mà nổi bật
                                </h4>
                                <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                    Cập nhật các xu hướng phối đồ đơn giản, trẻ trung cho mùa hè năm nay với những gam màu
                                    tươi mới.
                                </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-warning rounded-pill fw-semibold text-white px-4 py-2"
                                    style="background: linear-gradient(135deg, #f9a825 0%, #f57c00 100%); border: none; box-shadow: 0 4px 15px rgba(249, 168, 37, 0.4);">
                                    Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    <span>2.1k</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- View More Button -->
            <div class="d-flex justify-content-center mt-5">
                <a href="#" class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3 fw-semibold"
                    style="border: 2px solid #007bff; transition: all 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#007bff'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';">
                    <i class="fa-solid fa-newspaper me-2"></i>
                    Xem tất cả tin tức
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
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