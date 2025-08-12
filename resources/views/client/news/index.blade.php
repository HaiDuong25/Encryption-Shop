@extends('client.layout.main')

@section('title', 'Tin tức & Bài viết')

@section('content')
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Tin tức & Bài viết</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Tin tức</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Section Start -->
    <section class="blog-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-4">
                <div class="col-xxl-9 col-xl-8 col-lg-7 order-lg-2">
                    <div class="row g-4">
                        @if($news->count() > 0)
                                        @foreach($news as $article)
                                                        <div class="col-12">
                                                            <div class="blog-box blog-list wow fadeInUp">
                                                                <div class="blog-image">
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
                                                                    <a href="{{ route('client.news.show', $article->id) }}">
                                                                        <img src="{{ $imageUrl }}" class="blur-up lazyload" alt="{{ $article->title }}">
                                                                    </a>
                                                                </div>
                                                                <div class="blog-contain blog-contain-2">
                                                                    <div class="blog-label">
                                                                        <span class="time"><i class="fa-regular fa-clock"></i>
                                                                            <span>{{ $article->created_at->format('d/m/Y') }}</span></span>
                                                                        <span class="super"><i class="fa-regular fa-user"></i>
                                                                            <span>{{ $article->author_name ?? 'Admin' }}</span></span>
                                                                    </div>
                                                                    <a href="{{ route('client.news.show', $article->id) }}">
                                                                        <h3>{{ $article->title }}</h3>
                                                                    </a>
                                                                    <p>{{ Str::limit(strip_tags($article->content), 120) }}</p>
                                                                    <button onclick="location.href = '{{ route('client.news.show', $article->id) }}';"
                                                                        class="blog-button">Đọc tiếp <i class="fa-solid fa-right-long"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                        @endforeach
                        @else
                            <div class="col-12 text-center py-5">
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
                    <!-- Pagination -->
                    <nav class="custom-pagination">
                        <ul class="pagination justify-content-center">
                            {{ $news->links() }}
                        </ul>
                    </nav>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-5 order-lg-1">
                    <div class="left-sidebar-box wow fadeInUp">
                        <div class="left-search-box mb-4">
                            <div class="search-box">
                                <form action="{{ route('client.news.index') }}" method="GET">
                                    <input type="search" class="form-control" name="search" value="{{ request('search') }}"
                                        placeholder="Search....">
                                </form>
                            </div>
                        </div>
                        <!-- Recent News -->
                        @if($recentNews->count() > 0)
                                        <div class="accordion left-accordion-box" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne">
                                                        Bài viết gần đây
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="recent-post-box">
                                                            @foreach($recentNews as $recent)
                                                                                                <div class="recent-box mb-3">
                                                                                                    <a href="{{ route('client.news.show', $recent->id) }}" class="recent-image">
                                                                                                        @php
                                                                                                            $recentImages = json_decode($recent->image, true);
                                                                                                            $recentMainImage = is_array($recentImages) && !empty($recentImages) ? $recentImages[0] : $recent->image;
                                                                                                            $recentImageUrl = $recentMainImage ? asset('storage/' . $recentMainImage) : asset('assets/images/inner-page/blog/1.jpg');
                                                                                                        @endphp
                                                                                                        <img src="{{ $recentImageUrl }}" class="img-fluid blur-up lazyloaded"
                                                                                                            alt="{{ $recent->title }}">
                                                                                                    </a>
                                                                                                    <div class="recent-detail">
                                                                                                        <a href="{{ route('client.news.show', $recent->id) }}">
                                                                                                            <h5 class="recent-name">{{ Str::limit($recent->title, 50) }}</h5>
                                                                                                        </a>
                                                                                                        <h6>
                                                                                                            {{ $recent->created_at->format('d/m/Y') }} <i
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
                        <!-- Tags -->
                        <div class="accordion-item mt-4">
                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapseThree">Chủ đề phổ biến</button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                                <div class="accordion-body pt-0">
                                    <div class="product-tags-box">
                                        @php
                                            $tags = ['Thời trang', 'Xu hướng', 'Phong cách', 'Bí quyết', 'Outfit', 'Street Style', 'Mùa hè', 'Thu đông'];
                                        @endphp
                                        <ul>
                                            @foreach($tags as $tag)
                                                <li>
                                                    <a href="javascript:void(0)">{{ $tag }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection