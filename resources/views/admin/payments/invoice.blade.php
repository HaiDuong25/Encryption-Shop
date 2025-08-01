{{-- filepath: resources/views/admin/payments/invoice.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Hóa đơn thanh toán')

@push('styles')
<style>
    .invoice-container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
    }

    .company-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px 10px 0 0;
    }

    .status-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .table th {
        background: linear-gradient(45deg, #007bff, #0056b3) !important;
        color: white !important;
        border: none !important;
    }

    .table td {
        vertical-align: middle !important;
        border-color: #dee2e6 !important;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }

    /* Print styles */
    @media print {
        body {
            background: white !important;
            font-size: 12pt;
            line-height: 1.4;
        }

        .no-print {
            display: none !important;
        }

        .invoice-container {
            box-shadow: none !important;
            max-width: none !important;
            margin: 0 !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            break-inside: avoid;
        }

        .company-header {
            background: #007bff !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .table th {
            background: #007bff !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            color: white !important;
        }

        .badge {
            border: 1px solid #ccc !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .btn {
            display: none !important;
        }

        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
        }

        .table {
            page-break-inside: avoid;
        }

        @page {
            margin: 1.5cm;
            size: A4;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .company-header h1 {
            font-size: 1.5rem !important;
        }

        .table-responsive {
            font-size: 0.9rem;
        }

        .btn-group .btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
    <div class="container-fluid bg-white min-vh-100 py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <!-- Header với logo và thông tin công ty -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="text-primary fw-bold mb-2">🔐 ENCRYPTION SHOP</h2>
                                <p class="text-muted mb-1">Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                                <p class="text-muted mb-1">Điện thoại: (028) 1234 5678 | Email: info@encryptionshop.com</p>
                                <p class="text-muted mb-0">MST: 0123456789</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h1 class="text-danger fw-bold mb-2">HÓA ĐƠN BÁN HÀNG</h1>
                                <p class="text-muted mb-1">Ngày: {{ now()->format('d/m/Y') }}</p>
                                <div class="d-inline-block">
                                    <form action="{{ route('admin.payments.export-pdf', $payment->id) }}" method="GET" class="d-inline">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa-solid fa-file-pdf me-1"></i> Xuất PDF
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thông tin hóa đơn và khách hàng -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border p-3 rounded bg-light">
                                    <h5 class="text-primary fw-bold mb-3">📋 THÔNG TIN HÓA ĐƠN</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Số hóa đơn:</td>
                                            <td class="text-primary fw-bold">#HD{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Mã đơn hàng:</td>
                                            <td class="text-dark fw-bold">#DH{{ str_pad($payment->order->id ?? 0, 6, '0', STR_PAD_LEFT) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ngày đặt hàng:</td>
                                            <td>{{ $payment->order->created_at ? \Carbon\Carbon::parse($payment->order->created_at)->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ngày thanh toán:</td>
                                            <td>{{ $payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : 'Chưa thanh toán' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Trạng thái:</td>
                                            <td>
                                                @if ($payment->status === 'completed')
                                                    <span class="badge bg-success px-3 py-2">✅ Đã thanh toán</span>
                                                @elseif($payment->status === 'rejected')
                                                    <span class="badge bg-danger px-3 py-2">❌ Đã hủy</span>
                                                @else
                                                    <span class="badge bg-warning px-3 py-2">⏳ Chờ xác nhận</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border p-3 rounded bg-light">
                                    <h5 class="text-success fw-bold mb-3">👤 THÔNG TIN KHÁCH HÀNG</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold" style="width: 30%;">Họ tên:</td>
                                            <td class="text-primary fw-bold">{{ $payment->order->recipient_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Điện thoại:</td>
                                            <td>{{ $payment->order->recipient_phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Địa chỉ:</td>
                                            <td>{{ $payment->order->recipient_address ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Thanh toán:</td>
                                            <td class="text-info fw-bold">{{ $payment->paymentMethod->payment_type ?? 'Chưa chọn' }}</td>
                                        </tr>
                                        @if($payment->transaction_code)
                                        <tr>
                                            <td class="fw-bold">Mã GD:</td>
                                            <td class="text-muted">{{ $payment->transaction_code }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chi tiết sản phẩm -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="text-dark fw-bold mb-4">🛒 CHI TIẾT SẢN PHẨM</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead style="">
                                    <tr>
                                        <th class="fw-bold text-center" style="width: 5%;">STT</th>
                                        <th class="fw-bold" style="width: 40%;">Tên sản phẩm</th>
                                        <th class="fw-bold text-center" style="width: 15%;">Phân loại</th>
                                        <th class="fw-bold text-center" style="width: 12%;">Đơn giá</th>
                                        <th class="fw-bold text-center" style="width: 8%;">SL</th>
                                        <th class="fw-bold text-center" style="width: 20%;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $orderDetails = $payment->order->orderDetails ?? collect(); @endphp
                                    @foreach($orderDetails as $index => $detail)
                                        <tr style="border-bottom: 1px solid #dee2e6;">
                                            <td class="text-center align-middle fw-bold">{{ $index + 1 }}</td>
                                            <td class="align-middle">
                                                <div class="fw-bold text-dark mb-1">{{ $detail->product->name ?? '-' }}</div>
                                                @if($detail->product->category)
                                                    <small class="text-muted">
                                                        <i class="fa-solid fa-tag me-1"></i>{{ $detail->product->category->name }}
                                                    </small>
                                                @endif
                                            </td>
                                                                  <td class="text-center align-middle">
                                @if($detail->variant && $detail->variant->attributeValues && $detail->variant->attributeValues->count() > 0)
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        @php
                                            $hasColor = false;
                                            $hasSize = false;
                                        @endphp
                                        @foreach($detail->variant->attributeValues as $attrValue)
                                            @if($attrValue->attribute->name === 'Màu')
                                                @php $hasColor = true; @endphp
                                                <span class="badge" style="background: #e3f2fd; color: #1976d2; border: 1px solid #1976d2;">
                                                    🎨 {{ $attrValue->value }}
                                                </span>
                                            @elseif($attrValue->attribute->name === 'Size')
                                                @php $hasSize = true; @endphp
                                                <span class="badge" style="background: #fff3e0; color: #f57c00; border: 1px solid #f57c00;">
                                                    📏 {{ $attrValue->value }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Mặc định</span>
                                @endif
                            </td>
                                            <td class="text-center align-middle fw-bold">{{ format_vnd($detail->price) }} đ</td>                            <td class="text-center align-middle">
                                <span class="badge text-dark fs-6 px-3 py-2" >{{ $detail->quantity }}</span>
                            </td>
                                            <td class="text-center align-middle fw-bold text-primary fs-6">{{ format_vnd($detail->price * $detail->quantity) }} đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tóm tắt thanh toán -->
                <div class="card shadow border-0">
                    <div class="card-body p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <h5 class="text-dark fw-bold mb-4">💰 TỔNG KẾT THANH TOÁN</h5>
                        <div class="row g-4">
                            <div class="col-md-8">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td class="fw-semibold text-muted" style="width: 60%;">Tạm tính:</td>
                                            <td class="text-end fw-bold">{{ format_vnd($orderDetails->sum(fn($d) => $d->price * $d->quantity)) }} đ</td>
                                        </tr>
                                        @if($payment->order->coupon_code)
                                        <tr class="border-bottom">
                                            <td class="fw-semibold text-success">
                                                <i class="fa-solid fa-tag me-1"></i>Mã giảm giá ({{ $payment->order->coupon_code }}):
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                @if($payment->order->coupon_discount > 0)
                                                    -{{ format_vnd($payment->order->coupon_discount) }} đ
                                                @else
                                                    0 đ
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        <tr class="border-bottom">
                                            <td class="fw-semibold text-muted">Phí vận chuyển:</td>
                                            <td class="text-end fw-bold">{{ format_vnd($payment->order->shipping_fee ?? 0) }} đ</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-3 border-primary h-100" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                                    <div class="card-body text-white text-center d-flex flex-column justify-content-center p-4">
                                        <h6 class="mb-2 fw-semibold">TỔNG CỘNG</h6>
                                        <h3 class="mb-1 fw-bold">{{ format_vnd(($orderDetails->sum(fn($d) => $d->price * $d->quantity)) - ($payment->order->coupon_discount ?? 0) + ($payment->order->shipping_fee ?? 0)) }} đ</h3>
                                        <small class="opacity-75">Đã bao gồm VAT</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer và các hành động -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <div class="card-body text-center p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="text-muted">
                                            <small>
                                                <i class="fa-solid fa-calendar me-1"></i>
                                                Ngày xuất hóa đơn: {{ now()->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-0 text-primary fw-bold">
                                            ✨ CẢM ƠN QUÝ KHÁCH ĐÃ MUA HÀNG! ✨
                                        </h6>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted">
                                            <small>
                                                <i class="fa-solid fa-phone me-1"></i>
                                                Hotline: 1900-1000
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút điều hướng -->
                <div class="mt-4 text-center no-print">
                    <div class="btn-group" role="group">
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
