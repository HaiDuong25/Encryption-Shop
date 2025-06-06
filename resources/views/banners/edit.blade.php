@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sửa banner</h2>
    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh banner hiện tại</label><br>
            @if($banner->image)
                <img src="{{ asset('storage/'.$banner->image) }}" width="120">
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Đổi ảnh mới (nếu muốn)</label>
            <input type="file" name="image" class="form-control">
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
@endsection