@extends('admin.layouts.main')

@section('content')
<div class="container" style="max-width: 600px;">
    <h2 class="mb-4 text-center fw-bold" style="color: #b266ff; font-size: 28px;">
        Tạo mã giảm giá mới
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('coupons.store') }}" method="POST" class="border p-4 rounded shadow bg-white" style="border-color: #b266ff;">
        @csrf

        <div class="mb-3">
            <label for="discount" class="form-label fw-bold" style="color: #b266ff;">Giảm giá (%)</label>
            <input type="number" name="discount" id="discount" class="form-control form-control-lg" required min="1" max="100" placeholder="Nhập % giảm giá">
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label fw-bold" style="color: #b266ff;">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label fw-bold" style="color: #b266ff;">Ngày kết thúc <span class="text-danger">*</span></label>
            <input type="date" name="end_date" id="end_date" class="form-control form-control-lg" required>
        </div> 

        <div class="d-flex justify-content-between">
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-lg">Quay lại</a>
            <button type="submit" class="btn btn-lg" style="background-color: #b266ff; color: white;">
                Tạo mã giảm giá
            </button>
        </div>
    </form>
</div>
@endsection
