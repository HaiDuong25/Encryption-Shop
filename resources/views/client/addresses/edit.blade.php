@extends('client.layout.main')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
<style>
.addresses-wrapper {
    max-width: 1500px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem 1rem;
}
.form-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    padding: 2rem;
}
.form-section {
    border-left: 4px solid #4e73df;
    padding-left: 1rem;
    margin-bottom: 2rem;
}
.form-section h6 {
    color: #4e73df;
    font-weight: 600;
    margin-bottom: 1rem;
}
.required {
    color: #e74a3b;
}
.badge-current {
    background-color: #17a2b8;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}
</style>

<div class="addresses-wrapper">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa địa chỉ</h1>
            <p class="mb-0 text-muted">
                Cập nhật thông tin địa chỉ giao hàng
                @if($address->is_default)
                    <span class="badge-current ms-2">Địa chỉ mặc định</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('client.addresses.show', $address) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>Xem
            </a>
            <a href="{{ route('client.addresses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('client.addresses.update', $address) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-card">
                    <!-- Thông tin liên hệ -->
                    <div class="form-section">
                        <h6><i class="fas fa-user me-2"></i>Thông tin liên hệ</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên <span class="required">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $address->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $address->phone) }}" 
                                       placeholder="0123456789" required>
                                @error('phone')
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
                                <label for="province" class="form-label">Tỉnh/Thành phố <span class="required">*</span></label>
                                <select class="form-select @error('province') is-invalid @enderror" 
                                        id="province" name="province" required onchange="loadDistricts()">
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" 
                                                {{ old('province', $address->province) == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="required">*</span></label>
                                <select class="form-select @error('district') is-invalid @enderror" 
                                        id="district" name="district" required onchange="loadWards()">
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="required">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" 
                                        id="ward" name="ward" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_detail" class="form-label">Địa chỉ chi tiết <span class="required">*</span></label>
                            <textarea class="form-control @error('address_detail') is-invalid @enderror" 
                                      id="address_detail" name="address_detail" rows="3" 
                                      placeholder="Số nhà, tên đường..." required>{{ old('address_detail', $address->address_detail) }}</textarea>
                            @error('address_detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cài đặt -->
                    <div class="form-section">
                        <h6><i class="fas fa-cog me-2"></i>Cài đặt</h6>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" 
                                           name="is_default" value="1" 
                                           {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                    @if($address->is_default)
                                        <small class="text-muted d-block">Đây hiện là địa chỉ mặc định của bạn</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="2" 
                                      placeholder="Ghi chú thêm về địa chỉ này...">{{ old('note', $address->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật địa chỉ
                        </button>
                        <a href="{{ route('client.addresses.show', $address) }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>Xem địa chỉ
                        </a>
                        <a href="{{ route('client.addresses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy bỏ
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Side info -->
        <div class="col-lg-4">
            <div class="form-card">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Thông tin hiện tại
                </h6>
                <div class="text-muted small">
                    <div class="mb-2">
                        <strong>Tạo lúc:</strong> {{ $address->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Cập nhật:</strong> {{ $address->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @if($address->is_default)
                        <div class="mb-2">
                            <span class="badge bg-info">Địa chỉ mặc định</span>
                        </div>
                    @endif
                </div>
                
                <hr>
                
                <h6 class="text-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Lưu ý
                </h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Thay đổi sẽ được áp dụng cho các đơn hàng tiếp theo
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Nếu đặt làm mặc định, địa chỉ cũ sẽ không còn là mặc định
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Có thể xóa địa chỉ này nếu không cần thiết
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// API Functions sử dụng LocationController nội bộ
let allProvinces = [];

// Store original values for edit form
const originalValues = {
    province: `{{ old('province', $address->province) }}`,
    district: `{{ old('district', $address->district) }}`,
    ward: `{{ old('ward', $address->ward) }}`
};

console.log('Original values:', originalValues);

// Load dữ liệu tỉnh thành từ LocationController (fallback to direct API if needed)
async function loadProvinces() {
    try {
        console.log('Starting to load provinces from /api/provinces');
        
        let response = await fetch('/api/provinces');
        console.log('Response status:', response.status);
        
        // If internal API fails, fallback to external API
        if (!response.ok) {
            console.warn('Internal API failed, trying external API...');
            response = await fetch('https://provinces.open-api.vn/api/p/');
            
            if (!response.ok) {
                throw new Error('Both internal and external APIs failed');
            }
            
            // Process external API response
            const provincesData = await response.json();
            const provinces = provincesData.map(p => p.name);
            
            const provinceSelect = document.getElementById('province');
            const firstOption = provinceSelect.querySelector('option[value=""]');
            provinceSelect.innerHTML = '';
            if (firstOption) provinceSelect.appendChild(firstOption);
            
            allProvinces = provinces;
            
            provinces.forEach(provinceName => {
                const option = document.createElement('option');
                option.value = provinceName;
                option.textContent = provinceName;
                provinceSelect.appendChild(option);
            });
            
            console.log('Provinces loaded successfully from external API:', provinces.length);
            
            // Restore original province value if exists
            if (originalValues.province) {
                provinceSelect.value = originalValues.province;
                if (provinceSelect.value === originalValues.province) {
                    await loadDistricts();
                }
            }
            
            return;
        }
        
        const provinces = await response.json();
        
        const provinceSelect = document.getElementById('province');
        // Giữ lại option đầu tiên
        const firstOption = provinceSelect.querySelector('option[value=""]');
        provinceSelect.innerHTML = '';
        if (firstOption) provinceSelect.appendChild(firstOption);
        
        // Lưu danh sách provinces
        allProvinces = provinces;
        
        provinces.forEach(provinceName => {
            const option = document.createElement('option');
            option.value = provinceName;
            option.textContent = provinceName;
            provinceSelect.appendChild(option);
        });
        
        console.log('Provinces loaded successfully:', provinces.length);
        
        // Restore original province value if exists
        if (originalValues.province) {
            provinceSelect.value = originalValues.province;
            if (provinceSelect.value === originalValues.province) {
                await loadDistricts();
            }
        }
        
    } catch (error) {
        console.error('Error loading provinces:', error);
        alert('Không thể tải danh sách tỉnh thành. Vui lòng thử lại sau.');
    }
}

// Load quận/huyện theo tỉnh
async function loadDistricts() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset districts và wards
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    
    const provinceName = provinceSelect.value;
    if (!provinceName) return;
    
    try {
        districtSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        let response = await fetch(`/api/districts?province=${encodeURIComponent(provinceName)}`);
        
        // If internal API fails, fallback to external API  
        if (!response.ok) {
            console.warn('Internal districts API failed, trying external API...');
            response = await fetch('https://provinces.open-api.vn/api/?depth=2');
            
            if (!response.ok) throw new Error('Failed to load districts');
            
            const provincesData = await response.json();
            const targetProvince = provincesData.find(p => p.name === provinceName);
            
            if (!targetProvince || !targetProvince.districts) {
                districtSelect.innerHTML = '<option value="">Không có dữ liệu Quận/Huyện</option>';
                return;
            }
            
            const districts = targetProvince.districts.map(d => d.name);
            
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            
            districts.forEach(districtName => {
                const option = document.createElement('option');
                option.value = districtName;
                option.textContent = districtName;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
            
            // Restore original district value if exists
            if (originalValues.district) {
                districtSelect.value = originalValues.district;
                if (districtSelect.value === originalValues.district) {
                    await loadWards();
                }
            }
            
            console.log('Districts loaded successfully from external API for province:', provinceName);
            return;
        }
        
        const districts = await response.json();
        
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        
        if (Array.isArray(districts) && districts.length > 0) {
            districts.forEach(districtName => {
                const option = document.createElement('option');
                option.value = districtName;
                option.textContent = districtName;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
            
            // Restore original district value if exists
            if (originalValues.district) {
                districtSelect.value = originalValues.district;
                if (districtSelect.value === originalValues.district) {
                    await loadWards();
                }
            }
        } else {
            districtSelect.innerHTML = '<option value="">Không có dữ liệu Quận/Huyện</option>';
        }
        
        console.log('Districts loaded successfully for province:', provinceName);
    } catch (error) {
        console.error('Error loading districts:', error);
        districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Không thể tải danh sách quận/huyện. Vui lòng thử lại.');
    }
}

// Load phường/xã theo quận/huyện
async function loadWards() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset wards
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    const provinceName = provinceSelect.value;
    const districtName = districtSelect.value;
    
    if (!provinceName || !districtName) return;
    
    try {
        wardSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        let response = await fetch(`/api/wards?province=${encodeURIComponent(provinceName)}&district=${encodeURIComponent(districtName)}`);
        
        // If internal API fails, fallback to external API
        if (!response.ok) {
            console.warn('Internal wards API failed, trying external API...');
            response = await fetch('https://provinces.open-api.vn/api/?depth=3');
            
            if (!response.ok) throw new Error('Failed to load wards');
            
            const provincesData = await response.json();
            const targetProvince = provincesData.find(p => p.name === provinceName);
            
            if (!targetProvince || !targetProvince.districts) {
                wardSelect.innerHTML = '<option value="">Không có dữ liệu Phường/Xã</option>';
                return;
            }
            
            const targetDistrict = targetProvince.districts.find(d => d.name === districtName);
            
            if (!targetDistrict || !targetDistrict.wards) {
                wardSelect.innerHTML = '<option value="">Không có dữ liệu Phường/Xã</option>';
                return;
            }
            
            const wards = targetDistrict.wards.map(w => w.name);
            
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            wards.forEach(wardName => {
                const option = document.createElement('option');
                option.value = wardName;
                option.textContent = wardName;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
            
            // Restore original ward value if exists
            if (originalValues.ward) {
                wardSelect.value = originalValues.ward;
                if (wardSelect.value === originalValues.ward) {
                    console.log('Ward set successfully:', originalValues.ward);
                } else {
                    console.warn('Could not set ward value:', originalValues.ward);
                }
            }
            
            console.log('Wards loaded successfully from external API for district:', districtName);
            return;
        }
        
        const wards = await response.json();
        
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        if (Array.isArray(wards) && wards.length > 0) {
            wards.forEach(wardName => {
                const option = document.createElement('option');
                option.value = wardName;
                option.textContent = wardName;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
            
            // Restore original ward value if exists
            if (originalValues.ward) {
                wardSelect.value = originalValues.ward;
                if (wardSelect.value === originalValues.ward) {
                    console.log('Ward set successfully:', originalValues.ward);
                } else {
                    console.warn('Could not set ward value:', originalValues.ward);
                }
            }
        } else {
            wardSelect.innerHTML = '<option value="">Không có dữ liệu Phường/Xã</option>';
        }
        
        console.log('Wards loaded successfully for district:', districtName);
    } catch (error) {
        console.error('Error loading wards:', error);
        wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        alert('Không thể tải danh sách phường/xã. Vui lòng thử lại.');
    }
}

// Save current selections to localStorage for future use
function saveCurrentSelections() {
    const currentSelections = {
        province: document.getElementById('province').value,
        district: document.getElementById('district').value,
        ward: document.getElementById('ward').value,
        timestamp: Date.now()
    };
    
    localStorage.setItem('lastSelectedAddress', JSON.stringify(currentSelections));
    console.log('Saved current selections:', currentSelections);
}

// Add event listeners
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Page loaded, original values:', originalValues);
    
    // Load provinces first
    await loadProvinces();
    
    // Add event listeners
    document.getElementById('province').addEventListener('change', function() {
        // Clear original values when user manually changes
        originalValues.district = '';
        originalValues.ward = '';
        loadDistricts();
        saveCurrentSelections();
    });
    
    document.getElementById('district').addEventListener('change', function() {
        // Clear original ward when user manually changes district
        originalValues.ward = '';
        loadWards();
        saveCurrentSelections();
    });
    
    document.getElementById('ward').addEventListener('change', function() {
        saveCurrentSelections();
    });
});
</script>
@endsection
