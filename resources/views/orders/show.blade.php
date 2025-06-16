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

    <div class="order-section-title" style="margin-top:28px;">
        <i class="fas fa-box"></i> Sản phẩm trong đơn hàng
    </div>
    <div class="table-responsive">
        <table class="table table-bordered order-table">
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
                            @if ($detail->image)
                                <img src="{{ asset('storage/' . $detail->image) }}" width="60" style="border-radius:8px;">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $detail->product->name ?? 'Sản phẩm đã xóa' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-primary mt-2">
        Theo dõi đơn hàng
    </a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary order-back-btn">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
@endsection
