@extends('admin.layouts.main')

@section('title', 'Quản lý banner')

@section('content')
<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-flex justify-content-between align-items-center">
                <h5>Danh sách banner</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('banners.create') }}" class="btn btn-theme">
                        <i data-feather="plus"></i> Thêm Banner
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive table-product mt-3">
                <table class="table theme-table align-middle">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Link</th>
                            <th>Vị trí</th>
                            <th>Kích hoạt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                        <tr>
                            <td>
                                @if($banner->image)
                                    <img src="{{ asset('storage/'.$banner->image) }}" width="80" style="border-radius: 8px;">
                                @else
                                    <span class="text-muted fst-italic">Không có ảnh</span>
                                @endif
                            </td>
                            <td><strong>{{ $banner->title }}</strong></td>
                            <td style="max-width: 200px;">
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="text-truncate d-block" style="max-width: 200px;">
                                        {{ $banner->link }}
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Không có link</span>
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
                                <ul class="d-flex gap-2">
                                    <li>
                                        <a href="{{ route('banners.edit', $banner->id) }}" class="text-warning">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Xóa banner này?')" class="btn btn-link p-0 text-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Chưa có banner nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if (method_exists($banners, 'links'))
                <div class="mt-3 d-flex justify-content-end">
                    {{ $banners->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
