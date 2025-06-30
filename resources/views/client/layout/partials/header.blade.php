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
                        <a href="index.html" class="web-logo nav-logo">
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

                        <div class="middle-box md-2">
                            <div class="searchbar-box-2 input-group d-xl-flex d-none">
                                <button class="btn search-icon" type="button">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <input type="text" class="form-control"
                                    placeholder="Tìm kiếm sản phẩm thời trang, thương hiệu...">
                                <button class="btn search-button" type="button">Tìm kiếm</button>
                            </div>
                        </div>

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

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                            data-bs-toggle="dropdown">Tin tức</a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#">Xu hướng thời trang</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">Bí quyết phối đồ</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">Khuyến mãi</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="about-us.html">Giới thiệu</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact-us.html">Liên hệ</a>
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
                                <a href="compare.html" class="header-icon">
                                    <small class="badge-number badge-light">2</small>
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </a>
                            </li>

                            <li class="onhover-dropdown">
                                <a href="javascript:void(0)" class="header-icon swap-icon">
                                    <i class="fa-regular fa-heart"></i>
                                </a>

                                <div class="onhover-div">
                                    <ul class="cart-list">
                                        <li>
                                            <div class="drop-cart">
                                                <a href="product-left-thumbnail.html" class="drop-image">
                                                    <img src="{{ asset('assets-front/images/vegetable/product/1.png') }}"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="drop-contain">
                                                    <a href="product-left-thumbnail.html">
                                                        <h5>Fantasy Crunchy Choco Chip Cookies</h5>
                                                    </a>
                                                    <h6><span>1 x</span> $80.58</h6>
                                                    <button class="close-button">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="drop-cart">
                                                <a href="product-left-thumbnail.html" class="drop-image">
                                                    <img src="{{ asset('assets-front/images/vegetable/product/2.png') }}"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="drop-contain">
                                                    <a href="product-left-thumbnail.html">
                                                        <h5>Peanut Butter Bite Premium Butter Cookies 600 g</h5>
                                                    </a>
                                                    <h6><span>1 x</span> $25.68</h6>
                                                    <button class="close-button">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="price-box">
                                        <h5>Price :</h5>
                                        <h4 class="theme-color fw-bold">$106.58</h4>
                                    </div>

                                    <div class="button-group">
                                        <a href="cart.html" class="btn btn-sm cart-button">View Cart</a>
                                        <a href="checkout.html" class="btn btn-sm cart-button theme-bg-color
                                                    text-white">Checkout</a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <a href="cart.html" class="header-icon bag-icon">
                                    <small class="badge-number badge-light">2</small>
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </a>
                            </li>
                        </ul>

                        <a href="user-dashboard.html" class="user-box">
                            <span class="header-icon">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <div class="user-name">
                                <h6 class="text-content">Tài khoản của bạn</h6>
                                <h4 class="mt-1">Xin chào, {{ Auth::user()->name }}</h4>
                            </div>
                        </a>
                        
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