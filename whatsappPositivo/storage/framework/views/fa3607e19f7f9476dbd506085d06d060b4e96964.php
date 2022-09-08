<style>
    .masthead{
        /* background-image: none !important; */
        /* height: 500 !important;
        background-position: bottom !important; */
    }
    .display-2, .page-title{
        padding-top: 250px;
    }
</style>
<?php $__env->startSection('content'); ?>
    <header class="masthead" style="<?php echo e('background-image: url('.config('global.restorant_details_cover_image').')'); ?>">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-center">
                    
                </div>
            </div>
        </div>
    </header>
    <section class="section">
        <div class="container container-pages">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white">
                        <?php echo $page->content; ?> 
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', ['class' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/pages/show.blade.php ENDPATH**/ ?>