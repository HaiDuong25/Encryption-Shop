@extends('admin.layouts.main')

@section('title', 'Quản lý Đánh giá Khách hàng')

@section('content')
<div class="col-12">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đánh giá</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-table me-1"></i> Danh sách Đánh giá Khách hàng</h5>
        </div>

        <div class="card-body">
            {{-- Thông báo Session --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="ratesTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Sản phẩm ID</th>
                            <th>Điểm</th>
                            <th style="min-width: 200px;">Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th style="min-width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rates as $rate)
                        <tr>
                            <td>{{ $rate->id }}</td>
                            <td>
                                @if ($rate->user)
                                    {{ $rate->user->name }}
                                    <br><small class="text-muted">(ID: {{ $rate->user->id }})</small>
                                @else
                                    <span class="text-muted">Không xác định</span>
                                @endif
                            </td>
                            <td>{{ $rate->product_id ?? 'N/A' }}</td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-star {{ $i <= $rate->score ? 'fas text-warning' : 'far text-muted' }}"></i>
                                @endfor
                                ({{ $rate->score }})
                            </td>
                            <td>{{ Str::limit($rate->content, 100) }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $rate->status_class }}">
                                    {{ ucfirst(str_replace('_', ' ', $rate->status_text)) }}
                                </span>
                            </td>
                            <td>{{ $rate->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap justify-content-center">
                                    <a href="{{ route('rates.show', $rate->id) }}" class="btn btn-sm btn-info" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('rates.edit', $rate->id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('rates.destroy', $rate->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không có đánh giá nào để hiển thị.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            @if ($rates->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $rates->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Thêm style nếu cần --}}
@endpush

@push('scripts')
{{-- Thêm script nếu cần --}}
@endpush
