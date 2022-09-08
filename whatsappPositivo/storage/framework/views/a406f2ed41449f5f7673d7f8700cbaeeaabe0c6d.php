<div id="pricing" class="section pricing_section">
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <h3 class="display-3 ckedit" key="pricing_title" id="pricing_title"><?php echo e(__('whatsapp.pricing_title')); ?></h3>
        <p class="lead ckedit" key="pricing_subtitle" id="pricing_subtitle"><?php echo e(__('whatsapp.pricing_subtitle')); ?></p><br />
      </div>
    </div>

    <div class="row">
      <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo $__env->make('social.partials.plan',['plan'=>$plan,'col'=>$col], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</div><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/pricing.blade.php ENDPATH**/ ?>