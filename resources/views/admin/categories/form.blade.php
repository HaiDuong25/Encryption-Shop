@extends('admin.layouts.main')

@section('title', isset($category) ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục')

@section('content')
<div class="col-12">
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
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $category->name ?? '') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($categories->count() > 0)
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Danh mục cha</label>
                    <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        @foreach($categories as $cat)
                        @if(!isset($category) || $category->id !== $cat->id)
                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @else
                <div class="alert alert-warning">
                    Hiện chưa có danh mục cha nào. Vui lòng <a href="{{ route('categories.create-parent') }}">thêm danh mục cha</a> trước.
                </div>
                @endif


                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh danh mục</label>
                    @if(isset($category) && $category->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Ảnh hiện tại" width="100">
                    </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
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
