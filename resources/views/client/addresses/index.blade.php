@extends('client.layout.main')

@section('title', 'Sổ địa chỉ')

@section('content')
<style>
.addresses-wrapper {
    max-width: 1500px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem 1rem;
}
.address-card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    background: white;
    transition: all 0.3s ease;
}
.address-card:hover {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transform: translateY(-2px);
}
.address-card.default {
    border-color: #1cc88a;
    background: #f8fff9;
}
.default-badge {
    background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}
.contact-info {
    color: #5a5c69;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.address-detail {
    color: #6c757d;
    line-height: 1.5;
}
.action-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}
.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.15s ease-in-out;
}
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}
.empty-state i {
    font-size: 4rem;
    color: #e3e6f0;
    margin-bottom: 1rem;
}
</style>

<div class="addresses-wrapper">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Sổ địa chỉ</h1>
            <p class="mb-0 text-muted">Quản lý địa chỉ giao hàng của bạn</p>
        </div>
        <a href="{{ route('client.addresses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($addresses->count() > 0)
        <div class="row">
            @foreach($addresses as $address)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                        <!-- Header với badge default -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="contact-info">
                                <div class="fw-bold">{{ $address->name }}</div>
                                <div class="text-success">
                                    <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                </div>
                            </div>
                            @if($address->is_default)
                                <span class="default-badge">
                                    <i class="fas fa-star me-1"></i>Mặc định
                                </span>
                            @endif
                        </div>

                        <!-- Địa chỉ -->
                        <div class="address-detail mb-3">
                            <div class="mb-1">{{ $address->address_detail }}</div>
                            <div class="text-muted small">
                                {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                            </div>
                        </div>

                        <!-- Ghi chú nếu có -->
                        @if($address->note)
                            <div class="text-muted small mb-3">
                                <i class="fas fa-sticky-note me-1"></i>{{ $address->note }}
                            </div>
                        @endif

                        <!-- Action buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('client.addresses.show', $address) }}" 
                               class="btn btn-outline-info btn-action">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                            <a href="{{ route('client.addresses.edit', $address) }}" 
                               class="btn btn-outline-warning btn-action">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                            @if(!$address->is_default)
                                <form action="{{ route('client.addresses.set-default', $address) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success btn-action">
                                        <i class="fas fa-star me-1"></i>Đặt mặc định
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('client.addresses.destroy', $address) }}" 
                                  method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-action">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-map-marker-alt"></i>
            <h4>Chưa có địa chỉ nào</h4>
            <p class="mb-3">Thêm địa chỉ giao hàng đầu tiên để thuận tiện cho việc mua sắm</p>
            <a href="{{ route('client.addresses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm địa chỉ đầu tiên
            </a>
        </div>
    @endif
</div>

<script>
// Auto hide success message after 5 seconds
setTimeout(function() {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);
</script>
@endsection
