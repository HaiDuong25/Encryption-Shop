@extends('client.layout.main')

@section('title', 'Tin tức & Bài viết')

@section('content')
    <!-- Page Header -->
    <section class="page-header py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="text-white fw-bold display-5 mb-3">Tin tức & Bài viết</h1>
                    <p class="text-white-50 lead mb-0">Cập nhật những xu hướng thời trang mới nhất và bí quyết phối đồ</p>
                </div>
                <div class="col-lg-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-lg-end">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Tin tức</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- News Content -->
    <section class="news-content py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Search Bar -->
                    <div class="search-bar mb-5">
                        <form action="{{ route('client.news.index') }}" method="GET" class="row g-3">
                            <div class="col-md-9">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                                        value="{{ request('search') }}" placeholder="Tìm kiếm bài viết...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                                    <i class="fa-solid fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- News Grid -->
                    @if($news->count() > 0)
                                <div class="row g-4 mb-5">
                                    @foreach($news as $article)
                                                    <div class="col-md-6">
                                                        <article class="news-card h-100 shadow-sm border-0 rounded-4 overflow-hidden"
                                                            style="transition: all 0.3s ease;"
                                                            onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)';"
                                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.08)';">

                                                            <!-- Article Image -->
                                                            <div class="news-image position-relative" style="height: 250px; overflow: hidden;">
                                                                @php
                                                                    $newsImages = json_decode($article->image, true);
                                                                    $mainNewsImage = is_array($newsImages) && !empty($newsImages) ? $newsImages[0] : $article->image;
                                                                    $fallbackImages = [
                                                                        'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=80',
                                                                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80',
                                                                        'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80'
                                                                    ];
                                                                    $imageUrl = $mainNewsImage ? asset('storage/' . $mainNewsImage) : $fallbackImages[array_rand($fallbackImages)];
                                                                @endphp

                                                                <a href="{{ route('client.news.show', $article->id) }}">
                                                                    <img src="{{ $imageUrl }}" alt="{{ $article->title }}"
                                                                        class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;"
                                                                        onmouseover="this.style.transform='scale(1.05)';"
                                                                        onmouseout="this.style.transform='scale(1)';">
                                                                </a>

                                                                <!-- Category Badge -->
                                                                <div class="position-absolute top-0 start-0 m-3">
                                                                    @php
                                                                        $badges = ['Xu hướng', 'Bí quyết', 'Hot', 'Mới', 'Thời trang', 'Style'];
                                                                        $badgeColors = ['bg-warning', 'bg-success', 'bg-primary', 'bg-info', 'bg-secondary', 'bg-danger'];
                                                                        $randomIndex = array_rand($badges);
                                                                    @endphp
                                                                    <span
                                                                        class="badge {{ $badgeColors[$randomIndex] }} px-3 py-2 rounded-pill fw-bold shadow-sm">
                                                                        {{ $badges[$randomIndex] }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <!-- Article Content -->
                                                            <div class="card-body p-4">
                                                                <!-- Date -->
                                                                <div class="text-muted small mb-2">
                                                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                                                    {{ $article->created_at->format('j \T\h\á\n\g n, Y') }}
                                                                </div>

                                                                <!-- Title -->
                                                                <h5 class="card-title fw-bold mb-3" style="line-height: 1.4;">
                                                                    <a href="{{ route('client.news.show', $article->id) }}"
                                                                        class="text-decoration-none text-dark" onmouseover="this.style.color='#007bff';"
                                                                        onmouseout="this.style.color='#212529';">
                                                                        {{ $article->title }}
                                                                    </a>
                                                                </h5>

                                                                <!-- Excerpt -->
                                                                <p class="card-text text-secondary mb-3" style="line-height: 1.6;">
                                                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                                                </p>

                                                                <!-- Read More Button -->
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <a href="{{ route('client.news.show', $article->id) }}"
                                                                        class="btn btn-outline-primary rounded-pill px-4 fw-semibold"
                                                                        style="transition: all 0.3s ease;">
                                                                        Đọc tiếp
                                                                        <i class="fa-solid fa-arrow-right ms-2"></i>
                                                                    </a>
                                                                    <div class="text-muted small">
                                                                        <i class="fa-solid fa-eye me-1"></i>
                                                                        {{ rand(100, 1500) }} lượt xem
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $news->links() }}
                                </div>
                    @else
                        <!-- No News Found -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fa-solid fa-newspaper text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted mb-3">Không tìm thấy bài viết nào</h4>
                            <p class="text-muted mb-4">
                                @if(request('search'))
                                    Không có bài viết nào phù hợp với từ khóa "{{ request('search') }}"
                                @else
                                    Hiện tại chưa có bài viết nào được đăng
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('client.news.index') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Xem tất cả bài viết
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Recent News -->
                        @if($recentNews->count() > 0)
                                        <div class="recent-news bg-light rounded-4 p-4 mb-4">
                                            <h5 class="fw-bold mb-4">
                                                <i class="fa-solid fa-clock text-primary me-2"></i>
                                                Bài viết gần đây
                                            </h5>
                                            <div class="list-group list-group-flush">
                                                @foreach($recentNews as $recent)
                                                                        <div class="list-group-item bg-transparent border-0 px-0 py-3">
                                                                            <div class="row g-3">
                                                                                <div class="col-4">
                                                                                    @php
                                                                                        $recentImages = json_decode($recent->image, true);
                                                                                        $recentMainImage = is_array($recentImages) && !empty($recentImages) ? $recentImages[0] : $recent->image;
                                                                                        $recentImageUrl = $recentMainImage ? asset('storage/' . $recentMainImage) : 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=300&q=80';
                                                                                    @endphp
                                                                                    <img src="{{ $recentImageUrl }}" alt="{{ $recent->title }}"
                                                                                        class="img-fluid rounded-3 w-100"
                                                                                        style="aspect-ratio: 1; object-fit: cover;">
                                                                                </div>
                                                                                <div class="col-8">
                                                                                    <h6 class="fw-semibold mb-2" style="line-height: 1.3;">
                                                                                        <a href="{{ route('client.news.show', $recent->id) }}"
                                                                                            class="text-decoration-none text-dark"
                                                                                            onmouseover="this.style.color='#007bff';"
                                                                                            onmouseout="this.style.color='#212529';">
                                                                                            {{ Str::limit($recent->title, 50) }}
                                                                                        </a>
                                                                                    </h6>
                                                                                    <small class="text-muted">
                                                                                        <i class="fa-solid fa-calendar-days me-1"></i>
                                                                                        {{ $recent->created_at->format('d/m/Y') }}
                                                                                    </small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                @endforeach
                                            </div>
                                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .news-card {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .news-card:hover {
            border-color: rgba(0, 123, 255, 0.2);
        }

        .page-header {
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="1000,100 1000,0 0,100"/></svg>');
            background-size: cover;
        }

        .search-bar .input-group-text {
            background: white;
            border-color: #dee2e6;
        }

        .search-bar .form-control {
            border-color: #dee2e6;
        }

        .search-bar .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .sidebar .list-group-item:not(:last-child) {
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 768px) {
            .news-image {
                height: 200px !important;
            }

            .sidebar {
                margin-top: 2rem;
            }
        }
    </style>
@endsection