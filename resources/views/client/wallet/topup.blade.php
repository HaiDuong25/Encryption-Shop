@extends('client.layout.main')

@section('title', 'Nạp tiền')

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
                    <li class="breadcrumb-item"><a href="{{ route('wallet.index') }}">Ví của tôi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nạp tiền</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-wallet me-2"></i>Nạp tiền vào ví
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('wallet.process-topup') }}" method="POST" id="topupForm">
                        @csrf
                        
                        <!-- Amount Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-money-bill me-2"></i>Chọn số tiền nạp:
                            </label>
                            <div class="row g-3 mb-3">
                                @foreach([50000, 100000, 200000, 500000, 1000000, 2000000, 5000000, 10000000, 20000000] as $amount)
                                    <div class="col-md-4 col-6">
                                        <div class="amount-option" data-amount="{{ $amount }}">
                                            <div class="amount-card text-center p-3">
                                                <div class="amount-value">{{ number_format($amount, 0, ',', '.') }}</div>
                                                <div class="amount-currency">VND</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mb-3">
                                <label for="custom_amount" class="form-label">Hoặc nhập số tiền khác:</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="custom_amount" 
                                           name="amount"
                                           min="10000" 
                                           max="50000000" 
                                           step="1000"
                                           placeholder="Nhập số tiền (tối thiểu 10.000 VND)">
                                    <span class="input-group-text">VND</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Số tiền nạp tối thiểu: 10.000 VND, tối đa: 50.000.000 VND
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-credit-card me-2"></i>Chọn phương thức thanh toán:
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="payment-method-card">
                                        <input type="radio" class="btn-check" name="payment_method" value="momo" id="momo" required>
                                        <label class="btn btn-outline-primary w-100 p-3" for="momo">
                                            <div class="d-flex align-items-center">
                                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" 
                                                     alt="MoMo" class="me-3" style="width: 40px; height: 40px;">
                                                <div class="text-start">
                                                    <div class="fw-bold">Ví MoMo</div>
                                                    <div class="text-muted small">Thanh toán qua ví điện tử MoMo</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="payment-method-card">
                                        <input type="radio" class="btn-check" name="payment_method" value="zalopay" id="zalopay" required>
                                        <label class="btn btn-outline-info w-100 p-3" for="zalopay">
                                            <div class="d-flex align-items-center">
                                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png" 
                                                     alt="ZaloPay" class="me-3" style="width: 40px; height: 40px;">
                                                <div class="text-start">
                                                    <div class="fw-bold">ZaloPay</div>
                                                    <div class="text-muted small">Thanh toán qua ví điện tử ZaloPay</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Summary -->
                        <div class="card bg-light mb-4" id="summary-section" style="display: none;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Tóm tắt giao dịch:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-muted">Số tiền nạp:</div>
                                        <div class="fw-bold" id="summary-amount">0 VND</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted">Phương thức:</div>
                                        <div class="fw-bold" id="summary-method">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('wallet.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Tiến hành nạp tiền
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Hướng dẫn nạp tiền</h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Chọn số tiền muốn nạp (tối thiểu 10.000 VND)</li>
                        <li>Chọn phương thức thanh toán (MoMo hoặc ZaloPay)</li>
                        <li>Nhấn "Tiến hành nạp tiền" để chuyển đến trang thanh toán</li>
                        <li>Hoàn tất thanh toán trên ứng dụng ví điện tử</li>
                        <li>Số dư sẽ được cập nhật tự động sau khi thanh toán thành công</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.amount-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.amount-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    background: #fff;
    transition: all 0.3s ease;
}

.amount-option:hover .amount-card,
.amount-option.selected .amount-card {
    border-color: #007bff;
    background: #f8f9ff;
    transform: scale(1.05);
}

.amount-value {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
}

.amount-currency {
    font-size: 0.9rem;
    color: #666;
}

.payment-method-card .btn-check:checked + .btn {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.payment-method-card .btn-check:checked + .btn[for="zalopay"] {
    background-color: var(--bs-info);
    border-color: var(--bs-info);
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.btn-outline-primary:hover,
.btn-outline-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountOptions = document.querySelectorAll('.amount-option');
    const customAmountInput = document.getElementById('custom_amount');
    const summarySection = document.getElementById('summary-section');
    const summaryAmount = document.getElementById('summary-amount');
    const summaryMethod = document.getElementById('summary-method');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');

    // Amount selection
    amountOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            amountOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Set value to input
            const amount = this.dataset.amount;
            customAmountInput.value = amount;
            
            // Update summary
            updateSummary();
        });
    });

    // Custom amount input
    customAmountInput.addEventListener('input', function() {
        // Remove selected class from preset options
        amountOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Update summary
        updateSummary();
    });

    // Payment method selection
    paymentMethods.forEach(method => {
        method.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        const amount = parseFloat(customAmountInput.value) || 0;
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (amount > 0 && selectedMethod) {
            summarySection.style.display = 'block';
            summaryAmount.textContent = new Intl.NumberFormat('vi-VN').format(amount) + ' VND';
            
            if (selectedMethod.value === 'momo') {
                summaryMethod.textContent = 'Ví MoMo';
            } else if (selectedMethod.value === 'zalopay') {
                summaryMethod.textContent = 'ZaloPay';
            }
        } else {
            summarySection.style.display = 'none';
        }
    }

    // Format number input
    customAmountInput.addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = Math.round(value);
        }
    });
});
</script>
@endsection
