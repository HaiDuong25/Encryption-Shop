@extends('admin.layouts.main')

@section('title', isset($brand) ? 'Chỉnh sửa Thương hiệu' : 'Thêm Thương hiệu')

@section('content')
<div class="col-12 col-lg-6">
    <h3 class="my-3">{{ isset($brand) ? 'Sửa Thương hiệu' : 'Thêm Thương hiệu' }}</h3>

    <form action="{{ isset($brand) ? route('brands.update', $brand) : route('brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($brand))
            @method('PUT')
        @endif

        {{-- Tên --}}
        <div class="mb-3">
            <label for="name" class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $brand->name ?? '') }}" required>
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh --}}
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh (file upload)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            @if (isset($brand) && $brand->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $brand->image) }}" alt="Ảnh thương hiệu" style="max-width: 150px;">
                </div>
            @endif
            @error('image')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($brand) ? 'Cập nhật' : 'Thêm' }}</button>
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection
