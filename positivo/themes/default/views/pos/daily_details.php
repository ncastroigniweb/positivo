<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <?php if ($Owner || $Admin) { ?>
                <div class="filter-dates no-print">
                    <h2><?= lang("search_by_date") ?></h2>
                    <?= lang("date_start", "date"); ?>
                    <?= form_input('date', (""), 'class="form-control datetime" id="date_start" required="required"'); ?>
                    <?= lang("date_end", "date"); ?>
                    <?= form_input('date', (""), 'class="form-control datetime" id="date_end" required="required"'); ?>
                    <div style="margin-top: 10px">
                        <input type="button" name="filter_register" id="filter_register" value="Buscar" class="btn btn-primary">
                        <hr>
                    </div>
                </div>
            <?php } ?>
            <div style="clear: both;"></div>
            <p class="text-center">

                <?php

                echo $biller->company . "<br>";

                if ($biller->name != "") {
                    echo $biller->name . "<br>";
                }

                if ($biller->cf1 != "") {
                    echo lang("NIT"). ": " . $biller->cf1 . "<br>";
                }

                echo $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country;

                ?></p>
                <p class="text-center"><?= lang("date") ?> : <span class="d-date"><?= $this->sma->hrld($this->session->userdata('register_open_time')) . '</span> - <span class="d-date-end">' . $this->sma->hrld(date('Y-m-d H:i:s')) ?></span><br>
                    <?= lang('invoice_range') ?> : <span class="d-min-ref"><?= (int)$min_reference ?></span> - <span class="d-max-ref"><?= (int)$max_reference ?></span></p>
        </div>
        <div class="modal-body">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('exempt_sales') ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>0.00</span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('sales_excluded') ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>0.00</span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('gross_sales') ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-gross-sales"><?= $this->sma->formatMoney($discountsales->discount + $totalsales->paid - $taxsales->tax - $tipsales->tip) ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('discounts'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-discounts"><?= $this->sma->formatMoney($discountsales->discount ? $discountsales->discount : '0.00') ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('tax_sale_cash'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-cash-tax"><?= $this->sma->formatMoney($taxsalesCash->tax ? $taxsalesCash->tax : '0.00') ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('tax_sale_cc'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-CC-tax"><?= $this->sma->formatMoney($taxsalesCC->tax ? $taxsalesCC->tax : '0.00') ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('tax_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-tax"><?= $this->sma->formatMoney($taxsales->tax ? $taxsales->tax : '0.00') ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales_tax'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span class="d-sales-tax"><?= $this->sma->formatMoney($totalsales->paid ? $totalsales->paid - $taxsales->tax : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td width="300px;" style="font-weight:bold;border-bottom: 1px solid #DDD;"><h4><?= lang('saved_sales'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span class="d-saved-sales"><?= $this->sma->formatMoney($totalsales->paid ? $totalsales->paid - $taxsales->tax - $tipsales->tip : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_sales'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-cash-sales"><?= $this->sma->formatMoney($cashsales->paid ? $cashsales->paid - $taxsalesCash->tax - $tipsales->tip : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('sales_incheck'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span>0.00</span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('sales_bycard'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-sales-bycard"><?= $this->sma->formatMoney($ccsales->paid ? $ccsales->paid - $taxsalesCC->tax - ( $cashsales->paid ? 0 : $tipsales->tip ): '0.00'); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('sales_tocredit'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>0.00</span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('bonds'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span>0.00</span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('no_transactions'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span class="d-num-ref"><?= $num_reference ?></span>
                        </h4></td>
                </tr>
                <?php if(!empty($products_tax)){ ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('product_tax'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span class="d-products-tax"><?php $product_tax = $products_tax ? $products_tax->product_tax : 0; echo $this->sma->formatMoney($product_tax); ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;border-bottom: 1px solid #DDD;"><h4><?= lang('registered'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span class="d-registered"><?= $this->sma->formatMoney($totalsales->paid ? $totalsales->paid - $taxsales->tax - $tipsales->tip : '0.00'); ?></span>
                        </h4></td>
                </tr>
            </table>
            
            <div id="div_sales_details">
                <table width="100%" class="stable">
                    <thead>
                        <tr>
                            <th><h4><?= lang('invoice'); ?></h4></th>
                            <th><h4><?= lang('cash'); ?></h4></th>
                            <th><h4><?= lang('CC'); ?></h4></th>
                            <th><h4><?= lang('actions'); ?></h4></th>
                        </tr>
                    </thead>
                    <tbody class="d-sales-table">
                        <?php foreach ($sales as $sale) { ?>
                            <tr>
                                <td><h4><span><?= (int) $sale['reference']; ?></span></h4></td>
                                <td><h4><span><?= (isset($sale['paymentCash'])) ? $this->sma->formatMoney($sale['paymentCash']['total']) : '-' ; ?></span></h4></td>
                                <td><h4><span><?= (isset($sale['paymentCC'])) ? $this->sma->formatMoney($sale['paymentCC']['total']) : '-' ; ?></span></h4></td>
                                <td><a href="<?= base_url() . 'pos/view/' . $sale['id']; ?>" target="_blank" title="<?= lang('view_receipt'); ?>"><i class="fa fa-file-text-o"></i></a>
                                    <a href="<?= base_url() . 'sales/payments/' . $sale['id']; ?>" title="<?= lang('view_payments'); ?>" data-toggle="modal" data-target="#myModal3"><i class="fa fa-money"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                            <tr class="total_bills">
                                <td><h4><span class="lang-total-paying"><?= lang('total_paying'); ?></span></h4></td>
                                <td><h4><span><?= $this->sma->formatMoney($total_cash); ?></span></h4></td>
                                <td><h4><span><?= $this->sma->formatMoney($total_CC); ?></span></h4></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function () {
        $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
        $("#date_start").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'sma',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
        $("#date_end").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'sma',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
        
        $('#filter_register').click(function () {
            $.ajax({
                type: "get",
                url: "<?=site_url('pos/daily_details_filter')?>",
                data: {date: $('#date_start').val(), date_end: $('#date_end').val()},
                dataType: "json",
                success: function (data) {
                    // set values
                    $('.d-date').text($('#date_start').val());
                    $('.d-date-end').text($('#date_end').val());
                    $('.d-min-ref').text(parseInt( (data.min_reference != "") ? data.min_reference : '0' ) );
                    $('.d-max-ref').text(parseInt( data.max_reference) );
                    $('.d-gross-sales').text('$' + formatMoney(parseInt(data.discountsales.discount) + parseInt(data.totalsales.paid) - parseInt(data.taxsales.tax) - parseInt(data.tipsales.tip)));
                    $('.d-discounts').text('$' + formatMoney( (data.discountsales.discount) ? data.discountsales.discount : "0.00"));
                    $('.d-cash-tax').text('$' + formatMoney( (data.taxsalesCash.tax) ? data.taxsalesCash.tax : "0.00"));
                    $('.d-CC-tax').text('$' + formatMoney( (data.taxsalesCC.tax) ? data.taxsalesCC.tax : "0.00"));
                    $('.d-tax').text('$' + formatMoney( (data.taxsales.tax) ? data.taxsales.tax : "0.00"));
                    $('.d-sales-tax').text('$' + formatMoney( (data.totalsales.paid) ? data.totalsales.paid - (data.taxsales.tax ? data.taxsales.tax : 0 ) : "0.00"));
                    $('.d-saved-sales').text('$' + formatMoney(data.totalsales.paid ? parseInt(data.totalsales.paid) - parseInt(data.taxsales.tax) - parseInt(data.tipsales.tip) : '0.00'));
                    $('.d-cash-sales').text('$' + formatMoney(data.cashsales.paid ? parseInt(data.cashsales.paid) - parseInt(data.taxsalesCash.tax) - parseInt(data.tipsales.tip) : '0.00'));
                    $('.d-sales-bycard').text('$' + formatMoney(data.ccsales.paid ? parseInt(data.ccsales.paid) - parseInt(data.taxsalesCC.tax) - ( data.cashsales.paid ? 0 : parseInt(data.tipsales.tip) ) : '0.00'));
                    $('.d-num-ref').text(data.num_reference);
                    $('.d-registered').text('$' + formatMoney(data.totalsales.paid ? parseInt(data.totalsales.paid) - parseInt(data.taxsales.tax) - parseInt(data.tipsales.tip) : '0.00'));
                    
                    if(data.products_tax != null){
                        $('.d-products-tax').text('$' + formatMoney(data.products_tax.product_tax ? parseInt(data.products_tax.product_tax) : '0.00'));
                    }
                    
                    
                    var html = "";
                    $.each(data.sales, function(i, sale) {
                        var paymentCash = (sale.paymentCash) ? '$' + formatMoney(sale.paymentCash.total) : "-";
                        var paymentCC = (sale.paymentCC) ? '$' + formatMoney(sale.paymentCC.total) : "-";
                        html += '\
                            <tr>\
                                <td><h4><span>'+ parseInt(sale.reference) +'</span></h4></td>\
                                <td><h4><span>'+ paymentCash  +'</span></h4></td>\
                                <td><h4><span>'+ paymentCC +'</span></h4></td>\
                                <td><a href="/pos/view/'+ sale.id +'" target="_blank" ><i class="fa fa-file-text-o"></i></a>\
                                    <a href="sales/payments/' + sale.id +'" data-toggle="modal" data-target="#myModal3"><i class="fa fa-money"></i></a>\
                                </td>\
                            </tr>';
                    });

                    html += '\
                        <tr class="total_bills">\
                            <td><h4><span class="lang-total-paying">'+ $('.lang-total-paying').text() +'</span></h4></td>\
                            <td><h4><span>$'+ formatMoney(data.total_cash) +'</span></h4></td>\
                            <td><h4><span>$'+ formatMoney(data.total_CC) +'</span></h4></td>\
                        </tr>';

                    $('.d-sales-table').html("");
                    $('.d-sales-table').html(html);
                }
            });
        });
    });
</script>



