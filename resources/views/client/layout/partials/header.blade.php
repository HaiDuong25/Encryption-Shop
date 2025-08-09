<!-- Header Start -->
<header class="header-3">
    <div class="top-nav sticky-header sticky-header-2">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="navbar-top">
                        <button class="navbar-toggler d-xl-none d-block p-0 me-3" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#primaryMenu">
                            <span class="navbar-toggler-icon">
                                <i class="iconly-Category icli"></i>
                            </span>
                        </button>
                        <a href="{{ route('home') }}" class="web-logo nav-logo">
                            <img src="{{ asset('assets-front/images/logo/anhlogo2.png') }}"
                                class="img-fluid blur-up lazyload" alt="" style="width: 300px; height: auto;">
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

                            @foreach (request('categories', []) as $categoryId)
                                <input type="hidden" name="categories[]" value="{{ $categoryId }}">
                            @endforeach

                            @foreach (request('brands', []) as $brandId)
                                <input type="hidden" name="brands[]" value="{{ $brandId }}">
                            @endforeach

                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                            <div class="input-group shadow rounded search-input-group position-relative">
                                <input type="text" class="form-control border-0 search-input" id="search-product"
                                    name="keyword" placeholder="🔍 Tìm kiếm sản phẩm..."
                                    value="{{ request('keyword') }}" autocomplete="off">
                                <button type="submit" class="btn btn-primary search-button">
                                    <i class="fa fa-search me-1"></i> Tìm kiếm
                                </button>

                                <!-- Dropdown hiển thị kết quả tìm kiếm real-time -->
                                <div id="search-dropdown"
                                    class="search-dropdown position-absolute w-100 bg-white border rounded-bottom shadow-lg"
                                    style="top: 100%; left: 0; z-index: 9999 !important; max-height: 400px; overflow-y: auto; display: none;">
                                    <div id="search-results"></div>
                                </div>
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
                                        <span class="nav-link dropdown-toggle" role="button" id="dropdownDanhMuc"
                                            data-bs-toggle="dropdown" aria-expanded="false"
                                            data-href="{{ route('categories.index') }}">
                                            Danh mục
                                        </span>
                                        <div class="dropdown-menu p-3" style="min-width: 600px;">
                                            <div class="d-flex flex-wrap category-columns">
                                                @foreach ($categories as $parent)
                                                    <div class="category-group px-3">
                                                        <div class="category-parent text-center mb-2 fw-bold">
                                                            <a class="text-dark"
                                                                href="{{ route('categories.show', $parent->id) }}">
                                                                {{ $parent->name }}
                                                            </a>
                                                        </div>
                                                        <div
                                                            class="category-children d-flex flex-column align-items-center">
                                                            @foreach ($parent->children as $child)
                                                                <a class="dropdown-item py-1"
                                                                    href="{{ route('categories.show', $child->id) }}">
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
                            @php
                                $cartItems = collect([]);
                                $totalQuantity = 0;
                                $totalOrders = 0;
                                $savedCouponsCount = 0;
                                $wishlistCount = 0;

                                if (Auth::check()) {
                                    $cartItems = \App\Models\Cart::where('user_id', Auth::id())
                                        ->with(['product', 'variant'])
                                        ->get();
                                    $totalQuantity = $cartItems->sum('quantity');
                                    $totalOrders = \App\Models\Order::where('user_id', Auth::id())->count();
                                    $savedCouponsCount = \App\Models\UserSavedCoupon::where(
                                        'user_id',
                                        Auth::id(),
                                    )->count();
                                    $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count(); // Thêm dòng này
                                }
                            @endphp

                            <li>
                                <a href="javascript:void(0)" class="header-icon search-box search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="header-icon">
                                    <i class="fa-solid fa-heart"></i>
                                    @if (Auth::check() && $wishlistCount > 0)
                                        <small class="badge-number badge-light">{{ $wishlistCount }}</small>
                                    @endif
                                </a>
                            </li>
                            @php
                                $cartItems = collect([]);
                                $totalQuantity = 0;
                                $totalOrders = 0;
                                $savedCouponsCount = 0;
                                if (Auth::check()) {
                                    $cartItems = \App\Models\Cart::where('user_id', Auth::id())
                                        ->with(['product', 'variant'])
                                        ->get();
                                    $totalQuantity = $cartItems->sum('quantity');
                                    $totalOrders = \App\Models\Order::where('user_id', Auth::id())->count();
                                    $savedCouponsCount = \App\Models\UserSavedCoupon::where(
                                        'user_id',
                                        Auth::id(),
                                    )->count();
                                }
                            @endphp

                            <li>
                                <a href="{{ route('cart.index') }}" class="header-icon swap-icon">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    @if ($totalQuantity > 0)
                                        <small class="badge-number badge-light">{{ $totalQuantity }}</small>
                                    @endif
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('my-coupons') }}" class="header-icon" title="Mã giảm giá đã lưu">
                                    <i class="fa-solid fa-ticket"></i>
                                    @if (Auth::check() && $savedCouponsCount > 0)
                                        <small class="badge-number badge-light">{{ $savedCouponsCount }}</small>
                                    @endif
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('client.orders.index') }}" class="header-icon bag-icon">
                                    @if (Auth::check() && $totalOrders > 0)
                                        <small class="badge-number badge-light">{{ $totalOrders }}</small>
                                    @endif
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </a>
                            </li>
                            </li>
                        </ul>

                        @if (Auth::check())
                            <a href="{{ route('account.index') }}" class="user-box">
                                <span class="header-icon">
                                    @if (auth()->user()->avatar)
                                        <img id="header-user-avatar" class="user-profile rounded-circle"
                                            data-user-avatar src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                            alt="{{ auth()->user()->name }}"
                                            style="width: 35px; height: 35px; object-fit: cover;"
                                            title="Quản lý tài khoản">
                                    @else
                                        <i class="fa-solid fa-user" title="{{ auth()->user()->name }}"></i>
                                    @endif
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

    /* Style cho avatar user */
    .user-profile {
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Avatar transition mượt khi cập nhật */
    .user-profile.updating {
        opacity: 0.7;
        transform: scale(0.95);
    }
</style>

<style>
    /* Real-time Search Dropdown Styles */
    .search-dropdown {
        background: white;
        border: 1px solid #e0e0e0;
        border-top: none;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .search-result-item {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-item:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px);
    }

    .search-result-item.selected {
        background-color: #e3f2fd !important;
        transform: translateX(5px);
    }

    .search-result-image {
        border: 1px solid #e0e0e0;
        transition: transform 0.2s ease;
    }

    .search-result-item:hover .search-result-image {
        transform: scale(1.05);
    }

    .search-result-name {
        color: #333;
        line-height: 1.3;
    }

    .search-result-price {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .search-result-category {
        color: #6c757d;
        font-style: italic;
    }

    .search-view-all {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .search-view-all .btn-link {
        color: #007bff !important;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .search-view-all .btn-link:hover {
        background: rgba(0, 123, 255, 0.1);
        transform: translateY(-1px);
    }

    .search-input-group {
        position: relative;
        z-index: 1050;
    }

    .search-dropdown {
        z-index: 1060 !important;
    }

    /* Loading animation */
    .search-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        color: #6c757d;
    }

    .search-loading::after {
        content: '';
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .search-dropdown {
            max-height: 300px;
        }

        .search-result-item {
            padding: 0.75rem !important;
        }

        .search-result-image {
            width: 40px !important;
            height: 40px !important;
        }

        .search-result-name {
            font-size: 13px !important;
        }

        .search-result-price {
            font-size: 12px !important;
        }
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
    document.addEventListener("DOMContentLoaded", function() {
        const danhMucToggle = document.getElementById('dropdownDanhMuc');

        let isOpen = false;

        danhMucToggle.addEventListener('click', function(e) {
            if (!isOpen) {
                e.preventDefault(); // mở dropdown lần đầu
                isOpen = true;
            } else {
                // lần thứ 2 thì chuyển trang
                window.location.href = danhMucToggle.getAttribute('data-href');
            }
        });

        // Reset khi click ngoài menu
        document.addEventListener('click', function(e) {
            if (!danhMucToggle.contains(e.target)) {
                isOpen = false;
            }
        });

        // Update coupon badge on page load
        updateHeaderCouponBadge();

        // Real-time search functionality
        initializeRealTimeSearch();
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

    // Real-time search functionality
    function initializeRealTimeSearch() {
        const searchInput = document.getElementById('search-product');
        const searchDropdown = document.getElementById('search-dropdown');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;

        if (!searchInput || !searchDropdown || !searchResults) {
            return;
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                searchDropdown.style.display = 'none';
                return;
            }

            // Show loading
            searchResults.innerHTML =
                '<div class="search-loading p-3 text-center text-muted">Đang tìm kiếm...</div>';
            searchDropdown.style.display = 'block';

            searchTimeout = setTimeout(() => {
                const url = `{{ route('client.products.search') }}?query=${encodeURIComponent(query)}`;

                fetch(url)
                    .then(response => {
                        return response.json();
                    })
                    .then(products => {
                        displaySearchResults(products);
                    })
                    .catch(error => {
                        searchResults.innerHTML =
                            '<div class="p-3 text-danger text-center"><i class="fa fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tìm kiếm</div>';
                    });
            }, 300);
        });

        function displaySearchResults(products) {
            if (products.length === 0) {
                searchResults.innerHTML =
                    '<div class="p-3 text-muted text-center"><i class="fa fa-search me-2"></i>Không tìm thấy sản phẩm nào</div>';
                searchDropdown.style.display = 'block';
                return;
            }

            const html = products.map(product => `
                <a href="${product.url}" class="search-result-item d-flex align-items-center p-3 text-decoration-none border-bottom hover-bg-light">
                    <img src="${product.image || 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f8f9fa\'/%3E%3Ctext x=\'50%\' y=\'50%\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%236c757d\' font-family=\'Arial\' font-size=\'20\'%3E📦%3C/text%3E%3C/svg%3E'}"
                         alt="${product.name}"
                         class="search-result-image me-3 rounded"
                         style="width: 50px; height: 50px; object-fit: cover;"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'50\' height=\'50\' viewBox=\'0 0 50 50\'%3E%3Crect width=\'50\' height=\'50\' fill=\'%23f8f9fa\'/%3E%3Ctext x=\'50%\' y=\'50%\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%236c757d\' font-family=\'Arial\' font-size=\'20\'%3E📦%3C/text%3E%3C/svg%3E'">
                    <div class="search-result-info flex-grow-1">
                        <div class="search-result-name fw-semibold text-dark mb-1" style="font-size: 14px;">${product.name}</div>
                        ${product.category_name ? `<div class="search-result-category text-muted mb-1" style="font-size: 12px;"><i class="fa fa-tag me-1"></i>${product.category_name}</div>` : ''}
                        <div class="search-result-price text-primary fw-bold" style="font-size: 13px;">${product.formatted_price}₫</div>
                    </div>
                    <i class="fa fa-arrow-right text-muted ms-2"></i>
                </a>
            `).join('');

            // Add view all results link
            const viewAllHtml = `
                <div class="search-view-all border-top">
                    <button type="submit" class="btn btn-link text-primary w-100 py-3 text-decoration-none">
                        <i class="fa fa-search me-2"></i>Xem tất cả kết quả tìm kiếm
                        <i class="fa fa-arrow-right ms-2"></i>
                    </button>
                </div>
            `;

            searchResults.innerHTML = html + viewAllHtml;
            searchDropdown.style.display = 'block';
        }

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.style.display = 'none';
            }
        });

        // Show dropdown when focusing on input (if has content)
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && searchResults.innerHTML.trim()) {
                searchDropdown.style.display = 'block';
            }
        });

        // Keyboard navigation
        let selectedIndex = -1;
        searchInput.addEventListener('keydown', function(e) {
            const items = searchDropdown.querySelectorAll('.search-result-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                items[selectedIndex].click();
            } else if (e.key === 'Escape') {
                searchDropdown.style.display = 'none';
                selectedIndex = -1;
            }
        });

        function updateSelection(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }
    }

    // Make it globally available
    window.updateHeaderCouponBadge = updateHeaderCouponBadge;
</script>
