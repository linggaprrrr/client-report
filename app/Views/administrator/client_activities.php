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
                                <h5 class="modal-title">Upload Client Manifest</h5>
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
                                            <input type="text" class="form-control daterange-single" name="date" value="<?= date("m/d/Y") ?>">
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_form_upload_bulk"><i class="icon-file-upload mr-2"></i>Bulk Upload</button>
                <div id="modal_form_upload_bulk" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Bulk Upload Client Manifest</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('upload-report-bulk') ?>" method="POST" enctype="multipart/form-data">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    
                                    <div class="form-group">
                                        <label>File:</label>
                                        <label class="custom-file">

                                            <input type="file" name="file[]" class="custom-file-input" id="file-upload2" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" multiple required>
                                            <span class="custom-file-label" id="file-upload-filename2">Choose file</span>
                                        </label>
                                        <span class="form-text text-muted">Accepted formats: xls/xlsx. Max file size 10Mb</span>
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
                    <th>Amount</th>
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
                            <td><b>$<?= number_format($row['amount'], 0) ?></b></td>
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
                                            <a href="#" class="dropdown-item preview" data-id="<?= $row['investment_id'] ?>" data-toggle="modal" data-target="#previewManifest"><i class="icon-pencil text-warning"></i> Preview</a>
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
    <div class="modal fade" id="previewManifest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientName"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table manifestTable" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 12%;">UPC</th>
                                <th class="text-center">ITEM DESCRIPTION</th>
                                <th class="text-center" style="width: 5%;">COND</th>
                                <th class="text-center" style="width: 5%;">QTY</th>
                                <th class="text-center" style="width: 5%;">RETAIL</th>
                                <th class="text-center" style="width: 5%;">TOTAL RETAIL</th>
                                <th class="text-center" style="width: 5%;">CLIENT COST</th>
                                <th class="text-center">VENDOR NAME</th>
                            </tr>
                        </thead>
                        <tbody id="manifestBody">
                           
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                
                </div>
            </div>
        </div>
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
        var t = $('.manifestTable').DataTable(); 

        $('.preview').click(function() {
            const id = $(this).data('id');        
            var client = "";
            $.get('/get-manifest', {id: id}, function(data) {
                const res = JSON.parse(data);
                t.clear().draw(); 
                $('#clientName').html("");
                for (var i = 0; i < res.length; i++) {
                    if (i == 0) {
                        var client = res[i]['fullname']+' - '+res[i]['company'];
                    }
                    t.row.add([
                        '<p class="text-center font-weight-bold">'+res[i]['sku']+'</p>',
                        res[i]['item_description'],
                        res[i]['cond'],
                        res[i]['qty'],
                        '$'+res[i]['retail_value'],
                        '$'+res[i]['original_value'],
                        '$'+parseFloat(res[i]['cost'], 2),
                        res[i]['vendor'],
                    ]).draw(false);
                }
                $('#clientName').html(client);
            });
        })

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
</script>

<?= $this->endSection() ?>