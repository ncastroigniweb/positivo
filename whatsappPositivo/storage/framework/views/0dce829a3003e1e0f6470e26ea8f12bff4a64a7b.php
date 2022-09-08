<div class="card card-profile shadow  mt-4" id="clientBox">
    <div class="px-4">
      <div class="mt-5">
        <h3><?php echo e(__('customers_him_self')); ?><span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <?php echo $__env->make('partials.fields',
        ['fields'=>[
          ['ftype'=>'input','name'=>"Customer name",'id'=>"custom[client_name]",'placeholder'=>"Customer name",'required'=>true],
          ['ftype'=>'input','name'=>"Customer phone",'id'=>"custom[client_phone]",'placeholder'=>"Please enter phone number.",'required'=>true],
          ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
      </div>
      <br />
      <br />
    </div>
</div>
<?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/cart/newclient.blade.php ENDPATH**/ ?>