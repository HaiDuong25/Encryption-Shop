<?php $__env->startSection('title', 'Sản phẩm yêu thích'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h3 class="fw-bold mb-4">💖 Sản phẩm yêu thích</h3>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($wishlists->isEmpty()): ?>
        <div class="alert alert-info">Chưa có sản phẩm yêu thích nào.</div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php $__currentLoopData = $wishlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm position-relative">
                        <a href="<?php echo e(route('client.products.show', $item->product->id)); ?>">
                            <img src="<?php echo e(asset('storage/' . $item->product->image)); ?>"
                                class="card-img-top" style="height: 280px; object-fit: cover;">
                        </a>

                        <!-- Nút "bỏ yêu thích" kiểu trái tim -->
                        <form method="POST" action="<?php echo e(route('wishlist.remove', $item->product->id)); ?>"
                            class="position-absolute top-0 end-0 m-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-light rounded-circle border"
                                title="Bỏ yêu thích"
                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-heart text-danger"></i>
                            </button>
                        </form>

                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">
                                <a href="<?php echo e(route('client.products.show', $item->product->id)); ?>"
                                    class="text-decoration-none text-dark">
                                    <?php echo e($item->product->name); ?>

                                </a>
                            </h6>
                            <p class="card-text text-danger fw-bold">
                                <?php echo e(format_vnd($item->product->price)); ?>₫
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/client/wishlist/index.blade.php ENDPATH**/ ?>