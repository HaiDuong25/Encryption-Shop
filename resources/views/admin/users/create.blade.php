@extends('admin.layouts.main')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm người dùng</h2>

    {{-- Hiển thị thông báo lỗi tổng quát --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Lỗi nhập liệu:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <div class="col-md-6">
            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required placeholder="Nhập họ tên..." autofocus>
            @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required placeholder="example@gmail.com">
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required placeholder="Nhập mật khẩu...">
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="">-- Chọn vai trò --</option>
                @foreach(['admin','user'] as $role)
                <option value="{{ $role }}" @selected(old('role')==$role)>
                    {{ ucfirst($role) }}
                </option>
                @endforeach
            </select>
            @error('role')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="">-- Chọn trạng thái --</option>
                @foreach(['active','inactive'] as $status)
                <option value="{{ $status }}" @selected(old('status')==$status)>
                    {{ ucfirst($status) }}
                </option>
                @endforeach
            </select>
            @error('status')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}" placeholder="09xxxxxxxx">
            @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                value="{{ old('address') }}" placeholder="Nhập địa chỉ...">
            @error('address')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror"
                accept=".jpg,.jpeg,.png,.webp">
            @error('avatar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 d-flex justify-content-start">
            <button class="btn btn-primary px-5">Thêm mới</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2 px-5">Quay lại</a>
        </div>
    </form>
</div>
@endsection