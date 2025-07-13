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
                    <label for="discount" class="form-label fw-semibold" style="color: #009966; font-size: 1.1rem;">Giảm giá
                        (%)</label>
                    <input type="number" name="discount" id="discount" class="form-control form-control-lg" required min="1"
                        max="100" placeholder="Nhập % giảm giá" style="color: #222; font-size: 1.2rem;">
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('couponForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    form.addEventListener('submit', function(e) {
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