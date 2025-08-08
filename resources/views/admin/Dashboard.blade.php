@extends('admin.layouts.main')
@section('content')

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <label class="me-3 mb-0">Lọc theo:</label>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary filter-btn" data-type="day">Ngày</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-type="week">Tuần</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-type="month">Tháng</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-type="year">Năm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Tổng quan thống kê -->
    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-1-bg b-r-4 card-body">
                <div class="media align-items-center static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Tổng doanh thu</span>
                        <h4 class="mb-0 counter">{{ format_vnd($totalRevenue) }} đ</h4>
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
                        <span class="m-0">Tổng đơn hàng</span>
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
                        <span class="m-0">Tổng sản phẩm</span>
                        <h4 class="mb-0 counter">{{ $totalProducts }}
                            <a href="{{ route('products.create') }}" class="badge badge-light-secondary grow">THÊM MỚI</a>
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
                        <span class="m-0">Tổng khách hàng</span>
                        <h4 class="mb-0 counter">{{ $totalCustomers }}</h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-user-add-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Báo cáo doanh thu -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0 pb-1">
                <div class="card-header-title">
                    <h4>Báo cáo doanh thu</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="report-chart"></div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm bán chạy -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header card-header-top card-header--2 px-0 pt-0">
                <div class="card-header-title">
                    <h4>Sản phẩm bán chạy</h4>
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
                                        <h6>Giá</h6>
                                        <h5>{{ format_vnd($product->sale_price ?? $product->price) }} đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Đơn hàng</h6>
                                        <h5>{{ $product->total_orders }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Doanh thu</h6>
                                        <h5>{{ format_vnd(($product->sale_price ?? $product->price) * $product->total_orders) }} đ</h5>
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

    <!-- Đơn hàng gần đây -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header card-header-top card-header--2 px-0 pt-0">
                <div class="card-header-title">
                    <h4>Đơn hàng gần đây</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="best-selling-table table border-0 recent-orders-table">
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <div class="best-product-box">
                                        <div class="product-name">
                                            <h5>{{ $order->user->name ?? 'Khách' }}</h5>
                                            <h6>#{{ $order->id }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Ngày đặt</h6>
                                        <h5>{{ $order->created_at->format('d/m/Y') }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Giá trị</h6>
                                        <h5>{{ format_vnd($order->total_price) }} đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Trạng thái</h6>
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Chờ xử lý',
                                                'approved' => 'Đã duyệt',
                                                'confirmed' => 'Đã xác nhận', 
                                                'shipping' => 'Giao cho ĐVVC',
                                                'delivering' => 'Đang giao',
                                                'received' => 'Đã nhận',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy',
                                                'canceled' => 'Đã hủy'
                                            ];
                                            $statusLabel = $statusLabels[$order->status] ?? 'Không xác định';
                                            $statusColor = match($order->status) {
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'confirmed' => 'info',
                                                'shipping' => 'primary', 
                                                'delivering' => 'primary',
                                                'received' => 'success',
                                                'completed' => 'success',
                                                'cancelled', 'canceled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Thanh toán</h6>
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
                                <td colspan="5" class="text-center">Chưa có đơn hàng.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Giao dịch -->
    <div class="col-xl-6">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0">
                <div class="card-header-title">
                    <h4>Giao dịch</h4>
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
                                <td class="success">+ {{ format_vnd($transaction->total_amount) }} đ</td>
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
    let chart;
    
    document.addEventListener('DOMContentLoaded', function () {
        initializeChart(@json($revenues ?? [0]), @json($months ?? ['']));
        
        // Filter button click handler
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get filter type
                const filterType = this.dataset.type;
                
                // Show loading state
                document.body.style.cursor = 'wait';
                const buttons = document.querySelectorAll('.filter-btn');
                buttons.forEach(b => b.disabled = true);
                
                // Make AJAX request
                fetch(`/admin/dashboard/filter`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ type: filterType })
                })
                .then(response => response.json())
                .then(data => {
                    // Update statistics
                    document.querySelectorAll('.counter').forEach((counter, index) => {
                        switch(index) {
                            case 0:
                                counter.textContent = data.totalRevenue;
                                break;
                            case 1:
                                counter.textContent = data.totalOrders;
                                break;
                            case 2:
                                counter.textContent = data.totalProducts;
                                break;
                            case 3:
                                counter.textContent = data.totalCustomers;
                                break;
                        }
                    });
                    
                    // Update chart with animation
                    chart.updateOptions({
                        xaxis: {
                            categories: data.labels
                        }
                    }, false, true);
                    
                    // Clear existing series and add new one
                    chart.updateSeries([{
                        name: 'Doanh thu',
                        data: data.revenues.map(value => parseInt(value))
                    }], true);
                    
                    // Update tables
                    const tables = {
                        bestSelling: document.querySelector('.best-selling-table:not(.recent-orders-table) tbody'),
                        recentOrders: document.querySelector('.recent-orders-table tbody'),
                        transactions: document.querySelector('.transactions-table tbody')
                    };

                    if (tables.bestSelling) tables.bestSelling.innerHTML = data.bestSellingHtml;
                    if (tables.recentOrders) tables.recentOrders.innerHTML = data.recentOrdersHtml;
                    if (tables.transactions) tables.transactions.innerHTML = data.transactionsHtml;
                })
                .catch(error => {
                    console.error('Lỗi khi lọc dữ liệu:', error);
                })
                .finally(() => {
                    // Reset loading state
                    document.body.style.cursor = 'default';
                    buttons.forEach(b => b.disabled = false);
                });
            });
        });
    });
    
    function initializeChart(initialData, initialLabels) {
        const options = {
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
                data: initialData,
            }],
            xaxis: {
                categories: initialLabels,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 400,
                    },
                },
                title: {
                    text: 'Thời gian',
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
                    formatter: function (value) {
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
                    formatter: function (value) {
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
            chart = new ApexCharts(document.querySelector("#report-chart"), options);
            chart.render();
        } catch (error) {
            console.error('Lỗi khi hiển thị biểu đồ:', error);
        }
    }
</script>
@endpush

@push('styles')
<style>
    .filter-btn {
        min-width: 80px;
    }
    .filter-btn.active {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
        color: white !important;
    }
    .badge {
        font-size: 11px;
        padding: 6px 10px;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .status-badge {
        white-space: nowrap;
    }
    
    .product-detail-box h6 {
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 4px;
        font-weight: 500;
    }
    
    .product-detail-box h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endpush
