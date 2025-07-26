@extends('client.layout.main')

@section('content')
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-xxl-3 col-lg-4">
                <div class="dashboard-left-sidebar">
                    <div class="close-button d-flex d-lg-none">
                        <button class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="profile-box">
                        <div class="cover-image">
                            <img src="{{ asset('assets-front/images/inner-page/cover-img.jpg') }}" class="img-fluid blur-up lazyloaded" alt="">
                        </div>
                        <div class="profile-contain">
                            <div class="profile-image">
                                <div class="position-relative">
                                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets-front/images/inner-page/users/1.png') }}" class="blur-up update_img lazyloaded rounded-circle" alt="">
                                    <div class="cover-icon">
                                        <i class="fa-solid fa-pen">
                                            <input type="file" onchange="readURL(this,0)">
                                        </i>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-name text-center mt-3">
                                <h4>{{ auth()->user()->name }}</h4>
                                <h6 class="text-muted">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-pills user-nav-pills mt-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.editProfile') }}">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.changePassword') }}">Change Password</a>
                        </li>
                        {{-- Add more items like Order, Wishlist, etc. --}}
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-xxl-9 col-lg-8">
                <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show Menu</button>

                <div class="dashboard-right-sidebar">
                    <div class="dashboard-profile">
                        <div class="title">
                            <h2>My Profile</h2>
                            <span class="title-leaf">
                                <svg class="icon-width bg-gray">
                                    <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use>
                                </svg>
                            </span>
                        </div>

                        <!-- Profile Details -->
                        <div class="profile-detail dashboard-bg-box">
                            <div class="dashboard-title d-flex justify-content-between align-items-center">
                                <h3>Profile About</h3>
                                <a href="{{ route('account.editProfile') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                            </div>
                            <div class="profile mt-3">
                                <ul class="list-unstyled">
                                    <li class="mb-2 d-flex align-items-center">
                                        <h5 class="mb-1">Tài khoản</h5>
                                        <i class="feather-map-pin me-2"></i>
                                        <span>:{{ auth()->user()->Name }}</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <h5 class="mb-1">Số Điện thoại</h5>
                                        <i class="feather-mail me-2"></i>
                                        <span>: {{ auth()->user()->Phone }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="location-profile mt-3">
                                <ul class="list-unstyled">
                                    <li class="mb-2 d-flex align-items-center">
                                        <h5 class="mb-1">Địa chỉ</h5>
                                        <i class="feather-map-pin me-2"></i>
                                        <span>:{{ auth()->user()->address ?? 'No address set' }}</span>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center">
                                        <h5 class="mb-1">Email</h5>
                                        <i class="feather-mail me-2"></i>
                                        <span>: {{ auth()->user()->email }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="profile-description mt-3">
                                <p>{{ auth()->user()->bio ?? 'This user has not added a bio yet.' }}</p>
                            </div>
                        </div>

                        <!-- Login Details -->
                        <div class="profile-detail dashboard-bg-box mt-4">
                            <div class="dashboard-title">
                                <h3>Login Details</h3>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 d-flex justify-content-between align-items-center">
                                    <span><strong>Email:</strong> {{ auth()->user()->email }}</span>
                                    <a href="{{ route('account.editProfile') }}" class="btn btn-link btn-sm">Edit</a>
                                </div>
                                <div class="col-md-6 d-flex justify-content-between align-items-center">
                                    <span><strong>Password:</strong> ••••••••</span>
                                    <a href="{{ route('account.changePassword') }}" class="btn btn-link btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bạn có thể thêm thêm phần ảnh minh họa hoặc block đánh giá ở đây nếu muốn -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
