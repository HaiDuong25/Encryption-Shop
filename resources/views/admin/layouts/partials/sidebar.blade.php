<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="{{ route('admin.dashboard') }}" data-bs-original-title="" title="">
        <img class="img-fluid for-white" src="{{ asset('assets/images/logo/anhlogo2.png') }}" alt="logo" style="width: 200px; height: auto;">
    </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="index.html">
                <img class="img-fluid main-logo main-white" src="{{ asset('assets/images/logo/logo.png') }}" alt="logo">
                <img class="img-fluid main-logo main-dark" src="{{ asset('assets/images/logo/logo-white.png') }}"
                    alt="logo">
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
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý người dùng</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('users.index') }}">Danh sách người dùng</a>
                            </li>
                            <li>
                                <a href="{{ route('users.create') }}">Thêm người dùng</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý sản phẩm</span>
                        </a>

                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('products.index') }}">Danh sách sản phẩm</a>
                            </li>
                            <li>
                                <a href="{{ route('products.create') }}">Thêm sản phẩm mới</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list">

                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-list-check-2"></i>
                            <span>Quản lý danh mục</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.categories.index') }}">Danh sách danh mục</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.create') }}">Thêm danh mục mới</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.create-parent') }}">Thêm danh mục cha mới</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-list-check-2"></i>
                            <span>Quản lý thương hiệu</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('brands.index') }}">Danh sách thương hiệu</a>
                            </li>
                            <li>
                                <a href="{{ route('brands.create') }}">Thêm thương hiệu</a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>Roles</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="role.html">All roles</a>
                            </li>
                            <li>
                                <a href="create-role.html">Create Role</a>
                            </li>
                        </ul>
                    </li> -->

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('orders.index') }}">
                            <i class="ri-shopping-cart-line"></i>
                            <span>Quản lý đơn hàng</span>
                        </a>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('shipping-addresses.index') }}">
                            <i class="ri-map-pin-line"></i>
                            <span>Quản lý địa chỉ giao hàng</span>
                        </a>
                    </li>

                    <!-- <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('coupons.store') }}">Coupon List</a>
                        </li>

                        <li>
                            <a href="{{ route('coupons.create') }}">Create Coupon</a>
                        </li>
                    </ul> -->
                    <!-- <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('news.index') }}">
                            <i class="ri-newspaper-line"></i>
                            <span>News</span>
                        </a>
                    </li> -->
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('banners.index') }}">
                            <i class="ri-image-line"></i>
                            <span>Quản lý Banner</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('coupons.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý mã giảm giá</span>
                        </a>
                    </li>
                    <li class="sidebar-list">

                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('contacts.index') }}">
                            <i class="ri-phone-line"></i>
                            <span>Quản lý liên hệ</span>


                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('rates.index') }}">
                                <i class="ri-star-line"></i>
                                <span>Đánh giá sản phẩm</span>
                            </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('news.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý tin tức</span>

                        </a>

                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav"
                            href="{{ route('payment-methods.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý phương thức thanh toán</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('payments.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý thanh toán</span>
                        </a>
                    </li>
                    <li>
    <a href="{{ route('admin.returns.index') }}">
        <i class="fas fa-undo"></i> <span> Yêu cầu trả hàng </span>
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
