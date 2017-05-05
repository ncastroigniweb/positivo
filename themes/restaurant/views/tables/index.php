<div id="body">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs title-tables clearfix">
                        <?php if ($Owner || $Admin || $this->sma->is_product_admin()) { ?>
                            
                            <li class="totals_table_mobile">
                                <span class="label-receivable ng-binding label-info"><i class="fa fa-caret-down" aria-hidden="true"></i> <?= lang("sales"); ?></span>
                                <ul class="sub-menu">

                                        <li class="">
                                            <span class="label-receivable ng-binding label-info">
                                                <?= lang("receivable") ?> : <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($suspended_total); ?>
                                            </span>
                                        </li>
                                        <li class="">
                                            <span class="label-receivable ng-binding label-info">
                                                <?= lang("paid") ?>&emsp;: <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($totalsales->paid - $taxsales->tax); ?>
                                            </span>
                                        </li>
                                        <li class="">
                                            <span class="label-receivable ng-binding label-success">
                                                <?= lang("total") ?>&emsp;&emsp;&nbsp;&nbsp;: <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($totalsales->paid - $taxsales->tax + $suspended_total); ?>
                                            </span>
                                        </li>
                                </ul>
                            </li>
                        
                            <li class="pull-left ng-scope active totals_table">
                                <span class="label-receivable ng-binding label-info">
                                    <?= lang("receivable") ?>: <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($suspended_total); ?>
                                </span>
                            </li>
                            <li class="pull-left ng-scope active totals_table">
                                <span class="label-receivable ng-binding label-info">
                                    <?= lang("paid") ?>: <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($totalsales->paid - $taxsales->tax - $tipsales->tip); ?>
                                </span>
                            </li>
                            <li class="pull-left ng-scope active totals_table">
                                <span class="label-receivable ng-binding label-success">
                                    <?= lang("total") ?>: <?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($totalsales->paid - $taxsales->tax - $tipsales->tip + $suspended_total); ?>
                                </span>
                            </li>
                        <?php } ?>
                        <li class="ng-scope active">
                            <a class="legend-menu">
                                <span class="room-label ng-binding ">
                                    <div class="icon-stats-color stats-color-celeste"></div>
                                        <?= lang('table_free') ?>
                                    <div class="icon-stats-color stats-color-azul"></div>
                                        <?= lang('table_busy') ?>
                                    <div class="icon-stats-color stats-color-verde"></div>
                                        <?= lang('table_awating') ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </h1>
            </div>
        </div>

        <div class="wrapper table-grid-wrapper" id="div-btncantidadpersonas">
            <div class="content tables-container calc_05">
                <?php foreach ($tables as $table) { ?>
                    <div class="table-grid u-table-grid">
                        <?php
                            if ($table->available) {
                                $href = ($table->status == 0) ? "/tables/order/create/{$table->id}" : "/tables/order/edit/{$table->id}";
                                $no_allow = 'class="table"';
                            } else {
                                $href = "javascript:void(0)";
                                $no_allow = 'disabled class="table link-disabled"';
                            } 

                            $item_color = "status-free";
                            $icon_color = "stats-color-azul";

                            if ($table->status == 1) {
                                $item_color = " status-occupied";
                                $icon_color = "stats-color-celeste";
                            } else if ($table->status == 2) {
                                $item_color = "status-payment";
                                $icon_color = "stats-color-verde";
                            }
                            
                            
                            // If is chasier go to pos system
                            if ($this->sma->is_cashier() && $table->status == 2){
                                $href = "/pos/index/$table->bill";
                            }
                            
                        ?>
                        <div class="table-placeholder u-absolute-let-top <?= $item_color ?>">
                                <a href="<?= $href ?>" <?= $no_allow ?>>
                                    <div class="table-indicators">
                                        <div
                                            class="table-number <?= ($table->status == 0 || $table->status == 2) ? 'margin-element' : '' ; ?>">
                                            <span class="ng-binding ">
                                                <?= $table->name; ?>
                                            </span>
                                        </div>
                                        <?php if ($table->status == 1 || $table->status == 2) { ?>
                                            <div class="table-waiter">
                                                <span class="ng-binding ng-hide">
                                                    <?= $this->site->getUser($table->waiter)->first_name ?>
                                                </span>
                                            </div>
                                            <div class="table-count">
                                                <span class="ng-binding  ng-hide">
                                                    <i class="fa fa-users <?= $icon_color ?>" aria-hidden="true"></i>&nbsp;<?= $table->guests ?>
                                                    <?php if ($table->parent){ ?>
                                                                &nbsp;&nbsp;<i class="fa fa-thumb-tack <?= $icon_color ?>"
                                                                   aria-hidden="true"></i> <?= $table->parent ?>
                                                    <?php } ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </a>
                           
                        </div>
                    </div>
                <?php } ?>
               
            </div>
        </div>
        
    </main>

    <div class="no-mobile">
        <aside ui-view="" class="ng-scope ">
            <div class="content">
                <p class="help-select-item">
                    <span class="icon icon-caret-left"></span>
                    <span class="ng-binding "><?= lang('select_item') ?></span>
                </p>
            </div>
        </aside>
    </div>
</div>
