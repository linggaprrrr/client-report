<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-file-upload mr-2"></i>Upload Report</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Upload Client Report</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('upload-pl-report') ?>" method="POST" enctype="multipart/form-data">
                                <?php csrf_field() ?>
                                <div class="modal-body">

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
                                        <label for="">P&L Google Spreadsheet Link:</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-link"></i></span>
                                            </span>
                                            <input type="text" class="form-control" name="link" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Chart File:</label>
                                        <label class="custom-file">
                                            <input type="file" name="chart" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <span class="custom-file-label" id="file-upload-filename">Choose file</span>
                                        </label>
                                        <span class="form-text text-muted">Accepted formats: xls. Max file size 100Mb</span>
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

        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Client Name</th>
                    <th>Company Name</th>
                    <th>File Uploaded</th>
                    <th>Date Uploaded</th>
                    <th>Google Sheet</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllFiles->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($getAllFiles->getResultArray() as $row) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['company'] ?></td>
                            <td><a href="<?= base_url('files/' . $row['file']) ?>" download="<?= $row['file'] ?>"><?= $row['file'] ?></a></td>
                            <td class="text-center"><?= $row['date'] ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['link'])) : ?>
                                    <a href="<?= $row['link'] ?>" target="_blank"><i class="icon-link"></i></a>
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown position-static">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a href="#" class="dropdown-item editpl" data-toggle="modal" data-id="<?= $row['log_id'] ?>" data-target="#modal_form_reupload"><i class="icon-undo"></i> Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <form action="<?= base_url("/pl-report/" . $row['client_id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Delete
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
        <div id="modal_form_reupload" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">Re-upload Client Report</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="<?= base_url('reupload-pl-report') ?>" method="POST" enctype="multipart/form-data">
                        <?php csrf_field() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Client Name:</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-user"></i></span>
                                    </span>
                                    <input type="text" class="form-control" name="fullname" id="client" value="" readonly>
                                    <input type="hidden" class="form-control" name="client" id="client_id" value="" readonly>
                                    <input type="hidden" class="form-control" name="log_id" id="log_id" value="" readonly>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">P&L Google Spreadsheet Link:</label>
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-link"></i></span>
                                    </span>
                                    <input type="text" class="form-control" name="link" id="link" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Chart File:</label>
                                <label class="custom-file">
                                    <input type="file" name="chart" class="custom-file-input" id="file-upload2" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    <span class="custom-file-label" id="file-upload-filename2">Choose file</span>
                                    <small id="chart"></small>
                                </label>
                                <span class="form-text text-muted">Accepted formats: xls. Max file size 100Mb</span>
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

<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
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
    $('#noty_deleted').on('click', function() {
        new Noty({
            text: 'You successfully delete the report.',
            type: 'alert'
        }).show();
    });

    var input = document.getElementById('file-upload');
    var infoArea = document.getElementById('file-upload-filename');
    var input2 = document.getElementById('file-upload2');
    var infoArea2 = document.getElementById('file-upload-filename2');

    input.addEventListener('change', showFileName);
    input2.addEventListener('change', showFileName2);

    function showFileName(event) {
        // the change event gives us the input it occurred in 
        var input = event.srcElement;
        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input.files[0].name;
        // use fileName however fits your app best, i.e. add it into a div
        infoArea.textContent = '' + fileName;
    }


    function showFileName2(event) {
        // the change event gives us the input it occurred in 
        var input = event.srcElement;
        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input2.files[0].name;
        // use fileName however fits your app best, i.e. add it into a div
        infoArea2.textContent = '' + fileName;
    }

    $('.editpl').click(function() {
        var id = $(this).data('id');
        $('#client').val("");
        $('#client_id').val("");
        $('#link').val("");
        $('#chart').html("");
        $('#log_id').val("");
        $.get('/get-plclient', {
            log_id: id
        }, function(data) {
            var pl = JSON.parse(data);
            $('#client').val(pl['fullname']);
            $('#client_id').val(pl['user_id']);
            $('#link').val(pl['link']);
            $('#chart').html(pl['file']);
            $('#log_id').val(pl['log_id']);
        });
    });
</script>

<?= $this->endSection() ?>