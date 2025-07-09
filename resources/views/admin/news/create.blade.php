@extends('admin.layouts.main')
@section('content')
    <div class="container">
        <h2>Thêm tin tức mới</h2>
        <form method="POST" enctype="multipart/form-data" action="{{ route('news.store') }}">
            @csrf
            <div class="mb-3">
                <label>Tiêu đề</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nội dung</label>
                <textarea name="content" class="form-control summernote" rows="6" required></textarea>
            </div>
            <div class="mb-3">
                <label>Ảnh đại diện</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tác giả</label>
                <input type="text" name="author" class="form-control" value="{{ old('author') }}" required
                    placeholder="Nhập tên tác giả">
            </div>
            <div class="mb-3">
                <label><input type="checkbox" name="is_published"> Đăng ngay</label>
            </div>
            <button class="btn btn-success">Đăng Bài Viết</button>
            <a href="{{ route('news.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection