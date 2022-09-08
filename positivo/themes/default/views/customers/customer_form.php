<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => $form_customer["id_form"] );
        echo form_open_multipart("customers/".$form_customer[page].$customer->id, $attrib); ?>

            <div class="modal-body">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $form_customer["name_form"]; ?></div>
                    <div class="panel-body">
                        <div class="alert alert-info alert-dismissible fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= lang('enter_info'); ?>
                        </div>
                        <div class="form-group hide">
                            <label class="control-label"
                                   for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>

                            <div class="controls"> <?php
                                foreach ($customer_groups as $customer_group) {
                                    $cgs[$customer_group->id] = $customer_group->name;
                                }
                                echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                 <!-- campos cedula-->
                                <div class="form-group">
                                    <?= lang("docu_type", "docu_type"); ?>*
                                    <div class="input-group margin-bottom-sm">
                                        <span class="input-group-addon"><i class="fa fa-gavel fa-fw"></i></span>
                                        <select class="custom-select form-control"  id="docu_type" required="required" name="docu_type">
                                                <option value="<?=$customer->docu_type?>" selected><?=$customer->docu_type?></option>
                                                <?php
                                                    for ($i=0; $i<count($docu_type['docu_type']); $i++)
                                                        {?>

                                                            <option value='<?php echo $docu_type['docu_type'][$i]['cod']."-".$docu_type['docu_type'][$i]['description']?>'>
                                                                <?php echo $docu_type['docu_type'][$i]['description']?>
                                                            </option>

                                                    <?php }?>

                                        </select>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted"><?= lang("select")."  ".lang("docu_type") ?></small>

                                </div>
                                <div class="form-group">
                                    <div>
                                        <input type="checkbox" name="responsible_for_IVA" value="<?= $state_iva["iva_val"]?>" id="iva" <?=$state_iva["val_check"]?> >
                                        <label for="iva" id="log"><?=$state_iva['msg_iva']?></label>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted msg-iva"><?=$state_iva['msg_help']?></small>
                                </div>
                                <div class="form-group" hidden>
                                    <?= lang("vat_no", "vat_no"); ?>*
                                    <input type="text" name="vat_no" value="0" required="required" class="form-control" id="vat_no">

                                </div>

                                <div class="form-group">
                                    <?= lang("id_number", "id_number"); ?>*
                                    <input type="text" name="id_number" value="<?php echo $customer->docu_num ?>" required="required" class="form-control focus tip" id="id_number"  data-bv-notempty="true" oninput="procesarPDF417(this.value);" placeholder="Ej. 1072258233">

                                </div>
                                <div class="form-group person">
                                    <?= lang("name", "name"); ?>*
                                    <?php echo form_input('name', $customer->name, ' required="required" class="form-control tip" id="name" data-bv-notempty="true"'); ?>

                                </div>
                                <div class="container  ">
                                    <div class="row">
                                        <div class=" col-md-3 form-group" >
                                            <?= lang("gender", "gender"); ?>*
                                            <?php echo form_input('gender',  $customer->gender, ' required="required" class="form-control tip " id="gender" data-bv-notempty="true" maxlength="1" placeholder="M" pattern="[A-Z]"'); ?>
                                        </div>

                                        <div  class=" col-md-3 form-group" >
                                            <?= lang("blood_type", "blood_type"); ?>*
                                            <?php echo form_input('blood_type', $customer->blood_type, 'required="required" class="form-control tip " id="blood_type" data-bv-notempty="true" maxlength="3" placeholder="A+"'); ?>
                                        </div>

                                        <div class="col-md-6 form-group ">
                                             <?= lang("born", "born");?>*
                                            <input type="text" name="born"  class="form-control tip" required="required" data-bv-notempty="true"  value="<?php echo $customer->born?>"  id="born"
                                            placeholder="19911228">
                                        </div>
                                    </div>
                                </div>

                                <!--fin campos cedula-->

                                <!--<div class="form-group company">
                                <?= lang("contact_person", "contact_person"); ?>
                                <?php //echo form_input('contact_person', '', 'class="form-control" id="contact_person" data-bv-notempty="true"'); ?>
                            </div>-->
                                <div class="form-group">
                                    <?= lang("email_address", "email_address"); ?>
                                    <input type="email" name="email" class="form-control" value="<?= $customer->email ?>"  id="email_address"/>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <?= lang("phone", "phone"); ?>
                                            <input type="tel" name="phone" value="<?= $customer->phone ?>" class="form-control"  id="phone"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <?= lang("address", "address"); ?>
                                            <?php echo form_input('address', $customer->address, 'class="form-control" id="address"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group hidden">
                                    <?= lang("postal_code", "postal_code"); ?>
                                    <?php echo form_input('postal_code', $customer->postal_code, 'class="form-control" id="postal_code"'); ?>
                                </div>
                                <div class="form-group person">
                                    <?= lang("company", "company"); ?>
                                    <?php echo form_input('company', $customer->company, 'class="form-control tip" id="company" data-bv-notempty="true"'); ?>
                                </div>

                                <div class="form-group">
                                        <label class="control-label" for="price_group"><?php echo $this->lang->line("price_group"); ?></label>
                                        <?php
                                        $pgs[''] = lang('select').' '.lang('price_group');
                                        foreach ($price_groups as $price_group) {
                                            $pgs[$price_group->id] = $price_group->name;
                                        }
                                        echo form_dropdown('price_group', $pgs, $customer->price_group_id, 'class="custom-select form-control select" id="price_group"');
                                        ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("country","country") ?>
                                    <div class="input-group margin-bottom-sm">
                                        <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                                        <select class="custom-select form-control" id="country" required="required" name="country">
                                        <option value="CO-Colombia" selected>Colombia</option>
                                        <?php
                                            for ($i=0; $i<count($country['country']); $i++)
                                                {?>

                                                    <option value='<?php echo $country['country'][$i]['alfa-2']."-".$country['country'][$i]['common_name']?>'>
                                                        <?php echo $country['country'][$i]['common_name']?>
                                                    </option>

                                            <?php }?>

                                        </select>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted"><?= lang("select")."  ".lang("country") ?></small>
                                </div>


                                <div class="form-group">
                                    <?= lang("city", "city"); ?>
                                     <div class="input-group margin-bottom-sm">
                                        <span class="input-group-addon"><i class="fa fa-map fa-fw"></i></span>
                                        <select class="custom-select form-control" id="city" required="required" name="city" >
                                            <option value="<?=$customer->city."-".$customer->state."-".$customer->postal_code?>" selected><?=$customer->city?></option>
                                            <?php
                                                for ($i=0; $i<count($citys['Departamentos_Dian']); $i++)
                                                    {?>

                                                        <option value='<?php echo $citys['Departamentos_Dian'][$i]['Código Municipio']."-".$citys['Departamentos_Dian'][$i]['Nombre Municipio']."-".$citys['Departamentos_Dian'][$i]['Código Departamento']."-".$citys['Departamentos_Dian'][$i]['Nombre Departamento']."-".$citys['Departamentos_Dian'][$i]['Cod_postal']?>'>

                                                            <?php echo $citys['Departamentos_Dian'][$i]['Nombre Departamento']." - ".$citys['Departamentos_Dian'][$i]['Nombre Municipio']
                                                            ?>
                                                        </option>

                                                <?php }?>
                                        </select>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted"><?= lang("select")."  ".lang("Department")." ".lang("city", "city"); ?></small>

                                </div>
                                <div class="form-group">
                                    <?= lang("eps", "eps"); ?>
                                    <?php echo form_input('eps', $customer->cf5, 'class="form-control" id="eps"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("how_meet", "how_meet"); ?>
                                    <?php echo form_textarea('how_meet', $customer->cf6, 'class="form-control" id="how_meet" style="resize:none;height: 113px;"'); ?>
                                </div>
                                <div class="form-group hide">
                                    <?= lang("ccf1", "cf1"); ?>
                                    <?php echo form_input('cf1', $customer->cf1, 'class="form-control" id="cf1"'); ?>
                                </div>
                                <div class="form-group hide">
                                    <?= lang("ccf2", "cf2"); ?>
                                    <?php echo form_input('cf2', $customer->cf2, 'class="form-control" id="cf2"'); ?>

                                </div>
                                <div class="form-group hide">
                                    <?= lang("ccf3", "cf3"); ?>
                                    <?php echo form_input('cf3', $customer->cf3, 'class="form-control" id="cf3"'); ?>
                                </div>
                                <div class="form-group hide">
                                    <?= lang("ccf4", "cf4"); ?>
                                    <?php echo form_input('cf4', $customer->cf4, 'class="form-control" id="cf4"'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <?php echo form_submit('add_customer', $form_customer[name_form], 'class="btn btn-primary"'); ?>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- -->

<script type="text/javascript">
$(function () {
  $('#myModal').on('shown.bs.modal', function (e) {
    $('.focus').focus();
  })
});


function procesarPDF417(cadena) {
  var campos = cadena.split("|");


        $("#id_number").val(campos[2].trim());
        $("#name").val(campos[5].trim() +" "+ campos[6].trim() +" "+ campos[3].trim() +" "+ campos[4].trim());
        $("#gender").val(campos[7].trim());
        $("#blood_type").val(campos[9].trim());
        $("#born").val(campos[8].trim());


}
</script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
        $(document).on('change', '#country', function(event) {
            seleccionar_registro();
        });
    });
    function seleccionar_registro()
    {
        var select_item;
        var city;
        city = document.getElementById("city");
        select_item = $("#country").val();
        if (select_item == "CO-Colombia"){
            city.disabled = false;

        }
        else
        {

            city.disabled = true;

        }
    }
    $( "input" ).on( "click", function() {
        var state=$( "input:checked" ).val();
        if (state) {
            $( "input:checked" ).val('1');
            $( "#log" ).html("<?=lang("yes")." ".lang("responsible_for_IVA") ?>" );
            $( ".msg-iva" ).html("<?=lang('customer').", ".lang("yes")." ".lang("responsible_for_IVA") ?>");

        }
        else
        {
            $( "input:checked" ).val('0');
             $( "#log" ).html("<?=lang("no")." ".lang("responsible_for_IVA") ?>" );
              $( ".msg-iva" ).html("<?=lang('customer').", ".lang("no")." ".lang("responsible_for_IVA") ?>");
        }

    });
</script>
