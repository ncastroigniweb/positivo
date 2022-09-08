<?php $__env->startSection('extrameta'); ?>
<title><?php echo e($restorant->name); ?></title>
<meta property="og:image" content="<?php echo e($restorant->logom); ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="590">
<meta property="og:image:height" content="400">
<meta name="og:title" property="og:title" content="<?php echo e($restorant->name); ?>">
<meta name="description" content="<?php echo e($restorant->description); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }
?>
<?php echo $__env->make('restorants.partials.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section class="section-profile-cover section-shaped grayscale-05 d-none d-md-none d-lg-block d-lx-block">
      <!-- Circles background -->
      <img class="bg-image" loading="lazy" src="<?php echo e($restorant->coverm); ?>" style="width: 100%;">
      <!-- SVG separator -->
      <div class="separator separator-bottom separator-skew">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </section>

    <nav class="web-menu nav flex-column tabbable sticky" style="top: 50%; padding-left: 10px; position: fixed;">
                
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Nosotros" href="https://<?php echo e($_SERVER['SERVER_NAME']); ?>/blog/<?php echo e($restorant->id); ?>-nosotros">
            <i class="fa fa-users" aria-hidden="true"></i>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Contáctenos" href="https://<?php echo e($_SERVER['SERVER_NAME']); ?>/blog/<?php echo e($restorant->id); ?>-cont-ctenos">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
            </svg>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Preguntas frecuentes" href="https://<?php echo e($_SERVER['SERVER_NAME']); ?>/blog/<?php echo e($restorant->id); ?>-preguntas-frecuentes">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question" viewBox="0 0 16 16">
            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg>
        </a>
        <a class="nav-link icon icon-shape bg-gradient-blue text-white rounded-circle shadow mt-2" target="_blank" title="Ubicación" href="https://<?php echo e($_SERVER['SERVER_NAME']); ?>/blog/<?php echo e($restorant->id); ?>-ubicaci-n">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
        </a>
    </nav>
    

    <section class="section pt-lg-0 mb--5 mt--9 d-none d-md-none d-lg-block d-lx-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white"  <?php if($restorant->description){echo 'style="border-bottom: 1px solid #f2f2f2;"';} ?> >
                        <h1  hidden style="margin-bottom: 10px;" class="display-3 text-white notranslate" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;"><?php echo e($restorant->name); ?></h1>                     
                        <p class="display-4" style="margin-top: 160px"><?php echo e($restorant->description); ?></p>
                        <p><i class="ni ni-watch-time"></i> <?php if(!empty($openingTime)): ?><span class="closed_time"><?php echo e(__('Opens')); ?> <?php echo e($openingTime); ?></span><?php endif; ?> <?php if(!empty($closingTime)): ?><span class="opened_time"><?php echo e(__('Opened until')); ?> <?php echo e($closingTime); ?></span> <?php endif; ?> |   <?php if(!empty($restorant->address)): ?><i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($restorant->address)); ?>"><span class="notranslate"><?php echo e($restorant->address); ?></span></a>  | <?php endif; ?> <?php if(!empty($restorant->phone)): ?> <i class="ni ni-mobile-button"></i> <a href="tel:<?php echo e($restorant->phone); ?>"><?php echo e($restorant->phone); ?> </a> <?php endif; ?></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <?php if(auth()->user()&&auth()->user()->hasRole('admin')): ?>
                    <?php echo $__env->make('restorants.admininfo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>
        </div>

    </section>
    <section class="section section-lg d-md-block d-lg-none d-lx-none" style="padding-bottom: 0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $__env->make('partials.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">
                        <h1 class="display-3 text notranslate" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;"><?php echo e($restorant->name); ?></h1>
                        <p class="display-4 text"><?php echo e($restorant->description); ?></p>
                        <p><i class="ni ni-watch-time"></i> <?php if(!empty($openingTime)): ?><span class="closed_time"><?php echo e(__('Opens')); ?> <?php echo e($openingTime); ?></span><?php endif; ?> <?php if(!empty($closingTime)): ?><span class="opened_time"><?php echo e(__('Opened until')); ?> <?php echo e($closingTime); ?></span> <?php endif; ?>   <?php if(!empty($restorant->address)): ?><i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($restorant->address)); ?>"><?php echo e($restorant->address); ?></a>  | <?php endif; ?> <?php if(!empty($restorant->phone)): ?> <i class="ni ni-mobile-button"></i> <a href="tel:<?php echo e($restorant->phone); ?>"><?php echo e($restorant->phone); ?> </a> <?php endif; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-lg-0" id="restaurant-content" style="padding-top: 0px">
        <input type="hidden" id="rid" value="<?php echo e($restorant->id); ?>"/>
        <div class="container container-restorant">

            
            
            <?php if(!$restorant->categories->isEmpty()): ?>
        <nav class="tabbable sticky" style="top: <?php echo e(config('app.isqrsaas') ? 64:88); ?>px;">
                <ul class="nav nav-pills bg-white mb-2">
                    <li class="nav-item nav-item-category ">
                        <a class="nav-link  mb-sm-3 mb-md-0 active" data-toggle="tab" role="tab" href=""><?php echo e(__('All categories')); ?></a>
                    </li>
                    <?php $__currentLoopData = $restorant->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!$category->items->isEmpty()): ?>
                            <li class="nav-item nav-item-category" id="<?php echo e('cat_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>">
                                <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" role="tab" id="<?php echo e('nav_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>" href="#<?php echo e(clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>"><?php echo e($category->name); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

                
            </nav>

            
            <?php endif; ?>

            


            <?php if(!$restorant->categories->isEmpty()): ?>
            <?php $__currentLoopData = $restorant->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!$category->aitems->isEmpty()): ?>
                <div id="<?php echo e(clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>" class="<?php echo e(clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>">
                    <h1><?php echo e($category->name); ?></h1><br />
                </div>
                <?php endif; ?>
                <div class="row <?php echo e(clean(str_replace(' ', '', strtolower($category->name)).strval($key))); ?>">
                    <?php $__currentLoopData = $category->aitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="strip">
                                <?php if(!empty($item->image)): ?>
                                <figure>
                                    <a onClick="setCurrentItem(<?php echo e($item->id); ?>)" href="javascript:void(0)"><img src="<?php echo e($item->logom); ?>" loading="lazy" data-src="<?php echo e(config('global.restorant_details_image')); ?>" class="img-fluid lazy" alt=""></a>
                                </figure>
                                <?php endif; ?>
                                <div class="res_title"><b><a onClick="setCurrentItem(<?php echo e($item->id); ?>)" href="javascript:void(0)"><?php echo e($item->name); ?></a></b></div>
                                <div class="res_description"><?php echo e($item->short_description); ?></div>
                                <div class="res_mimimum"><?php echo money($item->price, config('settings.cashier_currency'),config('settings.do_convertion')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <p class="text-muted mb-0"><?php echo e(__('Hmmm... Nothing found!')); ?></p>
                        <br/><br/><br/>
                        <div class="text-center" style="opacity: 0.2;">
                            <img src="https://www.jing.fm/clipimg/full/256-2560623_juice-clipart-pizza-box-pizza-box.png" width="200" height="200"></img>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Check if is installed -->
            <?php if(isset($doWeHaveImpressumApp)&&$doWeHaveImpressumApp): ?>
                
                <!-- Check if there is value -->
                <?php if(strlen($restorant->getConfig('impressum_value',''))>5): ?>
                    <h3><?php echo e($restorant->getConfig('impressum_title','')); ?></h3>
                    <?php echo $restorant->getConfig('impressum_value',''); ?>
                <?php endif; ?>
            <?php endif; ?>
            
        </div>

        <?php if(  !(isset($canDoOrdering)&&!$canDoOrdering)   ): ?>
            <div onClick="openNav()" class="callOutShoppingButtonBottom icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
                <i class="ni ni-cart"></i>
            </div>

            <div style="background-color: #20bf55; position: fixed;  bottom: 0px; left: 20px; z-index: 1000;" class="icon icon-shape text-white rounded-circle shadow mb-4">
                <a class="text-white" target="_blank" title="Whastapp" href="https://api.whatsapp.com/send?phone=<?php echo e($restorant->phone); ?>">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                </a>            
            </div>
        <?php endif; ?>

    </section>
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-header bg-transparent pb-2">
                            <h4 class="text-center mt-2 mb-3"><?php echo e(__('Call Waiter')); ?></h4>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="post" action="<?php echo e(route('call.waiter')); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo $__env->make('partials.fields',$fields, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4"><?php echo e(__('Call Now')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php if($showGoogleTranslate&&!$showLanguagesSelector): ?>
    <?php echo $__env->make('googletranslate::buttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php if($showLanguagesSelector): ?>
    <?php $__env->startSection('addiitional_button_1'); ?>
        <div class="dropdown web-menu">
            <a href="#" class="btn btn-neutral dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                <!--<img src="<?php echo e(asset('images')); ?>/icons/flags/<?php echo e(strtoupper(config('app.locale'))); ?>.png" /> --> <?php echo e($currentLanguage); ?>

            </a>
            <ul class="dropdown-menu" aria-labelledby="">
                <?php $__currentLoopData = $restorant->localmenus()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($language->language!=config('app.locale')): ?>
                        <li>
                            <a class="dropdown-item" href="?lang=<?php echo e($language->language); ?>">
                                <!-- <img src="<?php echo e(asset('images')); ?>/icons/flags/<?php echo e(strtoupper($language->language)); ?>.png" /> --> <?php echo e($language->languageName); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('addiitional_button_1_mobile'); ?>
        <div class="dropdown mobile_menu">
           
            <a type="button" class="nav-link  dropdown-toggle" data-toggle="dropdown"id="navbarDropdownMenuLink2">
                <span class="btn-inner--icon">
                  <i class="fa fa-globe"></i>
                </span>
                <span class="nav-link-inner--text"><?php echo e($currentLanguage); ?></span>
              </a>
            <ul class="dropdown-menu" aria-labelledby="">
                <?php $__currentLoopData = $restorant->localmenus()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($language->language!=config('app.locale')): ?>
                        <li>
                            <a class="dropdown-item" href="?lang=<?php echo e($language->language); ?>">
                               <!-- <img src="<?php echo e(asset('images')); ?>/icons/flags/<?php echo e(strtoupper($language->language)); ?>.png" /> ---> <?php echo e($language->languageName); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('js'); ?>
    <script>
        var CASHIER_CURRENCY = "<?php echo  config('settings.cashier_currency') ?>";
        var LOCALE="<?php echo  App::getLocale() ?>";
        var IS_POS=false;
    </script>
    <script src="<?php echo e(asset('custom')); ?>/js/order.js"></script>
    <?php echo $__env->make('restorants.phporderinterface', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php if($showGoogleTranslate&&!$showLanguagesSelector): ?>
        <?php echo $__env->make('googletranslate::scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
     <script>
        var cpId ='<?php echo e($restorant->id); ?>';
        
        var rut= "https://<?php echo e($_SERVER['SERVER_NAME']); ?>/blog/<?php echo e($restorant->id); ?>";
        $('#pg-nosotros').click(function(){
            sessionStorage.setItem("cpId", cpId);            
            window.location.href = rut+'-nosotros';           

        }); 
        $('#pg-cont-ctenos').click(function(){
            sessionStorage.setItem("cpId", cpId);             
            window.location.href = rut+'-cont-ctenos';
            
        });
        $('#pg-preguntas-frecuentes').click(function(){
            sessionStorage.setItem("cpId", cpId);             
            window.location.href = rut+'-preguntas-frecuentes';
        });
        $('#pg-ubicaci-n').click(function(){
            sessionStorage.setItem("cpId", cpId);            
            window.location.href = rut+'-ubicaci-n';
        });
    </script> 

    <script>
        $(document).ready(function(){
            var networks = JSON.parse(JSON.stringify(<?= $networks ?>)); 
            $('#facebook').attr('href',networks['Facebook']);
            $('#instagram').attr('href',networks['Instagram']);                       
        });           
    </script>

<?php $__env->stopSection(); ?>

<?php if($showGoogleTranslate&&!$showLanguagesSelector): ?>
    <?php $__env->startSection('head'); ?>
        <!-- Style  Google Translate -->
        <link type="text/css" href="<?php echo e(asset('custom')); ?>/css/gt.css" rel="stylesheet">
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php echo $__env->make('layouts.front', ['class' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/restorants/show.blade.php ENDPATH**/ ?>