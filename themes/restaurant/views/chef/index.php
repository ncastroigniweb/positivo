<div id="body" class="chef-barman">
    <input type="hidden" id="language-js" value="<?= lang("chef_barman_hold"); ?>">
    <main class="container-main <?= (isset($table) && isset($order)) ? (($view_right) ? "container-main-small" : "container-main-full") : "side-left";?>">
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs">
                        <li class="ng-scope active">
                            <a data-toggle="tooltip" data-placement="right">
                                <span class="room-label ng-binding "><?= lang('orders'); ?></span>
                                <span class="room-count status-occupied ng-binding">
                                    <?= !empty($list_products) ? count($list_products) : 0 ; ?>
                                </span>
                            </a>
                        </li>
                        <li class="ng-scope active average-li hidden-lg">
                            <a data-toggle="tooltip" data-placement="right">
                                <span class="average"><?= (!empty($average_time)) ? $average_time : 0 . " " . lang('minutes_res');?></span>
                            </a>
                        </li>
                    </ul>
                </h1>
            </div>
            <?php if(isset($table) && isset($order)){ ?>
            <button id="slide-left" class="btn btn-inverse btn-app btn-xs ace-settings-btn aside-trigger slide-left"
                    data-target="" data-toggle="" type="button" onclick="switch_right_view(<?= ($this->session->userdata('user_id'));?>, <?= $view_right;?>)">
                <i data-icon1="fa-chevron-left" data-icon2="fa-chevron-right"
                   class="ace-icon fa bigger-110 icon-only <?= ($view_right) ? "fa-chevron-right" : "fa-chevron-left" ;?>" aria-hidden="true"></i>
            </button>
            <?php } ?>
            <div class="average_time hidden-xs hidden-sm" onclick="hide_average()">
                <span class="average"><?= (!empty($average_time)) ? $average_time : 0 . " " . lang('minutes_res');?></span>
            </div>
        </div>
        <div class="wrapper table-grid-wrapper" id="div-btncantidadpersonas">
            <div class="content tables-container text-center calc_05">
                <div class="data-wrapper content-chef-barman-index">
                    <table class="data stated">
                        <thead>
                        <tr>
                            <th><?= lang("chef_barman_table"); ?></th>
                            <th><?= lang("chef_barman_waiter"); ?></th>
                            <th class="col-id"></th>
                            <th class="col-id"><?= lang("chef_barman_quantity"); ?></th>
                            <th><?= lang("chef_barman_product"); ?></th>
                            <th><?= lang("chef_barman_minutes"); ?></th>
                            <th class="col-id"></th>
                        </tr>
                        </thead>
                        <tbody class="body-list">
                            <?php if(!empty($list_products)) { ?>
                            <?php foreach ($list_products as $pending_product) { ?>
                                <tr id="<?= $pending_product->id; ?>" class="status-in_course 
                                    <?= (isset($table) && ($table->id == $pending_product->product_table)) ? 'active' : ''; ?>
                                    <?= ($pending_product->subcategory_id == 16) ? ' bisque' : '' ?>
                                    <?= ($pending_product->total_minutes > $delay_product) ? ' delay_product flag_email' : '' ?>"
                                    >
                                    <td class="ng-binding font-chef">
                                        <button id="btn-waiting" class="btn btn-info ng-scope  ng-isolate-scope" title="<?= lang("chef_barman_hold"); ?>">
                                            <span id="table_<?= $pending_product->id; ?>" class="button-dispatch"><?= $pending_product->table_name; ?></span>
                                        </button>
                                    </td>
                                    <td class="ng-binding font-chef">
                                        <?= $this->site->getUser($pending_product->product_waiter)->first_name ?>
                                    </td>
                                    <td>
                                        <div class="bx-viewport bx-viewport-container">
                                            <img src="assets/uploads/<?= $pending_product->image ?>" height="65" width="72">
                                        </div>
                                    </td>
                                    <td class="col-id ng-binding category-chef-barman u_font_20  font-chef">
                                        <?= intval($pending_product->quantity); ?>
                                    </td>
                                    <td>
                                        <div class="ng-binding  ng-isolate-scope u_font_20 font-chef">
                                            <strong class="item-label">
                                                <span title="<?= $pending_product->product_name; ?>" class="ng-binding ">
                                                    <?= $pending_product->product_name; ?>
                                                </span>
                                            </strong>
                                            <?php if($pending_product->option_name){ ?>
                                                    <i>(<?= $pending_product->option_name ?>)</i>
                                                <?php } ?>
                                                
                                        </div>
                                        <div class="addition-comment ng-hide">
                                            <p class="ng-binding"><?= $pending_product->comments; ?></p>
                                        </div>
                                    </td>
                                    <td id="minutes_<?= $pending_product->id; ?>" class="font-chef <?= $hour . $min . $seg; ?>"><?= $pending_product->diff_minutes; ?></td>
                                    <td>
                                        <button id="button_<?= $pending_product->id; ?>" class="btn btn-success ng-scope  ng-isolate-scope" title="<?= lang("chef_barman_ready"); ?>"
                                                onclick="click_once(this); product_dispatch(<?= $pending_product->id ?>)">
                                            <span class="icon icon-check button-dispatch"></span><br>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="container-slide width_100">
                    <button class="btn btn-inverse btn-app btn-xs ace-settings-btn aside-trigger slide-down"
                            data-target="#top-menu" data-toggle="modal" type="button">
                        <i data-icon1="fa-chevron-down" data-icon2="fa-chevron-up"
                           class="ace-icon fa bigger-110 icon-only fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="tables-content">
                        <?php foreach ($list_tables as $list_table) { ?>
                            <div class="table-grid modal-chef-barman-tables">
                                <div class="table-placeholder status-occupied container-table">
                                    <a class="table" href="chef/view/<?= $list_table->id; ?>">
                                        <div class="table-indicators">
                                            <div class="table-number">
                                                <span class="ng-binding"><?= $list_table->name; ?></span>
                                            </div>
                                            <div class="table-waiter">
                                                <span class="ng-binding ng-hide">
                                                    <?= $this->site->getUser($list_table->waiter)->first_name ?>
                                                </span>
                                            </div>
                                            <div class="table-count">
                                                <span class="ng-binding ng-hide"><?= $list_table->guests; ?></span>
                                                <span class="table-count-reservation ng-binding ng-hide"></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="btn btn-inverse btn-app btn-xs ace-settings-btn aside-trigger slide-down responsive"
                            data-target="#top-menu" data-toggle="modal" type="button">
                        <i data-icon1="fa-chevron-down" data-icon2="fa-chevron-up"
                           class="ace-icon fa bigger-110 icon-only fa-chevron-down" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div>
        <?php if (isset($table) && isset($order)) { ?>
            <aside class="ng-scope container-bar-right <?= ($view_right) ? "container-side-right-small" : "container-side-right-zero" ;?>">
                <div class="content">
                    <div class="ng-scope">
                        <h2 class=" status-occupied">
                            <span class="ng-binding "><?= lang('table') ?> : <?= $table->name ?></span>
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
                                            <?= lang('comments') ?> :</strong><span>&nbsp;<?= $order->suspend_note ?>
                                        </span>
                                    <?php } ?>
                                </li>
                                <li>
                                    <em class="ng-binding "></em>
                                </li>
                            </ul>
                        </section>
                        <h3><span><?= lang('order_items') ?> (<?= count($products) ?>)</span></h3>
                        <section>
                            <ul class="list show items stated additions">
                                <?php foreach ($products as $product) { ?>
                                    <li class="ng-scope confirmed">
                                        <div class="item">
                                            <span class="count ng-binding "><?= intval($product->quantity) ?></span>
                                            <span class="item-label">
                                                <strong>
                                                    <span title="" class="ng-binding ">
                                                        <?= $product->product_name ?>
                                                    </span>
                                                </strong>
                                                
                                                <?php if($product->option_name){ ?>
                                                    <i>(<?= $product->option_name ?>)</i>
                                                <?php } ?>
                                                
                                                <?= ($product->comments) ? "<span>({$product->comments})</span>" : "" ?>
                                            </span>
                                            <span class="price ng-binding ">
                                                ( <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($product->unit_price) ?> )
                                            </span>
                                            <span class="price ng-binding ">
                                                <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($product->subtotal) ?>
                                            </span>
                                            <button class="button-icon button-simple ng-scope ng-isolate-scope"
                                                    onclick="window.location.href='chef/dispatch/<?= $product->id ?>' ">
                                                <span class="icon icon-check"></span>
                                            </button>
                                        </div>
                                        <div class="addition-comment ng-hide "><p class="ng-binding "></p></div>
                                    </li>
                                    <?php $total = $total + $product->subtotal; ?>
                                <?php } ?>
                            </ul>
                            <div class="total">
                                <span><?= lang('total') ?>:</span>
                                <strong
                                    class="ng-binding "><?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($total) ?></strong>
                            </div>
                        </section>
                    </div>
                </div>
            </aside>
        <?php } else { ?>
            <aside class="ng-scope no-mobile side-rigth">
                <div class="content">
                    <p class="help-select-item">
                        <span class="icon icon-caret-left"></span>
                        <span class="ng-binding "><?= lang('select_item'); ?></span>
                    </p>
                </div>
            </aside>
        <?php } ?>
    </div>
</div>