@extends('client.layout.main')

@section('content')
    <!-- Fix search button click issue caused by toast z-index -->
    <style>
        /* Ensure header search form has higher z-index than toast notifications */
        header .top-nav {
            position: relative !important;
            z-index: 1060 !important;
        }

        header .search-form {
            position: relative !important;
            z-index: 1061 !important;
        }

        header .search-input-group {
            position: relative !important;
            z-index: 1062 !important;
        }

        header .search-button {
            position: relative !important;
            z-index: 1063 !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }

        header .search-input {
            position: relative !important;
            z-index: 1062 !important;
            pointer-events: auto !important;
        }

        /* Ensure toast notifications don't interfere with header */
        .toast-container {
            z-index: 1055 !important;
        }

        /* Fix any potential overlay issues */
        header .search-full {
            z-index: 1050 !important;
        }

        header .search-full.open {
            z-index: 1059 !important;
        }

        /* Mobile fixes */
        @media (max-width: 768px) {
            header .top-nav {
                z-index: 1070 !important;
            }

            header .search-form {
                z-index: 1071 !important;
            }

            header .search-button {
                z-index: 1073 !important;
                min-height: 48px;
                touch-action: manipulation;
            }
        }
    </style>

    <style>
        /* News Section Animations */
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }

            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        .news-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .news-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .news-card:nth-child(3) {
            animation-delay: 0.4s;
        }

        .news-card .btn:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 0.6s ease-in-out;
        }

        .news-card .card-title {
            position: relative;
            overflow: hidden;
        }

        .news-card:hover .card-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transform-origin: left;
            animation: expandLine 0.3s ease-out forwards;
        }

        @keyframes expandLine {
            to {
                transform: scaleX(1);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <!-- Banner Section Start -->
    <section class="banner-section banner-large ratio_65 mb-5">
        <div class="container-fluid-lg">
            <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner rounded-4 shadow-lg">
                    @if($banners && $banners->count() > 0)
                                @foreach($banners as $index => $banner)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                @php
                                                    $images = json_decode($banner->image, true);
                                                    $mainImage = is_array($images) && !empty($images) ? $images[0] : $banner->image;
                                                @endphp
                                                <img src="{{ asset('storage/' . $mainImage) }}" class="d-block w-100 banner-img-large"
                                                    alt="{{ $banner->title }}">
                                                <div class="carousel-caption d-none d-md-block text-start">
                                                    <h2 class="fw-bold display-5 mb-2">{{ $banner->title }}</h2>
                                                    @if($banner->description)
                                                        <p class="lead mb-3">{{ $banner->description }}</p>
                                                    @endif
                                                    @if($banner->link)
                                                        <a href="{{ $banner->link }}" class="btn btn-lg btn-primary px-4 py-2">
                                                            Xem ngay
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                @endforeach
                    @else
                        <!-- Fallback banners nếu không có dữ liệu -->
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1600&q=80"
                                class="d-block w-100 banner-img-large" alt="Banner 1">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <h2 class="fw-bold display-5 mb-2">BST Mùa Hè 2025</h2>
                                <p class="lead mb-3">Khám phá phong cách mới, trẻ trung &amp; năng động</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1600&q=80"
                                class="d-block w-100 banner-img-large" alt="Banner 2">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <h2 class="fw-bold display-5 mb-2">Ưu đãi Đặc Biệt</h2>
                                <p class="lead mb-3">Giảm giá lên đến 50% cho các sản phẩm hot trend</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1600&q=80"
                                class="d-block w-100 banner-img-large" alt="Banner 3">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <h2 class="fw-bold display-5 mb-2">BST Đầm Dạ Hội</h2>
                                <p class="lead mb-3">Sang trọng, quyến rũ cho mọi sự kiện</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=1600&q=80"
                                class="d-block w-100 banner-img-large" alt="Banner 4">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <h2 class="fw-bold display-5 mb-2">Áo Sơ Mi Nam Cao Cấp</h2>
                                <p class="lead mb-3">Lịch lãm, trẻ trung cho phái mạnh</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($banners && $banners->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
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
                @if($categories && $categories->count() > 0)
                    @foreach($categories as $category)
                        <div class="col d-flex">
                            <div class="category-box-modern text-center w-100 h-100">
                                <div class="category-image-container">
                                    <a href="{{ route('categories.show', $category->id) }}">
                                        <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80' }}"
                                            class="img-fluid category-img-modern mx-auto" alt="{{ $category->name }}">
                                    </a>
                                    <div class="category-overlay">
                                        <div class="category-info">
                                            <h4 class="category-title">{{ $category->name }}</h4>
                                            <span class="category-count">{{ $category->totalProductsCount() }} sản phẩm</span>
                                        </div>
                                    </div>
                                </div>
                                <button onclick="location.href = '{{ route('categories.show', $category->id) }}';"
                                    class="btn btn-category-modern mt-3">
                                    <span>Khám phá ngay</span>
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback categories nếu không có dữ liệu -->
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="category-box-modern text-center w-100 h-100">
                            <div class="category-image-container">
                                <a href="{{ route('categories.index') }}">
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
                            <button onclick="location.href = '{{ route('categories.index') }}';"
                                class="btn btn-category-modern mt-3">
                                <span>Khám phá ngay</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                @endif
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
                @if($coupons && $coupons->count() > 0)
                        @foreach($coupons->take(3) as $index => $coupon)
                                <div class="col">
                                    @php
                                        $colors = [
                                            ['bg' => 'linear-gradient(135deg, #fff9e6 0%, #ffe0b3 100%)', 'shadow' => 'rgba(255, 193, 7, 0.2)', 'shadow_hover' => 'rgba(255, 193, 7, 0.3)', 'decoration' => 'rgba(255, 193, 7, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #fff3cd 0%, #ffe082 100%)', 'icon_shadow' => 'rgba(255, 193, 7, 0.3)', 'icon_color' => 'text-warning', 'badge_class' => 'bg-warning text-dark', 'text_color' => 'text-danger', 'btn_class' => 'btn-danger', 'border_class' => 'border-warning text-warning'],
                                            ['bg' => 'linear-gradient(135deg, #e8f5e8 0%, #c3e6c3 100%)', 'shadow' => 'rgba(40, 167, 69, 0.2)', 'shadow_hover' => 'rgba(40, 167, 69, 0.3)', 'decoration' => 'rgba(40, 167, 69, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)', 'icon_shadow' => 'rgba(40, 167, 69, 0.3)', 'icon_color' => 'text-success', 'badge_class' => 'bg-success text-white', 'text_color' => 'text-success', 'btn_class' => 'btn-success', 'border_class' => 'border-success text-success'],
                                            ['bg' => 'linear-gradient(135deg, #e6f3ff 0%, #b3d9ff 100%)', 'shadow' => 'rgba(0, 123, 255, 0.2)', 'shadow_hover' => 'rgba(0, 123, 255, 0.3)', 'decoration' => 'rgba(0, 123, 255, 0.1)', 'icon_bg' => 'linear-gradient(135deg, #e3f2fd 0%, #90caf9 100%)', 'icon_shadow' => 'rgba(0, 123, 255, 0.3)', 'icon_color' => 'text-primary', 'badge_class' => 'bg-primary text-white', 'text_color' => 'text-primary', 'btn_class' => 'btn-primary', 'border_class' => 'border-primary text-primary']
                                        ];
                                        $colorScheme = $colors[$index % 3];

                                        // Format discount value with max discount info
                                        $discountText = '';
                                        if ($coupon->discount_type === 'percentage') {
                                            $discountText = "Giảm {$coupon->discount}%";
                                            if ($coupon->max_discount_amount) {
                                                $discountText .= " (tối đa " . format_vnd($coupon->max_discount_amount) . "₫)";
                                            }
                                        } else {
                                            $discountText = "Giảm " . format_vnd($coupon->discount) . "₫";
                                        }
                                    @endphp

                                    <div class="voucher-card h-100 text-center"
                                        style="background: {{ $colorScheme['bg'] }}; border-radius: 2rem; box-shadow: 0 10px 30px {{ $colorScheme['shadow'] }}; transition: all 0.3s ease; overflow: hidden; position: relative;"
                                        onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px {{ $colorScheme['shadow_hover'] }}';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px {{ $colorScheme['shadow'] }}';">
                                        <div class="voucher-decoration"
                                            style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: {{ $colorScheme['decoration'] }}; border-radius: 50%; transform: rotate(45deg);">
                                        </div>
                                        <div class="card-body d-flex flex-column align-items-center justify-content-between py-4 px-4"
                                            style="position: relative; z-index: 1;">
                                            <div class="voucher-icon mb-3">
                                                <i class="fa-solid fa-gift fa-3x {{ $colorScheme['icon_color'] }}"
                                                    style="background: {{ $colorScheme['icon_bg'] }}; border-radius: 50%; padding: 20px; box-shadow: 0 8px 20px {{ $colorScheme['icon_shadow'] }};"></i>
                                            </div>
                                            <div class="voucher-code mb-3">
                                                <span
                                                    class="badge {{ $colorScheme['badge_class'] }} px-4 py-2 rounded-pill shadow-sm fw-bold"
                                                    style="font-size:1.2rem; letter-spacing: 1px;">{{ $coupon->code }}</span>
                                            </div>
                                            <h4 class="fw-bold {{ $colorScheme['text_color'] }} mb-3" style="font-size:1.4rem;">
                                                {{ $discountText }}
                                            </h4>

                                            <!-- Description with conditions -->
                                            <div class="mb-3">
                                                @if($coupon->description)
                                                    <p class="text-muted mb-2" style="font-size:1rem;">{{ $coupon->description }}</p>
                                                @endif
                                                @if($coupon->min_order_amount)
                                                    <small class="text-info d-block">
                                                        <i class="fa-solid fa-shopping-cart me-1"></i>
                                                        Đơn tối thiểu: {{ format_vnd($coupon->min_order_amount) }}₫
                                                    </small>
                                                @endif
                                            </div>
                                            @if($coupon->start_date && $coupon->end_date)
                                                <p class="text-muted mb-2" style="font-size:1.1rem;">
                                                    Từ {{ $coupon->start_date->format('d/m/Y') }} đến {{ $coupon->end_date->format('d/m/Y') }}
                                                </p>
                                            @elseif($coupon->expires_at)
                                                <p class="text-muted mb-2" style="font-size:1.1rem;">
                                                    Hết hạn: {{ $coupon->expires_at->format('d/m/Y') }}
                                                </p>
                                            @else
                                                <p class="text-muted mb-2" style="font-size:1.1rem;">Không giới hạn thời gian</p>
                                            @endif

                                            @if($coupon->usage_limit > 0)
                                                            @php
                                                                $remaining = $coupon->remainingUsage();
                                                                $usagePercent = ($coupon->used_count / $coupon->usage_limit) * 100;
                                                                $hasUsed = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                                                            @endphp
                                                            <div class="mb-3">
                                                                @if($hasUsed)
                                                                    <div class="alert alert-info py-2 px-3 mb-2"
                                                                        style="border-radius: 15px; font-size: 0.9rem;">
                                                                        <i class="fa-solid fa-check-circle me-1"></i> Bạn đã sử dụng mã này rồi
                                                                    </div>
                                                                @endif
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <small class="text-muted">Còn lại:
                                                                        <strong>{{ $remaining }}/{{ $coupon->usage_limit }}</strong></small>
                                                                    <small class="text-muted">{{ round($usagePercent) }}% đã dùng</small>
                                                                </div>
                                                                <div class="progress" style="height: 6px; border-radius: 3px;">
                                                                    <div class="progress-bar {{ $remaining <= 5 ? 'bg-danger' : ($remaining <= 15 ? 'bg-warning' : 'bg-success') }}"
                                                                        role="progressbar" style="width: {{ $usagePercent }}%; transition: width 0.3s ease;"
                                                                        aria-valuenow="{{ $usagePercent }}" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                @if($remaining <= 5)
                                                                    <small class="text-danger fw-bold mt-1 d-block">
                                                                        <i class="fa-solid fa-fire"></i> Sắp hết!
                                                                    </small>
                                                                @elseif($remaining <= 15)
                                                                    <small class="text-warning fw-bold mt-1 d-block">
                                                                        <i class="fa-solid fa-clock"></i> Còn ít!
                                                                    </small>
                                                                @endif
                                                            </div>
                                            @else
                                                            @php
                                                                $hasUsed = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                                                            @endphp
                                                            @if($hasUsed)
                                                                <div class="alert alert-info py-2 px-3 mb-3" style="border-radius: 15px; font-size: 0.9rem;">
                                                                    <i class="fa-solid fa-check-circle me-1"></i> Bạn đã sử dụng mã này rồi
                                                                </div>
                                                            @else
                                                                <p class="text-success mb-3" style="font-size:0.9rem;">
                                                                    <i class="fa-solid fa-infinity"></i> Không giới hạn số lần sử dụng
                                                                </p>
                                                            @endif
                                            @endif
                                            @php
                                                $isDisabled = Auth::check() && $coupon->hasBeenUsedByUser(Auth::id());
                                                $isSaved = Auth::check() && in_array($coupon->code, $userSavedCoupons ?? []);
                                            @endphp
                                            <button
                                                class="btn {{ $isSaved ? 'btn-success' : $colorScheme['btn_class'] }} btn-lg fw-bold px-4 py-2 rounded-pill save-coupon-btn mb-3 shadow-lg {{ $isDisabled ? 'disabled' : '' }}"
                                                data-code="{{ $coupon->code }}" data-discount="{{ $discountText }}"
                                                data-description="{{ $coupon->description ?? 'Mã giảm giá đặc biệt' }}"
                                                data-discount-type="{{ $coupon->discount_type ?? 'percentage' }}"
                                                data-discount-value="{{ $coupon->discount }}"
                                                data-max-discount="{{ $coupon->max_discount_amount ?? '' }}"
                                                data-min-order="{{ $coupon->min_order_amount ?? '' }}"
                                                style="font-size:1.1rem; min-width: 180px; transition: all 0.3s ease; {{ $isDisabled ? 'opacity: 0.6; cursor: not-allowed;' : '' }}"
                                                {{ $isDisabled ? 'disabled' : '' }}
                                                onmouseover="{{ $isDisabled ? '' : 'this.style.transform=\'scale(1.05)\';' }}"
                                                onmouseout="{{ $isDisabled ? '' : 'this.style.transform=\'scale(1)\';' }}">
                                                <i
                                                    class="fa-solid fa-{{ $isDisabled ? 'check' : ($isSaved ? 'check' : 'bookmark') }} me-2"></i>
                                                {{ $isDisabled ? 'Đã sử dụng' : ($isSaved ? 'Đã lưu' : 'Lưu mã') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                @else
                    <!-- Thông báo không có voucher -->
                    <div class="col-12">
                        <div class="empty-voucher-state text-center py-5"
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
                            <div class="mb-4">
                                <i class="fa-solid fa-ticket-simple fa-4x text-muted" style="opacity: 0.5;"></i>
                            </div>
                            <h3 class="text-muted mb-3 fw-bold">Hiện tại không có voucher nào</h3>
                            <p class="text-muted mb-4" style="font-size: 1.1rem;">
                                Hệ thống chưa có mã giảm giá nào. Vui lòng quay lại sau để nhận những ưu đãi hấp dẫn!
                            </p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <a href="{{ route('client.coupons.index') }}"
                                    class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="fa-solid fa-search me-2"></i>Tìm mã giảm giá
                                </a>
                                <a href="{{ route('my-coupons') }}" class="btn btn-outline-warning rounded-pill px-4 py-2">
                                    <i class="fa-solid fa-bookmark me-2"></i>Mã đã lưu
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- View All Coupons Button -->
            <div class="d-flex justify-content-center mt-5">
                <div class="btn-group" role="group">
                    <a href="{{ route('client.coupons.index') }}"
                        class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3 fw-semibold me-3"
                        style="border: 2px solid #007bff; color: #007bff; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#007bff'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#007bff';">
                        <i class="fa-solid fa-list me-2"></i>
                        Xem tất cả mã giảm giá
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('my-coupons') }}"
                        class="btn btn-outline-warning btn-lg rounded-pill px-5 py-3 fw-semibold"
                        style="border: 2px solid #ffc107; color: #ffc107; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ffc107';">
                        <i class="fa-solid fa-bookmark me-2"></i>
                        Mã đã lưu
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="couponToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <span id="toastMessage">Đã lưu mã vào tài khoản!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize button states on page load
            updateButtonStates();

            // Show toast notification
            function showToast(message, type = 'success') {
                const toast = document.getElementById('couponToast');
                const toastMessage = document.getElementById('toastMessage');

                toastMessage.textContent = message;
                toast.className = `toast align-items-center text-white bg-${type} border-0`;

                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }

            // Update button states based on saved coupons
            function updateButtonStates() {
                const savedCoupons = JSON.parse(localStorage.getItem('savedCoupons') || '[]');
                const savedCodes = savedCoupons.map(c => c.code);

                document.querySelectorAll('.save-coupon-btn').forEach(button => {
                    const code = button.getAttribute('data-code');
                    const isDisabled = button.hasAttribute('disabled') || button.classList.contains('disabled');

                    if (!isDisabled && savedCodes.includes(code)) {
                        markAsSaved(button);
                    }
                });
            }

            // Mark button as saved
            function markAsSaved(button) {
                button.classList.remove('btn-primary', 'btn-danger', 'btn-warning');
                button.classList.add('btn-success');
                button.innerHTML = '<i class="fa-solid fa-check me-2"></i>Đã lưu';
                // Don't disable the button, just change appearance
            }

            // Save coupon via AJAX and localStorage
            function saveCoupon(couponId, couponCode, discountText) {
                return fetch('{{ route("client.coupons.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        coupon_id: couponId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Save to localStorage for immediate state persistence
                            let savedCoupons = JSON.parse(localStorage.getItem('savedCoupons') || '[]');
                            if (!savedCoupons.find(c => c.code === couponCode)) {
                                savedCoupons.push({
                                    code: couponCode,
                                    discount_text: discountText,
                                    savedAt: new Date().toISOString()
                                });
                                localStorage.setItem('savedCoupons', JSON.stringify(savedCoupons));
                            }

                            // Update header badge
                            updateHeaderCouponBadge(data.saved_count);
                            return true;
                        } else {
                            showToast(data.message, 'warning');
                            return false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Có lỗi xảy ra khi lưu mã giảm giá', 'danger');
                        return false;
                    });
            }

            // Update header coupon badge
            function updateHeaderCouponBadge(count = null) {
                const badge = document.getElementById('headerCouponCount');
                if (badge && count !== null) {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'inline-block';

                        // Add pulse animation
                        badge.style.animation = 'pulse 0.5s ease-in-out';
                        setTimeout(() => {
                            badge.style.animation = '';
                        }, 500);
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            // Handle save coupon buttons
            document.querySelectorAll('.save-coupon-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    // Check if button is disabled or already saved
                    if (btn.disabled || btn.classList.contains('disabled') || btn.classList.contains('btn-success')) {
                        if (btn.classList.contains('btn-success')) {
                            showToast('Mã này đã được lưu rồi!', 'info');
                        }
                        return;
                    }

                    @guest
                        showToast('Vui lòng đăng nhập để lưu mã giảm giá', 'warning');
                        return;
                    @endguest

                                                const couponCode = btn.getAttribute('data-code');
                    const discountText = btn.getAttribute('data-discount');

                    // Check if already saved in localStorage
                    const savedCoupons = JSON.parse(localStorage.getItem('savedCoupons') || '[]');
                    if (savedCoupons.find(c => c.code === couponCode)) {
                        showToast('Mã này đã được lưu rồi!', 'info');
                        markAsSaved(btn);
                        return;
                    }

                    // Find coupon ID from the current coupon list
                    const couponId = @json($coupons->pluck('id', 'code'));

                    if (!couponId[couponCode]) {
                        showToast('Không tìm thấy mã giảm giá', 'danger');
                        return;
                    }

                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang lưu...';

                    saveCoupon(couponId[couponCode], couponCode, discountText).then(success => {
                        if (success) {
                            markAsSaved(btn);
                            showToast(`Đã lưu mã ${couponCode} thành công!`, 'success');

                            // Update header badge if exists
                            const headerBadge = document.getElementById('headerCouponCount');
                            if (headerBadge) {
                                const savedCoupons = JSON.parse(localStorage.getItem('savedCoupons') || '[]');
                                updateHeaderCouponBadge(savedCoupons.length);
                            }
                        } else {
                            btn.innerHTML = originalHTML;
                        }
                    });
                });
            });
        });
    </script>
    <!-- Product Section Start -->
    <section class="product-section-3 py-5">
        <div class="container-fluid-lg">
            <div class="title mb-5">
                <h2 class="fw-bold" style="font-size: 2rem;">
                    <i class="fa-solid fa-fire text-danger me-2"></i>
                    Sản phẩm hot trong tháng
                </h2>
                <p class="text-muted" style="font-size: 1.1rem;">
                    Top sản phẩm bán chạy và được đánh giá cao nhất tháng {{ now()->format('m/Y') }}
                </p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse ($products as $index => $product)
                            <div class="col">
                                <div class="product-card-modern shadow-lg border-0 h-100 d-flex flex-column"
                                    style="border-radius: 1.5rem; overflow: hidden; transition: all 0.3s ease; position: relative; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);"
                                    onmouseover="this.style.transform='translateY(-15px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.2)'; this.querySelector('.product-image').style.transform='scale(1.1)';"
                                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 40px rgba(0, 0, 0, 0.1)'; this.querySelector('.product-image').style.transform='scale(1)';">

                                    <!-- Monthly Sales Badge -->

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

                                        <!-- Rating with Monthly Stats -->
                                        <div class="product-rating d-flex justify-content-center align-items-center mb-3">
                                            @php
                                                $avgRate = $product->avg_rating ?? $product->rates->where('status', 1)->avg('score');
                                                $avgRate = round($avgRate * 2) / 2;
                                                $ratingCount = $product->ratings_count ?? $product->rates->where('status', 1)->count();
                                            @endphp
                                            <div class="stars d-flex">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fa-solid fa-star {{ $i < $avgRate ? 'text-warning' : 'text-muted' }}"
                                                        style="font-size: 1rem; margin: 0 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.1);"></i>
                                                @endfor
                                            </div>
                                            <span class="text-muted ms-2 fw-medium" style="font-size: 0.9rem;">
                                                ({{ number_format($avgRate, 1) }}) • {{ $ratingCount }} đánh giá
                                            </span>
                                        </div>

                                        <!-- Monthly Performance Indicator -->
                                        @if($product->monthly_sales > 0)
                                            <div class="monthly-stats text-center mb-3">
                                                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill text-white">
                                                        <i class="fa-solid fa-trending-up me-1"></i>
                                                        {{ $product->monthly_sales }} lượt mua trong tháng
                                                    </span>
                                                </div>
                                                @if($avgRate >= 4.5 && $ratingCount >= 5)
                                                    <small class="text-warning fw-semibold">
                                                        <i class="fa-solid fa-star me-1"></i>
                                                        Được đánh giá cao
                                                    </small>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Price -->
                                        <div class="product-price d-flex flex-column align-items-center gap-1 mb-4">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold text-danger"
                                                        style="font-size: 1.4rem; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);">
                                                        {{ format_vnd($product->sale_price) }}₫
                                                    </span>
                                                    <span class="text-muted text-decoration-line-through" style="font-size: 1rem;">
                                                        {{ format_vnd($product->price) }}₫
                                                    </span>
                                                </div>
                                                <div class="text-success fw-semibold" style="font-size: 0.9rem;">
                                                    <i class="fa-solid fa-piggy-bank me-1"></i>
                                                    Tiết kiệm {{ format_vnd($product->price - $product->sale_price) }}₫
                                                </div>
                                            @else
                                                <span class="fw-bold text-danger"
                                                    style="font-size: 1.4rem; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);">
                                                    {{ format_vnd($product->price) }}₫
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
                @empty
                    <!-- No products found -->
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fa-solid fa-box-open fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Chưa có sản phẩm hot trong tháng</h4>
                            <p class="text-muted">Hãy quay lại sau để xem những sản phẩm bán chạy nhất!</p>
                        </div>
                    </div>
                @endforelse
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
                @if($news && $news->count() > 0)
                        @foreach($news->take(3) as $index => $article)
                                <div class="col">
                                    <div class="card border-0 shadow-lg h-100 news-card"
                                        style="border-radius: 2rem; overflow: hidden; transition: transform 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-10px)'"
                                        onmouseout="this.style.transform='translateY(0)'">
                                        <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                                            @php
                                                $newsImages = json_decode($article->image, true);
                                                $mainNewsImage = is_array($newsImages) && !empty($newsImages) ? $newsImages[0] : $article->image;
                                                // Fallback images nếu không có ảnh
                                                $fallbackImages = [
                                                    'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=80',
                                                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80',
                                                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80'
                                                ];
                                                $imageUrl = $mainNewsImage ? asset('storage/' . $mainNewsImage) : $fallbackImages[$index % 3];
                                            @endphp
                                            <img src="{{ $imageUrl }}" class="card-img-top img-fluid" alt="{{ $article->title }}"
                                                style="object-fit:cover; height:300px; transition: transform 0.3s ease;"
                                                onmouseover="this.style.transform='scale(1.05)';"
                                                onmouseout="this.style.transform='scale(1)';">
                                            <div class="position-absolute top-0 start-0 p-3">
                                                @php
                                                    $badges = ['Xu hướng', 'Bí quyết', 'Hot', 'Mới', 'Thời trang', 'Style'];
                                                    $badgeColors = ['bg-warning text-dark', 'bg-success text-white', 'bg-primary text-white', 'bg-info text-white', 'bg-secondary text-white', 'bg-danger text-white'];
                                                    $badgeIndex = $index % count($badges);
                                                @endphp
                                                <span
                                                    class="badge {{ $badgeColors[$badgeIndex] }} px-3 py-2 rounded-pill shadow-sm fw-bold">
                                                    {{ $badges[$badgeIndex] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-between p-4">
                                            <div class="mb-3">
                                                <div class="text-muted small mb-2">
                                                    <i
                                                        class="fa-solid fa-calendar-days me-2"></i>{{ $article->created_at->format('j \T\h\á\n\g n, Y') }}
                                                </div>
                                                <h4 class="card-title fw-bold text-dark mb-3" style="font-size:1.4rem; line-height: 1.4;">
                                                    {{ $article->title }}
                                                </h4>
                                                <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                                    {{ Str::limit(strip_tags($article->content), 150) }}
                                                </p>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('client.news.show', $article->id) }}"
                                                    class="btn btn-warning rounded-pill fw-semibold text-white px-4 py-2"
                                                    style="background: linear-gradient(135deg, #f9a825 0%, #f57c00 100%); border: none; box-shadow: 0 4px 15px rgba(249, 168, 37, 0.4);">
                                                    Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                                </a>
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="fa-solid fa-eye me-1"></i>
                                                    <span>{{ rand(200, 2000) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                @else
                    <!-- Fallback news nếu không có dữ liệu -->
                    <div class="col">
                        <div class="card border-0 shadow-lg h-100 news-card"
                            style="border-radius: 2rem; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative;"
                            onmouseover="this.style.transform='translateY(-15px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.15)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                            <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                                <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=80"
                                    class="card-img-top img-fluid" alt="Tin tức 1"
                                    style="object-fit:cover; height:300px; transition: transform 0.4s ease;"
                                    onmouseover="this.style.transform='scale(1.08)';"
                                    onmouseout="this.style.transform='scale(1)';">
                                <div class="position-absolute top-0 start-0 p-3">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm fw-bold"
                                        style="backdrop-filter: blur(10px); background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%) !important;">
                                        ✨ Xu hướng
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 end-0 p-3"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                    <div class="d-flex align-items-center text-white">
                                        <i class="fa-solid fa-heart me-2"></i>
                                        <span class="me-3">234</span>
                                        <i class="fa-solid fa-share me-2"></i>
                                        <span>56</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="mb-3">
                                    <div class="text-muted small mb-3 d-flex align-items-center">
                                        <i class="fa-solid fa-calendar-days me-2 text-warning"></i>
                                        <span>10 Tháng 7, 2025</span>
                                        <span class="mx-2">•</span>
                                        <i class="fa-solid fa-clock me-1"></i>
                                        <span>5 phút đọc</span>
                                    </div>
                                    <h4 class="card-title fw-bold text-dark mb-3"
                                        style="font-size:1.4rem; line-height: 1.4; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                                                                                               -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    </h4>
                                    <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                        Khám phá những mẫu thiết kế mới nhất cho mùa thu đông, mang phong cách sang trọng và
                                        hiện đại cùng những gam màu ấm áp. ✨
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('client.news.index') }}"
                                        class="btn btn-warning rounded-pill fw-semibold text-white px-4 py-2"
                                        style="background: linear-gradient(135deg, #f9a825 0%, #f57c00 100%); border: none; 
                                                                                                               box-shadow: 0 8px 25px rgba(249, 168, 37, 0.4); transition: all 0.3s ease;">
                                        <span class="position-relative z-index-2">
                                            Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </span>
                                    </a>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fa-solid fa-eye me-1 text-primary"></i>
                                        <span class="fw-semibold">1.2k</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border-0 shadow-lg h-100 news-card"
                            style="border-radius: 2rem; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative;"
                            onmouseover="this.style.transform='translateY(-15px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.15)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                            <div class="position-relative" style="overflow: hidden; border-radius: 2rem 2rem 0 0;">
                                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80"
                                    class="card-img-top img-fluid" alt="Tin tức 3"
                                    style="object-fit:cover; height:300px; transition: transform 0.4s ease;"
                                    onmouseover="this.style.transform='scale(1.08)';"
                                    onmouseout="this.style.transform='scale(1)';">
                                <div class="position-absolute top-0 start-0 p-3">
                                    <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm fw-bold">
                                        🔥 Hot
                                    </span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="mb-3">
                                    <div class="text-muted small mb-3 d-flex align-items-center">
                                        <i class="fa-solid fa-calendar-days me-2 text-success"></i>
                                        <span>1 Tháng 7, 2025</span>
                                        <span class="mx-2">•</span>
                                        <i class="fa-solid fa-clock me-1"></i>
                                        <span>3 phút đọc</span>
                                    </div>
                                    <h4 class="card-title fw-bold text-dark mb-3" style="font-size:1.4rem; line-height: 1.4;">
                                        Xu hướng phụ kiện 2025
                                    </h4>
                                    <p class="card-text text-secondary mb-3" style="font-size:1.1rem; line-height: 1.6;">
                                        Những món phụ kiện không thể thiếu để hoàn thiện phong cách thời trang của bạn. 💎
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('client.news.index') }}"
                                        class="btn btn-success rounded-pill fw-semibold text-white px-4 py-2">
                                        Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fa-solid fa-eye me-1 text-success"></i>
                                        <span class="fw-semibold">892</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- View More Button -->
            <div class="d-flex justify-content-center mt-5">
                <a href="{{ route('client.news.index') }}"
                    class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3 fw-semibold"
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
    <!-- News Section End -->

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

    <!-- JavaScript to fix search button being blocked by toast -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Home page: Fixing search button z-index conflict with toast...');

            // Fix search button being blocked by toast notifications
            function fixSearchButtonZIndex() {
                const searchButton = document.querySelector('header .search-button');
                const searchForm = document.querySelector('header .search-form');
                const searchInputGroup = document.querySelector('header .search-input-group');
                const topNav = document.querySelector('header .top-nav');

                if (searchButton) {
                    // Force high z-index on all search elements
                    if (topNav) {
                        topNav.style.cssText += 'position: relative !important; z-index: 1060 !important;';
                    }

                    if (searchForm) {
                        searchForm.style.cssText += 'position: relative !important; z-index: 1061 !important;';
                    }

                    if (searchInputGroup) {
                        searchInputGroup.style.cssText += 'position: relative !important; z-index: 1062 !important;';
                    }

                    searchButton.style.cssText += `
                                                    position: relative !important;
                                                    z-index: 1063 !important;
                                                    pointer-events: auto !important;
                                                    cursor: pointer !important;
                                                `;

                    // Add click handler
                    searchButton.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        console.log('Search button clicked successfully!');

                        // Close any open overlays
                        const searchFull = document.querySelector('.search-full.open');
                        if (searchFull) {
                            searchFull.classList.remove('open');
                        }

                        // Submit form
                        if (searchForm) {
                            searchForm.submit();
                        }
                    });

                    console.log('Search button z-index fix applied successfully!');
                }
            }

            // Apply fix immediately and after a delay
            fixSearchButtonZIndex();
            setTimeout(fixSearchButtonZIndex, 500);

            // Also fix whenever a toast is shown
            const toastContainer = document.querySelector('.toast-container');
            if (toastContainer) {
                toastContainer.style.zIndex = '1055';

                // Watch for toast changes
                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.type === 'childList' || mutation.type === 'attributes') {
                            // Re-apply fix when toast appears
                            setTimeout(fixSearchButtonZIndex, 100);
                        }
                    });
                });

                observer.observe(toastContainer, {
                    childList: true,
                    attributes: true,
                    subtree: true
                });
            }
        });
    </script>

@endsection