<?= $this->extend('mobile/layout/template') ?>
<!-- Font Awesome -->

<?= $this->section('content') ?>

<div class="content">

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Well done!</span> Ticket Successfully Created! <a href="#" class="alert-link"></a>
        </div>
    <?php endif ?>
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

    </div>
    <div class="card">


        <!--/Blue select-->
        <div class="card-header header-elements-sm-inline">
            <h6 class="card-title">Overview: <span><b>RETAIL (NEW WITH TAGS) </b></span>
                <?php $temp = $getAllReports->getRowArray(0) ?>

            </h6>
            <div class="header-elements">
                <form method="get" action="<?= base_url('/mobile/dashboard') ?>" class="form-inline">
                    <div class="form-group row">
                        <label class="font-weight-bold mr-2">Order Date: </label>
                        <div class="form-group" style="width: 200px; text-align-last:center; font-weight: 700; font-size: 12px;text-transform: uppercase;">
                            <select class="form-control select-search" name="investdate" onchange="this.form.submit()" data-fouc>
                                <?php if (!empty($investDate)) : ?>
                                    <?php $idx = 1  ?>
                                    <?php foreach ($investDate->getResultArray() as $row) : ?>
                                        <?php $newDate = date("M-d-Y", strtotime($row['date'])); ?>
                                        <?php if ($row['id'] == $lastInvestment->id) : ?>
                                            <option value="<?= $row['id'] ?>" selected><?= strtoupper($newDate) ?></option>
                                        <?php else : ?>
                                            <option value="<?= $row['id'] ?>"><?= strtoupper($newDate) ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($temp['link'])) : ?>
                        <div class="form-group row ml-4">
                            <a href="<?= $temp['link'] ?>" class="btn btn-secondary" target="_blank"><i class="icon-google-drive mr-2"></i> Google Sheet</a>
                        </div>
                    <?php endif ?>

                </form>

            </div>

        </div>
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
                    <th style="width: 5%">No</th>
                    <th>Item Description</th>
                    <th style="width: 20%;">Vendor Name</th>
                    <th class="text-center" style="width: 5%;">Qty</th>
                    <th class="text-center " style="width: 10%;">Retail</th>
                    <th class="text-center" style="width: 10%;">Total</th>
                    <th class="text-center " style="width: 10%;">Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllReports->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($getAllReports->getResultArray() as $row) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <a href="#" class="text-body">
                                    <div class="font-weight-semibold"><?= $row['item_description'] ?></div>
                                    <span class="text-muted">SKU: <?= $row['sku'] ?></span>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <a href="#" class="text-body font-weight-semibold letter-icon-title"><?= $row['vendor'] ?></a>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <?= $row['qty'] ?>
                            </td>
                            <td class="text-center">$ <?= $row['retail_value'] ?></td>
                            <td class="text-center">$ <?= $row['original_value'] ?></td>
                            <td class="text-center">$ <?= $row['cost'] ?></td>

                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
    <!-- /blocks with chart -->
    <div class="row">
        <div class="col-xl-8">
            <!-- Multi level donut chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Brand By Total Quantity </h5>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="main"></div>
                        <?php
                        $vendorNames = array();
                        $vendorQty = array();
                        foreach (array_reverse($getVendorName->getResultArray()) as $vendor) {
                            if (strlen($vendor['vendor']) > 17) 
                                $str = substr($vendor['vendor'], 0, 14) . '...';
                            else 
                                $str = $vendor['vendor'];
                            array_push($vendorNames, $str);
                            array_push($vendorQty, $vendor['qty']);
                        }

                        $vendorNames = json_encode($vendorNames);
                        $vendorQty = json_encode($vendorQty);
                        ?>
                        <script type="text/javascript">
                            // Main vars
                            var nameData = [],
                                valueData = [],
                                foregroundColor = '#1990FF',
                                backgroundColor = '#f5f5f5',
                                barWidth = 5;

                            var data = <?= $vendorQty ?>;

                            // Initialize the echarts instance based on the prepared dom
                            var myChart = echarts.init(document.getElementById('main'));
                            // Specify the configuration items and data for the chart
                            option = {
                                textStyle: {
                                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                                    fontSize: 14
                                },
                                // Chart grid

                                tooltip: {
                                    trigger: 'axis',
                                    show: true,
                                    trigger: 'item',
                                    padding: [20, 15],
                                    axisPointer: {
                                        // Use axis to trigger tooltip
                                        type: 'shadow' // 'shadow' as default; can also be 'line' or 'shadow'
                                    }
                                },
                                legend: {},
                                grid: {
                                    left: '32%',
                                    right: '10%',
                                    bottom: '3%',
                                    containLabel: false
                                },
                                xAxis: {
                                    show: false
                                },

                                yAxis: [{
                                        type: 'category',
                                        data: <?= $vendorNames ?>,
                                        axisLine: {
                                            show: false
                                        },
                                        splitLine: {
                                            show: false
                                        },
                                        axisTick: {
                                            show: false
                                        },
                                        axisLabel: {
                                            margin: 5,
                                            fontSize: 9,
                                            fontWeight: 500,
                                        }

                                    },
                                    {
                                        type: 'category',
                                        data: <?= $vendorQty ?>,
                                        axisLine: {
                                            show: false
                                        },
                                        splitLine: {
                                            show: false
                                        },
                                        axisTick: {
                                            show: false
                                        },
                                        axisLabel: {
                                            align: 'left',
                                            margin: 5,
                                            fontSize: 9,
                                            fontWeight: 500,

                                        }

                                    },

                                ],

                                series: [{
                                        name: 'Total Qty ( Top 15 )',

                                        type: 'bar',
                                        stack: 'total',
                                        data: <?= $vendorQty ?>,
                                        barWidth: barWidth,
                                        itemStyle: {
                                            color: foregroundColor,
                                            barBorderRadius: 30
                                        },
                                        z: 10,
                                        showBackground: true,
                                        backgroundStyle: {
                                            barBorderRadius: 30,
                                            color: backgroundColor
                                        }
                                    },


                                ]
                            };

                            // Display the chart using the configuration items and data just specified.
                            myChart.setOption(option);
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>
        <div class="col-xl-4">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Cost Percentage </h5>
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
                                    radius: '50%',
                                    data: [{
                                            value: <?= number_format($totalFulfilled->total_fulfilled, 2, '.', '') ?>,
                                            name: 'Fulfilled <?= number_format($fulfilledPercent, 1) ?>%'
                                        },
                                        {
                                            value: <?= number_format(($totalInvest->total_client_cost), 2, '.', '') ?>,
                                            name: 'Remaining <?= number_format($clientCostPercent, 1) ?>%'
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
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>
<script src="/assets/js/demo_pages/form_select2.js"></script>
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>

<?= $this->endSection() ?>