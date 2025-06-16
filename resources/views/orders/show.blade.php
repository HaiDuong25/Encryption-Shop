@extends('admin.layouts.main')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
        .order-header-bar {
            background: #fff;
            padding: 20px 28px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-size: 16px;
            font-weight: 500;
        }

        .order-header-bar span {
            margin-right: 16px;
            color: #334155;
        }

        .table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
        }

        .badge-status {
            padding: 4px 12px;
            border-radius: 8px;
            color: #fff;
            font-size: 0.9rem;
        }

        .status-0 { background-color: #f59e0b; }
        .status-1 { background-color: #3b82f6; }
        .status-2 { background-color: #10b981; }
        .status-3 { background-color: #ef4444; }

        .summary-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .summary-card p { margin-bottom: 10px; }
        .summary-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
    </style>

    <div class="order-header-bar">
        <div>
            <span><i class="fas fa-calendar-alt text-info"></i> {{ $order->created_at->format('d/m/Y H:i') }}</span>
            <span><i class="fas fa-box text-primary"></i> {{ $order->orderDetails->sum('quantity') }} sản phẩm</span>
        </div>
        <div>
           <span><i class="fas fa-money-bill-wave text-danger"></i> Tổng: <strong
        style="color:#e11d48">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong></span>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 p-3">
                <h5 class="mb-3"><i class="fas fa-shopping-bag me-2 text-success"></i> Sản phẩm trong đơn hàng</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        @if ($detail->variant && $detail->variant->product && $detail->variant->product->image)
                                            <img src="{{ asset('storage/' . $detail->variant->product->image) }}" width="60" style="border-radius:8px;">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($detail->variant && $detail->variant->product)
                                            {{ $detail->variant->product->name }}
                                        @else
                                            <span class="text-danger">Sản phẩm đã xóa</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4 p-3">
                <h5 class="mb-3"><i class="fas fa-receipt me-2 text-primary"></i> Lịch sử thanh toán</h5>
                <ul class="list-unstyled">
                    @php
                        $validPayments = $order->payments->whereNotNull('created_at')->where('amount', '>', 0);
                    @endphp
                    @forelse ($validPayments as $payment)
                        <li class="mb-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            {{ $payment->created_at->format('d/m/Y H:i') }} -
                            <span class="text-success fw-bold">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</span>
                            <small class="text-muted">({{ $payment->note ?? '' }})</small>
                        </li>
                    @empty
                        <li><span class="text-danger">Chưa có thanh toán</span></li>
                    @endforelse
                </ul>
            </div>

            <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-primary mb-3">
                <i class="fas fa-truck"></i> Theo dõi đơn hàng
            </a>

            <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-title">Tóm tắt đơn hàng</div>
                <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
                <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p><strong>Trạng thái:</strong>
                    @php
                        $statusArr = [
                            0 => 'Chờ xử lí',
                            1 => 'Xác nhận',
                            2 => 'Giao cho ĐVVC',
                            3 => 'Đang giao',
                            4 => 'Đã nhận',
                            5 => 'Hoàn thành',
                        ];
                        $statusClass = 'badge-status status-' . ($order->status ?? 0);
                    @endphp
                    <span class="{{ $statusClass }}">
                        <i class="fas fa-circle"></i> {{ $statusArr[$order->status] ?? 'Không xác định' }}
                    </span>
                </p>
                <hr>
                <p><strong>Khách hàng:</strong><br>{{ $order->name }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Địa chỉ:</strong><br>{{ $order->address }}</p>
                <hr>
                <p><strong>Phương thức thanh toán:</strong><br>{{ $order->paymentMethod->payment_type ?? 'N/A' }}</p>
                <p><strong>Mã giảm giá:</strong> {{ $order->coupon->code ?? 'Không áp dụng' }}</p>
                <hr>
                <p><strong>Tổng tiền:</strong><br><span style="color:#e11d48;font-weight:600">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></p>
                <p><strong>Ngày giao dự kiến:</strong> {{ $order->created_at->addDays(2)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
@endsection
