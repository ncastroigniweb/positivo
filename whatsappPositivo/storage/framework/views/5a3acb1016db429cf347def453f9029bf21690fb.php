<div id="product" class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-8 mx-auto text-center">
          <h3 class="display-3 ckedit" key="explain_maintitle" id="explain_maintitle"><?php echo e(__('whatsapp.explain_maintitle')); ?></h3>
          <p class="lead ckedit" key="explain_mainsubtitle" id="explain_mainsubtitle"><?php echo e(__('whatsapp.explain_mainsubtitle')); ?></p>
          </div>
        </div>
        <div class="row align-items-center mt-5">
          <div class="col-md-7">
            <?php $__currentLoopData = $processes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $process): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="info info-horizontal info-hover-primary mt-5">
                <div class="description pl-4">
                  <h5 class="title"><?php echo e($process->title); ?></h5>
                  <p><?php echo e($process->description); ?></p>
                  <a href="<?php echo e($process->link); ?>" class="text-info"><?php echo e($process->link_name); ?></a>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>


          <!-- seccion de pricing  -->
          <div class="col-md-5">
            <img class="img-fluid" src="<?php echo e(asset('social')); ?>/img/pc.png" />
          </div>
        </div>
      </div>
    </div><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/explain.blade.php ENDPATH**/ ?>