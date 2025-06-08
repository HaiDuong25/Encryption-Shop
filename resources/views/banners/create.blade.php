@extends('admin.layouts.main')

@section('content')
    <div class="container">
        <h2>Thêm mới banner</h2>
        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Ảnh banner (tối đa 8 ảnh)</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" required>
                <small class="text-muted">Chọn tối đa 8 ảnh.</small>
                <div id="image-error" class="text-danger mt-1" style="display:none;"></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Vị trí</label>
                <input type="number" name="position" class="form-control" value="{{ old('position', 0) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Link (URL)</label>
                <input type="url" name="link" class="form-control" value="{{ old('link') }}"
                    placeholder="Nhập đường dẫn banner (nếu có)">
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $banner->is_active ?? 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Kích hoạt</label>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('bannerForm').addEventListener('submit', function (e) {
            var input = document.getElementById('images');
            var errorDiv = document.getElementById('image-error');
            if (input.files.length > 8) {
                e.preventDefault();
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Bạn chỉ được chọn tối đa 8 ảnh!';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    </script>
@endsection