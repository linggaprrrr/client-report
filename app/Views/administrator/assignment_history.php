<?= $this->extend('administrator/layout/template') ?>

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

            <table class="table datatable-basic" id="myTable" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width: 5%">Box Name</th>
                        <th class="text-center" style="width: 15%">Box Value</th>
                        <th class="text-center" style="width: 10%">Order Date</th>
                        <th class="text-center" style="width: 10%">Client</th>
                        <th class="text-center" style="width: 10%">AMZ Store</th>
                        <th class="text-center" style="width: 10%">Investment Date</th>
                        <th class="text-center" style="width: 10%">Cost Left</th>
                        <th class="text-center" style="width: 10%">VA</th>
                        <th class="text-center" style="width: 20%">FBA Number</th>
                        <th class="text-center" style="width: 20%">Shipment Number</th>
                        <th class="text-center" style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($assignCompleted->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($assignCompleted->getResultArray() as $row) : ?>
                            <?php if (!empty($row['userid'])) : ?>
                                <?php if ($row['status'] == 'waiting') : ?>
                                    <tr class="table-active">
                                    <?php elseif ($row['status'] == 'rejected') : ?>
                                    <tr class="table-warning">
                                    <?php else : ?>
                                    <tr class="table-success">
                                    <?php endif ?>
                                    <td><?= $no++ ?></td>
                                    <td class="text-center ">
                                        <a href="#" class="h6 box_name" data-box="<?= $row['box_name'] ?>">
                                            <b><?= $row['box_name'] ?></b>
                                        </a>
                                    </td>
                                    <td class="text-center value_box_<?= $no ?>">
                                        <?php if ($row['box_value'] == $row['new_box_value']) : ?>
                                            <b>$ <?= $row['box_value'] ?></b>
                                        <?php else : ?>
                                            <del>$ <?= $row['box_value'] ?></del> <b><mark>$ <?= $row['new_box_value'] ?></mark></b>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                        <b><?= $newDate ?></b>
                                    </td>
                                    <td>
                                        <b><?= $row['fullname'] ?></b>
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                        <b><?= $row['company'] ?> </b>
                                    </td>
                                    <td class="investment_box_<?= $no ?>">
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                        <b><?= strtoupper($newDateInvest) ?></b>
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                        <b>$ <?= number_format($row['cost_left'], 2) ?> </b>
                                    </td>
                                    <td class="text-center va_box<?= $no ?>">
                                        <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                            <?php if ($va['id'] == $row['va_id']) : ?>
                                                <b><?= $va['fullname'] ?></b>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    <td class="text-center fba_number_box_<?= $no ?>">
                                        <b><?= $row['fba_number'] ?></b>
                                    </td>
                                    <td class="text-center  shipment_box_<?= $no ?>">
                                        <b><?= $row['shipment_number'] ?></b>
                                    </td>
                                    <td class="text-center ">
                                        <b><?= strtoupper($row['status']) ?></b>
                                    </td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                </tbody>

            </table>
            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <!-- <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 2</b></button> -->
                </div>


            </div>

        </form>

        <div class="modal fade modal_scrollable_box" tabindex="-1">
            <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header pb-3">
                        <h5><b><span class="modal-title">#title</span></b></h5>
                        <div class="float-right">
                            <a href="" data-toggle="modal" class="copy-clipboard" data-clipboard-target="#resultsTable" title="Copy To Clipboard"><i class="icon-clipboard6"></i></a>
                        </div>
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
                                    <tbody class="item-tbody">

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
                                <textarea id="resultsTable" style="display:none;">
                                </textarea>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js" integrity="sha512-iJh0F10blr9SC3d0Ow1ZKHi9kt12NYa+ISlmCdlCdNZzFwjH1JppRTeAnypvUez01HroZhAmP4ro4AvZ/rG0UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('#copy-clipboard').tooltip();

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
                var item = JSON.parse(data);
                if (item.length > 0) {
                    $('#box_note').html(item[0]['box_note']);
                    var no = 1;
                    for (var i = 0; i < item.length; i++) {
                        if (item[i]['item_status'] == 1) {
                            if (i % 2 == 0) {
                                $('#item-table tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(parseFloat(parseFloat(item[i]['retail']))) + '</td> <td>$ ' + numberWithCommas(parseFloat(item[i]['original'])) + '</td><td>$ ' + numberWithCommas(parseFloat(item[i]['cost'])) + '</td> <td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                            } else {
                                $('#item-table tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td>' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td>$ ' + numberWithCommas(parseFloat(item[i]['retail'])) + '</td> <td>$ ' + numberWithCommas(parseFloat(item[i]['original'])) + '</td> <td>$ ' + numberWithCommas(parseFloat(item[i]['cost'])) + '</td><td>' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control" disabled value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
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


        $("form#box-details").on("submit", function(e) {
            e.preventDefault();
            $.post('<?= base_url('/save-box-details') ?>', $(this).serialize(), function(data) {

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
        $('item-tbody').append(removeRow);
        var rowCount = $('#table-removed tr').length;
        if (rowCount == 1) {
            $('#item-table-removed').css("display", "none");
        }
    });
    
    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the report.',
            type: 'alert'
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

    var clipboard = new Clipboard('.copy-clipboard');
    clipboard.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    

</script>

<?= $this->endSection() ?>

