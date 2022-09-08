<?php $__env->startSection('admin_title'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="header bg-primary pb-6 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0"><?php echo e(__('Landing Page')); ?></h3>
                </div>
                <div class="col text-right">
                  <div class="dropdown">
                    <a href="#" class="btn btn-default btn-sm dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                        <img src="<?php echo e(asset('images')); ?>/icons/flags/<?php echo e(strtoupper(config('app.locale'))); ?>.png" /> <?php echo e($currentLanguage); ?>

                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        <?php $__currentLoopData = $availableLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languageKey => $languageName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a class="dropdown-item" href="?lang=<?php echo e(strtolower($languageKey)); ?>">
                                <img src="<?php echo e(asset('images')); ?>/icons/flags/<?php echo e(strtoupper($languageKey)); ?>.png" /> <?php echo e($languageName); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                </div>
              </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name"><?php echo e(__('Sections')); ?></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th scope="row">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a href="<?php echo e(route('admin.landing.'.strtolower($section).'.index')); ?>"><span class="name mb-0 text-sm"><?php echo e(__($section)); ?></span></a>
                                </div>
                            </div>
                        </th>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="<?php echo e(route('admin.landing.'.strtolower($section).'.index')); ?>"><?php echo e(__('Edit')); ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
     
      </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', ['title' => __('Orders')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/landing/index.blade.php ENDPATH**/ ?>