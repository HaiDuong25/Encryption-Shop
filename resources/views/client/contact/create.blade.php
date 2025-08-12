@extends('client.layout.main')

@section('title', 'Liên hệ')

@section('content')
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Liên hệ</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Liên hệ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Box Section Start -->
    <section class="contact-box-section">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6">
                    <div class="left-sidebar-box">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="contact-image mb-4">
                                    <img src="{{ asset('assets/images/inner-page/contact-us.png') }}"
                                        class="img-fluid blur-up lazyloaded" alt="">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="contact-title mb-3">
                                    <h3>Thông tin liên hệ</h3>
                                </div>
                                <div class="contact-detail">
                                    <div class="row g-4">
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box d-flex align-items-center mb-3">
                                                <i class="fa-solid fa-location-dot fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <h5 class="mb-1">Địa chỉ</h5>
                                                    <p class="mb-0">Tòa nhà FPT Polytechnic, Cổng số 2, 13 P. Trịnh Văn Bô,
                                                        Xuân Phương, Nam Từ Liêm, Hà Nội</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box d-flex align-items-center mb-3">
                                                <i class="fa-solid fa-envelope fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <h5 class="mb-1">Email</h5>
                                                    <p class="mb-0">Encryption@gmail.com</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                                            <div class="contact-detail-box d-flex align-items-center mb-3">
                                                <i class="fa-solid fa-phone fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <h5 class="mb-1">Điện thoại</h5>
                                                    <p class="mb-0">(+84) 355 490 337</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="map-container mt-4 rounded-3 overflow-hidden" style="height: 250px;">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8639306974364!2d105.74726179999999!3d21.038129799999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754322510180!5m2!1svi!2s"
                                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                        style="border:0;width:100%;height:100%;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-sidebar-box">
                        <h4 class="mb-4 fw-bold">Gửi tin nhắn cho chúng tôi</h4>
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div><strong>Thành công!</strong> {{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                    <div>
                                        <strong>Lỗi:</strong>
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('client.contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ tên</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Nhập họ tên của bạn" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Nhập email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-muted">(Không bắt
                                        buộc)</span></label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                            </div>
                            <div class="mb-4">
                                <label for="content" class="form-label">Nội dung</label>
                                <textarea name="content" id="content" class="form-control" rows="6"
                                    placeholder="Nhập nội dung bạn muốn gửi..." required>{{ old('content') }}</textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi liên hệ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Box Section End -->
@endsection

@push('scripts')
    <script>
        // Tự động ẩn thông báo sau 5 giây
        document.addEventListener('DOMContentLoaded', function () {
            const customAlerts = document.querySelectorAll('.custom-alert');
            if (customAlerts.length > 0) {
                setTimeout(() => {
                    customAlerts.forEach(alert => {
                        // Sử dụng Bootstrap's dismiss instance để đóng alert một cách mượt mà
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            }
        });
    </script>
@endpush