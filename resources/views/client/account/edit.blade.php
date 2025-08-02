@extends('client.layout.main')

@section('title', 'Chỉnh sửa hồ sơ')

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
                <form action="{{ route('account.updateProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-card">
                        <div class="form-header">
                            <h4><i class="fas fa-edit me-2"></i>Chỉnh sửa hồ sơ cá nhân</h4>
                            <p class="text-muted">Cập nhật thông tin cá nhân của bạn</p>
                        </div>

                        <!-- Thông tin cơ bản -->
                        <div class="form-section">
                            <h6><i class="fas fa-user me-2"></i>Thông tin cơ bản</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                           placeholder="0123456789">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <!-- Địa chỉ -->
                        <div class="form-section">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                    <select class="form-select" id="province" name="province">
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ward" class="form-label">Xã/Phường/Thị trấn</label>
                                    <select class="form-select" id="ward" name="ward" disabled>
                                        <option value="">Chọn Xã/Phường/Thị trấn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address_detail" class="form-label">Địa chỉ chi tiết</label>
                                <input type="text" class="form-control" id="address_detail" 
                                       placeholder="Số nhà, tên đường...">
                            </div>

                            <!-- Hidden field để lưu địa chỉ đầy đủ -->
                            <input type="hidden" name="address" id="full_address" value="{{ old('address', auth()->user()->address) }}">
                        </div>

                        <!-- Ảnh đại diện và ảnh bìa -->
                        {{-- <div class="form-section">
                            <h6><i class="fas fa-camera me-2"></i>Hình ảnh cá nhân</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="avatar" class="form-label">Ảnh đại diện</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                           id="avatar" name="avatar" accept="image/*">
                                    <small class="form-text text-muted">
                                        Chấp nhận JPG, PNG, GIF. Tối đa 2MB.
                                    </small>
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if(auth()->user()->avatar)
                                        <div class="mt-2">
                                            <label class="form-label">Ảnh hiện tại</label>
                                            <div>
                                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                                     alt="Ảnh đại diện hiện tại" class="rounded-circle" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="cover_image" class="form-label">Ảnh bìa</label>
                                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                           id="cover_image" name="cover_image" accept="image/*">
                                    <small class="form-text text-muted">
                                        Chấp nhận JPG, PNG, GIF. Tối đa 5MB.
                                    </small>
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if(auth()->user()->cover_image)
                                        <div class="mt-2">
                                            <label class="form-label">Ảnh bìa hiện tại</label>
                                            <div>
                                                <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" 
                                                     alt="Ảnh bìa hiện tại" class="rounded" 
                                                     style="width: 100%; height: 60px; object-fit: cover;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}

                        <!-- Submit buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="{{ route('account.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// API Functions cho địa chỉ Việt Nam sử dụng Local JSON API
let allProvinces = [];
let currentUserAddress = null;

// Load dữ liệu tỉnh thành từ Local API
async function loadProvinces() {
    try {
        const response = await fetch('/api/provinces');
        if (!response.ok) throw new Error('Failed to load provinces');
        
        const provinces = await response.json();
        
        // Convert to format compatible with old code
        allProvinces = provinces.map(provinceName => ({
            name: provinceName,
            code: provinceName // Use name as code for compatibility
        }));
        
        const provinceSelect = document.getElementById('province');
        provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
        
        allProvinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            option.dataset.name = province.name;
            provinceSelect.appendChild(option);
        });
        
        console.log('Provinces loaded successfully:', allProvinces.length);
    } catch (error) {
        console.error('Error loading provinces:', error);
        alert('Không thể tải danh sách tỉnh thành. Vui lòng thử lại sau.');
    }
}

// Load quận/huyện theo tỉnh
// Load xã/phường theo tỉnh (hệ thống 2 cấp)
async function loadWards(provinceName) {
    const wardSelect = document.getElementById('ward');
    
    // Reset wards
    wardSelect.innerHTML = '<option value="">Chọn Xã/Phường/Thị trấn</option>';
    wardSelect.disabled = true;
    
    if (!provinceName) {
        updateCombinedAddress();
        return;
    }
    
    try {
        wardSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        const response = await fetch(`/api/wards?province=${encodeURIComponent(provinceName)}`);
        if (!response.ok) throw new Error('Failed to load wards');
        
        const wards = await response.json();
        
        wardSelect.innerHTML = '<option value="">Chọn Xã/Phường/Thị trấn</option>';
        
        if (wards && wards.length > 0) {
            wards.forEach(wardName => {
                const option = document.createElement('option');
                option.value = wardName;
                option.textContent = wardName;
                option.dataset.name = wardName;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
        }
        
        updateCombinedAddress();
        console.log('Wards loaded successfully for province:', provinceName);
    } catch (error) {
        console.error('Error loading wards:', error);
        wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Không thể tải danh sách xã/phường/thị trấn. Vui lòng thử lại.');
    }
}

// Parse địa chỉ hiện tại của user (cập nhật cho hệ thống 2 cấp)
function parseCurrentAddress() {
    const currentAddress = '{{ old("address", auth()->user()->address) }}';
    if (!currentAddress || currentAddress.trim() === '') return null;
    
    // Tách địa chỉ: "Số nhà, Xã/Phường, Tỉnh" (2 cấp)
    const parts = currentAddress.split(',').map(part => part.trim());
    if (parts.length >= 3) {
        return {
            address_detail: parts[0] || '',
            ward_name: parts[1] || '',
            province_name: parts[2] || ''
        };
    }
    
    return null;
}

// Load provinces từ API
async function loadProvinces() {
    try {
        const response = await fetch('/api/provinces');
        if (!response.ok) throw new Error('Failed to load provinces');
        
        const provinces = await response.json();
        const provinceSelect = document.getElementById('province');
        
        provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
        
        if (provinces && provinces.length > 0) {
            provinces.forEach(provinceName => {
                const option = document.createElement('option');
                option.value = provinceName;
                option.textContent = provinceName;
                option.dataset.name = provinceName;
                provinceSelect.appendChild(option);
            });
        }
        
        console.log('Provinces loaded successfully');
    } catch (error) {
        console.error('Error loading provinces:', error);
        alert('Không thể tải danh sách tỉnh/thành phố. Vui lòng thử lại.');
    }
}

// Update địa chỉ tổng hợp (cập nhật cho hệ thống 2 cấp)
function updateCombinedAddress() {
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');
    const addressDetail = document.getElementById('address_detail').value.trim();
    
    const provinceName = provinceSelect.selectedOptions[0]?.dataset?.name || '';
    const wardName = wardSelect.selectedOptions[0]?.dataset?.name || '';
    
    let fullAddress = '';
    if (addressDetail) fullAddress = addressDetail;
    if (wardName) fullAddress += (fullAddress ? ', ' : '') + wardName;
    if (provinceName) fullAddress += (fullAddress ? ', ' : '') + provinceName;
    
    document.getElementById('full_address').value = fullAddress;
    console.log('Combined address updated:', fullAddress);
}

// Khôi phục địa chỉ đã có (cập nhật cho hệ thống 2 cấp)
async function restoreUserAddress() {
    const currentUserAddress = parseCurrentAddress();
    
    if (!currentUserAddress) {
        console.log('No current address to restore');
        return;
    }
    
    console.log('Restoring address:', currentUserAddress);
    
    // Tìm và set tỉnh
    const provinceSelect = document.getElementById('province');
    const provinceOption = Array.from(provinceSelect.options).find(option => 
        option.value && (
            option.value.toLowerCase().includes(currentUserAddress.province_name.toLowerCase()) ||
            currentUserAddress.province_name.toLowerCase().includes(option.value.toLowerCase())
        )
    );
    
    if (provinceOption) {
        provinceSelect.value = provinceOption.value;
        
        // Load wards và tìm ward
        await loadWards(provinceOption.value);
        
        const wardSelect = document.getElementById('ward');
        const wardOption = Array.from(wardSelect.options).find(option => 
            option.value && (
                option.value.toLowerCase().includes(currentUserAddress.ward_name.toLowerCase()) ||
                currentUserAddress.ward_name.toLowerCase().includes(option.value.toLowerCase())
            )
        );
        
        if (wardOption) {
            wardSelect.value = wardOption.value;
        }
        
        // Set address detail
        document.getElementById('address_detail').value = currentUserAddress.address_detail;
        updateCombinedAddress();
    }
}

// Initialize khi trang load
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Initializing address selectors (2-level system)...');
    
    // Load provinces trước
    await loadProvinces();
    
    // Khôi phục địa chỉ user nếu có
    await restoreUserAddress();
    
    // Add event listeners
    document.getElementById('province').addEventListener('change', function() {
        loadWards(this.value);
    });
    
    document.getElementById('ward').addEventListener('change', function() {
        updateCombinedAddress();
    });
    
    document.getElementById('address_detail').addEventListener('input', function() {
        updateCombinedAddress();
    });
    
    console.log('Address system (2-level) initialized successfully');
});
</script>

<style>
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
</style>

@endsection


