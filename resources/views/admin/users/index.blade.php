@extends('admin.layouts.main')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title">
                <h5>Danh sách người dùng</h5>
                <a href="{{ route('users.create') }}" class="btn btn-theme">
                    <i data-feather="plus"></i> Thêm mới
                </a>
            </div>

            <div class="table-responsive table-product">
                <table class="table theme-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Quyền</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <!-- <td>
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/users/default.jpg') }}"
                                    width="60" class="rounded-circle">
                            </td> -->
                            <td>
                                @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" width="100">
                                @endif
                            </td>
                            <td>
                                <span>{{ $user->name }}</span>
                            </td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Khóa</span>
                                @endif
                            </td>
                            <td>
                                <ul>
                                    <li><a href="{{ route('users.edit', $user) }}"><i class="ri-pencil-line"></i></a></li>
                                    <li>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Xoá người dùng này?')" class="btn btn-link p-0 text-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection