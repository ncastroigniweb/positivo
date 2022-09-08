<?php $__env->startSection('cardbody'); ?>
<div class="container-fluid">
    <div class="row">
    <?php $__currentLoopData = $setup['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-4 ">
            <br/>
            <a href="<?php echo e(route("admin.landing.testimonials.edit",["testimonial"=>$item->id])); ?>">
            <div class="card cardWithShadow cardWithShadowAnimated testimonialCard shadow" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Edit this testimonial')); ?>">
              <div class="card-body">
                <div class="testimonials-item-author">
                  <div class="testimonials-item-author-image-container">
                    <img class="avatar rounded-circle"
                      src='<?php echo e(str_contains($item->image, "social") ? $item->image : "/uploads/restorants/".$item->image."_large.jpg"); ?>'
                      class="testimonials-item-author-image">
                  </div>
                  <div class="testimonials-item-author-info">
                    <div class="testimonials-item-author-info-name"><?php echo e($item->title); ?></div>
                    <div class="testimonials-item-author-info-position"><span class="small"><?php echo e($item->subtitle); ?></span></div>
                  </div>
                  <div class="testimonials-item-stars">
                    <span class="testimonials-item-stars-item">
                      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="14px" height="14px"
                        viewBox="0 0 100 100">
                        <use xlink:href="#icon-star">
                          <svg viewBox="4 4 16 16" id="icon-star">
                            <path
                              d="M11.567 17.36L8.063 19.2a.93.93 0 0 1-1.35-.98l.67-3.902a.93.93 0 0 0-.268-.823l-2.834-2.763a.93.93 0 0 1 .515-1.587l3.918-.569a.93.93 0 0 0 .7-.509l1.752-3.55a.93.93 0 0 1 1.668 0l1.752 3.55a.93.93 0 0 0 .7.51l3.917.568a.93.93 0 0 1 .516 1.587l-2.835 2.763a.93.93 0 0 0-.267.823l.67 3.902a.93.93 0 0 1-1.35.98l-3.504-1.842a.93.93 0 0 0-.866 0z">
                            </path>
                          </svg>
                        </use>
                      </svg>
                    </span>
                    <span class="testimonials-item-stars-item">
                      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="14px" height="14px"
                        viewBox="0 0 100 100">
                        <use xlink:href="#icon-star"></use>
                      </svg>
                    </span>
                    <span class="testimonials-item-stars-item">
                      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="14px" height="14px"
                        viewBox="0 0 100 100">
                        <use xlink:href="#icon-star"></use>
                      </svg>
                    </span>
                    <span class="testimonials-item-stars-item">
                      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="14px" height="14px"
                        viewBox="0 0 100 100">
                        <use xlink:href="#icon-star"></use>
                      </svg>
                    </span>
                    <span class="testimonials-item-stars-item">
                      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="14px" height="14px"
                        viewBox="0 0 100 100">
                        <use xlink:href="#icon-star"></use>
                      </svg>
                    </span>
                  </div>
                </div>

                <blockquote class="blockquote tetimonial_text">
                  <p class="mb-0">
                    <?php echo e($item->description); ?>

                  </p>
                </blockquote>
              </div>
            </div>
            </a>
            <br/>
            <div class="text-center">
                <a href="<?php echo e(route("admin.landing.testimonials.delete",["testimonial"=>$item->id])); ?>" class="btn btn-danger btn-sm"><?php echo e(__('Delete')); ?></a>
            </div>
          </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('general.index', $setup, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/landing/testimonials/index.blade.php ENDPATH**/ ?>