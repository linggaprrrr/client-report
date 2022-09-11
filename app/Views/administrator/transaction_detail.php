<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .client-name {
        font-weight: bold;
    }
</style>
<div class="content">
    <div class="card">   
        <div class="card-header">
            <h5>Client Name: <b><?= $client['fullname'] ?> (<?= $client['company'] ?>)</b></h5>
            <h5>Date: <b><?= $daterange ?></b></h5>
        </div>
        <form method="POST" action="<?= base_url('admin/save-chart') ?>">
        <?php csrf_field() ?>
        <input type="hidden" name="client" value="<?= $client['id'] ?>">
        <input type="hidden" name="month" value="<?= $month ?>">
            <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">                
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cube"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0"><?= $qtySold ?></h6>
                        <span class="text-muted">Qty Sold</span>
                        <input type="hidden" name="qty_sold" value="<?= $qtySold ?>">
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cube"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger"><?= $qtyReturned ?></h6>
                        <span class="text-muted">Qty Returned</span>
                        <input type="hidden" name="qty_returned" value="<?= $qtyReturned ?>">
                    </div>
                </div>     
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0">$<?= ($sold == 0) ? 0 : number_format($sold, 2) ?></h6>
                        <span class="text-muted">Sold</span>
                        <input type="hidden" name="sold" value="<?= $sold ?>"> 
                    </div>
                </div>       
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger">$<?= number_format($returned, 2) ?></h6>
                        <span class="text-muted">Returned</span>
                        <input type="hidden" name="returned" value="<?= $returned ?>">
                    </div>
                </div>        
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-coins"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger">$<?= number_format($cogs, 2) ?></h6>
                        <span class="text-muted">COGS</span>
                        <input type="hidden" name="cogs" value="<?= $cogs ?>">

                    </div>
                </div>    
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash3"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0">$<?= number_format($grossProfit, 2) ?></h6>
                        <span class="text-muted">Gross Profit</span>
                        <input type="hidden" name="gross_profit" value="<?= $grossProfit ?>">
                    </div>
                </div>  
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-percent"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0"><?= ($sold == 0) ? 0 : number_format(($grossProfit/$sold), 2) ?>%</h6>
                        <span class="text-muted">Gross Profit Margin</span>
                        <input type="hidden" name="gross_profit_margin" value="<?= ($sold == 0) ? 0  : $grossProfit/$sold ?>">

                    </div>
                </div>     
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-piggy-bank"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger">$<?= number_format($fees, 2) ?></h6>
                        <span class="text-muted">Fees</span>
                        <input type="hidden" name="fees" value="<?= $fees ?>">

                    </div>
                </div>        
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash3"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0">$<?= number_format($fees+$grossProfit, 2) ?></h6>
                        <span class="text-muted">Net Profit</span>
                        <input type="hidden" name="net_profit" value="<?= $fees+$grossProfit ?>">

                    </div>
                </div>   
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-percent"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0"><?= number_format(($fees+$grossProfit)/$grossProfit, 2) ?>%</h6>
                        <span class="text-muted">Net Profit Margin</span>
                        <input type="hidden" name="net_profit_margin" value="<?= (($fees+$grossProfit)/$grossProfit) ?>">

                    </div>
                </div>           
            </div>        
            <table class="table datatable-basic" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>settlement-id</th>
                        <th>sku</th>
                        <th>transaction-type</th>
                        <th>order-id</th>
                        <th>amount-type</th>
                        <th>amount-description</th>
                        <th>amount</th>
                        <th>posted-date</th>
                        <th>quantity-purchased</th>                    
                    </tr>
                </thead>
                <tbody>
                    <?php if ($transactions->getNumRows() > 0) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($transactions->getResultArray() as $row) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>                            
                                <td><?= $row['settlement-id'] ?></td>
                                <td><?= $row['sku'] ?></td>
                                <td><?= $row['transaction-type'] ?></td>
                                <td><?= $row['order-id'] ?></td>
                                <td><?= $row['amount-type'] ?></td>
                                <td><?= $row['amount-description'] ?></td>
                                <td><?= $row['amount'] ?></td>
                                <td><?= $row['posted-date'] ?></td>
                                <td><?= $row['quantity-purchased'] ?></td>                           
                            </tr>
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
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
    });

    $(function() {
        $('#daterange').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
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

    var input = document.getElementById('file-upload');
    var infoArea = document.getElementById('file-upload-filename');
    var input2 = document.getElementById('file-upload2');
    var infoArea2 = document.getElementById('file-upload-filename2');
    var input3 = document.getElementById('file-upload3');
    var infoArea3 = document.getElementById('file-upload-filename3');

    input.addEventListener('change', showFileName);
    input2.addEventListener('change', showFileName2);
    input3.addEventListener('change', showFileName3);

    function showFileName(event) {
        // the change event gives us the input it occurred in 
        var input = event.srcElement;
        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input.files[0].name;
        // use fileName however fits your app best, i.e. add it into a div
        infoArea.textContent = '' + fileName;
    }


    function showFileName2(event) {
        // the change event gives us the input it occurred in 
        var input = event.srcElement;
        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input2.files[0].name;
        // use fileName however fits your app best, i.e. add it into a div
        infoArea2.textContent = '' + fileName;
    }

    function showFileName3(event) {
        // the change event gives us the input it occurred in 
        var input = event.srcElement;
        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input3.files[0].name;
        // use fileName however fits your app best, i.e. add it into a div
        infoArea3.textContent = '' + fileName;
    }

    $('.editpl').click(function() {
        var id = $(this).data('id');
        $('#client').val("");
        $('#client_id').val("");
        $('#link').val("");
        $('#chart').html("");
        $('#log_id').val("");
        $.get('/get-plclient', {
            log_id: id
        }, function(data) {
            var pl = JSON.parse(data);
            $('#client').val(pl['fullname']);
            $('#client_id').val(pl['user_id']);
            $('#link').val(pl['link']);
            $('#chart').html(pl['file']);
            $('#log_id').val(pl['log_id']);
        });
    });

</script>

<?= $this->endSection() ?>