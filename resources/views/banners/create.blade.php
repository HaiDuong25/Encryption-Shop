@extends('admin.layouts.main')

@section('content')
<div class="container">
    <h2>Thêm mới banner</h2>
    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh banner</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Vị trí</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', 0) }}">
        </div>
      <div class="mb-3 form-check">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
        value="1" {{ old('is_active', $banner->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Kích hoạt</label>
</div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection