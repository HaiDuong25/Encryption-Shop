@extends('client.layout.main')

@section('title', $article->title)

@section('content')
    <!-- Simple Header Section -->
    <section class="article-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Article Title -->
                    <h1 class="fw-bold mb-3 text-dark"
                        style="font-size: clamp(24px, 3vw, 32px); line-height: 1.4; font-family: 'Inter', sans-serif;">
                        {{ $article->title }}
                    </h1>

                    <!-- Article Meta -->
                    <div class="d-flex align-items-center gap-4 text-muted mb-4" style="font-size: 14px;">
                        <div class="d-flex align-items-center">
                            <i class="far fa-calendar-alt me-2" style="color: #6c757d;"></i>
                            <span>{{ $article->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="far fa-eye me-2" style="color: #6c757d;"></i>
                            <span>{{ rand(150, 500) }} lượt xem</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="far fa-clock me-2" style="color: #6c757d;"></i>
                            <span>{{ rand(2, 5) }} phút đọc</span>
                        </div>
                    </div>

                    <!-- Category Tag -->
                    @php
                        $categories = ['Xu hướng thời trang', 'Mẹo phối đồ', 'Bộ sưu tập mới', 'Thời trang công sở', 'Streetwear'];
                        $categoryColors = ['#2563eb', '#059669', '#dc2626', '#7c3aed', '#ea580c'];
                        $randomCat = array_rand($categories);
                    @endphp
                    <div class="mb-4">
                        <span class="badge rounded-pill text-white px-3 py-2"
                            style="background-color: {{ $categoryColors[$randomCat] }}; font-size: 12px; font-weight: 500;">
                            {{ $categories[$randomCat] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clean Article Content -->
    <section class="article-content py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Main Article -->
                <div class="col-lg-8 col-xl-7">
                    <article class="article-main">
                        <!-- Featured Image -->
                        @php
                            $newsImages = json_decode($article->image, true);
                            $mainNewsImage = is_array($newsImages) && !empty($newsImages) ? $newsImages[0] : $article->image;
                            $fallbackImages = [
                                'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=80',
                                'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1200&q=80',
                                'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1200&q=80'
                            ];
                            $imageUrl = $mainNewsImage ? asset('storage/' . $mainNewsImage) : $fallbackImages[array_rand($fallbackImages)];
                        @endphp

                        <div class="featured-image mb-5">
                            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="img-fluid w-100 rounded-3"
                                style="height: 400px; object-fit: cover;">
                        </div>

                        <!-- Article Content -->
                        <div class="article-text">
                            {!! $article->content !!}
                        </div>

                        <!-- Share Section -->
                        <div class="share-section mt-5 pt-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-4 text-dark d-flex align-items-center">
                                        <i class="fas fa-share-alt me-2 text-primary"></i>
                                        Chia sẻ bài viết
                                    </h6>

                                    <!-- Enhanced Share Buttons -->
                                    <div class="share-buttons-container d-flex flex-wrap gap-3 mb-4">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                            target="_blank"
                                            class="share-btn facebook-btn d-flex align-items-center text-decoration-none">
                                            <div class="share-icon">
                                                <i class="fab fa-facebook-f"></i>
                                            </div>
                                            <span class="share-text">Facebook</span>
                                        </a>

                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}"
                                            target="_blank"
                                            class="share-btn twitter-btn d-flex align-items-center text-decoration-none">
                                            <div class="share-icon">
                                                <i class="fab fa-twitter"></i>
                                            </div>
                                            <span class="share-text">Twitter</span>
                                        </a>

                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                                            target="_blank"
                                            class="share-btn linkedin-btn d-flex align-items-center text-decoration-none">
                                            <div class="share-icon">
                                                <i class="fab fa-linkedin-in"></i>
                                            </div>
                                            <span class="share-text">LinkedIn</span>
                                        </a>

                                        <button onclick="copyToClipboard('{{ request()->fullUrl() }}')"
                                            class="share-btn copy-btn d-flex align-items-center border-0 bg-transparent">
                                            <div class="share-icon">
                                                <i class="fas fa-link"></i>
                                            </div>
                                            <span class="share-text">Sao chép</span>
                                        </button>

                                        <button onclick="printArticle()"
                                            class="share-btn print-btn d-flex align-items-center border-0 bg-transparent">
                                            <div class="share-icon">
                                                <i class="fas fa-print"></i>
                                            </div>
                                            <span class="share-text">In bài</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 col-xl-3">
                    <div class="sidebar mt-5 mt-lg-0">
                        <!-- Back to News Button - Always Visible -->
                        <div class="back-to-news-section mb-4">
                            <a href="{{ route('client.news.index') }}"
                                class="back-to-news-btn d-flex align-items-center text-decoration-none w-100">
                                <div class="back-icon">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                                <div class="back-text ms-3">
                                    <div class="fw-bold">Quay lại tin tức</div>
                                    <small class="text-muted">Xem thêm bài viết khác</small>
                                </div>
                            </a>
                        </div>
                        <!-- Related Articles -->
                        @if($relatedNews->count() > 0)
                                        <div class="related-section mb-5">
                                            <h5 class="fw-bold mb-4 text-dark" style="font-size: 18px;">Bài viết liên quan</h5>

                                            @foreach($relatedNews as $related)
                                                                <div class="related-item mb-4">
                                                                    <div class="row g-3">
                                                                        <div class="col-4">
                                                                            @php
                                                                                $relatedImages = json_decode($related->image, true);
                                                                                $relatedMainImage = is_array($relatedImages) && !empty($relatedImages) ? $relatedImages[0] : $related->image;
                                                                                $relatedImageUrl = $relatedMainImage ? asset('storage/' . $relatedMainImage) : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=200&q=80';
                                                                            @endphp
                                                                            <img src="{{ $relatedImageUrl }}" alt="{{ $related->title }}"
                                                                                class="img-fluid w-100 rounded-2" style="height: 80px; object-fit: cover;">
                                                                        </div>
                                                                        <div class="col-8">
                                                                            <h6 class="fw-semibold mb-2" style="font-size: 14px; line-height: 1.4;">
                                                                                <a href="{{ route('client.news.show', $related->id) }}"
                                                                                    class="text-decoration-none text-dark">
                                                                                    {{ Str::limit($related->title, 60) }}
                                                                                </a>
                                                                            </h6>
                                                                            <div class="text-muted" style="font-size: 12px;">
                                                                                {{ $related->created_at->format('d/m/Y') }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                            @endforeach
                                        </div>
                        @endif

                        <!-- Newsletter Icon Button -->
                        <div class="newsletter-icon-btn position-fixed d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-lg"
                            style="bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 1000; cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#newsletterModal">
                            <i class="fas fa-envelope" style="font-size: 24px;"></i>
                        </div>

                        <!-- Newsletter Modal -->
                        <div class="modal fade" id="newsletterModal" tabindex="-1" aria-labelledby="newsletterModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-dark" id="newsletterModalLabel">
                                            <i class="fas fa-envelope text-primary me-2"></i>
                                            Đăng ký nhận tin tức
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-2">
                                        <p class="text-muted mb-4">
                                            Nhận những xu hướng thời trang mới nhất và ưu đãi độc quyền từ chúng tôi
                                        </p>

                                        <form class="newsletter-modal-form">
                                            <div class="mb-3">
                                                <label for="newsletterName" class="form-label fw-semibold">Họ và tên</label>
                                                <input type="text" class="form-control" id="newsletterName"
                                                    placeholder="Nhập họ và tên của bạn" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="newsletterEmail" class="form-label fw-semibold">Email</label>
                                                <input type="email" class="form-control" id="newsletterEmail"
                                                    placeholder="Nhập email của bạn" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="newsletterPhone" class="form-label fw-semibold">Số điện thoại
                                                    (tùy chọn)</label>
                                                <input type="tel" class="form-control" id="newsletterPhone"
                                                    placeholder="Nhập số điện thoại">
                                            </div>
                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                                <label class="form-check-label text-muted" for="agreeTerms"
                                                    style="font-size: 14px;">
                                                    Tôi đồng ý nhận tin tức và ưu đãi từ cửa hàng
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" form="newsletter-modal-form" class="btn btn-primary px-4">
                                            <i class="fas fa-paper-plane me-2"></i>Đăng ký ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Enhanced Share Buttons */
        .share-buttons-container {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 1px solid #dee2e6;
        }

        .share-btn {
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-width: 120px;
            justify-content: center;
        }

        .share-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .share-btn:hover::before {
            left: 100%;
        }

        .share-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .share-text {
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        /* Facebook Button */
        .facebook-btn {
            background: linear-gradient(135deg, #1877f2 0%, #166fe5 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(24, 119, 242, 0.3);
        }

        .facebook-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(24, 119, 242, 0.4);
            color: white;
        }

        .facebook-btn .share-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Twitter Button */
        .twitter-btn {
            background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(29, 161, 242, 0.3);
        }

        .twitter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 161, 242, 0.4);
            color: white;
        }

        .twitter-btn .share-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* LinkedIn Button */
        .linkedin-btn {
            background: linear-gradient(135deg, #0077b5 0%, #005885 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 119, 181, 0.3);
        }

        .linkedin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 119, 181, 0.4);
            color: white;
        }

        .linkedin-btn .share-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Copy Button */
        .copy-btn {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .copy-btn .share-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Print Button */
        .print-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .print-btn .share-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Back to News Button */
        .back-to-news-section {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .back-to-news-btn {
            background: linear-gradient(135deg, #1a1a1a 0%, #343a40 100%);
            color: white;
            padding: 16px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 26, 26, 0.2);
            position: relative;
            overflow: hidden;
        }

        .back-to-news-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .back-to-news-btn:hover::before {
            left: 100%;
        }

        .back-to-news-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 26, 26, 0.3);
            color: white;
        }

        .back-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .back-to-news-btn:hover .back-icon {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        /* Responsive adjustments for share buttons */
        @media (max-width: 768px) {
            .share-buttons-container {
                padding: 15px;
            }

            .share-btn {
                min-width: 100px;
                padding: 10px 15px;
            }

            .share-icon {
                width: 28px;
                height: 28px;
                margin-right: 8px;
            }

            .share-text {
                font-size: 13px;
            }

            .back-to-news-btn {
                padding: 12px 16px;
            }

            .back-icon {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 576px) {
            .share-buttons-container {
                gap: 8px !important;
            }

            .share-btn {
                min-width: 90px;
                padding: 8px 12px;
            }

            .share-text {
                font-size: 12px;
            }
        }

        /* Newsletter icon button */
        .newsletter-icon-btn {
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        .newsletter-icon-btn:hover {
            transform: scale(1.1);
            background-color: #0d6efd !important;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4) !important;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        /* Newsletter modal styling */
        .modal-content {
            border-radius: 20px;
        }

        .modal-header {
            padding: 2rem 2rem 0 2rem;
        }

        .modal-body {
            padding: 1rem 2rem;
        }

        .modal-footer {
            padding: 0 2rem 2rem 2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Responsive adjustments for newsletter icon */
        @media (max-width: 768px) {
            .newsletter-icon-btn {
                width: 55px !important;
                height: 55px !important;
                bottom: 20px !important;
                right: 20px !important;
            }

            .newsletter-icon-btn i {
                font-size: 20px !important;
            }
        }

        @media (max-width: 576px) {
            .newsletter-icon-btn {
                width: 50px !important;
                height: 50px !important;
                bottom: 15px !important;
                right: 15px !important;
            }

            .newsletter-icon-btn i {
                font-size: 18px !important;
            }

            .modal-dialog {
                margin: 10px;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* Clean Fashion Store Styling */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            letter-spacing: -0.025em;
            color: #1a1a1a;
        }

        /* Breadcrumb styling */
        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: #6c757d;
            font-weight: 500;
        }

        .breadcrumb-item a:hover {
            color: #000 !important;
            text-decoration: none;
        }

        /* Article content styling */
        .article-text {
            font-size: 16px;
            line-height: 1.7;
            color: #374151;
        }

        .article-text h1,
        .article-text h2,
        .article-text h3,
        .article-text h4,
        .article-text h5,
        .article-text h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #1a1a1a;
        }

        .article-text h2 {
            font-size: 24px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }

        .article-text p {
            margin-bottom: 1.5rem;
        }

        .article-text img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 2rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .article-text blockquote {
            border-left: 4px solid #e5e7eb;
            background: #f9fafb;
            padding: 1.5rem 2rem;
            margin: 2rem 0;
            border-radius: 0 8px 8px 0;
            font-style: italic;
        }

        .article-text ul,
        .article-text ol {
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .article-text li {
            margin-bottom: 0.5rem;
        }

        /* Related articles styling */
        .related-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            padding: 0.5rem;
        }

        .related-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }

        .related-item a {
            color: #374151;
            transition: color 0.2s ease;
        }

        .related-item a:hover {
            color: #000;
        }

        /* Share buttons */
        .share-buttons .btn {
            transition: all 0.2s ease;
        }

        .share-buttons .btn:hover {
            transform: translateY(-1px);
        }

        /* Newsletter card */
        .newsletter-card {
            border: 1px solid #e5e7eb;
            transition: box-shadow 0.2s ease;
        }

        .newsletter-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .newsletter-form .form-control:focus {
            border-color: #374151;
            box-shadow: 0 0 0 0.25rem rgba(55, 65, 81, 0.1);
        }

        .newsletter-form-bottom .form-control:focus {
            border-color: #374151;
            box-shadow: 0 0 0 0.25rem rgba(55, 65, 81, 0.1);
        }

        /* Button styling */
        .btn-dark {
            background-color: #1a1a1a;
            border-color: #1a1a1a;
            transition: all 0.2s ease;
        }

        .btn-dark:hover {
            background-color: #000;
            border-color: #000;
            transform: translateY(-1px);
        }

        .btn-outline-primary:hover,
        .btn-outline-info:hover,
        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .featured-image img {
                height: 250px !important;
            }

            .share-buttons {
                justify-content: center;
            }

            .share-buttons .btn {
                flex: 1;
                margin-bottom: 0.5rem;
            }

            .newsletter-form-bottom {
                flex-direction: column;
                align-items: stretch;
            }

            .newsletter-form-bottom .form-control {
                max-width: none !important;
                margin-bottom: 1rem;
            }

            .article-text {
                font-size: 15px;
            }

            .sidebar {
                margin-top: 3rem;
            }
        }

        @media (max-width: 576px) {
            .related-item .row {
                --bs-gutter-x: 0.75rem;
            }

            .related-item h6 {
                font-size: 13px !important;
            }

            .related-item .text-muted {
                font-size: 11px !important;
            }
        }

        /* Smooth animations */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        /* Clean focus styles */
        .form-control:focus,
        .btn:focus {
            outline: none;
        }
    </style>

    <script>
        // Copy to clipboard function with enhanced feedback
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                const btn = event.target.closest('.copy-btn');
                const originalIcon = btn.querySelector('.share-icon i');
                const originalText = btn.querySelector('.share-text');

                originalIcon.className = 'fas fa-check';
                originalText.textContent = 'Đã sao chép!';
                btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';

                setTimeout(function () {
                    originalIcon.className = 'fas fa-link';
                    originalText.textContent = 'Sao chép';
                    btn.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
                }, 2000);
            }).catch(function () {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    const btn = event.target.closest('.copy-btn');
                    const originalIcon = btn.querySelector('.share-icon i');
                    const originalText = btn.querySelector('.share-text');

                    originalIcon.className = 'fas fa-check';
                    originalText.textContent = 'Đã sao chép!';
                    btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';

                    setTimeout(function () {
                        originalIcon.className = 'fas fa-link';
                        originalText.textContent = 'Sao chép';
                        btn.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
                    }, 2000);
                } catch (err) {
                    console.error('Copy failed', err);
                }
                document.body.removeChild(textArea);
            });
        }

        // Print article function
        function printArticle() {
            const printBtn = event.target.closest('.print-btn');
            const originalIcon = printBtn.querySelector('.share-icon i');
            const originalText = printBtn.querySelector('.share-text');

            originalIcon.className = 'fas fa-spinner fa-spin';
            originalText.textContent = 'Đang in...';

            setTimeout(function () {
                window.print();
                originalIcon.className = 'fas fa-print';
                originalText.textContent = 'In bài';
            }, 500);
        }

        // Enhanced social sharing tracking
        function initSocialSharing() {
            const shareButtons = document.querySelectorAll('.share-btn');
            shareButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    const platform = this.querySelector('.share-text').textContent.toLowerCase();
                    console.log(`Shared via: ${platform}`);

                    // Add ripple effect
                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    ripple.style.cssText = `
                            position: absolute;
                            border-radius: 50%;
                            background: rgba(255, 255, 255, 0.6);
                            transform: scale(0);
                            animation: ripple-animation 0.6s linear;
                            pointer-events: none;
                        `;

                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        }

        // Add ripple animation keyframes
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
                @keyframes ripple-animation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
        document.head.appendChild(rippleStyle);

        // Newsletter modal form submission
        document.addEventListener('DOMContentLoaded', function () {
            const newsletterModalForm = document.querySelector('.newsletter-modal-form');
            if (newsletterModalForm) {
                // Set the form attribute for the submit button
                const submitBtn = document.querySelector('button[form="newsletter-modal-form"]');
                if (submitBtn) {
                    submitBtn.setAttribute('form', 'newsletter-modal-form');
                    newsletterModalForm.setAttribute('id', 'newsletter-modal-form');
                }

                newsletterModalForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const nameInput = this.querySelector('#newsletterName');
                    const emailInput = this.querySelector('#newsletterEmail');
                    const phoneInput = this.querySelector('#newsletterPhone');
                    const agreeCheckbox = this.querySelector('#agreeTerms');
                    const submitButton = document.querySelector('button[form="newsletter-modal-form"]');

                    // Validation
                    if (!nameInput.value.trim()) {
                        nameInput.classList.add('is-invalid');
                        nameInput.focus();
                        return;
                    }

                    if (!emailInput.value || !emailInput.value.includes('@')) {
                        emailInput.classList.add('is-invalid');
                        emailInput.focus();
                        return;
                    }

                    if (!agreeCheckbox.checked) {
                        agreeCheckbox.classList.add('is-invalid');
                        return;
                    }

                    // Remove any previous invalid states
                    [nameInput, emailInput, agreeCheckbox].forEach(input => {
                        input.classList.remove('is-invalid');
                    });

                    const originalBtnText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
                    submitButton.disabled = true;

                    // Simulate API call
                    setTimeout(function () {
                        submitButton.innerHTML = '<i class="fas fa-check me-2"></i>Thành công!';
                        submitButton.classList.remove('btn-primary');
                        submitButton.classList.add('btn-success');

                        setTimeout(function () {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('newsletterModal'));
                            modal.hide();

                            // Reset form
                            newsletterModalForm.reset();
                            submitButton.innerHTML = originalBtnText;
                            submitButton.classList.remove('btn-success');
                            submitButton.classList.add('btn-primary');
                            submitButton.disabled = false;

                            // Show success toast/notification
                            const toast = document.createElement('div');
                            toast.className = 'position-fixed bg-success text-white p-3 rounded shadow';
                            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
                            toast.innerHTML = `
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <div>
                                                <div class="fw-bold">Đăng ký thành công!</div>
                                                <small>Cảm ơn bạn đã đăng ký nhận tin</small>
                                            </div>
                                        </div>
                                    `;
                            document.body.appendChild(toast);

                            setTimeout(() => {
                                toast.remove();
                            }, 5000);

                        }, 1500);
                    }, 2000);
                });

                // Remove invalid class when user starts typing
                const inputs = newsletterModalForm.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('input', function () {
                        this.classList.remove('is-invalid');
                    });
                });

                document.querySelector('#agreeTerms').addEventListener('change', function () {
                    this.classList.remove('is-invalid');
                });
            }
        });

        // Newsletter form submission for mini widget
        // Newsletter form submission (legacy - keeping for compatibility)
        const legacyNewsletterForm = document.querySelector('.newsletter-form');
        if (legacyNewsletterForm) {
            legacyNewsletterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const btn = this.querySelector('button');
                const input = this.querySelector('input');
                const originalText = btn.innerHTML;

                // Validate email
                if (!input.value || !input.value.includes('@')) {
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 3000);
                    return;
                }

                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                btn.disabled = true;
                input.disabled = true;

                setTimeout(function () {
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    setTimeout(function () {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        input.disabled = false;
                        input.value = '';

                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-light alert-dismissible fade show mt-2';
                        successMsg.innerHTML = `
                                            <i class="fa-solid fa-check-circle text-success me-2"></i>
                                            Cảm ơn bạn đã đăng ký! 🎉
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        `;
                        btn.parentNode.parentNode.appendChild(successMsg);

                        setTimeout(() => {
                            if (successMsg.parentNode) {
                                successMsg.remove();
                            }
                        }, 5000);
                    }, 1000);
                }, 2000);
            });

            // Reading progress bar
            function updateReadingProgress() {
                const article = document.querySelector('.article-text');
                const progressBar = document.querySelector('.reading-progress');

                if (!article || !progressBar) return;

                const articleTop = article.offsetTop;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                const articleBottom = articleTop + articleHeight;
                const windowBottom = scrollTop + windowHeight;

                if (scrollTop >= articleTop && scrollTop <= articleBottom - windowHeight) {
                    const progress = ((scrollTop - articleTop) / (articleHeight - windowHeight)) * 100;
                    progressBar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
                } else if (scrollTop > articleBottom - windowHeight) {
                    progressBar.style.width = '100%';
                } else {
                    progressBar.style.width = '0%';
                }
            }

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Lazy loading for images in article content
            function lazyLoadImages() {
                const images = document.querySelectorAll('.article-text img[data-src]');
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            img.classList.add('animate-fade-in');
                            observer.unobserve(img);
                        }
                    });
                });

                images.forEach(img => imageObserver.observe(img));
            }

            // Initialize animations on scroll
            function initScrollAnimations() {
                const animateElements = document.querySelectorAll('.animate-slide-right, .hover-lift');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationPlayState = 'running';
                        }
                    });
                }, { threshold: 0.1 });

                animateElements.forEach(el => {
                    el.style.animationPlayState = 'paused';
                    observer.observe(el);
                });
            }

            // Social sharing enhancements
            function enhanceSocialSharing() {
                const shareButtons = document.querySelectorAll('.share-buttons a');
                shareButtons.forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        // Add click analytics here if needed
                        console.log('Shared via:', this.textContent.trim());
                    });
                });
            }

            // Add hover effects for better UX
            function addHoverEffects() {
                const cards = document.querySelectorAll('.related-item, .recent-item');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function () {
                        this.style.transform = 'translateX(5px)';
                    });
                    card.addEventListener('mouseleave', function () {
                        this.style.transform = 'translateX(0)';
                    });
                });
            }

            // Initialize everything when DOM is loaded
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize social sharing
                initSocialSharing();

                // Add scroll event listener for reading progress
                window.addEventListener('scroll', updateReadingProgress);

                // Initialize other features
                lazyLoadImages();
                initScrollAnimations();
                enhanceSocialSharing();
                addHoverEffects();

                // Auto-hide navbar on scroll down, show on scroll up
                let lastScrollTop = 0;
                const navbar = document.querySelector('.navbar');

                if (navbar) {
                    window.addEventListener('scroll', function () {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        if (scrollTop > lastScrollTop && scrollTop > 100) {
                            navbar.style.transform = 'translateY(-100%)';
                        } else {
                            navbar.style.transform = 'translateY(0)';
                        }
                        lastScrollTop = scrollTop;
                    });
                }

                // Add keyboard navigation
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'ArrowUp' && e.ctrlKey) {
                        e.preventDefault();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else if (e.key === 'ArrowDown' && e.ctrlKey) {
                        e.preventDefault();
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    }
                });
            });

            // Performance optimization: Throttle scroll events
            function throttle(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Apply throttling to scroll events
            window.addEventListener('scroll', throttle(updateReadingProgress, 10));
    </script>
@endsection