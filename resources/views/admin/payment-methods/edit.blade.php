@extends('admin.layouts.main')

@section('title', 'Cập nhật phương thức thanh toán')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Cập nhật phương thức thanh toán</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <h6 class="fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> Vui lòng kiểm tra lại:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payment-methods.update', $payment_method) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="payment_type" class="form-label fw-semibold">Loại thanh toán <span class="text-danger">*</span></label>
                    <input type="text" name="payment_type" id="payment_type"
                        class="form-control @error('payment_type') is-invalid @enderror"
                        value="{{ old('payment_type', $payment_method->payment_type) }}" required>

                    @error('payment_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" id="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description', $payment_method->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('payment-methods.index') }}"
                        class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit"
                        class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fa fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
