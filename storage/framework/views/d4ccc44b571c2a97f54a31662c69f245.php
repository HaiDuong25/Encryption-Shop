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
                       <a href="<?php echo e(route('home')); ?>" class="web-logo nav-logo">
    <img src="<?php echo e(asset('assets-front/images/logo/anhlogo2.png')); ?>"
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

                        <form action="<?php echo e(route('client.products.index')); ?>" method="GET" class="mb-4 search-form">

                            <?php $__currentLoopData = request('categories', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="categories[]" value="<?php echo e($categoryId); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php $__currentLoopData = request('brands', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="brands[]" value="<?php echo e($brandId); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <input type="hidden" name="min_price" value="<?php echo e(request('min_price')); ?>">
                            <input type="hidden" name="max_price" value="<?php echo e(request('max_price')); ?>">

                            <div class="input-group shadow rounded search-input-group">
                                <input type="text" class="form-control border-0 search-input" id="search-product"
                                    name="keyword" placeholder="🔍 Tìm kiếm sản phẩm..."
                                    value="<?php echo e(request('keyword')); ?>">
                                <button type="submit" class="btn btn-primary search-button">
                                    <i class="fa fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>

                        </form>

                        <!-- <div class="rightside-menu support-sidemenu">
                                <div class="support-box">
                                    <div class="support-image">
                                        <img src="<?php echo e(asset('assets/images/icon/support.png')); ?>" class="img-fluid blur-up lazyload"
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
                                        <a class="nav-link" href="<?php echo e(route('home')); ?>"
                                            data-bs-toggle="dropdown-item">Trang chủ</a>
                                    </li>

                                    <?php
                                        use App\Models\Category;
                                        $categories = Category::whereNull('parent_id')->with('children')->get();
                                    ?>
                                    <li class="nav-item dropdown">
                                        <span class="nav-link dropdown-toggle" role="button" id="dropdownDanhMuc" data-bs-toggle="dropdown" aria-expanded="false" data-href="<?php echo e(route('categories.index')); ?>">
                                            Danh mục
                                        </span>
                                        <div class="dropdown-menu p-3" style="min-width: 600px;">
                                            <div class="d-flex flex-wrap category-columns">
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="category-group px-3">
                                                <div class="category-parent text-center mb-2 fw-bold">
                                                    <a class="text-dark" href="<?php echo e(route('categories.show', $parent->id)); ?>">
                                                        <?php echo e($parent->name); ?>

                                                    </a>
                                                </div>
                                                <div class="category-children d-flex flex-column align-items-center">
                                                    <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a class="dropdown-item py-1" href="<?php echo e(route('categories.show', $child->id)); ?>">
                                                            <?php echo e($child->name); ?>

                                                        </a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        </div>
                                    </li>


                                    <li class="nav-item dropdown dropdown-mega">
                                        <a class="nav-link" href="<?php echo e(route('client.products.index')); ?>"
                                            data-bs-toggle="dropdown-item">Sản phẩm</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('client.news.index')); ?>">Tin tức</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('client.contact.create')); ?>">Liên hệ</a>
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
                                <a href="<?php echo e(route('wishlist.index')); ?>" class="header-icon">
                                    <i class="fa-solid fa-heart"></i>
                                </a>
                            </li>


                            <?php
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
                            ?>

                            <li>
                                <a href="<?php echo e(route('cart.index')); ?>" class="header-icon swap-icon">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <?php if($totalQuantity > 0): ?>
                                        <small class="badge-number badge-light"><?php echo e($totalQuantity); ?></small>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('my-coupons')); ?>" class="header-icon" title="Mã giảm giá đã lưu">
                                    <i class="fa-solid fa-ticket"></i>
                                    <?php if(Auth::check() && $savedCouponsCount > 0): ?>
                                        <small class="badge-number badge-light"><?php echo e($savedCouponsCount); ?></small>
                                    <?php endif; ?>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('client.orders.index')); ?>" class="header-icon bag-icon">
                                    <?php if(Auth::check() && $totalOrders > 0): ?>
                                        <small class="badge-number badge-light"><?php echo e($totalOrders); ?></small>
                                    <?php endif; ?>
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </a>
                            </li>
                            </li>
                        </ul>

                        <?php if(Auth::check()): ?>
                            <a href="<?php echo e(route('account.index')); ?>" class="user-box">
                                <span class="header-icon">
                                    <?php if(auth()->user()->avatar): ?>
                                    <img id="header-user-avatar" 
                                         class="user-profile rounded-circle"
                                         data-user-avatar
                                         src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>"
                                         alt="<?php echo e(auth()->user()->name); ?>"
                                         style="width: 35px; height: 35px; object-fit: cover;"
                                         title="Quản lý tài khoản">
                                    <?php else: ?>
                                    <i class="fa-solid fa-user" title="<?php echo e(auth()->user()->name); ?>"></i>
                                    <?php endif; ?>
                                </span>
                            </a>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="user-box">
                                    <div class="user-name">
                                        <h6 class="text-content">Xin chào, <?php echo e(Auth::user()->name); ?></h6>
                                        <h4 class="mt-1">Đăng xuất</h4>
                                    </div>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login.form')); ?>" class="user-box">
                                <span class="header-icon">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <div class="user-name">
                                    <h6 class="text-content">Tài khoản của bạn</h6>
                                    <h4 class="mt-1">Đăng nhập</h4>
                                </div>
                            </a>
                        <?php endif; ?>

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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Avatar transition mượt khi cập nhật */
    .user-profile.updating {
        opacity: 0.7;
        transform: scale(0.95);
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

<?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/client/layout/partials/header.blade.php ENDPATH**/ ?>