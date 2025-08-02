<?php $__env->startSection('title', 'Quản lý người dùng'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
<div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="container-fluid">
    <div class="card card-table">
        <div class="card-body">
            <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                <h5>Danh sách người dùng</h5>
                <div class="right-options d-flex gap-2 align-items-center">
                    
                    <form method="GET" action="<?php echo e(route('users.index')); ?>" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên, email hoặc SĐT..." 
                               value="<?php echo e(request('search')); ?>" style="width: 280px;">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                                <i class="ri-refresh-line"></i> Xóa bộ lọc
                            </a>
                        <?php endif; ?>
                    </form>
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-theme">
                        <i data-feather="plus"></i> Thêm mới
                    </a>
                </div>
            </div>

            <div class="table-responsive table-product">
                <table class="table theme-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Quyền</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center">
                                <?php if($user->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" width="100">
                                <?php endif; ?>
                            </td>
                            <td>
                                <span><?php echo e($user->name); ?></span>
                            </td>
                            <td><?php echo e($user->role); ?></td>
                            <td><?php echo e($user->phone); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->address); ?></td>
                            <td>
                                <?php if($user->status == 'active'): ?>
                                <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Khóa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <ul>
                                    <li>
                                        <?php if($user->role !== 'admin'): ?>
                                        <button type="button" class="btn btn-link p-0 toggle-btn" 
                                            data-id="<?php echo e($user->id); ?>" 
                                            data-name="<?php echo e($user->name); ?>"
                                            data-current-status="<?php echo e($user->status); ?>">
                                            <?php echo e($user->status == 'active' ? 'Khóa' : 'Mở khóa'); ?>

                                        </button>
                                        <?php endif; ?>
                                    </li>

                                    <li><a href="<?php echo e(route('users.edit', $user)); ?>"><i class="ri-pencil-line"></i></a></li>

                                    <li>
                                        <?php if($user->role !== 'admin'): ?>
                                        <button type="button" class="btn btn-link p-0 text-danger delete-btn" 
                                            data-id="<?php echo e($user->id); ?>" 
                                            data-name="<?php echo e($user->name); ?>">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <?php else: ?>
                                        <span class="text-muted" style="font-size: 14px;">
                                            <i class="ri-delete-bin-line"></i>
                                        </span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX Toggle Status functionality
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const currentStatus = this.dataset.currentStatus;
            const action = currentStatus === 'active' ? 'khóa' : 'mở khóa';
            
            if (confirm(`Bạn chắc chắn muốn ${action} người dùng "${userName}"?`)) {
                // Show loading state
                const originalText = this.textContent;
                this.textContent = 'Đang xử lý...';
                this.disabled = true;
                
                fetch(`/admin/users/${userId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button text and status
                        const newStatus = data.status;
                        this.dataset.currentStatus = newStatus;
                        this.textContent = newStatus === 'active' ? 'Khóa' : 'Mở khóa';
                        
                        // Update status badge
                        const row = this.closest('tr');
                        const statusBadge = row.querySelector('.badge');
                        if (statusBadge) {
                            if (newStatus === 'active') {
                                statusBadge.className = 'badge bg-success';
                                statusBadge.textContent = 'Hoạt động';
                            } else {
                                statusBadge.className = 'badge bg-danger';
                                statusBadge.textContent = 'Khóa';
                            }
                        }
                        
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
                        alert(data.message || 'Có lỗi xảy ra!');
                        this.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                    this.textContent = originalText;
                })
                .finally(() => {
                    this.disabled = false;
                });
            }
        });
    });
    
    // AJAX Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            
            if (confirm(`Bạn có chắc muốn xóa người dùng "${userName}"?`)) {
                // Show loading state
                this.innerHTML = '<i class="ri-loader-4-line"></i>';
                this.disabled = true;
                
                fetch(`/admin/users/${userId}`, {
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
                        alert(data.message || 'Có lỗi xảy ra khi xóa người dùng!');
                        // Restore button state
                        this.innerHTML = '<i class="ri-delete-bin-line"></i>';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa người dùng!');
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
<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/users/index.blade.php ENDPATH**/ ?>