@extends('layouts.app')
@section('content')
<div class="container">
    {{-- Tiêu đề lớn --}}
    <h1 class="mb-3" style="font-size:2.2rem; font-weight: bold;">Danh sách quản lý banner</h1>

    {{-- Hai nút bên trái cùng một dòng --}}
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            Quay lại
        </a>
        <a href="{{ route('banners.create') }}" class="btn btn-success">
            Thêm Banner
        </a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div> 
    @endif

    <div class="table-responsive shadow rounded-2">
        <table class="table align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Link</th>
                    <th>Vị trí</th>
                    <th>Kích hoạt</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($banners as $banner)
                <tr>
                    <td>
                        @if($banner->image)
                            <img src="{{ asset('storage/'.$banner->image) }}" style="max-width: 100px; max-height: 64px; border-radius:8px; box-shadow:0 2px 6px #0001;">
                        @else
                            <span class="text-muted fst-italic">Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $banner->title }}</td>
                    <td>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
                        @endif
                    </td>
                    <td>{{ $banner->position }}</td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge bg-success">Hiện</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">
                            Sửa
                        </a>
                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xoá banner?')">
                                Xoá
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr>
<td colspan="6" class="text-center text-muted">Chưa có banner nào.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{-- Nếu có phân trang --}}
    @if (method_exists($banners, 'links'))
        <div class="d-flex justify-content-end mt-3">
            {{ $banners->links() }}
        </div>
    @endif
</div>
@endsection
