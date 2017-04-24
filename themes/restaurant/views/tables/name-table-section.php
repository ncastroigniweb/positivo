<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="menu_table_mobile">
    <h2 class="<?= ($table->status == 2) ? "status-payment" : "status-occupied" ?>">
        <span class="ng-binding ">
             <?= lang('table') ?> : <?= $table->name ?> (<?= $order->customer ?>)
        </span>

        <?php if ($this->sma->is_admin() || $this->sma->is_cashier()) { ?>
            <div ert-can="update_sales" class="ng-scope ng-isolate-scope">
                <ert-icon-edit title="<?= lang('change_table') ?>" class="ng-isolate-scope">
                    <button class="button-icon button-change-table">
                        <span class="icon"><i class="fa fa-table" aria-hidden="true"></i></span>
                        <span class="icon icon-edit u-icon-edit"></span>
                    </button>
                </ert-icon-edit>
            </div>
            <div ert-can="update_sales" class="ng-scope ng-isolate-scope">
                <ert-icon-edit title="<?= lang('change_waiter') ?>" class="ng-isolate-scope">
                    <button class="button-icon button-change-waiter">
                        <span class="nav-icon icon-system-user u-icon-user-margin"></span>
                        <span class="icon icon-edit u-icon-edit"></span>
                    </button>
                </ert-icon-edit>
            </div>
        <?php } ?>
        <div ert-can="update_sales" class="ng-scope ng-isolate-scope">
            <ert-icon-edit title="<?= lang('add_customer') ?>" class="ng-isolate-scope">
                <button class="button-icon"
                        onclick=" window.location.href='tables/customer/add/<?= $table->id ?>'">
                    <span class="nav-icon icon-system-user u-icon-user-margin"></span>
                    <span class="icon icon-plus u-icon-user-plus"></span>
                </button>
            </ert-icon-edit>
        </div>
    </h2>
    <section>
        <ul class="list show">
            <li class="sale-info">
                <strong class="ng-binding ">
                    <?= $table->guests ?> <?= lang('guests') ?>,
                </strong>
                <a class="ng-binding "></a>
                <?= $this->site->getUser($table->waiter)->first_name ?>,
                <span><?= $order->date ?></span><br>
                <?php if ($order->suspend_note) { ?>
                    <strong class="ng-binding ng-hide">
                        <?= lang('comments') ?>:
                    </strong>
                    <span>&nbsp;<?= $order->suspend_note ?></span>
                <?php } ?>
            </li>
            <li>
                <em class="ng-binding "></em>
            </li>
        </ul>
    </section>
    <!-- Change waiter -->
    <div class="section-change_waiter hide">
        <h3>
            <span><?= lang('change_waiter')  ?></span>
        </h3>
        <section class="u-no-margin">

            <?php $attrib = array('role' => 'form', 'id' => 'changewaiter', 'method' => "post");
            echo form_open("tables/order/change_waiter/{$table->id}", $attrib); ?>

            <div id="customers-form-1">
                <ul class="list edit">
                    <li class="u_padding_22">
                        <label class="required u_max_width_40rem">
                            <span><?= lang('waiter') ?></span>
                            <select class="form-control u_width_24rem" name="waiter" required="">
                                <?php foreach ($this->restaurant->getWaiters() as $waiter){ ?>
                                    <option value="<?= $waiter->id ?>" <?= ($order->id_waiter == $waiter->id) ? "selected" : "" ; ?> ><?= $waiter->first_name ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn-success u_disply_inlineblock button-add-client">
                                <span><i class="fa fa-check" aria-hidden="true"></i></span>
                            </button>
                        </label>
                    </li>
                </ul>
            </div>

            <?php echo form_close(); ?>
        </section>
    </div>
    <!-- Change Table -->
    <div class="section-change_table hide">
        <h3>
            <span><?= lang('change_table')  ?></span>
        </h3>
        <section class="u-no-margin">

            <?php $attrib = array('role' => 'form', 'id' => 'changetable', 'method' => "post");
            echo form_open("tables/order/change_table/{$table->id}", $attrib); ?>

            <div id="customers-form-1">
                <ul class="list edit">
                    <li class="u_padding_22">
                        <label class="required u_max_width_40rem">
                            <span><?= lang('table') ?></span>
                            <select class="form-control u_width_24rem" name="table" required="">
                                <?php foreach ($this->restaurant->getFreeTables() as $freetable){ ?>
                                    <option value="<?= $freetable->id ?>"><?= $freetable->name ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn-success u_disply_inlineblock button-add-client">
                                <span><i class="fa fa-check" aria-hidden="true"></i></span>
                            </button>
                        </label>
                    </li>
                </ul>
            </div>

            <?php echo form_close(); ?>
        </section>
    </div>
</div>
