@extends('admin.layouts.main')

@section('title', isset($category) ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục')

@section('content')
<div class="col-12 col-lg-6">
    <h3 class="my-3">{{ isset($category) ? 'Sửa Danh mục' : 'Thêm Danh mục' }}</h3>

    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($category))
        @method('PUT')
        @endif

        {{-- Tên danh mục --}}
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        {{-- Ảnh danh mục --}}
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh danh mục</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if(isset($category) && $category->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh danh mục" style="max-width: 150px;">
            </div>
            @endif
        </div>

        {{-- Trạng thái --}}
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select" required>
                <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection
