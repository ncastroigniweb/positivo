<?php $__env->startSection('cardbody'); ?>

<div class="container-fluid">
    <div class="row">
    <?php $__currentLoopData = $setup['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <br/>
            <a href="<?php echo e(route("admin.landing.processes.edit",["process"=>$item->id])); ?>">
            <div class="info info-horizontal info-hover-primary mt-5" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Edit this process')); ?>">
              <div class="description pl-4">
                <h3 class="title"><?php echo e($item->title); ?></h3>
                <p><?php echo e($item->description); ?></p>
                <a href="<?php echo e($item->link); ?>" class="text-info"><?php echo e($item->link_name); ?></a>
              </div>
            </div>
            </a>
            <br/>
            <div class="text-center">
                <a href="<?php echo e(route("admin.landing.processes.delete",["process"=>$item->id])); ?>" class="btn btn-danger btn-sm"><?php echo e(__('Delete')); ?></a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('general.index', $setup, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/landing/processes/index.blade.php ENDPATH**/ ?>