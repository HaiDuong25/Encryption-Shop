@extends('admin.layouts.main')

@section('title', 'Thêm Danh mục Cha')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Thêm mới Danh mục Cha</h3>
    <div class="card">
        <div class="card-body">
            {{-- Alert container for AJAX responses --}}
            <div id="alert-container"></div>

            <form id="parentCategoryForm" action="{{ route('admin.categories.store-parent') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @enderror
                    {{-- Error container for AJAX validation --}}
                    <div class="invalid-feedback ajax-error" style="display: none;"></div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    @enderror
                    {{-- Error container for AJAX validation --}}
                    <div class="invalid-feedback ajax-error" style="display: none;"></div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Lưu</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('parentCategoryForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    const alertContainer = document.getElementById('alert-container');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll('.invalid-feedback.ajax-error').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));
        alertContainer.innerHTML = '';

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang xử lý...';
        spinner.classList.remove('d-none');

        const formData = new FormData(form);

        // Add CSRF token to FormData
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('{{ route('admin.categories.store-parent') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => Promise.reject(data));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message with better UX
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                // Redirect after delay
                setTimeout(() => {
                    window.location.href = '{{ route('admin.categories.index') }}';
                }, 1500);

            } else {
                throw new Error(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Handle validation errors
            if (error.errors) {
                Object.keys(error.errors).forEach(field => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.parentNode.querySelector('.invalid-feedback.ajax-error');
                        if (feedback) {
                            feedback.textContent = error.errors[field][0];
                            feedback.style.display = 'block';
                        }
                    }
                });
            } else {
                // Show general error
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        ${error.message || 'Có lỗi xảy ra, vui lòng thử lại!'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            }
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            btnText.textContent = 'Lưu';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endsection
