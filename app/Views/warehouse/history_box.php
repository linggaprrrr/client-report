<?= $this->extend('warehouse/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin-right: 20px;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-header pb-0">
            <div class="form-group d-lg-flex  justify-content-lg-between" style="margin: 0;">
                <div>
                    <label>
                        <form action="<?= base_url('/warehouse/history-box') ?>" class="filter" method="get">
                            <span>Daterange:</span>                              
                            <?php if (!empty($date1)) : ?>                                
                                <input type="text" class="form-control" name="datefilter" value="<?= date('m/d/Y', strtotime($date1)) ?> - <?= date('m/d/Y', strtotime($date2)) ?>" style="    width: 220px; text-align: center;" readonly />
                            <?php else : ?>
                                <input type="text" class="form-control" name="datefilter" value="<?= date('m/d/Y') ?> - <?= date('m/d/Y') ?>" style="    width: 220px; text-align: center;" readonly />
                            <?php endif ?>
                            <input type="hidden" name="start">
                            <input type="hidden" name="end">
                        </form>
                    </label>
                </div>
                <div>

                    <button class="btn btn-success export-button"><i class="icon-file-excel mr-2"></i>Export to Excel</button>
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <hr>
            <div class="d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cube"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-box"><?= (is_null($totalBox) ? '0' : $totalBox ) ?></h5>
                        <span class="text-muted">Total Box</span>
                    </div>
                </div>  
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-clipboard3"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-qty"><?= (is_null($totalQty)) ? '0' : $totalQty ?></h5>
                        <span class="text-muted">Grand Total Qty</span>

                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-original">$<?= number_format($totalOriginal, 2) ?></h5>
                        <span class="text-muted">Grand Total Original</span>

                    </div>
                </div>    
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-wallet"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-client-cost">$<?= number_format($totalCost, 2) ?></h5>
                        <span class="text-muted">Grand Total Client Cost</span>
                    </div>
                </div>  
            </div>  
            <form id="exportForm" method="post" action="/export-box">
                <table class="table datatable-basic" id="myTable" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No</th>
                            <th class="text-center">Box Description</th>                        
                            <th class="text-center" style="width: 10%">Total Item</th>
                            <th class="text-center" style="width: 10%">Unknown UPC</th>
                            <th class="text-center" style="width: 10%">Date</th>
                            <th class="text-center" style="width: 15%">Admin</th>    
                            <th class="text-left" style="width: 3%"><input type="checkbox" class="check_all" /></th>                    
                        </tr>
                    </thead>
                    <tbody id="assign-body">
                        <?php if ($boxes->getNumRows() > 0) : ?>
                            <?php $no = 1 ?>
                            <?php foreach ($boxes->getResultArray() as $row) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $no++ ?>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="font-weight-bold box_name h6 name_box_<?= $no ?>" data-box="<?= $row['box_name'] ?>">
                                            <?= $row['box_name'] ?>
                                        </a>
                                        <br>
                                        <p class="desc_box_<?= $no ?>">
                                            <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                                <?php $desc = substr($row['description'], $pos + 1);     ?>
                                                <?= $desc  ?>
                                            <?php else : ?>
                                                None
                                            <?php endif ?>
                                        </p>
                                    </td>
                                    <?php if ($row['total_item'] > 0) : ?>
                                        <td class="text-center">
                                            <?= $row['total_item'] ?>
                                        </td>
                                    <?php else : ?>
                                        <td class="text-center">
                                            -
                                        </td>
                                    <?php endif ?>
                                    <?php if ($row['unknown_upc'] > 0) : ?>
                                        <td class="text-center text-danger">
                                            <?= $row['unknown_upc'] ?>
                                        </td>
                                    <?php else : ?>
                                        <td class="text-center">
                                            -
                                        </td>
                                    <?php endif ?>
                                    <td class="text-center">
                                        <?= date('m/d/Y H:i:s', strtotime($row['date_assigned'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $row['fullname'] ?>
                                    </td>
                                    <td class="text-left">
                                        <input type="checkbox" name="box[]" class="box_checklist" value="<?= $row['id'] ?>">
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>

                </table>
            </form>
        </div>
    
    </div>
    
    <div class="modal fade modal_scrollable_box" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title">#title</span></b> </h5>
                </div>
                <div class="modal-body py-0">
                    <form id="box-details">
                                              
                        <div class="table-responsive" id="item-table">
                            <!-- <form action="<?= base_url('/save-box-details') ?>" method="post"> -->

                            <?php csrf_field() ?>
                            <input type="hidden" name="box_name" id="box_name" value="">
                            <table class="table text-center" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 10%;">UPC</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody">

                                </tbody>
                            </table>

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
    <button type="button" id="noty_error" style="display: none;"></button>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>

    $(document).ready(function() {
        $('input[name="datefilter"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="end"]').val(end.format('YYYY-MM-DD'));
        });
        $('input[name="datefilter"]').change(function() {
            $('.filter').submit();
            
        });

        $(".check_all").click(function(){
            $('.box_checklist').prop('checked', this.checked);
        });
    });

    var i = 1;
    var tempTotal = 0;
    var total = 0;

    $('.export-button').on('click', function() {
        $('#exportForm').submit()
    });

    $('.box_name').on('click', function() {
        var boxName = $(this).data('box');
        $('#item-table tbody').html("");
        $('#sum tbody').html("");
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
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                for (var i = 0; i < item.length; i++) {
                    if (i % 2 == 0) {
                        if (item[i]['item_description'] == "ITEM NOT FOUND") {
                            $('#item-table tbody').append('<tr><td class="text-danger"><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td><td class="text-left text-danger">' + item[i]['item_description'] + '</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-left">-</td></tr>');
                        } else {
                            $('#item-table tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td><td class="text-left">' + item[i]['item_description'] + '</td><td class="text-center">' + item[i]['cond'] + '</td><td class="text-center">' + item[i]['qty'] + '</td><td class="text-center">$'+item[i]['retail']+'</td><td class="text-center">$'+item[i]['original']+'</td><td class="text-center">$'+parseFloat(item[i]['cost']).toFixed(2)+'</td><td class="text-left">' + item[i]['vendor'] + '</td></tr>');
                        }
                    } else {
                        if (item[i]['item_description'] == "ITEM NOT FOUND") {
                            $('#item-table tbody').append('<tr class="table-secondary"><td class="text-danger"><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td><td class="text-left text-danger">' + item[i]['item_description'] + '</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td><td class="text-left">-</td></tr>');
                        } else {
                            $('#item-table tbody').append('<tr class="table-secondary"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td><td class="text-left">' + item[i]['item_description'] + '</td><td class="text-center">' + item[i]['cond'] + '</td><td class="text-center">' + item[i]['qty'] + '</td><td class="text-center">$'+item[i]['retail']+'</td><td class="text-center">$'+item[i]['original']+'</td><td class="text-center">$'+parseFloat(item[i]['cost']).toFixed(2)+'</td><td class="text-left">' + item[i]['vendor'] + '</td></tr>');
                        }                        
                    }
                }                
            }

            $('.modal_scrollable_box').modal('show');
        });

    });

    $("form#box-details").on("submit", function(e) {
        e.preventDefault();
        $.post('<?= base_url('/update-price-item') ?>', $(this).serialize(), function(data) {
            new Noty({
                text: 'The box has been saved.',
                type: 'alert'
            }).show();
            $('.modal_scrollable_box').modal('hide');
        });
    });



    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the report.',
            type: 'success'
        }).show();


    });

    $('#noty_save').on('click', function() {
        new Noty({
            text: 'You successfully save first phase.',
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