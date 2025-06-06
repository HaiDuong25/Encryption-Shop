@extends('layouts.app')
@section('content')
<div class="container" style="max-width:500px;">
    <h2 class="mb-4 text-center">Tạo mã giảm giá mới</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('coupons.store') }}" method="POST" class="border p-4 rounded shadow-sm bg-light">
        @csrf

        <div class="mb-3">
            <label for="discount" class="form-label">Giảm giá (%)</label>
            <input type="number" name="discount" id="discount" class="form-control" required min="1" max="100" placeholder="Nhập % giảm giá">
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div> 

        <div class="d-flex justify-content-between">
            <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
        </div>
    </form>
</div>
@endsection