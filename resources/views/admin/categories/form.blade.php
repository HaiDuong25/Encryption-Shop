@extends('admin.layouts.main')

@section('title', isset($category) ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">{{ isset($category) ? 'Chỉnh sửa' : 'Thêm mới' }} Danh mục</h3>
    <div class="card">
        <div class="card-body">
            <form id="categoryForm" enctype="multipart/form-data">
                @csrf
                @if(isset($category))
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="categoryId" value="{{ $category->id }}">
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $category->name ?? '') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @enderror
                    {{-- Error container for AJAX validation --}}
                    <div class="invalid-feedback ajax-error" style="display: none;"></div>
                </div>

                @if(!isset($category) || ($category && $category->parent_id !== null))
                    @if($categories->count() > 0)
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Danh mục cha</label>
                            <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- Không có --</option>
                                @foreach($categories as $cat)
                                    @if(!isset($category) || $category->id !== $cat->id)
                                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                            @enderror
                            {{-- Error container for AJAX validation --}}
                            <div class="invalid-feedback ajax-error" style="display: none;"></div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Hiện chưa có danh mục cha nào. Vui lòng <a href="{{ route('admin.categories.create-parent') }}">thêm danh mục cha</a> trước.
                        </div>
                    @endif
                @endif

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh danh mục</label>
                    @if(isset($category) && $category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh hiện tại" width="100">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    @enderror
                    {{-- Error container for AJAX validation --}}
                    <div class="invalid-feedback ajax-error" style="display: none;"></div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    @enderror
                    {{-- Error container for AJAX validation --}}
                    <div class="invalid-feedback ajax-error" style="display: none;"></div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-text">{{ isset($category) ? 'Cập nhật' : 'Lưu' }}</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const updateRouteTemplate = @json(route('admin.categories.update', ['category' => '__ID__']));
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous error messages
        document.querySelectorAll('.invalid-feedback.ajax-error').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang xử lý...';
        spinner.classList.remove('d-none');

        const formData = new FormData(form);
        const isEdit = document.getElementById('categoryId');

        // Add CSRF token to FormData
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        let url;
        if (isEdit) {
            url = updateRouteTemplate.replace('__ID__', isEdit.value);
            formData.append('_method', 'PUT');
        } else {
            url = '{{ route("admin.categories.store") }}';
        }

        fetch(url, {
            method: 'POST', // giữ nguyên
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
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);

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
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${error.message || 'Có lỗi xảy ra, vui lòng thử lại!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                form.insertBefore(alertDiv, form.firstChild);
            }
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            btnText.textContent = isEdit ? 'Cập nhật' : 'Lưu';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endsection
