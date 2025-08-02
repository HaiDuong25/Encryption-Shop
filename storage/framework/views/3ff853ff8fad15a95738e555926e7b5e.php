<?php $__env->startSection('title', 'Quản lý Đánh giá Khách hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách Đánh giá Khách hàng</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            
                            <form method="GET" action="<?php echo e(route('rates.index')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên người dùng hoặc nội dung..." 
                                       value="<?php echo e(request('search')); ?>" style="width: 300px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line"></i> Tìm
                                </button>
                                <?php if(request('search')): ?>
                                    <a href="<?php echo e(route('rates.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
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

                    <div class="table-responsive mt-3">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm ID</th>
                                    <th>Điểm</th>
                                    <th style="min-width: 200px;">Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr style="border-bottom: none !important;">
                                    <td><?php echo e($rate->id); ?></td>
                                    <td>
                                        <?php if($rate->user): ?>
                                        <?php echo e($rate->user->name); ?>

                                        <br><small class="text-muted">(ID: <?php echo e($rate->user->id); ?>)</small>
                                        <?php else: ?>
                                        <span class="text-muted">Không xác định</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($rate->product_id ?? 'N/A'); ?></td>
                                    <td>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-star <?php echo e($i <= $rate->score ? 'fas text-warning' : 'far text-muted'); ?>"></i>
                                        <?php endfor; ?>
                                        (<?php echo e($rate->score); ?>)
                                    </td>
                                    <td><?php echo e(Str::limit($rate->content, 100)); ?></td>
                                    <td>
                                        <span class="badge rounded-pill <?php echo e($rate->status_class); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $rate->status_text))); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($rate->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="<?php echo e(route('rates.show', $rate->id)); ?>">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('rates.edit', $rate->id)); ?>">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="<?php echo e(route('rates.destroy', $rate->id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-link p-0 text-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không có đánh giá nào để hiển thị.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($rates->hasPages()): ?>
                    <div class="mt-3 d-flex justify-content-center">
                        <?php echo e($rates->withQueryString()->links()); ?>

                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/rates/index.blade.php ENDPATH**/ ?>