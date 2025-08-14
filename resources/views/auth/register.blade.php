@extends('client.layout.main')
@section('content')
    <section class="breadcrumb-section pt-0">
        {{-- ... phần breadcrumb của bạn giữ nguyên ... --}}
    </section>

    <section class="log-in-section section-b-space">
        <div class="container-fluid-lg w-100">
            <div class="row">
                <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                    <div class="image-contain">
                        <img src="https://themes.pixelstrap.com/fastkart/assets/images/inner-page/sign-up.png"
                            class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                    <div class="log-in-box">
                        <div class="log-in-title">
                            <h3>Welcome To Fastkart</h3>
                            <h4>Create New Account</h4>
                        </div>
                        <div class="input-box">
                            {{-- Vùng hiển thị lỗi validation từ JavaScript --}}
                            <div id="validation-errors" class="alert alert-danger" style="display: none;"></div>

                            {{-- Thêm id="register-form" để JavaScript có thể chọn form này --}}
                            <form class="row g-4" method="POST" action="{{ route('register') }}" id="register-form">
                                @csrf
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Họ và tên" value="{{ old('name') }}" required>
                                        <label for="name">Họ và tên</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Mật khẩu" required>
                                        <label for="password">Mật khẩu</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                                        <label for="password_confirmation">Xác nhận mật khẩu</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="forgot-box">
                                        <div class="form-check ps-0 m-0 remember-box">
                                            <input class="checkbox_animated check-box" type="checkbox" name="agree_terms"
                                                id="flexCheckDefault" required>
                                            <label class="form-check-label" for="flexCheckDefault">Tôi đồng ý với
                                                <span>Điều khoản</span> và <span>Chính sách bảo mật</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-animation w-100" type="submit">Đăng ký</button>
                                </div>
                            </form>
                        </div>
                        <div class="other-log-in">
                            <h6></h6>
                        </div>
                        <div class="sign-up-box">
                            <h4>Bạn đã có tài khoản?</h4>
                            <a href="{{ route('login.form') }}">Đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('register-form');
        const validationErrors = document.getElementById('validation-errors');

        form.addEventListener('submit', function(event) {
            // Ngăn chặn hành vi gửi form mặc định của trình duyệt
            event.preventDefault();

            // Xóa các lỗi cũ và ẩn đi
            validationErrors.innerHTML = '';
            validationErrors.style.display = 'none';

            // Lấy dữ liệu từ form
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            // Vô hiệu hóa nút và hiển thị trạng thái đang xử lý
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...`;

            // Gửi yêu cầu AJAX
            fetch('{{ route('register') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    // Nếu là lỗi validation từ Laravel (status 422), xử lý riêng
                    if (response.status === 422) {
                       return response.json().then(data => {
                           // Ném ra một lỗi để khối .catch() có thể bắt được
                           throw { validationErrors: data.errors };
                       });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Hiển thị thông báo thành công bằng SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            timer: 2500, // Tự động đóng sau 2.5 giây
                            showConfirmButton: false,
                        }).then(() => {
                            // Chuyển hướng đến trang đăng nhập
                            window.location.href = data.redirect;
                        });
                    } else {
                        // Hiển thị lỗi chung từ server
                        Swal.fire({
                            icon: 'error',
                            title: 'Đã có lỗi xảy ra',
                            text: data.message || 'Không thể đăng ký, vui lòng thử lại.'
                        });
                    }
                })
                .catch(error => {
                    // Xử lý lỗi validation hoặc lỗi mạng
                    if (error.validationErrors) {
                        let errorHtml = '<ul>';
                        for (const field in error.validationErrors) {
                            error.validationErrors[field].forEach(message => {
                                errorHtml += `<li>${message}</li>`;
                            });
                        }
                        errorHtml += '</ul>';
                        validationErrors.innerHTML = errorHtml;
                        validationErrors.style.display = 'block';
                    } else {
                        // Lỗi khác (ví dụ: mạng bị ngắt)
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi kết nối',
                            text: 'Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại đường truyền.'
                        });
                        console.error('Error:', error);
                    }
                })
                .finally(() => {
                    // Kích hoạt lại nút bấm sau khi hoàn tất
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
        });
    });
</script>
@endpush
