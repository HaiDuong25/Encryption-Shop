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

        .status-0 {
            background-color: #f59e0b;
        }

        .status-1 {
            background-color: #3b82f6;
        }

        .status-2 {
            background-color: #10b981;
        }

        .status-3 {
            background-color: #ef4444;
        }

        .summary-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .summary-card p {
            margin-bottom: 10px;
        }

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
                    style="color:#e11d48">{{ number_format($order->orderDetails->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }}
                    đ
                </strong></span>
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
                                        @php
                                            $product = null;
                                            $productImages = collect();
                                            
                                            // Ưu tiên lấy từ variant trước
                                            if ($detail->variant && $detail->variant->product) {
                                                $product = $detail->variant->product;
                                                $productImages = $product->productImages ?? collect();
                                            }
                                            // Nếu không có variant, lấy trực tiếp từ product (và product_id > 0)
                                            elseif ($detail->product_id > 0 && $detail->product) {
                                                $product = $detail->product;
                                                $productImages = $product->productImages ?? collect();
                                            }
                                        @endphp
                                        
                                        @if($product && $productImages->isNotEmpty())
                                            <img src="{{ asset('storage/' . $productImages->first()->image_path) }}"
                                                width="60" height="60" style="border-radius:8px; object-fit:cover;">
                                        @elseif($product && $product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                width="60" height="60" style="border-radius:8px; object-fit:cover;">
                                        @elseif($product)
                                            <div class="no-image" style="width:60px; height:60px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @else
                                            <div class="no-image" style="width:60px; height:60px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product)
                                            <div><strong>{{ $product->name }}</strong></div>
                                            @if($detail->variant && $detail->variant->attribute_values)
                                                <small class="text-muted">Phân loại: {{ $detail->variant->attribute_values }}</small>
                                            @endif
                                        @else
                                            <div class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Sản phẩm không còn tồn tại
                                            </div>
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
                    <li>
                        <i class="fas fa-money-bill-wave text-success"></i>
                        Tổng tiền thanh toán:
                        <span class="text-success fw-bold">
                            {{ number_format($order->orderDetails->sum(fn($d) => $d->price * $d->quantity), 0, ',', '.') }}
                            đ
                        </span>
                    </li>
                    @php
                        $validPayments = $order->payments->whereNotNull('created_at');
                    @endphp
                    @forelse ($validPayments as $payment)
                        <li class="mb-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $payment->note ?? '' }})</small>
                        </li>
                    @empty
                        <li><span class="text-danger">Chưa có thanh toán</span></li>
                    @endforelse
                </ul>
            </div>

            <!-- <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-primary mb-3">
                        <i class="fas fa-truck"></i> Theo dõi đơn hàng
                    </a> -->

            <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-title">Tóm tắt đơn hàng</div>
                <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
                <p><strong>Ngày dặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p><strong>Trạng thái:</strong>
                    @php
                        // Convert numeric status to string for compatibility
                        $statusValue = $order->status;
                        if (is_numeric($statusValue)) {
                            $statusMap = [
                                '0' => 'pending',
                                '1' => 'confirmed', 
                                '2' => 'shipping',
                                '3' => 'delivering',
                                '4' => 'received',
                                '5' => 'completed'
                            ];
                            $statusValue = $statusMap[$statusValue] ?? 'pending';
                        }
                    @endphp
                    
                    @if($statusValue == 'pending')
                        <span class="badge bg-warning">Chờ xử lý</span>
                    @elseif($statusValue == 'confirmed')
                        <span class="badge bg-primary">Đã xác nhận</span>
                    @elseif($statusValue == 'shipping')
                        <span class="badge bg-info">Giao cho ĐVVC</span>
                    @elseif($statusValue == 'delivering')
                        <span class="badge bg-purple">Đang giao</span>
                    @elseif($statusValue == 'received')
                        <span class="badge bg-cyan">Đã nhận</span>
                    @elseif($statusValue == 'completed')
                        <span class="badge bg-success">Hoàn thành</span>
                    @elseif($statusValue == 'cancelled')
                        <span class="badge bg-danger">Đã hủy</span>
                    @else
                        <span class="badge bg-secondary">{{ $statusValue }}</span>
                    @endif
                </p>
                <hr>
                <div class="summary-title">Thông tin khách hàng</div>
                <p><strong>Khách hàng:</strong><br>{{ $order->name }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong><br>{{ $order->address }}</p>
                <hr>
                <p><strong>Phương thức thanh toán:</strong><br>{{ $order->paymentMethod->payment_type ?? 'N/A' }}</p>
                <p><strong>Mã giảm giá:</strong> {{ $order->coupon->code ?? 'Không áp dụng' }}</p>

                <!-- <p><strong>Ngày giao dự kiến:</strong> {{ $order->created_at->addDays(2)->format('d/m/Y') }}</p> -->
            </div>

            <div class="summary-card mt-3">
                <div class="summary-title">Chi tiết giao hàng</div>
                <p><strong>Người nhận:</strong> {{ $order->shipping_name ?? $order->name }}</p>
                <p><strong>Địa chỉ:</strong><br>
                    {{ $order->shipping_address ?? $order->address }}
                </p>
                <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone ?? $order->phone }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p><strong>Phương thức vận chuyển:</strong> {{ $order->shipping_method ?? 'Miễn phí vận chuyển' }}</p>
            </div>
        </div>
    </div>
@endsection
