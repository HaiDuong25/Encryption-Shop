@extends('admin.layouts.main')
@section('content')


<div class="row g-3"> <!-- Thêm khoảng cách giữa các phần với g-3 -->
    <!-- Tổng quan thống kê -->
    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-1-bg b-r-4 card-body">
                <div class="media align-items-center static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Total Revenue</span>
                        <h4 class="mb-0 counter">{{ number_format($totalRevenue) }} đ</h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-database-2-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-2-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Total Orders</span>
                        <h4 class="mb-0 counter">{{ $totalOrders }}</h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-3-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Total Products</span>
                        <h4 class="mb-0 counter">{{ $totalProducts }}
                            <a href="{{ route('products.create') }}" class="badge badge-light-secondary grow">ADD NEW</a>
                        </h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-chat-3-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-4-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Total Customers</span>
                        <h4 class="mb-0 counter">{{ $totalCustomers }}</h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-user-add-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Report -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0 pb-1">
                <div class="card-header-title">
                    <h4>Revenue Report</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="report-chart"></div>
            </div>
        </div>
    </div>

    <!-- Best Selling Product -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header card-header-top card-header--2 px-0 pt-0">
                <div class="card-header-title">
                    <h4>Best Selling Product</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="best-selling-table table border-0">
                        <tbody>
                            @foreach($bestSellingProducts as $product)
                            <tr>
                                <td>
                                    <div class="best-product-box">
                                        <div class="product-image">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                                        </div>
                                        <div class="product-name">
                                            <h5>{{ $product->name }}</h5>
                                            <h6>{{ $product->created_at->format('d-m-Y') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Price</h6>
                                        <h5>{{ number_format($product->sale_price ?? $product->price) }} đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Orders</h6>
                                        <h5>{{ $product->total_orders }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Stock</h6>
                                        <h5>{{ $product->stock ?? 'N/A' }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Amount</h6>
                                        <h5>{{ number_format(($product->sale_price ?? $product->price) * $product->total_orders) }} đ</h5>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header card-header-top card-header--2 px-0 pt-0">
                <div class="card-header-title">
                    <h4>Recent Orders</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="best-selling-table table border-0">
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <div class="best-product-box">
                                        <div class="product-name">
                                            <h5>{{ $order->user->name ?? 'Guest' }}</h5>
                                            <h6>#{{ $order->id }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Date Placed</h6>
                                        <h5>{{ $order->created_at->format('d/m/Y') }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Price</h6>
                                        <h5>{{ number_format($order->total_price) }} đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Order Status</h6>
                                        <h5>{{ $order->status_label }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Payment</h6>
                                        @php
                                        $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                                        @endphp
                                        @if ($isPaid)
                                        <span class="badge bg-success status-badge">Đã thanh toán</span>
                                        @else
                                        <span class="badge bg-warning text-dark status-badge">Chưa thanh toán</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No recent orders.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0">
                <div class="card-header-title">
                    <h4>Transactions</h4>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="user-table transactions-table table border-0">
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="transactions-icon">
                                        @if($transaction->paymentMethod->payment_type == 'momo')
                                        <i class="ri-bank-card-line"></i>
                                        @else
                                        <i class="ri-money-dollar-circle-line"></i>
                                        @endif
                                    </div>
                                    <div class="transactions-name">
                                        <h6>{{ strtoupper($transaction->paymentMethod->payment_type) }}</h6>
                                        <p>{{ $transaction->paymentMethod->description }}</p>
                                    </div>
                                </td>

                                <td class="success">+ {{ number_format($transaction->total_amount) }} đ</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cấu hình biểu đồ
        var options = {
            chart: {
                type: 'line',
                height: 400,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 2,
                    blur: 4,
                    opacity: 0.2,
                },
            },
            series: [{
                name: 'Doanh thu',
                data: @json($revenues ?? [0]), // Fallback nếu $revenues rỗng
            }],
            xaxis: {
                categories: @json($months ?? ['']), // Fallback nếu $months rỗng
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 400,
                    },
                },
                title: {
                    text: 'Tháng',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                    },
                },
            },
            yaxis: {
                title: {
                    text: 'Doanh thu (₫)',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                    },
                },
                labels: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(value);
                    },
                },
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            colors: ['#4e73df'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100],
                },
            },
            markers: {
                size: 5,
                hover: {
                    size: 8,
                },
            },
            grid: {
                borderColor: '#e7e7e7',
                strokeDashArray: 4,
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(value);
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
        };

        try {
            var chart = new ApexCharts(document.querySelector("#report-chart"), options);
            chart.render();
        } catch (error) {
            console.error('Error rendering ApexCharts:', error);
        }
    });
</script>
@endpush
