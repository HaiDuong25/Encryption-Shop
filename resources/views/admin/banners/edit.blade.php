@extends('admin.layouts.main')

@section('content')
<div class="container">
    <h2>Sửa banner</h2>
    
    {{-- Alert container for AJAX responses --}}
    <div id="alert-container"></div>
    
    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" id="bannerEditForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả banner">{{ old('description', $banner->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh banner hiện tại</label><br>
            @if(!empty($banner->images) && is_array($banner->images))
                <div class="d-flex flex-wrap gap-2">
                    @foreach($banner->images as $img)
                        <img src="{{ asset('storage/' . $img) }}" width="80" height="80"
                             style="object-fit:contain; aspect-ratio:1/1; border-radius:6px; border:1px solid #eee; background:#fafafa;">
                    @endforeach
                </div>
            @else
                <span class="text-muted fst-italic">Không có ảnh</span>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Đổi ảnh mới (tối đa 8 ảnh, chọn lại sẽ thay thế toàn bộ)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="images">
            <div id="image-error" class="text-danger mt-1" style="display:none;"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Vị trí</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', $banner->position) }}">
        </div>
        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Kích hoạt</label>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary" id="submitBtn">Cập nhật</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('bannerEditForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('alert-container');
        const input = document.getElementById('images');
        const errorDiv = document.getElementById('image-error');

        // Check image count first if files are selected
        if (input.files.length > 8) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Bạn chỉ được chọn tối đa 8 ảnh!';
            return;
        } else {
            errorDiv.style.display = 'none';
        }

        // Show loading state
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i data-feather="loader" class="rotating"></i> Đang cập nhật...';
        submitBtn.disabled = true;

        // Clear previous alerts
        alertContainer.innerHTML = '';

        // Create FormData for file upload
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                // Redirect after success
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("banners.index") }}';
                }, 1500);
            } else {
                // Show error messages
                if (data.errors) {
                    let errorHtml = '<div class="alert alert-danger alert-dismissible fade show"><strong>Đã có lỗi xảy ra:</strong><ul class="mb-0 mt-2">';
                    Object.values(data.errors).flat().forEach(error => {
                        errorHtml += `<li>${error}</li>`;
                    });
                    errorHtml += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                    alertContainer.innerHTML = errorHtml;
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            ${data.message || 'Có lỗi xảy ra khi cập nhật banner!'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }

                // Restore button state
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
                feather.replace();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show">
                    Có lỗi xảy ra khi cập nhật banner!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Restore button state
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
            feather.replace();
        });
    });
</script>
<style>
    .rotating {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection