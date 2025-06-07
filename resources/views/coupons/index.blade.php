@extends('admin.layouts.main')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-flex justify-content-between align-items-center">
                <h5>Danh sách mã giảm giá</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('coupons.create') }}" class="btn btn-theme">
                        <i data-feather="plus"></i> Tạo mã mới
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
                                <ul class="d-flex gap-2">
                                    <li>
                                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="text-warning">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Xóa mã này?')" class="btn btn-link p-0 text-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Chưa có mã giảm giá nào.</td>
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
