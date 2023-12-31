<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_tip_rate'); ?></h4>
        </div>
        <?php echo form_open("system_settings/edit_tip_rate/" . $id); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', $tip_rate->name, 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>

                <div
                    class="controls"> <?php echo form_input('code', $tip_rate->code, 'class="form-control" id="code"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="rate"><?php echo $this->lang->line("tip_rate"); ?></label>

                <div
                    class="controls"> <?php echo form_input('rate', $tip_rate->rate, 'class="form-control" id="rate" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label for="type"><?php echo $this->lang->line("type"); ?></label>

                <div class="controls"> <?php $type = array('1' => lang('percentage'), '2' => lang('fixed'));
                    echo form_dropdown('type', $type, $tip_rate->type, 'class="form-control" id="type" required="required"'); ?> </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_tip_rate', lang('edit_tip_rate'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>