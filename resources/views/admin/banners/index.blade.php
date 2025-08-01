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

                {{-- Form tìm kiếm --}}
                <form action="{{ route('banners.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                    <div class="search-box" style="width:250px;">
                        <input type="text" name="search" value="{{ request('search') ?? request('title') }}" placeholder="Tìm kiếm theo tiêu đề..."
                            class="form-control">
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="ri-search-line"></i> Tìm kiếm
                    </button>
                    @if(request()->hasAny(['search', 'title']))
                        <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>

                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                <div class="table-responsive table-product mt-3">
                    <table class="table theme-table align-middle">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
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
                                                            @php
                                                                $images = [];
                                                                if ($banner->image) {
                                                                    $images = json_decode($banner->image, true) ?: [];
                                                                }
                                                            @endphp
                                                            @if($images && is_array($images))
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($images as $img)
                                                                        <a href="{{ asset('storage/' . $img) }}" target="_blank" title="Xem ảnh lớn">
                                                                            <img src="{{ asset('storage/' . $img) }}" width="60" height="60"
                                                                                style="object-fit:contain; aspect-ratio:1/1; border-radius: 6px; border:1px solid #eee; background:#fafafa;">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có ảnh</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>{{ $banner->title }}</strong></td>
                                                        <td style="max-width: 200px;">
                                                            @if($banner->description)
                                                                <span class="text-truncate d-block" title="{{ $banner->description }}">
                                                                    {{ Str::limit($banner->description, 50) }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted fst-italic">Không có mô tả</span>
                                                            @endif
                                                        </td>
                                                        <td style="max-width: 200px;">
                                                            @if($banner->link)
                                                                <a href="{{ $banner->link }}" target="_blank" class="text-truncate d-block"
                                                                    style="max-width: 200px;">
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
                                                                <span class="badge bg-danger">Ẩn</span>
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
                                                                    <a href="{{ route('banners.show', $banner->id) }}" class="text-info"
                                                                        title="Xem chi tiết">
                                                                        <i class="ri-eye-line"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button class="btn btn-link p-0 text-danger delete-btn"
                                                                            data-id="{{ $banner->id }}"
                                                                            data-name="{{ $banner->title }}"
                                                                            title="Xoá">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
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

@push('scripts')
<script>
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bannerId = this.dataset.id;
            const bannerTitle = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa banner "${bannerTitle}"?`)) {
                // Show loading state
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-line rotating"></i>';
                this.disabled = true;
                
                fetch(`/admin/banners/${bannerId}`, {
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
                        alert(data.message || 'Có lỗi xảy ra khi xóa banner!');
                        // Restore button state
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa banner!');
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