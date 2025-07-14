@extends('admin.layouts.main')
@section('content')
    <div class="container">
        <h2>Sửa tin tức</h2>
        
        {{-- Alert container for AJAX responses --}}
        <div id="alert-container"></div>
        
        <form id="newsEditForm" method="POST" enctype="multipart/form-data" action="{{ route('news.update', $news->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title', $news->title) }}">
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <textarea name="content" class="form-control" rows="5"
                    required>{{ old('content', $news->content) }}</textarea>
                @error('content') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh đại diện</label>
                <input type="file" name="image" class="form-control">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" style="max-width:120px" class="mt-2">
                @endif
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Tác giả</label>
                <input type="text" name="author" class="form-control" required value="{{ old('author', $news->author) }}"
                    placeholder="Nhập tên tác giả">
                @error('author') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" class="form-check-input" id="is_published" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Đăng ngay</label>
            </div>
            <button type="submit" class="btn btn-success" id="submitBtn">Cập nhật</button>
            <a href="{{ route('news.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

<script>
// AJAX form submission
document.getElementById('newsEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const alertContainer = document.getElementById('alert-container');
    
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
                window.location.href = data.redirect || '{{ route("news.index") }}';
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
                        ${data.message || 'Có lỗi xảy ra khi cập nhật tin tức!'}
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
                Có lỗi xảy ra khi cập nhật tin tức!
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