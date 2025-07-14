@extends('admin.layouts.main')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm người dùng</h2>

    <!-- {{-- Hiển thị thông báo lỗi tổng quát --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Lỗi nhập liệu:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    <form id="userForm" enctype="multipart/form-data" class="row g-3">
        @csrf

        <div class="col-md-6">
            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Nhập họ tên..." autofocus>
            @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="example@gmail.com">
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
            <input type="text" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Nhập mật khẩu...">
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
            <select name="role" class="form-select @error('role') is-invalid @enderror">
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
            <select name="status" class="form-select @error('status') is-invalid @enderror">
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

        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-5">
                <span class="btn-text">Thêm mới</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2 px-5">Quay lại</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang xử lý...';
        spinner.classList.remove('d-none');
        
        const formData = new FormData(form);
        
        fetch('{{ route('users.store') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route('users.index') }}';
            } else {
                alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        })
        .finally(() => {
            submitBtn.disabled = false;
            btnText.textContent = 'Thêm mới';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endsection