@extends('client.layout.main')

@section('content')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height:70vh;">
    <div class="w-100" style="max-width:420px;">
        <div class="text-center mb-4">
            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;font-size:28px;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="fw-semibold mb-2">Thiết lập mã PIN</h3>
            <p class="text-muted mb-0">Mã PIN 6 số bảo vệ rút tiền và thanh toán bằng ví của bạn.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <form method="POST" action="{{ route('wallet.pin.store') }}" class="card border-0 shadow-sm p-4" style="background:#ffffff;">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-medium">PIN (6 số)</label>
                <div class="input-group">
                    <input type="password" name="pin" inputmode="numeric" pattern="\d{6}" maxlength="6" autocomplete="off" class="form-control text-center fs-5 @error('pin') is-invalid @enderror" required aria-label="PIN" id="pinField">
                    <button class="btn btn-outline-secondary" type="button" tabindex="-1" id="togglePin"><i class="fas fa-eye"></i></button>
                </div>
                @error('pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Xác nhận PIN</label>
                <div class="input-group">
                    <input type="password" name="pin_confirmation" inputmode="numeric" pattern="\d{6}" maxlength="6" autocomplete="off" class="form-control text-center fs-5 @error('pin_confirmation') is-invalid @enderror" required aria-label="Xác nhận PIN" id="pinConfirmField">
                    <button class="btn btn-outline-secondary" type="button" tabindex="-1" id="togglePinConfirm"><i class="fas fa-eye"></i></button>
                </div>
                @error('pin_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">
                <i class="fas fa-lock me-2"></i>Lưu mã PIN
            </button>
            <div class="text-center mt-3 small text-muted">
                Giữ bí mật mã PIN. Không chia sẻ với bất kỳ ai.
            </div>
        </form>
        @push('scripts')
        <script>
            (function(){
                function toggle(btnId, inputId){
                    const btn = document.getElementById(btnId);
                    const input = document.getElementById(inputId);
                    if(!btn||!input) return;
                    btn.addEventListener('click', function(){
                        const icon = this.querySelector('i');
                        if(input.type === 'password') { input.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
                        else { input.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
                        input.focus();
                    });
                }
                toggle('togglePin','pinField');
                toggle('togglePinConfirm','pinConfirmField');
            })();
        </script>
        @endpush
    </div>
</div>
@endsection
