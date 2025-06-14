@extends('admin.layouts.main')

@section('content')
    <h2 class="mb-3">Cập nhật phương thức</h2>

    <form action="{{ route('payment-methods.update', $payment_method) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Loại thanh toán</label>
            <input type="text" name="payment_type" class="form-control" value="{{ old('payment_type', $payment_method->payment_type) }}" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $payment_method->description) }}</textarea>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-2">
            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary btn-sm px-3 fw-bold rounded-2 shadow-sm">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
            <button class="btn btn-success btn-sm px-3">Cập nhật</button>
        </div>
    </form>
@endsection
