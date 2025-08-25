@extends('admin.layouts.main')
@section('content')
   <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center fw-bold text-success">Cập nhật mã giảm giá</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form id="couponEditForm">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="couponId" value="{{ $coupon->id }}">

                        <div class="mb-4">
                            <label for="code" class="form-label fw-semibold text-success">Mã giảm giá</label>
                            <input type="text" name="code" id="code" class="form-control"
                                value="{{ old('code', $coupon->code) }}" placeholder="Mã giảm giá" maxlength="50">
                            <div class="form-text">Để trống để hệ thống tự tạo mã ngẫu nhiên</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-success">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                placeholder="Mô tả về mã giảm giá này..." maxlength="500">{{ old('description', $coupon->description) }}</textarea>
                            <div class="form-text">Tối đa 500 ký tự</div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label fw-semibold text-success">Loại giảm giá</label>
                                <select name="discount_type" id="discount_type" class="form-select" required>
                                    <option value="percentage" {{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed" {{ ($coupon->discount_type ?? 'percentage') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="discount" class="form-label fw-semibold text-success">
                                    <span id="discount-label">{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Giảm giá (%)' : 'Giảm giá (₫)' }}</span>
                                </label>
                                <input type="number" name="discount" id="discount" class="form-control" required
                                    min="1" max="{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? '100' : '10000000' }}"
                                    value="{{ old('discount', (int)$coupon->discount) }}"
                                    placeholder="{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Nhập giá trị từ 1-100' : 'Nhập số tiền giảm giá' }}">
                                <div class="form-text" id="discount-help">
                                    {{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Nhập giá trị từ 1 đến 100' : 'Nhập số tiền giảm giá (tối đa 10.000.000₫)' }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 mt-4" id="max-discount-container" style="display: {{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'block' : 'none' }};">
                            <label for="max_discount_amount" class="form-label fw-semibold text-success">Số tiền giảm tối đa (₫)</label>
                            <input type="number" name="max_discount_amount" id="max_discount_amount" class="form-control"
                                min="0" step="1" value="{{ old('max_discount_amount', (int)$coupon->max_discount_amount) }}"
                                placeholder="Nhập số tiền giảm tối đa">
                            <div class="form-text">Áp dụng cho giảm giá theo %. VD: Giảm 10% tối đa 50,000₫</div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="min_order_amount" class="form-label fw-semibold text-success">Đơn hàng tối thiểu (₫)</label>
                                <input type="number" name="min_order_amount" id="min_order_amount" class="form-control"
                                    min="0" step="1" value="{{ old('min_order_amount', (int)$coupon->min_order_amount) }}"
                                    placeholder="Không yêu cầu nếu để trống">
                            </div>

                            <div class="col-md-6">
                                <label for="usage_limit" class="form-label fw-semibold text-success">Giới hạn số lần sử dụng</label>
                                <input type="number" name="usage_limit" id="usage_limit" class="form-control"
                                    min="0" value="{{ old('usage_limit', $coupon->usage_limit ?? 0) }}" placeholder="0 = không giới hạn">
                                <div class="form-text">Để trống hoặc 0 để không giới hạn số lần sử dụng</div>

                                @if($coupon->used_count > 0)
                                    <div class="mt-2">
                                        <span class="badge bg-info">Đã sử dụng: {{ $coupon->used_count }} lần</span>
                                        @if($coupon->usage_limit > 0)
                                            <span class="badge bg-warning">Còn lại: {{ max(0, $coupon->usage_limit - $coupon->used_count) }} lần</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-check my-4">
                            <input class="form-check-input" type="checkbox" name="is_one_time_per_user" id="is_one_time_per_user"
                                   value="1" {{ old('is_one_time_per_user', $coupon->is_one_time_per_user ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-success" for="is_one_time_per_user">
                                Giới hạn 1 lần sử dụng mỗi user
                            </label>
                            <div class="form-text">Mỗi tài khoản chỉ có thể sử dụng mã này 1 lần duy nhất</div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold text-success">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required
                                    value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}">
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $start = $coupon->start_date ? $coupon->start_date->format('Y-m-d') : null;
                                @endphp
                                @if($start)
                                    <div class="mt-2">
                                        @if($start <= $today->format('Y-m-d'))
                                            <span class="badge bg-success">Đã bắt đầu</span>
                                        @else
                                            <span class="badge bg-danger">Chưa bắt đầu</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-semibold text-success">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required
                                    value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content mt-5">
                            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-lg px-4 me-2">Quay lại</a>
                            <button type="submit" class="btn btn-success btn-lg px-4 fw-semibold">
                                <span class="btn-text">Cập nhật</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('couponEditForm');
            const submitBtn = form.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            const couponId = document.getElementById('couponId').value;
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

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                btnText.style.display = 'none';
                spinner.classList.remove('d-none');
                submitBtn.disabled = true;

                const formData = new FormData(form);
                
                fetch(`/admin/coupons/${couponId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    
                    // Status 422 vẫn là response hợp lệ, chỉ là validation error
                    if (response.status === 422 || (response.status >= 200 && response.status < 300)) {
                        return response.json();
                    }
                    
                    // Chỉ throw error cho các status code khác
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.log('Error response body:', text);
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Hiển thị thông báo thành công
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success';
                        successAlert.textContent = data.message;
                        form.insertBefore(successAlert, form.firstChild);
                        
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
                    errorAlert.textContent = error.message || 'Có lỗi xảy ra khi cập nhật mã giảm giá';
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
@endsection