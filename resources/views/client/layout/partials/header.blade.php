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
    <img src="{{ asset('assets-front/images/logo/anhlogo2.png') }}"
         class="img-fluid blur-up lazyload"
         alt=""
         style="width: 300px; height: auto;">
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

                                    @php
                                        use App\Models\Category;
                                        $categories = Category::whereNull('parent_id')->with('children')->get();
                                    @endphp
                                    <li class="nav-item dropdown">
                                        <span class="nav-link dropdown-toggle" role="button" id="dropdownDanhMuc" data-bs-toggle="dropdown" aria-expanded="false" data-href="{{ route('categories.index') }}">
                                            Danh mục
                                        </span>
                                        <div class="dropdown-menu p-3" style="min-width: 600px;">
                                            <div class="d-flex flex-wrap category-columns">
                                        @foreach ($categories as $parent)
                                            <div class="category-group px-3">
                                                <div class="category-parent text-center mb-2 fw-bold">
                                                    <a class="text-dark" href="{{ route('categories.show', $parent->id) }}">
                                                        {{ $parent->name }}
                                                    </a>
                                                </div>
                                                <div class="category-children d-flex flex-column align-items-center">
                                                    @foreach ($parent->children as $child)
                                                        <a class="dropdown-item py-1" href="{{ route('categories.show', $child->id) }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                        </div>
                                    </li>


                                    <li class="nav-item dropdown dropdown-mega">
                                        <a class="nav-link" href="{{ route('client.products.index') }}"
                                            data-bs-toggle="dropdown-item">Sản phẩm</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('client.news.index') }}">Tin tức</a>
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
                                $savedCouponsCount = 0;
                                if (Auth::check()) {
                                    $cartItems = \App\Models\Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
                                    $totalQuantity = $cartItems->sum('quantity');
                                    $totalOrders = \App\Models\Order::where('user_id', Auth::id())->count();
                                    $savedCouponsCount = \App\Models\UserSavedCoupon::where('user_id', Auth::id())->count();
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
                                <a href="{{ route('my-coupons') }}" class="header-icon" title="Mã giảm giá đã lưu">
                                    <i class="fa-solid fa-ticket"></i>
                                    @if(Auth::check() && $savedCouponsCount > 0)
                                        <small class="badge-number badge-light">{{ $savedCouponsCount }}</small>
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
<style>
/* Dropdown toàn bộ */
.dropdown-menu {
    width: 100%;
    max-width: 1000px;
    background-color: #fff;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
}

/* Flex nhóm danh mục */
.category-columns {
    display: flex;
    flex-wrap: wrap;
    justify-content: start;
    gap: 20px;
}

/* Nhóm cha + con */
.category-group {
    min-width: 150px;
    border-right: 1px solid #ddd;
    padding-right: 15px;
}

/* Bỏ border phải cột cuối */
.category-group:last-child {
    border-right: none;
}

.category-parent {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 6px;
    margin-bottom: 8px;
    width: 100%;
    text-align: center;
}

/* Tên danh mục cha */
.category-parent a {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

/* Danh mục con */
.category-children a {
    font-size: 14px;
    color: #555;
    text-align: center;
}

/* Hover hiệu ứng */
.category-children a:hover {
    color: #007bff;
    background-color: #f0f0f0;
    border-radius: 4px;
}
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const danhMucToggle = document.getElementById('dropdownDanhMuc');

        let isOpen = false;

        danhMucToggle.addEventListener('click', function (e) {
            if (!isOpen) {
                e.preventDefault(); // mở dropdown lần đầu
                isOpen = true;
            } else {
                // lần thứ 2 thì chuyển trang
                window.location.href = danhMucToggle.getAttribute('data-href');
            }
        });

        // Reset khi click ngoài menu
        document.addEventListener('click', function (e) {
            if (!danhMucToggle.contains(e.target)) {
                isOpen = false;
            }
        });

        // Update coupon badge on page load
        updateHeaderCouponBadge();
    });

    // Function to update header coupon badge
    function updateHeaderCouponBadge() {
        const saved = localStorage.getItem('savedCoupons');
        const savedCoupons = saved ? JSON.parse(saved) : [];
        const badge = document.getElementById('headerCouponCount');

        if (badge) {
            badge.textContent = savedCoupons.length;
            badge.style.display = 'inline-block';
        }
    }

    // Make it globally available
    window.updateHeaderCouponBadge = updateHeaderCouponBadge;
                isOpen = false;
            }
        });
    });
</script>

