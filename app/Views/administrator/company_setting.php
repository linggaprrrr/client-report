<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">

        <div class="card-body">
            <form action="<?= base_url('/company-update') ?>" method="POST" enctype="multipart/form-data">
                <?php csrf_field() ?>
                <div class="form-group row" style="align-items: center;">
                    <label class="col-form-label col-lg-2">Logo</label>
                    <div class="col-lg-10" style="text-align: center;">
                        <div class="card-img-actions d-inline-block">
                            <?php if (!empty($company['logo'])) : ?>
                                <img class="img-fluid rounded-circle" id="output" src="/assets/images/<?= $company['logo'] ?>" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
                            <?php else : ?>
                                <img class="img-fluid rounded-circle" id="output" src="/assets/images/placeholders/user.png" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
                            <?php endif ?>
                            <div class="card-img-actions-overlay card-img rounded-circle">
                                <a href="#" class="btn btn-white btn-icon btn-sm rounded-pill" id="uploadImg">
                                    <i class="icon-pencil"></i>
                                </a>

                                <script>
                                    $("#uploadImg").click(function() {
                                        $("#photo").click();
                                    })

                                    var loadFile = function(event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                            URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                </script>
                            </div>
                            <div style="display: none;">
                                <input type="file" name="logo" accept="image/*" id="photo" onchange="loadFile(event)">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Company Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="company" autocomplete="false" value="<?= $company['name'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Address</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="address" col=4><?= $company['address'] ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Email</label> 
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="email" autocomplete="false" value="<?= $company['email'] ?>">
                    </div> 
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Phone</label> 
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="phone" autocomplete="false" value="<?= $company['phone'] ?>">
                    </div> 
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-secondary" id="btnSave">Save <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
        </div>
        <button type="button" id="noty_created" style="display: none;"></button>
        <button type="button" id="noty_deleted" style="display: none;"></button>
    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('failed')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Password Matching').css('color', 'green');

            } else
                $('#message').html('Password not matching!').css('color', 'red');

        });
    });

    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully update the company.',
            type: 'alert'
        }).show();
    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You password is incorrect, update failed.',
            type: 'alert'
        }).show();
    });
</script>


<?= $this->endSection() ?>