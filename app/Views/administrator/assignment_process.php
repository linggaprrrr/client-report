<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin:0 20px 20px;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            
        </div>
        <form class="wizard-form steps-async wizard clearfix" action="<?= base_url() ?>/save-assignment" method="post" data-fouc="" role="application" id="steps-uid-1">
            <?= csrf_field() ?>
            <div class="steps clearfix">
                <ul role="tablist">
                    <li role="tab" class="disabled" aria-disabled="false" ><a  class=""><span class="current-info audible">current step: </span><span class="number">1</span> Box Assignment</a></li>
                    <li role="tab" class="first current" aria-disabled="true" aria-selected="true"><a  class="disabled"><span class="number">2</span> Assignment Process</a></li>
                    <li role="tab" class="disabled" aria-disabled="false"><a class="disabled"><span class="number">3</span> Completed Assignment</a></li>
                </ul>
            </div>
            <div class="reset-button">
                <a href="<?= base_url('/reset-assignment') ?>"><span class="badge badge-danger"><i class="icon-reset mr-2"></i>RESET</span></a>
            </div>
            <table class="table datatable-basic" id="myTable" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center" style="width: 10%">Box Name</th>                        
                        <th class="text-center" style="width: 10%">Box Value</th>
                        <th class="text-center" style="width: 10%">Order Date</th>
                        <th class="text-center" style="width: 10%">Client</th>
                        <th class="text-center" style="width: 10%">AMZ Store</th>
                        <th class="text-center" style="width: 20%">FBA Number</th>
                        <th class="text-center" style="width: 20%">Shipment Number</th>
                        <th class="text-center" style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($getAllAssignReportProcess->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($getAllAssignReportProcess->getResultArray() as $row) : ?>
                            <?php if (!empty($row['userid'])) : ?> 
                                <?php if ($row['status'] == 'waiting'): ?>
                                <tr class="table-active">
                                <?php elseif ($row['status'] == 'rejected') : ?>
                                    <tr class="table-warning">
                                <?php else: ?>       
                                    <tr class="table-success">                             
                                <?php endif ?>                                 
                                    <td><?= $no++ ?></td>
                                    <td class="">
                                        <a href="#" class="h5 box_name" data-box="<?= $row['box_name'] ?>">
                                            <b><?= $row['box_name'] ?></b>
                                        </a>
                                    </td>                                    
                                    <td class="value_box_<?= $no ?>"><b>$ <?= number_format($row['box_value'], 2) ?></b></td>
                                    <td>
                                        <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>       
                                        <b><?= $newDate ?></b>                                                                                                              
                                    </td>
                                    <td>
                                        <b><?= $row['fullname'] ?></b>                                        
                                    </td>
                                    <td class="company_box_<?= $no ?>">
                                        <b><?= $row['company'] ?> </b>    
                                    </td>
                                    <td class="fba_number_box_<?= $no ?>">                                    
                                        <input class="form-control" name="fba_number[]" placeholder="FBA Number"> 
                                    </td>
                                    <td class="shipment_box_<?= $no ?>">
                                        <input class="form-control" name="shipment_number[]" placeholder="Shipment Number"> 
                                    </td>
                                    <td>
                                        <select class="form-control" style="width: 130px;">
                                            <option value="0">...</option>
                                            <option value="approved">APPROVED</option>
                                            <option value="reject">REJECTED</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php else : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="name_box_<?= $no ?>"><?= $row['box_name'] ?></td>
                                <td><span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span></td>
                                <td class="value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                                <td>   
                                    <input type="text" class="daterange-single order_box_<?= $no ?>"  name="date[]" value="<?= date("m/d/Y") ?>" style="width: 90px; text-align:center">                                       
                                </td>
                                <td>
                                    <select class="form-control clientSelect select-search" name="client[]" id="box_<?= $no ?> " data-fouc>
                                        <option value="0">...</option>  
                                        <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                            <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?></b></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td class="company_box_<?= $no ?>">
                                </td>
                                <td class="date_box_<?= $no ?>">                            
                                    <select class="select_date_box_<?= $no ?>">                                    
                                    </select>
                                </td>
                                <td class="currentCost_box_<?= $no ?>">                            
                                </td>
                                <td class="total_box_<?= $no ?>"></td>
                            </tr>
                            <?php endif ?>  
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
                                            
            </table>
            <div class="modal fade modal_scrollable_box" tabindex="-1">
                <div class="modal-dialog modal-full modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header pb-3">
                            <h5><b>BOX #<span class="modal-title">#title</span></b></h5>
                            <div class="reset-button-item">
                                <a href="#" id="reset"><span class="badge badge-danger"><i class="icon-reset mr-1"></i>RESET</span></a>
                            </div>
                        </div>

                        <div class="modal-body py-0">
                            <form>
                                <div class="table-responsive" id="item-table">
                                    
                                    <table class="table" style="font-weight:bold">
                                        <thead>
                                            <tr class="bg-secondary text-white">
                                                <th style="width: 5%;">#</th>
                                                <th>SKU</th>
                                                <th>Item Description</th>
                                                <th>Condition</th>
                                                <th>Qty</th>
                                                <th>Retail</th>
                                                <th>Original</th>
                                                <th>Vendor</th>
                                                <th style="width: 5%;"><i class="icon-arrow-down12"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody id="item-tbody">                                            
                                            
                                        </tbody>
                                    </table>                                    
                                </div>
                                <div class="table-responsive" id="item-table-removed">                                    
                                    <table class="table" style="font-weight:bold">
                                        <thead>
                                            <tr class="bg-danger text-white">
                                                <th style="width: 5%;">#</th>
                                                <th>SKU</th>
                                                <th>Item Description</th>
                                                <th>Condition</th>
                                                <th>Qty</th>
                                                <th>Retail</th>
                                                <th>Original</th>
                                                <th>Vendor</th>
                                                <th style="width: 5%;"><i class="icon-arrow-down12"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody id="item-tbody">                                            
                                            
                                        </tbody>
                                    </table>                                    
                                </div>
                                
                                <div class="form-group">
                                    <label for=""><b>Note:</b></label>
                                    <div class="input-group">                                        
                                        <textarea name="note" class="form-control" id="note" rows="3" placeholder="-"></textarea>
                                    </div>
                                </div>
                            </form>
                            
                        </div>

                        <div class="modal-footer pt-3">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-secondary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 2</b></button>  
                </div>                            
                <div class="text-left">
                    <a href="<?= base_url('/admin/assignment-report') ?>" class="btn btn-light "><i class="icon-arrow-left8 mr-2"></i>Previous</a>                            
                    <a href="#" class="btn btn-primary disabled">Next Phase<i class="icon-arrow-right8 ml-2"></i></a>
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
<script src="/assets/js/demo_pages/picker_date.js"></script>
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
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
        $(".clientSelect").select2({
            width: '150px'
        });

        $.fn.inputFilter = function(inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        };
        $(".floatTextBox").inputFilter(function(value) {
            return /^-?\d*[.]?\d*$/.test(value);
        });


        $('.box_name').on('click', function() {
            var boxName =  $(this).attr('data-box');
            $('#item-table tbody').html("");
            $.get('/get-box-summary', {box_name: boxName}, function(data) {
                var item = JSON.parse(data);
                
                if (item.length > 0) {             
                    var no = 1;   
                    for (var i = 0; i < item.length; i++) {                        
                        if (i % 2 == 0) {
                            $('#item-table tbody').append('<tr> <td>'+ no++ +'</td> <td>'+item[i]['sku']+'</td> <td>'+item[i]['item_description']+'</td> <td>'+item[i]['cond']+'</td> <td>'+item[i]['qty']+'</td> <td>$ '+numberWithCommas(item[i]['retail'])+'</td> <td>$ '+numberWithCommas(item[i]['original'])+'</td> <td>'+item[i]['vendor']+'</td> <td><a href="#" class="remove"><i class="icon-minus-circle2"></a></td> </tr>');
                        } else {
                            $('#item-table tbody').append('<tr class="table-active"> <td>'+ no++ +'</td> <td>'+item[i]['sku']+'</td> <td>'+item[i]['item_description']+'</td> <td>'+item[i]['cond']+'</td> <td>'+item[i]['qty']+'</td> <td>$ '+numberWithCommas(item[i]['retail'])+'</td> <td>$ '+numberWithCommas(item[i]['original'])+'</td> <td>'+item[i]['vendor']+'</td> <td><a href="#" class="remove"><i class="icon-minus-circle2"></a></td> </tr>');
                        }
                    }
                }
                $('.modal-title').html("<b>"+ boxName + "</b>");                    
                $('.modal_scrollable_box').modal('show');

                $('#reset').on('click', function() {
                    $('#item-table tbody').html("");
                    if (item.length > 0) {             
                    var no = 1;   
                    for (var i = 0; i < item.length; i++) {                        
                        if (i % 2 == 0) {
                            $('#item-table tbody').append('<tr> <td>'+ no++ +'</td> <td>'+item[i]['sku']+'</td> <td>'+item[i]['item_description']+'</td> <td>'+item[i]['cond']+'</td> <td>'+item[i]['qty']+'</td> <td>$ '+numberWithCommas(item[i]['retail'])+'</td> <td>$ '+numberWithCommas(item[i]['original'])+'</td> <td>'+item[i]['vendor']+'</td> <td><a href="#" class="remove"><i class="icon-minus-circle2"></a></td> </tr>');
                        } else {
                            $('#item-table tbody').append('<tr class="table-active"> <td>'+ no++ +'</td> <td>'+item[i]['sku']+'</td> <td>'+item[i]['item_description']+'</td> <td>'+item[i]['cond']+'</td> <td>'+item[i]['qty']+'</td> <td>$ '+numberWithCommas(item[i]['retail'])+'</td> <td>$ '+numberWithCommas(item[i]['original'])+'</td> <td>'+item[i]['vendor']+'</td> <td><a href="#" class="remove"><i class="icon-minus-circle2"></a></td> </tr>');
                        }
                    }
                }
                });
            });
        });
    });

    $(document).on('click','.remove',function(){
        $(this).parents('tr').remove();
    });


    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the report.',
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