@extends('client.layout.main')

@section('title', 'Ví điện tử')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets-front/css/wallet-custom.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets-front/js/wallet-fix.js') }}"></script>
@endpush

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ví của tôi</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php($user = auth()->user())
        @if($user && !$user->pin_code_hash)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <strong>Bảo mật ví:</strong> Bạn chưa thiết lập mã PIN. Vui lòng tạo ngay để sử dụng rút tiền và thanh toán bằng ví an toàn hơn.
                </div>
                <a href="{{ route('wallet.pin.setup') }}" class="btn btn-sm btn-primary ms-3">Thiết lập PIN</a>
            </div>
        @elseif(session('require_pin'))
            <div class="alert alert-warning">
                <strong>Yêu cầu xác thực PIN:</strong> Phiên PIN đã hết hạn. <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#walletPinVerifyModal">Nhập PIN</button>
            </div>
        @elseif($user && $user->pin_code_hash)
            <div class="alert alert-primary d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div><i class="fas fa-shield-alt me-1"></i> PIN ví đã được thiết lập.</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePinModal"><i class="fas fa-edit me-1"></i>Đổi PIN</button>
                    <a href="{{ route('wallet.pin.forgot') }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-key me-1"></i>Quên PIN</a>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Wallet Balance Card -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card wallet-balance-card">
                    <div class="card-body">
                        <div class="wallet-header mb-3">
                            <div class="wallet-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="wallet-title">
                                <h6 class="mb-1">Ví điện tử của bạn</h6>
                                <small class="text-muted">Số dư hiện tại</small>
                            </div>
                        </div>

                        <div class="balance-display mb-4">
                            <h3 class="balance-amount mb-0">
                                {{ number_format($wallet->balance, 0, ',', '.') }}
                            </h3>
                            <span class="currency">VND</span>
                        </div>

                        <div class="wallet-actions">
                            <div class="row g-2">
                                {{-- Nạp tiền --}}
                                <div class="col-4">
                                    <a href="{{ route('wallet.topup') }}" class="btn btn-primary btn-action w-100">
                                        <i class="fas fa-plus mb-1"></i>
                                        <div class="action-text">Nạp tiền</div>
                                    </a>
                                </div>

                                {{-- Rút tiền --}}
                                <div class="col-4">
                                    <a href="{{ route('wallet.withdraw') }}" class="btn btn-outline-primary btn-danger btn-action w-100">
                                        <i class="fas fa-arrow-down mb-1"></i>
                                        <div class="action-text">Rút tiền</div>
                                    </a>
                                </div>

                                {{-- Lịch sử --}}
                                <div class="col-4">
                                    <a href="{{ route('wallet.history') }}"
                                        class="btn btn-outline-primary btn-action w-100">
                                        <i class="fas fa-history mb-1"></i>
                                        <div class="action-text">Lịch sử</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-8 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="quick-action-item text-center p-3">
                                    <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                                    <h6>Nạp tiền MoMo</h6>
                                    <p class="text-muted small">Nạp tiền nhanh chóng qua ví MoMo</p>
                                    <form action="{{ route('wallet.process-topup') }}" method="POST" class="topup-form">
                                        @csrf
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="text" name="amount" class="form-control form-control-sm amount-input" min="10000" step="1000" placeholder="Nhập số tiền nạp" required style="height:32px;" autocomplete="off" inputmode="numeric">
                                            <input type="hidden" name="payment_method" value="momo">
                                            <button type="submit" class="btn btn-sm btn-outline-primary flex-shrink-0" style="height:32px;">Nạp tiền</button>
                                        </div>
                                        <div class="text-danger mt-2 d-none topup-limit-warning">Số tiền nạp vượt quá mức cho phép (50,000,000 VND).</div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="quick-action-item text-center p-3">
                                    <i class="fas fa-wallet fa-2x text-info mb-2"></i>
                                    <h6>Nạp tiền ZaloPay</h6>
                                    <p class="text-muted small">Nạp tiền tiện lợi qua ví ZaloPay</p>
                                    <form action="{{ route('wallet.process-topup') }}" method="POST" class="topup-form">
                                        @csrf
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="text" name="amount" class="form-control form-control-sm amount-input" min="10000" step="1000" placeholder="Nhập số tiền nạp" required style="height:32px;" autocomplete="off" inputmode="numeric">
                                            <input type="hidden" name="payment_method" value="zalopay">
                                            <button type="submit" class="btn btn-sm btn-outline-info flex-shrink-0" style="height:32px;">Nạp tiền</button>
                                        </div>
                                        <div class="text-danger mt-2 d-none topup-limit-warning">Số tiền nạp vượt quá mức cho phép (50,000,000 VND).</div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="quick-action-item text-center p-3">
                                    <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                    <h6>Mua sắm</h6>
                                    <p class="text-muted small">Sử dụng ví để thanh toán đơn hàng</p>
                                    <a href="{{ route('client.products.index') }}"
                                        class="btn btn-sm btn-outline-success">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Giao dịch gần đây</h5>
                        <a href="{{ route('wallet.history') }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Loại</th>
                                            <th>Số tiền</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    @if ($transaction->type === 'deposit')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-plus me-1"></i>Nạp tiền
                                                        </span>
                                                    @elseif($transaction->type === 'withdraw')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-arrow-up me-1"></i>Rút tiền
                                                        </span>
                                                    @elseif($transaction->type === 'refund')
                                                        <span class="badge bg-info text-dark">
                                                            <i class="fas fa-undo me-1"></i>Hoàn tiền
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-minus me-1"></i>Thanh toán
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (in_array($transaction->type, ['deposit', 'refund']))
                                                        <span
                                                            class="text-success">+{{ number_format($transaction->amount, 0, ',', '.') }}
                                                            VND</span>
                                                    @else
                                                        <span
                                                            class="text-danger">-{{ number_format($transaction->amount, 0, ',', '.') }}
                                                            VND</span>
                                                    @endif
                                                </td>
                                                <td>{{ $transaction->description }}</td>
                                                <td>
                                                    @switch($transaction->status)
                                                        @case('completed')
                                                            <span class="badge bg-success">Hoàn thành</span>
                                                        @break

                                                        @case('pending')
                                                            <span class="badge bg-warning">Đang xử lý</span>
                                                        @break

                                                        @case('failed')
                                                            <span class="badge bg-danger">Thất bại</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có giao dịch nào</p>
                                <a href="{{ route('wallet.topup') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nạp tiền ngay
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal xác thực PIN -->
<div class="modal fade" id="walletPinVerifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác thực PIN Ví</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pinVerifyAlert" class="alert d-none"></div>
                <form id="walletPinVerifyForm" autocomplete="off" novalidate>
                    @csrf
                    <!-- Dummy anti-autofill fields (trick browsers/password managers) -->
                    <input type="text" name="fake_user" value="" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">
                    <input type="password" name="fake_pass" value="" autocomplete="new-password" class="d-none" tabindex="-1" aria-hidden="true">
                    <div class="mb-3 position-relative">
                        <label class="form-label">Nhập PIN 6 số</label>
                        <div class="input-group" id="verifyPinWrapper" data-dynamic-pin></div>
                        <small class="text-muted">Trường này được tạo động để chặn autofill.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Xác thực</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal đổi PIN -->
<div class="modal fade" id="changePinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="{{ route('wallet.pin.change') }}" autocomplete="off" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Đổi PIN Ví</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
            <!-- Dummy fields to confuse autofill -->
            <input type="text" name="fake_user2" value="" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">
            <input type="password" name="fake_pass2" value="" autocomplete="new-password" class="d-none" tabindex="-1" aria-hidden="true">
                    <div class="mb-3 position-relative">
                        <label class="form-label">PIN hiện tại</label>
                        <div class="input-group">
                <input type="password" id="oldPinInput" name="old_pin" inputmode="numeric" autocomplete="one-time-code" autocorrect="off" autocapitalize="off" data-lpignore="true" data-1p-ignore="true" spellcheck="false" pattern="\d{6}" maxlength="6" class="form-control" required aria-label="PIN hiện tại" data-pin-field>
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" data-pin-toggle data-target="#oldPinInput"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">PIN mới</label>
                        <div class="input-group">
                <input type="password" id="newPinInput" name="new_pin" inputmode="numeric" autocomplete="new-password" autocorrect="off" autocapitalize="off" data-lpignore="true" data-1p-ignore="true" spellcheck="false" pattern="\d{6}" maxlength="6" class="form-control" required aria-label="PIN mới" data-pin-field>
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" data-pin-toggle data-target="#newPinInput"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Xác nhận PIN mới</label>
                        <div class="input-group">
                <input type="password" id="confirmPinInput" name="new_pin_confirmation" inputmode="numeric" autocomplete="new-password" autocorrect="off" autocapitalize="off" data-lpignore="true" data-1p-ignore="true" spellcheck="false" pattern="\d{6}" maxlength="6" class="form-control" required aria-label="Xác nhận PIN mới" data-pin-field>
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" data-pin-toggle data-target="#confirmPinInput"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <style>
        /* Modern Wallet Balance Card */
        .wallet-balance-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }

        .wallet-balance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .wallet-balance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4285f4, #34a853, #fbbc04, #ea4335);
        }

        .wallet-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wallet-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .wallet-title h6 {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .wallet-title small {
            font-size: 12px;
            color: #888;
        }

        .balance-display {
            text-align: center;
            padding: 20px 10px;
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
            border-radius: 16px;
            margin: 20px 0;
            position: relative;
        }

        .balance-amount {
            font-family: 'Segoe UI', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            color: #2563eb;
            line-height: 1;
        }

        .currency {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
            margin-left: 8px;
        }

        .btn-action {
            border-radius: 12px;
            padding: 12px 8px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-action i {
            font-size: 16px;
        }

        .action-text {
            font-size: 11px;
            line-height: 1;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-primary.btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-outline-primary.btn-action {
            border: 2px solid #e5e7eb;
            color: #6b7280;
            background: #fff;
        }

        .btn-outline-primary.btn-action:hover {
            border-color: #667eea;
            color: #667eea;
            background: #f8faff;
        }

        .quick-action-item {
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #ffffff;
            border: 2px solid #f1f5f9;
        }

        .quick-action-item:hover {
            background: #f8faff;
            border-color: #e2e8f0;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            background: #f8faff;
            color: #374151;
            font-size: 13px;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 2px solid #f1f5f9;
            padding: 20px 24px;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-body {
            padding: 24px;
        }

        .badge {
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 500;
        }
    </style>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.amount-input').forEach(function(input) {
    input.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value) {
            this.value = Number(value).toLocaleString('vi-VN');
        } else {
            this.value = '';
        }
        // Hiển thị cảnh báo nếu vượt quá 50 triệu
        let warning = input.closest('form').querySelector('.topup-limit-warning');
        if (Number(value) > 50000000) {
            warning.classList.remove('d-none');
        } else {
            warning.classList.add('d-none');
        }
    });
    input.form && input.form.addEventListener('submit', function(e) {
        let raw = input.value.replace(/\D/g, '');
        if (Number(raw) > 50000000) {
            let warning = input.closest('form').querySelector('.topup-limit-warning');
            warning.classList.remove('d-none');
            input.focus();
            e.preventDefault();
            return false;
        }
        input.value = raw;
    });
});

// Xử lý form xác thực PIN
const pinForm = document.getElementById('walletPinVerifyForm');
if(pinForm){
    pinForm.addEventListener('submit', function(e){
        e.preventDefault();
        const pinValue = document.getElementById('verifyPinInput').value.trim();
        if(!/^\d{6}$/.test(pinValue)){
            const alertBox = document.getElementById('pinVerifyAlert');
            alertBox.classList.remove('d-none','alert-success');
            alertBox.classList.add('alert-danger');
            alertBox.textContent = 'PIN phải gồm 6 chữ số';
            return;
        }
        fetch("{{ route('wallet.pin.verify') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': pinForm.querySelector('input[name="_token"]').value, 'Accept':'application/json','Content-Type':'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ pin: pinValue })
        }).then(r=>r.json()).then(data=>{
            const alertBox = document.getElementById('pinVerifyAlert');
            alertBox.classList.remove('d-none','alert-danger','alert-success');
            if(data.success){
                alertBox.classList.add('alert-success');
                alertBox.textContent = data.message || 'Thành công';
                setTimeout(()=>window.location.reload(), 800);
            } else {
                alertBox.classList.add('alert-danger');
                alertBox.textContent = data.message || 'PIN không đúng';
            }
        }).catch(()=>{
            const alertBox = document.getElementById('pinVerifyAlert');
            alertBox.classList.remove('d-none');
            alertBox.classList.add('alert-danger');
            alertBox.textContent = 'Lỗi kết nối';
        });
    });
}

// Delegated toggle hiển thị/ẩn PIN (hoạt động với input tạo động)
document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-pin-toggle]');
    if(!btn) return;
    const selector = btn.getAttribute('data-target');
    const target = document.querySelector(selector);
    if(!target) return;
    if(target.type === 'password'){
        target.type='text';
        btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        target.type='password';
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    }
    target.focus();
});

// Tạo động ô PIN trong modal verify để ngăn autofill
const verifyModal = document.getElementById('walletPinVerifyModal');
verifyModal?.addEventListener('shown.bs.modal', () => {
    const wrapper = document.getElementById('verifyPinWrapper');
    if(!wrapper || wrapper.dataset.built) return;
    const randomName = 'p_'+Math.random().toString(36).slice(2);
    const input = document.createElement('input');
    input.type = 'password';
    input.inputMode = 'numeric';
    input.autocomplete = 'new-password';
    input.maxLength = 6;
    input.pattern = '\\d{6}';
    input.className = 'form-control';
    input.id = 'verifyPinInput';
    input.name = randomName; // tránh tên cố định
    input.setAttribute('aria-label','Mã PIN 6 số');
    input.setAttribute('data-pin-field','');
    input.setAttribute('data-lpignore','true');
    input.setAttribute('data-1p-ignore','true');
    const btn = document.createElement('button');
    btn.type='button';
    btn.className='btn btn-outline-secondary';
    btn.setAttribute('data-pin-toggle','');
    btn.setAttribute('data-target','#verifyPinInput');
    btn.innerHTML = '<i class="fas fa-eye"></i>';
    wrapper.appendChild(input);
    wrapper.appendChild(btn);
    wrapper.dataset.built = '1';
    setTimeout(()=>input.focus(),30);
});
</script>
@endpush
