@extends('admin.layouts.main')

@section('title', isset($brand) ? 'Chỉnh sửa Thương hiệu' : 'Thêm Thương hiệu')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">{{ isset($brand) ? 'Chỉnh sửa' : 'Thêm mới' }} Thương hiệu</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($brand) ? route('brands.update', $brand) : route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($brand))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tên thương hiệu</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $brand->name ?? '') }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh</label>
                    @if (isset($brand) && $brand->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="Ảnh hiện tại" width="100">
                        </div>
                    @endif
                    <input type="file" name="image" id="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary me-2">Huỷ</a>
                    <button type="submit" class="btn btn-primary">{{ isset($brand) ? 'Cập nhật' : 'Thêm' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
