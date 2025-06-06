@extends('admin.layouts.main')
@section('content')
<div class="container">
    <h1 class="mb-3" style="font-size:2.2rem; font-weight: bold;">Danh sách mã giảm giá</h1>
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Quay lại</a>
        <a href="{{ route('coupons.create') }}" class="btn btn-success">Tạo mã mới</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow rounded-2">
        <table class="table align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Mã giảm giá</th>
                    <th>Giá trị (%)</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th style="width: 160px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
            @forelse($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td><strong>{{ $coupon->code }}</strong></td>
                    <td>{{ $coupon->discount }}</td>
                    <td>
                        {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        @php
                            $now = \Carbon\Carbon::now();
                            $start = $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date) : null;
                            $end = $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date) : null;
                        @endphp

                        @if($start && $end && $now->between($start, $end))
                            <span class="badge bg-success">
                             Còn hạn {{ floor($now->diffInDays($end) + 1) }} ngày
                            </span>
                        @elseif($end && $now->gt($end))
                            <span class="badge bg-danger">Hết hạn</span>
                        @elseif($start && $now->lt($start))
                            <span class="badge bg-secondary">Chưa bắt đầu</span>
                        @else
                            <span class="badge bg-warning">Không xác định</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa mã này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Chưa có mã giảm giá nào.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
