<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin-right: 20px;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-file-upload mr-2"></i>Upload Report</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Upload Assignment Report</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('upload-assignment') ?>" method="POST" enctype="multipart/form-data">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>File:</label>
                                        <label class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                            <span class="custom-file-label" id="file-upload-filename">Choose file</span>
                                        </label>
                                        <span class="form-text text-muted">Accepted formats: xls/xlsx. Max file size 10Mb</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary">Save <i class="icon-paperplane ml-2"></i></button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-0">
        <form class="wizard-form steps-async wizard clearfix" action="<?= base_url() ?>/save-assignment" method="post" data-fouc="" role="application" id="steps-uid-1">
            <?= csrf_field() ?>
            <div class="steps clearfix">
                <ul role="tablist">
                    <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="steps-uid-1-t-0" href="#steps-uid-1-h-0" aria-controls="steps-uid-1-p-0" class=""><span class="current-info audible">current step: </span><span class="number">1</span> Box Assignment</a></li>
                    <li role="tab" class="disabled" aria-disabled="true"><a id="steps-uid-1-t-1" href="#steps-uid-1-h-1" aria-controls="steps-uid-1-p-1" class="disabled"><span class="number">2</span> Assignment Process</a></li>
                    <li role="tab" class="disabled" aria-disabled="true"><a id="steps-uid-1-t-2" href="#steps-uid-1-h-2" aria-controls="steps-uid-1-p-2" class="disabled"><span class="number">3</span> Completed Assignment</a></li>
                </ul>
            </div>
            <div class="reset-button">
                <a href="<?= base_url('/reset-assignment') ?>"><span class="badge badge-danger"><i class="icon-reset mr-2"></i>RESET</span></a>
            </div>
            <table class="table datatable-basic" id="myTable" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 10%">Box Name</th>
                        <th class="text-center" style="width: 10%">Status</th>
                        <th class="text-center" style="width: 15%">Box Value</th>
                        <th class="text-center" style="width: 5%">Order</th>
                        <th class="text-center">Client</th>
                        <th class="text-center">AMZ Store</th>
                        <th class="text-center">Investment Date</th>
                        <th class="text-center">Current</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($getAllAssignReport->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>

                        <?php foreach ($getAllAssignReport->getResultArray() as $row) : ?>
                            <?php if (!empty($row['userid']) && $row['confirmed'] == 0) : ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                    </td>
                                    <td>
                                        <a href="#" class="h6 box_name name_box_<?= $no ?>" data-box="<?= $row['box_name'] ?>">
                                            <?= $row['box_name'] ?>
                                        </a>
                                    </td>
                                    <td><span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span></td>
                                    <td class="value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                                    <td>
                                        <input type="text" class="daterange-single order_box_<?= $no ?>" name="date[]" value="<?= date("m/d/Y") ?>" style="width: 90px; text-align:center">
                                    </td>
                                    <td>
                                        <select class="form-control clientSelect select-search" name="client[]" id="box_<?= $no ?> " data-fouc>
                                            <option value="0">...</option>
                                            <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                                <?php if ($client['id'] == $row['userid']) : ?>
                                                    <option value="<?= $client['id'] ?>" selected><b><?= $client['fullname'] ?></b></option>
                                                <?php else : ?>
                                                    <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?></b></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                        <b><?= $row['company'] ?> </b>
                                    </td>
                                    <td class="date_box_<?= $no ?>">
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>

                                        <select class="select_date_box_<?= $no ?>">
                                            <option value="<?= $row['investment_id'] ?>" selected><?= strtoupper($newDateInvest) ?></option>
                                        </select>
                                    </td>
                                    <td class="currentCost_box_<?= $no ?>">
                                        <b><?= "$ " . number_format($row['current_cost'], 2) ?></b>
                                    </td>
                                    <td class="total_box_<?= $no ?>">
                                        <b><?= "$ " . number_format($row['cost_left'], 2) ?></b>
                                    </td>
                                </tr>

                            <?php elseif (empty($row['userid'])) : ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                    </td>
                                    <td>
                                        <a href="#" class="h6 box_name name_box_<?= $no ?>" data-box="<?= $row['box_name'] ?>">
                                            <?= $row['box_name'] ?>
                                        </a>
                                    </td>
                                    <td><span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span></td>
                                    <td class="value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                                    <td>
                                        <input type="text" class="daterange-single order_box_<?= $no ?>" name="date[]" value="<?= date("m/d/Y") ?>" style="width: 90px; text-align:center">
                                    </td>
                                    <td>
                                        <select class="form-control clientSelect select-search" name="client[]" id="box_<?= $no ?> " data-fouc>
                                            <option value="0">...</option>
                                            <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                                <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?></b></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                    </td>
                                    <td class="date_box_<?= $no ?>">
                                        <select class="select_date_box_<?= $no ?>">
                                        </select>
                                    </td>
                                    <td class="currentCost_box_<?= $no ?>">
                                    </td>
                                    <td class="total_box_<?= $no ?>"></td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>

            </table>
            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 1</b></button>
                </div>
                <div class="text-left">
                    <a href="#" class="btn btn-light disabled"><i class="icon-arrow-left8 mr-2"></i>Previous</a>
                    <a href="<?= base_url('/admin/assignment-process') ?>" class="btn btn-primary">Next Phase<i class="icon-arrow-right8 ml-2"></i></a>
                </div>

            </div>
        </form>
        <div class="card-body">
            The pending box
        </div>
        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%">No</th>
                    <th class="text-center" style="width: 10%">Box Name</th>
                    <th class="text-center" style="width: 10%">Status</th>
                    <th class="text-center" style="width: 15%">Box Value</th>
                    <th class="text-center" style="width: 5%">Order</th>
                    <th class="text-center">Client</th>
                    <th class="text-center">AMZ Store</th>
                    <th class="text-center">Investment Date</th>
                    <th class="text-center">Current</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllAssignReportPending->getNumRows() > 0) : ?>
                    <?php $no = 1 ?>
                    <?php foreach ($getAllAssignReportPending->getResultArray() as $row) : ?>
                        <tr class="table-active">
                            <td>
                                <?= $no++ ?>
                                <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                            </td>
                            <td class="name_box_<?= $no ?>">
                                <a href="#" class="h6 box_name" data-box="<?= $row['box_name'] ?>">
                                    <b><?= $row['box_name'] ?></b>
                                </a>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'waiting') : ?>
                                    <span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php elseif ($row['status'] == 'rejected') : ?>
                                    <span class="badge badge-danger"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php else : ?>
                                    <span class="badge badge-success"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php endif ?>
                            </td>
                            <td class="value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                            <td>
                                <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                <input type="text" class="order_box_<?= $no ?>" name="date[]" value="<?= $newDate ?>" style="width: 90px; text-align:center" readonly>
                            </td>
                            <td>
                                <b><?= $row['fullname'] ?></b>
                            </td>
                            <td class="company_box_<?= $no ?>">
                                <b><?= $row['company'] ?> </b>
                            </td>
                            <td class="date_box_<?= $no ?>">
                                <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                <b><?= strtoupper($newDateInvest) ?></b>
                            </td>
                            <td class="currentCost_box_<?= $no ?>">
                                <b><?= "$ " . number_format($row['current_cost'], 2) ?></b>
                            </td>
                            <td class="total_box_<?= $no ?>">
                                <b><?= "$ " . number_format($row['cost_left'], 2) ?></b>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>

        </table>
        <div class="card-body">
            The boxes that have been checked by VA
        </div>
        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%">No</th>
                    <th class="text-center" style="width: 10%">Box Name</th>
                    <th class="text-center" style="width: 10%">Status</th>
                    <th class="text-center" style="width: 15%">Box Value</th>
                    <th class="text-center">FBA Number</th>
                    <th class="text-center">Shipment Number</th>
                    <th class="text-center" style="width: 5%">Order</th>
                    <th class="text-center">Client</th>
                    <th class="text-center">AMZ Store</th>
                    <th class="text-center">Investment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllAssignReportCompleted->getNumRows() > 0) : ?>
                    <?php $no = 1 ?>
                    <?php foreach ($getAllAssignReportCompleted->getResultArray() as $row) : ?>
                        <?php if ($row['status'] == 'waiting') : ?>
                            <tr class="table-active">
                            <?php elseif ($row['status'] == 'rejected') : ?>
                            <tr class="table-warning">
                            <?php else : ?>
                            <tr class="table-success">
                            <?php endif ?>
                            <td>
                                <?= $no++ ?>
                                <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                            </td>
                            <td>
                                <a href="#" class="h6 box_name" data-box="<?= $row['box_name'] ?>">
                                    <b><?= $row['box_name'] ?></b>
                                </a>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'waiting') : ?>
                                    <span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php elseif ($row['status'] == 'rejected') : ?>
                                    <span class="badge badge-danger"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php else : ?>
                                    <span class="badge badge-success"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php endif ?>
                            </td>
                            <td class="value_box_<?= $no ?>">
                                <?php if ($row['box_value'] == $row['new_box_value']) : ?>
                                    <b>$ <?= number_format($row['box_value'], 2) ?></b>
                                <?php else : ?>
                                    <del>$ <?= $row['box_value'] ?></del> <b><mark>$ <?= number_format($row['new_box_value'], 2) ?></mark></b>
                                <?php endif ?>
                            </td>
                            <td>
                                <b><?= $row['fba_number'] ?></b>
                            </td>
                            <td>
                                <b><?= $row['shipment_number'] ?> </b>
                            </td>
                            <td>
                                <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                <input disabled type="text" class="daterange-single order_box_<?= $no ?>" name="date[]" value="<?= $newDate ?>" style="width: 90px; text-align:center">
                            </td>
                            <td>
                                <b><?= $row['fullname'] ?></b>
                            </td>
                            <td>
                                <b><?= $row['company'] ?> </b>
                            </td>
                            <td>
                                <select>
                                    <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                    <option value="investment_id" selected><b><?= strtoupper($newDateInvest) ?></b></option>
                                </select>
                            </td>

                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
            </tbody>

        </table>

    </div>

    <div class="modal fade modal_scrollable_box" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title">#title</span></b></h5>
                </div>
                <div class="modal-body py-0">
                    <form id="box-details">
                        <div class="table-responsive" id="item-table">
                            <!-- <form action="<?= base_url('/save-box-details') ?>" method="post"> -->

                            <?php csrf_field() ?>
                            <input type="hidden" name="box_name" id="box_name" value="">
                            <table class="table" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th>SKU</th>
                                        <th>Item Description</th>
                                        <th>Condition</th>
                                        <th>Qty</th>
                                        <th>Retail</th>
                                        <th>Original</th>
                                        <th>Cost</th>
                                        <th>Vendor</th>
                                        <th>Note</th>

                                    </tr>
                                </thead>
                                <tbody id="item-tbody">

                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive mt-2" id="item-table-removed" style="display: none;">
                            <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                <span class="font-weight-semibold">Some items have been removed in a box!</span>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>
                            <table class="table" style="font-weight:bold; font-size:12px" id="table-removed">
                                <thead>
                                    <tr class="bg-danger text-white">
                                        <th>SKU</th>
                                        <th>Item Description</th>
                                        <th>Condition</th>
                                        <th>Qty</th>
                                        <th>Retail</th>
                                        <th>Original</th>
                                        <th>Cost</th>
                                        <th>Vendor</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody-removed">

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group">
                            <label for=""><b>Note:</b></label>
                            <div class="input-group">
                                <textarea name="box_note" disabled class="form-control" id="box_note" rows="3" placeholder="-"></textarea>
                            </div>

                        </div>

                </div>

                <div class="modal-footer pt-3">
                    <a class="btn btn-light" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
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
<script src="/assets/js/demo_pages/form_select2.js"></script>
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            swal("Great!", "<?= session()->getFlashdata('success') ?>", "success");
        <?php endif ?>

        var input = document.getElementById('file-upload');
        var infoArea = document.getElementById('file-upload-filename');

        input.addEventListener('change', showFileName);

        function showFileName(event) {
            // the change event gives us the input it occurred in 
            var input = event.srcElement;
            // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
            var fileName = input.files[0].name;
            // use fileName however fits your app best, i.e. add it into a div
            infoArea.textContent = '' + fileName;
        }
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
        $(".clientSelect").select2({
            width: '150px'
        });

        $.fn.inputFilter = function(inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        };
        $(".floatTextBox").inputFilter(function(value) {
            return /^-?\d*[.]?\d*$/.test(value);
        });

    });
    var i = 1;
    var tempTotal = 0;
    var total = 0;


    $('.clientSelect').on('change', function() {
        var boxId = $(this).attr('id');
        var boxNameId = "name_" + $(this).attr('id');
        var boxName = $('.' + boxNameId).html().trim();
        var valueBoxId = "value_" + $(this).attr('id');
        var valueBox = $('.' + valueBoxId).html();
        var orderDateId = "order_" + $(this).attr('id');
        var orderDate = $('.' + orderDateId).val();
        var valueBox = valueBox.substring(2);
        var clientId = this.value;
        $('.select_date_' + boxId).html("");
        $.get('/get-company/' + clientId, function(data) {
            var company = JSON.parse(data);
            if (company != null) {
                $('.company_' + boxId).html("<b>" + company['company'] + "</b>");
            } else {
                $('.company_' + boxId).html("");
            }
        });
        $.post('/get-investment-client', {
            id: clientId
        }, function(data) {
            var investdate = JSON.parse(data);
            if (investdate.length > 0) {
                for (var i = 0; i < investdate.length; i++) {
                    $('.select_date_' + boxId).append(investdate[i]);
                }

                var selected = $('.select_date_' + boxId).find('option:selected');
                var currentCost = selected.data('foo');



                $('.currentCost_' + boxId).html("<b>$ " + numberWithCommas(currentCost.toFixed(2)) + "</b>");
                var investmentId = $('.select_date_' + boxId + ' option:selected').val();
                $.post('/assign-box', {
                    box_id: boxId,
                    box_name: boxName,
                    order_date: orderDate,
                    client_id: clientId,
                    value_box: valueBox,
                    current_cost: currentCost,
                    investment_id: investmentId
                }, function(data) {
                    var resp = JSON.parse(data);
                    console.log(resp['status']);
                    if (resp['status'] == 0) {
                        swal("Oops...", "Total exceed $250.00!", "warning");
                        $('.total_' + boxId).html("");
                    } else {
                        $('.total_' + boxId).html("<b>$ " + numberWithCommas(resp['cost_left'].toFixed(2)) + "</b>");
                    }
                });
            } else {
                $.post('/assign-box', {
                    box_id: boxId,
                    box_name: boxName,
                    order_date: orderDate,
                    client_id: clientId,
                    value_box: valueBox,
                    current_cost: 0,
                    investment_id: 0
                }, function(data) {

                });
                $('.select_date_' + boxId).html("");
                $('.currentCost_' + boxId).html("");
                $('.total_' + boxId).html("");
            }

        });
        $('.select_date_' + boxId).on('change', function() {
            var selected = $(this).find('option:selected');
            var currentCost = selected.data('foo');
            $('.currentCost_' + boxId).html("<b>$ " + numberWithCommas(currentCost.toFixed(2)) + "</b>");
            var investmentId = $('.select_date_' + boxId + ' option:selected').val();
            $.post('/assign-box', {
                box_id: boxId,
                box_name: boxName,
                order_date: orderDate,
                client_id: clientId,
                value_box: valueBox,
                current_cost: currentCost,
                investment_id: investmentId
            }, function(data) {
                var resp = JSON.parse(data);
                if (resp['status'] == 0) {
                    swal("Oops...", "Total exceed $250.00!", "warning");
                    $('.total_' + boxId).html("");
                } else {
                    $('.total_' + boxId).html("<b>$ " + numberWithCommas(resp['cost_left'].toFixed(2)) + "</b>");
                }
            });

        });


    });


    $('.box_name').on('click', function() {
        var boxName = $(this).data('box');
        console.log(boxName);
        $('#item-table tbody').html("");
        $.get('/get-box-summary', {
            box_name: boxName
        }, function(data) {
            var item = JSON.parse(data);
            $('.modal-title').html("<b>" + item[0]['description'] + "</b>");
            $('#box_name').val(boxName);
            $('#item-table-removed').css("display", "none");
            $('.modal_scrollable_box').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#item-tbody-removed').html("");

            if (item.length > 0) {
                $('#box_note').html(item[0]['box_note']);
                var no = 1;
                for (var i = 0; i < item.length; i++) {
                    if (item[i]['item_status'] == 1) {
                        if (i % 2 == 0) {
                            $('#item-table tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(item[i]['retail']) + '</td> <td>$ ' + numberWithCommas(item[i]['original']) + '</td><td>$ ' + numberWithCommas(item[i]['cost']) + '</td> <td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        } else {
                            $('#item-table tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(item[i]['retail']) + '</td> <td>$ ' + numberWithCommas(item[i]['original']) + '</td> <td>$ ' + numberWithCommas(item[i]['cost']) + '</td><td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        }
                    } else {
                        $('#item-table-removed').css("display", "block");
                        if (i % 2 == 0) {
                            $('#item-table-removed tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(item[i]['retail']) + '</td> <td>$ ' + numberWithCommas(item[i]['original']) + '</td><td>$ ' + numberWithCommas(item[i]['cost']) + '</td> <td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        } else {
                            $('#item-table-removed tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(item[i]['retail']) + '</td> <td>$ ' + numberWithCommas(item[i]['original']) + '</td><td>$ ' + numberWithCommas(item[i]['cost']) + '</td> <td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        }
                    }
                }
            }

            $('.modal_scrollable_box').modal('show');
        });

    });

    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the report.',
            type: 'success'
        }).show();


    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the report.',
            type: 'alert'
        }).show();
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

<?= $this->endSection() ?>