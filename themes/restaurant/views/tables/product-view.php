<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of product-view
 *
 * @author Juan Manuel
 */

?>

<div id="body">
    <div class="wrapper-title">
        <div class="content">
            <h1>
                <ul class="title-tabs tabs-responsive">
                    <?php foreach ($categories as $categorie) { ?>
                        <li class="ng-scope <?= ($product->subcategory_id == $categorie['id']) ? 'active' : '' ?>">
                            <a href="tables/category/view/<?= $categorie['id'] ?>/<?= $table->id ?>"
                               data-placement="right">
                                <span class="room-label ng-binding "><?= $categorie['name'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </h1>
        </div>
    </div>

    <div class="goodsBox">
        <section class="attr_img">
            <h2 class="u-no-margin"><?= $product->name ?>&nbsp;
                <span class="unavailable">
                    <?= (intval($product->quantity) < 1 && intval($product->quantity) == NULL) ? "(" . lang('unavailable') . ")" : ""; ?>
                </span>
            </h2>
            <div class="img_box bs_bbox">
                <div class="bx-wrapper u_max_width_100">
                    <div class="bx-viewport bx-viewport-container">
                        <img src="assets/uploads/<?= $product->image ?>" height="250" width="250">
                    </div>
                </div>
            </div>
            <div class="goods_attr">
                <div class="attr_color">
                    <strong class="attr_name"><?= strip_tags($product->product_details) ?></strong>
                </div>
            </div>
            
            <?php if(!($product->status)){ ?>
                    <div class="div_product_not_enable">
                        <label class="product_not_enable">
                            <?= lang('view-product-unavailable'); ?>
                        </label>
                    </div>
            <?php } ?>
            
            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add2order', 'method' => "post");
            echo form_open("tables/product/add2order/{$product->id}/{$table->id}", $attrib); ?>

            <ul class="list show items stated additions u-no-margin">
                <li class="status-new ng-scope">
                    <div class="item">
                        <div class="incrementer">
                            <a id="min">
                                <div class="icon icon-minus"></div>
                            </a>
                            <input name="qty" id="orp_cantidad" class="shy ng-pristine"
                                   max="<?= ($product->quantity != 0) ? intval($product->quantity) : "99" ?>" min="1"
                                   required="" value="1" type="number">
                            <a id="plus">
                                <div class="icon icon-plus"></div>
                            </a>
                        </div>
                        <strong class="item-label ng-binding " title=""><?= $product->name ?></strong>
                        <?php if($product->price != 0){ ?>
                            <div class="ng-scope ">
                                <label class="ng-binding "><?= $this->Settings->symbol ?>
                                    &nbsp;<?= $this->sma->formatDecimal($product->price) ?></label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="addition-comment ng-hide ">
                        <input class="shy ng-pristine " name="comments" placeholder="<?= lang("add_comment"); ?>">
                    </div>
                </li>
            </ul>
            <?php if ($product->options) { ?>
                <div class="form-group product-variants">
                    <?php foreach ($product->options as $option) { ?>
                        <div class="u_disply_inlineblock">
                            <input type="radio" name="product_option" class="radio" value="<?= $option->id ?>"
                                   id="po_<?= $option->id ?>" <?= (reset($product->options[0]) == $option->id) ? "checked" : "" ?>>
                            <label for="po_<?= $option->id ?>"><?= $option->name ?> <i>(<?= $this->Settings->symbol ?><?= $this->sma->formatDecimal($option->price) ?>)</i></label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="button-container end status-new u-no-margin">
                <button class="button button-action <?= ($product->status) ? "" : "disabled_product" ?>" type="submit" tabindex="2" <?= ($product->status) ? "" : "disabled" ?> ><?= lang("confirm"); ?></button>
                <a href="tables/category/view/<?= $product->subcategory_id ?>/<?= $table->id ?>"
                   class="button button-action"><?= lang("cancel"); ?></a>
            </div>

            <?php echo form_close(); ?>
        </section>
    </div>
</div>