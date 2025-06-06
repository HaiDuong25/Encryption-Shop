@extends('admin.layouts.main')

@section('title', isset($category) ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục')

@section('content')
<div class="col-12 col-md-6 offset-md-3">
    <h3 class="mt-3 mb-3">{{ isset($category) ? 'Chỉnh sửa' : 'Thêm mới' }} Danh mục</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $category->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh danh mục</label>
                    @if(isset($category) && $category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh hiện tại" width="100">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
