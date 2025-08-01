@extends('admin.layouts.main')

@section('title', 'Quản lý Địa chỉ giao hàng')

@section('content')
<style>
.status-badge {
    font-size: 0.85rem;
    padding: 0.3em 0.6em;
    font-weight: 500;
    border-radius: 4px;
}
.address-info {
    line-height: 1.4;
}
.address-info .name {
    font-weight: 600;
    color: #2563eb;
}
.address-info .phone {
    color: #059669;
    font-size: 0.9em;
}
.address-info .address {
    color: #6b7280;
    font-size: 0.9em;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}
.action-buttons li {
    list-style: none;
}
.action-buttons ul {
    margin: 0;
    padding: 0;
    display: flex;
    gap: 8px;
}
.action-buttons a,
.action-buttons button {
    border: none;
    background: none;
    padding: 0;
    font-size: 1.1rem;
    text-decoration: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="card card-table">
    <div class="card-body">
        <div class="title-header option-title">
            <h5>Quản lý Địa chỉ giao hàng</h5>
            <div class="d-flex gap-2">
                <form action="{{ route('shipping-addresses.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Tìm theo tên hoặc email..." 
                           value="{{ $search }}" style="width: 250px;">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="ri-search-line"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('shipping-addresses.index') }}" class="btn btn-outline-secondary ms-1">
                            <i class="ri-close-line"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table all-package theme-table" id="table_id">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thông tin tài khoản</th>
                        <th>Số địa chỉ</th>
                        <th>Địa chỉ mặc định</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="address-info">
                                <div class="name">{{ $user->name }}</div>
                                <div class="phone text-muted">{{ $user->email }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info status-badge">{{ $user->shipping_addresses_count }} địa chỉ</span>
                        </td>
                        <td>
                            @php
                                $defaultAddress = $user->shippingAddresses->where('is_default', true)->first();
                            @endphp
                            @if($defaultAddress)
                                <div class="address-info">
                                    <div class="address" style="font-size: 0.85em;">{{ Str::limit($defaultAddress->address_detail, 30) }}</div>
                                    <small class="text-muted">{{ $defaultAddress->ward }}, {{ $defaultAddress->district }}</small>
                                </div>
                            @else
                                <span class="text-muted">Chưa có mặc định</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <ul class="action-buttons">
                                <li>
                                    <a href="{{ route('shipping-addresses.user-addresses', $user) }}" 
                                       title="Xem địa chỉ" style="color: #007bff;">
                                        <i class="ri-map-pin-line"></i>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="ri-inbox-line" style="font-size: 2rem;"></i>
                                @if($search)
                                    <p class="mt-2">Không tìm thấy người dùng nào với từ khóa "{{ $search }}"</p>
                                @else
                                    <p class="mt-2">Chưa có người dùng nào có địa chỉ giao hàng</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(['search' => $search])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
