<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .reset-button {
        text-align: right;
        margin-right: 20px;
    }
</style>
<div class="content">
    <div class="row">
        <div class="col-lg-3">
            <div class="card bg-secondary text-white">
                <div class="card-header d-flex pb-1">
                    <div>
                        <span class="card-title font-weight-semibold">Total Box On-Process</span>
                        <h2 class="font-weight-bold mb-0"><span class="total_box_onprocess">...</span><small class="text-danger font-size-base ml-2"></small></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-header d-flex pb-1">
                    <div>
                        <span class="card-title font-weight-semibold">Total Box Completed</span>
                        <h2 class="font-weight-bold mb-0"><span class="total_box_completed">...</span></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-danger text-white">
                <div class="card-header d-flex pb-1">
                    <div>
                        <span class="card-title font-weight-semibold">Total Box This Week</span>
                        <h2 class="font-weight-bold mb-0"><span class="total_box">...</span> <code class="ml-2">(<span class="total_unit">0</span> Units)</code> </h2>
                    </div>
                    <div class="dropdown ml-auto">
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-warning text-white">
                <div class="card-header d-flex pb-1">
                    <div>

                        <span class="card-title font-weight-semibold">Total Client Cost</span>
                        <h2 class="font-weight-bold mb-0 ">$ <span class="total_client_cost">...</span> <small class="text-danger font-size-base ml-2"></small></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">

        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div>
                <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_form_upload"><i class="icon-file-upload mr-2"></i>Upload Report</button>
                <div id="modal_form_upload" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Upload Assignment Report</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="<?= base_url('upload-assignment') ?>" method="POST" enctype="multipart/form-data">
                                <?php csrf_field() ?>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>File:</label>
                                        <label class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                            <span class="custom-file-label" id="file-upload-filename">Choose file</span>
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
            <div>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_assignbrand"><i class="icon-bold2 mr-2"></i>Assign Brand</button>
                <div id="modal_assignbrand" class="modal fade">
                    <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Assign Brand</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form id="brand-list">
                                    <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-4">

                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label font-weight-bold">CLIENT:</label>
                                            <div class="col-sm-9">
                                            <select class="form-control select-search user-select user_list" name="user" data-fouc>
                                                <option value="0">...</option>
                                                <?php foreach ($users->getResultArray() as $client) : ?>
                                                <option value="<?= $client['id'] ?>"><?= $client['fullname'] . " (" . $client['company'] . ")" ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-4">

                                   

                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="form-group" id="brandcontent">
                                        <p class="font-weight-semibold"></p>
                                        <div class="row brandlist">
                                        <?php foreach ($brands->getResultArray() as $brand) : ?>
                                            <div class="col-md-2">
                                            <label class="custom-control custom-control-dark custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input brand_check">
                                                <span class="custom-control-label font-weight-bold"><?= $brand['brand_name'] ?></span>
                                            </label>
                                            </div>
                                        <?php endforeach ?>
                                        </div>
                                    </div>
                                        <div class="text-center">
                                            <button class="btn btn-secondary btn_check" ><i class="icon-checkmark2"></i> Save</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="text-right">
                                <a class="btn btn-light" data-dismiss="modal">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#week_modal"><i class="icon-gear mr-2"></i>Period Setting</button>
                    <div id="week_modal" class="modal fade" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary text-white">
                                    <h5 class="modal-title">Period Setting</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('save-periode-setting') ?>" method="POST" enctype="multipart/form-data">
                                    <?php csrf_field() ?>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Week 1:</label>
                                            <label class="custom-file">
                                                <?php
                                                $week1_start = $weeks[0]['date1'];
                                                $week1_end = $weeks[0]['date2'];
                                                $week1_start = str_replace('-', '/', $week1_start);
                                                $week1_start = date('m/d/Y', strtotime($week1_start));
                                                $week1_end = str_replace('-', '/', $week1_end);
                                                $week1_end = date('m/d/Y', strtotime($week1_end));
                                                ?>
                                                <input type="text" class="form-control" name="week1" value="<?= $week1_start ?> - <?= $week1_end ?>" readonly />
                                                <input type="hidden" name="week1-start" value="<?= $weeks[0]['date1'] ?>">
                                                <input type="hidden" name="week1-end" value="<?= $weeks[0]['date2'] ?>">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Week 2:</label>
                                            <label class="custom-file">
                                                <?php
                                                $week2_start = $weeks[1]['date1'];
                                                $week2_end = $weeks[1]['date2'];
                                                $week2_start = str_replace('-', '/', $week2_start);
                                                $week2_start = date('m/d/Y', strtotime($week2_start));
                                                $week2_end = str_replace('-', '/', $week2_end);
                                                $week2_end = date('m/d/Y', strtotime($week2_end));
                                                ?>
                                                <input type="text" class="form-control" name="week2" value="<?= $week2_start ?> - <?= $week2_end ?>" readonly />
                                                <input type="hidden" name="week2-start" value="<?= $weeks[1]['date1'] ?>">
                                                <input type="hidden" name="week2-end" value="<?= $weeks[1]['date2'] ?>">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Week 3:</label>
                                            <label class="custom-file">
                                                <?php
                                                $week3_start = $weeks[2]['date1'];
                                                $week3_end = $weeks[2]['date2'];
                                                $week3_start = str_replace('-', '/', $week3_start);
                                                $week3_start = date('m/d/Y', strtotime($week3_start));
                                                $week3_end = str_replace('-', '/', $week3_end);
                                                $week3_end = date('m/d/Y', strtotime($week3_end));
                                                ?>
                                                <input type="text" class="form-control" name="week3" value="<?= $week3_start ?> - <?= $week3_end ?>" readonly />
                                                <input type="hidden" name="week3-start" value="<?= $weeks[2]['date1'] ?>">
                                                <input type="hidden" name="week3-end" value="<?= $weeks[2]['date2'] ?>">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Week 4:</label>
                                            <label class="custom-file">
                                                <?php
                                                $week4_start = $weeks[3]['date1'];
                                                $week4_end = $weeks[3]['date2'];
                                                $week4_start = str_replace('-', '/', $week4_start);
                                                $week4_start = date('m/d/Y', strtotime($week4_start));
                                                $week4_end = str_replace('-', '/', $week4_end);
                                                $week4_end = date('m/d/Y', strtotime($week4_end));
                                                ?>
                                                <input type="text" class="form-control" name="week4" value="<?= $week4_start ?> - <?= $week4_end ?>" readonly />
                                                <input type="hidden" name="week4-start" value="<?= $weeks[3]['date1'] ?>">
                                                <input type="hidden" name="week4-end" value="<?= $weeks[3]['date2'] ?>">
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Week 5:</label>
                                            <label class="custom-file">
                                                <?php
                                                $week5_start = $weeks[4]['date1'];
                                                $week5_end = $weeks[4]['date2'];
                                                $week5_start = str_replace('-', '/', $week5_start);
                                                $week5_start = date('m/d/Y', strtotime($week5_start));
                                                $week5_end = str_replace('-', '/', $week5_end);
                                                $week5_end = date('m/d/Y', strtotime($week5_end));
                                                ?>
                                                <input type="text" class="form-control" name="week5" value="<?= $week5_start ?> - <?= $week5_end ?>" readonly />
                                                <input type="hidden" name="week5-start" value="<?= $weeks[4]['date1'] ?>">
                                                <input type="hidden" name="week5-end" value="<?= $weeks[4]['date2'] ?>">
                                            </label>
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
        </div>
        <hr class="m-0">
        <form class="wizard-form steps-async wizard clearfix" action="<?= base_url() ?>/save-assignment" method="post" data-fouc="" role="application" id="steps-uid-1">
            <?= csrf_field() ?>
            <div class="steps clearfix">
                <ul role="tablist">
                    <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="steps-uid-1-t-0" href="#steps-uid-1-h-0" aria-controls="steps-uid-1-p-0" class=""><span class="current-info audible">current step: </span><span class="number">1</span> Box Assignment</a></li>
                    <li role="tab" class="disabled" aria-disabled="true"><a id="steps-uid-1-t-1" href="#steps-uid-1-h-1" aria-controls="steps-uid-1-p-1" class="disabled"><span class="number">2</span> Assignment Process</a></li>
                    <li role="tab" class="disabled" aria-disabled="true"><a id="steps-uid-1-t-2" href="#steps-uid-1-h-2" aria-controls="steps-uid-1-p-2" class="disabled"><span class="number">3</span> Completed Assignment</a></li>
                </ul>
            </div>
            <div class="reset-button">
                <a href="<?= base_url('/reset-assignment') ?>"><span class="badge badge-danger"><i class="icon-reset mr-2"></i>RESET</span></a>
            </div>
            <table class="table datatable-basic" id="myTable" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Box Name</th>
                        <th class="text-center" style="width: 5%">Category</th>
                        <th class="text-center" style="width: 5%">Status</th>
                        <th class="text-center" style="width: 10%">Box Value</th>
                        <th class="text-center" style="width: 15%">VA User</th>
                        <th class="text-center" style="width: 15%">Client</th>
                        <th class="text-center" style="width: 10%">Company</th>
                        <th class="text-center" style="width: 5%">Brand Approval</th>
                        <th class="text-center" style="width: 5%">Investment Date</th>
                        <th class="text-center" style="width: 15%">Current</th>
                        <th class="text-center" style="width: 15%">Total</th>
                    </tr>
                </thead>
                <tbody id="assign-body">
                    <?php if ($getAllAssignReport->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>

                        <?php foreach ($getAllAssignReport->getResultArray() as $row) : ?>
                            <?php if (!empty($row['userid']) && $row['confirmed'] == 0) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $no++ ?>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="font-weight-bold box_name h6 name_box_<?= $no ?>" data-box="<?= $row['box_name'] ?>">
                                            <?= $row['box_name'] ?>
                                        </a>
                                        <br>
                                        <p class="desc_box_<?= $no ?>">
                                            <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                                <?php $desc = substr($row['description'], $pos + 1);     ?>
                                                <?= $desc  ?>
                                            <?php else : ?>
                                                None
                                            <?php endif ?>
                                        </p>
                                    </td>
                                    <td class="text-center category_box_<?= $no ?>">
                                        <b><?= strtoupper($row['category']) ?></b>
                                    </td>
                                    <td class="text-center"><span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span></td>
                                    <td class="text-center font-weight-bold value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                                    <td class="text-center">
                                        <select class="form-control va_box_<?= $no ?>" name="va[]">
                                            <option value="0">...</option>
                                            <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                                <?php if ($va['id'] == $row['va_id']) : ?>
                                                    <option value="<?= $va['id'] ?>" selected><b><?= $va['fullname'] ?></b></option>
                                                <?php else : ?>
                                                    <option value="<?= $va['id'] ?>"><b><?= $va['fullname'] ?></b></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-control clientSelect" name="client[]" id="box_<?= $no ?> ">
                                            <option value="0">...</option>
                                            <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                                <?php if ($client['id'] == $row['userid']) : ?>
                                                    <option value="<?= $client['id'] ?>" selected><b><?= $client['fullname'] ?></b></option>
                                                <?php else : ?>
                                                    <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?></b></option>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td class="text-center company_box_<?= $no ?>"><?= $row['company'] ?></td>
                                    <td class="text-center">
                                        <a href="#" style="color: #232F3E" class="popoverbrand_box_<?= $no ?>" data-popup="popover-custom" title="Brand Approval" data-trigger="focus" data-content="<?= $row['brand_approval'] ?>">
                                            <i class="fab fa-amazon mr-3 fa"></i>
                                        </a>
                                    </td>
                                    <td class="text-center date_box_<?= $no ?>">
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                        <select class="select_date_box_<?= $no ?>">
                                            <option value="<?= $row['investment_id'] ?>" selected><?= strtoupper($newDateInvest) ?></option>
                                        </select>
                                    </td>
                                    <td class="currentCost_box_<?= $no ?>">
                                        <b><?= "$ " . number_format($row['current_cost'], 2) ?></b>
                                        <a href="#" class="popover_box_<?= $no ?>" data-popup="popover-custom" title="Category Percentage" data-trigger="focus" data-content="Loading..."> <i class="icon-info22"></i></a>
                                    </td>
                                    <td class="total_box_<?= $no ?>">
                                        <b><?= "$ " . number_format($row['cost_left'], 2) ?></b>
                                    </td>
                                </tr>

                            <?php elseif (empty($row['userid'])) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $no++ ?>
                                        <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="font-weight-bold h6 box_name name_box_<?= $no ?>" data-box="<?= $row['box_name'] ?>">
                                            <?= $row['box_name'] ?>
                                        </a>
                                        <br>
                                        <p class="desc_box_<?= $no ?>">
                                            <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                                <?php $desc = substr($row['description'], $pos + 1);     ?>
                                                <?= $desc  ?>
                                            <?php else : ?>
                                                None
                                            <?php endif ?>
                                        </p>
                                        
                                    </td>
                                    <td class="text-center category_box_<?= $no ?>">
                                        <b><?= strtoupper($row['category']) ?></b>
                                    </td>
                                    <td class="text-center"><span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span></td>
                                    <td class="text-center font-weight-bold value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>

                                    <td class="text-center">
                                        <select class="form-control va_box_<?= $no ?>" name="va[]">
                                            <option value="0">...</option>
                                            <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                                <option value="<?= $va['id'] ?>"><b><?= $va['fullname'] ?></b></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <select class="form-control clientSelect client_box_<?= $no ?>" name="client[]" id="box_<?= $no ?> ">
                                            <option value="0">...</option>

                                        </select>
                                    </td>
                                    <td class="text-center company_box_<?= $no ?>">...</td>
                                    <td class="text-center">
                                        <a href="#" style="color: #232F3E" class="popoverbrand_box_<?= $no ?>" data-popup="popover-custom" title="Brand Approval" data-trigger="focus" data-content="Loading...">
                                            <i class="fab fa-amazon mr-3 fa"></i>
                                        </a>
                                    </td>
                                    </td>
                                    <td class="text-center date_box_<?= $no ?>">
                                        <select class="select_date_box_<?= $no ?>">
                                        </select>
                                    </td>
                                    <td class="currentCost_box_<?= $no ?>">
                                        <a href="#" class="popover_box_<?= $no ?>" data-popup="popover-custom" title="Category Percentage" data-trigger="focus" data-content="Loading..."> <i class="icon-info22"></i></a>
                                    </td>
                                    <td class="total_box_<?= $no ?>"></td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>

            </table>
            <div class="card-body" style="display: flex">
                <div class="text-right" style="margin: auto;">
                    <button type="submit" class="btn btn-danger"><i class="icon-checkmark3 mr-2"></i> <b>Save Phase 1</b></button>
                </div>
                <div class="text-left">
                    <a href="#" class="btn btn-light disabled"><i class="icon-arrow-left8 mr-2"></i>Previous</a>
                    <a href="<?= base_url('/admin/assignment-process') ?>" class="btn btn-primary">Next Phase<i class="icon-arrow-right8 ml-2"></i></a>
                </div>

            </div>
        </form>
        <div class="card-body">

            The pending box
        </div>
        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%">No</th>
                    <th class="text-center" style="width: 10%">Box Name</th>
                    <th class="text-center" style="width: 5%">Category</th>
                    <th class="text-center" style="width: 10%">Status</th>
                    <th class="text-center">VA</th>
                    <th class="text-center" style="width: 15%">Box Value</th>
                    <th class="text-center" style="width: 5%">Order</th>
                    <th class="text-center">Client</th>
                    <th class="text-center">Investment Date</th>
                    <th class="text-center">Current</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllAssignReportPending->getNumRows() > 0) : ?>
                    <?php $no = 1 ?>
                    <?php foreach ($getAllAssignReportPending->getResultArray() as $row) : ?>
                        <tr class="table-active">
                            <td>
                                <?= $no++ ?>
                                <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                            </td>
                            <td class="name_box_<?= $no ?>">
                                <a href="#" class="h6 box_name2" data-box="<?= $row['box_name'] ?>">
                                    <b><?= $row['box_name'] ?></b>
                                </a>
                                <br>
                                <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                    <?php $desc = substr($row['description'], $pos + 1);     ?>
                                    <?= $desc  ?>
                                <?php else : ?>
                                    None
                                <?php endif ?>
                            </td>
                            <td class="text-ceenter category_box_<?= $no ?>">
                                <b><?= strtoupper($row['category']) ?></b>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'waiting') : ?>
                                    <span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php elseif ($row['status'] == 'rejected') : ?>
                                    <span class="badge badge-danger"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php else : ?>
                                    <span class="badge badge-success"><b><?= strtoupper($row['status']) ?></b></span>
                                <?php endif ?>
                            </td>
                            <td class="company_box_<?= $no ?>">
                                <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                    <?php if ($va['id'] == $row['va_id']) : ?>
                                        <b><?= $va['fullname'] ?></b>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </td>
                            <td class="value_box_<?= $no ?>">$ <?= $row['box_value'] ?></td>
                            <td>
                                <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                <input type="text" class="order_box_<?= $no ?>" name="date[]" value="<?= $newDate ?>" style="width: 90px; text-align:center" readonly>
                            </td>
                            <td>
                                <b><?= $row['fullname'] ?></b>
                            </td>

                            <td class="date_box_<?= $no ?>">
                                <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                <b><?= strtoupper($newDateInvest) ?></b>
                            </td>
                            <td class="currentCost_box_<?= $no ?>">
                                <b><?= "$ " . number_format($row['current_cost'], 2) ?></b>
                            </td>
                            <td class="total_box_<?= $no ?>">
                                <b><?= "$ " . number_format($row['cost_left'], 2) ?></b>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>

        </table>
        <div class="card-body">
            The boxes that have been checked by VA
        </div>
        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%">No</th>
                    <th class="text-center" style="width: 10%">Box Name</th>
                    <th class="text-center" style="width: 5%">Category</th>
                    <th class="text-center" style="width: 10%">Status</th>
                    <th class="text-center" style="width: 15%">Box Value</th>
                    <th class="text-center">VA</th>
                    <th class="text-center">FBA Number</th>
                    <th class="text-center">Shipment Number</th>
                    <th class="text-center" style="width: 5%">Order</th>
                    <th class="text-center">Client</th>
                    <th class="text-center">Investment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllAssignReportCompleted->getNumRows() > 0) : ?>
                    <?php $no = 1 ?>
                    <?php foreach ($getAllAssignReportCompleted->getResultArray() as $row) : ?>
                        <?php if ($row['status'] == 'reassigned') : ?>
                            <tr class="table-warning">
                                <td>
                                    <?= $no++ ?>
                                    <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                </td>
                                <td class="text-center">
                                    <a href="#" class="h6 box_name3" data-box="<?= $row['box_name'] ?>">
                                        <b><?= $row['box_name'] ?></b>
                                    </a>
                                    <br>
                                    <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                        <?php $desc = substr($row['description'], $pos + 1);     ?>
                                        <?= $desc  ?>
                                    <?php else : ?>
                                        None
                                    <?php endif ?>
                                </td>
                                <td class="text-ceenter category_box_<?= $no ?>">
                                    <b><?= strtoupper($row['category']) ?></b>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 'reassigned') : ?>
                                        <span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php elseif ($row['status'] == 'remanifested') : ?>
                                        <span class="badge badge-danger"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php else : ?>
                                        <span class="badge badge-success"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php endif ?>
                                </td>
                                <td class="value_box_<?= $no ?>">
                                    <?php if ($row['box_value'] == $row['new_box_value']) : ?>
                                        <b>$ <?= number_format($row['box_value'], 2) ?></b>
                                    <?php else : ?>
                                        <del>$ <?= $row['box_value'] ?></del> <b><mark>$ <?= number_format($row['new_box_value'], 2) ?></mark></b>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                        <?php if ($va['id'] == $row['va_id']) : ?>
                                            <b><?= $va['fullname'] ?></b>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                </td>
                                <td>
                                    <b><?= $row['fba_number'] ?></b>
                                </td>
                                <td>
                                    <b><?= $row['shipment_number'] ?> </b>
                                </td>
                                <td>
                                    <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                    <input disabled type="text" class="daterange-single order_box_<?= $no ?>" name="date[]" value="<?= $newDate ?>" style="width: 90px; text-align:center">
                                </td>
                                <td>
                                    <b><?= $row['fullname'] ?></b>
                                </td>

                                <td class="text-center">
                                    <select>
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                        <option value="investment_id" selected><b><?= strtoupper($newDateInvest) ?></b></option>
                                    </select>
                                    <div class="list-icons">
                                        <div class="dropdown position-static">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a href="#" class="dropdown-item rollback" data-id="<?= $row['box_name'] ?>"><i class="icon-undo"></i> Rollback Assignment</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php else : ?>
                            <tr class="table-success">
                                <td>
                                    <?= $no++ ?>
                                    <input type="hidden" name="box_id[]" value="<?= $row['id'] ?>">
                                </td>
                                <td class="text-center">
                                    <a href="#" class="h6 box_name2" data-box="<?= $row['box_name'] ?>">
                                        <b><?= $row['box_name'] ?></b>
                                    </a>
                                    <br>
                                    <?php if (($pos = strpos($row['description'], "-")) !== FALSE) : ?>
                                        <?php $desc = substr($row['description'], $pos + 1);     ?>
                                        <?= $desc  ?>
                                    <?php else : ?>
                                        None
                                    <?php endif ?>
                                </td>
                                <td class="text-ceenter category_box_<?= $no ?>">
                                    <b><?= strtoupper($row['category']) ?></b>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 'reassigned') : ?>
                                        <span class="badge badge-secondary"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php elseif ($row['status'] == 'remanifested') : ?>
                                        <span class="badge badge-danger"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php else : ?>
                                        <span class="badge badge-success"><b><?= strtoupper($row['status']) ?></b></span>
                                    <?php endif ?>
                                </td>
                                <td class="value_box_<?= $no ?>">
                                    <?php if ($row['box_value'] == $row['new_box_value']) : ?>
                                        <b>$ <?= number_format($row['box_value'], 2) ?></b>
                                    <?php else : ?>
                                        <del>$ <?= $row['box_value'] ?></del> <b><mark>$ <?= number_format($row['new_box_value'], 2) ?></mark></b>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php foreach ($getAllVA->getResultArray() as $va) : ?>
                                        <?php if ($va['id'] == $row['va_id']) : ?>
                                            <b><?= $va['fullname'] ?></b>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                </td>
                                <td>
                                    <b><?= $row['fba_number'] ?></b>
                                </td>
                                <td>
                                    <b><?= $row['shipment_number'] ?> </b>
                                </td>
                                <td>
                                    <?php $newDate = date('m/d/Y', strtotime($row['order_date'])); ?>
                                    <input disabled type="text" class="daterange-single order_box_<?= $no ?>" name="date[]" value="<?= $newDate ?>" style="width: 90px; text-align:center">
                                </td>
                                <td>
                                    <b><?= $row['fullname'] ?></b>
                                </td>

                                <td>
                                    <select>
                                        <?php $newDateInvest = date("M-d-Y", strtotime($row['investdate'])); ?>
                                        <option value="investment_id" selected><b><?= strtoupper($newDateInvest) ?></b></option>
                                    </select>
                                </td>
                            </tr>
                        <?php endif ?>



                    <?php endforeach ?>
                <?php endif ?>
            </tbody>

        </table>

    </div>
    <div class="modal fade assignboxmodal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form>
                    <div class="modal-header pb-3">
                        <h5><b><span class="modal-title-assign">#title</span></b> </h5>
                    </div>
                    <div class="modal-body py-0">                
                            <?php csrf_field() ?>
                            <div class="form-group">
                                <label for=""><b>Select Client:</b></label>
                                <div class="input-group">
                                    <select class="form-control select-client-reject" name="client">
                                        <option value="0">...</option>
                                        <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                            <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?> (<?= $client['company'] ?> )</b></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                            </div>
                        
                    </div>
                    <div class="modal-footer pt-3">
                        <a class="btn btn-light" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-secondary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal_scrollable_box" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title">#title</span></b> </h5>
                </div>
                <div class="modal-body py-0">
                    <form id="box-details">
                        <?php csrf_field() ?>
                        <div class="table-responseive">
                            <table class="table text-center" id="sum" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Total Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Total Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" value="1" readonly contenteditable="true"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="item-table">
                            <!-- <form action="<?= base_url('/save-box-details') ?>" method="post"> -->

                            <?php csrf_field() ?>
                            <input type="hidden" name="box_name" id="box_name" value="">
                            <table class="table text-center" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody">

                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive mt-2" id="item-table-removed" style="display: none;">
                            <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                <span class="font-weight-semibold">Some items have been removed in a box!</span>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>

                            <table class="table text-center" style="font-weight:bold; font-size:12px" id="table-removed">
                                <thead>

                                    <tr class="bg-danger text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody-removed">

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group">
                            <label for=""><b>Note:</b></label>
                            <div class="input-group">
                                <textarea name="box_note" disabled class="form-control" id="box_note" rows="3" placeholder="-"></textarea>
                            </div>

                        </div>

                </div>

                <div class="modal-footer pt-3">
                    <a class="btn btn-light" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-secondary">Save Changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal_scrollable_box2" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title">#title</span></b> </h5>
                </div>
                <div class="modal-body py-0">
                    <form id="box-details">
                        <?php csrf_field() ?>

                        <div class="table-responseive">
                            <table class="table text-center" id="sum2" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Total Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Total Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" value="1" readonly contenteditable="true"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="item-table2">

                            <input type="hidden" name="box_name" id="box_name2" value="">
                            <table class="table text-center" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody2">

                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive mt-2" id="item-table-removed2" style="display: none;">
                            <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                <span class="font-weight-semibold">Some items have been removed in a box!</span>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>

                            <table class="table text-center" style="font-weight:bold; font-size:12px" id="table-removed">
                                <thead>

                                    <tr class="bg-danger text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody-removed2">

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group">
                            <label for=""><b>Note:</b></label>
                            <div class="input-group">
                                <textarea name="box_note" disabled class="form-control" id="box_note" rows="3" placeholder="-"></textarea>
                            </div>

                        </div>

                </div>

                <div class="modal-footer pt-3">
                    <a class="btn btn-light" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal_scrollable_box3" tabindex="-1">
        <div class="modal-dialog modal-full modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header pb-3">
                    <h5><b><span class="modal-title3">#title</span></b> </h5>
                </div>
                <div class="modal-body py-0">
                    <form method="post" action="<?= base_url('/reassign-box') ?>">
                        <?php csrf_field() ?>
                        <div class="form-group">
                            <label for=""><b>Select New Client:</b></label>
                            <div class="input-group">
                                <select class="form-control select-client-reject" name="client">
                                    <option value="0">...</option>
                                    <?php foreach ($getAllClient->getResultArray() as $client) : ?>
                                        <option value="<?= $client['id'] ?>"><b><?= $client['fullname'] ?> (<?= $client['company'] ?> )</b></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for=""><b>Investment Date:</b></label>
                            <div class="input-group">
                                <select class="select_date_reject form-control">
                                </select>
                            </div>
                        </div>
                        <div class="table-responseive">
                            <table class="table text-center" id="sum3" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Total Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Total Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" value="1" readonly contenteditable="true"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" id="item-table3">

                            <input type="hidden" name="box_name" id="box_name3" value="">
                            <table class="table text-center" style="font-weight:bold; font-size:12px">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody3">

                                </tbody>
                            </table>

                        </div>
                        <div class="table-responsive mt-2" id="item-table-removed3" style="display: none;">
                            <div class="alert alert-info alert-dismissible alert-styled-left border-top-0 border-bottom-0 border-right-0">
                                <span class="font-weight-semibold">Some items have been removed in a box!</span>
                                <button type="button" class="close" data-dismiss="alert">×</button>
                            </div>

                            <table class="table text-center" style="font-weight:bold; font-size:12px" id="table-removed3">
                                <thead>

                                    <tr class="bg-danger text-white">
                                        <th style="width: 10%;">SKU</th>
                                        <th style="width: 20%;">Item Description</th>
                                        <th style="width: 5%;">Condition</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 10%;">Retail</th>
                                        <th style="width: 10%;">Total Retail</th>
                                        <th style="width: 10%;">Cost</th>
                                        <th style="width: 15%;">Vendor</th>
                                        <th style="width: 15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="item-tbody-removed3">

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group">
                            <label for=""><b>Note:</b></label>
                            <div class="input-group">
                                <textarea name="box_note" disabled class="form-control" id="box_note" rows="3" placeholder="-"></textarea>
                            </div>

                        </div>




                </div>

                <div class="modal-footer pt-3">
                    <button type="submit" class="btn btn-secondary">Save Changes</button>
                    <a class="btn btn-light" data-dismiss="modal">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /blocks with chart -->
    <button type="button" id="noty_created" style="display: none;"></button>
    <button type="button" id="noty_deleted" style="display: none;"></button>
    <button type="button" id="noty_error" style="display: none;"></button>
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
<script src="/assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/assets/js/demo_pages/components_popups.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(document).ready(function() {
        $('.user-select').change(function() {
            const id = $(this).val();
            $.get('/get-brands-client', {
                userid: id
            }, function(data) {
                $('.brandlist').html("");
                const brand = JSON.parse(data);
                for (var i = 0; i < brand.length; i++) {
                if (brand[i]['checked'] == 1) {
                    $('.brandlist').append('<div class="col-md-2"> <label class="custom-control custom-control-dark custom-checkbox mb-2"> <input type="checkbox" name="brand[]" value="' + brand[i]['id'] + '" class="custom-control-input brand_check" checked> <span class="custom-control-label font-weight-bold">' + brand[i]['brand_name'] + '</span> </label> </div>');
                } else {
                    $('.brandlist').append(' <div class="col-md-2"> <label class="custom-control custom-control-dark custom-checkbox mb-2"> <input type="checkbox" name="brand[]" value="' + brand[i]['id'] + '" class="custom-control-input brand_check" > <span class="custom-control-label font-weight-bold">' + brand[i]['brand_name'] + '</span> </label> </div>');
                }
                }
            })
        });

        $('.brand-select').change(function() {
            const id = $(this).val();
            $.get('/get-client-by-brand', {
                brandid: id
            }, function(data) {
                $('.userlist').html("");
                const user = JSON.parse(data);
                $('#totalclient').html(user.length);
                for (var i = 0; i < user.length; i++) {
                if (user[i]['checked'] == 1) {
                    $('.userlist').append('<div class="col-md-3"><dl class="mb-0"> <dt class="font-weight-bold"><i class="icon-checkmark-circle"></i> ' + user[i]['fullname'] + ' (' + user[i]['company'] + ')</dt> </dl></div>');
                }
                }
            })
        });

        $("form#brand-list").on("submit", function(e) {
            e.preventDefault();
            $.post('<?= base_url('/save-brand-client') ?>', $(this).serialize(), function(data) {
            if (data != "0") {
                new Noty({
                text: 'Brands has been saved.',
                type: 'alert'
                }).show();
            } else {
                new Noty({
                text: 'Please select client first',
                type: 'alert'
                }).show();
            }
            });
        });


        $("form#formaddbrand").on("submit", function(e) {
            e.preventDefault();
            $.post('<?= base_url('/add-brand') ?>', $(this).serialize(), function(data) {
            new Noty({
                text: 'New Brand has been saved.',
                type: 'alert'
            }).show();
            $("#brandcontent").load(location.href + " #brandcontent");

            $('#modal_form_upload').modal('hide');
            });
        });

        $('.rollback').on('click', function() {
            var boxName = $(this).data('id');
            swal("Enter your password:", {
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Type your password",
                            type: "password",
                        },
                    },
                })
                .then((value) => {
                    const pw = "superadmin";
                    if (pw == value) {
                        $.post('/rollback-assignment', {
                            box_name: boxName
                        }, function(data) {
                            window.location.reload();
                        });
                    } else {
                        swal("Wrong Password!");
                    }

                });
        })

        $('input[name="week1"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="week1-start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="week1-end"]').val(end.format('YYYY-MM-DD'));
        });

        $('input[name="week2"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="week2-start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="week2-end"]').val(end.format('YYYY-MM-DD'));
        });

        $('input[name="week3"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="week3-start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="week3-end"]').val(end.format('YYYY-MM-DD'));
        });

        $('input[name="week4"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="week4-start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="week4-end"]').val(end.format('YYYY-MM-DD'));
        });

        $('input[name="week5"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $('input[name="week5-start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="week5-end"]').val(end.format('YYYY-MM-DD'));
        });

        <?php if (session()->getFlashdata('save')) : ?>
            swal("Great!", "<?= session()->getFlashdata('save') ?>", "success");
        <?php endif ?>

        <?php if (session()->getFlashdata('error')) : ?>
            swal("Oops!", "<?= session()->getFlashdata('error') ?>", "warning");
        <?php endif ?>

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
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
        <?php if (session()->getFlashdata('delete')) : ?>
            $('#noty_deleted').click();
        <?php endif ?>


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

    });
    var i = 1;
    var tempTotal = 0;
    var total = 0;



    $('.select-client-reject').on('change', function() {
        const clientId = $(this).val();
        $.get('/get-company/' + clientId, function(data) {
            var company = JSON.parse(data);
            $('.select_date_reject').html("");
            $.post('/get-investment-client', {
                id: clientId
            }, function(data) {
                var investdate = JSON.parse(data);
                if (investdate.length > 0) {
                    for (var i = 0; i < investdate.length; i++) {
                        $('.select_date_reject').append(investdate[i]);
                    }
                }
                var selected = $(this).find('option:selected');
                var currentCost = selected.data('foo');
            });
        });
    });

    $('.clientSelect').on('focus', function() {
        var boxId = $(this).attr('id');
        var boxNameId = "name_" + $(this).attr('id');
        var boxName = $('.' + boxNameId).html().trim();
        var descId = "desc_" + $(this).attr('id');
        var desc = $('.' + descId).html().trim();
        var clientId = "client_" + $(this).attr('id');
        var client = $('.' + clientId).html().trim();
        $("." + clientId)
            .empty()
            .append('<option value="0">...</option>');
        const myarr = desc.split("-").join(', ').split('/').join(', ').split(', ');
        const newDesc = myarr[0];
        console.log(newDesc);
        $.get('/get-client-by-branddesc', {
            description: newDesc
        }, function(data) {
            const myData = JSON.parse(data);
            for (var i = 0; i < myData.length; i++) {
                $("." + clientId).append($('<option>', {
                    value: myData[i]['id'],
                    text: myData[i]['fullname']
                }));
            }
        });
    });
    
    $('.clientSelect').on('change', function() {
        var optionSelected = $("option:selected", this);
        var boxId = $(this).attr('id');
        var boxNameId = "name_" + $(this).attr('id');
        var boxName = $('.' + boxNameId).html().trim();
        var descId = "desc_" + $(this).attr('id');
        var desc = $('.' + descId).html().trim();
        var valueBoxId = "value_" + $(this).attr('id');
        var valueBox = $('.' + valueBoxId).html();
        var orderDateId = "order_" + $(this).attr('id');
        var orderDate = $('.' + orderDateId).val();
        var valueBox = valueBox.substring(2);
        var clientId = this.value;
        var vaId = "va_" + $(this).attr('id');
        var vaUser = $('.' + vaId).val();
        $('.select_date_' + boxId).html("");
        $.get('/get-company/' + clientId, function(data) {
            var company = JSON.parse(data);
            console.log(company);
            if (company != null) {
                $('.company_' + boxId).html("<b>" + company['company'] + "</b>");
            } else {
                $('.company_' + boxId).html("");
            }
            if (company['brands'] == "") {
                var popoverbrand = $('.popoverbrand_' + boxId).attr('data-content', "none");
            } else {
                var popoverbrand = $('.popoverbrand_' + boxId).attr('data-content', company['brands']);
            }
            var unrest = "Unrestricted";
            if (company['brands'].includes(desc.split('-')[0]) == true || company['brands'].includes(desc.split('/')[0]) == true || desc.split('-')[0].toUpperCase() === unrest.toUpperCase()) {
                $.post('/get-investment-client', {
                    id: clientId
                }, function(data) {
                    var investdate = JSON.parse(data);
                    if (investdate.length > 0) {
                        for (var i = 0; i < investdate.length; i++) {
                            $('.select_date_' + boxId).append(investdate[i]);
                        }
                        var selected = $('.select_date_' + boxId).find('option:selected');
                        var currentCost = selected.data('foo');
                        $('.currentCost_' + boxId).find("span").remove();
                        $('.currentCost_' + boxId).prepend("<span class='current_" + boxId + "'><b>$ " + numberWithCommas(currentCost.toFixed(2)) + "</b></span>");
                        var investmentId = $('.select_date_' + boxId + ' option:selected').val();

                        $.post('/assign-box', {
                            box_id: boxId,
                            box_name: boxName,
                            order_date: orderDate,
                            client_id: clientId,
                            value_box: valueBox,
                            current_cost: currentCost,
                            investment_id: investmentId,
                            va_id: vaUser
                        }, function(data) {
                            var resp = JSON.parse(data);
                            if (resp['status'] == 0) {
                                swal("Oops...", "Total exceed $250.00!", "warning");
                                $('.total_' + boxId).html("");
                            } else {
                                $('.total_' + boxId).html("<b>$ " + numberWithCommas(resp['cost_left'].toFixed(2)) + "</b>");
                            }
                        });

                        $.get('/get-category', {
                            investment_id: investmentId,
                            current_cost: currentCost
                        }, function(data) {
                            var cat = JSON.parse(data);
                            var desc = "";

                            for (var i = 0; i < cat.length; i++) {
                                desc = desc.concat(cat[i]['category'] + ' (' + cat[i]['percent'] + '%) ')
                            }
                            var popover = $('.popover_' + boxId).attr('data-content', desc);
                        });
                    } else {
                        $.post('/assign-box', {
                            box_id: boxId,
                            box_name: boxName,
                            order_date: orderDate,
                            client_id: clientId,
                            value_box: valueBox,
                            current_cost: 0,
                            investment_id: 0,
                            va_id: vaUser,
                        }, function(data) {

                        });
                        $('.select_date_' + boxId).html("");
                        $('.currentCost_' + boxId).find("span").remove();
                        $('.total_' + boxId).html("");
                    }

                });
            } else {
                swal("Oops...", "This brand is not allowed on this client", "warning");
            } 


        });


        $('.select_date_' + boxId).on('change', function() {
            var selected = $(this).find('option:selected');
            var currentCost = selected.data('foo');
            $('.currentCost_' + boxId).html("<span class='current_" + boxId + "'><b>$ " + numberWithCommas(currentCost.toFixed(2)) + "</b></span>");
            var investmentId = $('.select_date_' + boxId + ' option:selected').val();
            $.post('/assign-box', {
                box_id: boxId,
                box_name: boxName,
                order_date: orderDate,
                client_id: clientId,
                value_box: valueBox,
                current_cost: currentCost,
                investment_id: investmentId
            }, function(data) {
                var resp = JSON.parse(data);
                if (resp['status'] == 0) {
                    swal("Oops...", "Total exceed $250.00!", "warning");
                    $('.total_' + boxId).html("");
                } else {
                    $('.total_' + boxId).html("<b>$ " + numberWithCommas(resp['cost_left'].toFixed(2)) + "</b>");
                }
            });

        });


    });


    $('.box_name').on('click', function() {
        var boxName = $(this).data('box');
        $('#item-table tbody').html("");
        $('#sum tbody').html("");
        $.get('/get-box-summary', {
            box_name: boxName
        }, function(data) {
            var item = JSON.parse(data);
            $('.modal-title').html("<b>" + item[0]['description'] + "</b>");
            $('#box_name').val(boxName);
            $('#item-table-removed').css("display", "none");
            $('.modal_scrollable_box').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#item-tbody-removed').html("");

            if (item.length > 0) {
                $('#box_note').html(item[0]['box_note']);
                var no = 1;
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                for (var i = 0; i < item.length; i++) {
                    if (item[i]['item_status'] == 1) {

                        if (i % 2 == 0) {
                            $('#item-table tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        } else {
                            $('#item-table tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        }
                    } else {
                        $('#item-table-removed').css("display", "block");
                        if (i % 2 == 0) {
                            $('#item-table-removed tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        } else {
                            $('#item-table-removed tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        }
                    }
                    qty = qty + parseInt(item[i]['qty']);
                    retail = retail + parseFloat(item[i]['retail']);
                    total = total + parseFloat(item[i]['original']);
                    cost = cost + parseFloat(item[i]['cost']);
                }
                $('#sum tbody').append('<tr><td>-</td> <td>-</td>  <td>-</td><td>' + qty + '</td> <td>$ ' + numberWithCommas(retail.toFixed(2)) + '</td> <td>$ ' + numberWithCommas(total.toFixed(2)) + '</td><td>$ ' + numberWithCommas(cost.toFixed(2)) + '</td> <td>-</td> <td>-</td></tr>');
            }

            $('.modal_scrollable_box').modal('show');
        });

    });

    $('.box_name2').on('click', function() {
        var boxName = $(this).data('box');
        $('#item-table2 tbody').html("");
        $('#sum2 tbody').html("");
        $.get('/get-box-summary', {
            box_name: boxName
        }, function(data) {
            var item = JSON.parse(data);
            $('.modal-title').html("<b>" + item[0]['description'] + "</b>");
            $('#box_name2').val(boxName);
            $('#item-table-removed2').css("display", "none");
            $('.modal_scrollable_box2').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#item-tbody-removed2').html("");

            if (item.length > 0) {
                $('#box_note').html(item[0]['box_note']);
                var no = 1;
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                for (var i = 0; i < item.length; i++) {
                    if (item[i]['item_status'] == 1) {

                        if (i % 2 == 0) {
                            $('#item-table2 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        } else {
                            $('#item-table2 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        }
                    } else {
                        $('#item-table-removed2').css("display", "block");
                        if (i % 2 == 0) {
                            $('#item-table-removed2 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td>  <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        } else {
                            $('#item-table-removed2 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td>  <td><b>$ ' + numberWithCommas(item[i]['retail']) + '</b></td> <td><b>$ ' + numberWithCommas(item[i]['original']) + '</b></td><td><b>$ ' + numberWithCommas(item[i]['cost']) + '</b></td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        }
                        qty = qty + parseInt(item[i]['qty']);
                        retail = retail + parseFloat(item[i]['retail']);
                        total = total + parseFloat(item[i]['original']);
                        cost = cost + parseFloat(item[i]['cost']);
                    }
                    qty = qty + parseInt(item[i]['qty']);
                    retail = retail + parseFloat(item[i]['retail']);
                    total = total + parseFloat(item[i]['original']);
                    cost = cost + parseFloat(item[i]['cost']);
                }
                $('#sum2 tbody').append('<tr><td>-</td> <td>-</td>  <td>-</td><td>' + qty + '</td> <td>$ ' + numberWithCommas(retail.toFixed(2)) + '</td> <td>$ ' + numberWithCommas(total.toFixed(2)) + '</td><td>$ ' + numberWithCommas(cost.toFixed(2)) + '</td> <td>-</td> <td>-</td></tr>');
            }

            $('.modal_scrollable_box2').modal('show');
        });

    });

    $('.box_name3').on('click', function() {
        var boxName = $(this).data('box');
        $('#item-table3 tbody').html("");
        $('#sum3 tbody').html("");
        $.get('/get-box-summary', {
            box_name: boxName
        }, function(data) {
            var item = JSON.parse(data);
            $('.modal-title3').html("<b>" + item[0]['description'] + "</b>");
            $('#box_name3').val(boxName);
            $('#item-table-removed3').css("display", "none");
            $('.modal_scrollable_box3').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#item-tbody-removed3').html("");

            if (item.length > 0) {
                $('#box_note3').html(item[0]['box_note']);
                var no = 1;
                var qty = 0;
                var retail = 0;
                var total = 0;
                var cost = 0;
                for (var i = 0; i < item.length; i++) {
                    if (item[i]['item_status'] == 1) {

                        if (i % 2 == 0) {
                            $('#item-table3 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        } else {
                            $('#item-table3 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td> <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td> </tr>');
                        }
                    } else {
                        $('#item-table-removed3').css("display", "block");
                        if (i % 2 == 0) {
                            $('#item-table-removed3 tbody').append('<tr><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        } else {
                            $('#item-table-removed3 tbody').append('<tr class="table-active"><td><input type="hidden" name="item[]" value="' + item[i]['id'] + '">' + item[i]['sku'] + '</td> <td class="text-left">' + item[i]['item_description'] + '</td> <td>' + item[i]['cond'] + '</td> <td>' + item[i]['qty'] + '</td> <td><input type="text" name="retail[]" class="retail_edit" value="$ ' + numberWithCommas(item[i]['retail']) + '"</td> <td><input type="text" name="original[]" value="$ ' + numberWithCommas(item[i]['original']) + '"</td><td><input type="text" name="cost[]" value="$ ' + numberWithCommas(item[i]['cost']) + '"</td>  <td class="text-left">' + item[i]['vendor'] + '</td> <td><input type="text" name="note[]" class="form-control text-left" readonly value="' + $.trim(item[i]['item_note']) + '"></td></tr>');
                        }
                    }
                    qty = qty + parseInt(item[i]['qty']);
                    retail = retail + parseFloat(item[i]['retail']);
                    total = total + parseFloat(item[i]['original']);
                    cost = cost + parseFloat(item[i]['cost']);
                }
                $('#sum3 tbody').append('<tr><td>-</td> <td>-</td>  <td>-</td><td>' + qty + '</td> <td>$ ' + numberWithCommas(retail.toFixed(2)) + '</td> <td>$ ' + numberWithCommas(total.toFixed(2)) + '</td><td>$ ' + numberWithCommas(cost.toFixed(2)) + '</td> <td>-</td> <td>-</td></tr>');
            }

            $('.modal_scrollable_box3').modal('show');
        });

    });


    $("form#box-details").on("submit", function(e) {
        e.preventDefault();
        $.post('<?= base_url('/update-price-item') ?>', $(this).serialize(), function(data) {
            new Noty({
                text: 'The box has been saved.',
                type: 'alert'
            }).show();
            $('.modal_scrollable_box').modal('hide');
        });
    });



    $('#noty_created').on('click', function() {
        new Noty({
            text: 'You successfully upload the report.',
            type: 'success'
        }).show();


    });

    $('#noty_save').on('click', function() {
        new Noty({
            text: 'You successfully save first phase.',
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