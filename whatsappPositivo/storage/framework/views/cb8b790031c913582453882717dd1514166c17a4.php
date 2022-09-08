<?php $__env->startSection('body'); ?>

    <div class="panel w-1/2">

        <div class="panel-header">

            <?php echo e(__('translation::translation.add_language')); ?>


        </div>

        <form action="<?php echo e(route('languages.store')); ?>" method="POST">

            <fieldset>

                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                <div class="panel-body p-4">

                    <?php echo $__env->make('translation::forms.text', ['field' => 'name', 'label' => __('translation::translation.language_name'), ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <?php echo $__env->make('translation::forms.text', ['field' => 'locale', 'label' => __('translation::translation.locale'), ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>

            </fieldset>

            <div class="panel-footer flex flex-row-reverse">

                <button class="button button-blue">
                    <?php echo e(__('translation::translation.save')); ?>

                </button>

            </div>

        </form>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('translation::layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/vendor/translation/languages/create.blade.php ENDPATH**/ ?>