<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_profit'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("profits/edit_profit/" . $other_profit->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <?php if ($Owner || $Admin) { ?>

                <div class="form-group">
                    <?= lang("date", "date"); ?>
                    <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->sma->hrld($other_profit->date)), 'class="form-control datetime" id="date" required="required"'); ?>
                </div>
            <?php } ?>

            <div class="form-group">
                <?= lang("reference", "reference"); ?>
                <?= form_input('reference', (isset($_POST['reference']) ? $_POST['reference'] : $other_profit->reference), 'class="form-control tip" id="reference" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('category', 'category'); ?>
                <?php
                $ct[''] = lang('select').' '.lang('category');
                foreach ($categories as $category) {
                    $ct[$category->id] = $category->name;
                }
                ?>
                <?= form_dropdown('category', $ct, set_value('category', $other_profit->category_id), 'class="form-control tip" id="category"'); ?>
            </div>

            <div class="form-group">
                <?= lang("amount", "amount"); ?>
                <input name="amount" type="text" id="amount" value="<?= $this->sma->formatDecimal($other_profit->amount); ?>"
                       class="pa form-control kb-pad amount" required="required"/>
            </div>

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $other_profit->note), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_profit', lang('edit_profit'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
    });
</script>
