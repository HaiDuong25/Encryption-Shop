
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
                                <img src="{{ asset('assets-front/images/logo/4.png') }}" class="img-fluid blur-up lazyload" alt="">
                            </a>

                            <div class="search-full">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i data-feather="search" class="font-light"></i>
                                    </span>
                                    <input type="text" class="form-control search-type" placeholder="Search here..">
                                    <span class="input-group-text close-search">
                                        <i data-feather="x" class="font-light"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="middle-box">
                                <div class="center-box">
                                    <div class="location-box-2">
                                        <button class="btn location-button" data-bs-toggle="modal"
                                            data-bs-target="#locationModal">
                                            <i class="iconly-Location icli"></i>
                                            <span>Location</span>
                                            <i class="fa-solid fa-angle-down down-arrow"></i>
                                        </button>
                                    </div>

                                    <div class="searchbar-box-2 input-group d-xl-flex d-none">
                                        <button class="btn search-icon" type="button">
                                            <i class="iconly-Search icli"></i>
                                        </button>
                                        <input type="text" class="form-control"
                                            placeholder="Search for products, styles,brands...">
                                        <button class="btn search-button" type="button">Search</button>
                                    </div>
                                </div>
                            </div>

                            <div class="rightside-menu support-sidemenu">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12 position-relative">
                    <div class="main-nav nav-left-align">
                        <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky p-0">
                            <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                                <div class="offcanvas-header navbar-shadow">
                                    <h5>Menu</h5>
                                    <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <ul class="navbar-nav">
                                        <li class="nav-item dropdown dropdown-mega">
                                            <a class="nav-link dropdown-toggle ps-0" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Home</a>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="index.html">Kartshop</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-2.html">Sweetshop</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-3.html">Organic</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-4.html">Supershop</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-5.html">Classic shop</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-6.html">Furniture</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-7.html">Search Oriented</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-8.html">Category Focus</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-9.html">Fashion</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-10.html">Book</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="index-11.html">Digital</a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Shop</a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="shop-category-slider.html">Shop
                                                        Category Slider</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-category.html">Shop
                                                        Category Sidebar</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-banner.html">Shop Banner</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-left-sidebar.html">Shop Left
                                                        Sidebar</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-list.html">Shop List</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-right-sidebar.html">Shop
                                                        Right Sidebar</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="shop-top-filter.html">Shop Top
                                                        Filter</a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Product</a>

                                            <div class="dropdown-menu dropdown-menu-3 dropdown-menu-2">
                                                <div class="row">
                                                    <div class="col-xl-3">
                                                        <div class="dropdown-column m-0">
                                                            <h5 class="dropdown-header">
                                                                Product Pages </h5>
                                                            <a class="dropdown-item"
                                                                href="product-left-thumbnail.html">Product
                                                                Thumbnail</a>
                                                            <a class="dropdown-item" href="product-4-image.html">Product
                                                                Images</a>
                                                            <a class="dropdown-item" href="product-slider.html">Product
                                                                Slider</a>
                                                            <a class="dropdown-item" href="product-sticky.html">Product
                                                                Sticky</a>
                                                            <a class="dropdown-item"
                                                                href="product-accordion.html">Product Accordion</a>
                                                            <a class="dropdown-item" href="product-circle.html">Product
                                                                Tab</a>
                                                            <a class="dropdown-item" href="product-digital.html">Product
                                                                Digital</a>

                                                            <h5 class="custom-mt dropdown-header">Product Features
                                                            </h5>
                                                            <a class="dropdown-item" href="product-circle.html">Bundle
                                                                (Cross Sale)</a>
                                                            <a class="dropdown-item"
                                                                href="product-left-thumbnail.html">Hot Stock
                                                                Progress <label class="menu-label">New</label>
                                                            </a>
                                                            <a class="dropdown-item" href="product-sold-out.html">SOLD
                                                                OUT</a>
                                                            <a class="dropdown-item" href="product-circle.html">
                                                                Sale Countdown</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <div class="dropdown-column m-0">
                                                            <h5 class="dropdown-header">
                                                                Product Variants Style </h5>
                                                            <a class="dropdown-item"
                                                                href="product-rectangle.html">Variant Rectangle</a>
                                                            <a class="dropdown-item" href="product-circle.html">Variant
                                                                Circle <label class="menu-label">New</label></a>
                                                            <a class="dropdown-item"
                                                                href="product-color-image.html">Variant Image
                                                                Swatch</a>
                                                            <a class="dropdown-item" href="product-color.html">Variant
                                                                Color</a>
                                                            <a class="dropdown-item" href="product-radio.html">Variant
                                                                Radio Button</a>
                                                            <a class="dropdown-item"
                                                                href="product-dropdown.html">Variant Dropdown</a>
                                                            <h5 class="custom-mt dropdown-header">Product Features
                                                            </h5>
                                                            <a class="dropdown-item"
                                                                href="product-left-thumbnail.html">Sticky
                                                                Checkout</a>
                                                            <a class="dropdown-item" href="product-dynamic.html">Dynamic
                                                                Checkout</a>
                                                            <a class="dropdown-item" href="product-sticky.html">Secure
                                                                Checkout</a>
                                                            <a class="dropdown-item" href="product-bundle.html">Active
                                                                Product view</a>
                                                            <a class="dropdown-item" href="product-bundle.html">
                                                                Active
                                                                Last Orders
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <div class="dropdown-column m-0">
                                                            <h5 class="dropdown-header">
                                                                Product Features </h5>
                                                            <a class="dropdown-item" href="product-image.html">Product
                                                                Simple</a>
                                                            <a class="dropdown-item" href="product-rectangle.html">
                                                                Product Classified <label class="menu-label">New</label>
                                                            </a>
                                                            <a class="dropdown-item" href="product-size-chart.html">Size
                                                                Chart <label class="menu-label">New</label></a>
                                                            <a class="dropdown-item"
                                                                href="product-size-chart.html">Delivery &
                                                                Return</a>
                                                            <a class="dropdown-item"
                                                                href="product-size-chart.html">Product Review</a>
                                                            <a class="dropdown-item" href="product-expert.html">Ask
                                                                an Expert</a>
                                                            <h5 class="custom-mt dropdown-header">Product Features
                                                            </h5>
                                                            <a class="dropdown-item"
                                                                href="product-bottom-thumbnail.html">Product
                                                                Tags</a>
                                                            <a class="dropdown-item" href="product-image.html">Store
                                                                Information</a>
                                                            <a class="dropdown-item" href="product-image.html">Social
                                                                Share <label
                                                                    class="menu-label warning-label">Hot</label>
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="product-left-thumbnail.html">Related Products
                                                                <label class="menu-label warning-label">Hot</label>
                                                            </a>
                                                            <a class="dropdown-item"
                                                                href="product-right-thumbnail.html">Wishlist &
                                                                Compare</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 d-xl-block d-none">
                                                        <div class="dropdown-column m-0">
                                                            <div class="menu-img-banner">
                                                                <a class="text-title" href="product-circle.html">
                                                                    <img src="{{ asset('assets-front/images/mega-menu.png') }}"
                                                                        alt="banner">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="nav-item dropdown dropdown-mega">
                                            <a class="nav-link dropdown-toggle ps-xl-2 ps-0" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Mega Menu</a>

                                            <div class="dropdown-menu dropdown-menu-2 dropdown-menu-left">
                                                <div class="row">
                                                    <div class="dropdown-column col-xl-3">
                                                        <h5 class="dropdown-header">Daily Vegetables</h5>
                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Beans &
                                                            Brinjals</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Broccoli
                                                            & Cauliflower</a>

                                                        <a href="shop-left-sidebar.html" class="dropdown-item">Chilies,
                                                            Garlic, Lemon & Ginger</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Cut
                                                            Vegetables & Salads</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Gourd,
                                                            Cucumber & Pumpkin</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Herbs &
                                                            Sprouts</a>

                                                        <a href="demo-personal-portfolio.html"
                                                            class="dropdown-item">Lettuce
                                                            & Leafy</a>
                                                    </div>

                                                    <div class="dropdown-column col-xl-3">
                                                        <h5 class="dropdown-header">Baby Tender</h5>
                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Beans &
                                                            Brinjals</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Broccoli
                                                            & Cauliflower</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Chilies,
                                                            Garlic, Lemon & Ginger</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Cut
                                                            Vegetables & Salads</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Gourd,
                                                            Cucumber & Pumpkin</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Onions,
                                                            Potatoes & Tomatoes</a>

                                                        <a href="shop-left-sidebar.html" class="dropdown-item">Peas &
                                                            Corn</a>
                                                    </div>

                                                    <div class="dropdown-column col-xl-3">
                                                        <h5 class="dropdown-header">Exotic Vegetables</h5>
                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Asparagus
                                                            & Artichokes</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Avocados
                                                            & Peppers</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Broccoli
                                                            & Zucchini</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Celery,
                                                            Fennel & Leeks</a>

                                                        <a class="dropdown-item" href="shop-left-sidebar.html">Chilies &
                                                            Lime</a>
                                                    </div>

                                                    <div class="dropdown-column dropdown-column-img col-3"></div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Blog</a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="blog-detail.html">Blog Detail</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="blog-grid.html">Blog Grid</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="blog-list.html">Blog List</a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown new-nav-item">
                                            <label class="new-dropdown">New</label>
                                            <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Pages</a>
                                            <ul class="dropdown-menu">
                                                <li class="sub-dropdown-hover">
                                                    <a class="dropdown-item" href="javascript:void(0)">Email
                                                        Template <span class="new-text"><i
                                                                class="fa-solid fa-bolt-lightning"></i></span></a>
                                                    <ul class="sub-menu">
                                                        <li>
                                                            <a
                                                                href="https://themes.pixelstrap.com/fastkart/email-templete/abandonment-email.html">Abandonment</a>
                                                        </li>
                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/email-templete/offer-template.html">Offer
                                                                Template</a>
                                                        </li>
                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/email-templete/order-success.html">Order
                                                                Success</a>
                                                        </li>
                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/email-templete/reset-password.html">Reset
                                                                Password</a>
                                                        </li>
                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/email-templete/welcome.html">Welcome
                                                                template</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="sub-dropdown-hover">
                                                    <a class="dropdown-item" href="javascript:void(0)">Invoice
                                                        Template <span class="new-text"><i
                                                                class="fa-solid fa-bolt-lightning"></i></span></a>
                                                    <ul class="sub-menu">
                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/invoice/invoice-1.html">Invoice 1</a>
                                                        </li>

                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/invoice/invoice-2.html">Invoice 2</a>
                                                        </li>

                                                        <li>
                                                            <a href="https://themes.pixelstrap.com/fastkart/invoice/invoice-3.html">Invoice 3</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="404.html">404</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="about-us.html">About Us</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="cart.html">Cart</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="contact-us.html">Contact</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="checkout.html">Checkout</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="coming-soon.html">Coming Soon</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="compare.html">Compare</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="faq.html">Faq</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="order-success.html">Order
                                                        Success</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="order-tracking.html">Order
                                                        Tracking</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="otp.html">OTP</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="search.html">Search</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="user-dashboard.html">User
                                                        Dashboard</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="wishlist.html">Wishlist</a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                data-bs-toggle="dropdown">Seller</a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="seller-become.html">Become a
                                                        Seller</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="seller-dashboard.html">Seller
                                                        Dashboard</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="seller-detail.html">Seller Detail</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="seller-detail-2.html">Seller Detail
                                                        2</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="seller-grid.html">Seller Grid</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="seller-grid-2.html">Seller Grid 2</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="rightside-menu">
                            <ul class="option-list-2">
                                <li>
                                    <a href="javascript:void(0)" class="header-icon search-box search-icon">
                                        <i class="iconly-Search icli"></i>
                                    </a>
                                </li>

                                <li>
                                    <a href="compare.html" class="header-icon">
                                        <small class="badge-number badge-light">2</small>
                                        <i class="iconly-Swap icli"></i>
                                    </a>
                                </li>

                                <li class="onhover-dropdown">
                                    <a href="javascript:void(0)" class="header-icon swap-icon">
                                        <i class="iconly-Heart icli"></i>
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
                                        <i class="iconly-Bag-2 icli"></i>
                                    </a>
                                </li>
                            </ul>

                            <a href="user-dashboard.html" class="user-box">
                                <span class="header-icon">
                                    <i class="iconly-Profile icli"></i>
                                </span>
                                <div class="user-name">
                                    <h6 class="text-content">My Account</h6>
                                    <h4 class="mt-1">Jennifer V. Ward</h4>
                                </div>
                            </a>

                            <a target="_blank" class="btn mobile-app d-xxl-flex d-none"
                                href="https://play.google.com/store/games?utm_source=apac_med&amp;utm_medium=hasem&amp;utm_content=Oct0121&amp;utm_campaign=Evergreen&amp;pcampaignid=MKT-EDR-apac-in-1003227-med-hasem-py-Evergreen-Oct0121-Text_Search_BKWS-BKWS%7CONSEM_kwid_43700065205026415_creativeid_535350509927_device_c&amp;gclid=Cj0KCQjw8uOWBhDXARIsAOxKJ2H1K3VqdJFHodt0-XSnQzcuOuTP-s2aPBE6lG0QVOf8D5cJBsB-DxQaAkNAEALw_wcB&amp;gclsrc=aw.ds">
                                <div class="mobile-image">
                                    <img src="{{ asset('assets-front/images/icon/mobile.png') }}" class="img-fluid blur-up lazyload"
                                        alt="">
                                </div>

                                <div class="mobile-name">
                                    <h4>Download App</h4>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->