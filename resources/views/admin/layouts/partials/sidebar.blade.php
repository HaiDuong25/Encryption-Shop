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
                                <a href="{{ route('products.index') }}">Products</a>
                            </li>
                            <li>
                                <a href="{{ route('products.create') }}">Add New Product</a>
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
                                <a href="{{ route('categories.index') }}">Category List</a>
                            </li>
                            <li>
                                <a href="{{ route('categories.create') }}">Add New Category</a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-list-check-2"></i>
                            <span>Brand</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('brands.index') }}">Brand List</a>
                            </li>
                            <li>
                                <a href="{{ route('brands.create') }}">Add New Brand</a>
                            </li>
                        </ul>
                    </li> -->


                    <!-- <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>Users</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="all-users.html">All users</a>
                            </li>
                            <li>
                                <a href="add-new-user.html">Add new user</a>
                            </li>
                        </ul>
                    </li> -->

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

                    <!-- <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('inventory.index') }}">
                            <i class="ri-archive-line"></i> {{-- icon đổi tùy ý --}}
                            <span>Inventory</span>
                        </a>
                    </li> -->

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('brands.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý thương hiệu</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('product-variants.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý biến thể</span>
                        </a>
                    </li>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('orders.index') }}">Order List</a>
                        </li>
                    </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-focus-3-line"></i>
                            <span>Quản lý đơn hàng</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('orders.index') }}">Danh sách đơn hàng</a>
                            </li>
                        </ul>
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
                        <a class="linear-icon-link sidebar-link sidebar-title" href="{{ route('banners.index') }}">
                            <i class="ri-store-3-line"></i>
                            <span>Quản lý banner</span>

                        </a>
                    </li>
                    <li class="sidebar-list">

                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('contacts.index') }}">
                                <i class="ri-phone-line"></i>
                                <span>Quản lý liên hệ</span>
                            </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-nav" href="{{ route('payment-methods.index') }}">
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
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>
