<?php echo $__env->make('admin.layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- Page Header Ends-->

<!-- Page Body Start-->
<div class="page-body-wrapper">
    <!-- Page Sidebar Start-->
    <?php echo $__env->make('admin.layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Page Sidebar Ends-->

    <!-- index body start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12"></div>

                <?php echo $__env->yieldContent('content'); ?>

            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- footer start-->
    <?php echo $__env->make('admin.layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- footer End-->

    <?php echo $__env->yieldPushContent('scripts'); ?> 

</div> 
<?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/layouts/main.blade.php ENDPATH**/ ?>