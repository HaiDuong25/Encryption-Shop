@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa địa chỉ giao hàng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="title-header">
            <h5>Chỉnh sửa địa chỉ giao hàng #{{ $shippingAddress->id }}</h5>
            <a href="{{ route('shipping-addresses.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('shipping-addresses.update', $shippingAddress) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Thông tin liên hệ -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="ri-user-line"></i> Thông tin liên hệ</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $shippingAddress->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $shippingAddress->phone) }}" 
                                       placeholder="0123456789" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin địa chỉ -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="ri-map-pin-line"></i> Thông tin địa chỉ</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('province') is-invalid @enderror" 
                                        id="province" name="province" required onchange="loadDistricts()">
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" 
                                                {{ old('province', $shippingAddress->province) == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('district') is-invalid @enderror" 
                                        id="district" name="district" required onchange="loadWards()">
                                    <option value="">Chọn Quận/Huyện</option>
                                    <option value="{{ old('district', $shippingAddress->district) }}" selected>
                                        {{ old('district', $shippingAddress->district) }}
                                    </option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" 
                                        id="ward" name="ward" required>
                                    <option value="">Chọn Phường/Xã</option>
                                    <option value="{{ old('ward', $shippingAddress->ward) }}" selected>
                                        {{ old('ward', $shippingAddress->ward) }}
                                    </option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address_detail" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address_detail') is-invalid @enderror" 
                                          id="address_detail" name="address_detail" rows="3" 
                                          placeholder="Số nhà, tên đường..." required>{{ old('address_detail', $shippingAddress->address_detail) }}</textarea>
                                @error('address_detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cài đặt -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="ri-settings-line"></i> Cài đặt</h6>
                </div>
                <div class="card-body">                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" 
                                       name="is_default" value="1"
                                       {{ old('is_default', $shippingAddress->is_default) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="note" class="form-label">Ghi chú</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                  id="note" name="note" rows="2" 
                                  placeholder="Ghi chú thêm về địa chỉ này...">{{ old('note', $shippingAddress->note) }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('shipping-addresses.index') }}" class="btn btn-secondary me-2">Hủy</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Cập nhật địa chỉ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadDistricts() {
    const province = document.getElementById('province').value;
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset districts and wards (keep current value for editing)
    const currentDistrict = districtSelect.value;
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    
    if (province) {
        fetch(`/api/districts?province=${encodeURIComponent(province)}`)
            .then(response => response.json())
            .then(districts => {
                districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    if (district === currentDistrict) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
                // Load wards for current district if exists
                if (currentDistrict) {
                    loadWards();
                }
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                alert('Có lỗi khi tải danh sách Quận/Huyện');
            });
    }
}

function loadWards() {
    const district = document.getElementById('district').value;
    const province = document.getElementById('province').value;
    const wardSelect = document.getElementById('ward');
    
    // Keep current value for editing
    const currentWard = wardSelect.value;
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    if (district) {
        console.log('Loading wards for district:', district, 'in province:', province);
        
        const url = `/api/wards?district=${encodeURIComponent(district)}&province=${encodeURIComponent(province)}`;
        fetch(url)
            .then(response => {
                console.log('Wards API response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(wards => {
                console.log('Wards received:', wards);
                
                if (Array.isArray(wards) && wards.length > 0) {
                    wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward;
                        option.textContent = ward;
                        if (ward === currentWard) {
                            option.selected = true;
                        }
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
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Lỗi tải dữ liệu Phường/Xã';
                wardSelect.appendChild(option);
            });
    }
}

// Load districts on page load for editing
document.addEventListener('DOMContentLoaded', function() {
    const province = document.getElementById('province').value;
    if (province) {
        loadDistricts();
    }
});
</script>
@endsection
