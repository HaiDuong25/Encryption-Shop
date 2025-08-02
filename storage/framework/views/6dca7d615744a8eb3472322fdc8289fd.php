<?php $__env->startSection('title', 'Danh sách yêu cầu trả hàng'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">📦 Danh sách yêu cầu trả hàng</h2>

            <form action="<?php echo e(route('admin.returns.index')); ?>" method="GET" class="d-flex gap-2" style="max-width: 400px;">
                <input type="text" name="search" class="form-control" placeholder="Tìm khách hàng, sản phẩm, lý do..."
                       value="<?php echo e(request('search')); ?>">
                <button class="btn btn-primary me-2">
                    <i class="ri-search-line"></i> Tìm
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('admin.returns.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $statusLabels = [
                                    'pending' => ['label' => 'Chờ duyệt đơn ', 'class' => 'bg-warning'],
                                    'returning' => ['label' => 'Đang trả hàng', 'class' => 'bg-info'],
                                    'approved' => ['label' => 'Đã trả hàng', 'class' => 'bg-success'],
                                    'rejected' => ['label' => 'Từ chối', 'class' => 'bg-danger'],
                                    'returned' => ['label' => 'Đã duyệt đơn', 'class' => 'bg-secondary'],
                                    'refunded' => ['label' => 'Đã hoàn tiền', 'class' => 'bg-success'],
                                ];
                                $status = $statusLabels[$return->status] ?? ['label' => ucfirst($return->status), 'class' => 'bg-light'];
                            ?>
                            <tr>
                                <td><?php echo e($return->id); ?></td>
                                <td><?php echo e($return->user->name ?? 'Ẩn danh'); ?></td>
                                <td><?php echo e($return->orderDetail->product->name ?? 'Không rõ'); ?></td>
                                <td><?php echo e($return->reason); ?></td>
                                <td><span class="badge <?php echo e($status['class']); ?>"><?php echo e($status['label']); ?></span></td>
                                <td><?php echo e($return->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.returns.show', $return->id)); ?>"
                                       class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có yêu cầu trả hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="mt-3">
            <?php echo e($returns->withQueryString()->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/returns/index.blade.php ENDPATH**/ ?>