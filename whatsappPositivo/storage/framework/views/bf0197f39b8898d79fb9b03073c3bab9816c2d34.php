<!-- Modal -->
<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('whatsapp.modal_title')); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo e(route('newrestaurant.register')); ?>" class="d-flex flex-column mb-5 mb-lg-0">
          <input class="form-control" type="text" name="name" placeholder="<?php echo e(__('whatsapp.modal_input_name')); ?>" required>
          <input class="form-control my-3" type="email" name="email" placeholder="<?php echo e(__('whatsapp.modal_input_email')); ?>" required>
          <input class="form-control my-1" type="text" name="phone" placeholder="<?php echo e(__('whatsapp.modal_input_phone')); ?>" required>
          <button disabled class="btn btn-success my-3" id="submitRegister" type="submit"><?php echo e(__('whatsapp.join_now')); ?></button>

          <div class="form-check"><input type="checkbox" name="termsCheckBox" id="termsCheckBox" class="form-check-input"> 
            <label for="terms" class="form-check-label">
              &nbsp;&nbsp;<?php echo e(__('whatsapp.i_agree_to')); ?>

              <a href="<?php echo e(config('settings.link_to_ts')); ?>" target="_blank" style="text-decoration: underline;"><?php echo e(__('whatsapp.terms_of_service')); ?></a> <?php echo e(__('whatsapp.and')); ?>

              <a href="<?php echo e(config('settings.link_to_pr')); ?>" target="_blank" style="text-decoration: underline;"><?php echo e(__('whatsapp.privacy_policy')); ?></a>.
            </label>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><?php /**PATH /home/positivo/whatsapp.positivo.co/resources/views/social/partials/modals.blade.php ENDPATH**/ ?>