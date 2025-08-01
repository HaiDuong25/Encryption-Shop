@extends('admin.layouts.main')

@section('title', 'Chi tiết địa chỉ giao hàng')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="title-header">
            <h5>Chi tiết địa chỉ giao hàng #{{ $shippingAddress->id }}</h5>
            <div>
                <a href="{{ route('shipping-addresses.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin liên hệ -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="ri-user-line"></i> Thông tin liên hệ</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4"><strong>Họ tên:</strong></div>
                            <div class="col-8">{{ $shippingAddress->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4"><strong>Số điện thoại:</strong></div>
                            <div class="col-8">
                                <a href="tel:{{ $shippingAddress->phone }}" class="text-success">
                                    {{ $shippingAddress->phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin địa chỉ -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="ri-map-pin-line"></i> Thông tin địa chỉ</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4"><strong>Tỉnh/TP:</strong></div>
                            <div class="col-8">{{ $shippingAddress->province }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4"><strong>Quận/Huyện:</strong></div>
                            <div class="col-8">{{ $shippingAddress->district }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4"><strong>Phường/Xã:</strong></div>
                            <div class="col-8">{{ $shippingAddress->ward }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4"><strong>Địa chỉ chi tiết:</strong></div>
                            <div class="col-8">{{ $shippingAddress->address_detail }}</div>
                        </div>
                        <div class="row">
                            <div class="col-4"><strong>Địa chỉ đầy đủ:</strong></div>
                            <div class="col-8">
                                <div class="alert alert-light">
                                    {{ $shippingAddress->full_address }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cài đặt và thông tin khác -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="ri-settings-line"></i> Cài đặt</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4"><strong>Địa chỉ mặc định:</strong></div>
                            <div class="col-8">
                                @if($shippingAddress->is_default)
                                    <span class="badge bg-primary">Địa chỉ mặc định</span>
                                @else
                                    <span class="badge bg-outline-secondary">Không</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="ri-information-line"></i> Thông tin khác</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4"><strong>Ngày tạo:</strong></div>
                            <div class="col-8">{{ $shippingAddress->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4"><strong>Cập nhật cuối:</strong></div>
                            <div class="col-8">{{ $shippingAddress->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($shippingAddress->note)
                            <div class="row">
                                <div class="col-4"><strong>Ghi chú:</strong></div>
                                <div class="col-8">
                                    <div class="alert alert-light">
                                        {{ $shippingAddress->note }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo chỉ đọc -->
<div class="alert alert-info mt-3">
    <i class="ri-information-line"></i>
    <strong>Lưu ý:</strong> Admin chỉ có quyền xem địa chỉ giao hàng để hỗ trợ xử lý đơn hàng. 
    Để tôn trọng quyền riêng tư, người dùng phải tự quản lý địa chỉ của họ.
</div>
@endsection
