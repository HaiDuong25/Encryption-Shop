@extends('admin.layouts.main')
@section('content')
    <div class="container-fluid py-5 d-flex justify-content-center align-items-center"
        style="min-height: 90vh; background: #f6fafd; color: #222;">
        <div class="card shadow-lg border-0 p-5 w-100" style="max-width: 800px; border-radius: 18px; background: #fff;">
            <h2 class="mb-4 text-center fw-bold" style="color: #009966; font-size: 2.2rem; letter-spacing: 1px;">Cập nhật mã
                giảm giá</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form id="couponEditForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="couponId" value="{{ $coupon->id }}">

                <div class="mb-4">
                    <label for="discount_type" class="form-label fw-semibold"
                        style="color: #009966; font-size: 1.2rem;">Loại giảm giá</label>
                    <select name="discount_type" id="discount_type" class="form-control form-control-lg" required
                        style="color: #222; font-size: 1.3rem;">
                        <option value="percentage" {{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ ($coupon->discount_type ?? 'percentage') == 'fixed' ? 'selected' : '' }}>Số
                            tiền cố định (₫)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="discount" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">
                        <span
                            id="discount-label">{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Giảm giá (%)' : 'Giảm giá (₫)' }}</span>
                    </label>
                    <input type="number" name="discount" id="discount" class="form-control form-control-lg" required min="1"
                        max="{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? '100' : '10000000' }}"
                        value="{{ old('discount', $coupon->discount) }}"
                        placeholder="{{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Nhập giá trị từ 1-100' : 'Nhập số tiền giảm giá' }}"
                        style="color: #222; font-size: 1.3rem;">
                    <small id="discount-help" class="form-text text-muted">
                        {{ ($coupon->discount_type ?? 'percentage') == 'percentage' ? 'Nhập giá trị từ 1 đến 100' : 'Nhập số tiền giảm giá (tối đa 10.000.000₫)' }}
                    </small>
                </div>

                <div class="mb-4">
                    <label for="usage_limit" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Giới
                        hạn số lần sử dụng</label>
                    <input type="number" name="usage_limit" id="usage_limit" class="form-control form-control-lg" min="0"
                        value="{{ old('usage_limit', $coupon->usage_limit ?? 0) }}" placeholder="0 = không giới hạn"
                        style="color: #222; font-size: 1.3rem;">
                    <small class="form-text text-muted">Để trống hoặc 0 để không giới hạn số lần sử dụng</small>
                    @if($coupon->used_count > 0)
                        <div class="mt-2">
                            <span class="badge bg-info">Đã sử dụng: {{ $coupon->used_count }} lần</span>
                            @if($coupon->usage_limit > 0)
                                <span class="badge bg-warning">Còn lại: {{ max(0, $coupon->usage_limit - $coupon->used_count) }}
                                    lần</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="mb-4">
                    <label for="start_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Ngày
                        bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" required
                        value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}"
                        style="color: #222; font-size: 1.3rem;">
                    @php
                        $today = \Carbon\Carbon::today();
                        $start = $coupon->start_date ? $coupon->start_date->format('Y-m-d') : null;
                    @endphp
                    @if($start)
                        @if($start <= $today->format('Y-m-d'))
                            <span class="badge bg-success mt-2">Đã bắt đầu</span>
                        @else
                            <span class="badge bg-danger mt-2">Chưa bắt đầu</span>
                        @endif
                    @endif
                </div>
                <div class="mb-4">
                    <label for="end_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Ngày kết
                        thúc <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-lg" required
                        value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}"
                        style="color: #222; font-size: 1.3rem;">
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-lg px-4">Quay lại</a>
                    <button type="submit" class="btn btn-lg px-4"
                        style="background-color: #009966; color: #fff; font-weight: 600; font-size: 1.2rem;">
                        <span class="btn-text">Cập nhật</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
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

            // Handle discount type change
            discountType.addEventListener('change', function () {
                if (this.value === 'percentage') {
                    discountLabel.textContent = 'Giảm giá (%)';
                    discountInput.placeholder = 'Nhập giá trị từ 1-100';
                    discountInput.max = '100';
                    discountHelp.textContent = 'Nhập giá trị từ 1 đến 100';
                } else {
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
                btnText.textContent = 'Đang cập nhật...';
                spinner.classList.remove('d-none');

                const formData = new FormData(form);

                fetch(`/admin/coupons/${couponId}`, {
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
                        btnText.textContent = 'Cập nhật';
                        spinner.classList.add('d-none');
                    });
            });
        });
    </script>
@endsection