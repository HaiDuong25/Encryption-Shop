<?php $__env->startSection('title', 'Quản lý Thương hiệu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách thương hiệu</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            
                            <form method="GET" action="<?php echo e(route('brands.index')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên thương hiệu..." 
                                       value="<?php echo e(request('search')); ?>" style="width: 250px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-search-line"></i> Tìm
                                </button>
                                <?php if(request('search')): ?>
                                    <a href="<?php echo e(route('brands.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                                    </a>
                                <?php endif; ?>
                            </form>
                            <a class="btn btn-solid btn-sm" href="<?php echo e(route('brands.create')); ?>">Thêm mới</a>
                        </div>
                    </div>

                    <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <div class="table-responsive mt-3">
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên thương hiệu</th>
                                    <th>Ngày tạo</th>
                                    <th>Ảnh</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr style="border-bottom: none !important;">
                                    <td><?php echo e($brand->name); ?></td>
                                    <td>
                                        <?php echo e($brand->created_at ? $brand->created_at->format('d/m/Y H:i') : '—'); ?>

                                    </td>
                                    <td>
                                        <?php if($brand->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $brand->image)); ?>" class="img-fluid" width="60" alt="<?php echo e($brand->name); ?>">
                                        <?php else: ?>
                                        —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="<?php echo e(route('brands.edit', $brand)); ?>">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-link p-0 text-danger delete-btn" data-id="<?php echo e($brand->id); ?>" data-name="<?php echo e($brand->name); ?>">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có thương hiệu.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const brandId = this.dataset.id;
            const brandName = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa thương hiệu "${brandName}"?`)) {
                // Show loading state
                this.innerHTML = '<i class="ri-loader-4-line"></i>';
                this.disabled = true;
                
                fetch(`/admin/brands/${brandId}`, {
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
                        alert(data.message || 'Có lỗi xảy ra khi xóa thương hiệu!');
                        // Restore button state
                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa thương hiệu!');
                    // Restore button state
                    this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                    this.disabled = false;
                });
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/brands/index.blade.php ENDPATH**/ ?>