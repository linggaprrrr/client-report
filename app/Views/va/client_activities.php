<?= $this->extend('va/layout/template') ?>

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
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">

            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-table2"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0"><?= $totalClientUploaded->total ?></h5>
                    <span class="text-muted">Total Client Uploaded</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-file-excel"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0"><?= $totalReport->total ?></h5>
                    <span class="text-muted">Total Report</span>

                </div>
            </div>
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-file-upload mr-2"></i>Upload Report</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Upload Client Report</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('upload-report') ?>" method="POST" enctype="multipart/form-data">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Investment Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                            </span>
                                            <input type="text" class="form-control daterange-single" name="date" value="12/21/2021">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Client Name:</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-user"></i></span>
                                            </span>
                                            <select class="form-control" name="client">
                                                <?php if ($getAllClient->getNumRows() > 0) : ?>
                                                    <?php foreach ($getAllClient->getResultArray() as $row) : ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['fullname'] . " (" . $row['company'] . ")" ?></option>
                                                    <?php endforeach ?>
                                                <?php else : ?>
                                                    <option value="-">-</option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>File:</label>
                                        <!-- <div class="controls">
                                        <div class="entry input-group upload-input-group">
                                            <input class="form-control" name="file[]" type="file">
                                            <input class="form-control ml-2" name="link[]" type="text" placeholder="www.google.com">
                                            <button class="btn btn-upload btn-success btn-add" type="button">
                                                <i class="icon-plus3"></i>
                                            </button>
                                        </div> -->

                                        <!-- </div> -->

                                        <label class="custom-file">

                                            <input type="file" name="file" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <span class="custom-file-label" id="file-upload-filename">Choose file</span>
                                        </label>
                                        <span class="form-text text-muted">Accepted formats: xls/xlsx. Max file size 10Mb</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Google Spreadsheet Link:</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-link"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="link" required>
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
        </div>

        <table class="table datatable-basic" id="client_act" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Investment Date</th>
                    <th>Client Name</th>
                    <th>Company Name</th>
                    <th>File Uploaded</th>
                    <th>Date Uploaded</th>
                    <th class="text-center" style="width: 12%">Google Sheet</th>
                    <th class="text-center" style="width: 10%">Download</th>
                    <th class="text-center" style="width: 5px;"><i class="icon-arrow-down12"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllFiles->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($getAllFiles->getResultArray() as $row) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center font-weight-bold">
                                <?php $newDate = date("M-d-Y", strtotime($row['invest_date'])); ?>
                                <?= strtoupper($newDate) ?>
                            </td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['company'] ?></td>
                            <td><?= $row['file'] ?></td>
                            <td class="text-center"><?= $row['date'] ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['link'])) : ?>
                                    <a href="<?= $row['link'] ?>" target="_blank"><i class="icon-link"></i></a>
                                <?php else : ?>
                                    <a href="#" class="link" data-id="<?php echo $row['log_id'] ?>" style="color: red;"><i class="icon-unlink"></i></a>
                                <?php endif ?>
                            </td>
                            <td class="text-center"><a href="<?= base_url('files/' . $row['file']) ?>" download="<?= $row['file'] ?>"><i class="icon-download4"></i></a></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <form action="<?= base_url("/report/" . $row['investment_id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Delete</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
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
            text: 'You successfully upload the report.',
            type: 'success'
        }).show();
    });
    $('#noty_link').on('click', function() {
        new Noty({
            text: 'You successfully update the link.',
            type: 'success'
        }).show();
    });
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the report.',
            type: 'alert'
        }).show();
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

    // $(function () {
    //         $(document).on('click', '.btn-add', function (e) {
    //             e.preventDefault();

    //             var controlForm = $('.controls:first'),
    //                 currentEntry = $(this).parents('.entry:first'),
    //                 newEntry = $(currentEntry.clone()).appendTo(controlForm);

    //             newEntry.find('input').val('');
    //             controlForm.find('.entry:not(:last) .btn-add')
    //                 .removeClass('btn-add').addClass('btn-remove')
    //                 .removeClass('btn-success').addClass('btn-danger')
    //                 .html('<span class="icon-cross3"></span>');
    //         }).on('click', '.btn-remove', function (e) {
    //             $(this).parents('.entry:first').remove();

    //             e.preventDefault();
    //             return false;
    //         });
    //     });
</script>

<?= $this->endSection() ?>