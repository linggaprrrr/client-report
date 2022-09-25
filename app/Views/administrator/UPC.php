<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<div class="content">


    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal_box"><i class="icon-file-excel mr-2"></i>Upload UPC</button>
            <div id="modal_box" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="<?= base_url('upload-upc') ?>" enctype="multipart/form-data">
                    <?php csrf_field() ?>      
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Upload UPC</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>                                              
                            <div class="modal-body">
                                <div class="form-group">                                    
                                    <div class="form-group">
                                        <label>UPC File:</label>
                                        <label class="custom-file">
                                            <input type="file" name="upc" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <span class="custom-file-label" id="file-upload-filename">Choose file</span>
                                        </label>
                                        <span class="form-text text-muted">Accepted formats: xls. Max file size 100Mb</span>
                                    </div>
                                </div>                            
                            </div>

                            <div class="modal-footer">
                                <div class="text-right">
                                    <button type="submit" id="submit" class="btn btn-secondary">Save <i class="icon-paperplane ml-2"></i></button>
                                </div>

                            </div>
                        </form>                        
                    </div>
                </div>                
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%" style="font-size: 10px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 15%">UPC</th>
                                <th class="text-center" style="width: 15%">ASIN</th>
                                <th class="text-center" style="width: 25%">ITEM DESCRIPTION</th>
                                <th class="text-center" style="width: 10%">RETAIL VALUE</th>
                                <th class="text-center" style="width: 20%">VENDOR NAME</th>
                            </tr>
                        </thead>
                        
                    </table>
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
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>


<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                    "url": "<?= base_url('load-upc') ?>",
                    "dataType": "json",
                    "type": "POST"
                },
            "columns": [
                
                { "data": "upc" },
                { "data": "asin" },
                { "data": "item_description" },
                { "data": "retail_value" },
                { "data": "vendor_name" },
            ],
            
        });

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
</script>
<?= $this->endSection() ?>