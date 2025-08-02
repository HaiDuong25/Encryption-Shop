<?php $__env->startSection('title', 'Quản lý sản phẩm'); ?>

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
            <div class="title-header option-title">
                <h5>Danh sách sản phẩm</h5>
                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-theme">
                    <i data-feather="plus"></i> Thêm sản phẩm
                </a>
            </div>
            <form action="<?php echo e(route('products.index')); ?>" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                <div class="search-box" style="width:250px;">
                    <input type="text" name="search" value="<?php echo e(request('search') ?? request('keyword')); ?>" placeholder="Tìm kiếm theo tên sản phẩm..." class="form-control">
                </div>

                <select name="category_id" class="form-select" style="width:180px;">
                    <option value="">-- Danh mục --</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                        <?php echo e($cat->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <input type="number" name="price_from" value="<?php echo e(request('price_from')); ?>" placeholder="Giá từ" class="form-control" style="width:120px;">
                <input type="number" name="price_to" value="<?php echo e(request('price_to')); ?>" placeholder="Giá đến" class="form-control" style="width:120px;">

                <select name="status" class="form-select" style="width:150px;">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Hiển thị</option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Ẩn</option>
                </select>

                <button class="btn btn-primary me-2" type="submit">
                    <i class="ri-search-line"></i> Tìm
                </button>
                <?php if(request()->hasAny(['search', 'keyword', 'category_id', 'price_from', 'price_to', 'status'])): ?>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary me-2 bg-dark">
                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                    </a>
                <?php endif; ?>
            </form>


            <div class="table-responsive table-product">
                <table class="table theme-table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center">
                                <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" width="80" class="rounded border">
                                <?php else: ?>
                                <span class="text-secondary small fst-italic">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-semibold"><?php echo e($product->name); ?></span>
                            </td>
                            <td><?php echo e($product->category->name ?? '-'); ?></td>
                            <td><?php echo e($product->brand->name ?? '-'); ?></td>
                            <td>
                                <?php if($product->sale_price): ?>
                                    <span class="text-muted text-decoration-line-through small">
                                        <?php echo e(format_vnd($product->price)); ?> đ
                                    </span><br>
                                    <span class="text-danger fw-bold">
                                        <?php echo e(format_vnd($product->sale_price)); ?> đ
                                    </span>
                                <?php else: ?>
                                    <span class="text-danger fw-bold">
                                        <?php echo e(format_vnd($product->price)); ?> đ
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($product->status == 'active'): ?>
                                <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <ul class="d-flex flex-wrap gap-2 mb-0" style="list-style:none; padding-left:0;">
                                    <li>
                                        <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-link p-0" title="Xem chi tiết">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-link p-0" title="Sửa">
                                            <i data-feather="edit"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <button class="btn btn-link p-0 text-danger delete-btn"
                                                data-id="<?php echo e($product->id); ?>"
                                                data-name="<?php echo e($product->name); ?>"
                                                title="Xoá">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const productId = this.dataset.id;
        const productName = this.dataset.name;

        if (!confirm(`Bạn có chắc muốn xóa sản phẩm "${productName}"?`)) return;

        const icon = this.querySelector('i');
        const originalContent = this.innerHTML;
        this.innerHTML = '<i data-feather="loader" class="rotating"></i>';
        this.disabled = true;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.closest('tr').remove();
                alert(data.message || 'Xóa thành công!');
            } else if (data.requiresConfirmation) {
                const confirmHide = confirm(data.message);
                if (confirmHide) {
                    // Gửi lại DELETE request với param set_inactive=true
                    const response2 = await fetch(`/admin/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ set_inactive: true })
                    });

                    const data2 = await response2.json();

                    if (response2.ok && data2.success) {
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('td span.badge');
                        if (statusCell) {
                            statusCell.className = 'badge bg-danger';
                            statusCell.textContent = 'Ẩn';
                        }

                        this.closest('li').remove(); // Ẩn nút xóa
                        alert(data2.message || 'Đã chuyển sang trạng thái ẩn.');
                    } else {
                        alert(data2.message || 'Không thể ẩn sản phẩm.');
                    }
                } else {
                    alert('Đã hủy thao tác.');
                }
            } else {
                alert(data.message || 'Không thể xóa sản phẩm.');
            }
        } catch (error) {
            console.error('Lỗi khi xóa:', error);
            alert('Có lỗi xảy ra trong quá trình xử lý.');
        } finally {
            this.innerHTML = originalContent;
            this.disabled = false;
            feather.replace();
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


<?php echo $__env->make('admin.layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/products/index.blade.php ENDPATH**/ ?>