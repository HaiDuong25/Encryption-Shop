<?php $__env->startSection('title', 'Quản lý Tin tức'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                            <h5>Danh sách tin tức</h5>
                            <div class="right-options d-flex gap-2 align-items-center">
                                <a class="btn btn-solid" href="<?php echo e(route('news.create')); ?>">Thêm tin mới</a>
                            </div>
                        </div>

                        
                        <form action="<?php echo e(route('news.index')); ?>" method="GET" class="mb-3 d-flex flex-wrap gap-2">
                            <input type="text" name="title" value="<?php echo e(request('title')); ?>" placeholder="Tìm theo tiêu đề..."
                                class="form-control" style="width:220px;">
                            <button class="btn btn-primary me-2" type="submit">
                                <i class="ri-search-line"></i> Tìm
                            </button>
                            <?php if(request('title')): ?>
                                <a href="<?php echo e(route('news.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                    <i class="ri-refresh-line"></i> Xóa bộ lọc
                                </a>
                            <?php endif; ?>
                        </form>

                        <div class="table-responsive">
                            <table class="table all-package theme-table table-product text-center align-middle"
                                style="border-collapse: separate; border-spacing: 0 12px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Tác giả</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đăng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr style="border-bottom: none !important;">
                                            <td>
                                                <div class="table-image">
                                                    <?php if($item->image): ?>
                                                        <img src="<?php echo e(asset('storage/' . $item->image)); ?>" class="img-fluid"
                                                            width="60" alt="<?php echo e($item->title); ?>">
                                                    <?php else: ?>
                                                        —
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo e($item->title); ?></td>
                                            <td>
                                                <div class="small text-muted"
                                                    style="max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <?php echo e(\Illuminate\Support\Str::limit(strip_tags($item->content), 60)); ?>

                                                </div>
                                            </td>
                                            <td>
                                                <?php if($item->user): ?>
                                                    <?php echo e($item->user->name); ?>

                                                <?php else: ?>
                                                    <?php echo e($item->author); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="<?php echo e($item->is_published ? 'status-close' : 'status-danger'); ?>">
                                                    <?php echo e($item->is_published ? 'Đã đăng' : 'Nháp'); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($item->created_at->format('d/m/Y')); ?></td>
                                            <td>
                                                <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                    <li>
                                                        <a href="<?php echo e(route('news.show', $item->id)); ?>" class="text-info"
                                                            title="Xem chi tiết">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo e(route('news.edit', $item->id)); ?>" class="text-warning"
                                                            title="Sửa">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="btn btn-link p-0 text-danger delete-btn"
                                                                data-id="<?php echo e($item->id); ?>"
                                                                data-name="<?php echo e($item->title); ?>"
                                                                title="Xoá">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <?php echo e(request('title') ? 'Không tìm thấy tin tức nào phù hợp.' : 'Chưa có tin tức nào.'); ?>

                                            </td>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const newsId = this.dataset.id;
            const newsTitle = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa tin tức "${newsTitle}"?`)) {
                // Show loading state
                const icon = this.querySelector('i');
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="ri-loader-line rotating"></i>';
                this.disabled = true;
                
                fetch(`/admin/news/${newsId}`, {
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
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 3000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa tin tức!');
                        // Restore button state
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa tin tức!');
                    // Restore button state
                    this.innerHTML = originalContent;
                    this.disabled = false;
                });
            }
        });
    });
</script>
<style>
    .rotating {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/news/index.blade.php ENDPATH**/ ?>