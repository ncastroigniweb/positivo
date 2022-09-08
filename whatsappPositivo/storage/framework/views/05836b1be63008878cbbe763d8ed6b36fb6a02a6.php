<?php $__env->startSection('content'); ?>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <?php if(isset($coupon)): ?>
                                <h3 class="mb-0"><?php echo e(__('Edit coupon')); ?></h3>
                            <?php else: ?>
                                <h3 class="mb-0"><?php echo e(__('New coupon')); ?></h3>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 text-right">
                            <a href="<?php echo e(route('admin.restaurant.coupons.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Coupon information')); ?></h6>
                    <?php if(session('status')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('status')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="pl-lg-4">
                        <?php if(isset($coupon)): ?>
                            <form method="post" action="<?php echo e(route('admin.restaurant.coupons.update', $coupon->id)); ?>" autocomplete="off" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>
                        <?php else: ?>
                            <form method="post" action="<?php echo e(route('admin.restaurant.coupons.store')); ?>" autocomplete="off" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                        <?php endif; ?>
                            <?php echo $__env->make('coupons.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div>
                                <?php if(isset($coupon)): ?>
                                    <button type="submit" class="btn btn-primary mt-4"><?php echo e(__('Update')); ?></button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        "use strict";
        /*$('#type option').each(function() {
            if($(this).is(':selected')){
                alert($(this).value)
            }
        })*/

        var coupon = <?php if(isset($coupon)) { echo json_encode($coupon); } else { echo json_encode(null); } ?>;
        if(coupon != null){
            var coupon_type = coupon.type;
            if(coupon_type == 0){
                $('#form-group-price_fixed').show();

                $("#price_fixed").attr("required",true);
                $("#price_percentage").attr("required",false);
            }else{
                $('#form-group-price_percentage').show();

                $("#price_percentage").attr("required",true);
                $("#price_fixed").attr("required",false);
            }
        }

        $('#type').on('change', function() {
            if(this.value == 0){
                $("#price_percentage").attr("required",false);
                $('#form-group-price_percentage').hide();

                $('#form-group-price_fixed').show();
                $("#price_fixed").attr("required",true);

            }else{
                $('#form-group-price_fixed').hide();
                $("#price_fixed").attr("required",false);

                $('#form-group-price_percentage').show();
                $("#price_percentage").attr("required",true);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => __('Coupon')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/coupons/create.blade.php ENDPATH**/ ?>