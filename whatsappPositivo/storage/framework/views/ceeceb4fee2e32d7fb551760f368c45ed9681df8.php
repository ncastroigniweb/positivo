<div class="row">
    <div class="col-md-3">
        <?php if(isset($coupon)): ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter code name",'required'=>true, 'value'=>$coupon->name], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter code name",'required'=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
    <div class="col-md-3">
        <?php if(isset($coupon)): ?>
            <?php echo $__env->make('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Fixed', 'Percentage'],'required'=>true, 'value'=>$coupon->type], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Fixed', 'Percentage'],'required'=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
    <div class="col-md-3">
        <?php if(isset($coupon) && $coupon->type == 0): ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency'), 'value'=>$coupon->price], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value', 'value'=>$coupon->price], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(isset($coupon) && $coupon->type == 1): ?>
        <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency'), 'value'=>$coupon->price], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value','value'=>$coupon->price], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="input-daterange datepicker row align-items-center" style="margin-left: 15px;">
           <div class="form-group">
                <label class="form-control-label"><?php echo e(__('Active from')); ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <?php if(isset($coupon)): ?>
                        <input name="active_from" class="form-control" placeholder="<?php echo e(__('Active from')); ?>" value="<?php echo e(old('active_from', $coupon->active_from)); ?>" type="text">
                    <?php else: ?>
                        <input name="active_from" class="form-control" placeholder="<?php echo e(__('Active from')); ?>" type="text">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-daterange datepicker row align-items-center" style="margin-left: 15px;">
           <div class="form-group">
                <label class="form-control-label"><?php echo e(__('Active to')); ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <?php if(isset($coupon)): ?>
                        <input name="active_to" class="form-control" placeholder="<?php echo e(__('Active to')); ?>" value="<?php echo e(old('active_to', $coupon->active_to)); ?>" type="text">
                    <?php else: ?>
                        <input name="active_to" class="form-control" placeholder="<?php echo e(__('Active to')); ?>" type="text">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <?php if(isset($coupon)): ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number','name'=>"Limit number",'id'=>"limit_to_num_uses",'placeholder'=>"Limit number to use",'required'=>true, 'value'=>$coupon->limit_to_num_uses, 'step'=>1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('partials.input',['class'=>"col-12", 'type'=>'number','name'=>"Limit number",'id'=>"limit_to_num_uses",'placeholder'=>"Limit number to use",'required'=>true, 'step'=>1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/coupons/form.blade.php ENDPATH**/ ?>