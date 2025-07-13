<!-- Header Start -->
<header class="header-3">
    <div class="top-nav sticky-header sticky-header-2">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-top">
                        <button class="navbar-toggler d-xl-none d-block p-0 me-3" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                            <span class="navbar-toggler-icon">
                                <i class="iconly-Category icli"></i>
                            </span>
                        </button>
                        <a href="{{ route('home') }}" class="web-logo nav-logo">
                            <img src="{{ asset('assets-front/images/logo/4.png') }}" class="img-fluid blur-up lazyload"
                                alt="">
                        </a>

                        <div class="search-full">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search" class="font-light"></i>
                                </span>
                                <input type="text" class="form-control search-type"
                                    placeholder="Tìm kiếm áo, quần, phụ kiện...">
                                <span class="input-group-text close-search">
                                    <i data-feather="x" class="font-light"></i>
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('client.products.index') }}" method="GET" class="mb-4 search-form">

                            @foreach(request('categories', []) as $categoryId)
                                <input type="hidden" name="categories[]" value="{{ $categoryId }}">
                            @endforeach

                            @foreach(request('brands', []) as $brandId)
                                <input type="hidden" name="brands[]" value="{{ $brandId }}">
                            @endforeach

                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                            <div class="input-group shadow rounded search-input-group">
                                <input type="text" class="form-control border-0 search-input" id="search-product"
                                    name="keyword" placeholder="🔍 Tìm kiếm sản phẩm..."
                                    value="{{ request('keyword') }}">
                                <button type="submit" class="btn btn-primary search-button">
                                    <i class="fa fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>

                        </form>

                        <!-- <div class="rightside-menu support-sidemenu">
                                <div class="support-box">
                                    <div class="support-image">
                                        <img src="{{ asset('assets/images/icon/support.png') }}" class="img-fluid blur-up lazyload"
                                            alt="">
                                    </div>
                                    <div class="support-number">
                                        <h2>(123) 456 7890</h2>
                                        <h4>24/7 Support Center</h4>
                                    </div>
                                </div>
                            </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12 position-relative">
                <div class="main-nav nav-align justify-content-center">
                    <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky p-0 justify-content-center">
                        <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                            <div class="offcanvas-header navbar-shadow">
                                <h5>Menu</h5>
                                <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav mx-auto">
                                    <li class="nav-item dropdown dropdown-mega">
                                        <a class="nav-link" href="{{ route('home') }}"
                                            data-bs-toggle="dropdown-item">Trang chủ</a>
                                    </li>
                                    <li class="nav-item dropdown dropdown-mega">
                                        <a class="nav-link" href="{{ route('client.products.index') }}"
                                            data-bs-toggle="dropdown-item">Sản phẩm</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                            data-bs-toggle="dropdown">Danh mục quần áo</a>
                                        <div class="dropdown-menu dropdown-menu-3 dropdown-menu-2">
                                            <div class="row">
                                                <div class="col-xl-3">
                                                    <div class="dropdown-column m-0">
                                                        <h5 class="dropdown-header">Áo Nam</h5>
                                                        <a class="dropdown-item" href="#">Áo Thun</a>
                                                        <a class="dropdown-item" href="#">Áo Sơ Mi</a>
                                                        <a class="dropdown-item" href="#">Áo Polo</a>
                                                        <a class="dropdown-item" href="#">Áo Khoác</a>
                                                        <h5 class="custom-mt dropdown-header">Quần Nam</h5>
                                                        <a class="dropdown-item" href="#">Quần Jean</a>
                                                        <a class="dropdown-item" href="#">Quần Tây</a>
                                                        <a class="dropdown-item" href="#">Quần Short</a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3">
                                                    <div class="dropdown-column m-0">
                                                        <h5 class="dropdown-header">Áo Nữ</h5>
                                                        <a class="dropdown-item" href="#">Đầm/Váy</a>
                                                        <a class="dropdown-item" href="#">Áo Kiểu</a>
                                                        <a class="dropdown-item" href="#">Áo Sơ Mi Nữ</a>
                                                        <a class="dropdown-item" href="#">Áo Khoác Nữ</a>
                                                        <h5 class="custom-mt dropdown-header">Quần Nữ</h5>
                                                        <a class="dropdown-item" href="#">Quần Jean Nữ</a>
                                                        <a class="dropdown-item" href="#">Quần Tây Nữ</a>
                                                        <a class="dropdown-item" href="#">Chân Váy</a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3">
                                                    <div class="dropdown-column m-0">
                                                        <h5 class="custom-mt dropdown-header">Bộ sưu tập</h5>
                                                        <a class="dropdown-item" href="#">Hè 2025</a>
                                                        <a class="dropdown-item" href="#">Thu Đông 2025</a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 d-xl-block d-none">
                                                    <div class="dropdown-column m-0">
                                                        <div class="menu-img-banner">
                                                            <a class="text-title" href="#">
                                                                <img src="{{ asset('assets-front/images/mega-menu-fashion.png') }}"
                                                                    alt="banner thời trang">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('client.news.index') }}">Tin tức</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="about-us.html">Giới thiệu</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('client.contact.create') }}">Liên hệ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="rightside-menu">
                        <ul class="option-list-2">
                            <li>
                                <a href="javascript:void(0)" class="header-icon search-box search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="header-icon">
                                    <i class="fa-solid fa-heart"></i>
                                </a>
                            </li>


                            @php
                                $cartItems = collect([]);
                                $totalQuantity = 0;
                                $totalOrders = 0;
                                if (Auth::check()) {
                                    $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
                                    $totalQuantity = $cartItems->sum('quantity');
                                    $totalOrders = \App\Models\Order::where('user_id', Auth::id())->count();
                                }
                            @endphp

                            <li>
                                <a href="{{ route('cart.index') }}" class="header-icon swap-icon">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    @if($totalQuantity > 0)
                                        <small class="badge-number badge-light">{{ $totalQuantity }}</small>
                                    @endif
                                </a>
                            </li>


                            <li>
                                <a href="{{ route('client.orders.index') }}" class="header-icon bag-icon">
                                    @if(Auth::check() && $totalOrders > 0)
                                        <small class="badge-number badge-light">{{ $totalOrders }}</small>
                                    @endif
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </a>
                            </li>
                        </ul>

                        @if(Auth::check())
                            <a href="{{ route('account.index') }}" class="user-box">
                                <span class="header-icon">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="user-box">
                                    <div class="user-name">
                                        <h6 class="text-content">Xin chào, {{ Auth::user()->name }}</h6>
                                        <h4 class="mt-1">Đăng xuất</h4>
                                    </div>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login.form') }}" class="user-box">
                                <span class="header-icon">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <div class="user-name">
                                    <h6 class="text-content">Tài khoản của bạn</h6>
                                    <h4 class="mt-1">Đăng nhập</h4>
                                </div>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header End -->

<style>
    /* Đảm bảo menu căn giữa và không bị dính vào góc */
    .main-nav .navbar-nav {
        justify-content: center !important;
        width: 100%;
    }

    .main-nav .navbar-nav .nav-item {
        margin-left: 10px;
        margin-right: 10px;
    }
</style>
<style>
    .onhover-div {
        min-width: 320px !important;
        min-height: 120px !important;
        max-width: 400px;
        border: 2px solid #e0e0e0;
        background: #fff;
        z-index: 9999;
        padding: 16px 12px 12px 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .cart-list {
        min-height: 60px;
        margin-bottom: 8px;
        background: #f9f9f9;
    }

    .cart-list li {
        padding: 6px 0;
    }

    .cart-list p.text-center {
        color: #888;
        font-size: 15px;
        margin: 0;
    }
</style>
<style>
    /* Loại bỏ khung viền và nền cho các icon */
    .header-icon,
    .user-box {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    /* Nếu có border-radius */
    .header-icon,
    .user-box {
        border-radius: 0 !important;
    }

    /* Đảm bảo icon bên trong không bị đóng khung */
    .header-icon i,
    .user-box .header-icon i {
        box-shadow: none !important;
        background: transparent !important;
    }
</style>