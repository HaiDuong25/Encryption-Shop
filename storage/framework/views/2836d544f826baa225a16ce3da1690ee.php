<?php $__env->startSection('title', 'Quản lý Thanh Toán'); ?>

<?php $__env->startSection('content'); ?>
    <?php use Carbon\Carbon; ?>

<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                <h5>Quản lý Thanh Toán</h5>
                <div class="right-options d-flex gap-2 align-items-center">
                    
                    <form method="GET" action="<?php echo e(route('payments.index')); ?>" class="d-flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control me-2" 
                               placeholder="Tìm theo tên người nhận hoặc ID đơn hàng..." style="width: 280px;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive table-product mt-3">
                <table class="table theme-table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Đơn hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Dữ liệu giao dịch</th>
                            <th>Trạng thái</th>
                            <th>Ngày thanh toán</th>
                            <th>Hành động</th>
                            <th>Xem hóa đơn</th>
                        </tr>
                    </thead>
            <tbody>
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($payment->id); ?></td>
                        <td>
                            <span class="badge text-dark" style="font-size: 1rem;">Đơn hàng <?php echo e($payment->order->id ?? 'N/A'); ?></span><br>
                            <small class="text-muted"><?php echo e($payment->order->recipient_name ?? ''); ?></small>
                        </td>
                        <td class="text-end"><?php echo e(format_vnd($payment->order->total_price ?? 0)); ?> <span class="text-secondary">VND</span></td>
                        <td>
                            <span class="badge bg-light text-dark border border-1 border-secondary"><?php echo e($payment->paymentMethod->payment_type ?? 'Chưa chọn'); ?></span>
                        </td>
                        <td>
                            <?php if($payment->payment_method_type && in_array($payment->payment_method_type, ['MoMo', 'ZaloPay'])): ?>
                                <div class="text-center">
                                    <span class="badge bg-info text-white mb-1"><?php echo e($payment->payment_method_type); ?></span><br>
                                    <?php if($payment->transaction_code): ?>
                                        <small class="text-dark fw-bold">Mã GD: <?php echo e($payment->transaction_code); ?></small><br>
                                    <?php endif; ?>
                                    <?php if($payment->order && $payment->order->transaction_id): ?>
                                        <small class="text-muted">Mã ĐH: <?php echo e($payment->order->transaction_id); ?></small><br>
                                    <?php endif; ?>
                                    <?php if($payment->confirmed_at): ?>
                                        <small class="text-success"><?php echo e(\Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i')); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD'): ?>
                                <div class="text-center">
                                    <span class="badge bg-success text-white mb-1">COD</span><br>
                                    <small class="text-muted">Thanh toán khi nhận hàng</small>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $statusText = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'completed' => 'Đã thanh toán',
    'rejected' => 'Đã hủy',
    'refunded' => 'Đã hoàn tiền'
];

$statusColor = [
    'pending' => 'warning',
    'confirmed' => 'info',
    'completed' => 'success',
    'rejected' => 'danger',
    'refunded' => 'info'
];
                            ?>
                            <span class="badge bg-<?php echo e($statusColor[$payment->status] ?? 'secondary'); ?>" style="font-size: 1rem;">
                                <?php echo e($statusText[$payment->status] ?? ucfirst($payment->status)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($payment->confirmed_at): ?>
                                <span class="text-success"><i class="fa-solid fa-check-circle me-1"></i><?php echo e(\Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i')); ?></span>
                            <?php elseif($payment->rejected_at): ?>
                                <span class="text-danger"><i class="fa-solid fa-times-circle me-1"></i><?php echo e(\Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i')); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($payment->paymentMethod && $payment->paymentMethod->payment_type == 'COD'): ?>
                                
                                <?php if($payment->status === 'pending'): ?>
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="<?php echo e(route('payments.confirm', $payment->id)); ?>" method="POST"
                                            onsubmit="return confirm('Xác nhận đơn hàng COD này?');" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-info btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-check me-1"></i> Xác nhận đơn
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('payments.reject', $payment->id)); ?>" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                    </div>
                                <?php elseif($payment->status === 'confirmed'): ?>
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="<?php echo e(route('payments.complete', $payment->id)); ?>" method="POST"
                                            onsubmit="return confirm('Hoàn thành đơn hàng COD này? (Khách đã thanh toán)');" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-xs px-2 py-1"
                                                style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-check-double me-1"></i> Hoàn thành
                                            </button>
                                        </form>
                                        <span class="badge bg-info text-white" style="font-size: 0.85rem;">
                                            Đã xác nhận lúc
                                            <?php echo e($payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m H:i') : ''); ?>

                                        </span>
                                    </div>
                                <?php elseif($payment->status === 'completed'): ?>
                                    <span class="badge bg-success text-white">
                                        Đã hoàn thành lúc
                                        <?php echo e($payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : ''); ?>

                                    </span>
                                <?php elseif($payment->status === 'rejected'): ?>
                                    <span class="badge bg-danger text-white">
                                        Đã hủy lúc
                                        <?php echo e($payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : ''); ?>

                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                
                                <?php if($payment->status === 'pending'): ?>
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <form action="<?php echo e(route('payments.confirm', $payment->id)); ?>" method="POST"
                                            onsubmit="return confirm('Xác nhận thanh toán cho đơn này?');" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-xs px-2 py-1"
                                                style="font-size: 0.85rem; background-color: #28a745; border-color: #28a745;">
                                                <i class="fa-solid fa-check me-1"></i> Xác nhận
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('payments.reject', $payment->id)); ?>" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-xs px-2 py-1"
                                                style="font-size: 0.85rem; background-color: #dc3545; border-color: #dc3545;">
                                                <i class="fa-solid fa-times me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                    </div>
                                <?php elseif($payment->status === 'completed'): ?>
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <span class="badge bg-success text-white" style="background-color: #28a745;">
                                            Đã xác nhận lúc
                                            <?php echo e($payment->confirmed_at ? \Carbon\Carbon::parse($payment->confirmed_at)->format('d/m/Y H:i') : ''); ?>

                                        </span>
                                    </div>
                                <?php elseif($payment->status === 'rejected'): ?>
                                    <span class="badge bg-danger text-white" style="background-color: #dc3545;">
                                        Đã hủy lúc
                                        <?php echo e($payment->rejected_at ? \Carbon\Carbon::parse($payment->rejected_at)->format('d/m/Y H:i') : ''); ?>

                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                        <?php if(in_array($payment->status, ['completed'])): ?>
                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                    <a href="<?php echo e(route('admin.payments.invoice', $payment->id)); ?>" class="btn btn-primary btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                    </a>
                                    <a href="<?php echo e(route('admin.payments.download-invoice', $payment->id)); ?>" class="btn btn-success btn-xs px-2 py-1"
                                        style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-download me-1"></i> Tải PDF
                                    </a>
                                </div>
                            <?php elseif($payment->status === 'rejected'): ?>
                                <a href="<?php echo e(route('admin.payments.invoice', $payment->id)); ?>" class="btn btn-primary btn-xs px-2 py-1"
                                    style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-file-invoice me-1"></i> Xem hóa đơn
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Chưa có hóa đơn</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <?php echo e($payments->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>