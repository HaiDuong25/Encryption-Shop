@extends('admin.layouts.main')

@section('title', 'Quản lý tin tức')

@section('content')
<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            {{-- Tiêu đề và nút --}}
            <div class="title-header option-title d-flex justify-content-between align-items-center">
                <h5>Danh sách tin tức</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        Quay lại
                    </a>
                    <a href="{{ route('news.create') }}" class="btn btn-theme">
                        <i data-feather="plus"></i> Thêm tin mới
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            {{-- Bảng danh sách --}}
            <div class="table-responsive table-product mt-3">
                <table class="table theme-table align-middle">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Nội dung</th>
                            <th>Tác giả</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/'.$item->image) }}" width="64" style="border-radius: 8px; box-shadow:0 2px 6px #0001;">
                                @else
                                    <span class="text-muted fst-italic">Không có ảnh</span>
                                @endif
                            </td>
                            <td><strong>{{ $item->title }}</strong></td>
                            <td style="max-width: 240px;">
                                <div class="text-truncate d-block" style="max-width: 240px;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 60) }}
                                </div>
                            </td>
                            <td>{{ $item->author ?? 'Không rõ' }}</td>
                            <td>
                                @if($item->is_published)
                                    <span class="badge bg-success">Đã đăng</span>
                                @else
                                    <span class="badge bg-warning">Nháp</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <ul class="d-flex gap-2">
                                    <li>
                                        <a href="{{ route('news.edit', $item->id) }}" class="text-warning">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('news.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Xóa tin này?')" class="btn btn-link p-0 text-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Chưa có tin tức nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{-- Phân trang --}}
                @if(method_exists($news, 'links'))
                <div class="d-flex justify-content-end mt-3">
                    {{ $news->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
