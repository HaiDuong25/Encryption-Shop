<?php $__env->startSection('title', 'Thêm địa chỉ mới'); ?>

<?php $__env->startSection('content'); ?>
<style>
.addresses-wrapper {
    max-width: 1500px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem 1rem;
}
.form-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    padding: 2rem;
}
.form-section {
    border-left: 4px solid #4e73df;
    padding-left: 1rem;
    margin-bottom: 2rem;
}
.form-section h6 {
    color: #4e73df;
    font-weight: 600;
    margin-bottom: 1rem;
}
.required {
    color: #e74a3b;
}
</style>

<div class="addresses-wrapper">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Thêm địa chỉ mới</h1>
            <p class="mb-0 text-muted">Thêm địa chỉ giao hàng mới vào sổ địa chỉ</p>
        </div>
        <a href="<?php echo e(route('client.addresses.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo e(route('client.addresses.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-card">
                    <!-- Thông tin liên hệ -->
                    <div class="form-section">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6><i class="fas fa-user me-2"></i>Thông tin liên hệ</h6>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="fillAccountInfo()">
                                <i class="fas fa-user-circle me-2"></i>Sử dụng thông tin tài khoản
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên <span class="required">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Nhập họ tên" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="required">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="phone" name="phone" value="<?php echo e(old('phone')); ?>" 
                                       placeholder="0123456789" required>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="form-section">
                        <h6><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố <span class="required">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="province" name="province" required onchange="loadWards()">
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($province); ?>" <?php echo e(old('province') == $province ? 'selected' : ''); ?>>
                                            <?php echo e($province); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ward" class="form-label">Xã/Phường/Thị trấn <span class="required">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="ward" name="ward" required disabled>
                                    <option value="">Chọn Xã/Phường/Thị trấn</option>
                                </select>
                                <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address_detail" class="form-label">Địa chỉ chi tiết <span class="required">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['address_detail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="address_detail" name="address_detail" rows="3" 
                                      placeholder="Số nhà, tên đường..." required><?php echo e(old('address_detail')); ?></textarea>
                            <?php $__errorArgs = ['address_detail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Cài đặt -->
                    <div class="form-section">
                        <h6><i class="fas fa-cog me-2"></i>Cài đặt</h6>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" 
                                           name="is_default" value="1" 
                                           <?php echo e(!$hasExistingAddresses ? 'checked disabled' : ''); ?>

                                           <?php echo e(old('is_default') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                    <?php if(!$hasExistingAddresses): ?>
                                        <small class="text-info d-block">Địa chỉ đầu tiên sẽ tự động được đặt làm mặc định</small>
                                        <input type="hidden" name="is_default" value="1">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="note" name="note" rows="2" 
                                      placeholder="Ghi chú thêm về địa chỉ này..."><?php echo e(old('note')); ?></textarea>
                            <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Submit buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu địa chỉ
                        </button>
                        <a href="<?php echo e(route('client.addresses.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy bỏ
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Side info -->
        <div class="col-lg-4">
            <div class="form-card">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Lưu ý
                </h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Địa chỉ sẽ được lưu vào sổ địa chỉ để sử dụng cho các đơn hàng tiếp theo
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Bạn có thể đặt địa chỉ này làm mặc định
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Có thể chỉnh sửa hoặc xóa địa chỉ này sau khi lưu
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Load wards based on selected province
async function loadWards() {
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');
    const selectedProvince = provinceSelect.value;
    
    // Clear current wards
    wardSelect.innerHTML = '<option value="">Đang tải...</option>';
    wardSelect.disabled = true;
    
    if (!selectedProvince) {
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wardSelect.disabled = true;
        return;
    }
    
    try {
        const response = await fetch(`/api/wards?province=${encodeURIComponent(selectedProvince)}`);
        const data = await response.json();
        
        // Clear and populate ward options
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        // Handle different response formats
        let wardsList = [];
        if (Array.isArray(data)) {
            wardsList = data;
        } else if (data.success && Array.isArray(data.wards)) {
            wardsList = data.wards;
        } else if (data.value && Array.isArray(data.value)) {
            wardsList = data.value;
        }
        
        if (wardsList.length > 0) {
            wardsList.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward;
                option.textContent = ward;
                wardSelect.appendChild(option);
            });
        } else {
            wardSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
        }
        
        wardSelect.disabled = false;
    } catch (error) {
        console.error('Error loading wards:', error);
        wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        wardSelect.disabled = false;
    }
}

// Fill account information
function fillAccountInfo() {
    // Get account info from server
    const accountName = "<?php echo e(Auth::user()->name); ?>";
    const accountPhone = "<?php echo e(Auth::user()->phone ?? ''); ?>";
    
    // Fill the form fields
    document.getElementById('name').value = accountName;
    if (accountPhone) {
        document.getElementById('phone').value = accountPhone;
    }
    
    // Show confirmation
    if (accountName || accountPhone) {
        const toast = document.createElement('div');
        toast.className = 'toast show position-fixed bottom-0 end-0 m-3';
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Đã điền thông tin từ tài khoản!
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');
    
    // Add event listener for province change
    provinceSelect.addEventListener('change', loadWards);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/client/addresses/create.blade.php ENDPATH**/ ?>