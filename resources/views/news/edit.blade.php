@extends('admin.layouts.main')
@section('content')
    <div class="container">
        <h2>Sửa tin tức</h2>
        <form method="POST" enctype="multipart/form-data" action="{{ route('news.update', $news->id) }}">
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
            <button class="btn btn-success">Cập nhật</button>
            <a href="{{ route('news.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection