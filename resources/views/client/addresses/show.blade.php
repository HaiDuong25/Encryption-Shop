@extends('client.layout.main')

@section('title', 'Chi tiết địa chỉ')

@section('content')
<style>
.addresses-wrapper {
    max-width: 1500px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem 1rem;
}
.info-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    padding: 2rem;
}
.info-section {
    border-left: 4px solid #4e73df;
    padding-left: 1rem;
    margin-bottom: 2rem;
}
.info-section h6 {
    color: #4e73df;
    font-weight: 600;
    margin-bottom: 1rem;
}
.info-row {
    margin-bottom: 1rem;
}
.info-label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 0.25rem;
}
.info-value {
    color: #858796;
}
.badge-default {
    background-color: #28a745;
    color: white;
}
.action-buttons {
    gap: 0.5rem;
}
.copy-button {
    border: none;
    background: none;
    color: #6c757d;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out;
}
.copy-button:hover {
    color: #495057;
    background-color: #f8f9fa;
}
</style>

<div class="addresses-wrapper">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết địa chỉ</h1>
            <p class="mb-0 text-muted">
                Thông tin chi tiết về địa chỉ giao hàng
                @if($address->is_default)
                    <span class="badge badge-default ms-2">Mặc định</span>
                @endif
            </p>
        </div>
        <div class="d-flex action-buttons">
            <a href="{{ route('client.addresses.edit', $address) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i>Xóa
            </button>
            <a href="{{ route('client.addresses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin liên hệ -->
            <div class="info-card mb-4">
                <div class="info-section">
                    <h6><i class="fas fa-user me-2"></i>Thông tin liên hệ</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Họ tên</div>
                                <div class="info-value d-flex align-items-center">
                                    <span id="contact-name">{{ $address->name }}</span>
                                    <button type="button" class="copy-button ms-2" onclick="copyToClipboard('contact-name')" title="Sao chép">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Số điện thoại</div>
                                <div class="info-value d-flex align-items-center">
                                    <span id="contact-phone">{{ $address->phone }}</span>
                                    <button type="button" class="copy-button ms-2" onclick="copyToClipboard('contact-phone')" title="Sao chép">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Địa chỉ -->
            <div class="info-card mb-4">
                <div class="info-section">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Tỉnh/Thành phố</div>
                                <div class="info-value">{{ $address->province }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Quận/Huyện</div>
                                <div class="info-value">{{ $address->district }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Phường/Xã</div>
                                <div class="info-value">{{ $address->ward }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Địa chỉ chi tiết</div>
                        <div class="info-value">{{ $address->address_detail }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Địa chỉ đầy đủ</div>
                        <div class="info-value d-flex align-items-start">
                            <span id="full-address">{{ $address->address_detail }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</span>
                            <button type="button" class="copy-button ms-2" onclick="copyToClipboard('full-address')" title="Sao chép địa chỉ đầy đủ">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            @if($address->note)
            <div class="info-card mb-4">
                <div class="info-section">
                    <h6><i class="fas fa-sticky-note me-2"></i>Ghi chú</h6>
                    <div class="info-value">{{ $address->note }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Side info -->
        <div class="col-lg-4">
            <div class="info-card mb-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Thông tin khác
                </h6>
                
                <div class="info-row">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">
                        @if($address->is_default)
                            <span class="badge badge-default">Địa chỉ mặc định</span>
                        @else
                            <span class="badge bg-secondary">Địa chỉ phụ</span>
                        @endif
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ngày tạo</div>
                    <div class="info-value">{{ $address->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Cập nhật lần cuối</div>
                    <div class="info-value">{{ $address->updated_at->format('d/m/Y H:i') }}</div>
                </div>

                @if($address->created_at != $address->updated_at)
                <div class="info-row">
                    <div class="info-label">Thời gian chỉnh sửa</div>
                    <div class="info-value text-muted small">{{ $address->updated_at->diffForHumans() }}</div>
                </div>
                @endif
            </div>

            <div class="info-card">
                <h6 class="text-warning mb-3">
                    <i class="fas fa-tools me-2"></i>Thao tác
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('client.addresses.edit', $address) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa địa chỉ
                    </a>
                    
                    @if(!$address->is_default)
                    <form action="{{ route('client.addresses.update', $address) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $address->name }}">
                        <input type="hidden" name="phone" value="{{ $address->phone }}">
                        <input type="hidden" name="province" value="{{ $address->province }}">
                        <input type="hidden" name="district" value="{{ $address->district }}">
                        <input type="hidden" name="ward" value="{{ $address->ward }}">
                        <input type="hidden" name="address_detail" value="{{ $address->address_detail }}">
                        <input type="hidden" name="note" value="{{ $address->note }}">
                        <input type="hidden" name="is_default" value="1">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-star me-2"></i>Đặt làm mặc định
                        </button>
                    </form>
                    @endif
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Xóa địa chỉ
                    </button>
                    
                    <a href="{{ route('client.addresses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-2"></i>Danh sách địa chỉ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa địa chỉ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa địa chỉ này không?</p>
                <div class="alert alert-warning">
                    <strong>Địa chỉ:</strong> {{ $address->address_detail }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                </div>
                @if($address->is_default)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Lưu ý:</strong> Đây là địa chỉ mặc định của bạn. Sau khi xóa, bạn cần đặt địa chỉ khác làm mặc định.
                </div>
                @endif
                <p class="text-muted">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                <form action="{{ route('client.addresses.destroy', $address) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa địa chỉ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success notification for copy -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-check-circle text-success me-2"></i>
            <strong class="me-auto">Thành công</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Đã sao chép vào clipboard!
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success toast
        const toast = new bootstrap.Toast(document.getElementById('copyToast'));
        toast.show();
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            const toast = new bootstrap.Toast(document.getElementById('copyToast'));
            toast.show();
        } catch (err) {
            console.error('Fallback: Could not copy text: ', err);
        }
        document.body.removeChild(textArea);
    });
}
</script>
@endsection
