<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title"
                id="myModalLabel"><?= lang('expenses') . ' (' . $this->sma->hrld($register_open_time ? $register_open_time : $this->session->userdata('register_open_time')) . ' - ' . $this->sma->hrld(date('Y-m-d H:i:s')) . ')'; ?></h4>
        </div>
        <div class="modal-body">
            <div id="div_view_expenses">
                <table id="view_expenses" width="100%" class="stable">
                    <thead>
                        <tr>
                            <th class="col-md-3"><h4><?= lang('date'); ?></h4></th>
                            <th class="col-md-3"><h4><?= lang('amount'); ?></h4></th>
                            <th class="col-md-3"><h4><?= lang('note'); ?></h4></th>
                            <th class="col-md-3"><h4><?= lang('actions'); ?></h4></th>
                        </tr>
                    </thead>
                    <tbody class="d-sales-table">
                        <?php 
                            $total_expense = 0;
                        foreach ($expenses as $expense) { ?>
                            <tr>
                                <td class="col-md-3"><h4><span><?= $this->sma->hrld($expense->date); ?></span></h4></td>
                                <td class="col-md-1"><h4><span><?= $this->sma->formatMoney($expense->amount); ?></span></h4></td>
                                <td class="col-md-5"><h4><span><?= $expense->note; ?></span></h4></td>
                                <td class="col-md-3"><a href="<?= base_url() . 'purchases/expense_note/' . $expense->id; ?>" title="<?= lang('view_expense'); ?>" data-toggle="modal" data-target="#myModal3"><i class="fa fa-file-text-o"></i></a>
                                    <a href="<?= base_url() . 'purchases/edit_expense/' . $expense->id; ?>" title="<?= lang('edit_expense'); ?>" data-toggle="modal" data-target="#myModal3"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        <?php 
                            $total_expense += $expense->amount;
                        } ?>
                            <tr class="total_bills">
                                <td class="col-md-3"><h4><span class="lang-total-paying"><?= lang('total_expenses'); ?></span></h4></td>
                                <td class="col-md-1"><h4><span><?= $this->sma->formatMoney($total_expense); ?></span></h4></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

