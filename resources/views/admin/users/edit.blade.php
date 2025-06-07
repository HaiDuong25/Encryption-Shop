@extends('admin.layouts.main')
@section('content')
<div class="container">
    <h3 class="mb-4">Sửa người dùng</h3>

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
            <select name="role" class="form-select">
                @foreach(['admin','user'] as $role)
                    <option value="{{ $role }}" @selected($user->role == $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                @foreach(['active','inactive','pending'] as $status)
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

        <button class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
