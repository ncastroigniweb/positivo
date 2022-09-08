<script>
    $(document).ready(function() {
        var oTable = $('#POSData').dataTable({
            "aaSorting": [
                [0, "asc"],
                [1, "desc"]
            ],
            "aLengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "<?= lang('all') ?>"]
            ],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?= site_url('pos/getSales' . ($warehouse_id ? '/' . $warehouse_id : '')) ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "receipt_link";
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {
                "mRender": fld
            }, null, null, null, {
                "mRender": currencyFormat
            }, {
                "mRender": currencyFormat
            }, {
                "mRender": currencyFormat
            }, {
                "mRender": row_status
            }, {
                "bSortable": false
            }],
            "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0,
                    paid = 0,
                    balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][5]);
                    paid += parseFloat(aaData[aiDisplay[i]][6]);
                    balance += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = currencyFormat(parseFloat(gtotal));
                nCells[6].innerHTML = currencyFormat(parseFloat(paid));
                nCells[7].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([{
                column_number: 1,
                filter_default_label: "[<?= lang('date'); ?> (yyyy-mm-dd)]",
                filter_type: "text",
                data: []
            },
            {
                column_number: 2,
                filter_default_label: "[<?= lang('reference_no'); ?>]",
                filter_type: "text",
                data: []
            },
            {
                column_number: 3,
                filter_default_label: "[<?= lang('biller'); ?>]",
                filter_type: "text",
                data: []
            },
            {
                column_number: 4,
                filter_default_label: "[<?= lang('customer'); ?>]",
                filter_type: "text"
            },
            {
                column_number: 8,
                filter_default_label: "[<?= lang('payment_status'); ?>]",
                filter_type: "text",
                data: []
            },
        ], "footer");

        $(document).on('click', '.email_receipt', function() {
            var sid = $(this).attr('data-id');
            var ea = $(this).attr('data-email-address');
            var email = prompt("<?= lang("email_address"); ?>", ea);
            if (email != null) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('pos/email_receipt') ?>/" + sid,
                    data: {
                        <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>",
                        email: email,
                        id: sid
                    },
                    dataType: "json",
                    success: function(data) {
                        bootbox.alert(data.msg);
                    },
                    error: function() {
                        bootbox.alert('<?= lang('ajax_request_failed'); ?>');
                        return false;
                    }
                });
            }
        });
    });
</script>

<?php if ($Owner || $GP['bulk_actions']) {
    echo form_open('sales/sale_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('pos_sales') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('pos') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_sale') ?></a></li>
                        <?php if ($Owner) { ?>
                            <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                            <li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_sales") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_sales') ?></a></li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('pos/sales') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('pos/sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">


                <div class="table-responsive tb-sales">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="post-dian">
                                <svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#e83e8c"></rect><text x="50%" y="50%" fill="#e83e8c" dy=".3em">32x32</text>
                                </svg>
                            </div>
                            <div class="post-dian-m">
                                <h5 class="d-block text-gray-dark">
                                    <?= lang("sales") . " <strong>" . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . "</strong>"; ?>
                                </h5>
                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray"><?= lang('list_results'); ?></p>
                            </div>
                        </div>
                        <?php if ($tpFAct[0]->billing == 1 AND $countSales!=false ) { ?>
                            <div class="col-md-4 msg-facVent">
                                <a href="#" data-toggle="modal" data-target="#staticBackdrop">
                                    <div>
                                        <h4><?= lang('unbilled') ?></h4>
                                        <span>
                                            <?= $countSales==false ? "0": count($countSales);?>
                                        </span>
                                        <p>
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            <small> <?= lang('sales_issued') ?> </small>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>



                <table id="POSData" class="table table-striped table-hover table-striped data-sales">
                    <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check" />
                            </th>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("reference_no"); ?></th>
                            <th><?= lang("biller"); ?></th>
                            <th><?= lang("customer"); ?></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th><?= lang("paid"); ?></th>
                            <th><?= lang("balance"); ?></th>
                            <th><?= lang("payment_status"); ?></th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang("loading_data"); ?></td>
                        </tr>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check" />
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th><?= lang("paid"); ?></th>
                            <th><?= lang("balance"); ?></th>
                            <th class="defaul-color"></th>
                            <th></th>

                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action" />
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("pos/manuBilling", $attrib);
                ?>
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><?= lang('unbilled') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    <?= lang('m-alert-billing') ?>
                </div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col"> <?= lang('starting') ?></th>
                            <th scope="col"><?= lang('ends') ?></th>
                            <th scope="col"><?= lang('total_invoices') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <kbd>
                                    <?= $countSales[0]->reference_no ?>
                                </kbd>
                            </td>
                            <td>
                                <kbd>
                                    <?= $countSales[count($countSales) - 1]->reference_no ?>
                                </kbd>
                            </td>
                            <td>
                                <?= count($countSales) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('close') ?></button>
                <button type="submit" class="btn-env"><?= lang('view_receipt_dian') ?></button>
            </div>
           <?php echo form_close(); ?>
        </div>
    </div>
</div>
<style type="text/css">
    .msg-facVent div {
        background: #ff7e33eb;
        border: solid 1px #ff7e33c9;
        color: #fff;
        text-align: center;
        width: fit-content;
        padding: 0px 10px;
        float: right;
    }

    .msg-facVent a {
        text-decoration: none;
    }

    .msg-facVent div:hover {
        background: #ff6233;
    }

    .msg-facVent div span {
        font-size: 30px;
        font-weight: bold;
    }
</style>