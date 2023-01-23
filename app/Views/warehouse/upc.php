<?= $this->extend('warehouse/layout/template') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="card">
        <div class="card-header">    
            <form action="<?= base_url('/warehouse/upc') ?>" method="get">                
                <h6 class="font-weight-semibold mb-0">
                <select name="client" class="form-control select-search" id="select-client"  onchange="this.form.submit()" data-fouc>                    
                    <option value="">All</option>
                    <?php foreach ($clients->getResultArray() as $row) : ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $clientSelect ? 'selected' : '' ?> ><?= $row['fullname'] ?> - <?= $row['company'] ?></option>
                    <?php endforeach ?>
                </select>
                </h6>                
            </form>
            
        </div>
        
        <?php if ($clientSelect == "") : ?>
            <div class="card-body">
                <div class="text-right extract-btn" style="display: none;">
                    <a href="/export-search-all/" class="btn btn-success export-search"><i class="icon-file-excel mr-2"></i> Export Result</a>
                </div>                
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover clientAll" width="100%" style="font-size: 10px;" cellspacing="0">
                            <thead>
                                <tr> 
                                    <th class="text-center">CLIENT</th>                                
                                    <th class="text-center">COMPANY</th>                                
                                    <th class="text-center" style="width: 15%">UPC</th>                                
                                    <th class="text-center" style="width: 25%">ITEM DESCRIPTION</th>
                                    <th class="text-center" style="width: 5%">QTY</th>
                                    <th class="text-center" style="width: 5%">RETAIL VALUE</th>
                                    <th class="text-center" style="width: 5%">TOTAL RETAIL</th>
                                    <th class="text-center" style="width: 5%">CLIENT COST</th>
                                    <th class="text-center" style="width: 20%">VENDOR NAME</th>
                                </tr>
                            </thead>                        
                        </table>
                    </div>
                
                </div>
            </div>
        <?php else : ?>
            <div class="card-body">
                <div class="text-right extract-btn" style="display: none;">
                    <a href="/export-search/" class="btn btn-success export-search" ><i class="icon-file-excel mr-2"></i> Export Result</a>
                </div>
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover client" width="100%" style="font-size: 10px;" cellspacing="0">
                            <thead>
                                <tr>
                                     
                                    <th class="text-center" style="width: 15%">UPC</th>                                
                                    <th class="text-center" style="width: 25%">ITEM DESCRIPTION</th>
                                    <th class="text-center" style="width: 5%">QTY</th>
                                    <th class="text-center" style="width: 5%">RETAIL VALUE</th>
                                    <th class="text-center" style="width: 5%">TOTAL RETAIL</th>
                                    <th class="text-center" style="width: 5%">CLIENT COST</th>
                                    <th class="text-center" style="width: 20%">VENDOR NAME</th>
                                </tr>
                            </thead>                        
                        </table>
                    </div>
                
                </div>
            </div>
        <?php endif ?>
    </div>
    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
</div>



<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script src="/assets/js/demo_pages/form_select2.js"></script>
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>


<script>
    $(document).ready(function() {
       

        $('.clientAll').DataTable({
            "aLengthMenu": [[1000, -1], [1000, "All"]],
            "iDisplayLength": false,
            "processing": true,
            "serverSide": true,
            "ajax":{
                    "url": "<?= base_url('load-client-upc') ?>",
                    "dataType": "json",
                    "type": "POST"
                },
            "columns": [
                { "data": "fullname" },                
                { "data": "company" },                
                { "data": "sku" },                
                { "data": "item_description" },
                { "data": "qty" },
                { "data": "retail_value" },
                { "data": "total_retail" },
                { "data": "client_cost" },
                { "data": "vendor" },
            ],
        });

        $('.client').DataTable({
            "aLengthMenu": [[1000, -1], [1000, "All"]],
            "iDisplayLength": false,
            "processing": true,
            "serverSide": true,
            "ajax":{
                    "url": "<?= base_url('load-client-upc') ?>/<?= $clientSelect ?>",
                    "dataType": "json",
                    "type": "POST"
                },
            "columns": [
                
                { "data": "sku" },                
                { "data": "item_description" },
                { "data": "qty" },
                { "data": "retail_value" },
                { "data": "total_retail" },
                { "data": "client_cost" },
                { "data": "vendor" },
            ],
            
        });


        $('.clientAll').on('search.dt', function() {
            var value = $('.dataTables_filter input').val();
            if (value != "") {
                $('.extract-btn').css('display', 'block');
            } else {
                $('.extract-btn').css('display', 'none');
            }
            $(".export-search").attr("href", "/export-search-all/"+value);
        }); 

        $('.client').on('search.dt', function() {
            var value = $('.dataTables_filter input').val();
            var client = $('#select-client').val();
            if (value != "") {
                $('.extract-btn').css('display', 'block');
            } else {
                $('.extract-btn').css('display', 'none');
            }
            $(".export-search").attr("href", "/export-search/"+client+"/"+value);
            
        }); 
      
    });

</script>
<?= $this->endSection() ?>