<?php
/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 06/08/16
 * Time: 10:37
 */
?>

<div id="body">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs tabs-responsive">
                        <?php foreach ($categories as $categorie) { ?>
                            <li class="ng-scope <?= ($category->id == $categorie['id']) ? 'active' : '' ?>">
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
        <div class="wrapper table-grid-wrapper" id="div-btncantidadpersonas">
            <div class="content u-calc-05">
                <ul id="js_imgBox" class="clearfix cateList mt5 u_list_type_none">
                    <?php foreach ($products as $product) { ?>
                        <li>
                            <div class="proBox <?= ($product->status) ? "" : "disable-div-product" ?>">
                                <?php if ($product->status) { ?>
                                <a href="tables/product/view/<?= $product->id ?>/<?= $table->id ?>" class="proImg_a ">
                                <?php } ?>
                                    <div class="all_proImg">
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
                                    <?php } ?> 
                                    <?php if (intval($product->quantity) < 1 && intval($product->quantity) == NULL) { ?>
                                        <div class="all_proStock">(<?= lang('unavailable') ?>)</div>
                                    <?php } ?>
                                <?php if ($product->status) { ?>
                                </a>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </main>
</div>
