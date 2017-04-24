<?php
/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 06/08/16
 * Time: 10:37
 */

$first_key = key($categories);
?>

<div id="body">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs ">
                        <?php foreach($categories as $cat_key => $categorie){ ?>
                            <li class="ng-scope <?= ($first_key == $cat_key) ? 'active' : ''?>">
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
                    <?php foreach($subcategories as $parent => $categories){ ?>
                        <div id="<?= $parent ?>" class="tab-pane fade in <?= ($first_key == $parent) ? 'active' : ''?> <?= $first_key ." - ". $parent ?>">
                            <div class="calc_05 u_margin_20">
                                <div class="quick-add">
                                    <?php foreach($categories as $category) { ?>
                                        <button
                                            onclick=" window.location.href='tables/category/view/<?= $category['id'] ?>/<?= $table->id ?>'"
                                            class="button ng-scope" title="<?= $category['name'] ?>">
                                            <span class="product-code ng-binding "><?= $category['id'] ?></span>
                                            <span class="product-label ng-binding "><?= $category['name'] ?></span>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <aside ui-view="" class="ng-scope">
        <div class="content">
            <div class="ng-scope">
                <h2 class="<?= ($table->status == 2) ? "status-payment" : "status-occupied" ?>">
                    <span class="ng-binding ">
                         <?= lang('table') ?> : <?= $table->name ?> (<?= $order->customer ?>)
                    </span>

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
                <h3>
                    <span><?= lang('load_customer')  ?></span>
                </h3>
                <section>

                    <?php $attrib = array('role' => 'form', 'id' => 'loadcustomer', 'method' => "post");
                    echo form_open("tables/customer/load/{$table->id}", $attrib); ?>

                    <div id="customers-form-1">


                        <ul class="list edit">
                            <li class="u_padding_22">
                                <label class="required u_max_width_40rem">
                                    <span><?= lang('cname') ?></span>
                                    <select class="form-control u_width_24rem" name="customer" required="">
                                        <?php foreach ($this->restaurant->getCustomers() as $id => $customer){ ?>
                                            <option value="<?= $id ?>" <?= ($order->customer_id == $id) ? "selected" : "" ; ?> ><?= $customer ?></option>
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

                    <div>
                        <h3>
                            <span><?= lang('new_customer')  ?></span>
                        </h3>

                        <?php $attrib2 = array('role' => 'form', 'id' => 'createcustomer', 'method' => "post");
                        echo form_open("tables/customer/create/{$table->id}", $attrib2); ?>

                        <div id="customers-form-2">
                            <ul class="list edit">
                                <li class="u_paddingtop_22">
                                    <label class="required u_max_width_40rem">
                                        <span><?= lang('cname') ?></span>
                                        <input name="cname"  class="u_width_24rem" required="">
                                    </label>
                                </li>
                                <li>
                                    <label class="u_max_width_40rem">
                                        <span><?= lang('email') ?></span>
                                        <input name="email"  class="u_width_24rem" type="email">
                                    </label>
                                </li>
                                <li>
                                    <label class="u_max_width_40rem">
                                        <span><?= lang('phone') ?></span>
                                        <input name="phone"  class="u_width_24rem" type="number">
                                    </label>
                                </li>
                                <li>
                                    <label class="u_max_width_40rem">
                                        <span><?= lang('address') ?></span>
                                        <input name="address" class="u_width_24rem">
                                    </label>
                                </li>
                            </ul>

                            <div class="button-container">
                                <button class="btn btn-success">
                                    <span class="ng-scope"><?= lang('new_customer') ?></span>
                                </button>
                            </div>
                        </div>

                        <?php echo form_close(); ?>

                    </div>
                </section>
            </div>
        </div>
    </aside>
</div>

