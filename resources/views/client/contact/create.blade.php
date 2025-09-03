@extends('client.layout.main')

@section('title', 'Liên hệ')
<style>
    /* --- Global & Typography --- */
    :root {
        --primary-color: #0d6efd;
        --primary-rgb: 13, 110, 253;
        --light-gray: #f8faff;
        --border-color: #eef2f7;
        --text-dark: #212529;
        --text-muted: #6c757d;
    }

    .section-bg {
        background-color: var(--light-gray);
    }

    h2, h3, h6 {
        color: var(--text-dark);
        font-weight: 700;
    }

    p {
        color: var(--text-muted);
        line-height: 1.7;
    }

    /* --- Breadcrumb / Page Header --- */
    .breadcrumb-section {
        background-color: #fff;
        border-bottom: 1px solid var(--border-color);
        padding-top: 1.5rem !important; /* Ghi đè pt-0 */
        padding-bottom: 1.5rem;
    }

    .breadcrumb-contain h2 {
        font-size: 2rem;
    }

    /* --- Main Content Cards (Left & Right Column) --- */
    .main-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,.04);
        transition: all 0.3s ease-in-out;
    }
    .main-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }

    /* --- Contact Info Cards (Address, Email, Phone) --- */
    .contact-card {
        background: var(--light-gray);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.3s ease-in-out;
    }
    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,.07);
        border-color: rgba(var(--primary-rgb), 0.3);
        background: #fff;
    }

    .contact-icon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #fff;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--primary-color);
        transition: all 0.3s ease-in-out;
    }
    .contact-card:hover .contact-icon {
        background: var(--primary-color);
        color: #fff;
        transform: scale(1.1) rotate(-15deg);
    }

    /* --- Form Elements --- */
    .form-floating > .form-control {
        background-color: #f9fafb;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease-in-out;
    }
    .form-floating > .form-control:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.15);
    }
    .form-floating > label {
        color: #9ca3af;
    }
    .form-floating > .form-control:focus ~ label {
        color: var(--primary-color);
    }

    /* --- Submit Button --- */
    .btn-primary {
        background-image: linear-gradient(to right, #2575fc 0%, #6a11cb 100%);
        border: none;
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
        transition: all 0.3s ease-in-out;
    }
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(0,0,0,.2);
    }
    .btn-primary:disabled {
         background-image: none;
         background-color: #6c757d;
    }

    /* --- Custom Alerts --- */
    .alert {
        border-radius: 0.75rem;
        border-width: 0;
        border-left-width: 5px;
        display: flex;
        align-items: center;
    }
    .alert-success {
        background-color: #e6f9f0;
        border-left-color: #198754;
        color: #0f5132;
    }
    .alert-danger {
        background-color: #fdeeee;
        border-left-color: #dc3545;
        color: #842029;
    }
    .alert strong {
        display: block;
        margin-bottom: 0.25rem;
    }
    .alert .fa-2x { font-size: 1.5rem; margin-right: 0.75rem; }


    /* --- Map Container --- */
    .map-container {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
    }
    /* Giữ lại theo yêu cầu của bạn */
    .map-container iframe {
        border: 0; width: 100%; height: 100%; display: block;
    }
/* --- Main Card (Info + Form) --- */
.contact-main-box {
    background: #fff;
    border-radius: 1.25rem;   /* bo góc lớn hơn */
    box-shadow: 0 2px 8px rgba(0,0,0,.05); /* bóng nhẹ hơn */
    padding: 2rem 2.5rem;    /* rộng rãi hơn */
}


</style>

@section('content')
    <section class="breadcrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-contain">
                        <h2>Liên hệ với chúng tôi</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact -->
    <section class="section-bg py-5">
        <div class="container-xxl">
            <div class="row g-4 g-lg-5">
                <!-- Thông tin -->
                <div class="col-lg-6">
                   <div class="contact-main-box h-100">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/anhmoi/contact-us.png') }}"
                                class="img-fluid rounded-3" alt="Contact Illustration" style="max-height:200px">
                        </div>
                        <h3 class="fw-bold mb-2">Thông tin liên hệ</h3>
                        <p class="text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn.</p>

                        <div class="row g-3 mt-3">
                            <div class="col-12">
                                <div class="contact-card d-flex align-items-start gap-3">
                                    <div class="contact-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Địa chỉ</h6>
                                        <p class="mb-0 text-muted small">
                                            Tòa nhà FPT Polytechnic, Cổng số 2, 13 Trịnh Văn Bô, Phường Xuân Phương, TP Hà Nội
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="contact-card d-flex align-items-start gap-3">
                                    <div class="contact-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Email</h6>
                                        <p class="mb-0 text-muted small">Encryption@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="contact-card d-flex align-items-start gap-3">
                                    <div class="contact-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Điện thoại</h6>
                                        <p class="mb-0 text-muted small">(+84) 355 490 337</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="col-lg-6">
                    <div class="contact-main-box h-100">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Gửi tin nhắn</h2>
                            <p class="text-muted">Mọi thắc mắc sẽ được giải đáp sớm nhất.</p>
                        </div>

                        {{-- Alerts --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                                <i class="fas fa-check-circle fa-2x me-2"></i>
                                <strong>Thành công!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                                <i class="fas fa-exclamation-circle fa-2x me-2"></i>
                                <strong>Vui lòng kiểm tra lại:</strong>
                                <ul class="mb-0 ps-3 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('client.contact.store') }}" method="POST" class="mt-4" id="contactForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Họ tên"
                                            value="{{ old('name') }}" required>
                                        <label for="name">Họ và tên</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                            value="{{ old('email') }}" required>
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại"
                                    value="{{ old('phone') }}">
                                <label for="phone">Số điện thoại (không bắt buộc)</label>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea name="content" id="content" class="form-control" placeholder="Nội dung" required
                                    style="height:150px">{{ old('content') }}</textarea>
                                <label for="content">Nội dung tin nhắn</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg py-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-paper-plane"></i> Gửi Tin Nhắn
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Map -->
                <div class="col-12">
                    <div class="map-container rounded-4 overflow-hidden shadow mt-4" style="height:450px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863931182039!2d105.74468687503175!3d21.038129780613545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1756118652471!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.custom-alert');
        if (alerts.length) {
            setTimeout(() => {
                alerts.forEach(alert => {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                });
            }, 5000);
        }

        // Chống double-submit
        const form = document.getElementById('contactForm');
        if (form) {
            form.addEventListener('submit', function () {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang gửi...';
                }
            });
        }
    });
</script>
@endpush
