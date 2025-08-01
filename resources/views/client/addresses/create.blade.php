@extends('client.layout.main')

@section('title', 'Thêm địa chỉ mới')

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
</style>

<div class="addresses-wrapper">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Thêm địa chỉ mới</h1>
            <p class="mb-0 text-muted">Thêm địa chỉ giao hàng mới vào sổ địa chỉ</p>
        </div>
        <a href="{{ route('client.addresses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('client.addresses.store') }}" method="POST">
                @csrf
                
                <div class="form-card">
                    <!-- Thông tin liên hệ -->
                    <div class="form-section">
                        <h6><i class="fas fa-user me-2"></i>Thông tin liên hệ</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên <span class="required">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
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
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
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
                                        id="district" name="district" required onchange="loadWards()" disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="required">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" 
                                        id="ward" name="ward" required disabled>
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
                                      placeholder="Số nhà, tên đường..." required>{{ old('address_detail') }}</textarea>
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
                                           name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="2" 
                                      placeholder="Ghi chú thêm về địa chỉ này...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu địa chỉ
                        </button>
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
                    <i class="fas fa-info-circle me-2"></i>Lưu ý
                </h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Địa chỉ sẽ được lưu vào sổ địa chỉ để sử dụng cho các đơn hàng tiếp theo
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Bạn có thể đặt địa chỉ này làm mặc định
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Có thể chỉnh sửa hoặc xóa địa chỉ này sau khi lưu
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function loadDistricts() {
    const province = document.getElementById('province').value;
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset districts and wards
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    
    if (province) {
        console.log('Loading districts for province:', province);
        
        // Show loading
        districtSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        return fetch(`/api/districts?province=${encodeURIComponent(province)}`)
            .then(response => {
                console.log('Districts API response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(districts => {
                console.log('Districts received:', districts);
                
                // Reset select with default option
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                
                if (Array.isArray(districts) && districts.length > 0) {
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                    districtSelect.disabled = false;
                } else {
                    console.warn('No districts found for province:', province);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu Quận/Huyện';
                    districtSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                
                // Show user-friendly error message
                alert('Không thể tải danh sách Quận/Huyện. Vui lòng thử lại sau.');
            });
    }
    
    return Promise.resolve();
}

function loadWards() {
    const district = document.getElementById('district').value;
    const province = document.getElementById('province').value;
    const wardSelect = document.getElementById('ward');
    
    // Reset wards
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    if (district && province) {
        console.log('Loading wards for district:', district, 'in province:', province);
        
        // Show loading
        wardSelect.innerHTML = '<option value="">Đang tải...</option>';
        
        const url = `/api/wards?district=${encodeURIComponent(district)}&province=${encodeURIComponent(province)}`;
        return fetch(url)
            .then(response => {
                console.log('Wards API response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(wards => {
                console.log('Wards received:', wards);
                
                // Reset select with default option
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                
                if (Array.isArray(wards) && wards.length > 0) {
                    wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward;
                        option.textContent = ward;
                        wardSelect.appendChild(option);
                    });
                    wardSelect.disabled = false;
                } else {
                    console.warn('No wards found for district:', district);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu Phường/Xã';
                    wardSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                
                // Show user-friendly error message
                alert('Không thể tải danh sách Phường/Xã. Vui lòng thử lại sau.');
            });
    }
    
    return Promise.resolve();
}
                    wardSelect.disabled = false;
                } else {
                    console.warn('No wards found for district:', district);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu Phường/Xã';
                    wardSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                
                // Show user-friendly error message
                alert('Không thể tải danh sách Phường/Xã. Vui lòng thử lại sau.');
            });
    }
}

// Save selections to localStorage for user convenience
function saveCurrentSelections() {
    try {
        const selections = {
            province: document.getElementById('province').value,
            district: document.getElementById('district').value,
            ward: document.getElementById('ward').value,
            timestamp: Date.now()
        };
        
        localStorage.setItem('user_address_selections', JSON.stringify(selections));
    } catch (error) {
        console.warn('Unable to save address selections:', error);
    }
}

// Load saved selections from localStorage
function loadSavedSelections() {
    try {
        const saved = localStorage.getItem('user_address_selections');
        if (!saved) return null;
        
        const selections = JSON.parse(saved);
        
        // Check if data is less than 24 hours old
        const oneDay = 24 * 60 * 60 * 1000;
        if (Date.now() - selections.timestamp > oneDay) {
            localStorage.removeItem('user_address_selections');
            return null;
        }
        
        return selections;
    } catch (error) {
        console.warn('Unable to load saved address selections:', error);
        return null;
    }
}

// Load districts and set saved values
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Load saved selections
    const savedSelections = loadSavedSelections();
    
    if (savedSelections) {
        // Restore province
        if (savedSelections.province) {
            provinceSelect.value = savedSelections.province;
            
            // Load districts and restore district
            loadDistricts().then(() => {
                if (savedSelections.district) {
                    districtSelect.value = savedSelections.district;
                    
                    // Load wards and restore ward
                    loadWards().then(() => {
                        if (savedSelections.ward) {
                            wardSelect.value = savedSelections.ward;
                        }
                    });
                }
            });
        }
    }
    
    // Add event listeners to save selections as user makes changes
    provinceSelect.addEventListener('change', saveCurrentSelections);
    districtSelect.addEventListener('change', saveCurrentSelections);
    wardSelect.addEventListener('change', saveCurrentSelections);
});
</script>
@endsection
