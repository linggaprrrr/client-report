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
                        <h6 class="font-weight-semibold mb-0 qty-sold-label">...</h6>
                        <span class="text-muted">Qty Sold</span>
                        <input type="hidden" name="qty_sold" class="qty_sold" value="">
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cube"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger qty-returned-label">...</h6>
                        <span class="text-muted">Qty Returned</span>
                        <input type="hidden" name="qty_returned" class="qty_returned" value="">
                    </div>
                </div>     
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 sold-label">$ ...</h6>
                        <span class="text-muted">Sold</span>
                        <input type="hidden" name="sold" class="sold" value=""> 
                    </div>
                </div>       
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger returned-label">$ ...</h6>
                        <span class="text-muted">Returned</span>
                        <input type="hidden" name="returned" class="returned" value="">
                    </div>
                </div>        
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-coins"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger cogs-label">$ ...</h6>
                        <span class="text-muted">COGS</span>
                        <input type="hidden" name="cogs" class="cogs" value="">

                    </div>
                </div>    
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash3"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 gross-profit-label">$ ...</h6>
                        <span class="text-muted">Gross Profit</span>
                        <input type="hidden" name="gross_profit" class="gross-profit" value="">
                    </div>
                </div>  
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-percent"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 gross-profit-margin-label">... %</h6>
                        <span class="text-muted">Gross Profit Margin</span>
                        <input type="hidden" name="gross_profit_margin"  class="gross-profit-margin" value="">

                    </div>
                </div>     
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-piggy-bank"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 text-danger fees-label">$ ...</h6>
                        <span class="text-muted">Fees</span>
                        <input type="hidden" name="fees" class="fees" value="">

                    </div>
                </div>        
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-cash3"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 net-profit-label">$ ...</h6>
                        <span class="text-muted">Net Profit</span>
                        <input type="hidden" name="net_profit" class="net-profit" value="">

                    </div>
                </div>   
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-percent"></i>
                    </a>
                    <div class="ml-3">
                        <h6 class="font-weight-semibold mb-0 net-profit-margin-label">... %</h6>
                        <span class="text-muted">Net Profit Margin</span>
                        <input type="hidden" name="net_profit_margin" class="net-profit-margin" value="">

                    </div>
                </div>           
            </div>      
            <?php if ($skuNotFound->getNumRows() > 0) : ?>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <a class="navbar-nav-link navbar-nav-link-toggler refresh-pl float-right alert-warning p-0" >
                            <i class="icon-sync"></i>
                        </a>
                        <h5 class="alert-heading">Woops there's a problem!</h5>
                        <p><a href="#" class="alert-link"><span class="num-upc"><?= $skuNotFound->getNumRows() ?></span> UPC are missing</a>, please check the manifest!</p>
                        <hr>
                        <p class="mb-0"> Missing UPCs as follow: 
                            <span class="upc-list font-weight-bold">
                            <?php foreach($skuNotFound->getResultObject() as $sku) : ?>
                                <b><?= $sku->sku.', ' ?></b>
                            <?php endforeach ?>
                            </span>                            
                        </p>
                    </div>
                </div>
            <?php endif ?> 
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                    Preview</button>
                
                <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">P&L Preview</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5>Month: <small id="month" class="font-weight-bold"><?= strtoupper($month) ?></small></h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Chart Title</th>
                                        <th scope="col">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Active SKU</td>
                                        <td id="active-sku"><input type="text" class="form-control" name="active-sku"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Gross Sales</td>
                                        <td id="gross-sales">...</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Net Sales</td>
                                        <td id="net-sales">...</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>COGS</td>
                                        <td id="cogs">...</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Gross Profit</td>
                                        <td id="gross-profit">...</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Gross Profit Margin</td>
                                        <td id="gross-profit-margin">...</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Fees and Subtractions</td>
                                        <td id="fees">...</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Fees and Subtractions Rate</td>
                                        <td id="fees-rate">...</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Refunds</td>
                                        <td id="refunds">...</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Inbound Transport Fees</td>
                                        <td id="inbound-fees">...</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Storage Fees</td>
                                        <td id="storage-fees">...</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Net Profit</td>
                                        <td id="net-profit">...</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Net Profit Margin</td>
                                        <td id="net-profit-margin">...</td>
                                    </tr>
                                </tbody>
                                </table>
                        </div>
                        <div class="modal-footer">
                           
                            <button type="submit" class="btn btn-secondary"><i class="icon-checkmark3"></i> Save</button>
                        </div>
                        </div>
                    </div>
                </div>
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
        const client = <?= $client['id'] ?>;
        const id = <?= $id ?>;

        $.get( "<?= base_url('/get-summary-pl') ?>", { id: id, client: client }, function(data) {
            const summary = JSON.parse(data);            
            $('.qty-sold-label').html(summary['qtySold']);
            $('.qty_sold').val(summary['qtySold']);

            $('.qty-returned-label').html(summary['qtyReturned']);
            $('.qty_returned').val(summary['qtyReturned']);
            
            $('.sold-label').html('$ '+ parseFloat(summary['sold']).toFixed(2) );
            $('.sold').val(parseFloat(summary['sold']).toFixed(2));

            $('.returned-label').html('$ '+ parseFloat(summary['returned']).toFixed(2));
            $('.returned').val(parseFloat(summary['returned']).toFixed(2));       

            $('.cogs-label').html('$ '+ parseFloat(summary['cogs']).toFixed(2));
            $('.cogs').val(summary['cogs'].toFixed(2));   

            $('.gross-profit-label').html('$ '+ parseFloat(summary['grossProfit']).toFixed(2));
            $('.gross-profit').val(parseFloat(summary['grossProfit']).toFixed(2));   

            $('.gross-profit-margin-label').html(parseFloat(summary['grossProfitMargin']).toFixed(2) +'%');
            $('.gross-profit-margin').val(parseFloat(summary['grossProfitMargin']).toFixed(2));   

            $('.fees-label').html('$ '+ parseFloat(summary['fees']).toFixed(2));
            $('.fees').val(parseFloat(parseFloat(summary['fees'])).toFixed(2));   

            $('.net-profit-label').html('$ '+ parseFloat(summary['netProfit']).toFixed(2));
            $('.net-profit').val(parseFloat(summary['netProfit']).toFixed(2));   

            $('.net-profit-margin-label').html(parseFloat(summary['netProfitMargin']).toFixed(2) +'%');
            $('.net-profit-margin').val(parseFloat(summary['netProfitMargin']).toFixed(2));   


            $('#gross-sales').html('$ '+ parseFloat(summary['sold']).toFixed(0));
            $('#net-sales').html('$ '+ parseFloat(summary['netSales']).toFixed(0));
            $('#cogs').html('$ '+ parseFloat(summary['cogs']).toFixed(0));
            $('#gross-profit').html('$ '+ parseFloat(summary['grossProfit']).toFixed(0));
            $('#gross-profit-margin').html(parseFloat(summary['grossProfitMargin']).toFixed(2) + '%');
            $('#fees').html('$ '+ parseFloat(summary['fees']).toFixed(0));
            $('#fees-rate').html( (parseFloat(summary['netProfit'])/parseFloat(summary['grossProfit']) * 100).toFixed(2) + '%');
            $('#refunds').html('$ '+ parseFloat(summary['returned']).toFixed(0));
            $('#net-profit').html('$ '+ parseFloat(summary['netProfit']).toFixed(0));
            $('#net-profit-margin').html(parseFloat(summary['netProfitMargin']).toFixed(2) + '%');
            $('#inbound-fees').html('$ '+ parseFloat(summary['storageFee']).toFixed(0));
            $('#storage-fees').html('$ '+ parseFloat(summary['transportFee']).toFixed(0));

        });

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

    $('.refresh-pl').click(function() {
        const client = <?= $client['id'] ?>;
        const id = <?= $id ?>;

        $('.qty-sold-label').html('...');
        $('.qty-returned-label').html('...');
        $('.sold-label').html('$ ...');
        $('.returned-label').html('$ ...');
        $('.cogs-label').html('$ ...');
        $('.gross-profit-label').html('$ ...');
        $('.gross-profit-margin-label').html('...%');
        $('.fees-label').html('$ ...');
        $('.net-profit-label').html('$ ...');
        $('.net-profit-margin-label').html('...%');
        $('.num-upc').html('...');
        $('.upc-list').html('');
           
        $.get( "<?= base_url('/get-summary-pl') ?>", { id: id, client: client }, function(data) {
            const summary = JSON.parse(data);            
            $('.qty-sold-label').html(summary['qtySold']);
            $('.qty_sold').val(summary['qtySold']);

            $('.qty-returned-label').html(summary['qtyReturned']);
            $('.qty_returned').val(summary['qtyReturned']);
            
            $('.sold-label').html('$ '+ parseFloat(summary['sold']).toFixed(2) );
            $('.sold').val(parseFloat(summary['sold']).toFixed(2));

            $('.returned-label').html('$ '+ parseFloat(summary['returned']).toFixed(2));
            $('.returned').val(parseFloat(summary['returned']).toFixed(2));       

            $('.cogs-label').html('$ '+ parseFloat(summary['cogs']).toFixed(2));
            $('.cogs').val(summary['cogs'].toFixed(2));   

            $('.gross-profit-label').html('$ '+ parseFloat(summary['grossProfit']).toFixed(2));
            $('.gross-profit').val(parseFloat(summary['grossProfit']).toFixed(2));   

            $('.gross-profit-margin-label').html(parseFloat(summary['grossProfitMargin']).toFixed(2) +'%');
            $('.gross-profit-margin').val(parseFloat(summary['grossProfitMargin']).toFixed(2));   

            $('.fees-label').html('$ '+ parseFloat(summary['fees']).toFixed(2));
            $('.fees').val(parseFloat(parseFloat(summary['fees'])).toFixed(2));   

            $('.net-profit-label').html('$ '+ parseFloat(summary['netProfit']).toFixed(2));
            $('.net-profit').val(parseFloat(summary['netProfit']).toFixed(2));   

            $('.net-profit-margin-label').html(parseFloat(summary['netProfitMargin']).toFixed(2) +'%');
            $('.net-profit-margin').val(parseFloat(summary['netProfitMargin']).toFixed(2));   


            $('#gross-sales').html('$ '+ parseFloat(summary['sold']).toFixed(0));
            $('#net-sales').html('$ '+ parseFloat(summary['netSales']).toFixed(0));
            $('#cogs').html('$ '+ parseFloat(summary['cogs']).toFixed(0));
            $('#gross-profit').html('$ '+ parseFloat(summary['grossProfit']).toFixed(0));
            $('#gross-profit-margin').html(parseFloat(summary['grossProfitMargin']).toFixed(2) + '%');
            $('#fees').html('$ '+ parseFloat(summary['fees']).toFixed(0));
            $('#fees-rate').html( (parseFloat(summary['netProfit'])/parseFloat(summary['grossProfit'])).toFixed(2) + '%');
            $('#refunds').html('$ '+ parseFloat(summary['returned']).toFixed(0));
            $('#net-profit').html('$ '+ parseFloat(summary['netProfit']).toFixed(0));
            $('#net-profit-margin').html(parseFloat(summary['netProfitMargin']).toFixed(2) + '%');
            $('#inbound-fees').html('$ '+ parseFloat(summary['storageFee']).toFixed(0));
            $('#storage-fees').html('$ '+ parseFloat(summary['transportFee']).toFixed(0));

            $('.num-upc').html(summary['numOfSku']);
            for (var i = 0; i < parseInt(summary['numOfSku']); i++) {
                $('.upc-list').append(summary['missingSku'][i] + ', ');
            }
        });
    });


</script>

<?= $this->endSection() ?>