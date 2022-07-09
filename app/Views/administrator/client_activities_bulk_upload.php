<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .btn-upload {
        padding: 10px 20px;
        margin-left: 10px;
    }

    .upload-input-group {
        margin-bottom: 10px;
    }

    .input-group>.custom-select:not(:last-child),
    .input-group>.form-control:not(:last-child) {
        height: 45px;
    }
</style>
<div class="content">
    <form method="POST" action="<?= base_url('/assign-report-bulk') ?>" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">

                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-table2"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0"><?= count($files) ?></h5>
                        <span class="text-muted">Total Uploaded</span>

                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-secondary"><i class="icon-paperplane  mr-2"></i>Submit</button>
                    <button type="button" class="btn btn-danger cancel"><i class="icon-cross2 mr-2"></i>Cancel</button>

                </div>
            </div>

            <table class="table datatable-basic" id="client_act" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%;">Client Name</th>
                        <th style="width: 25%;">Link</th>
                        <th class="text-center" style="width: 10%;">Investment Date</th>
                        <th>File</th>
                        <th class="text-center" style="width: 5%;"><i class="icon-arrow-down12"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($files) > 0) : ?>
                        <?php $no = 1; ?>
                        <?php foreach ($files as $row) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>

                                <td>
                                    <select name="client[]" class="form-control" style="font-size: 12px; font-weight:700">
                                        <?php foreach($getAllClient->getResultArray() as $client) : ?>\
                                            <option value="<?= $client['id'] ?>"><?= $client['fullname'] . " - ".$client['company'] ."</b>"  ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td><input type="text" name="link[]" class="form-control" style="font-size: 12px" placeholder="https://docs.google.com/spreadsheets"></td>
                                <td><input type="text" class="form-control daterange-single text-center" name="date[]" value="<?= date("m/d/Y") ?>" readonly></td>
                                <td><input type="text" name="file[]" class="form-control" value="<?= $row ?>" style="font-size: 12px; font-weight:700" readonly> </td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                        <a href="#" class="delete"><i class="icon-cross2 text-danger"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </form>
    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
    <button type="button" id="noty_link" style="display: none;"></button>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('link')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>
    });



    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the manifest.',
            type: 'alert'
        }).show();
    });
    $('#noty_link').on('click', function() {
        new Noty({
            text: 'You successfully update the link.',
            type: 'alert'
        }).show();
    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the manifest.',
            type: 'alert'
        }).show();
    });

    $('.delete').click(function() {
        $($(this).closest("tr")).remove();
    });

    $("#client_act").on("click", '.link', function() {
        swal("Google Spreadsheet Link:", {
                content: "input",
            })
            .then((value) => {
                if (value.trim() === "") {
                    swal("Link cant empty");
                } else {
                    var id = $(this).data("id");
                    $.post("/update-link-spreadhsheet", {
                        file_id: id,
                        link: value
                    }, function(data) {
                        location.reload();
                    });

                }
            });
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
</script>

<?= $this->endSection() ?>