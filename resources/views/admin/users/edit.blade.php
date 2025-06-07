@extends('admin.layouts.main')
@section('content')

<h4>Sửa người dùng</h4>

<form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}">
    <input type="email" name="email" class="form-control mb-2" value="{{ $user->email }}">

    <select name="role" class="form-select mb-2">
        @foreach(['admin', 'staff', 'user'] as $role)
            <option value="{{ $role }}" @selected($user->role == $role)>{{ ucfirst($role) }}</option>
        @endforeach
    </select>

    <select name="status" class="form-select mb-2">
        @foreach(['active', 'inactive', 'pending'] as $status)
            <option value="{{ $status }}" @selected($user->status == $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>

    <input type="text" name="phone" class="form-control mb-2" value="{{ $user->phone }}">
    <input type="text" name="address" class="form-control mb-2" value="{{ $user->address }}">

    @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" width="80" class="mb-2">
    @endif
    <input type="file" name="avatar" class="form-control mb-2">

    <button class="btn btn-success">Cập nhật</button>
</form>
@endsection
