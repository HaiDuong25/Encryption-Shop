<?php $__env->startSection('content'); ?>

<div class="row g-3">
    <!-- Tổng quan thống kê -->
    <div class="col-sm-6 col-lg-3">
        <div class="main-tiles border-0 card-hover card o-hidden">
            <div class="custome-1-bg b-r-4 card-body">
                <div class="media align-items-center static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">Tổng doanh thu</span>
                        <h4 class="mb-0 counter"><?php echo e(format_vnd($totalRevenue)); ?> đ</h4>
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
                        <h4 class="mb-0 counter"><?php echo e($totalOrders); ?></h4>
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
                        <h4 class="mb-0 counter"><?php echo e($totalProducts); ?>

                            <a href="<?php echo e(route('products.create')); ?>" class="badge badge-light-secondary grow">THÊM MỚI</a>
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
                        <h4 class="mb-0 counter"><?php echo e($totalCustomers); ?></h4>
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
                            <?php $__currentLoopData = $bestSellingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="best-product-box">
                                        <div class="product-image">
                                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" class="img-fluid" alt="<?php echo e($product->name); ?>">
                                        </div>
                                        <div class="product-name">
                                            <h5><?php echo e($product->name); ?></h5>
                                            <h6><?php echo e($product->created_at->format('d-m-Y')); ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Giá</h6>
                                        <h5><?php echo e(format_vnd($product->sale_price ?? $product->price)); ?> đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Đơn hàng</h6>
                                        <h5><?php echo e($product->total_orders); ?></h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Doanh thu</h6>
                                        <h5><?php echo e(format_vnd(($product->sale_price ?? $product->price) * $product->total_orders)); ?> đ</h5>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <table class="best-selling-table table border-0">
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="best-product-box">
                                        <div class="product-name">
                                            <h5><?php echo e($order->user->name ?? 'Khách'); ?></h5>
                                            <h6>#<?php echo e($order->id); ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Ngày đặt</h6>
                                        <h5><?php echo e($order->created_at->format('d/m/Y')); ?></h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Giá trị</h6>
                                        <h5><?php echo e(format_vnd($order->total_price)); ?> đ</h5>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Trạng thái</h6>
                                        <?php
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
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColor); ?>"><?php echo e($statusLabel); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-detail-box">
                                        <h6>Thanh toán</h6>
                                        <?php
                                        $isPaid = $order->payments && $order->payments->where('status', 'completed')->count() > 0;
                                        ?>
                                        <?php if($isPaid): ?>
                                        <span class="badge bg-success status-badge">Đã thanh toán</span>
                                        <?php else: ?>
                                        <span class="badge bg-warning text-dark status-badge">Chưa thanh toán</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">Chưa có đơn hàng.</td>
                            </tr>
                            <?php endif; ?>
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
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="transactions-icon">
                                        <?php if($transaction->paymentMethod->payment_type == 'momo'): ?>
                                        <i class="ri-bank-card-line"></i>
                                        <?php else: ?>
                                        <i class="ri-money-dollar-circle-line"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="transactions-name">
                                        <h6><?php echo e(strtoupper($transaction->paymentMethod->payment_type)); ?></h6>
                                        <p><?php echo e($transaction->paymentMethod->description); ?></p>
                                    </div>
                                </td>
                                <td class="success">+ <?php echo e(format_vnd($transaction->total_amount)); ?> đ</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/apexcharts.min.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                data: <?php echo json_encode($revenues ?? [0], 15, 512) ?>,
            }],
            xaxis: {
                categories: <?php echo json_encode($months ?? [''], 15, 512) ?>,
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
            var chart = new ApexCharts(document.querySelector("#report-chart"), options);
            chart.render();
        } catch (error) {
            console.error('Lỗi khi hiển thị biểu đồ:', error);
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>