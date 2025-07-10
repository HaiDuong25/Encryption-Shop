<div class="dashboard-left-sidebar">
    <div class="profile-box">
        <div class="cover-image">
            <img src="{{ asset('assets-front/images/inner-page/cover-img.jpg') }}" class="img-fluid blur-up lazyloaded" alt="">
        </div>
        <div class="profile-contain">
            <div class="profile-image">
                <div class="position-relative">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets-front/images/inner-page/users/1.png') }}" class="blur-up update_img lazyloaded rounded-circle" alt="">
                </div>
            </div>
            <div class="profile-name text-center mt-3">
                <h4>{{ auth()->user()->name }}</h4>
                <h6 class="text-muted">{{ auth()->user()->email }}</h6>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills user-nav-pills mt-4">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('account.index') ? 'active' : '' }}" href="{{ route('account.index') }}">Profile</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('account.editProfile') ? 'active' : '' }}" href="{{ route('account.editProfile') }}">Edit Profile</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('account.changePassword') ? 'active' : '' }}" href="{{ route('account.changePassword') }}">Change Password</a></li>
    </ul>
</div>
