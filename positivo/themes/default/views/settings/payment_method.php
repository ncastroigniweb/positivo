<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
    $data= file_get_contents('app/json/payment_means.json'); 
    $payment_method = json_decode($data,true);
?>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-credit-card"></i><?= $page_title ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" ><i class="icon fa fa-tasks tip"
                                                                                  title="<?php echo lang("payment_method_add")?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="#" data-toggle="modal"
                               data-target="#exampleModal"><i class="fa fa-plus"></i>Añadir método de pago</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- <p class="introtext"><?php echo $this->lang->line("list_results"); ?></p> -->

                <div class="table-responsive">
                    <table id="" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line("payment_method"); ?></th>
                            <th><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php for( $i=0; $i < count($payment_method["payment_means"]);  $i++) { $code = $payment_method['payment_means'][$i]['code']; $name = $payment_method['payment_means'][$i]['name']; ?>
                                <tr>
                                   <td><?php echo $payment_method["payment_means"][$i]["name"]; ?></td>
                                   <td><div class="text-center">
                                                <?php 
                                                if($code != 1){?>
                                                    <a onclick="idPayment(<?php echo $code;?>,'<?php echo $name;?>');"><i class="icon fa fa-edit fa-lg" title="<?php echo lang("payment_method_edit")?>"></i></a>&nbsp;
                                                    <!-- <a href="<?php //echo base_url();?>system_settings/deletePaymentMethod/<?php //echo $code;?>" class="" title=""> <i class='icon fa fa-trash-o fa-lg'  title="<?php //echo lang("payment_method_delete")?>"></i></a> -->
                                                    <!-- <a href="<?php // echo base_url();?>system_settings/deletePaymentMethod/<?php echo $code;?>" class="" title=""> <i class='icon fa fa-trash-o fa-lg'  title="<?php //echo lang("payment_method_delete")?>"></i></a> -->
                                                    <a data-toggle="popover" title="Borrar Método" data-html="true" data-content='
                                                    <p>¿Está seguro?</p>
                                                    <div class="btn-group ">
                                                        <a href="<?php echo base_url();?>system_settings/deletePaymentMethod/<?php echo $code;?>" class="btn btn-danger mt-1" onclick="stopDefAction(event);">Sí</a>
                                                        <button class="cerrar-popover btn">No</button>
                                                    </div>'>
                                                    <i class='icon fa fa-trash-o fa-lg'  title="<?php echo lang("payment_method_delete")?>"></i></a>
                                             <?php }
                                                ?>                                            
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

<div style="display: none;">
    <input type="hidden" name="form_action" value="" id="form_action"/>
    <?= form_submit('submit', 'submit', 'id="action-form-submit"') ?>
</div>
<?= form_close() ?>

<!-- Modal Create -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nuevo método de pago</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?= form_open('system_settings/getPaymentMethod', 'id="action-form"') ?>
        <div class="modal-body">
            <div class="form-group">
                <label class="control-label" for="name">Método de pago<?php //echo $this->lang->line("currency_code"); ?></label>
                <div class="controls"> 
                    <?php echo form_input('name', '', 'class="form-control" id="name" '); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <div style="display: none;"></div>
            <input type="hidden" name="form_action" value="" id="form_action"/>
            <?= form_submit('submit', 'Guardar', 'class="btn btn-primary"') ?>
        </div>
        </div>
  </div>
  <?php echo form_close(); ?>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Editar método de pago</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?= form_open('', 'id="edit-form"') ?>
        <div class="modal-body">
            <div class="form-group">
                <label class="control-label" for="name">Método de pago<?php //echo $this->lang->line("currency_code"); ?></label>
                <div class="controls"> 
                    <input type="text" name="name" id="form-name" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <div style="display: none;"></div>
            <input type="hidden" name="form_action" value="" id="form_action"/>
            <?= form_submit('submit', 'Editar', 'class="btn btn-primary"') ?>
        </div>
        </div>
  </div>
  <?php echo form_close(); ?>
</div>
<script>
    function idPayment(code,name){
        let url = 'system_settings/editPaymentMethod' + '/' + code;
        $("#edit-form").attr("action",url);
        document.getElementById('form-name').value = name;
        $('#editModal').modal('toggle')
    }
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                html: true
            });
            
            $('body').on('click', '.cerrar-popover', function(){
                $('[data-toggle="popover"]').popover('hide');
            });
        });
</script>
