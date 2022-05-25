<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="card">
        <div class="card-header header-elements-sm-inline">
            <h6 class="card-title">
                <span><b>Overview:</b></span>
            </h6>
            <div class="header-elements">
                <form method="get" action="<?= base_url('/admin/checklist-report') ?>">
                    <div class="form-group row">
                        <label class="font-weight-bold mr-1">Status: </label>
                        <div class="form-group" style="text-align-last:center; text-transform: uppercase;">
                            <select name="status" onchange="this.form.submit()">
                                <?php if (isset($_GET['status'])) : ?>
                                    <?php if ($_GET['status'] == 'assign') : ?>
                                        <option value="incomplete">...</option>
                                        <option value="assign" selected>READY TO ASSIGN</option>
                                        <option value="complete">COMPLETE</option>
                                    <?php elseif ($_GET['status'] == 'complete') : ?>
                                        <option value="incomplete">...</option>
                                        <option value="assign">READY TO ASSIGN</option>
                                        <option value="complete" selected>COMPLETE</option>
                                    <?php else : ?>
                                        <option value="incomplete" selected>...</option>
                                        <option value="assign">READY TO ASSIGN</option>
                                        <option value="complete">COMPLETE</option>
                                    <?php endif ?>
                
                                <?php else : ?>
                                    <option value="incomplete" selected>...</option>
                                    <option value="assign">READY TO ASSIGN</option>
                                    <option value="complete">COMPLETE</option>
                                
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" action="checklist-report-save">
            <?php csrf_field() ?>
            <table class="table datatable-basic">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th class="text-center">Client Name</th>
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Investment Date</th>
                        <th class="text-center">Investment Cost</th>
                        <th class="text-center">Cost Left</th>
                        <th class="text-center" style="width: 15%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tempAmount = 0;
                    $tempCostLeft = 0;
                    ?>
                    <?php if ($getAllInvestment->getNumRows() > 0) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($getAllInvestment->getResultArray() as $row) : ?>
                            <?php
                            $newDate = date("M-d-Y", strtotime($row['date']));
                            ?>
                            <?php if ($row['cost_left'] > 0) : ?>
                                <tr class="table-active">
                                <?php else : ?>
                                <tr class="table-warning">
                                <?php endif ?>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="font-weight-bold"><?= $row['fullname'] ?></td>
                                <td><?= $row['company'] ?></td>
                                <td class="text-center font-weight-bold"><?= strtoupper($newDate) ?></td>
                                <td class="font-weight-bold">$<?= number_format($row['cost'], 2) ?></td>
                                <td class="font-weight-bold"><?= number_format($row['cost_left'], 2) ?></td>
                                <td class="text-center font-weight-bold">
                                    <select name="status[]" class="status_change" style="text-align:center; font-weight:800">
                                        <?php if ($row['status'] == 'complete') : ?>
                                            <option value="incomplete">...</option>
                                            <option value="complete" selected>COMPLETE</option>
                                            <option value="assign" data-foo='<?= $row['cost_left'] ?>'>READY TO ASSIGN</option>
                                        <?php elseif ($row['status'] == 'assign') : ?>
                                            <option value="incomplete">...</option>
                                            <option value="complete" data-foo='<?= $row['cost_left'] ?>'>COMPLETE</option>
                                            <option value="assign" data-foo='<?= $row['cost_left'] ?>' selected>READY TO ASSIGN</option>
                                        <?php else : ?>
                                            <option value="incomplete" selected>...</option>
                                            <option value="complete" data-foo='<?= $row['cost_left'] ?>'>COMPLETE</option>
                                            <option value="assign" data-foo='<?= $row['cost_left'] ?>'>READY TO ASSIGN</option>
                                        <?php endif ?>
                                    </select>
                                    <input type="hidden" name="investment_id[]" value="<?= $row['id'] ?>">
                                </td>
                                </tr>
                                <?php

                                $tempAmount =  $tempAmount + $row['cost'];
                                $tempCostLeft =  $tempCostLeft + $row['cost_left'];

                                ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </tbody>
            </table>
            <div class="card-body">
                <button type="submit" class="btn btn-secondary"><i class="icon-checkmark3"></i> Save</button>
            </div>
        </form>
    </div>
    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="/assets/js/demo_pages/picker_date.js"></script>
<script src="/assets/js/plugins/pickers/daterangepicker.js"></script>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
    });

    $('.status_change').on('change', function() {
        var value = $(this).find('option:selected').data('foo');
        if ($(this).val() == 'complete' && value > 0) {
            swal("Are you sure?", "The cost left over than 0");
        }
    });


    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully save the report.',
            type: 'alert'
        }).show();
    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the report.',
            type: 'alert'
        }).show();
    });
</script>

<?= $this->endSection() ?>