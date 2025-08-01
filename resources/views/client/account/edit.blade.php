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
                                <div class="col-md-4 mb-3">
                                    <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                    <select class="form-select" id="province" name="province">
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="district" class="form-label">Quận/Huyện</label>
                                    <select class="form-select" id="district" name="district" disabled>
                                        <option value="">Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ward" class="form-label">Phường/Xã</label>
                                    <select class="form-select" id="ward" name="ward" disabled>
                                        <option value="">Chọn Phường/Xã</option>
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
// API Functions cho địa chỉ Việt Nam sử dụng provinces.open-api.vn
let allProvinces = [];
let currentUserAddress = null;

// Load dữ liệu tỉnh thành từ API
async function loadProvinces() {
    try {
        const response = await fetch('https://provinces.open-api.vn/api/p/');
        if (!response.ok) throw new Error('Failed to load provinces');
        
        allProvinces = await response.json();
        
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
async function loadDistricts(provinceCode) {
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset districts và wards
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    
    if (!provinceCode) {
        updateCombinedAddress();
        return;
    }
    
    try {
        districtSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        const response = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
        if (!response.ok) throw new Error('Failed to load districts');
        
        const province = await response.json();
        
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        
        if (province.districts && province.districts.length > 0) {
            province.districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                option.dataset.name = district.name;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        }
        
        updateCombinedAddress();
        console.log('Districts loaded successfully for province:', province.name);
    } catch (error) {
        console.error('Error loading districts:', error);
        districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Không thể tải danh sách quận/huyện. Vui lòng thử lại.');
    }
}

// Load phường/xã theo quận/huyện
async function loadWards(districtCode) {
    const wardSelect = document.getElementById('ward');
    
    // Reset wards
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    if (!districtCode) {
        updateCombinedAddress();
        return;
    }
    
    try {
        wardSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        const response = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        if (!response.ok) throw new Error('Failed to load wards');
        
        const district = await response.json();
        
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        if (district.wards && district.wards.length > 0) {
            district.wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.code;
                option.textContent = ward.name;
                option.dataset.name = ward.name;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
        }
        
        updateCombinedAddress();
        console.log('Wards loaded successfully for district:', district.name);
    } catch (error) {
        console.error('Error loading wards:', error);
        wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Không thể tải danh sách phường/xã. Vui lòng thử lại.');
    }
}

// Parse địa chỉ hiện tại của user
function parseCurrentAddress() {
    const currentAddress = '{{ old("address", auth()->user()->address) }}';
    if (!currentAddress || currentAddress.trim() === '') return null;
    
    // Tách địa chỉ: "Số nhà, Phường, Quận, Tỉnh"
    const parts = currentAddress.split(',').map(part => part.trim());
    if (parts.length >= 4) {
        return {
            address_detail: parts[0] || '',
            ward_name: parts[1] || '',
            district_name: parts[2] || '',
            province_name: parts[3] || ''
        };
    }
    
    return null;
}

// Tìm mã tỉnh theo tên
function findProvinceByName(name) {
    return allProvinces.find(p => 
        p.name.toLowerCase().includes(name.toLowerCase()) ||
        name.toLowerCase().includes(p.name.toLowerCase())
    );
}

// Update địa chỉ tổng hợp
function updateCombinedAddress() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressDetail = document.getElementById('address_detail').value.trim();
    
    const provinceName = provinceSelect.selectedOptions[0]?.dataset?.name || '';
    const districtName = districtSelect.selectedOptions[0]?.dataset?.name || '';
    const wardName = wardSelect.selectedOptions[0]?.dataset?.name || '';
    
    let fullAddress = '';
    if (addressDetail) fullAddress = addressDetail;
    if (wardName) fullAddress += (fullAddress ? ', ' : '') + wardName;
    if (districtName) fullAddress += (fullAddress ? ', ' : '') + districtName;
    if (provinceName) fullAddress += (fullAddress ? ', ' : '') + provinceName;
    
    document.getElementById('full_address').value = fullAddress;
    console.log('Combined address updated:', fullAddress);
}

// Khôi phục địa chỉ đã có
async function restoreUserAddress() {
    currentUserAddress = parseCurrentAddress();
    
    if (!currentUserAddress) {
        console.log('No current address to restore');
        return;
    }
    
    console.log('Restoring address:', currentUserAddress);
    
    // Tìm và set tỉnh
    const province = findProvinceByName(currentUserAddress.province_name);
    if (province) {
        document.getElementById('province').value = province.code;
        
        // Load districts và tìm district
        await loadDistricts(province.code);
        
        const districtSelect = document.getElementById('district');
        const districtOption = Array.from(districtSelect.options).find(option => 
            option.dataset.name && (
                option.dataset.name.toLowerCase().includes(currentUserAddress.district_name.toLowerCase()) ||
                currentUserAddress.district_name.toLowerCase().includes(option.dataset.name.toLowerCase())
            )
        );
        
        if (districtOption) {
            districtSelect.value = districtOption.value;
            
            // Load wards và tìm ward
            await loadWards(districtOption.value);
            
            const wardSelect = document.getElementById('ward');
            const wardOption = Array.from(wardSelect.options).find(option => 
                option.dataset.name && (
                    option.dataset.name.toLowerCase().includes(currentUserAddress.ward_name.toLowerCase()) ||
                    currentUserAddress.ward_name.toLowerCase().includes(option.dataset.name.toLowerCase())
                )
            );
            
            if (wardOption) {
                wardSelect.value = wardOption.value;
            }
        }
        
        // Set address detail
        document.getElementById('address_detail').value = currentUserAddress.address_detail;
        updateCombinedAddress();
    }
}

// Initialize khi trang load
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Initializing address selectors...');
    
    // Load provinces trước
    await loadProvinces();
    
    // Khôi phục địa chỉ user nếu có
    await restoreUserAddress();
    
    // Add event listeners
    document.getElementById('province').addEventListener('change', function() {
        loadDistricts(this.value);
    });
    
    document.getElementById('district').addEventListener('change', function() {
        loadWards(this.value);
    });
    
    document.getElementById('ward').addEventListener('change', function() {
        updateCombinedAddress();
    });
    
    document.getElementById('address_detail').addEventListener('input', function() {
        updateCombinedAddress();
    });
    
    console.log('Address system initialized successfully');
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


