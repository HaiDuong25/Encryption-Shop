@extends('admin.layouts.main')

@section('title', 'Địa chỉ giao hàng của ' . $user->name)

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
.user-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>

<!-- Thông tin user -->
<div class="card user-info-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><i class="ri-user-line"></i> {{ $user->name }}</h5>
                <p class="mb-0"><i class="ri-mail-line"></i> {{ $user->email }}</p>
                @if($user->phone)
                    <p class="mb-0"><i class="ri-phone-line"></i> {{ $user->phone }}</p>
                @endif
            </div>
            <div class="text-end">
                <div class="badge bg-light text-dark fs-6">{{ $addresses->count() }} địa chỉ</div>
                <div class="mt-2">
                    <a href="{{ route('shipping-addresses.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line"></i> Quay lại
                    </a>
                    <a href="{{ route('shipping-addresses.create', ['user_id' => $user->id]) }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line"></i> Thêm địa chỉ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách địa chỉ -->
<div class="card card-table">
    <div class="card-body">
        <div class="title-header option-title">
            <h5>Danh sách địa chỉ giao hàng</h5>
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
                        <th>Thông tin liên hệ</th>
                        <th>Địa chỉ</th>
                        <th>Mặc định</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($addresses as $address)
                    <tr>
                        <td>{{ $address->id }}</td>
                        <td>
                            <div class="address-info">
                                <div class="name">{{ $address->name }}</div>
                                <div class="phone">{{ $address->phone }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="address-info">
                                <div class="address">{{ $address->address_detail }}</div>
                                <small class="text-muted">{{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</small>
                            </div>
                        </td>
                        <td>
                            @if($address->is_default)
                                <span class="badge bg-primary status-badge">Mặc định</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($address->note, 30) }}</td>
                        <td>
                            <ul class="action-buttons">
                                <li>
                                    <a href="{{ route('shipping-addresses.show', $address) }}" 
                                       title="Xem chi tiết" style="color: #6c757d;">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('shipping-addresses.edit', $address) }}" 
                                       title="Chỉnh sửa" style="color: #ffc107;">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </li>
                                @if(!$address->is_default)
                                <li>
                                    <form action="{{ route('shipping-addresses.set-default', $address) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Đặt mặc định" style="color: #007bff;">
                                            <i class="ri-star-line"></i>
                                        </button>
                                    </form>
                                </li>
                                @endif
                                <li>
                                    <form action="{{ route('shipping-addresses.destroy', $address) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Xóa" style="color: #dc3545;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="ri-inbox-line" style="font-size: 2rem;"></i>
                                <p class="mt-2">{{ $user->name }} chưa có địa chỉ giao hàng nào</p>
                                <a href="{{ route('shipping-addresses.create', ['user_id' => $user->id]) }}" class="btn btn-primary">Thêm địa chỉ đầu tiên</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
