<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">

        <div class="card-body">
            <form action="<?= base_url('/update-setting') ?>" method="POST" enctype="multipart/form-data">
                <?php csrf_field() ?>
                <div class="form-group row" style="align-items: center;">
                    <label class="col-form-label col-lg-2">Profile Picture</label>
                    <div class="col-lg-10" style="text-align: center;">
                        <div class="card-img-actions d-inline-block">
                            <?php if (!empty($profile['photo'])) : ?>
                                <img class="img-fluid rounded-circle" id="output" src="/img/<?= $profile['photo'] ?>" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
                            <?php else : ?>
                                <img class="img-fluid rounded-circle" id="output" src="/assets/images/placeholders/placeholder.jpg" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
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
                                <input type="file" name="photo" accept="image/*" id="photo" onchange="loadFile(event)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Username</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="id" readonly value="<?= $profile['id'] ?>">
                        <input type="text" class="form-control" disabled value="<?= $profile['username'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Fullname</label>
                    <div class="col-lg-10">
                        <input type="text" name="fullname" class="form-control" value="<?= $profile['fullname'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Company Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="company" autocomplete="false" value="<?= $profile['company'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Address</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="address" col=4><?= $profile['address'] ?></textarea>
                    </div>
                </div>
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Change Password</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Old Password</label>
                        <div class="col-lg-10">
                            <input type="password" name="old_password" class="form-control" autocomplete="false" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">New Password</label>
                        <div class="col-lg-10">
                            <input type="password" name="new_password" class="form-control" autocomplete="false" id="password" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Confirm New Password</label>
                        <div class="col-lg-10">
                            <input type="password" name="confirm_password" class="form-control" autocomplete="false" id="confirm_password" value="">
                        </div>
                    </div>
                    <div class="message">
                        <span id='message'></span>
                    </div>

                </fieldset>
                <div class="text-right">
                    <button type="submit" class="btn btn-secondary" id="btnSave">Save <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-lock2 mr-2"></i>Reset Password</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Reset Password</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('/reset-password') ?>" method="POST" enctype="multipart/form-data" id="form">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-lock2"></i></span>
                                            </span>
                                            <input type="hidden" name="id" readonly value="<?= $profile['id'] ?>">
                                            <input type="password" name="new_password" class="form-control" autocomplete="false" id="password" value="" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary disabled" id="btnAdd">Save <i class="icon-paperplane ml-2"></i></button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
            text: 'You successfully update your pofile.',
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