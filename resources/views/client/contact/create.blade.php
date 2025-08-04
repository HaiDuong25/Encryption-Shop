@extends('client.layout.main')

@section('title', 'Liên hệ')

@section('content')

<style>
    /* Tổng thể */
    .contact-section {
        background-color: #f8f9fa; /* Màu nền nhẹ nhàng */
        padding: 60px 0;
    }

    h2.section-title {
        font-weight: 700;
        color: #343a40;
        margin-bottom: 1rem;
    }

    .section-subtitle {
        color: #6c757d;
        margin-bottom: 4rem;
    }

    /* Khối thông tin liên hệ */
    .contact-info-card {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .contact-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        transition: transform 0.3s ease;
    }

    .contact-info-item:hover {
        transform: translateX(5px);
    }

    .contact-info-icon {
        font-size: 24px;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 50%;
        color: #fff;
        background: linear-gradient(45deg, #007bff, #0056b3);
        margin-right: 20px;
        flex-shrink: 0; /* Ngăn icon bị co lại */
    }

    .contact-info-content h5 {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }

    .contact-info-content p {
        color: #6c757d;
        margin: 0;
    }

    /* Form liên hệ */
    .contact-form-card {
        padding: 30px;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 8px;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004080);
        transform: translateY(-2px);
    }

    /* Bản đồ */
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        margin-top: 30px;
        height: 250px; /* Chiều cao cố định cho bản đồ */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .map-container iframe {
        border: 0;
        width: 100%;
        height: 100%;
    }

    /* Alert custom style - Giữ lại và tinh chỉnh */
    .custom-alert {
        border-radius: 10px;
        padding: 15px 20px;
        background: linear-gradient(45deg, #d4edda, #c3e6cb);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        animation: slideDown 0.5s ease;
    }
    .custom-alert i { color: #28a745; }
    .alert-danger.custom-alert { background: linear-gradient(45deg, #f8d7da, #f5c6cb); }
    .alert-danger.custom-alert i { color: #dc3545; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="contact-section">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">📞 Liên Hệ Với Chúng Tôi</h2>
            <p class="section-subtitle">
                Chúng tôi luôn sẵn lòng lắng nghe bạn. Vui lòng điền vào biểu mẫu bên dưới hoặc sử dụng thông tin liên hệ.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <h4 class="mb-4 fw-bold">Thông tin liên hệ</h4>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-info-content">
                            <h5>Địa chỉ</h5>
                            <p>Tòa nhà FPT Polytechnic., Cổng số 2, 13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-info-content">
                            <h5>Email</h5>
                            <p>Encryption@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-info-content">
                            <h5>Điện thoại</h5>
                            <p>(+84) 355 490 337</p>
                        </div>
                    </div>

                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8639306974364!2d105.74726179999999!3d21.038129799999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754322510180!5m2!1svi!2s"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-form-card">
                    <h4 class="mb-4 fw-bold">Gửi tin nhắn cho chúng tôi</h4>

                    {{-- Thông báo thành công --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div><strong>Thành công!</strong> {{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Thông báo lỗi --}}
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
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nhập họ tên của bạn" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-muted">(Không bắt buộc)</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea name="content" id="content" class="form-control" rows="6" placeholder="Nhập nội dung bạn muốn gửi..." required>{{ old('content') }}</textarea>
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
</div>
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
