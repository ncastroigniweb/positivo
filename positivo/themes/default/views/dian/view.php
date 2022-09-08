
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cog"></i><?= lang('dian_settings'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('update_info'); ?></p>
                <main role="main" class="container">
                    <div class="media text-muted pt-3">
                        <div class="row">
                            <div class="post-dian">
                                <svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32"><title>Placeholder</title><rect width="100%" height="100%" fill="#e83e8c"></rect><text x="50%" y="50%" fill="#e83e8c" dy=".3em">32x32</text>
                                </svg>
                            </div>

                            <div class="post-dian-m">
                                <h5 class="d-block text-gray-dark"><strong>@<?= $username?></strong></h5>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                    <?= lang('start-message')?>
                                    <a href="<?= site_url('system_settings') ?>"><?= lang('system_settings')?></a>.
                                </p>
                            </div>

                        </div>
                    </div>

                </main>

                <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("dian/update", $attrib);
                ?>

                <div class="row">
                    <div class="col-lg-12">
                         <!---mensajes respuesta api--->
                        <a href="#" data-toggle="modal" data-target="#staticBackdrop">
                            <div class="col-md-3 msg-fact-api <?=$msg_api ?>">
                                        <div class="pre-dian" id="bor-api">
                                            <div class="body-d msg-api">
                                                <div class="dto-d">
                                                        <ul class="list-group">
                                                            <li class="d-flex justify-content-between align-items-center">
                                                                <kbd class="ws-<?=$getsoap["Result"]['code']?>">
                                                                    <?=$getsoap["Result"]['code']?>
                                                                </kbd>
                                                            </li>
                                                            <li class="d-flex justify-content-between align-items-center">
                                                                <kbd>
                                                                       <abbr title="<?=utf8_encode( $getsoap["Result"]['success'])?>">
                                                                          <?=lang("message")?>
                                                                       </abbr>
                                                                </kbd>
                                                            </li>
                                                            <li class="d-flex justify-content-between align-items-center">
                                                                <kbd>
                                                                     <small><?=$getsoap["Result"]['transaccionID']?></small>
                                                                </kbd>
                                                            </li>
                                                            <?php
                                                            if (($getsoap["Result"]['error'])!="") {?>
                                                                <li class="d-flex justify-content-between align-items-center" >

                                                                    <kbd>
                                                                        <abbr title="<?= utf8_encode( $getsoap["Result"]['error'])?>">
                                                                            <?=lang("error")?>
                                                                        </abbr>
                                                                     </kbd>
                                                                </li>
                                                            <?php  }
                                                            ?>

                                                        </ul>
                                                </div>
                                            </div>
                                    </div>
                            </div>
                        </a>

                        <!---status document---->
                    <?php if($getsoap['Result']['error']==""){?>
                        <div class="col-md-3 msg-fact-api <?=$msg_api ?>">
                            <div class="pre-dian" id="bor-api">
                                <div class="body-d msg-api">
                                    <div class="dto-d">
                                        <ul class="list-group">
                                            <?php foreach ($getStatusFile["Result"] as $result) {
                                                if ($result) {
                                            ?>
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <kbd class="ws-<?=$result?>">
                                                            <?=$result?>
                                                        </kbd>
                                                    </div>
                                                </li>
                                            <?php }}?>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                        <!----fin mensajes api-->
                        <div class="col-lg-10">

                        <div class="container cont-dian">
                            <h6><?=lang("dian")?></h6>
                            <div class="body-dian" >
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="environments"><?= lang("environments"); ?>
                                    </label>
                                    <a tabindex="0" class="help-input"data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("environments"); ?>" data-content="<?=lang("m-enviroment")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <?php $env= array('2' =>lang("tests"),'1'=>lang("production") ); ?>
                                        <?php echo form_dropdown('environments', $env ,$settings_dian[0]->environment, 'class="form-control tip sel-dian" required="required" id="environments"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="overselling"><?= lang("document_type"); ?></label>
                                    <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("document_type"); ?>" data-content="<?=lang("m-document_type")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <?php
                                        $opt = array("INVOIC" => lang('invoic'), "ND" => lang('nd'),"NC"=> lang("nc"));
                                        echo form_dropdown('document_type', $opt, $settings_dian[0]->document_type, 'class="form-control tip sel-dian" id="overselling" required="required" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="ubl_version"><?= lang("ubl_version"); ?></label>
                                    <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("ubl_version"); ?>" data-content="<?=lang("m-ubl_version")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>

                                    <div class="controls">
                                        <?php
                                        $ref = array("UBL 2.1" => "UBL 2.1", "UBL 2.0" => "UBL 2.0");
                                        echo form_dropdown('ubl_version', $ref, $settings_dian[0]->ubl_version, 'class="form-control tip sel-dian" required="required" id="ubl_version" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                     <label class="control-label"
                                           for="ubl_version"> <?= lang("format_version"); ?></label>
                                    <?php $tr['0'] = lang("disable");
                                    $fv= array('DIAN 2.1' =>'DIAN 2.1' );
                                    echo form_dropdown('format_version', $fv, $settings_dian[0]->format_version, 'id="format_version" class="form-control tip sel-dian" required="required" style="width:100%;"'); ?>
                                </div>
                            </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="dian_num"><?= lang("dian_num"); ?></label>
                                    <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("dian_num"); ?>" data-content="<?=lang("m-dian_num")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <?php
                                        echo form_input('dian_num', $settings_dian[0]->dian_num, ' id="dian_num" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="'.lang("example").' 201911110152"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="start_dian"><?= lang("start_dian"); ?></label>
                                    <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("start_dian"); ?>" data-content="<?=lang("m-start_dian")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <input type="date" value="<?=$settings_dian[0]->start_dian?>" name="start_dian"  id="start_dian" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="end_dian"><?= lang("end_dian"); ?></label>
                                     <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("end_dian"); ?>" data-content="<?=lang("m-end_dian")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <input type="date" value="<?=$settings_dian[0]->end_dian?>" name="end_dian"  id="end_dian" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="min_number"><?= lang("min_number"); ?></label>
                                    <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("min_number"); ?>" data-content="<?=lang("m-min_number")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <?php
                                        echo form_input('min_number', $settings_dian[0]->min_number, ' id="min_number" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="'.lang("example").' 1"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="max_number"><?= lang("max_number"); ?></label>
                                     <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("max_number"); ?>" data-content="<?=lang("m-max_number")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <div class="controls">
                                        <?php
                                        echo form_input('max_number', $settings_dian[0]->max_number, ' id="max_number" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="'.lang("example").' 5000000"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                     <label class="control-label" for="billing"> <?=lang('billing')?></label>
                                     <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("billing"); ?>" data-content="<?=lang("m-billing")?>">
                                        <i class="fa fa-question " aria-hidden="true"></i>
                                    </a>
                                    <?php
                                    $tf= array('0' =>lang('automatic'),'1'=>lang('handbook') );
                                    echo form_dropdown('billing', $tf,$settings_dian[0]->billing,  'id="billing" class="form-control tip sel-dian" required="required" style="width:100%;"'); ?>
                                </div>
                            </div>

                            </div>
                        </div>
                    <div class="container cont-dian">
                        <h6><?=lang("setting")?></h6>
                            <div class="body-dian" >
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="sales_invoice"><?= lang("sales_invoice"); ?></label>

                                        <div class="controls">
                                            <?php
                                            $si=array('01'=>lang("national"),'02'=>lang("export"),'03'=>lang("invoice_contingency"),'04'=>lang("dian_contingency"),'91'=>lang("nc"),'92'=>lang("nd"));
                                            echo form_dropdown('sales_invoice', $si, $settings_dian[0]->sales_invoice, 'id="sales_invoice" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="operation"><?= lang("operation"); ?></label>
                                        <div class="controls">
                                            <?php
                                            $op=array('10'=>lang("standard"),'09'=>lang("aiu"),'11'=>lang("mandates"));
                                            echo form_dropdown('operation', $op, $settings_dian[0]->operation, 'id="operation" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="types_person"><?= lang("types_person"); ?></label>
                                        <div class="controls">
                                            <?php
                                            $tp=array('1'=>lang("legal"),'2'=>lang("natural"));
                                            echo form_dropdown('types_person', $tp, $settings_dian[0]->types_person, 'id="types_person" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="tax_identifier"><?= lang("tax_identifier"); ?></label>
                                        <div class="controls">
                                            <?php
                                            $ti=array('31'=>lang("Colombiano"));
                                            echo form_dropdown('tax_identifier', $ti, $settings_dian[0]->tax_identifier, 'id="tax_identifier" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="responsible_iva"><?= lang("responsible_iva"); ?></label>
                                        <div class="controls">
                                            <?php
                                            $ti=array('48'=>lang("yes"),'49'=>lang("no"));
                                            echo form_dropdown('responsible_iva', $ti, $settings_dian[0]->responsible_iva, 'id="responsible_iva" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="payment_method"><?= lang("payment_method"); ?></label>
                                        <div class="controls">
                                            <?php
                                            $ti=array('1'=>lang("Efectivo"),'2'=>lang("Credito"));
                                            echo form_dropdown('payment_method', $ti, $settings_dian[0]->payment_method, 'id="payment_method" class="form-control tip sel-dian" required="required" style="width:100%;"');
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="fiscal_respon"><?= lang("fiscal_respon"); ?></label>
                                        <div class="controls">
                                            <select class="custom-select form-control sel-dian" id="fiscal_respon" required="required" name="fiscal_respon">
                                                <?php
                                                    for ($i=0; $i<count($fiscal_respon['fiscal_respon']); $i++){
                                                            if ($fiscal_respon['fiscal_respon'][$i]['cod']==$settings_dian[0]->fiscal_respon) {?>
                                                                <option value="<?=$settings_dian[0]->fiscal_respon?>"
                                                                    selected> <?=$fiscal_respon['fiscal_respon'][$i]['description']?>
                                                                </option>
                                                           <?php } ?>
                                                            <option value='<?php echo $fiscal_respon['fiscal_respon'][$i]['cod']?>'>
                                                                <?php echo $fiscal_respon['fiscal_respon'][$i]['description']?>
                                                            </option>
                                                    <?php }?>
                                            </select>
                                         </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="commer_regist"><?= lang("commer_regist"); ?></label>
                                        <div class="controls">
                                            <?php
                                            echo form_input('commer_regist', $settings_dian[0]->commer_regist, ' id="commer_regist" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="'.lang("example").' 282641"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="cod_activity"><?= lang("cod_activity"); ?></label>
                                        <a tabindex="0" class="help-input" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="<?= lang("cod_activity"); ?>" data-content="<?=lang("m-cod_activity")?>">
                                            <i class="fa fa-question " aria-hidden="true"></i>
                                        </a>
                                        <div class="controls">
                                            <?php
                                            echo form_input('cod_activity', $settings_dian[0]->cod_activity, ' id="cod_activity" class="form-control tip sel-dian" required="required" style="width:100%;" Placeholder="'.lang("example").' 7020"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="commer_regist"><?php  $docu=explode("-",$biller->docu_type); echo $docu[1]?></label>
                                        <span>
                                            <a href="<?= site_url('billers') ?>">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                        <div class="controls">
                                            <?php
                                            echo form_input('document_number', $biller->docu_num, ' id="document_number" class="form-control tip sel-dian" style="width:100%;" disabled');
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>
                        <!--   messaje preconf ----->

                        <div class="col-lg-2">
                            <div class="pre-dian <?=$visible?>" id="state_api">
                                <div class="body-d">
                                    <h6><i class="fa fa-code-fork fa-fw" aria-hidden="true"></i><?= lang("api_configuration");?></h6>
                                    <div class="dto-d">
                                        <?php $visible_api="";?>
                                        <ul class="list-group lis-api <?=$visible_api?>">
                                            <li class="d-flex justify-content-between align-items-center"><small> <?=lang("enable")?></small></li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <small><?= lang("api_url")?>
                                                    <kbd>
                                                        <a href="<?= $api->api_url?>" target="_blank">
                                                            <abbr title="<?= $api->api_url?>"><?= lang("dian")?>
                                                            </abbr>
                                                        </a>
                                                    </kbd>
                                                </small>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <small><?= lang("service")?> <?= $api->service?></small>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <small><?= $api->proxyhost?></small>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <small><?= $api->proxyport?></small>
                                            </li>
                                            <li class="d-flex justify-content-between align-items-center">
                                                <small><?=lang("username")?> <?= $api->username?></small>
                                            </li>
                                        </ul>
                                    </div>
                                    <span>
                                        <a href="#" data-toggle="modal" data-target="#apiDianModal">
                                            <i class="fa fa-cog fa-fw" aria-hidden="true"></i>
                                         </a>
                                        <?= $state_btn_api?>
                                    </span>

                                </div>
                            </div>
                            <div class="pre-dian">
                                <div class="body-d">
                                    <h6><i class="fa fa-tag fa-fw" aria-hidden="true"></i><?= lang("tribute")?>XXXX</h6>
                                    <?php ;
                                        foreach ($tax_rates as $rate) {
                                            if($rate->id==$Settings->default_tax_rate2)
                                            {
                                                $tr=$rate->code."-".$rate->name;
                                            }

                                    }?>
                                    <div class="dto-d">
                                        <span>
                                            <?=$tr?>
                                            <a href="<?= site_url('system_settings') ?>">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" name="tribute" value="<?=$tr?>">
                            </div>
                            <div class="pre-dian">
                                <div class="body-d">
                                    <h6><i class="fa fa-usd fa-fw" aria-hidden="true"></i><?= lang("badge")?></h6>
                                    <?php ;
                                        foreach ($currencies as $currency) {
                                            if($currency->code==$Settings->default_currency)
                                            {
                                                $cu= $currency->code;
                                            }

                                        }
                                     ?>
                                    <div class="dto-d">
                                        <span>
                                            <?=$cu?>
                                            <a href="<?= site_url('system_settings') ?>">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" name="badge" value="<?=$cu?>">
                            </div>
                            <div class="pre-dian">
                                <div class="body-d">
                                    <h6><i class="fa fa-thumb-tack fa-fw" aria-hidden="true"></i><?= lang("billing_prefix");?></h6>
                                    <div class="dto-d">
                                        <span>
                                            <?=$Settings->sales_prefix?>
                                            <a href="<?= site_url('system_settings') ?>">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" name="billing_prefix" value="<?=$Settings->sales_prefix?>">
                            </div>
                            <div class="pre-dian">
                                <div class="body-d">
                                    <h6><i class="fa fa-hourglass-end fa-fw" aria-hidden="true"></i><?= lang("measure");?></h6>
                                    <div class="dto-d">
                                        <span>
                                            <?=lang("unit")?>
                                            <a href="">
                                                <i class="fa fa fa-code fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="pre-dian">
                                <div class="body-d">
                                    <h6><i class="fa fa-map-signs fa-fw" aria-hidden="true"></i><?= lang("order_ref");?></h6>
                                    <div class="dto-d">
                                        <span>
                                            <?=$settings_dian[0]->current_number-1?>
                                            <a href="">
                                                <i class="fa fa-ticket fa-fw" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!---End messajes preconf--->

                </div>
            </div>
            <div style="clear: both; height: 10px;"></div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class="controls">
                        <button type="submit" class="btn-env"><?=lang("update_settings")?></button>
                        <?php// echo form_submit('update_settings', lang("update_settings"), 'class="btn btn-primary btn-lg btn-block"'); ?>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</div>
</div>
<!-- Modal config api-dian -->
<div class="modal fade" id="apiDianModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=lang("api_configuration")?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class'=>'form_api',);
                echo form_open_multipart("dian/addDianApi", $attrib);
                ?>
        <div class="row">
            <div class="col-lg-12 <?=$visible_api?>">
                <div class="col-md-12" id="cod-api">
                    <kbd>
                         <i class="fa fa-code" aria-hidden="true"></i>
                         <?=$api->api_url?>
                         <a href="<?=$api->api_url?>" target="_blank" title="<?= lang("api_url"); ?>">
                            <i class="fa fa-external-link" aria-hidden="true"></i>
                        </a>
                    </kbd>
                </div>
            </div>
            <div class="col-lg-12">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="api_url"><?= lang("api_url"); ?></label>
                                <div class="controls">
                                    <?php
                                    echo form_input('api_url', $api->api_url, ' id="api_url" class="form-control tip" required="required" style="width:100%;" Placeholder="https://"');
                                    ?>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="service"><?= lang("service"); ?></label>
                                <div class="controls">
                                    <?php
                                    echo form_input('service', $api->service,'id="service" class="form-control tip" required="required" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="username"><?= lang("username"); ?>
                                 </label>
                                <div class="controls">
                                    <?php
                                    echo form_input('username', $api->username, 'id="username" class="form-control tip" required="required" style="width:100%;"');
                                    ?>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="password"><?= lang("password"); ?></label>
                                <div class="controls">
                                    <?php
                                    echo form_input('password', $api->password, ' id="password" class="form-control tip" required="required" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="proxyhost"><?= lang("proxyhost"); ?></label>
                                <div class="controls">
                                    <?php
                                    echo form_input('proxyhost', $api->proxyhost, ' id="proxyhost" class="form-control tip"  style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="proxyport"><?= lang("proxyport"); ?></label>
                                <div class="controls">
                                    <?php
                                    echo form_input('proxyport', $api->proxyport, ' id="proxyport" class="form-control tip"  style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        </div>



            </div>
            <div class="col-lg-12 <?=$visible_api?>">
                <div class="col-md-12" id="cod-api">
                    <kbd>
                         <i class="fa fa-code" aria-hidden="true"></i>
                         <?= lang("layout_xml");?>
                         <a href="<?= base_url()."venta.xml" ?>" target="_blank" title="<?= lang("layout_xml"); ?>">
                            <i class="fa fa-external-link" aria-hidden="true"></i>
                        </a>
                    </kbd>
                </div>
            </div>
      </div>
      <hr/>
        <?php echo form_submit('activate_billing', $state_btn_api, 'class="btn btn-primary btn-lg btn-block"'); ?>
    <?php echo form_close(); ?>

<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form','class'=>'form_api '.$visible_api.'');
                echo form_open_multipart("dian/deleteDian_api", $attrib);
                ?>
                <input type="hidden" name="api_flag" value="<?=$api->id?>" <?=$api_flag?> >
        <?php echo form_submit('delete', lang("delete"), 'class="btn btn-danger btn-lg btn-block"'); ?>
        <?php echo form_close(); ?>

        <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal"><?=lang("close")?></button>

    </div>

  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade control-label" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><?= lang('status')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
           <?= utf8_encode( ($getsoap['Result']['error'])!=""?"<kbd>".lang('error')." ".$getsoap['Result']['code']."</kbd>"." ".$getsoap['Result']['error']: "<kbd>".$getsoap['Result']['code']."</kbd>"." ".$getsoap['Result']['success'])?>
        </p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('close')?></button>
      </div>
    </div>
  </div>
</div>
<!--- end modal--->





