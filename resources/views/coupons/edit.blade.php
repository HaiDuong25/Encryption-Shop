@extends('admin.layouts.main')
@section('content')
<div class="container-fluid py-5 d-flex justify-content-center align-items-center" style="min-height: 90vh; background: #f6fafd; color: #222;">
    <div class="card shadow-lg border-0 p-5 w-100" style="max-width: 800px; border-radius: 18px; background: #fff;">
        <h2 class="mb-4 text-center fw-bold" style="color: #009966; font-size: 2.2rem; letter-spacing: 1px;">Cập nhật mã giảm giá</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="discount" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Giảm giá (%)</label>
                <input type="number" name="discount" id="discount" class="form-control form-control-lg" required min="1" max="100" value="{{ old('discount', $coupon->discount) }}" style="color: #222; font-size: 1.3rem;">
            </div>
            <div class="mb-4">
                <label for="start_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Ngày bắt đầu <span class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" required value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}" style="color: #222; font-size: 1.3rem;">
                @php
                    $today = \Carbon\Carbon::today();
                    $start = $coupon->start_date ? $coupon->start_date->format('Y-m-d') : null;
                @endphp
                @if($start)
                    @if($start <= $today->format('Y-m-d'))
                        <span class="badge bg-success mt-2">Đã bắt đầu</span>
                    @else
                        <span class="badge bg-danger mt-2">Chưa bắt đầu</span>
                    @endif
                @endif
            </div>
            <div class="mb-4">
                <label for="end_date" class="form-label fw-semibold" style="color: #009966; font-size: 1.2rem;">Ngày kết thúc <span class="text-danger">*</span></label>
                <input type="date" name="end_date" id="end_date" class="form-control form-control-lg" required value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}" style="color: #222; font-size: 1.3rem;">
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-lg px-4">Quay lại</a>
                <button type="submit" class="btn btn-lg px-4" style="background-color: #009966; color: #fff; font-weight: 600; font-size: 1.2rem;">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection