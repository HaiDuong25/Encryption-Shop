@extends('client.layout.main')

@section('content')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height:70vh;">
    <div class="w-100" style="max-width:460px;">
        <div class="text-center mb-4">
            <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;font-size:28px;">
                <i class="fas fa-key"></i>
            </div>
            <h3 class="fw-semibold mb-2">Quên PIN Ví</h3>
            <p class="text-muted mb-0">Xác thực bằng mật khẩu tài khoản để đặt lại mã PIN mới.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <form method="POST" action="{{ route('wallet.pin.reset') }}" class="card border-0 shadow-sm p-4">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium">Mật khẩu tài khoản</label>
                <input type="password" name="account_password" class="form-control @error('account_password') is-invalid @enderror" required>
                @error('account_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">PIN mới (6 số)</label>
                <input type="password" name="new_pin" inputmode="numeric" pattern="\d{6}" maxlength="6" class="form-control text-center fs-5 @error('new_pin') is-invalid @enderror" placeholder="••••••" required>
                @error('new_pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Xác nhận PIN mới</label>
                <input type="password" name="new_pin_confirmation" inputmode="numeric" pattern="\d{6}" maxlength="6" class="form-control text-center fs-5 @error('new_pin_confirmation') is-invalid @enderror" placeholder="••••••" required>
                @error('new_pin_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-warning w-100 py-2 fw-semibold" type="submit">
                <i class="fas fa-sync-alt me-2"></i>Đặt lại PIN
            </button>
            <div class="text-center mt-3">
                <a href="{{ route('wallet.index') }}" class="small">Quay lại ví</a>
            </div>
        </form>
    </div>
</div>
@endsection
