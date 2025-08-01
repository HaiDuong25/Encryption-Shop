@extends('client.layout.main')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="address-form-wrapper">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('client.account.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="form-card">
                    <div class="form-header">
                        <h4><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</h4>
                        <p class="text-muted">Xem và quản lý thông tin cá nhân của bạn</p>
                    </div>

                    <!-- Profile Summary -->
                    <div class="profile-summary mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="profile-avatar">
                                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets-front/images/inner-page/users/1.png') }}" 
                                         alt="Ảnh đại diện" class="rounded-circle">
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->phone)
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-phone me-1"></i>{{ auth()->user()->phone }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('account.editProfile') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h6><i class="fas fa-info-circle me-2"></i>Thông tin cá nhân</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <div class="form-control-static" tabindex="-1">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control-static" tabindex="-1">{{ auth()->user()->email }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <div class="form-control-static" tabindex="-1">
                                    {{ auth()->user()->phone ?: 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <div class="form-control-static" tabindex="-1">
                                    {{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật' }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <div class="form-control-static" tabindex="-1">
                                    @if(auth()->user()->gender == 'male')
                                        Nam
                                    @elseif(auth()->user()->gender == 'female')
                                        Nữ
                                    @elseif(auth()->user()->gender == 'other')
                                        Khác
                                    @else
                                        Chưa cập nhật
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->address)
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <div class="form-control-static" tabindex="-1">{{ auth()->user()->address }}</div>
                            </div>
                        @endif

                        @if(auth()->user()->bio)
                            <div class="mb-3">
                                <label class="form-label">Giới thiệu bản thân</label>
                                <div class="form-control-static" tabindex="-1">{{ auth()->user()->bio }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Security Information -->
                    {{-- <div class="form-section">
                        <h6><i class="fas fa-shield-alt me-2"></i>Bảo mật tài khoản</h6>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="security-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Mật khẩu</strong>
                                            <p class="text-muted small mb-0">Cập nhật lần cuối: {{ auth()->user()->updated_at->format('d/m/Y') }}</p>
                                        </div>
                                        <a href="{{ route('account.changePassword') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-key me-1"></i>Đổi mật khẩu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
 --}}
                    <!-- Quick Actions -->
                    {{-- <div class="form-section">
                        <h6><i class="fas fa-rocket me-2"></i>Truy cập nhanh</h6>
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('client.addresses.index') }}" class="quick-action-card">
                                    <div class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="content">
                                        <h6>Sổ địa chỉ</h6>
                                        <p>Quản lý địa chỉ giao hàng</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('orders.index') }}" class="quick-action-card">
                                    <div class="icon">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div class="content">
                                        <h6>Đơn hàng</h6>
                                        <p>Theo dõi đơn hàng của bạn</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('wishlist.index') }}" class="quick-action-card">
                                    <div class="icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="content">
                                        <h6>Yêu thích</h6>
                                        <p>Sản phẩm đã lưu</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-summary {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    padding: 25px;
    border-radius: 15px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
}

.profile-avatar {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #fff;
    overflow: hidden;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.form-control-static {
    padding: 12px 15px !important;
    min-height: 20px !important;
    color: #495057 !important;
    background: #fff !important;
    border: 2px solid #e9ecef !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: none !important;
    cursor: default !important;
    user-select: text !important;
    outline: none !important;
    box-shadow: none !important;
}

.form-control-static:hover {
    border-color: #e9ecef !important;
    background: #fff !important;
    outline: none !important;
    box-shadow: none !important;
}

.form-control-static:focus {
    border-color: #e9ecef !important;
    background: #fff !important;
    outline: none !important;
    box-shadow: none !important;
}

.security-item {
    padding: 20px;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.security-item:hover {
    border-color: #667eea;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
}

.security-item:last-child {
    margin-bottom: 0;
}

.quick-action-card {
    display: block;
    padding: 25px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
    border-color: #667eea;
}

.quick-action-card .icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.quick-action-card:hover .icon {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.quick-action-card .icon i {
    color: white;
    font-size: 24px;
}

.quick-action-card h6 {
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.quick-action-card p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
    line-height: 1.5;
}

/* Style cho các nút */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 500;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary {
    background: #f8f9fa !important;
    border: 2px solid #dee2e6 !important;
    color: #6c757d !important;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: #667eea !important;
    border-color: #667eea !important;
    color: white !important;
    transform: translateY(-1px);
}

/* Style cho form sections */
.form-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    border-left: 4px solid #667eea;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.form-section h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 16px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 14px;
}

/* Style cho alert */
.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    border: 1px solid rgba(40, 167, 69, 0.2);
    border-radius: 10px;
    color: #155724;
}

/* Style cho form header */
.form-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.form-header h4 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-header p {
    color: #6c757d;
    margin: 0;
}
</style>
@endsection
