@extends('admin.layouts.main')

@section('title', 'Quản lý Đánh giá Khách hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách Đánh giá Khách hàng</h5>
                    </div>

                    {{-- Thông báo Session --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm ID</th>
                                    <th>Điểm</th>
                                    <th style="min-width: 200px;">Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $rate)
                                <tr style="border-bottom: none !important;">
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
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="{{ route('rates.show', $rate->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('rates.edit', $rate->id) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('rates.destroy', $rate->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?');">
                                                    @csrf
                                                    @method('DELETE')
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
    </div>
</div>
@endsection
