<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">


    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-ticket mr-2"></i>Create Promo Code</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Add Promocode</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('/add-promo') ?>" method="POST" enctype="multipart/form-data" id="form">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Promo Code</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-ticket"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="promocode" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Promo Description</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-paragraph-center3"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="promo-description" placeholder="-" value="" >
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label>Clothes <small>(Divider for shoes category)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-bag"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="clothes" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="" >                                            
                                        </div>
                                    </div>      
                                    <div class="form-group">
                                        <label>Shoes <small>(Divider for shoes category)</small></label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-bag"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="shoes" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="" >
                                            
                                        </div>
                                    </div>                              
                                </div>

                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary" id="btnAdd">Add <i class="icon-paperplane ml-2"></i></button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width:5%">No.</th>
                    <th class="text-center">Promo Code</th>
                    <th class="text-center">Promo Description</th>
                    <th class="text-center">Clothes</th>
                    <th class="text-center">Shoes</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center"><i class="icon-arrow-down12"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($promocode->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach($promocode->getResultObject() as $promo) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center font-weight-bold"><?= strtoupper($promo->promo) ?></td>
                            <td class=""><?= $promo->description ?></td>
                            <td class=""><?= $promo->clothes ?></td>
                            <td class=""><?= $promo->shoes ?></td>
                            <td class="text-center"><?= date('m/d/Y', strtotime($promo->date)) ?></td>
                            <td><a href="" data-toggle="modal" data-id="<?= $promo->id ?>" class="editpromo"><i class="icon-pencil5"></i></a></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
        <div id="modal_form_edit" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">Edit Promocode</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="update_promo">
                        <?php csrf_field() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Promo Code</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-ticket"></i></span>
                                    </span>
                                    <input type="hidden" name="id" class="id" value="">
                                    <input type="text" class="form-control promocode" name="promocode" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Promo Description</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-paragraph-center3"></i></span>
                                    </span>
                                    <input type="text" class="form-control desc" name="promo-description" placeholder="-" value="" >
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>Clothes <small>(Divider for shoes category)</small></label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-bag"></i></span>
                                    </span>
                                    <input type="text" class="form-control clothes" name="clothes" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="" >                                            
                                </div>
                            </div>      
                            <div class="form-group">
                                <label>Shoes <small>(Divider for shoes category)</small></label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-bag"></i></span>
                                    </span>
                                    <input type="text" class="form-control shoes" name="shoes" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="" >
                                    
                                </div>
                            </div>                              
                        </div>

                        <div class="modal-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-secondary" id="btnAdd">Add <i class="icon-paperplane ml-2"></i></button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
    <button type="button" id="noty_update" style="display: none;"></button>
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
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Password Matching').css('color', 'green');
                $('#btnAdd').removeClass('disabled');
            } else
                $('#message').html('Password not matching!').css('color', 'red');
        });

        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>

        $('.editpromo').click(function(e) {
            const id = $(this).data('id');            
            $.get('/get-promocode', {id: id}, function(data) {
                const res = JSON.parse(data);
                $('.id').val(res['id']);
                $('.promocode').val(res['promo']);
                $('.desc').val(res['description']);
                $('.clothes').val(res['clothes']);
                $('.shoes').val(res['shoes']);
                $('#modal_form_edit').modal('show');
            })
        });

        $('form#update_promo').on('submit', function (e) {
            e.preventDefault();
            $.post('/update-promocode', $('form#update_promo').serialize(), function(data) {
                setTimeout(location.reload.bind(location), 500);
                $('#modal_form_edit').modal('hide');
                $('#noty_update').click();
            });
        });
    });

    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully create the promo.',
            type: 'alert'
        }).show();
    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the report.',
            type: 'alert'
        }).show();
    });
    $('#noty_update').on('click', function() {
        new Noty({
            text: 'You successfully update the promo.',
            type: 'alert'
        }).show();
    });
    
</script>


<?= $this->endSection() ?>