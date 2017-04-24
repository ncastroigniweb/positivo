<div id="body" class="chef-barman">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs">
                        <li class="ng-scope active">
                            <a data-toggle="tooltip" data-placement="right">
                                <span class="room-label ng-binding "><?= lang('orders'); ?></span>
                                <span class="room-count status-occupied ng-binding">
                                    <?= count($list_products); ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </h1>
            </div>
        </div>
        <div class="wrapper table-grid-wrapper" id="div-btncantidadpersonas">
            <div class="content tables-container text-center calc_05">
                <div class="data-wrapper">
                    <table class="data stated">
                        <thead>
                        <tr>
                            <th class="col-id"><?= lang("chef_barman_table"); ?></th>
                            <th><?= lang("chef_barman_waiter"); ?></th>
                            <th class="col-id"><?= lang("chef_barman_quantity"); ?></th>
                            <th><?= lang("chef_barman_product"); ?></th>
                            <th><?= lang("chef_barman_order_date"); ?></th>
                            <th><?= lang("chef_barman_order_dispatched"); ?></th>
                            <th><?= lang("chef_barman_minutes"); ?></th>
                            <td><?= lang("chef_barman_state"); ?></td>

                        </tr>
                        </thead>
                        <tbody class="body-list">
                            <?php foreach ($list_products as $pending_dispatched) { ?>
                                <tr id="<?= $pending_dispatched->id; ?>" class="status-in_course
                                    <?= (isset($table) && ($table->id == $pending_dispatched->product_table)) ? 'active' : ''; ?>">
                                    <td>
                                        <button class="btn btn-success ng-scope  ng-isolate-scope" title="<?= lang("chef_barman_ready"); ?>"
                                            <span class="button-dispatch"><?= $pending_dispatched->table_name ?></span><br>
                                        </button>
                                    </td>
                                    <td class="ng-binding ">
                                        <?= $this->site->getUser($pending_dispatched->product_waiter)->first_name ?>
                                    </td>
                                    <td class="col-id ng-binding category-chef-barman">
                                        <?= intval($pending_dispatched->quantity); ?>
                                    </td>
                                    <td>
                                        <div class="ng-binding  ng-isolate-scope ">
                                            <strong class="item-label">
                                                <span title="<?= $pending_dispatched->product_name; ?>" class="ng-binding ">
                                                    <?= $pending_dispatched->product_name; ?>
                                                </span>
                                            </strong>
                                            <?php if($pending_dispatched->option_name){ ?>
                                                    <i>(<?= $pending_dispatched->option_name ?>)</i>
                                                <?php } ?>
                                                
                                        </div>
                                        <div class="addition-comment ng-hide">
                                            <p class="ng-binding"><?= $pending_dispatched->comments; ?></p>
                                        </div>
                                    </td>
                                    <td><?= date("Y-m-d h:i",strtotime($pending_dispatched->date_confirmed)); ?></td>
                                    <td><?= date("Y-m-d h:i",strtotime($pending_dispatched->date_dispatched)); ?></td>
                                    <td id="minutes_<?= $pending_dispatched->id; ?>"><?= $pending_dispatched->diff_minutes; ?></td>
                                    <td>
                                        <span class="status-label ng-binding "><?= lang("chef_barman_dispatched"); ?></span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>