<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cogs"></i><?= lang('pos_settings'); ?></h2>
        <?php if(isset($pos->purchase_code) && ! empty($pos->purchase_code) && $pos->purchase_code != 'purchase_code') { ?>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('pos/updates') ?>" class="toggle_down"><i
                            class="icon fa fa-upload"></i><span class="padding-right-10"><?= lang('updates'); ?></span></a>
                </li>
            </ul>
        </div>
        <?php } ?>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('update_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'pos_setting');
                echo form_open("pos/settings", $attrib);
                ?>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('pos_config') ?></legend>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('pro_limit', 'limit'); ?>
                            <?= form_input('pro_limit', $pos->pro_limit, 'class="form-control" id="limit" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('delete_code', 'pin_code'); ?>
                            <?= form_password('pin_code', $pos->pin_code, 'class="form-control" pattern="[0-9]{4,8}"id="pin_code"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_category', 'default_category'); ?>
                            <?php
                            $ct[''] = lang('select').' '.lang('default_category');
                            foreach ($categories as $catrgory) {                                
                                if($catrgory->status ==1){
                                $ct[$catrgory->id] = $catrgory->name;
                            }}
                            echo form_dropdown('category', $ct, $pos->default_category, 'class="form-control" id="default_category" required="required" style="width:100%;"');
                            ?>
                            
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_biller', 'default_biller'); ?>
                            <?php
                            $bl[0] = "";
                            foreach ($billers as $biller) {
                                $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                            }
                            if (isset($_POST['biller'])) {
                                $biller = $_POST['biller'];
                            } else {
                                $biller = "";
                            }
                            echo form_dropdown('biller', $bl, $pos->default_biller, 'class="form-control" id="default_biller" required="required" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_customer', 'customer1'); ?>
                            <?= form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : $pos->default_customer), 'id="customer1" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control" style="width:100%;"'); ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('display_time', 'display_time'); ?>
                            <?php
                            $yn = array('1' => lang('yes'), '0' => lang('no'));
                            echo form_dropdown('display_time', $yn, $pos->display_time, 'class="form-control" id="display_time" required="required"');
                            ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('onscreen_keyboard', 'keyboard'); ?>
                            <?php
                            echo form_dropdown('keyboard', $yn, $pos->keyboard, 'class="form-control" id="keyboard" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('product_button_color', 'product_button_color'); ?>
                            <?php $col = array('default' => lang('default'), 'primary' => lang('primary'), 'info' => lang('info'), 'warning' => lang('warning'), 'danger' => lang('danger'));
                            echo form_dropdown('product_button_color', $col, $pos->product_button_color, 'class="form-control" id="product_button_color" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('tooltips', 'tooltips'); ?>
                            <?php
                            echo form_dropdown('tooltips', $yn, $pos->tooltips, 'class="form-control" id="tooltips" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('rounding', 'rounding'); ?>
                            <?php
                            $rnd = array('0' => lang('disable'), '1' => lang('to_nearest_005'), '2' => lang('to_nearest_050'), '3' => lang('to_nearest_number'), '4' => lang('to_next_number'));
                            echo form_dropdown('rounding', $rnd, $pos->rounding, 'class="form-control" id="rounding" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('item_order', 'item_order'); ?>
                            <?php $oopts = array(0 => lang('default'), 1 => lang('category')); ?>
                            <?= form_dropdown('item_order', $oopts, $pos->item_order, 'class="form-control" id="item_order" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('after_sale_page', 'after_sale_page'); ?>
                            <?php $popts = array(0 => lang('receipt'), 1 => lang('pos')); ?>
                            <?= form_dropdown('after_sale_page', $popts, $pos->after_sale_page, 'class="form-control" id="after_sale_page" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('enable_java_applet', 'enable_java_applet'); ?>
                            <?= form_dropdown('enable_java_applet', $yn, $pos->java_applet, 'class="form-control" id="enable_java_applet" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_waiter', 'default_waiter'); ?>
                            <?php
                                $waiters[0] = lang('no_waiter');
                                ksort($waiters);
                            ?>
                            <?= form_dropdown('default_waiter', $waiters, !empty($pos->default_waiter) ? $pos->default_waiter : 0, 'class="form-control" id="default_waiter" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="jac" class="col-md-12" style="display: none;">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('receipt_printer', 'rec1'); ?>
                                <?= form_input('receipt_printer', $pos->receipt_printer, 'class="form-control tip" id="rec1"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('char_per_line', 'char_per_line'); ?>
                                <?= form_input('char_per_line', $pos->char_per_line, 'class="form-control tip" id="char_per_line" placeholder="' . lang('char_per_line') . '"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('cash_drawer_codes', 'cash1'); ?>
                                <?= form_input('cash_drawer_codes', $pos->cash_drawer_codes, 'class="form-control tip" id="cash1" placeholder="Hex value (x1C)"'); ?>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('pos_list_printers', 'pos_printers'); ?>
                                <?= form_input('pos_printers', $pos->pos_printers, 'class="form-control tip" id="pos_printers"'); ?>
                            </div>
                        </div>
                        <div class="well well-sm">
                            <p>Please add <strong><?= base_url() ?></strong> to your java Exception Site List under
                                Security tab.</p>

                            <p><strong>Access Java Control Panel</strong></p>
                            <pre><strong>Windows:</strong> Control Panel > (Java Icon) Java > Security tab > Exception Site List > Edit Site List > add<br><strong>Mac:</strong> System Preferences > (Java Icon) Java > Security tab > Exception Site List > Edit Site List > add</pre>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('custom_fileds') ?></legend>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_title1', 'tcf1'); ?>
                            <?= form_input('cf_title1', $pos->cf_title1, 'class="form-control tip" id="tcf1"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_value1', 'vcf1'); ?>
                            <?= form_input('cf_value1', $pos->cf_value1, 'class="form-control tip" id="vcf1"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_title2', 'tcf2'); ?>
                            <?= form_input('cf_title2', $pos->cf_title2, 'class="form-control tip" id="tcf2"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_value2', 'vcf2'); ?>
                            <?= form_input('cf_value2', $pos->cf_value2, 'class="form-control tip" id="vcf2"'); ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('shortcuts') ?></legend>
                    <p><?= lang('shortcut_heading') ?></p>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('focus_add_item', 'focus_add_item'); ?>
                            <?= form_input('focus_add_item', $pos->focus_add_item, 'class="form-control tip" id="focus_add_item"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('add_manual_product', 'add_manual_product'); ?>
                            <?= form_input('add_manual_product', $pos->add_manual_product, 'class="form-control tip" id="add_manual_product"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('customer_selection', 'customer_selection'); ?>
                            <?= form_input('customer_selection', $pos->customer_selection, 'class="form-control tip" id="customer_selection"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('add_customer', 'add_customer'); ?>
                            <?= form_input('add_customer', $pos->add_customer, 'class="form-control tip" id="add_customer"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('toggle_category_slider', 'toggle_category_slider'); ?>
                            <?= form_input('toggle_category_slider', $pos->toggle_category_slider, 'class="form-control tip" id="toggle_category_slider"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('toggle_subcategory_slider', 'toggle_subcategory_slider'); ?>
                            <?= form_input('toggle_subcategory_slider', $pos->toggle_subcategory_slider, 'class="form-control tip" id="toggle_subcategory_slider"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('toggle_brands_slider', 'toggle_brands_slider'); ?>
                            <?= form_input('toggle_brands_slider', $pos->toggle_brands_slider, 'class="form-control tip" id="toggle_brands_slider"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('cancel_sale', 'cancel_sale'); ?>
                            <?= form_input('cancel_sale', $pos->cancel_sale, 'class="form-control tip" id="cancel_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('suspend_sale', 'suspend_sale'); ?>
                            <?= form_input('suspend_sale', $pos->suspend_sale, 'class="form-control tip" id="suspend_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('print_items_list', 'print_items_list'); ?>
                            <?= form_input('print_items_list', $pos->print_items_list, 'class="form-control tip" id="print_items_list"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('finalize_sale', 'finalize_sale'); ?>
                            <?= form_input('finalize_sale', $pos->finalize_sale, 'class="form-control tip" id="finalize_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('today_sale', 'today_sale'); ?>
                            <?= form_input('today_sale', $pos->today_sale, 'class="form-control tip" id="today_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('open_hold_bills', 'open_hold_bills'); ?>
                            <?= form_input('open_hold_bills', $pos->open_hold_bills, 'class="form-control tip" id="open_hold_bills"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('close_register', 'close_register'); ?>
                            <?= form_input('close_register', $pos->close_register, 'class="form-control tip" id="close_register"'); ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('payment_gateways') ?></legend>
                    <?php
                    if ($paypal_balance) {
                        if (! isset ($paypal_balance['error']) ) {
                            echo '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button><strong>' . lang('paypal_balance') . '</strong><p>';
                            $blns = sizeof($paypal_balance['amount']);
                            $r = 1;
                            foreach ($paypal_balance['amount'] as $balance) {
                                echo lang('balance') . ': ' . $balance['L_AMT'] . ' (' . $balance['L_CURRENCYCODE'] . ')';
                                if ($blns != $r) {
                                    echo ', ';
                                }
                                $r++;
                            }
                            echo '</p></div>';
                        } else {
                            echo '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><p>';
                            foreach ($paypal_balance['message'] as $msg) {
                                echo $msg['L_SHORTMESSAGE'].' ('.$msg['L_ERRORCODE'].'): '.$msg['L_LONGMESSAGE'].'<br>';
                            }
                            echo '</p></div>';
                        }
                    }
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('paypal_pro', 'paypal_pro'); ?>
                            <?= form_dropdown('paypal_pro', $yn, $pos->paypal_pro, 'class="form-control" id="paypal_pro" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="paypal_pro_con">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('APIUsername', 'APIUsername'); ?>
                                <?= form_input('APIUsername', $APIUsername, 'class="form-control tip" id="APIUsername"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('APIPassword', 'APIPassword'); ?>
                                <?= form_input('APIPassword', $APIPassword, 'class="form-control tip" id="APIPassword"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('APISignature', 'APISignature'); ?>
                                <?= form_input('APISignature', $APISignature, 'class="form-control tip" id="APISignature"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                    if ($stripe_balance) {
                        echo '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button><strong>' . lang('stripe_balance') . '</strong>';
                        echo '<p>' . lang('pending_amount') . ': ' . $stripe_balance['pending_amount'] . ' (' . $stripe_balance['pending_currency'] . ')';
                        echo ', ' . lang('available_amount') . ': ' . $stripe_balance['available_amount'] . ' (' . $stripe_balance['available_currency'] . ')</p>';
                        echo '</div>';
                    }
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('stripe', 'stripe'); ?>
                            <?= form_dropdown('stripe', $yn, $pos->stripe, 'class="form-control" id="stripe" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="stripe_con">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('stripe_secret_key', 'stripe_secret_key'); ?>
                                <?= form_input('stripe_secret_key', $stripe_secret_key, 'class="form-control tip" id="stripe_secret_key"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('stripe_publishable_key', 'stripe_publishable_key'); ?>
                                <?= form_input('stripe_publishable_key', $stripe_publishable_key, 'class="form-control tip" id="stripe_publishable_key"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('authorize', 'authorize'); ?>
                            <?= form_dropdown('authorize', $yn, $pos->authorize, 'class="form-control" id="authorize" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="authorize_con">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('api_login_id', 'api_login_id'); ?>
                                <?= form_input('api_login_id', $api_login_id, 'class="form-control tip" id="api_login_id"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('api_transaction_key', 'api_transaction_key'); ?>
                                <?= form_input('api_transaction_key', $api_transaction_key, 'class="form-control tip" id="api_transaction_key"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </fieldset>

                <?= form_submit('update_settings', lang('update_settings'), 'class="btn btn-primary"'); ?>

                <?= form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#pos_setting').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 7});
        // fields = $('.form-control');
        // $.each(fields, function () {
        //     var id = $(this).attr('id');
        //     var iname = $(this).attr('name');
        //     var iid = '#' + id;
        //     if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
        //         $("label[for='" + id + "']").append(' *');
        //         $(document).on('change', iid, function () {
        //             $('#pos_setting').bootstrapValidator('revalidateField', iname);
        //         });
        //     }
        // });
        $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('#customer1').val('<?= $pos->default_customer; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customers/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

        $('#enable_java_applet').change(function () {
            var ja = $(this).val();
            if (ja == 1) {
                $('#jac').slideDown();
            } else {
                $('#jac').slideUp();
            }
        });
        var ja = '<?=$pos->java_applet?>';
        if (ja == 1) {
            $('#jac').slideDown();
        } else {
            $('#jac').slideUp();
        }
        $('#paypal_pro').change(function () {
            var pp = $(this).val();
            if (pp == 1) {
                $('#paypal_pro_con').slideDown();
            } else {
                $('#paypal_pro_con').slideUp();
            }
        });
        $('#stripe').change(function () {
            var st = $(this).val();
            if (st == 1) {
                $('#stripe_con').slideDown();
            } else {
                $('#stripe_con').slideUp();
            }
        });
        $('#authorize').change(function () {
            var st = $(this).val();
            if (st == 1) {
                $('#authorize_con').slideDown();
            } else {
                $('#authorize_con').slideUp();
            }
        });
        var st = '<?=$pos->stripe?>';
        var pp = '<?=$pos->paypal_pro?>';
        var az = '<?=$pos->authorize?>';
        if (st == 1) {
            $('#stripe_con').slideDown();
        } else {
            $('#stripe_con').slideUp();
        }
        if (pp == 1) {
            $('#paypal_pro_con').slideDown();
        } else {
            $('#paypal_pro_con').slideUp();
        }
        if (st == 1) {
            $('#authorize_con').slideDown();
        } else {
            $('#authorize_con').slideUp();
        }

    });
</script>
