@extends('admin.layouts.main')

@section('title', 'Quản lý Tin tức')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                            <h5>Danh sách tin tức</h5>
                            <div class="right-options d-flex gap-2 align-items-center">
                                <a class="btn btn-solid" href="{{ route('news.create') }}">Thêm tin mới</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table all-package theme-table table-product text-center align-middle"
                                style="border-collapse: separate; border-spacing: 0 12px;">
                                <thead class="table-light">
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
                                        <tr style="border-bottom: none !important;">
                                            <td>
                                                <div class="table-image">
                                                    @if($item->image)
                                                        <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid"
                                                            width="60" alt="{{ $item->title }}">
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                <div class="small text-muted"
                                                    style="max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 60) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->user)
                                                    {{ $item->user->name }}
                                                @else
                                                    {{ $item->author }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="{{ $item->is_published ? 'status-close' : 'status-danger' }}">
                                                    {{ $item->is_published ? 'Đã đăng' : 'Nháp' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                    <li>
                                                        <a href="{{ route('news.show', $item->id) }}" class="text-info"
                                                            title="Xem chi tiết">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('news.edit', $item->id) }}" class="text-warning"
                                                            title="Sửa">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('news.destroy', $item->id) }}" method="POST"
                                                            onsubmit="return confirm('Xác nhận xoá?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0 text-danger">
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

                            {{-- Nếu có phân trang --}}
                            {{--
                            @if ($news->hasPages())
                            <div class="mt-3">
                                {{ $news->links() }}
                            </div>
                            @endif
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection