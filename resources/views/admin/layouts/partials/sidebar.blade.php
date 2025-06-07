<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="index.html" data-bs-original-title="" title="">
                <img class="img-fluid for-white" src="{{ asset('assets/images/logo/full-white.png') }}" alt="logo">
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="ri-apps-line status_toggle middle sidebar-toggle"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="index.html">
                <img class="img-fluid main-logo main-white" src="{{ asset('assets/images/logo/logo.png') }}" alt="logo">
                <img class="img-fluid main-logo main-dark" src="{{ asset('assets/images/logo/logo-white.png') }}" alt="logo">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"></li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}">
                            <i class="ri-home-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('products.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý sản phẩm</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('categories.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý danh mục</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('brands.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý thương hiệu</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('product-variants.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý biến thể</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('rates.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý đánh giá sản phẩm</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('coupons.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý mã giảm giá</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('contacts.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý liên hệ</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('news.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý tin tức</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('banners.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý banner</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('admin.payment-methods.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý phương thức thanh toán</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('admin.payments.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý thanh toán</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>
