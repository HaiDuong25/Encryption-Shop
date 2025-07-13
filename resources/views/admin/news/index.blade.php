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

                        {{-- Thêm form tìm kiếm theo tiêu đề --}}
                        <form action="{{ route('news.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2">
                            <input type="text" name="title" value="{{ request('title') }}" placeholder="Tìm theo tiêu đề..."
                                class="form-control" style="width:220px;">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="ri-search-line"></i> Tìm
                            </button>
                            @if(request('title'))
                                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                            @endif
                        </form>

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
                                                        <button class="btn btn-link p-0 text-danger delete-btn"
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->title }}"
                                                                title="Xoá">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                {{ request('title') ? 'Không tìm thấy tin tức nào phù hợp.' : 'Chưa có tin tức nào.' }}
                                            </td>
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

@push('scripts')
<script>
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const newsId = this.dataset.id;
            const newsTitle = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa tin tức "${newsTitle}"?`)) {
                // Show loading state
                const icon = this.querySelector('i');
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-line rotating"></i>';
                this.disabled = true;
                
                fetch(`/admin/news/${newsId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from table
                        const row = this.closest('tr');
                        row.remove();
                        
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa tin tức!');
                        // Restore button state
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa tin tức!');
                    // Restore button state
                    this.innerHTML = originalContent;
                    this.disabled = false;
                });
            }
        });
    });
</script>
<style>
    .rotating {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush