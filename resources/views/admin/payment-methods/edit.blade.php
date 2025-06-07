@extends('admin.layouts.main')

@section('content')
    <h2>Cập nhật phương thức</h2>

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
        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
