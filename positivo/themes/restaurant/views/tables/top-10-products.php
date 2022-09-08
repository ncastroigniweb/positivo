
<?php 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<ul id="js_imgBox" class="clearfix cateList mt5 u_list_type_none">
    <?php
    if(!empty($top_10_products)) {
        foreach ($top_10_products as $key => $product) { ?>
    <li>
        <?php 
            $onclick = '';
            if ($product->status) { 
                $onclick = 'onclick="charge_product(' . $product->id . ',\'' . $product->name . '\',\'' . $this->Settings->symbol . $this->sma->formatDecimal($product->price) . '\')"';
            } 
        ?>
        
        <div id="product_<?= $product->id ?>" class="proBox <?= ($product->status) ? "able-product" : "disable-div-product" ?>" <?= $onclick ?>>
            <div class="all_proImg" >
                <img class="img-rounded u-image-100-table"
                     src="assets/uploads/<?= $product->image ?>">
                <p class="addFav"></p>
            </div>
            <div class="all_proNam">
                <?= $product->name ?>
            </div>
            <div class="all_price">
                <?php if($product->price != 0){ ?>
                    <span class="bz_icon"><?= $this->Settings->symbol ?></span>
                    <span class="my_shop_price"><?= $this->sma->formatDecimal($product->price) ?></span>
                <?php } ?>
            </div>
            <?php if (!($product->status)) { ?>
                <div class="all_status">
                    <span class="product-status"><?= lang('product-unavailable') ?></span> 
                </div>
            <?php } else { 
                if($product->options){ ?>
                <button class="more_options" onclick="details_product(<?= $product->id ?>,<?= $table->id ?>)">
                    <div>
                        <i class="fa fa-list-ul" aria-hidden="true"></i> 
                    </div>
                </button>
                <?php } ?> 
                <button id="add_product_<?= $product->id ?>" class="button button-action add_product" onclick="ajax_add_product(<?= $product->id ?>,<?= $table->id ?>)">
                    <span class="icon icon-plus u-icon-user-plus"></span>
                </button>
            <?php } ?> 
            <?php if (intval($product->quantity) < 1 && intval($product->quantity) == NULL) { ?>
                <div class="all_proStock">(<?= lang('unavailable') ?>)</div>
            <?php } ?>
        </div>
    </li>
    <?php }
    } ?>
</ul>