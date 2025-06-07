@extends('admin.layouts.main')
@section('content')
<div class="container">
    {{-- Tiêu đề lớn --}}
    <h1 class="mb-3" style="font-size:2.2rem; font-weight: bold;">Danh sách tin tức</h1>

    {{-- Hai nút bên dưới, nằm cạnh nhau bên trái --}}
    <div class="mb-4 d-flex justify-content-end gap-2">
        <a href="{{ route('news.create') }}" class="btn btn-success" style="color: #fff;">
            + Thêm tin mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow rounded-2">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Tác giả</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th style="width: 160px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
            @forelse($news as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" style="max-width: 64px; max-height:64px; border-radius:8px; box-shadow:0 2px 6px #0001;">
                        @else
                            <span class="text-muted fst-italic">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->title }}</strong>
                    </td>
                    <td>
                        <div class="small text-muted" style="max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 60) }}
                        </div>
                    </td>
                    <td>{{ $item->author }}</td>
                <td>
    @if($item->is_published)
        <span class="badge bg-success">Đã đăng</span>
    @else
        <span class="badge bg-danger">Nháp</span>
    @endif
</td>

                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('news.edit', $item->id) }}" class="btn btn-success btn-sm" style="color: #fff;">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form action="{{ route('news.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" style="background-color: #e67e22; border-color: #e67e22; color: #fff;" onclick="return confirm('Xóa tin này?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Chưa có tin tức nào.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mt-3">
        {{ $news->links() }}
    </div>
</div>
@endsection
