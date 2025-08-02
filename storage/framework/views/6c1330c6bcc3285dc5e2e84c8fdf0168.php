<?php $__env->startSection('title', 'Quản lý Liên hệ Khách hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">

                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Danh sách Liên hệ Khách hàng</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            
                            <form method="GET" action="<?php echo e(route('contacts.index')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên, email hoặc nội dung..." 
                                       value="<?php echo e(request('search')); ?>" style="width: 280px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line"></i> Tìm
                                </button>
                                <?php if(request('search')): ?>
                                    <a href="<?php echo e(route('contacts.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <?php $__currentLoopData = ['success', 'error']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(session($msg)): ?>
                            <div class="alert alert-<?php echo e($msg == 'success' ? 'success' : 'danger'); ?> alert-dismissible fade show mt-2">
                                <?php echo e(session($msg)); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="table-responsive mt-3">
                        <table class="table theme-table text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người gửi</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th style="min-width: 250px;">Nội dung (tóm tắt)</th>
                                    <th>Ngày gửi</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($contact->id); ?></td>
                                        <td>
                                            <?php echo e($contact->name); ?>

                                            <br>
                                            <small class="text-muted">
                                                <?php echo e($contact->user_id && $contact->user ? "(User: {$contact->user->name} - ID: {$contact->user_id})" : '(Khách)'); ?>

                                            </small>
                                        </td>
                                        <td><a href="mailto:<?php echo e($contact->email); ?>"><?php echo e($contact->email); ?></a></td>
                                        <td><?php echo e($contact->phone ?: 'N/A'); ?></td>
                                        <td><?php echo e(Str::limit($contact->content, 100)); ?></td>
                                        <td><?php echo e(optional($contact->created_at)->format('d/m/Y H:i') ?? 'Không rõ'); ?></td>
                                        <td>
                                            <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                <li>
                                                    <a href="<?php echo e(route('contacts.show', $contact->id)); ?>" title="Xem chi tiết">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="<?php echo e(route('contacts.destroy', $contact->id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-link p-0 text-danger" title="Xóa">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="7" class="text-center">Chưa có liên hệ nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($contacts->hasPages()): ?>
                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($contacts->links()); ?>

                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/contacts/index.blade.php ENDPATH**/ ?>