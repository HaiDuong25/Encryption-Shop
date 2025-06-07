@extends('admin.layouts.main')
@section('content')

<h4>Thêm người dùng</h4>

<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="text" name="name" class="form-control mb-2" placeholder="Tên">
    <input type="email" name="email" class="form-control mb-2" placeholder="Email">
    <input type="password" name="password" class="form-control mb-2" placeholder="Mật khẩu">

    <select name="role" class="form-select mb-2">
        @foreach(['admin', 'staff', 'user'] as $role)
            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
        @endforeach
    </select>

    <select name="status" class="form-select mb-2">
        @foreach(['active', 'inactive', 'pending'] as $status)
            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
        @endforeach
    </select>

    <input type="text" name="phone" class="form-control mb-2" placeholder="SĐT">
    <input type="text" name="address" class="form-control mb-2" placeholder="Địa chỉ">
    <input type="file" name="avatar" class="form-control mb-2">

    <button class="btn btn-primary">Thêm</button>
</form>
@endsection
