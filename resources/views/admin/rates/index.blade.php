@extends('admin.layouts.main') {{-- Kế thừa từ layout chính của bạn --}}

@section('title', 'Quản lý Đánh giá Khách hàng') {{-- Đặt tiêu đề cho trang --}}

@section('content')
{{-- Vì @yield('content') trong main.blade.php đã nằm trong <div class="row">,
         chúng ta có thể bắt đầu với một cột bao quanh nội dung chính của trang này. --}}
<div class="col-12">
    {{-- Bạn có thể có một phần tiêu đề trang hoặc breadcrumb ở đây nếu layout của bạn không tự động tạo --}}
    {{-- Ví dụ:
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Quản lý Đánh giá</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{-- route('admin.dashboard') --}}</a></li>
    <li class="breadcrumb-item active">Đánh giá</li>
    </ol>
</div>
</div>
</div>

{{-- Hoặc đơn giản là một tiêu đề --}}
<h3 class="mt-3 mb-3">Danh sách Đánh giá Khách hàng</h3>

<div class="card">
    <div class="card-header">
        {{-- <h5 class="card-title mb-0"> <i class="fas fa-table me-1"></i> Tất cả Đánh giá</h5> --}}
        <div class="row">
            <div class="col">
                <h5 class="card-title mb-0"> <i class="fas fa-table me-1"></i> Tất cả Đánh giá</h5>
            </div>
        </div>
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
            <table class="table table-bordered table-hover table-striped" id="ratesTable"> {{-- Cân nhắc dùng ID nếu muốn dùng DataTables JS --}}
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Sản phẩm ID</th>
                        <th>Điểm</th>
                        <th style="min-width: 200px;">Nội dung (tóm tắt)</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="min-width: 100px;">Hành động</th>
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
                        <td>{{ $rate->product_id ?: 'N/A' }}</td>
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
    <div class="d-flex flex-wrap gap-1">
        {{-- Nút Xem --}}
        <a href="{{ route('admin.rates.show', $rate->id) }}" class="btn btn-primary btn-sm" title="Xem chi tiết">
            <i class="fas fa-eye"></i>
        </a>

        {{-- Nút Sửa --}}
        <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-primary btn-sm" title="Sửa trạng thái">
            <i class="fas fa-edit"></i>
        </a>

        {{-- Nút Xóa --}}
        <form action="{{ route('admin.rates.destroy', $rate->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" title="Xóa đánh giá">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có đánh giá nào để hiển thị.</td>
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
{{-- Thêm CSS cho DataTables nếu bạn sử dụng nó và muốn load riêng cho trang này --}}
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
@endpush

@push('scripts')
{{-- Thêm JS cho DataTables nếu bạn sử dụng nó --}}
{{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}
{{-- <script>
        $(document).ready(function() {
            $('#ratesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json" // Đường dẫn tới file ngôn ngữ tiếng Việt
                }
                // Các tùy chọn khác của DataTables
            });
        });
    </script> --}}
@endpush
