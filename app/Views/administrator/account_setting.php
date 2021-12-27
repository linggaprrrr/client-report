<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert bg-success text-white alert-styled-left alert-dismissible m-2">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span class="font-weight-semibold">Well done!</span> Your Profile Successfully Updated <a href="#" class="alert-link"></a>
            </div>
        <?php endif ?>
        <?php if (session()->getFlashdata('failed')) : ?>
            <div class="alert bg-danger text-white alert-styled-left alert-dismissible m-2">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span class="font-weight-semibold">Failed!</span> Your Password Incorrect <a href="#" class="alert-link"></a>
            </div>
        <?php endif ?>
        <div class="card-body">
            <form action="<?= base_url('/update-setting') ?>" method="POST" enctype="multipart/form-data">
                <?php csrf_field() ?>
                <div class="form-group row" style="align-items: center;">
                    <label class="col-form-label col-lg-2">Profile Picture</label>
                    <div class="col-lg-10" style="text-align: center;">
                        <div class="card-img-actions d-inline-block">
                            <?php if (!empty($user['photo'])) : ?>
                                <img class="img-fluid rounded-circle" id="output" src="<?= base_url() ?>/img/<?= $user['photo'] ?>" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
                            <?php else : ?>
                                <img class="img-fluid rounded-circle" id="output" src="<?= base_url() ?>/assets/images/placeholders/placeholder.jpg" alt="Profile Picture" style="width:250px; height:250px;object-fit: contain;">
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
                        <input type="hidden" name="id" readonly value="<?= $user['id'] ?>">
                        <input type="text" class="form-control" disabled value="<?= $user['username'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Fullname</label>
                    <div class="col-lg-10">
                        <input type="text" name="fullname" class="form-control" value="<?= $user['fullname'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Company Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="company" autocomplete="false" value="<?= $user['company'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Address</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="address" col=4><?= $user['address'] ?></textarea>
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
        </div>

    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Password Matching').css('color', 'green');

            } else
                $('#message').html('Password not matching!').css('color', 'red');

        });
    });
</script>


<?= $this->endSection() ?>