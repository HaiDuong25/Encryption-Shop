@extends('client.layout.main')

@section('content')
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-xxl-3 col-lg-4">
                @include('client.account.sidebar')
            </div>

            <!-- Main content -->
            <div class="col-xxl-9 col-lg-8">
                <div class="dashboard-right-sidebar">
                    <div class="dashboard-profile">
                        <div class="title d-flex justify-content-between align-items-center">
                            <h2>Edit Profile</h2>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="edit-profile-form" action="{{ route('account.updateProfile') }}" method="POST" enctype="multipart/form-data" class="dashboard-bg-box p-4 rounded shadow-sm">

                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>

                                <!-- Province -->
                                <div class="col-md-4">
                                    <label class="form-label">Province/City</label>
                                    <select id="province" class="form-select" required></select>
                                </div>

                                <!-- District -->
                                <div class="col-md-4">
                                    <label class="form-label">District</label>
                                    <select id="district" class="form-select" required></select>
                                </div>

                                <!-- Ward -->
                                <div class="col-md-4">
                                    <label class="form-label">Ward</label>
                                    <select id="ward" class="form-select" required></select>
                                </div>

                                <!-- Street -->
                                <div class="col-md-12">
                                    <label class="form-label">Street / House No.</label>
                                    <input type="text" id="address_detail" class="form-control" placeholder="123 Lê Lợi" required>
                                </div>

                                <!-- Hidden full address -->
                                <input type="hidden" name="address" id="full_address" value="{{ old('address', auth()->user()->address) }}">

                                <!-- Avatar -->
                                <div class="col-md-12">
                                    <label class="form-label">Avatar</label>
                                    <input type="file" name="avatar" class="form-control">
                                    @if (auth()->user()->avatar)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" width="100" class="rounded-circle" alt="Avatar">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('account.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");
    const detailInput = document.getElementById("address_detail");
    const addressInput = document.getElementById("full_address");

    let dataVN = [];

    axios.get("/js/vietnam-location.json")
        .then(res => {
            dataVN = res.data;
            provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
            dataVN.forEach(province => {
                provinceSelect.innerHTML += `<option value="${province.code}" data-name="${province.name}">${province.name}</option>`;
            });
        });

    provinceSelect.addEventListener("change", function () {
        const province = dataVN.find(p => p.code == this.value);
        districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

        if (province) {
            province.districts.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.code}" data-name="${district.name}">${district.name}</option>`;
            });
        }
    });

    districtSelect.addEventListener("change", function () {
        const province = dataVN.find(p => p.code == provinceSelect.value);
        const district = province?.districts?.find(d => d.code == this.value);
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

        if (district) {
            district.wards.forEach(ward => {
                wardSelect.innerHTML += `<option value="${ward.code}" data-name="${ward.name}">${ward.name}</option>`;
            });
        }
    });

    // Gộp địa chỉ trước khi submit
    const form = document.getElementById("edit-profile-form");
    form.addEventListener("submit", function (e) {
        const provinceName = provinceSelect.selectedOptions[0]?.dataset.name || '';
        const districtName = districtSelect.selectedOptions[0]?.dataset.name || '';
        const wardName = wardSelect.selectedOptions[0]?.dataset.name || '';
        const street = detailInput.value || '';

        const fullAddress = `${street}, ${wardName}, ${districtName}, ${provinceName}`.trim();
        addressInput.value = fullAddress;
        console.log("Full Address:", addressInput.value);

    });
});
</script>
@endsection


