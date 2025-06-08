@extends('admin.layouts.main')

@section('content')
<div class="container">
    <h2>Sửa banner</h2>
    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" id="bannerEditForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh banner hiện tại</label><br>
            @if(!empty($banner->images) && is_array($banner->images))
                <div class="d-flex flex-wrap gap-2">
                    @foreach($banner->images as $img)
                        <img src="{{ asset('storage/' . $img) }}" width="80" style="border-radius:6px; border:1px solid #eee;">
                    @endforeach
                </div>
            @else
                <span class="text-muted fst-italic">Không có ảnh</span>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Đổi ảnh mới (tối đa 8 ảnh, chọn lại sẽ thay thế toàn bộ)</label>
<<<<<<< HEAD
            <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="images">
            <small class="text-muted">Có thể chọn từ 1 đến 8 ảnh mới, nếu chọn sẽ thay thế toàn bộ ảnh cũ.</small>
            <div id="image-error" class="text-danger mt-1" style="display:none;"></div>
=======
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Có thể chọn từ 1 đến 8 ảnh mới, nếu chọn sẽ thay thế toàn bộ ảnh cũ.</small>
>>>>>>> 491560e08aa557f2984f6b43bc80eba2f6c217b2
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
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<script>
    document.getElementById('bannerEditForm').addEventListener('submit', function(e) {
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