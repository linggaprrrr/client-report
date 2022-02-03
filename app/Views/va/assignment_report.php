<?= $this->extend('va/layout/template') ?>

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
                <!-- <a href="<?= base_url('/reset-assignment') ?>"><span class="badge badge-danger"><i class="icon-reset mr-2"></i>RESET</span></a> -->
            </div>
            <table class="table datatable-basic" id="myTable" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 5%">Box Name</th>
                        <th class="text-center" style="width: 5%">Status</th>
                        <th class="text-center" style="width: 10%">Box Value</th>
                        <th class="text-center" style="width: 12%">VA User</th>
                        <th class="text-center" style="width: 5%">Order</th>
                        <th class="text-center" style="width: 12%">Client</th>
                        <th class="text-center" style="width: 10%">Investment Date</th>
                        <th class="text-center" style="width: 10%">Current</th>
                        <th class="text-center" style="width: 10%">Total</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($getAllAssignReport->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($getAllAssignReport->getResultArray() as $row) : ?>
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

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                </td>
                                <td class="date_box_<?= $no ?>">
                                    <select class="select_date_box_<?= $no ?>">
                                    </select>
                                </td>
                                <td class="currentCost_box_<?= $no ?>">
                                    <a href="#" class="popover_box_<?= $no ?>" data-popup="popover-custom" title="Category Percentage" data-trigger="focus" data-content="Loading..."> <i class="icon-info22"></i></a>
                                </td>
                                <td class="total_box_<?= $no ?>"></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>

            </table>
            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <!-- <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 1</b></button> -->
                </div>
                <div class="text-left">
                    <a href="#" class="btn btn-light disabled"><i class="icon-arrow-left8 mr-2"></i>Previous</a>
                    <a href="<?= base_url('/va/assignment-process') ?>" class="btn btn-primary">Next Phase<i class="icon-arrow-right8 ml-2"></i></a>
                </div>

            </div>
        </form>


    </div>


    <div class="modal fade modal_scrollable_box2" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title">#title</span></b> </h5>
                </div>
                <div class="modal-body py-0">
                    <form id="box-details">
                        <?php csrf_field() ?>
                        <div class="table-responseive">
                            <table class="table text-center" id="sum2" style="font-weight:bold; font-size:12px">
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
                                    <tr>
                                        <td><input type="text" value="1" readonly contenteditable="true"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="item-table2">
                            <!-- <form action="<?= base_url('/save-box-details') ?>" method="post"> -->

                            <?php csrf_field() ?>
                            <input type="hidden" name="box_name" id="box_name2" value="">
                            <table class="table text-center" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody2">

                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive mt-2" id="item-table-removed2" style="display: none;">
                            <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                <span class="font-weight-semibold">Some items have been removed in a box!</span>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>

                            <table class="table text-center" style="font-weight:bold; font-size:12px" id="table-removed">
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
                                <tbody id="item-tbody-removed2">

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
<script src="/assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/assets/js/demo_pages/components_popups.js"></script>
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



    $('.box_name2').on('click', function() {
        var boxName = $(this).data('box');
        $('#item-table2 tbody').html("");
        $('#sum2 tbody').html("");
        $.get('/get-box-summary', {
            box_name: boxName
        }, function(data) {
            var item = JSON.parse(data);
            $('.modal-title').html("<b>" + item[0]['description'] + "</b>");
            $('#box_name2').val(boxName);
            $('#item-table-removed2').css("display", "none");
            $('.modal_scrollable_box2').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#item-tbody-removed2').html("");

            if (item.length > 0) {
                $('#box_note').html(item[0]['box_note']);
                var no = 1;
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                for (var i = 0; i < item.length; i++) {
                    if (item[i]['item_status'] == 1) {

                        if (i % 2 == 0) {
                            $('#item-table2 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        } else {
                            $('#item-table2 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        }
                    } else {
                        $('#item-table-removed2').css("display", "block");
                        if (i % 2 == 0) {
                            $('#item-table-removed2 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td>  <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        } else {
                            $('#item-table-removed2 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td>  <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        }
                    }
                    qty = qty + parseInt(item[i]['qty']);
                    retail = retail + parseFloat(item[i]['retail']);
                    total = total + parseFloat(item[i]['original']);
                    cost = cost + parseFloat(item[i]['cost']);
                }
                $('#sum2 tbody').append('<tr><td>-</td> <td>-</td>  <td>-</td><td>' + qty + '</td> <td>$ ' + numberWithCommas(retail.toFixed(2)) + '</td> <td>$ ' + numberWithCommas(total.toFixed(2)) + '</td><td>$ ' + numberWithCommas(cost.toFixed(2)) + '</td> <td>-</td> <td>-</td></tr>');
            }

            $('.modal_scrollable_box2').modal('show');
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