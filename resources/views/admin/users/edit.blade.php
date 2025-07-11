@extends('admin.layouts.main')
@section('content')
<div class="container">
    <h3 class="mb-4">Sửa người dùng</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Vai trò</label>
            @if($user->role !== 'admin')
            <select name="role" class="form-select">
                @foreach(['admin','user'] as $role)
                <option value="{{ $role }}" @selected($user->role == $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @else
            <select name="role" class="form-select" readonly>
                <option value="admin" selected>Admin</option>
            </select>
            <input type="hidden" name="role" value="admin">
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                @foreach(['active','inactive'] as $status)
                <option value="{{ $status }}" @selected($user->status == $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label><br>
            @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" width="80" class="rounded mb-2">
            @endif
            <input type="file" name="avatar" class="form-control">
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success px-5">Sửa</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2 px-5">Quay lại</a>
        </div>
    </form>
</div>
@endsection