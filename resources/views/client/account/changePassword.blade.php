@extends('client.layout.main')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="address-form-wrapper">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('client.account.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <form action="{{ route('account.updatePassword') }}" method="POST" id="change-password-form">
                    @csrf
                    <div class="form-card">
                        <div class="form-header">
                            <h4><i class="fas fa-key me-2"></i>Đổi mật khẩu</h4>
                            <p class="text-muted">Cập nhật mật khẩu để bảo mật tài khoản của bạn</p>
                        </div>

                        <!-- Hướng dẫn bảo mật -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Yêu cầu mật khẩu mạnh:</h6>
                            <ul class="mb-0 small">
                                <li>Ít nhất 8 ký tự</li>
                                <li>Chứa ít nhất 1 chữ hoa (A-Z)</li>
                                <li>Chứa ít nhất 1 chữ thường (a-z)</li>
                                <li>Chứa ít nhất 1 số (0-9)</li>
                            </ul>
                        </div>

                        <!-- Form đổi mật khẩu -->
                        <div class="form-section">
                            <h6><i class="fas fa-shield-alt me-2"></i>Thông tin mật khẩu</h6>
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password')">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password" required minlength="8"
                                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                           onkeyup="checkPasswordStrength()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password')">
                                        <i class="fas fa-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="password-strength" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="new_password_confirmation" name="new_password_confirmation" required minlength="8"
                                           onkeyup="checkPasswordMatch()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password_confirmation')">
                                        <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                    </button>
                                </div>
                                <div id="password-match" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save me-2"></i>Cập nhật mật khẩu
                            </button>
                            <a href="{{ route('account.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Side info -->
            <div class="col-lg-12 mt-4">
                <div class="form-card">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-lightbulb me-2"></i>Mẹo bảo mật
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Sử dụng mật khẩu duy nhất cho mỗi tài khoản
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Không chia sẻ mật khẩu với ai khác
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Đổi mật khẩu định kỳ để tăng bảo mật
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-2">
                                    <i class="fas fa-times text-danger me-2"></i>
                                    Không sử dụng thông tin cá nhân làm mật khẩu
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times text-danger me-2"></i>
                                    Tránh mật khẩu quá đơn giản như "123456"
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times text-danger me-2"></i>
                                    Không lưu mật khẩu trên thiết bị công cộng
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const iconElement = document.getElementById(fieldId + '_icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        iconElement.classList.remove('fa-eye');
        iconElement.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        iconElement.classList.remove('fa-eye-slash');
        iconElement.classList.add('fa-eye');
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthDiv = document.getElementById('password-strength');
    
    let strength = 0;
    let feedback = [];
    
    // Check length
    if (password.length >= 8) {
        strength += 1;
    } else {
        feedback.push('Ít nhất 8 ký tự');
    }
    
    // Check lowercase
    if (/[a-z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Chứa chữ thường');
    }
    
    // Check uppercase
    if (/[A-Z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Chứa chữ hoa');
    }
    
    // Check numbers
    if (/\d/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Chứa số');
    }
    
    // Display strength
    let strengthText = '';
    let strengthClass = '';
    
    switch (strength) {
        case 0:
        case 1:
            strengthText = 'Rất yếu';
            strengthClass = 'text-danger';
            break;
        case 2:
            strengthText = 'Yếu';
            strengthClass = 'text-warning';
            break;
        case 3:
            strengthText = 'Trung bình';
            strengthClass = 'text-info';
            break;
        case 4:
            strengthText = 'Mạnh';
            strengthClass = 'text-success';
            break;
    }
    
    if (password.length > 0) {
        let html = `<small class="${strengthClass}">Độ mạnh: ${strengthText}</small>`;
        if (feedback.length > 0) {
            html += `<br><small class="text-muted">Thiếu: ${feedback.join(', ')}</small>`;
        }
        strengthDiv.innerHTML = html;
    } else {
        strengthDiv.innerHTML = '';
    }
    
    // Enable/disable submit button
    updateSubmitButton();
}

function checkPasswordMatch() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const matchDiv = document.getElementById('password-match');
    
    if (confirmPassword.length > 0) {
        if (newPassword === confirmPassword) {
            matchDiv.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>Mật khẩu khớp</small>';
        } else {
            matchDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>Mật khẩu không khớp</small>';
        }
    } else {
        matchDiv.innerHTML = '';
    }
    
    // Enable/disable submit button
    updateSubmitButton();
}

function updateSubmitButton() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const submitBtn = document.getElementById('submit-btn');
    
    // Check if password is strong enough and matches
    const isStrongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(newPassword);
    const passwordsMatch = newPassword === confirmPassword && confirmPassword.length > 0;
    
    if (isStrongPassword && passwordsMatch) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-secondary');
        submitBtn.classList.add('btn-primary');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-secondary');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Change Password page loaded');
    
    // Form submit debugging
    const form = document.querySelector('form');
    console.log('Form found:', form);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('=== FORM SUBMIT DEBUG ===');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            const formData = new FormData(this);
            console.log('Form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ':', pair[0].includes('password') ? '***' : pair[1]);
            }
            
            // Validate trước khi submit
            const currentPassword = formData.get('current_password');
            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('new_password_confirmation');
            
            console.log('Validation check:');
            console.log('- Current password:', currentPassword ? 'Provided' : 'Missing');
            console.log('- New password:', newPassword ? 'Provided' : 'Missing');
            console.log('- Confirm password:', confirmPassword ? 'Provided' : 'Missing');
            console.log('- Passwords match:', newPassword === confirmPassword);
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                console.log('Form validation failed - missing fields');
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin');
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                console.log('Form validation failed - passwords do not match');
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });
    }
    
    updateSubmitButton();
    
    // Add event listeners
    document.getElementById('new_password').addEventListener('input', function() {
        checkPasswordStrength();
        checkPasswordMatch();
    });
    
    document.getElementById('new_password_confirmation').addEventListener('input', checkPasswordMatch);
});
</script>

<style>
.input-group .btn {
    border-left: 0;
}

.form-control:focus + .btn {
    border-color: #86b7fe;
}

#submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Style cho các nút có màu nền mặc định */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 500;
    padding: 12px 25px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 500;
    padding: 12px 25px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
}

.btn-outline-secondary {
    background: #f8f9fa !important;
    border: 2px solid #dee2e6 !important;
    color: #6c757d !important;
    border-radius: 0 8px 8px 0;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: #667eea !important;
    border-color: #667eea !important;
    color: white !important;
}

/* Style cho form controls */
.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Style cho form sections */
.form-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #667eea;
}

.form-section h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Style cho required field */
.required {
    color: #e74c3c;
}

/* Style cho alert info */
.alert-info {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
    color: #495057;
}
</style>
@endsection
