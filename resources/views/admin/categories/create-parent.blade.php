@extends('admin.layouts.main')

@section('title', 'Thêm Danh mục Cha')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Thêm mới Danh mục Cha</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.store-parent') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
