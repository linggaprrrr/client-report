<?= $this->extend('va/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin: 0 20px 20px;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">

        </div>
        <form class="wizard-form steps-async wizard clearfix" action="<?= base_url('save-assignment-process') ?>" method="post" data-fouc="" role="application" id="steps-uid-1">
            <?= csrf_field() ?>
            <div class="steps clearfix">
                <ul role="tablist">
                    <li role="tab" class="disabled" aria-disabled="false"><a class=""><span class="current-info audible">current step: </span><span class="number">1</span> Box Assignment</a></li>
                    <li role="tab" class="first current" aria-disabled="true" aria-selected="true"><a class="disabled"><span class="number">2</span> Assignment Process</a></li>
                    <li role="tab" class="disabled" aria-disabled="false"><a class="disabled"><span class="number">3</span> Completed Assignment</a></li>
                </ul>
            </div>
            <div class="reset-button">
                <a href="<?= base_url('/reset-second-phase') ?>"><span class="badge badge-danger"><i class="icon-reset mr-2"></i>RESET</span></a>
            </div>
            <table class="table datatable-basic" id="myTable" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width: 10%">Box Name</th>
                        <th class="text-center" style="width: 10%">Order Date</th>
                        <th class="text-center" style="width: 10%">Client</th>
                        <th class="text-center" style="width: 10%">AMZ Store</th>
                        <th class="text-center" style="width: 10%">Investment Date</th>
                        <th class="text-center" style="width: 20%">FBA Number</th>
                        <th class="text-center" style="width: 20%">Shipment Number</th>
                        <th class="text-center" style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($getAllAssignReportProcess->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($getAllAssignReportProcess->getResultArray() as $row) : ?>
                            <?php if (!empty($row['userid'])) : ?>
                                <?php if ($row['status'] == 'reassign') : ?>
                                    <tr class="table-active">
                                    <?php elseif ($row['status'] == 'rejected') : ?>
                                    <tr class="table-warning">
                                    <?php else : ?>
                                    <tr class="table-success">
                                    <?php endif ?>
                                    <td><?= $no++ ?></td>
                                    <td class="text-center">
                                        <a href="#" class="h6 box_name" data-box="<?= $row['box_name'] ?>">
                                            <b><?= $row['box_name'] ?></b>
                                        </a>
                                        <br>
                                        <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                            <?php $desc = substr($row['description'], $pos + 1);     ?>
                                            <?= $desc  ?>
                                        <?php else : ?>
                                            None
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                        <b><?= $newDate ?></b>
                                    </td>
                                    <td>
                                        <input type="hidden" name="client[]" value="<?= $row['userid'] ?>">
                                        <b><?= $row['fullname'] ?></b>
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                        <b><?= $row['company'] ?> </b>
                                    </td>
                                    <td class="investment_box_<?= $no ?>">
                                        <input type="hidden" name="investment_id[]" value="<?= $row['investment_id'] ?>">
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                        <b><?= strtoupper($newDateInvest) ?></b>
                                    </td>
                                    <td class="fba_number_box_<?= $no ?>">
                                        <input class="form-control fba_input" name="fba_number[]" placeholder="FBA Number" data-box="<?= $row['box_name'] ?>" value="<?= $row['fba_number'] ?>" readonly>
                                    </td>
                                    <td class="shipment_box_<?= $no ?>">
                                        <input class="form-control shipment_input" name="shipment_number[]" placeholder="Shipment Number" data-box="<?= $row['box_name'] ?>" value="<?= $row['shipment_number'] ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                        <select class="form-control" name="status[]" style="width: 130px;">
                                            <?php if ($row['status'] == 'waiting') : ?>
                                                <option value="0" selected>...</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="remanifested">RE-MANIFEST</option>
                                                <option value="reassigned">RE-ASSIGN</option>
                                            <?php elseif ($row['status'] == 'approved') : ?>
                                                <option value="0">...</option>
                                                <option value="approved" selected>APPROVED</option>
                                                <option value="remanifested">RE-MANIFEST</option>
                                                <option value="reassigned">RE-ASSIGN</option>
                                            <?php elseif ($row['status'] == 'remanifest') : ?>
                                                <option value="0">...</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="remanifested" selected>RE-MANIFEST</option>
                                                <option value="reassigned">RE-ASSIGN</option>
                                            <?php else : ?>
                                                <option value="0">...</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="remanifested">RE-MANIFEST</option>
                                                <option value="reassigned" selected>RE-ASSIGN</option>
                                            <?php endif ?>
                                        </select>
                                    </td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </tbody>

            </table>
            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 2</b></button>
                </div>
                <div class="text-left">
                    <a href="<?= base_url('/va/assignment-report') ?>" class="btn btn-light"><i class="icon-arrow-left8 mr-2"></i>Previous</a>
                    <a href="<?= base_url('/va/assignment-completed') ?>" class="btn btn-primary">Next Phase<i class="icon-arrow-right8 ml-2"></i></a>
                </div>

            </div>

        </form>

        <div class="modal fade modal_scrollable_box" tabindex="-1">
            <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header pb-3">
                        <h5><b>BOX #<span class="modal-title">#title</span></b></h5>
                    </div>
                    <div class="modal-body py-0">
                        <form id="box-details">
                            <div class="table-responseive">
                                <table class="table text-center" id="sum" style="font-weight:bold; font-size:12px">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th style="width: 10%;">SKU</th>
                                            <th style="width: 20%;">Item Description</th>
                                            <th style="width: 5%;">Condition</th>
                                            <th style="width: 10%;">Total Qty</th>
                                            <th style="width: 10%;">Retail</th>
                                            <th style="width: 10%;">Total Retail</th>
                                            <th style="width: 10%;">Total Cost</th>
                                            <th style="width: 15%;">Vendor</th>
                                            <th style="width: 15%;">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
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
                                            <th>Total</th>
                                            <th>Cost</th>
                                            <th>Vendor</th>
                                            <th>Note</th>
                                            <th style="width: 5%;"><i class="icon-arrow-down12"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="item-tbody">

                                    </tbody>
                                </table>

                            </div>
                            <div class="table-responsive mt-2" id="item-table-removed" style="display: none;">
                                <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                    <span class="font-weight-semibold">Some items will be removed in a box!</span>
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
                                            <th>Vendor</th>
                                            <th>Note</th>
                                            <th style="width: 5%;"><i class="icon-arrow-down12"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="item-tbody-removed">

                                    </tbody>
                                </table>

                            </div>

                            <div class="form-group">
                                <label for=""><b>Note:</b></label>
                                <div class="input-group">
                                    <textarea name="box_note" class="form-control" id="box_note" rows="3" placeholder="-"></textarea>
                                </div>

                            </div>

                    </div>

                    <div class="modal-footer pt-3">
                        <a class="btn btn-light" data-dismiss="modal">Close</a>
                        <input id="submit" type="submit" class="btn btn-secondary" value="Save changes">
                    </div>
                    </form>
                </div>
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
        <?php if (session()->getFlashdata('required')) : ?>
            swal("Warning!", "<?= session()->getFlashdata('required') ?>", "warning");
        <?php endif ?>



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


        $('.box_name').on('click', function() {
            var boxName = $(this).attr('data-box');
            $('#item-table tbody').html("");
            $('#sum tbody').html("");
            $.get('/get-box-summary', {
                box_name: boxName
            }, function(data) {
                $('.modal-title').html("<b>" + boxName + "</b>");
                $('#box_name').val(boxName);
                $('#item-table-removed').css("display", "none");
                $('.modal_scrollable_box').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                $('#item-tbody-removed').html("");
                var item = JSON.parse(data);
                var no = 1;
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                if (item.length > 0) {
                    $('#box_note').html(item[0]['box_note']);
                    var no = 1;
                    for (var i = 0; i < item.length; i++) {
                        if (item[i]['item_status'] == '1') {
                            if (item[i]['item_check'] == '1') {
                                $('#item-table tbody').append('<tr class="table-secondary"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td><input type="text" name="item_description[]" value="' + item[i]['item_description'] + '" ></td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td><input type="text" name="vendor[]" value="' + item[i]['vendor'] + '"></td> <td><input type="text" name="note[]" class="form-control" placeholder="Add Note" value="' + $.trim(item[i]['item_note']) + '"></td> <td><a href="#" class="remove" id="' + item[i]['item_status'] + '"><i class="icon-minus-circle2"></i><input type="hidden" class="item_status" name="item_status[]" value="' + item[i]['item_status'] + '"> </a> <a style="color:green" href="#" class="check" id="' + item[i]['item_check'] + '"><i class="icon-checkmark-circle"></i><input type="hidden" class="item_check" name="item_check[]" value="' + item[i]['item_check'] + '"> </a></td> </tr>');
                            } else {
                                $('#item-table tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td><input type="text" name="item_description[]" value="' + item[i]['item_description'] + '" ></td>  <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td><input type="text" name="vendor[]" value="' + item[i]['vendor'] + '"></td> <td><input type="text" name="note[]" class="form-control" placeholder="Add Note" value="' + $.trim(item[i]['item_note']) + '"></td> <td><a href="#" class="remove" id="' + item[i]['item_status'] + '"><i class="icon-minus-circle2"></i><input type="hidden" class="item_status" name="item_status[]" value="' + item[i]['item_status'] + '"> </a> <a style="color:green" href="#" class="check" id="' + item[i]['item_check'] + '"><i class="icon-checkmark-circle"></i><input type="hidden" class="item_check" name="item_check[]" value="' + item[i]['item_check'] + '"> </a></td> </tr>');
                            }
                        } else {
                            $('#item-table-removed').css("display", "block");
                            if (i % 2 == 0) {
                                $('#item-table-removed tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td><td><input type="text" name="item_description[]" value="' + item[i]['item_description'] + '" ></td>  <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td><input type="text" name="vendor[]" value="' + item[i]['vendor'] + '"></td> <td><input type="text" name="note[]" class="form-control" placeholder="Add Note" value="' + $.trim(item[i]['item_note']) + '"></td><td><a href="#" class="remove" id="' + item[i]['item_status'] + '"><i class="icon-minus-circle2"></i><input type="hidden" class="item_status" name="item_status[]" value="' + item[i]['item_status'] + '"> </a> <a style="color:green" href="#" class="check" id="' + item[i]['item_check'] + '"><i class="icon-checkmark-circle"></i><input type="hidden" class="item_check" name="item_check[]" value="' + item[i]['item_check'] + '"> </a></td> </tr>');
                            } else {
                                $('#item-table-removed tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td><input type="text" name="item_description[]" value="' + item[i]['item_description'] + '" ></td>  <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td><input type="text" name="vendor[]" value="' + item[i]['vendor'] + '"></td> <td><input type="text" name="note[]" class="form-control" placeholder="Add Note" value="' + $.trim(item[i]['item_note']) + '"></td><td><a href="#" class="remove" id="' + item[i]['item_status'] + '"><i class="icon-minus-circle2"></i><input type="hidden" class="item_status" name="item_status[]" value="' + item[i]['item_status'] + '"> </a> <a style="color:green" href="#" class="check" id="' + item[i]['item_check'] + '"><i class="icon-checkmark-circle"></i><input type="hidden" class="item_check" name="item_check[]" value="' + item[i]['item_check'] + '"> </a></td> </tr>');
                            }
                        }
                        qty = qty + parseInt(item[i]['qty']);
                        retail = retail + parseFloat(item[i]['retail']);
                        total = total + parseFloat(item[i]['original']);
                        cost = cost + parseFloat(item[i]['cost']);
                    }
                    $('#sum tbody').append('<tr><td>-</td> <td>-</td>  <td>-</td><td>' + qty + '</td> <td>$ ' + numberWithCommas(retail.toFixed(2)) + '</td> <td>$ ' + numberWithCommas(total.toFixed(2)) + '</td><td>$ ' + numberWithCommas(cost.toFixed(2)) + '</td> <td>-</td> <td>-</td></tr>');
                }

                $('.modal_scrollable_box').modal('show');
            });

        });


        $("form#box-details").on("submit", function(e) {
            e.preventDefault();
            $.post('<?= base_url('/save-box-details') ?>', $(this).serialize(), function(data) {
                new Noty({
                    text: 'The box has been saved.',
                    type: 'alert'
                }).show();
                $('.modal_scrollable_box').modal('hide');
            });
        });

    });

    $('#item-table').on('click', '.remove', function() {
        $(this).find(".item_status").val("0")
        var removeRow = $(this).parents('tr').clone(true);
        $(this).parents('tr').remove();
        $('#item-tbody-removed').append(removeRow);
        $('#item-table-removed').css("display", "block");
    });

    $('#item-table-removed').on('click', '.remove', function() {
        $(this).find(".item_status").val("1")
        var removeRow = $(this).parents('tr').clone(true);
        $(this).parents('tr').remove();
        $('#item-tbody').append(removeRow);
        var rowCount = $('#table-removed tr').length;
        if (rowCount == 1) {
            $('#item-table-removed').css("display", "none");
        }
    });


    $('#item-table').on('click', '.check', function() {
        $(this).find(".item_check").val("1")
        $(this).parents('tr').removeClass();
        $(this).parents('tr').addClass("table-secondary");
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

    $('.fba_input').on('click', function() {
        var current = $(this).val();
        var fbaNumber = "";
        var boxName = $(this).data('box');
        swal("Input FBA Number:", {
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Type your password",
                        type: "text",
                        value: current
                    },
                },
            })
            .then((value) => {
                if (value) {
                    fbaNumber = value;
                    swal(`FBA Number: ${value}`);
                    $(this).val(value);
                } else {
                    fbaNumber = current;
                    swal(`FBA Number: ${current}`);
                    $(this).val(current);
                }

                $.post('/save-fba-number', {
                    fba_number: fbaNumber,
                    box_name: boxName
                }, function(data) {

                });
            });
    });

    $('.shipment_input').on('click', function() {
        var current = $(this).val();
        var shipmentNumber = "";
        var boxName = $(this).data('box');
        swal("Input Shipment Number:", {
                content: {
                    element: "input",
                    attributes: {
                        placeholder: "Type your password",
                        type: "text",
                        value: current
                    },
                },
            })
            .then((value) => {
                if (value) {
                    shipmentNumber = value;
                    swal(`Shipment Number: ${value}`);
                    $(this).val(value);
                } else {
                    shipmentNumber = current;
                    swal(`Shipment Number: ${current}`);
                    $(this).val(current);
                }
                $.post('/save-shipment-number', {
                    shipment_number: shipmentNumber,
                    box_name: boxName
                }, function(data) {

                });
            });
    });
</script>

<?= $this->endSection() ?>