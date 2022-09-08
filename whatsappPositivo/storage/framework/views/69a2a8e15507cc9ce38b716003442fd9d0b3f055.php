<?php $__env->startSection('cardbody'); ?>
<div class="container-fluid">
    <div class="row">
    <?php $__currentLoopData = $setup['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-4">
            <br/>
            <a href="<?php echo e(route("admin.landing.features.edit",["feature"=>$item->id])); ?>">
                <div class="card cardWithShadow cardWithShadowAnimated shadow" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Edit this feature')); ?>">
                    <div class="card-body">
                        <div class="imgHolderInCard">
                        <img 
                            class="image-in-card" 
                            src='<?php echo e(str_contains($item->image, "social") ? $item->image : "/uploads/restorants/".$item->image."_large.jpg"); ?>'
                            width="150" 
                            height="150"
                        />
                        </div>
                        <h3 class="card-title"><?php echo e($item->title); ?></h3>
                        <p class="card-text"><?php echo e($item->description); ?></p>
                    </div>
                </div>
            </a>
            <br/>
            <div class="text-center">
                <a href="<?php echo e(route("admin.landing.features.delete",["feature"=>$item->id])); ?>" class="btn btn-danger btn-sm"><?php echo e(__('Delete')); ?></a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('general.index', $setup, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/landing/features/index.blade.php ENDPATH**/ ?>