<div class="col-sm-12 col-md-<?php echo e($col); ?> col-lg-<?php echo e($col); ?> mb-<?php echo e($col); ?> ">
  <div class="card-plans cardWithShadow pricingCard">
    <div class="card-body">
      <!-- <div class="imgHolderInCard">
        <img class="image-in-card"
          src="<?php echo e(asset('social')); ?>/img/SVG/512/rocket.svg" />
      </div> -->
      <h5 class="card-title text-center" style="color: #31b1a0; font-family: sans-serif;"><?php echo e(__($plan['name'])); ?></h5>
      <p class="card-text text-center" style="font-weight: bold;"><?php echo e(__($plan['description'])); ?></p>
      <img style="width: 90%;" src="<?php echo e(asset('social')); ?>/img/plans/<?php echo e(__($plan['image'])); ?>" alt="">
      <div class="price-block">
        <!--<span class="price-block-currency"><?php echo e(config('settings.cashier_currency')); ?></span>
        <span class="price-block-value"><?php echo e(number_format($plan['price'] ,0,'.','.')); ?></span>
        <span class="price-block-period">/<?php echo e($plan['period'] == 1? __('whatsapp.month') :  __('whatsapp.year')); ?></span>-->
      </div>
      <div class="plan_feature_list">
        <ul class="plan_features list-unstyled">
          <?php $__currentLoopData = explode(",",$plan['features']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
              <p class="text-sm">
                <i class="fa fa-check-circle-o" aria-hidden="true" style="color: #31b1a0"></i> <?php echo e(__($feature)); ?>

              </p>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
      <br />
      <a href="<?php echo e(route('newrestaurant.register')); ?>" type="button" class="btn btn-outline-success">
        <?php echo e(__('whatsapp.start_now')); ?>

      </a>
    </div>
  </div>
</div><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/plan.blade.php ENDPATH**/ ?>