@extends('client.layout.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Hoàn tất thanh toán ZaloPay
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            {{ session('info') }}
                        </div>
                    @endif

                    @if(session('zalopay_url'))
                        <div class="alert alert-success border-primary">
                            <h5><i class="fa-solid fa-external-link-alt me-2"></i>Bước 1: Thanh toán ZaloPay</h5>
                            <p class="mb-3">Nhấn vào nút bên dưới để mở trang thanh toán ZaloPay (mở tab mới):</p>
                            <a href="{{ session('zalopay_url') }}" target="_blank" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-wallet me-2"></i>Mở trang thanh toán ZaloPay
                            </a>
                        </div>

                        <div class="alert alert-warning border-warning">
                            <h5><i class="fa-solid fa-step-forward me-2"></i>Bước 2: Hoàn tất đơn hàng</h5>
                            <p class="mb-2">Sau khi thanh toán thành công trên ZaloPay, bạn sẽ thấy URL có dạng:</p>
                            <code class="d-block p-2 bg-light rounded mb-3">
                                https://docs.zalopay.vn/result?status=1&apptransid=...
                            </code>
                            <p class="mb-0"><strong>Copy toàn bộ URL này và dán vào form bên dưới để hoàn tất đơn hàng.</strong></p>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="fa-solid fa-info-circle me-2"></i>Thông báo về môi trường test</h5>
                        <p class="mb-2">
                            Do ZaloPay đang trong môi trường test, sau khi thanh toán thành công, bạn sẽ được chuyển hướng đến trang tài liệu ZaloPay thay vì về website.
                        </p>
                        <p class="mb-0">
                            <strong>Vui lòng copy URL từ thanh địa chỉ của trình duyệt và dán vào form bên dưới để hoàn tất đơn hàng.</strong>
                        </p>
                    </div>

                    <form action="{{ route('zalopay.process-manual-return') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="zalopay_url" class="form-label fw-bold">
                                <i class="fa-solid fa-link me-2"></i>URL từ ZaloPay:
                            </label>
                            <textarea 
                                class="form-control" 
                                id="zalopay_url" 
                                name="zalopay_url" 
                                rows="4" 
                                placeholder="Dán URL hoàn chỉnh từ ZaloPay vào đây...&#10;Ví dụ: https://docs.zalopay.vn/v2/?amount=476130&appid=2553&apptransid=250726_1753536251&bankcode=CC&checksum=85909f1274235ab64121a5a33a86606f7d8b6f05c9985636021e2dc2f95beb29&discountamount=0&pmcid=36&status=1"
                                required
                            ></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa-solid fa-check me-2"></i>Xác nhận thanh toán
                            </button>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-2"></i>Quay lại thanh toán
                            </a>
                        </div>
                    </form>

                    <div class="mt-4">
                        <h6><i class="fa-solid fa-question-circle me-2"></i>Hướng dẫn:</h6>
                        <ol class="small text-muted">
                            <li>Sau khi thanh toán thành công trên ZaloPay, bạn sẽ thấy URL có dạng: <code>https://docs.zalopay.vn/v2/?amount=...&status=1</code></li>
                            <li>Copy toàn bộ URL này từ thanh địa chỉ trình duyệt</li>
                            <li>Dán vào ô bên trên và nhấn "Xác nhận thanh toán"</li>
                            <li>Hệ thống sẽ tự động xử lý và tạo đơn hàng cho bạn</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    background: linear-gradient(135deg, #f39c12, #e67e22) !important;
}

.btn-success {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-success:hover {
    background: linear-gradient(135deg, #229954, #27ae60);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
}

.alert {
    border-radius: 8px;
    border: none;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #f39c12;
    box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
}

code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.9em;
}
</style>
@endsection
