@extends('admin.layouts.main')

@section('title', 'Quản lý mã giảm giá')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="container-fluid">
        <div class="card card-table">
            <div class="card-body">
                <div class="title-header option-title">
                    <h5>Danh sách mã giảm giá</h5>
                    <a href="{{ route('coupons.create') }}" class="btn btn-theme">
                        <i data-feather="plus"></i> Tạo mã mới
                    </a>
                </div>
                {{-- Form tìm kiếm theo giá trị giảm giá --}}
                <form action="{{ route('coupons.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2">
                    <input type="number" name="discount" value="{{ request('discount') }}" placeholder="Nhập giá trị (%)" class="form-control" style="width:180px;">
                    <button class="btn btn-outline-primary" type="submit">
                        <i data-feather="search"></i> Tìm
                    </button>
                    @if(request('discount'))
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
                </form>

                <div class="table-responsive table-product mt-3">
                    <table class="table theme-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã giảm giá</th>
                                <th>Giá trị (%)</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>{{ $coupon->discount }}%</td>
                                    <td>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $start = $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date) : null;
                                            $end = $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date) : null;
                                        @endphp

                                        @if($start && $end && $now->between($start, $end))
                                            <span class="badge bg-primary">
                                                Còn {{ floor($now->diffInDays($end) + 1) }} ngày
                                            </span>
                                        @elseif($end && $now->gt($end))
                                            <span class="badge bg-danger">Hết hạn</span>
                                        @elseif($start && $now->lt($start))
                                            <span class="badge bg-success">Chưa bắt đầu</span>
                                        @else
                                            <span class="badge bg-warning">Không xác định</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="d-flex flex-wrap gap-2 mb-0" style="list-style:none; padding-left:0;">
                                            <li>
                                                <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-link p-0" title="Sửa">
                                                    <i data-feather="edit"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Xóa mã này?')" class="btn btn-link p-0 text-danger" title="Xoá">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        {{ request('discount') ? 'Không tìm thấy mã giảm giá nào phù hợp.' : 'Chưa có mã giảm giá nào.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($coupons, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $coupons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endpush