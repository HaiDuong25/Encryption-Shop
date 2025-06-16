@extends('admin.layouts.main')

@section('content')
    <h2 class="mb-3">Thêm phương thức thanh toán</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payment-methods.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Loại thanh toán</label>
            <input type="text" name="payment_type" class="form-control" value="{{ old('payment_type') }}" >
           @error('payment_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-2">
            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary btn-sm px-3 fw-bold rounded-2 shadow-sm">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
            <button class="btn btn-success btn-sm px-3">Lưu</button>
        </div>
    </form>
@endsection
