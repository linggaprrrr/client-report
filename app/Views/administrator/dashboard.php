<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">

    <!-- Blocks with chart -->
    <div class="row">
        <div class="col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-header d-flex pb-1">
                    <div>

                        <span class="card-title font-weight-semibold">Total Client Cost</span>
                        <h2 class="font-weight-bold mb-0">$ <?= number_format($totalInvest->total_client_cost, 2) ?> <small class="text-success font-size-base ml-2"></small></h2>
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
            <div class="card bg-danger text-white">
                <div class="card-header d-flex pb-1">
                    <div>

                        <span class="card-title font-weight-semibold">Total Unit</span>
                        <h2 class="font-weight-bold mb-0"><?= ($totalUnit->total_unit > 0) ? $totalUnit->total_unit : "0" ?></h2>
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

                        <span class="card-title font-weight-semibold">Total Original Retail</span>
                        <h2 class="font-weight-bold mb-0">$ <?= number_format($totalRetail->total_retail, 2) ?> <small class="text-danger font-size-base ml-2"></small></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-secondary text-white">
                <div class="card-header d-flex pb-1">
                    <div>
                        <span class="card-title font-weight-semibold">Total Cost Left</span>
                        <h2 class="font-weight-bold mb-0">$ <?= number_format($totalCostLeft, 2) ?><small class="text-danger font-size-base ml-2"></small></h2>
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
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-table2"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0"><?= ($totalUnit->total_unit > 0) ? $totalUnit->total_unit : "0" ?></h5>
                    <span class="text-muted">Total Unit</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cart-remove"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <?= number_format($totalFulfilled->total_fulfilled, 2) ?></h5>
                    <span class="text-muted">Total Fulfilled</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cube"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <?= ($totalUnit->total_unit != 0) ? number_format(($totalInvest->total_client_cost / $totalUnit->total_unit), 2) : "0" ?></h5>
                    <span class="text-success font-size-sm font-weight-normal">
                        AVG UNIT RETAIL ( $ <?= ($totalUnit->total_unit != 0) ? number_format(($totalRetail->total_retail / $totalUnit->total_unit), 2) : "0" ?> )
                    </span>

                </div>
            </div>

            <div>

            </div>
        </div>

        <table class="table datatable-basic" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Client Name</th>
                    <th>Company</th>
                    <th>Total Unit</th>
                    <th>Total Retail</th>
                    <th>Total Client Cost</th>
                    <th>Total Fulfilled</th>
                    <th>Total Cost Left</th>
                    <th>AVG Unit Client Cost</th>
                    <th>AVG Unit Retail</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllReports->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($getAllReports->getResultArray() as $row) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['company'] ?></td>
                            <td class="text-center"><?= $row['total_unit'] ?></td>
                            <td class="text-center">$ <?= number_format($row['total_retail'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['client_cost'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['total_fulfilled'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['cost_left'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['avg_client_cost'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['avg_unit_retail'], 2) ?></td>

                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
    <!-- /blocks with chart -->
    <div class="row">
        <div class="col-xl-12">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title"> </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="percentage"></div>
                        <?php
                        if ($totalFulfilled->total_fulfilled != 0 || $totalInvest->total_client_cost != 0) {
                            $fulfilledPercent = ($totalFulfilled->total_fulfilled / $totalInvest->total_client_cost) * 100;
                            $clientCostPercent = 100 - $fulfilledPercent;
                        }

                        ?>
                        <script type="text/javascript">
                            // Initialize the echarts instance based on the prepared dom
                            var myChart = echarts.init(document.getElementById('percentage'));
                            // Specify the configuration items and data for the chart
                            option = {
                                title: {
                                    text: 'Cost Percentage',
                                    left: 'center'
                                },
                                tooltip: {
                                    trigger: 'item'
                                },
                                legend: {
                                    orient: 'vertical',
                                    left: 'left'
                                },
                                series: [{
                                    name: 'Total',
                                    type: 'pie',
                                    radius: '70%',
                                    data: [{
                                            value: <?= number_format($totalFulfilled->total_fulfilled, 2, '.', '') ?>,
                                            name: 'Fulfilled <?= number_format($fulfilledPercent, 1) ?>%'
                                        },
                                        {
                                            value: <?= number_format(($totalInvest->total_client_cost), 2, '.', '') ?>,
                                            name: 'Client Cost <?= number_format($clientCostPercent, 1) ?>%'
                                        }

                                    ],
                                    emphasis: {
                                        itemStyle: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }]
                            };

                            // Display the chart using the configuration items and data just specified.
                            myChart.setOption(option);
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>

    </div>
    <!-- Support tickets -->
    <div class="card">
        <div class="card-header header-elements-sm-inline">
            <h6 class="card-title">Support tickets</h6>
            <div class="header-elements">
                <a class="text-body daterange-ranges font-weight-semibold cursor-pointer dropdown-toggle">
                    <i class="icon-calendar3 mr-2"></i>
                    <span></span>
                </a>
            </div>
        </div>

        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <div id="">
                    <img src="<?= base_url() ?>/assets/images/pie-chart.png">
                </div>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">14,327 <span class="text-success font-size-sm font-weight-normal"><i class="icon-arrow-up12"></i> (+2.9%)</span></h5>
                    <span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">Jun 16, 10:00 am</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-alarm-add"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">1,132</h5>
                    <span class="text-muted">total tickets</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-spinner11"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">06:25:00</h5>
                    <span class="text-muted">response time</span>
                </div>
            </div>

            <div>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 50px">Due</th>
                        <th style="width: 300px;">User</th>
                        <th>Description</th>
                        <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-active table-border-double">
                        <td colspan="3">Active tickets</td>
                        <td class="text-right">
                            <span class="badge badge-primary badge-pill">24</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <h6 class="mb-0">12</h6>
                            <div class="font-size-sm text-muted line-height-1">hours</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-teal rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon">A</span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Annabelle Doney</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-primary mr-1"></span> Active</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div class="font-weight-semibold">[#1183] Workaround for OS X selects printing bug</div>
                                <span class="text-muted">Chrome fixed the bug several versions ago, thus rendering this...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-checkmark3 text-success"></i> Resolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <h6 class="mb-0">16</h6>
                            <div class="font-size-sm text-muted line-height-1">hours</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-primary rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold">Chris Macintyre</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-primary mr-1"></span> Active</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div class="font-weight-semibold">[#1249] Vertically center carousel controls</div>
                                <span class="text-muted">Try any carousel control and reduce the screen width below...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-checkmark3 text-success"></i> Resolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <h6 class="mb-0">20</h6>
                            <div class="font-size-sm text-muted line-height-1">hours</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-primary rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Robert Hauber</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-primary mr-1"></span> Active</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div class="font-weight-semibold">[#1254] Inaccurate small pagination height</div>
                                <span class="text-muted">The height of pagination elements is not consistent with...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-checkmark3 text-success"></i> Resolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <h6 class="mb-0">40</h6>
                            <div class="font-size-sm text-muted line-height-1">hours</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-warning rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Robert Hauber</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-primary mr-1"></span> Active</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div class="font-weight-semibold">[#1184] Round grid column gutter operations</div>
                                <span class="text-muted">Left rounds up, right rounds down. should keep everything...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-checkmark3 text-success"></i> Resolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-active table-border-double">
                        <td colspan="3">Resolved tickets</td>
                        <td class="text-right">
                            <span class="badge badge-success badge-pill">42</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <i class="icon-checkmark3 text-success"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-success rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Alan Macedo</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-success mr-1"></span> Resolved</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div>[#1046] Avoid some unnecessary HTML string</div>
                                <span class="text-muted">Rather than building a string of HTML and then parsing it...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-plus3 text-primary"></i> Unresolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <i class="icon-checkmark3 text-success"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-pink rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Brett Castellano</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-success mr-1"></span> Resolved</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div>[#1038] Update json configuration</div>
                                <span class="text-muted">The <code>files</code> property is necessary to override the files property...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-plus3 text-primary"></i> Unresolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <i class="icon-checkmark3 text-success"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-indigo rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold">Roxanne Forbes</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-success mr-1"></span> Resolved</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div>[#1034] Tooltip multiple event</div>
                                <span class="text-muted">Fix behavior when using tooltips and popovers that are...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-plus3 text-primary"></i> Unresolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Close issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-active table-border-double">
                        <td colspan="3">Closed tickets</td>
                        <td class="text-right">
                            <span class="badge badge-danger badge-pill">37</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <i class="icon-cross2 text-danger"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-indigo rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold">Mitchell Sitkin</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-danger mr-1"></span> Closed</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div>[#1040] Account for static form controls in form group</div>
                                <span class="text-muted">Resizes control label's font-size and account for the standard...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-plus3 text-primary"></i> Unresolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-spinner11 text-success"></i> Reopen issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <i class="icon-cross2 text-danger"></i>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <a href="#" class="btn btn-indigo rounded-pill btn-icon btn-sm">
                                        <span class="letter-icon"></span>
                                    </a>
                                </div>
                                <div>
                                    <a href="#" class="text-body font-weight-semibold letter-icon-title">Katleen Jensen</a>
                                    <div class="text-muted font-size-sm"><span class="badge badge-mark border-danger mr-1"></span> Closed</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-body">
                                <div>[#1038] Proper sizing of form control feedback</div>
                                <span class="text-muted">Feedback icon sizing inside a larger/smaller form-group...</span>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-undo"></i> Quick reply</a>
                                        <a href="#" class="dropdown-item"><i class="icon-history"></i> Full history</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item"><i class="icon-plus3 text-primary"></i> Unresolve issue</a>
                                        <a href="#" class="dropdown-item"><i class="icon-spinner11 text-success"></i> Reopen issue</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /support tickets -->
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/js/demo_pages/picker_date.js"></script>
<script src="<?= base_url() ?>/assets/js/plugins/pickers/daterangepicker.js"></script>
<script src="<?= base_url() ?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/demo_pages/datatables_basic.js"></script>

<script>
    $(document).ready(function() {
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Matching').css('color', 'green');
            } else
                $('#message').html('Not Matching').css('color', 'red');
        });
    });
</script>
<?= $this->endSection() ?>