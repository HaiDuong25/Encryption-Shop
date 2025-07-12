@extends('admin.layouts.main')

@section('title', 'Thêm địa chỉ giao hàng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="title-header">
            <h5>Thêm địa chỉ giao hàng mới</h5>
            <a href="{{ route('shipping-addresses.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('shipping-addresses.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Thông tin liên hệ -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6><i class="ri-user-line"></i> Thông tin liên hệ</h6>
                        </div>
                        <div class="card-body">
                            @if(request('user_id'))
                                @php $selectedUser = \App\Models\User::find(request('user_id')); @endphp
                                @if($selectedUser)
                                    <div class="alert alert-info">
                                        <i class="ri-information-line"></i> 
                                        Đang tạo địa chỉ cho tài khoản: <strong>{{ $selectedUser->name }}</strong> ({{ $selectedUser->email }})
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                                @endif
                            @else
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Chọn tài khoản <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Chọn tài khoản</option>
                                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $selectedUser->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $selectedUser->phone ?? '') }}" 
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
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
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
                                        id="district" name="district" required onchange="loadWards()" disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" 
                                        id="ward" name="ward" required disabled>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address_detail" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address_detail') is-invalid @enderror" 
                                          id="address_detail" name="address_detail" rows="3" 
                                          placeholder="Số nhà, tên đường..." required>{{ old('address_detail') }}</textarea>
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" 
                                       name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
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
                                  placeholder="Ghi chú thêm về địa chỉ này...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('shipping-addresses.index') }}" class="btn btn-secondary me-2">Hủy</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Lưu địa chỉ
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
    
    // Reset districts and wards
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    
    if (province) {
        console.log('Loading districts for province:', province);
        
        fetch(`/api/districts?province=${encodeURIComponent(province)}`)
            .then(response => {
                console.log('Districts API response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(districts => {
                console.log('Districts received:', districts);
                
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
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Lỗi tải dữ liệu Quận/Huyện';
                districtSelect.appendChild(option);
            });
    }
}

function loadWards() {
    const district = document.getElementById('district').value;
    const province = document.getElementById('province').value;
    const wardSelect = document.getElementById('ward');
    
    // Reset wards
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

// Khôi phục giá trị cũ khi có lỗi validation
document.addEventListener('DOMContentLoaded', function() {
    const oldProvince = '{{ old("province") }}';
    const oldDistrict = '{{ old("district") }}';
    const oldWard = '{{ old("ward") }}';
    
    if (oldProvince) {
        document.getElementById('province').value = oldProvince;
        loadDistricts();
        
        // Đợi districts load xong rồi mới set district
        setTimeout(() => {
            if (oldDistrict) {
                document.getElementById('district').value = oldDistrict;
                loadWards();
                
                // Đợi wards load xong rồi mới set ward
                setTimeout(() => {
                    if (oldWard) {
                        document.getElementById('ward').value = oldWard;
                    }
                }, 500);
            }
        }, 500);
    }
});
</script>
@endsection
