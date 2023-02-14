<?= $this->extend('scanner/layout/template') ?>

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
    p {
        margin: 0;
    }
</style>
<div class="content">
    <div class="card">
    <form class="wizard-form steps-async wizard clearfix" id="log-form" >
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <a href="" data-toggle="modal" class="btn btn-success export" ><i class="icon-file-excel"></i> Export to excel</a>
        </div>
        <div class="card-body">
                <button type="submit" disabled="" style="display: none" aria-hidden="true"></button>
                <div class="table-responsive">                
                  
                    <table class="table table-striped table-bordered display nowrap" width="100%" style="font-size: 10px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20%">UPC/SKU</th>
                                <th class="text-center">DESCRIPTION</th>
                               
                            </tr>
                        </thead>
                        <tbody class="upc-row">
                            <tr class="item-list">
                                <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td>
                                <td>
                                    <div class="row">                                        
                                        <div class="col-lg-10">
                                            <h6 class="title_row_1"></h6>
                                            <p class="brand_row_1"></p>
                                            <p class="asin_row_1"></p>
                                            <p class="price_row_1"></p>
                                            <br>
                                            <p class="cat_row_1"></p>
                                            <input type="hidden" class="input_title_1" name="title[]">
                                            <input type="hidden" class="input_brand_1" name="brand[]">
                                            <input type="hidden" class="input_asin_1" name="asin[]">
                                            
                                            <input type="hidden" class="input_cat_1" name="cat[]">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="float-right img_row_1">
                                            </div>                                           
                                            <input type="hidden" class="input_img_1" name="img[]">
                                        </div>
                                    </div>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>    
            </form>   
        </div>

    </div>

    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>    
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>

<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>

<script>
    var row = 1;
    var audio = new Audio('/assets/beep.mp3');
    $(document).on('change', '.upc', function(){
        const upc = $(this).val();        
        var price = "";
        $('.upc').attr('readonly', true);
        if (upc != "") {            
            $.get('https://api.asinscope.com/products/lookup?key=ktr8kyb7ootltxjb6um9ak77k&upc='+upc+'&domain=com', function(data) {
                console.log(data['items'].length);
                if (data['items'].length > 0) {
                    $('.title_row_'+row+'').html('<strong>'+data['items']['0']['title']+'</strong>');
                    $('.brand_row_'+row+'').html("<strong>"+data['items']['0']['brand']+" </strong>");
                    $('.asin_row_'+row+'').html("ASIN "+data['items']['0']['asin']+"");
                    $('.img_row_'+row+'').html('<img src="'+data['items']['0']['mediumImage']+'" style="width:80px" > ');                    
                    $('.cat_row_'+row+'').html("Category: "+data['items']['0']['category']+"");
                    if (data['items']['0']['lowestFormattedPrice'] != null) {
                        $('.price_row_'+row+'').html('<input class="font-weight-bold text-danger" style="outline: 0;border-width: 0 0 1px; border-color: red" name="price[]" value="'+data['items']['0']['lowestFormattedPrice']+'">');
                        price = data['items']['0']['lowestFormattedPrice'];
                    } else {
                        $('.price_row_'+row+'').html('<input class="font-weight-bold text-danger" style="outline: 0;border-width: 0 0 1px; border-color: red" name="price[]" value="-">');
                        price = '-';
                    }
                    

                    $('.input_title_'+row+'').val(data['items']['0']['title']);
                    $('.input_brand_'+row+'').val(data['items']['0']['brand']);
                    $('.input_asin_'+row+'').val(data['items']['0']['asin']);
                    $('.input_price_'+row+'').val(data['items']['0']['lowestFormattedPrice']);
                    $('.input_cat_'+row+'').val(data['items']['0']['category']);
                    $('.input_img_'+row+'').val(data['items']['0']['mediumImage']);        
                    $.post("/save-to-upc-db", {
                        upc: upc,
                        asin: data['items']['0']['asin'],
                        title: data['items']['0']['title'],
                        brand: data['items']['0']['brand'],
                        price: price,
                        img: data['items']['0']['mediumImage'],
                    }, function(data) {
                        new Noty({
                            text: 'UPC successfully added to database.',
                            type: 'alert'
                        }).show();
                    }); 
                                                 
                } else {
                    $('.title_row_'+row+'').html('<strong>Not Accepted by Amazon</strong>');                    
                    $('.input_title_'+row+'').val('Not Accepted by Amazon');
                    $('.input_brand_'+row+'').val('-');
                    $('.input_asin_'+row+'').val('-');
                    $('.input_price_'+row+'').val('-');
                    $('.input_cat_'+row+'').val('-');
                    $('.input_img_'+row+'').val('-'); 
                }
                $(".upc").removeClass('upc');
                $('.upc-row').append('<tr class="item-list"> <td><input type="text" name="upc[]" class="form-control custom-field upc" ></td><td> <div class="row"> <div class="col-lg-10"> <h6 class="title_row_'+row+'"></h6> <p class="brand_row_'+row+'"></p><p class="asin_row_'+row+'"></p><p class="price_row_'+row+'"></p><br><p class="cat_row_'+row+'"></p><input type="hidden" class="input_title_'+row+'" name="title[]"> <input type="hidden" class="input_brand_'+row+'" name="brand[]"> <input type="hidden" class="input_asin_'+row+'" name="asin[]"> <input type="hidden" class="input_cat_'+row+'" name="cat[]"> </div><div class="col-lg-2"> <div class="float-right img_row_'+row+'"> </div><input type="hidden" class="input_img_'+row+'" name="img[]"> </div></div></td></tr>');
                audio.play();
                $(".upc").focus();
            });
            
        }

        $(document).on('click', '.export', function(event){
            event.preventDefault();
            
            $.post( '/export-amazon-upc', $('form#log-form').serialize(), function(data) {
                const res = JSON.parse(data);
                window.location.href = '/files/'+res['file']+'';
            });
        });

        $('#noty_created').on('click', function() {
        new Noty({
            text: 'UPC successfully added to database.',
            type: 'alert'
        }).show();
    });

    });
</script>
<?= $this->endSection() ?>
