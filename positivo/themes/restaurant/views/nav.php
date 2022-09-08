<?php
/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 23/06/16
 * Time: 14:44
 */

?>

<?php
    list($flag, $suspended_bill) = $this->sma->getUriOrderTable();
    $url_concat = '';
    if($flag && $suspended_bill != 0){
        $url_concat = '/index/' . $suspended_bill;
    }else{
        $this->sma->clean_Storage();
    }
?>
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle u_float_right" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
            <span class="sr-only"><?= lang('show_nav'); ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <button class="<?= ($nav['active'] == 'notify') ? "active" : ""; ?> navbar-toggle notify-container button-notify-mobile hide">
            <a href="/tables/notifications/view">
                <i class="fa fa-bell faa-ring animated icon-header" aria-hidden="true"
                   title="<?= lang('notifications') ?>"></i>
            </a>
                <span class="notify-alert">
                    0
                </span>
        </button>
        <a class="brand" href="/tables"></a>
    </div>
    
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav menu-main">
            <?php if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_waiter() || $this->sma->is_product_admin()) { ?>
                <li class="<?= ($nav['active'] == 'home') ? "active" : ""; ?>">
                    <a href="/tables">
                        <i class="fa fa-table icon-header" aria-hidden="true" title="<?= lang('tables') ?>"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($this->sma->is_admin() || $this->sma->is_product_admin()) { ?>

                <li class="<?= ($nav['active'] == 'clients') ? "active" : ""; ?>">
                    <a href="/customers">
                        <i class="fa fa-users icon-header" aria-hidden="true" title="<?= lang('customers') ?>"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_chef() || $this->sma->is_product_admin()) { ?>
                <li class="<?= ($nav['active'] == 'kitchen') ? "active" : ""; ?>">
                    <a href="/chef">
                        <i class="fa fa-cutlery icon-header" aria-hidden="true" title="<?= lang('kitchen') ?>"></i>
                    </a>
                </li>
                
                <li class="<?= ($nav['active'] == 'chef/dispatched') ? "active" : ""; ?>">
                    <a href="/chef/dispatched">
                        <i class="fa fa-thumbs-up icon-header" aria-hidden="true" title="<?= lang('dispatched_items_list') ?>"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_barman() || $this->sma->is_product_admin()) { ?>
                <li class="<?= ($nav['active'] == 'barman') ? "active" : ""; ?>">
                    <a href="/barman">
                        <i class="fa fa-coffee icon-header" aria-hidden="true" title="<?= lang('drinks') ?>"></i>
                    </a>
                </li>
                
                <li class="<?= ($nav['active'] == 'barman/dispatched') ? "active" : ""; ?>">
                    <a href="/barman/dispatched">
                        <i class="fa fa-thumbs-up icon-header" aria-hidden="true" title="<?= lang('dispatched_items_list') ?>"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_product_admin()) { ?>
                <li>
                    <a href="/pos<?= $url_concat; ?>">
                        <i class="fa fa-credit-card icon-header" aria-hidden="true"
                           title="<?= lang('pos_module') ?>"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($this->sma->is_admin() || $this->sma->is_product_admin()) { ?>
                <li class="<?= ($nav['active'] == 'admin') ? "active" : ""; ?>">
                    <a href="/system_settings">
                        <i class="fa fa-cog icon-header" aria-hidden="true" title="<?= lang('admin') ?>"></i>
                    </a>
                </li>
            <?php } ?>


            <li class="<?= ($nav['active'] == 'notify') ? "active" : ""; ?> notify-container no-mobile hide">
                <a href="/tables/notifications/view">
                    <i class="fa fa-bell faa-ring animated icon-header" aria-hidden="true"
                       title="<?= lang('notifications') ?>"></i>
                </a>
                <span class="notify-alert">
                    0
                </span>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li>
                <?php include "date.php"; ?>
            </li>

            <li class="dropdown">
                <a class="nav-button dropdown-toggle" title="<?= lang('language') ?>"
                   data-placement="bottom" data-toggle="dropdown" href="#">
                    &nbsp;<img src="<?= base_url('assets/images/' . $Settings->user_language . '.png'); ?>"
                               alt="<?= lang('language') ?>">&nbsp;
                </a>
                <ul class="dropdown-menu">
                    <?php $scanned_lang_dir = array_map(function ($path) {
                        return basename($path);
                    }, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
                    foreach ($scanned_lang_dir as $entry) { ?>
                        <li>
                            <a href="<?= site_url('lang/' . $entry); ?>">
                                <img src="<?= base_url(); ?>assets/images/<?= $entry; ?>.png" class="language-img">
                                &nbsp;&nbsp;<?= ucwords($entry); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-button dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user icon-user" aria-hidden="true"></i>
                    <span class="nav-label ng-binding ">
                        <?= $this->session->userdata('username') ?>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <?php if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_waiter() || $this->sma->is_product_admin()) { ?>
                        <li class="divider"></li>
                    <?php } ?>
                    <li><a href="/logout"><?= lang('logout') ?></a></li>
                </ul>
            </li>

            <li class="dropdown nav-dropdown">
                <a href="#" data-toggle="dropdown" class="nav-button dropdown-toggle notify-dropdown">
                    <span class="nav-icon icon-system-notifications"></span>
                    <?php $stock = $this->site->get_total_qty_alerts(); ?>
                    <span
                        class="nav-notifications-count ng-binding <?= ($stock != 0) ? 'u_display_block_im' : 'u_display_none'; ?>">
                        <?= $stock ?>
                    </span>
                    <div
                        class="nav-notifications-bubble ng-hide <?= ($stock != 0) ? 'u_display_none' : 'u_display_none'; ?>">
                        <?= lang('new_notifications') ?>
                    </div>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="/notifications"><?= lang('see_notifications') ?></a></li>
                </ul>
            </li>

        </ul>
    </div>
</nav>

<div class="subtop">
    <div class="subtop-wrapper ng-isolate-scope ">
        <ul class="subtop-tabs u_height_4em">
            <?php if (isset($parent_category) && $parent_category) { ?>
                <?php foreach ($categories_parents as $cat_key => $categorie) { ?>
                    <li class="<?= ($category->parent_id == $categorie['id']) ? 'active' : '' ?>">
                        <a onclick="$('#link-category-<?= $categorie['id']; ?>').submit();" data-placement="right">
                            <span class="room-label ng-binding "><?= $categorie['name'] ?></span>
                        </a>
                        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'link-category-' . $categorie['id'], 'method' => "post", 'class' => "hide");
                        echo form_open("tables/order/edit/{$table->id}", $attrib); ?>
                        <input type="hidden" name="category_id" value="<?= $categorie['id']; ?>">
                        <?php echo form_close(); ?>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <li class="active">
                    <a ui-sref="tables" class="no-link" href="javascript:void(0)">
                        <span><?= $nav['title_top'] ?></span>
                    </a>
                </li>
            <?php } ?>
            <li class="u_right_white">
                <button onclick="history.back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> <?= lang('back') ?>
                </button>
            </li>
        </ul>
    </div>

    <?php if ($nav['enable_search']) { ?>
        <div class="subtop-search ng-isolate-scope " focus="focus">
            <?php $attrib = array('role' => 'form', 'id' => 'form1', 'method' => "post", "class" => 'ng-valid-min ng-pristine ng-invalid ng-invalid-required');
            echo form_open("tables", $attrib); ?>
            <label>
                <div class="icon icon-search"></div>
                <input name="table" onblur="$('#form1').submit()"
                       placeholder="<?= lang('search_table') ?>" required=""
                       class="ng-isolate-scope ng-valid-min ng-pristine ng-invalid ng-invalid-required ng-touched">
            </label>
            <button type="submit" id="mes_numero" class="button button-action hide"></button>
            <?= form_close(); ?>
        </div>
    <?php } ?>
</div>

