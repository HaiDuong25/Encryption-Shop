@extends('admin.layouts.main')

@section('content')
    <h2>Thêm phương thức thanh toán</h2>

    <form action="{{ route('payment-methods.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Loại thanh toán</label>
            <input type="text" name="payment_type" class="form-control" value="{{ old('payment_type') }}" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
