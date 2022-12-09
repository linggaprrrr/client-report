<?= $this->extend('warehouse/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin: 0 20px 20px;
    }
    .custom-field {
        font-size: 10px;
        text-align: center;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">                                
        </div>
        <div class="card-body">
            <form class="wizard-form steps-async wizard clearfix" action="<?= base_url('/warehouse/input-manual') ?>" onkeydown="return event.key != 'Enter';" method="post" >
                <div class="table-responsive">                
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Client Name:</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-user"></i></span>
                            </span>
                            <select class="form-control client-select" name="client" required>
                                <option value="">-</option>
                                <?php if ($getAllClient->getNumRows() > 0) : ?>
                                    <?php foreach ($getAllClient->getResultArray() as $row) : ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['fullname'] . " (" . $row['company'] . ")" ?></option>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <option value="-">-</option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Investment Date:</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar22"></i></span>
                            </span>
                            <select class="form-control investment-date" name="investment" required>
                               
                            </select>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered display nowrap" width="100%" style="font-size: 10px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 15%">UPC/SKU</th>
                                <th class="text-center" style="width: 25%">ITEM DESCRIPTION</th>
                                <th class="text-center" style="width: 7%">CONDITION</th>
                                <th class="text-center" style="width: 8%">ORIGINAL QTY</th>
                                <th class="text-center" style="width: 8%">RETAIL VALUE</th>
                                <th class="text-center" style="width: 8%">TOTAL CLIENT COST</th>
                                <th class="text-center" style="width: 25%">VENDOR NAME</th>
                            </tr>
                        </thead>
                        <tbody class="upc-row">
                            <tr class="item-list">
                                <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td>
                                <td><input type="text" name="desc[]" class="form-control custom-field desc" ></td>
                                <td><input type="text" name="condition[]" class="form-control custom-field condition" value="NEW" readonly></td>
                                <td><input type="text" name="qty[]" class="form-control custom-field qty" value="1"></td>                                
                                <td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" ></td>
                                <td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" ></td>
                                <td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <div class="float-right mt-4">
                    <button type="submit" class="btn btn-secondary"><i class="icon-checkmark2 mr-2"></i>Save</button>
                </div>  
            </form>   
        </div>

    </div>

    <div class="card">
        <div class="card-header pb-0">
            <div class="form-group float-left" style="margin: 0;">
                <label>
                    <form action="<?= base_url('/warehouse/input-item') ?>" class="filter" method="get">
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
            <a href="<?= base_url('/warehouse/export-manual-input'.'/'. $date1 .'/'. $date2.'') ?>" class="btn btn-primary float-right"><i class="icon-file-spreadsheet mr-2"></i>Export Data</a>
        </div>
        <div class="card-body">
            <hr>      
            <div class="d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">               
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-clipboard3"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-qty"><?= (is_null($totalQty->qty)) ? '0' : $totalQty->qty ?></h5>
                        <span class="text-muted">Grand Total Qty</span>

                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-original">$<?= number_format($totalRetail->retail, 2) ?></h5>
                        <span class="text-muted">Grand Total Retail</span>

                    </div>
                </div> 
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-original">$<?= number_format($totalOriginal->original, 2) ?></h5>
                        <span class="text-muted">Grand Total Original</span>

                    </div>
                </div>    
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-wallet"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0 total-client-cost">$<?= number_format($totalClientCost->client_cost, 2) ?></h5>
                        <span class="text-muted">Grand Total Client Cost</span>
                    </div>
                </div>  
            </div>        
            <table class="table datatable-basic" id="myTable" style="font-size: 11px;">
                <thead>
                    <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">UPC/SKU</th>
                    <th class="text-center">ITEM DESCRIPTION</th>
                    <th class="text-center">CONDITION</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">RETAIL VALUE</th>
                    <th class="text-center">ORIGINAL QTY</th>                    
                    <th class="text-center">CLIENT COST</th>
                    <th class="text-center">VENDOR NAME</th>                    
                    <th class="text-center">ADMIN</th>
                    <th class="text-center">INPUT DATE</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($items->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($items->getResultArray() as $row) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $no++ ?>                                    
                                </td>
                                <td class="text-center">
                                    <?= $row['sku'] ?>
                                </td>
                                <td class="text-center">
                                    <?= $row['item_description'] ?>
                                </td>
                                <td class="text-center">
                                    <?= $row['cond'] ?>
                                </td>
                                <td class="text-center">
                                    <?= $row['qty'] ?>
                                </td>
                                <td class="text-center">
                                    $<?= number_format($row['retail_value'], 2) ?>
                                </td>
                                <td class="text-center">
                                    $<?= number_format($row['original_value'], 2) ?>
                                </td>
                                <td class="text-center">
                                    $<?= number_format($row['cost'], 2) ?>
                                </td>    
                                <td class="text-center">
                                    <?= $row['vendor'] ?>
                                </td>                                
                                <td class="text-center">
                                    <?= $row['fullname'] ?>
                                </td>
                                <td class="text-center">
                                    <?= date('m/d/Y', strtotime($row['input_date'])) ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>

            </table>
        </div>
    
    </div>

    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
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
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    });

    var cat = "";
    $('form#newBox').submit(function(event) {
        const boxName = $('.box-name').val();
        cat = $('.category').val();        
        var desc = "";
        if (cat == "1") {
            desc = "SHOES";
        } else {
            desc = "CLOTHES";
        }
        $.post('<?= base_url('save-box') ?>', {            
            category: desc,
            box: $('.box-name').val(),
        }, function(data) {
                  
        });
        $('#box_name').html('BOX #'+boxName+'-'+desc);
        $('.box').val(boxName);
        $('.cat').val(cat);
        $('#modal_box').modal('hide');
        $('.upc').prop("disabled", false);            
        event.preventDefault();        
        
    });

    $('.client-select').change(function() {
        const clientId = $(this).val();
        $.post('/get-investment-client-all', {
            id: clientId
        }, function(data) {
            var investdate = JSON.parse(data);
            if (investdate.length > 0) {
                for (var i = 0; i < investdate.length; i++) {
                    $('.investment-date').append(investdate[i]);
                }
            }  
        });
    });

    $('.btn-save').click(function() {
        $.post( '<?= base_url('save-log') ?>', $('form#log-form').serialize(), function(data) {
            location.reload();
        });
    })

    $(document).on('change', '.vendor-name', function(){                           
        const upc = $('.upc').val();
        $(".upc-row").append('<tr class="item-list"><td><input type="text" name="upc[]" class="form-control custom-field upc"></td><td><input type="text" name="desc[]" class="form-control custom-field desc"></td><td><input type="text" name="condition[]" class="form-control custom-field condition" value="NEW" readonly="readonly"></td><td><input type="text" name="qty[]" class="form-control custom-field qty" value="1"></td><td><input type="text" name="original-retail[]" class="form-control custom-field retail"></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost"></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name"></td></tr>');      
    });        
    
</script>
<?= $this->endSection() ?>