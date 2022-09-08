<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= $customer->company && $customer->company != '-' ? $customer->company : $customer->name; ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table text-center">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col"><?= lang("id_number", "id_number"); ?></th>
                      <th scope="col"><?= lang("name", "name"); ?></th>
                      <th scope="col"><?= lang("gender", "gender"); ?></th>
                      <th scope="col"><?= lang("blood_type", "blood_type"); ?></th>
                      <th scope="col"><?= lang("born", "born"); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row"><?= $customer->docu_num; ?></th>
                      <td><?= $customer->name; ?></td>
                      <td><?= $customer->gender; ?></td>
                      <td><?= $customer->blood_type; ?></td>
                      <td><?= $customer->born; ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="margin-bottom:0;">
                    <tbody>
                        <tr>
                            <td><strong><?= lang("beneficiary_name", "beneficiary_name"); ?></strong></td>
                            <td><?= $customer->company; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?= lang("customer_group"); ?></strong></td>
                            <td><?= $customer->customer_group_name; ?></strong></td>
                        </tr>

                        <tr>
                            <td><strong><?= lang("email"); ?></strong></td>
                            <td><?= $customer->email; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?= lang("phone"); ?></strong></td>
                            <td><?= $customer->phone; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?= lang("address"); ?></strong></td>
                            <td><?= $customer->address; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?= lang("eps", "eps"); ?></strong></td>
                            <td><?= $customer->cf5; ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong><?= lang("how_meet", "how_meet"); ?></strong></td>
                            <td><?= $customer->cf6; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table text-center">
                  <thead class="thead-dark">
                    <tr class="table-info">
                      <th scope="col"><?= lang("country"); ?></th>
                      <th scope="col"><?= lang("state"); ?></th>
                      <th scope="col"><?= lang("city"); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $customer->country; ?></td>
                      <td><?= $customer->state; ?></td>
                      <td><?= $customer->city; ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
                <?php if ($Owner || $Admin || $GP['reports-customers']) { ?>
                    <a href="<?=site_url('reports/customer_report/'.$customer->id);?>" target="_blank" class="btn btn-primary"><?= lang('customers_report'); ?></a>
                <?php } ?>
                <?php if ($Owner || $Admin || $GP['customers-edit']) { ?>
                    <a href="<?=site_url('customers/edit/'.$customer->id);?>" data-toggle="modal" data-target="#myModal2" class="btn btn-primary"><?= lang('edit_customer'); ?></a>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>