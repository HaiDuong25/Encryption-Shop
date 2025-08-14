@extends('client.layout.main')

@section('title', $article->title)

@section('content')
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>{{ $article->title }}</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('client.news.index') }}">Tin tức</a></li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Details Section Start -->
    <section class="blog-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-xl-4 col-lg-5 d-lg-block d-none">
                    <div class="left-sidebar-box">
                        <div class="left-search-box">
                            <div class="search-box">
                                <form action="{{ route('client.news.index') }}" method="GET">
                                    <input type="search" class="form-control" name="search" value="{{ request('search') }}"
                                        placeholder="Search....">
                                </form>
                            </div>
                        </div>
                        <!-- Recent News -->
                        @if($relatedNews->count() > 0)
                                        <div class="accordion left-accordion-box" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne">
                                                        Bài viết liên quan
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="recent-post-box">
                                                            @foreach($relatedNews as $related)
                                                                                                <div class="recent-box mb-3">
                                                                                                    <a href="{{ route('client.news.show', $related->id) }}"
                                                                                                        class="recent-image">
                                                                                                        @php
                                                                                                            $relatedImages = json_decode($related->image, true);
                                                                                                            $relatedMainImage = is_array($relatedImages) && !empty($relatedImages) ? $relatedImages[0] : $related->image;
                                                                                                            $relatedImageUrl = $relatedMainImage ? asset('storage/' . $relatedMainImage) : asset('assets/images/inner-page/blog/1.jpg');
                                                                                                        @endphp
                                                                                                        <img src="{{ $relatedImageUrl }}" class="img-fluid blur-up lazyloaded"
                                                                                                            alt="{{ $related->title }}">
                                                                                                    </a>
                                                                                                    <div class="recent-detail">
                                                                                                        <a href="{{ route('client.news.show', $related->id) }}">
                                                                                                            <h5 class="recent-name">{{ Str::limit($related->title, 50) }}</h5>
                                                                                                        </a>
                                                                                                        <h6>
                                                                                                            {{ $related->created_at->format('d/m/Y') }} <i
                                                                                                                class="fa-regular fa-thumbs-up ms-2"></i>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        @endif
                        <!-- Chủ đề phổ biến đã bị xóa theo yêu cầu -->
                    </div>
                </div>
                <div class="col-xxl-9 col-xl-8 col-lg-7 ratio_50">
                    <div class="blog-detail-image rounded-3 mb-4">
                        @php
                            $newsImages = json_decode($article->image, true);
                            $mainNewsImage = is_array($newsImages) && !empty($newsImages) ? $newsImages[0] : $article->image;
                            $fallbackImages = [
                                asset('assets/images/inner-page/blog/1.jpg'),
                                asset('assets/images/inner-page/blog/2.jpg'),
                                asset('assets/images/inner-page/blog/3.jpg'),
                                asset('assets/images/inner-page/blog/4.jpg'),
                                asset('assets/images/inner-page/blog/5.jpg'),
                            ];
                            $imageUrl = $mainNewsImage ? asset('storage/' . $mainNewsImage) : $fallbackImages[array_rand($fallbackImages)];
                        @endphp
                        <img src="{{ $imageUrl }}" class="bg-img blur-up lazyload" alt="{{ $article->title }}" />
                        <div class="blog-image-contain">
                            <ul class="contain-list">
                                @php
                                    $tags = ['backpack', 'life style', 'organic'];
                                @endphp
                                @foreach($tags as $tag)
                                    <li>{{ $tag }}</li>
                                @endforeach
                            </ul>
                            <h2>{{ $article->title }}</h2>
                            <ul class="contain-comment-list">
                                <li>
                                    <div class="user-list">
                                        <i class="fa-regular fa-user"></i>
                                        <span>{{ $article->author_name ?? 'Admin' }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="user-list">
                                        <i class="fa-regular fa-calendar"></i>
                                        <span>{{ $article->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="user-list">
                                        <i class="fa-regular fa-message"></i>
                                        <span>{{ $article->comments_count ?? 0 }} Bình luận</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="blog-detail-contain">
                        {!! $article->content !!}
                    </div>
                    <!-- Comment Section -->
                    <div class="comment-box overflow-hidden">
                        <div class="leave-title">
                            <h3>Bình luận</h3>
                        </div>
                        <div class="user-comment-box">
                            <ul>
                                @foreach($comments as $comment)
                                    <li>
                                        <div class="user-box border-color">
                                            <div class="reply-button">
                                                <i class="fa-solid fa-reply"></i>
                                                <span class="theme-color">Trả lời</span>
                                            </div>
                                            <div class="user-image">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=0D8ABC&color=fff&size=64"
                                                    alt="{{ $comment->name ?? 'User' }}" />
                                                <div class="user-name">{{ $comment->name ?? 'User' }}</div>
                                            </div>
                                            <div class="user-contain">
                                                <p>{{ $comment->content ?? '' }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Leave Comment Section -->
                    <div class="leave-box">
                        <div class="leave-title mt-0">
                            <h3>Để lại bình luận</h3>
                        </div>
                        <div class="leave-comment">
                            @if(session('success'))
                                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('client.news.comment', $article->id) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-xxl-4 col-lg-12 col-sm-6">
                                        <div class="blog-input">
                                            <input type="text" class="form-control" name="name" placeholder="Họ và tên"
                                                required value="{{ old('name') }}" />
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-12 col-sm-6">
                                        <div class="blog-input">
                                            <input type="email" class="form-control" name="email" placeholder="Email"
                                                required value="{{ old('email') }}" />
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-12 col-sm-6">
                                        <div class="blog-input">
                                            <input type="tel" class="form-control" name="phone" placeholder="Phone"
                                                value="{{ old('phone') }}" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="blog-input">
                                            <textarea class="form-control" name="content" rows="4" placeholder="Bình luận"
                                                required>{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check d-flex mt-4 p-0">
                                    <input class="checkbox_animated" type="checkbox" value="1" name="save_info"
                                        id="flexCheckDefault" {{ old('save_info') ? 'checked' : '' }} />
                                    <label class="form-check-label text-content" for="flexCheckDefault">
                                        <span class="color color-1">Lưu thông tin cho lần sau</span>
                                    </label>
                                </div>
                                <button class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold" type="submit">Gửi
                                    bình luận</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->

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