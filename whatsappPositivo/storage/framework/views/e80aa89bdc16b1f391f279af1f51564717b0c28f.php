<div id="features" class="section features-6">
      <div class="container">
        <div class="row">
        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <!-- Card  -->
          <div class="col-sm-12 col-md-6 col-lg-4 mb-4 ">
            <div class="card cardWithShadow cardWithShadowAnimated">
              <div class="card-body">
                <div class="imgHolderInCard">
                  <img 
                    class="image-in-card" 
                    src='<?php echo e(str_contains($feature->image, "social") ? $feature->image : "/uploads/restorants/".$feature->image."_large.jpg"); ?>' 
                  />
                </div>
                <h5 class="card-title"><?php echo e($feature->title); ?></h5>
                <p class="card-text"><?php echo e($feature->description); ?></p>
              </div>
            </div>
          </div>
          <!-- End card -->
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <br />

        <div class="container">
          <div class="row">
            <div class="col-md-8 mx-auto text-center">
              <!-- <div class="alert alert-success ckedit" role="alert" key="feature_main_subtitle1" id="feature_main_subtitle1">asdadsf<?php echo e(__('whatsapp.feature_main_subtitle1')); ?></div> -->
              <div class="alert alert-success ckedit" role="alert" key="feature_main_subtitle2" id="feature_main_subtitle2"><?php echo e(__('whatsapp.feature_main_subtitle2')); ?></div>
            </div>
          </div>
        </div>
      </div>
    </div><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/features.blade.php ENDPATH**/ ?>