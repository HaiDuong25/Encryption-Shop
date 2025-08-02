<?php $__env->startSection('title', 'Phương thức thanh toán'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Phương thức thanh toán</h3>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tất cả phương thức</h5>
            <div class="right-options d-flex gap-2 align-items-center">
                
                <form method="GET" action="<?php echo e(route('payment-methods.index')); ?>" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo loại hoặc mô tả..." 
                           value="<?php echo e(request('search')); ?>" style="width: 250px;">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="ri-search-line"></i> Tìm
                    </button>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('payment-methods.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                            <i class="ri-refresh-line"></i> Xóa bộ lọc
                        </a>
                    <?php endif; ?>
                </form>
                <a href="<?php echo e(route('payment-methods.create')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm mới
                </a>
            </div>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-hover table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Loại thanh toán</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($method->id); ?></td>
                            <td><?php echo e($method->payment_type); ?></td>
                            <td><?php echo e($method->description); ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?php echo e(route('payment-methods.edit', $method)); ?>" class="btn btn-sm btn-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?php echo e($method->id); ?>" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <!-- Modal Xác nhận Xóa -->
                                <div class="modal fade" id="deleteModal<?php echo e($method->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Bạn có chắc chắn muốn xóa phương thức <strong><?php echo e($method->payment_type); ?></strong> không?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="<?php echo e(route('payment-methods.destroy', $method)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                <?php echo e($methods->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/payment-methods/index.blade.php ENDPATH**/ ?>