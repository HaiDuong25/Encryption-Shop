<?php $__env->startSection('title', 'Hồ sơ cá nhân'); ?>

<?php $__env->startPush('style'); ?>
<style>
/* Avatar transition mượt */
.profile-avatar img, [data-user-avatar] {
    transition: opacity 0.3s ease-in-out;
}

.profile-avatar img.updating, [data-user-avatar].updating {
    opacity: 0.7;
}

.profile-avatar {
    position: relative;
}

.profile-avatar.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="address-form-wrapper">
    <div class="container-fluid">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php echo $__env->make('client.account.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="form-card">
                    <div class="form-header">
                        <h4><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</h4>
                        <p class="text-muted">Xem và quản lý thông tin cá nhân của bạn</p>
                    </div>

                    <!-- Profile Summary -->
                    <div class="profile-summary mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="profile-avatar">
                                    <img id="profile-avatar-main" 
                                         data-user-avatar
                                         src="<?php echo e(auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets-front/images/inner-page/users/1.png')); ?>" 
                                         alt="Ảnh đại diện" class="rounded-circle">
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1"><?php echo e(auth()->user()->name); ?></h5>
                                <p class="text-muted mb-2"><?php echo e(auth()->user()->email); ?></p>
                                <?php if(auth()->user()->phone): ?>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-phone me-1"></i><?php echo e(auth()->user()->phone); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto">
                                <a href="<?php echo e(route('account.editProfile')); ?>" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h6><i class="fas fa-info-circle me-2"></i>Thông tin cá nhân</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <div class="form-control-static" tabindex="-1"><?php echo e(auth()->user()->name); ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control-static" tabindex="-1"><?php echo e(auth()->user()->email); ?></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <div class="form-control-static" tabindex="-1">
                                    <?php echo e(auth()->user()->phone ?: 'Chưa cập nhật'); ?>

                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <div class="form-control-static" tabindex="-1">
                                    <?php echo e(auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật'); ?>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <div class="form-control-static" tabindex="-1">
                                    <?php if(auth()->user()->gender == 'male'): ?>
                                        Nam
                                    <?php elseif(auth()->user()->gender == 'female'): ?>
                                        Nữ
                                    <?php elseif(auth()->user()->gender == 'other'): ?>
                                        Khác
                                    <?php else: ?>
                                        Chưa cập nhật
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if(auth()->user()->address): ?>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <div class="form-control-static" tabindex="-1"><?php echo e(auth()->user()->address); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if(auth()->user()->bio): ?>
                            <div class="mb-3">
                                <label class="form-label">Giới thiệu bản thân</label>
                                <div class="form-control-static" tabindex="-1"><?php echo e(auth()->user()->bio); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Security Information -->
                    
                    <!-- Quick Actions -->
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lắng nghe sự kiện avatar update để tạo hiệu ứng mượt
    document.addEventListener('avatarUpdated', function(event) {
        const avatarElements = document.querySelectorAll('[data-user-avatar], #profile-avatar-main, #header-user-avatar');
        
        avatarElements.forEach(element => {
            // Thêm class updating để tạo hiệu ứng
            element.classList.add('updating');
            
            // Sau 150ms thì cập nhật ảnh và remove class
            setTimeout(() => {
                element.src = event.detail.avatarUrl;
                element.classList.remove('updating');
            }, 150);
        });
    });
    
    // Thêm loading indicator khi bắt đầu upload
    const avatarUpload = document.getElementById('avatar-upload');
    if (avatarUpload) {
        avatarUpload.addEventListener('change', function() {
            const profileAvatar = document.querySelector('.profile-avatar');
            if (profileAvatar) {
                profileAvatar.classList.add('loading');
            }
        });
    }
    
    // Remove loading indicator sau khi upload xong
    document.addEventListener('avatarUpdated', function() {
        const profileAvatar = document.querySelector('.profile-avatar');
        if (profileAvatar) {
            profileAvatar.classList.remove('loading');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/client/account/index.blade.php ENDPATH**/ ?>