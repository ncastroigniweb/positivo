<div id="body">
    <main>
        <div class="wrapper-title">
            <div class="content">
                <h1>
                    <ul class="title-tabs ">
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
        
        <?php put_header_alerts($message,$error,$warning,$info); ?>
        
        <div class="wrapper table-grid-wrapper">
            <div class="content tables-container calc_05">
                <div class="table-grid u-table-grid">
                    <div class="table-placeholder status-free u-absolute-let-top">
                        <a href="/tables/order/create/<?= $table->id ?>" class="table">
                            <div class="table-indicators">
                                <div class="table-number margin-element">
                                    <span class="ng-binding "><?= $table->name ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <aside ui-view="" class="ng-scope ">
        <div class="content">
            <div>
                <h2 class="status-free ">
                    <span class="ng-binding "><?= lang('table') ?> <?= $table->name ?></span>
                </h2>
                <section>

                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'neworder', 'method' => "post");
                    echo form_open("tables/order/create/{$table->id}", $attrib);?>

                        <ul class="list edit">
                            <?php if($this->sma->is_admin() || $this->sma->is_cashier()) { ?>
                                <li>
                                    <label class="required">
                                        <span><?= lang('waiter') ?></span>
                                        <select class="form-control" name="waiter" required="">
                                            <?php foreach ($this->restaurant->getWaiters() as $waiter){ ?>
                                                <option value="<?= $waiter->id ?>"><?= "{$waiter->first_name} {$waiter->last_name}" ?></option>
                                            <?php } ?>
                                        </select>
                                    </label>
                                </li>
                            <?php } ?>
                            <li>
                                <label class="required">
                                    <span><?= lang('guests') ?></span>
                                    <input max="50" min="1" name="guests" required="" type="number" value="<?= ($table->guests == 0) ? 1 : $table->guests ?>">
                                    <a class="help-tooltip">
                                        <span class="ng-scope "><?= lang('scope_order_create') ?></span>
                                    </a>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <span><?= lang('comments') ?></span>
                                    <textarea name="comments"><?= $table->comments ?></textarea>
                                </label>
                            </li>
                        </ul>

                        <div class="button-container indent">
                            <button type="submit" class="button button-action"><?= lang('book_table') ?></button>
                        </div>

                        <input type="hidden" name="table" value="<?= $table->id ?>">

                    <?php echo form_close(); ?>

                    <script>
                        $("button").click(function () {
                            var $btn = $(this);
                            $btn.button('loading');
                            // simulating a timeout
                            setTimeout(function () {
                                $btn.button('reset');
                            }, 1000);
                        });
                    </script>
                </section>
            </div>
        </div>
    </aside>
</div>