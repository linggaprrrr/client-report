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
            <div>
                <button class="btn btn-danger box-button" data-toggle="modal" data-target="#modal_box"><i class="icon-dropbox mr-2"></i>New Box</button>
                <!-- <button class="btn btn-success done-button"><i class="icon-file-excel mr-2"></i>Export to Excel</button> -->
            </div>
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
                                        <input type="text" class="form-control box-name" name="box" placeholder="box name" required>
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
                                <div class="form-group">
                                    <label>Box Dimensions:</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-inbox-alt"></i></span>
                                        </span>
                                        <input type="text" class="form-control box-dimension" name="box_dimension" placeholder="18x14x10-5lbs">
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
    var audio = new Audio('/assets/beep.mp3');
    $('form#newBox').submit(function(event) {
        const boxName = $('.box-name').val();
        const boxDimension = $('.box-dimension').val();
        cat = $('.category').val();        
        var desc = "";
        if (cat == "1") {
            desc = "SHOES";
        } else {
            desc = "CLOTHES";
        }
        event.preventDefault();
        $.post('<?= base_url('save-box') ?>', {            
            category: desc,
            box: boxName,
            dimension: boxDimension
        }, function(data) {
            const resp = JSON.parse(data);
            if (resp['status'] == '200') {
                $('#box_name').html('BOX #'+boxName+'-'+desc);
                $('.box').val(boxName);
                $('.cat').val(cat);
                $('#modal_box').modal('hide');
                $('.upc').prop("disabled", false);            
                        
                $('.box-button').html("<i class='icon-dropbox mr-2'></i>Done & Create a New Box");
            } else {
                
                $('#noty_created').click();
            }
        });
        
    });
    
    $('#noty_created').on('click', function() {
        new Noty({
            text: 'The box name is already in use.',
            type: 'alert'
        }).show();
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
        var title = '';
        var brand = '';
        audio.play();
        $.get('https://api.asinscope.com/products/lookup?key=lg9u9jgqqknvfnwzy2fqp68bk&upc='+upc+'&domain=com', function(data) {
            if (data['items'].length > 0) {
                title = data['items']['0']['title'];
                brand = data['items']['0']['brand'];
                $(".desc").val(title);
                $(".condition").val('NEW');
                $(".qty").val('1');
                $(".vendor-name").val(brand);
                $.get('/search-upc', {upc: upc})
                    .done(function(data) {
                        const res = JSON.parse(data);
                        if (res != "0") {
                            $(".retail").val('$'+res['retail_value']);
                            $(".original-retail").val('$'+res['retail_value']);
                            if (cat == 1) {
                                itemCost = parseFloat(res['retail_value']) / 3.5;
                            } else {
                                itemCost = parseFloat(res['retail_value']) / 5;
                            }
                            $(".client-cost").val('$'+itemCost.toFixed(2));
                            $(".desc").removeClass('desc');
                            $(".upc").removeClass('upc');
                            $(".condition").removeClass('condition');
                            $(".qty").removeClass('qty');
                            $(".retail").removeClass('retail');
                            $(".original-retail").removeClass('original-retail');
                            $(".client-cost").removeClass('client-cost');
                            $(".vendor-name").removeClass('vendor-name');
                    
                            
                            $.post('<?= base_url('save-log') ?>', {
                                upc: upc,
                                desc: title,
                                retail: res['retail_value'],
                                vendor: brand,
                                category: cat,
                                box: $('.box-name').val(),
                            }, function(data) {
                                $(".upc-row").append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc"></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');                            
                                $(".upc").focus();
                            });
                        } else {
                            $(".retail").val('');
                            $(".original-retail").val('');
                            $(".client-cost").val('');
                            $(".desc").removeClass('desc');
                            $(".upc").removeClass('upc');
                            $(".condition").removeClass('condition');
                            $(".qty").removeClass('qty');
                            $(".retail").removeClass('retail');
                            $(".original-retail").removeClass('original-retail');
                            $(".client-cost").removeClass('client-cost');
                            $(".vendor-name").removeClass('vendor-name');
                    
                            $.post('<?= base_url('save-log') ?>', {
                                upc: upc,
                                desc: title,
                                retail: 0,
                                vendor: brand,
                                category: cat,
                                box: $('.box-name').val(),
                            }, function(data) {
                                $(".upc-row").append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc"></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');                        
                                $(".upc").focus();
                            });
                        }
                        
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

                $(".retail").val('');
                $(".original-retail").val('');
                $(".client-cost").val('');
                $(".desc").removeClass('desc');
                $(".upc").removeClass('upc');
                $(".condition").removeClass('condition');
                $(".qty").removeClass('qty');
                $(".retail").removeClass('retail');
                $(".original-retail").removeClass('original-retail');
                $(".client-cost").removeClass('client-cost');
                $(".vendor-name").removeClass('vendor-name');
        
                
                $.post('<?= base_url('save-log') ?>', {
                    upc: upc,
                    desc: "ITEM NOT FOUND",
                    category: cat,
                    box: $('.box-name').val(),
                }, function(data) {
                    $(".upc-row").append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc"></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');                
                    $(".upc").focus();
                });
                
                
            }   
            
            
        });        
    });          
    
    $('.box-button').click(function() {
        if (cat != '') {
            $('.upc-row').html("");
            $('#box_name').html("");
            $('#modal_box').modal('show');
            $('.box-name').val("");
            $('.box-dimension').val("");
            $('.category').val("");
            $('.upc-row').append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');                  
            $('.box-button').html("<i class='icon-dropbox mr-2'></i>New Box");
        } else {
            
            $('.upc-row').html("");
            $('#box_name').html("");
            $('#modal_box').modal('show');
            $('.box-name').val("");
            $('.box-dimension').val("");
            $('.category').val("");
            $('.upc-row').append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td><td><input type="text" name="desc[]" class="form-control custom-field desc" readonly></td><td><input type="text" name="condition[]" class="form-control custom-field condition" readonly></td><td><input type="text" name="qty[]" class="form-control custom-field qty" readonly></td><td><input type="text" name="retail[]" class="form-control custom-field retail" readonly></td><td><input type="text" name="original-retail[]" class="form-control custom-field original-retail" readonly></td><td><input type="text" name="client-cost[]" class="form-control custom-field client-cost" readonly></td><td><input type="text" name="vendor-name[]" class="form-control custom-field vendor-name" readonly></td></tr>');
            
        }
        
    });
</script>
<?= $this->endSection() ?>