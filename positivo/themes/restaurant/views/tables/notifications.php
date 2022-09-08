<?php
/**
 * Created by PhpStorm.
 * User: Igniweb038
 * Date: 06/09/16
 * Time: 09:46
 */

?>

<div id="body">
    <main class="ng-isolate-scope ">
        <div class="wrapper-title">
            <div class="content">
                <h1><span><?= lang('notifications') ?></span>
                </h1>
            </div>
        </div>
        <div class="wrapper">
            <div class="content">
                <div class="ng-isolate-scope ">
                    <div class="data-wrapper">
                        <table class="data stated tall notifications">
                            <thead>
                                <tr>
                                    <th class="col-icon"></th>
                                    <th><?= lang('subject') ?></th>
                                    <th class="col-date"><?= lang('date') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notifications as $notify) { ?>
                                    <tr class="ng-scope status-negative"
                                        onclick=" window.location.href='tables/notifications/read/<?= $notify->id ?>/<?= $notify->product_table ?>'">
                                        <th class="ng-binding "></th>
                                        <td>
                                            <p>
                                                <?= str_replace(array("%product%","%table%"),array($notify->product_name, $notify->table_name), lang('message_notify')); ?>
                                            </p>
                                        </td>
                                        <td>
                                            <strong class="status-label ng-binding  ng-scope ">
                                                <?= $notify->date_dispatched ?>
                                            </strong>
                                        </td>
                                        <td class="col-icon"></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div>
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