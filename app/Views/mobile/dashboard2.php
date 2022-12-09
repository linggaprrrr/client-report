<?= $this->extend('mobile/layout/template') ?>

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
                        <h2 class="font-weight-bold mb-0">$ <?= number_format(0, 2) ?> <small class="text-success font-size-base ml-2"></small></h2>
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
                        <h2 class="font-weight-bold mb-0">$ <?= number_format(0, 2) ?><small class="text-danger font-size-base ml-2"></small></h2>
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
                        <h2 class="font-weight-bold mb-0"><?= (0 > 0) ? $totalUnit->total_unit : "0" ?></h2>
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
                        <h2 class="font-weight-bold mb-0">$ <?= number_format(0, 2) ?> <small class="text-danger font-size-base ml-2"></small></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header header-elements-sm-inline">
            <h6 class="card-title">Overview: <span><b></b></span></h6>
            <div class="header-elements">
                <form method="post" action="<?= base_url('/dashboard') ?>">
                    <select class="form-control" name="investdate" onchange="this.form.submit()">

                    </select>
                </form>
            </div>
        </div>
        <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-table2"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0"><?= "0" ?></h5>
                    <span class="text-muted">Total Unit</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cart-remove"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <?= number_format(0, 2) ?></h5>
                    <span class="text-muted">Total Fulfilled</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cube"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <?= "0" ?> <span class="text-success font-size-sm font-weight-normal">
                            AVG UNIT RETAIL ( $ <?= "0" ?> )
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

            </tbody>
        </table>
    </div>
    <!-- /blocks with chart -->
    <div class="row">
        <div class="col-xl-8">
            <!-- Multi level donut chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Brand By Quantity </h5>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="main"></div>

                        <script type="text/javascript">
                            // Main vars
                            var nameData = [],
                                valueData = [],
                                foregroundColor = '#1990FF',
                                backgroundColor = '#f5f5f5',
                                barWidth = 5;

                            var data = 0;

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
                                    left: '35%',
                                    right: '6%',
                                    bottom: '3%',
                                    containLabel: false
                                },
                                xAxis: {
                                    show: false
                                },

                                yAxis: [{
                                        type: 'category',
                                        data: '0',
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
                                            margin: 10,
                                            fontSize: 12,
                                            fontWeight: 500,
                                        }

                                    },
                                    {
                                        type: 'category',
                                        data: '0',
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
                                            margin: 20,
                                            fontSize: 14,
                                            fontWeight: 500,

                                        }

                                    },

                                ],

                                series: [{
                                        name: 'Total Qty',
                                        type: 'bar',
                                        stack: 'total',
                                        data: 0,
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
                                            value: 0,
                                            name: 'Fulfilled '
                                        },
                                        {
                                            value: 0,
                                            name: 'Client Cost '
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


<?= $this->endSection() ?>