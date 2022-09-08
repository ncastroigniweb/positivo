<footer class="footer">
      <div class="container">
        <div class="row row-grid align-items-center mb-5">
          <div class="col-lg-6">
            <?php if(config('settings.enable_default_cookie_consent')): ?>
              <?php echo $__env->make('cookieConsent::index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            <h3 class="text-primary font-weight-light mb-2 ckedit" key="footer_title" id="footer_title"><?php echo e(__('whatsapp.footer_title')); ?></h4></h3>
            <h4 class="mb-0 font-weight-light ckedit" key="footer_subtitle" id="footer_subtitle"><?php echo e(__('whatsapp.footer_subtitle')); ?></h4>
          </div>
          <div class="col-lg-6 text-lg-center btn-wrapper">
            <!--<button target="_blank" href="#" rel="nofollow"
              class="btn btn-icon-only btn-twitter rounded-circle" data-toggle="tooltip"
              data-original-title="Follow us">
              <span class="btn-inner--icon"><i class="fa fa-twitter"></i></span>
            </button>-->
            <?php if(config('global.facebook')): ?>
            <a target="_blank" href="<?php echo e(config('global.facebook')); ?>" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-facebook" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
            </a>
            <?php endif; ?>
            <!--<button target="_blank" href="#" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-youtube" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-youtube"></i></span>
            </button>-->
            <?php if(config('global.instagram')): ?>
            <a target="_blank" href="<?php echo e(config('global.instagram')); ?>" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-instagram" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
            </a>
            <?php endif; ?>
          </div>
        </div>
        <hr>
        <div class="row align-items-center justify-content-md-between">
          <div class="col-md-6">
            <div class="copyright">
              &copy; <?php echo e(date('Y')); ?> <a href="" target="_blank"><?php echo e(config('app.name')); ?></a>.
            </div>
          </div>
          <div class="col-md-6">
            <ul class="nav nav-footer justify-content-end">
             
                    
              <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $namePage= explode('-',$page->title); ?>
                <li class="nav-item">
                  <a href="/blog/<?php echo e($page->slug); ?>" class="nav-link" target="_blank"><?php echo e(count($namePage)>1 ? '': $page->title); ?></a>
                </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        </div>
      </div>
    </footer><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/footer.blade.php ENDPATH**/ ?>