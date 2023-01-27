<?= $this->extend('warehouse/layout/template') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="card">
        <div class="card-header">    
            <form action="<?= base_url('/warehouse/manifest') ?>" method="get">                
                <h6 class="font-weight-semibold mb-0">
                <select name="client" class="form-control select-search" id="select-client"  onchange="this.form.submit()" data-fouc>                    
                    <option value="">-</option>
                    <?php foreach ($clients->getResultArray() as $row) : ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $clientSelect ? 'selected' : '' ?> ><?= $row['fullname'] ?> - <?= $row['company'] ?></option>
                    <?php endforeach ?>
                </select>
                </h6>                
            </form>
            
        </div>
        
        <?php if ($clientSelect != "") : ?>
            <div class="card-body">
                <div class="text-right extract-btn">
                    <a href="/export-manifest/<?= $clientSelect ?>" class="btn btn-success export-search"><i class="icon-file-excel mr-2"></i> Export Result</a>
                </div>                
                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover client" width="100%"  style="font-size: 11px;" cellspacing="0">
                            <thead>
                                <tr>                                                 
                                    <th>SKU</th>
                                    <th>ITEM DESCRIPTION</th>                                    
                                    <th>CONDITION</th>
                                    <th>QTY</th>
                                    <th>RETAIL VALUE</th>
                                    <th>TOTAL RETAIL</th>
                                    <th>CLIENT COST</th>
                                    <th>VENDOR NAME</th>
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
    

        $('.client').DataTable({
            "aLengthMenu": [[1000, -1], [1000, "All"]],
            "iDisplayLength": 1000,
            "processing": true,
            "serverSide": true,
            "ajax":{
                    "url": "<?= base_url('load-client-manifest') ?>/<?= $clientSelect ?>",
                    "dataType": "json",
                    "type": "POST"
                },
            "columns": [                
                { 
                    "data": "sku",
                    "className": "text-center"
                },                                
               
                { 
                    "data": "item_description",
                    "className": "text-center"
                },   
                { 
                    "data": "cond",
                    "className": "text-center"
                },   
                { 
                    "data": "qty",
                    "className": "text-center"
                },   
                { 
                    "data": "retail_value",
                    "className": "text-center"
                },   
                { 
                    "data": "original_value",
                    "className": "text-center"
                },   
                { 
                    "data": "cost",
                    "className": "text-center"
                },   
                { 
                    "data": "vendor",
                    "className": "text-center"
                },   
            ],
            
        });

        $('#select-client').change(function() {
            const value = $(this).val();
            $(".export-search").attr("href", "/export-search/"+value);  
        })
      
    });

</script>
<?= $this->endSection() ?>