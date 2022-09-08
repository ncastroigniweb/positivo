<?php

/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 06/08/16
 * Time: 10:37
 */

$first_key = key($categories);
$unconfirmed = 0;
$total = 0;
?>

<div id="body">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs ">
                        <?php foreach ($categories as $cat_key => $categorie) { ?>
                            <li class="ng-scope <?= ($first_key == $cat_key) ? 'active' : '' ?>">
                                <a data-toggle="tab" href="#<?= $categorie['id'] ?>" data-placement="right" id="category-link-<?= $categorie['id'] ?>">
                                    <span class="room-label ng-binding "><?= $categorie['name'] ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </h1>
            </div>
        </div>

        <div class="wrapper table-grid-wrapper" id="div-btncantidadpersonas">
            <div class="content calc_05">
                <div class="tab-content">
                    <?php foreach ($subcategories as $parent => $categories) { ?>
                        <div id="<?= $parent ?>" class="tab-pane fade in <?= ($first_key == $parent) ? 'active' : '' ?> <?= $first_key . " - " . $parent ?>">
                            <div class="calc_05 u_margin_20">
                                <div class="quick-add">
                                    <?php if (!isset($categories['code'])) { ?>
                                        <?php foreach ($categories as $category) { ?>
                                            <button onclick=" ajax_show_products(<?= $category['id'] ?>)" class="button ng-scope" title="<?= $category['name'] ?>">
                                                <span class="product-code ng-binding "><?= $category['id'] ?></span>
                                                <span class="product-label ng-binding "><?= $category['name'] ?></span>
                                            </button>
                                        <?php } ?>
                                    <?php
                                    } else {
                                        ($categories['code'] == 'Top10Productos') ? (include 'top-10-products.php') : '';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="ajax_products">
                    <ul id="js_imgBox" class="clearfix cateList mt5 u_list_type_none products"></ul>
                </div>
            </div>
        </div>
    </main>

    <aside ui-view="" class="ng-scope">
        <div class="content">
            <div class="ng-scope">
                <div class="menu_table">
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
                                <button class="button-icon" onclick=" window.location.href='tables/customer/add/<?= $table->id ?>'">
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
                                                <?php foreach ($this->restaurant->getWaiters() as $waiter) { ?>
                                                    <option value="<?= $waiter->id ?>" <?= ($order->id_waiter == $waiter->id) ? "selected" : ""; ?>><?= $waiter->first_name ?></option>
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
                                                <?php foreach ($this->restaurant->getFreeTables() as $freetable) { ?>
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
                <h3>
                    <span><?= lang('order_items') ?> (<?= count($products) ?>)</span>
                    <span class="text-right">
                        <i class="fa fa-search button-search" title="<?= lang('products_search') ?>" aria-hidden="true"></i><span class="sr-only"><?= lang('products_search') ?></span>
                    </span>
                </h3>
                <section>
                    <!-- Product search -->
                    <div class="adder hide u_display_block">
                        <div class="adder-product autocomplete">
                            <input type="text" autofocus="" id="bs-prods" class="input ng-pristine ng-valid ng-isolate-scope ng-empty ng-touched" placeholder="<?= lang('products_search') ?>...">
                        </div>
                    </div>

                    <!--// results ajax search product-->
                    <div class="registros hide" id="agrega-registros">
                        <div class="quick-add"></div>
                    </div>

                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add2order', 'method' => "post", 'class' => "hide");
                    echo form_open("tables/product/add2order", $attrib); ?>

                    <div class="registros" id="agrega-registros2">
                        <ul class="list show items stated additions">
                            <li class="status-new ng-scope ">
                                <div class="item">
                                    <div class="incrementer">
                                        <a id="min">
                                            <div class="icon icon-minus"></div>
                                        </a>
                                        <input name="qty" id="orp_cantidad" value="1" size="4" class="shy" max="99" min="1" required="" type="number">
                                        <a id="plus">
                                            <div class="icon icon-plus"></div>
                                        </a>
                                    </div>

                                    <strong class="item-label ng-binding form-product-name"></strong>

                                    <div class="ng-scope ">
                                        <span class="form-product-price"></span>
                                    </div>

                                    <button class="button-icon button-simple" title="<?= lang('cancel') ?>" type="button" onclick="cancelDetail(this)">
                                        <span class="icon icon-x"></span>
                                    </button>
                                </div>
                                <div class="addition-comment ng-hide">
                                    <input name="comments" id="orp_descripcion" class="shy ng-pristine ng-untouched ng-valid ng-isolate-scope ng-empty" placeholder="<?= lang("add_comment"); ?>">
                                </div>
                            </li>
                        </ul>
                        <div class="button-container status-new">
                            <input type="hidden" id="table_id" value="<?= $table->id ?>">
                            <button id="myButton" class="button button-action ng-isolate-scope">
                                <span class="ng-scope"><?= lang('confirm') ?></span>
                            </button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>

                    <?php include 'name-table-section.php'; ?>
                    <!-- list Products -->
                    <ul id="products_order" class="list show items stated">
                        <?php foreach ($products as $product) { ?>
                            <li class="ng-scope <?= $product->product_status ?>">
                                <?php $unconfirmed = ($product->product_status == 'pending') ? ++$unconfirmed : $unconfirmed; ?>
                                <div class="item">
                                    <span class="count ng-binding "><?= intval($product->quantity) ?></span>
                                    <span class="item-label">
                                        <span title="" class="ng-binding ">
                                            <strong>
                                                <?php if (!in_array($product->product_waiter, $waiters)) { ?>
                                                    <i class="fa fa-bookmark icon-explain-product" aria-hidden="true"></i>&nbsp;
                                                <?php } ?>

                                                <?= $product->product_name ?>&nbsp;
                                            </strong>

                                            <?php if ($product->option_id) { ?>
                                                <i>(<?= $this->restaurant->getProductOptionByID($product->option_id)->name ?>)</i>
                                            <?php } ?>
                                        </span>
                                        <?= ($product->comments) ? "<span>({$product->comments})</span>" : "" ?>
                                    </span>
                                    <span class="price ng-binding "><?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($product->unit_price) ?></span>
                                    <span class="price ng-binding "><?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($product->subtotal) ?></span>

                                    <button <?= ($product->product_status == 'dispatched') ? "disabled" : ""; ?> class="button-icon button-simple ng-scope  ng-isolate-scope" onclick="window.location.href='tables/product/remove/<?= $product->id ?>/<?= $table->id ?>'">
                                        <span class="icon icon-x"></span>
                                    </button>
                                </div>
                                <div class="addition-comment ng-hide ">
                                    <p class="ng-binding "></p>
                                </div>
                            </li>
                            <?php $total = $total + $product->subtotal; ?>
                        <?php } ?>
                    </ul>
                    <div class="explain-product">
                        <i class="fa fa-bookmark icon-explain-product" aria-hidden="true"></i>
                        <?= lang('scope_item_advice') ?>
                    </div>

                    <!-- Seccion total products -->
                    <div class="total">
                        <span><?= lang('total') ?>:</span>
                        <strong id="products_added" class="ng-binding " total="<?= $this->sma->formatDecimal($total) ?>"><?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($total) ?></strong>
                    </div>

                    <div class="button-container end">
                        <div ert-can="create_discounts" class="ng-isolate-scope"></div>
                        <?php if ($products && $unconfirmed > 0) { ?>
                            <button id="product_confirm" onclick="window.location.href='tables/order/confirm/<?= $table->id ?>'" class="button button-action btn-confirm-3 u_float_left">
                                <i class="fa fa-exclamation-triangle faa-flash animated icon-alert-confirm" aria-hidden="true"></i>Confirmar
                            </button>
                        <?php } ?>

                        <?php if ($products && $table->status == 1) { ?>
                            <?php if ($product->product_status == 'confirmed') { ?>
                                <button id="prueba" class="button button-action ng-isolate-scope" onclick="ticket()">
                                    Generar ticket
                                </button>
                            <?php } ?>
                            <button onclick="window.location.href='tables/order/bill/<?= $table->id ?>'" class="button button-action ng-isolate-scope">
                                <?= lang('request_bill') ?>
                            </button>
                        <?php } ?>

                        <?php if (!$products && $table->status == 1) { ?>
                            <button onclick="window.location.href='tables/order/close/<?= $table->id ?>'" class="button button-action ng-isolate-scope">
                                <?= lang('free_table') ?>
                            </button>
                        <?php } ?>
                        <?php if ($products && $table->status == 2) { ?>
                            <?php if ($product->product_status == 'confirmed') { ?>
                                <button id="prueba" class="button button-action ng-isolate-scope" onclick="ticket()">
                                    Generar ticket
                                </button>
                            <?php } ?>
                            <button onclick="show_bill()" class="button button-action ng-isolate-scope">
                                <?= lang('bill') ?>
                            </button>

                        <?php } ?>
                    </div>
                </section>
            </div>
        </div>
    </aside>
</div>

<div id="bill_tbl">
    <style>
        .table_bill,
        .table_bill th,
        .table_bill td {
            border-collapse: collapse;
            border-bottom: 1px solid #CCC;
        }

        .no-border {
            border: 0;
        }

        .bold {
            font-weight: bold;
        }

        #bill_span h3 {
            display: block;
        }
    </style>
    <span id="bill_span" style="text-align:center;">
        <h3><?= lang('bill'); ?></h3>
        <p>
            <?php
            echo "<b>";
            if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") {
                echo $pos_settings->cf_value1 . "<br>";
            }
            echo "</b><br>";

            if ($biller->name != "") {
                echo $biller->name . "<br>";
            }

            if ($biller->cf1 != "") {
                echo lang("NIT") . ": " . $biller->cf1 . "<br>";
            }
            echo  $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country .
                "<br>" . lang("tel") . ": " . $biller->phone . "<br>";
            ?>
        </p>

        <h3><?= lang('table') ?> #<?= $table->name ?></h3>
        <p>
            <?= lang("waiter_name") ?>: <?= $waiter_name ?><br>
            <?= lang("customer") ?>: <?= $order->customer ?> <br>
            <?= date("d/m/Y H:i") ?>
        </p>
    </span>
    <table id="bill-table" class="table_bill prT table table-striped" style="margin-bottom:0;width: 100%;">
        <tbody>
            <?php

            $products_array = array();
            foreach ($products as $product) {
                if (!array_key_exists($product->product_code, $products_array)) {
                    // does not exist
                    $product_data = array(
                        'price' => ((floatval($product->unit_price) + floatval($product->item_tax)) * floatval($product->quantity)),
                        'qty' => $this->sma->formatQuantity($product->quantity),
                        'name' => $product->product_name,
                        'item_id' => $product->id
                    );
                    $products_array[$product->product_code] = $product_data;
                } else {
                    $products_array[$product->product_code]['qty'] += $this->sma->formatQuantity($product->quantity);
                    $product_price = ((floatval($product->unit_price) + floatval($product->item_tax)) * floatval($product->quantity));
                    $products_array[$product->product_code]['price'] = ($product_price + $products_array[$product->product_code]['price']);
                }
            }

            $totalbill = 0;
            foreach ($products_array as $product) { ?>
                <tr class="item">
                    <td class="quantity no-border"><?= intval($product['qty']) ?></td>
                    <td class="item-label no-border">
                        <span class="name">
                            <strong>
                                <?= $product['name'] ?>&nbsp;
                            </strong>
                        </span>
                    </td>
                    <td class="price ng-binding" style="text-align:right;"><?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($product['price']) ?></td>
                </tr>
            <?php $totalbill += $product['price'];
            } ?>

        </tbody>
    </table>
    <table id="bill-total-table" class="table_bill prT table" style="margin-bottom:0;">
        <tbody>
            <tr class="bold">
                <td class="total"><?= lang('total_x_tax') ?></td>
                <td style="text-align:right;"><?= $total ?></td>
            </tr>
            <?php if ($this->sma->get_activated_tip()) { ?>
                <tr class="bold tip">
                    <td class="total"><?= lang('tip') ?></td>
                    <td style="text-align:right;"><?= $total * 0.10 ?></td>
                </tr>
            <?php } ?>
            <tr class="bold grand-total">
                <?php if ($this->sma->get_activated_tip()) { ?>
                    <td class="total"><?= lang('total') ?> + <?= lang('tip') ?></td>
                    <td style="text-align:right;"><?= $total + ($total * 0.10) ?></td>
                <?php } else { ?>
                    <td class="total"><?= lang('total') ?></td>
                    <td style="text-align:right;"><?= $total ?></td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="well well-sm" style="text-align: center">
                        <?= $this->sma->decode_html($biller->invoice_footer); ?>
                    </div>
                    <div class="footer-fact" style="text-align: center;font-size: 10px;">
                        Impreso por IGNIWEB POS<br>
                        www.igniweb.com tel: 301 786 2011 - 745 1042
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
/*
Ticket para confirmación de pedido
*/
<div id="ticket">
    <style>
        .table_bill,
        .table_bill th,
        .table_bill td {
            border-collapse: collapse;
            border-bottom: 1px solid #CCC;
        }

        .no-border {
            border: 0;
        }

        .bold {
            font-weight: bold;
        }

        #bill_span h3 {
            display: block;
        }
    </style>
    <span id="bill_span" style="text-align:center;">
        <h3><?= lang('table') ?> #<?= $table->name ?></h3>
    </span>
    <table id="bill-table" class="table_bill prT table table-striped" style="margin-bottom:0;width: 100%;">
        <tbody>
            <?php
            $products_array1 = array();
            $products_array2 = array();
            foreach ($products as $product) {
                if (!array_key_exists($product->product_code, $products_array1)) {
                    // does not exist


                    if ($product->product_status == "confirmed") {
                        //if ($product->product_status == "pending") {
                        if ($product->product_category == 10) {
                            $chef_data = array(
                                'price' => ((floatval($product->unit_price) + floatval($product->item_tax)) * floatval($product->quantity)),
                                'qty' => $this->sma->formatQuantity($product->quantity),
                                'name' => $product->product_name,
                                'item_id' => $product->id,
                                'data' => $product->product_category,
                                'comments' => $product->comments
                            );
                            $products_array1[$product->product_code] = $chef_data;
                        } else {
                            $barman_data = array(
                                'price' => ((floatval($product->unit_price) + floatval($product->item_tax)) * floatval($product->quantity)),
                                'qty' => $this->sma->formatQuantity($product->quantity),
                                'name' => $product->product_name,
                                'item_id' => $product->id,
                                'data' => $product->product_category,
                                'comments' => $product->comments
                            );
                            $products_array2[$product->product_code] = $barman_data;
                        }
                    }
                } else {
                    $products_array[$product->product_code]['qty'] += $this->sma->formatQuantity($product->quantity);
                    $product_price = ((floatval($product->unit_price) + floatval($product->item_tax)) * floatval($product->quantity));
                    $products_array[$product->product_code]['price'] = ($product_price + $products_array[$product->product_code]['price']);
                }
            }

            ?>
            <tr>
                <td colspan="2">
                    Barman
                </td>
            </tr>
            <?php
            foreach ($products_array1 as $product) {
            ?>
                <tr class="item">
                    <td class="quantity no-border"><?= intval($product['qty']) ?></td>
                    <td class="item-label no-border">
                        <span class="name">
                            <strong>
                                <?= $product['name'] ?>&nbsp;
                            </strong>
                            <?= $product['comments'] ?>
                        </span>
                    </td>
                </tr>
            <?php  } ?>
            <tr>
                <td colspan="2">
                    <div style="border-top: 1px; border-style: dashed;"> </div>
                    <small style="text-align: center;">Corte aquí</small>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span id="bill_span" style="text-align:center;">
                        <h3><?= lang('table') ?> #<?= $table->name ?></h3>
                    </span>
                </td>
            </tr>
            <tr>

                <td colspan="2">

                    Chef
                </td>
            </tr>
            <?php foreach ($products_array2 as $product) {
            ?>
                <tr class="item">
                    <td class="quantity no-border"><?= intval($product['qty']) ?></td>
                    <td class="item-label no-border">
                        <span class="name">
                            <strong>
                                <?= $product['name'] ?>&nbsp;
                            </strong>
                            <?= $product['comments'] ?>
                        </span>
                    </td>
                </tr>


            <?php
            } ?>
        </tbody>
    </table>
    <table id="bill-total-table" class="table_bill prT table" style="margin-bottom:0;">
        <tbody>
            <tr>
                <td colspan="2">
                    <div class="footer-fact" style="text-align: center;font-size: 10px;">
                        Impreso por IGNIWEB POS<br>
                        www.igniweb.com tel: 301 786 2011 - 745 1042
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

//end
<script>
    /*
    $('#product_confirm').click(function() {
        Popup($('#ticket').html());
        location.reload();
    });
*/
    function ticket() {
        Popup($('#ticket').html());
    }

    function show_bill() {
        Popup($('#bill_tbl').html());
    }

    function Popup(data) {
        //variables para android
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");

        var mywindow = window.open('', 'bill_print', 'height=500,width=300');
        mywindow.document.write('<html><head><title>Print</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css" type="text/css" />');

        if (!isAndroid) {
            mywindow.document.write('</head><style>.tip{border-top: 2px solid;}.grand-total{border-top: 2px solid;border-bottom: 2px solid;}.table {width: 98%;}/*.well{font-size:8px;line-height:8px;}*/h1, .h1, h2, .h2, h3, .h3 {margin-top: 5px;margin-bottom: 5px;}h3, .h3 {font-size: 18px;font-weight: bold;}h4, .h4 {font-size: 17px;font-weight: bold;}h4, .h4, h5, .h5, h6, .h6 {margin-top: 5px;margin-bottom: 5px;}.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {padding: 5px;font-size: 12px;}.grand-total td{font-size: 13px;}</style><body >');
        } else {
            mywindow.document.write('</head><style>.tip{border-top: 2px solid;}.grand-total{border-top: 2px solid;border-bottom: 2px solid;}.total{font-size: 16px;}.well-sm{font-size: 14px;text-align: justify;}h3, .h3 {font-size: 17.5px;font-weight: bold;}h4, .h4 {font-size: 16.5px;font-weight: bold;}h5, .h5 {font-size: 14px;}.table>tbody>tr>td{font-size: 13px;}@media print {@page {margin: 0mm 5mm 0mm 0mm;}}</style><body >');
        }
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        //        if(!isAndroid) {
        mywindow.print();
        mywindow.close();
        //        }

        return true;
    }

    $(document).ready(function() {
        <?php if (isset($js_nav_category)) { ?>
            $('#category-link-<?= $js_nav_category ?>').click();
        <?php } ?>



    });
</script>