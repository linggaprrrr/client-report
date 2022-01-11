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
                    <h5 class="font-weight-semibold mb-0">$ <?= ($totalUnit->total_unit != 0) ? number_format(($totalFulfilled->total_fulfilled / $totalUnit->total_unit), 2) : "0" ?> <span class="text-success font-size-sm font-weight-normal">
                            AVG UNIT RETAIL ( $ <?= ($totalUnit->total_unit != 0) ? number_format(($totalRetail->total_retail / $totalUnit->total_unit), 2) : "0" ?> )
                        </span></h5>

                    <span class="text-muted">AVG Unit Client Cost</span>

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
                    <th>Investment Date</th>
                    <th>Total Unit</th>
                    <th>Total Retail</th>
                    <th>Total Client Cost</th>
                    <th>Total Fulfilled</th>
                    <th>Total Cost Left</th>
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
                            <td class="text-center font-weight-bold">
                            <?php $newDate = date("M-d-Y", strtotime($row['investment_date'])); ?>
                                <?= strtoupper($newDate) ?>
                            </td>
                            <td class="text-center"><?= $row['total_unit'] ?></td>
                            <td class="text-center">$ <?= number_format($row['total_retail'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['client_cost'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['total_fulfilled'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['cost_left'], 2) ?></td>

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
                        } else {
                            $fulfilledPercent = 0;
                            $clientCostPercent = 0;
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
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/demo_pages/datatables_basic.js"></script>

<?= $this->endSection() ?>