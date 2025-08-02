<div class="container-fluid">
    <footer class="footer">
        <div class="row">
            <div class="col-md-12 footer-copyright text-center">
                <p class="mb-0">Copyright <?= date('Y') ?> © Encryption Shop </p>
            </div>
        </div>
    </footer>
</div>
</div>
<!-- index body end -->

</div>
<!-- Page Body End -->
</div>
<!-- page-wrapper End-->

<!-- Modal Start -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="staticBackdropLabel">Đăng xuất</h5>
                <p>Bạn có muốn đăng xuất không?</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box">
                    <button type="button" class="btn btn--no" data-bs-dismiss="modal">Không</button>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn--yes btn-success">Có</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

<!-- latest js -->
<script src="<?php echo e(asset('assets/js/jquery-3.6.0.min.js')); ?>"></script>

<!-- Bootstrap js -->
<script src="<?php echo e(asset('assets/js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>

<!-- feather icon js -->
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/icons/feather-icon/feather-icon.js')); ?>"></script>

<!-- scrollbar simplebar js -->
<script src="<?php echo e(asset('assets/js/scrollbar/simplebar.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/scrollbar/custom.js')); ?>"></script>

<!-- Sidebar jquery -->
<script src="<?php echo e(asset('assets/js/config.js')); ?>"></script>

<!-- tooltip init js -->
<script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>

<!-- Plugins JS -->
<script src="<?php echo e(asset('assets/js/sidebar-menu.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>

<!-- Apexchar js -->
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart1.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>


<!-- slick slider js -->
<script src="<?php echo e(asset('assets/js/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/custom-slick.js')); ?>"></script>

<!-- customizer js -->
<script src="<?php echo e(asset('assets/js/customizer.js')); ?>"></script>

<!-- ratio js -->
<script src="<?php echo e(asset('assets/js/ratio.js')); ?>"></script>

<!-- sidebar effect -->
<script src="<?php echo e(asset('assets/js/sidebareffect.js')); ?>"></script>

<!-- Theme js -->
<script src="<?php echo e(asset('assets/js/script.js')); ?>"></script>
</body>


<!-- Mirrored from themes.pixelstrap.com/encryptionstore/back-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Nov 2024 14:35:33 GMT -->

</html><?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/admin/layouts/partials/footer.blade.php ENDPATH**/ ?>