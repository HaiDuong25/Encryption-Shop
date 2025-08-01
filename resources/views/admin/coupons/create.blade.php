@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid py-5 d-flex justify-content-center align-items-center"
        style="min-height: 90vh; background: #f6fafd; color: #222;">
        <div class="card shadow-lg border-0 p-5 w-100" style="max-width: 700px; border-radius: 18px; background: #fff;">
            <h2 class="mb-4 text-center fw-bold" style="color: #009966; font-size: 2.3rem; letter-spacing: 1px;">Tạo mã giảm
                giá</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form id="couponForm">
                @csrf
                
                <div class="mb-4">
                    <label for="code" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Mã giảm giá (tùy chọn)</label>
                    <input type="text" name="code" id="code" class="form-control form-control-lg" 
                        placeholder="Để trống để tự động tạo mã" style="color: #222; font-size: 1.2rem;" maxlength="50">
                    <small class="form-text text-muted">Để trống để hệ thống tự tạo mã ngẫu nhiên</small>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Mô tả</label>
                    <textarea name="description" id="description" class="form-control form-control-lg" rows="3"
                        placeholder="Mô tả về mã giảm giá này..." style="color: #222; font-size: 1.2rem;" maxlength="500"></textarea>
                    <small class="form-text text-muted">Tối đa 500 ký tự</small>
                </div>
                
                <div class="mb-4">
                    <label for="discount_type" class="form-label fw-semibold"
                        style="color: #009966; font-size: 1.1rem;">Loại giảm giá</label>
                    <select name="discount_type" id="discount_type" class="form-control form-control-lg" required
                        style="color: #222; font-size: 1.2rem;">
                        <option value="percentage">Phần trăm (%)</option>
                        <option value="fixed">Số tiền cố định (₫)</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="discount" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">
                        <span id="discount-label">Giảm giá (%)</span>
                    </label>
                    <input type="number" name="discount" id="discount" class="form-control form-control-lg" required min="1"
                        max="100" placeholder="Nhập giá trị giảm giá" style="color: #222; font-size: 1.2rem;">
                    <small id="discount-help" class="form-text text-muted">Nhập giá trị từ 1 đến 100</small>
                </div>
                
                <div class="mb-4" id="max-discount-container" style="display: none;">
                    <label for="max_discount_amount" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">
                        Số tiền giảm tối đa (₫)
                    </label>
                    <input type="number" name="max_discount_amount" id="max_discount_amount" class="form-control form-control-lg" 
                        min="0" placeholder="Nhập số tiền giảm tối đa" style="color: #222; font-size: 1.2rem;">
                    <small class="form-text text-muted">Áp dụng cho giảm giá theo %. VD: Giảm 10% tối đa 50,000₫</small>
                </div>
                
                <div class="mb-4">
                    <label for="min_order_amount" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">
                        Đơn hàng tối thiểu (₫)
                    </label>
                    <input type="number" name="min_order_amount" id="min_order_amount" class="form-control form-control-lg" 
                        min="0" placeholder="Nhập giá trị đơn hàng tối thiểu" style="color: #222; font-size: 1.2rem;">
                    <small class="form-text text-muted">Để trống nếu không yêu cầu đơn hàng tối thiểu</small>
                </div>
                
                <div class="mb-4">
                    <label for="usage_limit" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Giới
                        hạn số lần sử dụng</label>
                    <input type="number" name="usage_limit" id="usage_limit" class="form-control form-control-lg" min="0"
                        placeholder="0 = không giới hạn" style="color: #222; font-size: 1.2rem;" value="0">
                    <small class="form-text text-muted">Để trống hoặc 0 để không giới hạn số lần sử dụng</small>
                </div>
                
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_one_time_per_user" id="is_one_time_per_user" value="1" checked>
                        <label class="form-check-label fw-semibold" for="is_one_time_per_user" style="color: #009966; font-size: 1.1rem;">
                            Giới hạn 1 lần sử dụng mỗi user
                        </label>
                    </div>
                    <small class="form-text text-muted">Mỗi tài khoản chỉ có thể sử dụng mã này 1 lần duy nhất</small>
                </div>
                
                <div class="mb-4">
                    <label for="start_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Ngày
                        bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" required
                        style="color: #222; font-size: 1.2rem;">
                </div>
                
                <div class="mb-4">
                    <label for="end_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Ngày kết
                        thúc <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-lg" required
                        style="color: #222; font-size: 1.2rem;">
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-lg px-4">Quay lại</a>
                    <button type="submit" class="btn btn-lg px-4"
                        style="background-color: #009966; color: #fff; font-weight: 600; font-size: 1.1rem;">
                        <span class="btn-text">Tạo mã giảm giá</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('couponForm');
            const submitBtn = form.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            const discountType = document.getElementById('discount_type');
            const discountInput = document.getElementById('discount');
            const discountLabel = document.getElementById('discount-label');
            const discountHelp = document.getElementById('discount-help');
            const maxDiscountContainer = document.getElementById('max-discount-container');

            // Handle discount type change
            discountType.addEventListener('change', function () {
                if (this.value === 'percentage') {
                    discountLabel.textContent = 'Giảm giá (%)';
                    discountInput.placeholder = 'Nhập giá trị từ 1-100';
                    discountInput.max = '100';
                    discountHelp.textContent = 'Nhập giá trị từ 1 đến 100';
                    maxDiscountContainer.style.display = 'block';
                } else {
                    discountLabel.textContent = 'Giảm giá (₫)';
                    discountInput.placeholder = 'Nhập số tiền giảm';
                    discountInput.max = '10000000';
                    discountHelp.textContent = 'Nhập số tiền từ 1 đến 10,000,000₫';
                    maxDiscountContainer.style.display = 'none';
                    document.getElementById('max_discount_amount').value = '';
                }
            });

            // Trigger initial change
            discountType.dispatchEvent(new Event('change'));

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                btnText.style.display = 'none';
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;

                const formData = new FormData(form);
                
                fetch('{{ route("coupons.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hiển thị thông báo thành công
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success';
                        successAlert.textContent = data.message;
                        form.insertBefore(successAlert, form.firstChild);
                        
                        // Reset form
                        form.reset();
                        discountType.dispatchEvent(new Event('change'));
                        
                        // Chuyển hướng sau 2s
                        setTimeout(() => {
                            window.location.href = '{{ route("coupons.index") }}';
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Hiển thị thông báo lỗi
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger';
                    errorAlert.textContent = error.message || 'Có lỗi xảy ra khi tạo mã giảm giá';
                    form.insertBefore(errorAlert, form.firstChild);
                })
                .finally(() => {
                    btnText.style.display = 'inline';
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
                    discountLabel.textContent = 'Giảm giá (₫)';
                    discountInput.placeholder = 'Nhập số tiền giảm giá';
                    discountInput.max = '10000000';
                    discountHelp.textContent = 'Nhập số tiền giảm giá (tối đa 10.000.000₫)';
                }
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Show loading state
                submitBtn.disabled = true;
                btnText.textContent = 'Đang tạo...';
                spinner.classList.remove('d-none');

                const formData = new FormData(form);

                fetch('{{ route('coupons.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = '{{ route('coupons.index') }}';
                        } else {
                            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        btnText.textContent = 'Tạo mã giảm giá';
                        spinner.classList.add('d-none');
                    });
            });
        });
    </script>
@endsection