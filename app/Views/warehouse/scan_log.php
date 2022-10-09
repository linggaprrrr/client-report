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
            <button class="btn btn-danger box-button" data-toggle="modal" data-target="#modal_box"><i class="icon-dropbox mr-2"></i>New Box</button>
            <h1 class="float-right" id="box_name"></h1>
            <div id="modal_box" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title">Assign New Box</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="newBox">
                            <?php csrf_field() ?>                            
                            <div class="modal-body">                                 
                                <div class="form-group">
                                    <label>Box Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-drawer-in"></i></span>
                                        </span>
                                        <input type="text" class="form-control box-name" name="box" required>
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label>Box Category:</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-price-tag2"></i></span>
                                        </span>
                                        <select name="category" class="form-control category" required>
                                            <option value="">-</option>
                                            <option value="1">SHOES</option>
                                            <option value="2">CLOTHES</option>
                                        </select>
                                    </div>
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
        <div class="card-body">
            <form class="wizard-form steps-async wizard clearfix" id="log-form"  method="post" >
                <div class="table-responsive">                
                    <?= csrf_field() ?>
                    <input type="hidden" class="box" name="box">
                    <input type="hidden" class="category" name="category">
                    <table class="table table-striped table-bordered display nowrap" width="100%" style="font-size: 10px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 15%">UPC/SKU</th>
                                <th class="text-center" style="width: 25%">ITEM DESCRIPTION</th>
                                <th class="text-center" style="width: 7%">CONDITION</th>
                                <th class="text-center" style="width: 8%">ORIGINAL QTY</th>
                                <th class="text-center" style="width: 8%">RETAIL VALUE</th>
                                <th class="text-center" style="width: 8%">TOTAL ORIGINAL VALUE</th>
                                <th class="text-center" style="width: 8%">TOTAL CLIENT COST</th>
                                <th class="text-center" style="width: 25%">VENDOR NAME</th>
                            </tr>
                        </thead>
                        <tbody class="upc-row">
                            <tr class="item-list">
                                <td><input type="text" name="upc[]" class="form-control custom-field upc" disabled></td>
                                <td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td>
                                <td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td>
                                <td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td>
                                <td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td>
                                <td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td>
                                <td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td>
                                <td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>    
            </form>   
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

    $('.btn-save').click(function() {
        $.post( '<?= base_url('save-log') ?>', $('form#log-form').serialize(), function(data) {
            location.reload();
        });
    })

    $(document).on('change', '.upc', function(){           
        $(".desc").val('...');
        $(".condition").val('...');
        $(".qty").val('...');
        $(".retail").val('$...');
        $(".original-retail").val('$...');
        $(".client-cost").val('$...');
        $(".vendor-name").val('...');
        // search upc
        const upc = $('.upc').val();
        var itemCost = 0;
        $.get('/search-upc', {upc: upc}, function(data) {
            const res = JSON.parse(data);
            if (res != "0") {
                $(".desc").val(res['item_description']);
                $(".condition").val('NEW');
                $(".qty").val('1');
                $(".retail").val('$'+res['retail_value']);
                $(".original-retail").val('$'+res['retail_value']);
                if (cat == 1) {
                    itemCost = parseFloat(res['retail_value']) / 3;
                } else {
                    itemCost = parseFloat(res['retail_value']) / 4;
                }
                $(".client-cost").val('$'+itemCost);
                $(".vendor-name").val(res['vendor_name']);

                $.post('<?= base_url('save-log') ?>', {
                    upc: upc,
                    desc: res['item_description'],
                    retail: res['retail_value'],
                    vendor: res['vendor_name'],
                    category: cat,
                    box: $('.box-name').val(),
                }, function(data) {
                    
                });
            } else {
                $(".upc").addClass("alert-danger");
                $(".desc").val('ITEM NOT FOUND');                
                $(".condition").val('...');
                $(".qty").val('...');
                $(".retail").val('$...');
                $(".original-retail").val('$...');
                $(".client-cost").val('$...');
                $(".vendor-name").val('...');

                $.post('<?= base_url('save-log') ?>', {
                    upc: upc,
                    desc: "ITEM NOT FOUND",
                    category: cat,
                    box: $('.box-name').val(),
                }, function(data) {
                    
                });
            }
            $(".desc").removeClass('desc');
            $(".upc").removeClass('upc');
            $(".condition").removeClass('condition');
            $(".qty").removeClass('qty');
            $(".retail").removeClass('retail');
            $(".original-retail").removeClass('original-retail');
            $(".client-cost").removeClass('client-cost');
            $(".vendor-name").removeClass('vendor-name');
    
            $(".upc-row").append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc"></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');
            $(".upc").focus();
        });        
    });        
    
    $('.box-button').click(function() {
        if (cat != '') {
            $('.upc-row').html("");
            $('#box_name').html("");
            $('#modal_box').modal('show');
            $('.box-name').val("");
            $('.category').val("");
            $('.upc-row').append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');                  
        } else {
            $('.upc-row').html("");
            $('#box_name').html("");
            $('#modal_box').modal('show');
            $('.box-name').val("");
            $('.category').val("");
            $('.upc-row').append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');
        }
        
    });
</script>
<?= $this->endSection() ?>