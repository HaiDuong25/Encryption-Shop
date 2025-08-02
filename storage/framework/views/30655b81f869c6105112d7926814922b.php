<?php $__env->startSection('title', 'Quản lý Danh mục'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách danh mục</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            <a class="btn btn-solid btn-sm" href="<?php echo e(route('admin.categories.create')); ?>">Thêm danh mục</a>
                        </div>
                    </div>

                    <form action="<?php echo e(route('admin.categories.index')); ?>" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                        <div class="search-box" style="width:250px;">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <select name="parent_id" class="form-select" style="width:200px;">
                            <option value="">-- Danh mục cha --</option>
                            <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($parent->id); ?>" <?php echo e(request('parent_id') == $parent->id ? 'selected' : ''); ?>>
                                    <?php echo e($parent->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select name="status" class="form-select" style="width:150px;">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Hiển thị</option>
                            <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Ẩn</option>
                        </select>
                        <button class="btn btn-primary me-2" type="submit">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        <?php if(request()->hasAny(['search', 'parent_id', 'status'])): ?>
                            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        <?php endif; ?>
                    </form>

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
                        <table class="table theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Danh mục cha</th>
                                    <th>Ngày tạo</th>
                                    <th>Ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $grouped = $categories->groupBy('parent_id');
                                    $parents = $grouped[null] ?? collect();
                                ?>

                                <?php $__empty_1 = true; $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="parent-row" data-id="<?php echo e($parent->id); ?>">
                                        <td class="text-start">
                                            <a href="javascript:void(0);" class="toggle-children fw-bold text-dark text-decoration-none">
                                                <i class="ri-arrow-down-s-line me-1"></i> <?php echo e($parent->name); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($parent->created_at?->format('d/m/Y H:i') ?? '—'); ?></td>
                                        <td>
                                            <?php if($parent->image): ?>
                                                <img src="<?php echo e(asset('storage/' . $parent->image)); ?>" width="60" alt="<?php echo e($parent->name); ?>">
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?php echo e($parent->status ? 'status-close' : 'status-danger'); ?>">
                                            <span><?php echo e($parent->status ? 'Hiển thị' : 'Ẩn'); ?></span>
                                        </td>
                                        <td>
                                            <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                <li><a href="<?php echo e(route('admin.categories.edit', $parent)); ?>"><i class="ri-pencil-line"></i></a></li>
                                                <li>
                                                    <button type="button" class="btn btn-link p-0 text-danger delete-btn" data-id="<?php echo e($parent->id); ?>" data-name="<?php echo e($parent->name); ?>">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                    <?php $__currentLoopData = $grouped[$parent->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="child-row d-none" data-parent-id="<?php echo e($parent->id); ?>">
                                            <td class="text-start">└── <?php echo e($child->name); ?></td>
                                            <td><?php echo e($child->created_at?->format('d/m/Y H:i') ?? '—'); ?></td>
                                            <td>
                                                <?php if($child->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $child->image)); ?>" width="60" alt="<?php echo e($child->name); ?>">
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                            <td class="<?php echo e($child->status ? 'status-close' : 'status-danger'); ?>">
                                                <span><?php echo e($child->status ? 'Hiển thị' : 'Ẩn'); ?></span>
                                            </td>
                                            <td>
                                                <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                    <li><a href="<?php echo e(route('admin.categories.edit', $child)); ?>"><i class="ri-pencil-line"></i></a></li>
                                                    <li>
                                                        <button type="button" class="btn btn-link p-0 text-danger delete-btn" data-id="<?php echo e($child->id); ?>" data-name="<?php echo e($child->name); ?>">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="5" class="text-center">Không có danh mục.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-children').forEach(button => {
            button.addEventListener('click', function () {
                const parentRow = button.closest('tr');
                const parentId = parentRow.dataset.id;
                const icon = button.querySelector('i');

                document.querySelectorAll(`tr[data-parent-id='${parentId}']`).forEach(row => {
                    row.classList.toggle('d-none');
                });

                if (icon) {
                    icon.classList.toggle('ri-arrow-down-s-line');
                    icon.classList.toggle('ri-arrow-up-s-line');
                }
            });
        });

        const selectedParentId = '<?php echo e(request("parent_id")); ?>';
        if (selectedParentId) {
            const parentRow = document.querySelector(`tr[data-id='${selectedParentId}']`);
            if (parentRow) {
                document.querySelectorAll(`tr[data-parent-id='${selectedParentId}']`).forEach(row => {
                    row.classList.remove('d-none');
                });

                const icon = parentRow.querySelector('i');
                if (icon) {
                    icon.classList.remove('ri-arrow-down-s-line');
                    icon.classList.add('ri-arrow-up-s-line');
                }
            }
        }

        // AJAX Delete functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.dataset.id;
                const categoryName = this.dataset.name;

                if (confirm(`Bạn có chắc muốn xóa danh mục "${categoryName}"?`)) {
                    // Show loading state
                    this.innerHTML = '<i class="ri-loader-4-line"></i>';
                    this.disabled = true;

                    fetch(`<?php echo e(route('admin.categories.destroy', ':id')); ?>`.replace(':id', categoryId), {
    method: 'DELETE',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from table
                            const row = this.closest('tr');
                            row.remove();

                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                            alertDiv.innerHTML = `
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.querySelector('.table-responsive').before(alertDiv);

                            // Auto hide after 3 seconds
                            setTimeout(() => {
                                if (alertDiv.parentNode) {
                                    alertDiv.remove();
                                }
                            }, 3000);
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi xóa danh mục!');
                            // Restore button state
                            this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa danh mục!');
                        // Restore button state
                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                        this.disabled = false;
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>