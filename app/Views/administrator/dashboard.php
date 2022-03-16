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
                        <h2 class="font-weight-bold mb-0">$ <span class="total_client_cost">...</span> <small class="text-success font-size-base ml-2"></small></h2>
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
                        <h2 class="font-weight-bold mb-0">$ <span class="total_cost_left">...</span><small class="text-danger font-size-base ml-2"></small></h2>
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
                        <h2 class="font-weight-bold mb-0 total_unit">0</h2>
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
                        <h2 class="font-weight-bold mb-0 ">$ <span class="total_original">...</span> <small class="text-danger font-size-base ml-2"></small></h2>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart" style="height: 50px"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Financial Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <p class="mb-3"></p>

                    <div class="card card-table table-responsive shadow-none mb-0">
                        <table class="table table-bordered summary" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center">MONTH</th>
                                    <th class="text-center">CLIENT SPEND</th>
                                    <th class="text-center">CLIENT SPEND FULFILLED</th>
                                    <th class="text-center">% FULFILLED</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $month = array();
                                $percen = array();
                                ?>
                                <?php foreach ($finSummary as $sum) : ?>
                                    <tr>
                                        <td class="font-weight-bold" style="padding: 5px"><?= strtoupper($sum['month']) ?></td>
                                        <td class="text-center font-weight-bold" style="padding: 5px">$ <?= number_format($sum['spend'], 2) ?></td>
                                        <td class="text-center font-weight-bold" style="padding: 5px">$ <?= number_format($sum['fulfill'], 2) ?></td>
                                        <?php if ($sum['fulfill'] != 0) : ?>
                                            <td class="text-center font-weight-bold" style="padding: 5px"><?= number_format($fullfilled = ($sum['fulfill'] / $sum['spend']) * 100, 2) ?>%</td>
                                        <?php else : ?>
                                            <td class="text-center font-weight-bold" style="padding: 5px"><?= number_format($fullfilled = 0, 2) ?>%</td>
                                        <?php endif ?>
                                    </tr>
                                    <?php
                                    array_push($month, $sum['month']);
                                    array_push($percen, number_format($fullfilled, 2));
                                    ?>
                                <?php endforeach ?>
                                <?php
                                $month = json_encode($month);
                                $percen = json_encode($percen);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="chart has-fixed-height" id="summ"></div>
                    <script type="text/javascript">
                        // Initialize the echarts instance based on the prepared dom
                        var myChart = echarts.init(document.getElementById('summ'));
                        // Specify the configuration items and data for the chart
                        option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'shadow'
                                }
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [{
                                type: 'category',
                                data: <?= $month ?>,
                                axisTick: {
                                    alignWithLabel: true
                                }
                            }],
                            yAxis: [{
                                type: 'value'
                            }],
                            series: [{
                                name: 'Direct',
                                type: 'bar',
                                barWidth: '60%',
                                data: <?= $percen ?>
                            }]
                        };

                        // Display the chart using the configuration items and data just specified.
                        myChart.setOption(option);
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Box Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5">
                    <p class="mb-3"></p>

                    <div class="card card-table table-responsive shadow-none mb-0">
                        <table class="table table-bordered summary" style="font-size: 11px">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Total Shipped</th>
                                    <th class="text-center">Total Remanifested</th>
                                    <th class="text-center">Total Reassigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($boxStatSummary as $boxSum) : ?>
                                    <tr>
                                        <td class="text-center font-weight-bold" style="padding: 5px"><?= $boxSum['date'] ?></td>
                                        <td class="text-center font-weight-bold" style="padding: 5px">$ <?= $boxSum['shipped'] ?></td>
                                        <td class="text-center font-weight-bold" style="padding: 5px">$ <?= $boxSum['remanifested'] ?></td>
                                        <td class="text-center font-weight-bold" style="padding: 5px">$ <?= $boxSum['reassigned'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                                
                            </tbody>
                        </table>
                    </div>
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
                    <h5 class="font-weight-semibold mb-0 total_unit">0</h5>
                    <span class="text-muted">Total Unit</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cart-remove"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <span class="total_fulfilled">...</span></h5>
                    <span class="text-muted">Total Fulfilled</span>

                </div>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                    <i class="icon-cube"></i>
                </a>
                <div class="ml-3">
                    <h5 class="font-weight-semibold mb-0">$ <span class="avg_retail"></span> <span class="text-success font-size-sm font-weight-normal">
                            AVG UNIT RETAIL ( $ <span class="avg_client_cost"></span> )
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
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getAllReports->getNumRows() > 0) : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($getAllReports->getResultArray() as $row) : ?>
                        <?php if ($row['status'] == 'complete') : ?>
                            <tr class="table-info">
                            <?php else : ?>
                            <tr class="table-active">
                            <?php endif ?>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['company'] ?></td>
                            <td class="text-center font-weight-bold">
                                <?php $newDate = date("M-d-Y", strtotime($row['investment_date'])); ?>
                                <?= strtoupper($newDate) ?>
                            </td>
                            <td class="text-center"><?= $row['total_unit'] ?? 0 ?></td>
                            <td class="text-center">$ <?= number_format($row['total_retail'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['client_cost'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['total_fulfilled'], 2) ?></td>
                            <td class="text-center">$ <?= number_format($row['cost_left'], 2) ?></td>
                            <?php if ($row['status'] == 'complete') : ?>
                                <td class="text-center font-weight-bold"><span class="badge badge-primary">COMPLETE</span></td>
                            <?php else : ?>
                                <td class="text-center font-weight-bold"><span class="badge badge-secondary">WORKING</span></td>
                            <?php endif ?>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
            </tbody>
        </table>
    </div>
    <!-- /blocks with chart -->
    <div class="row">
        <div class="col-xl-6">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Top 10 Investor (Ready To Assign)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="assign"></div>
                        <script type="text/javascript">
                            const userRAssign = [];
                            const totalInvestmentAssign = [];
                            const costLeft = [];
                            $.get('/get-top-readyassign', function(data) {
                                const assign = JSON.parse(data);
                                for (var i = 0; i < assign.length; i++) {
                                    userRAssign.push(assign[i]['fullname']);
                                    totalInvestmentAssign.push(assign[i]['amount']);
                                    costLeft.push(assign[i]['cost_left']);
                                }
                                var nameData = [],
                                    valueData = [],
                                    foregroundColor = '#1990FF',
                                    backgroundColor = '#f5f5f5',
                                    barWidth = 5;

                                var data = totalInvestmentAssign;

                                // Initialize the echarts instance based on the prepared dom
                                var myChart = echarts.init(document.getElementById('assign'));
                                // Specify the configuration items and data for the chart
                                option = {

                                    tooltip: {
                                        trigger: 'axis',
                                        axisPointer: {
                                            type: 'shadow'
                                        }
                                    },
                                    legend: {},
                                    grid: {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                    },
                                    xAxis: {
                                        type: 'value',
                                        boundaryGap: [0, 0.01]
                                    },
                                    yAxis: {
                                        type: 'category',
                                        data: userRAssign
                                    },
                                    series: [{
                                            name: 'Total Amount',
                                            type: 'bar',
                                            data: totalInvestmentAssign
                                        },
                                        {
                                            name: 'Total Cost Left',
                                            type: 'bar',
                                            data: costLeft
                                        }
                                    ]
                                };

                                // Display the chart using the configuration items and data just specified.
                                myChart.setOption(option);
                            });
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>
        <div class="col-xl-6">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Total Category/Item</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="category"></div>
                        <script type="text/javascript">
                            const tempCat = [];
                            const categoryItem = {};
                            $.get('/get-total-cat', function(data) {
                                const cat = JSON.parse(data);
                                // for (var i = 0; i < cat.length; i++) {
                                //     categoryItem.value = cat[i]['qty'];
                                //     categoryItem.category = cat[i]['category'];
                                //     tempCat.push(categoryItem);
                                // }
                                // console.log(tempCat);
                                // Initialize the echarts instance based on the prepared dom
                                var myChart = echarts.init(document.getElementById('category'));
                                // Specify the configuration items and data for the chart
                                option = {
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        top: '5%',
                                        left: 'center'
                                    },
                                    series: [{
                                        name: 'Total',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: false,
                                            position: 'center'
                                        },
                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: '18',
                                                fontWeight: 'bold'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: cat
                                    }]
                                };

                                // Display the chart using the configuration items and data just specified.
                                myChart.setOption(option);
                            });
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <!-- Multi level donut chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top 10 Investor</h5>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="topInvestment"></div>
                        <script type="text/javascript">
                            const userTopInvestment = []
                            const amountTopInvestment = [];
                            const currency = [];
                            $.get('/get-top-investments', function(data) {
                                const invest = JSON.parse(data);
                                for (var i = 0; i < invest.length; i++) {
                                    userTopInvestment.push(invest[i]['fullname']);
                                    amountTopInvestment.push(invest[i]['amount']);
                                    currency.push(invest[i]['currency']);
                                }
                                var nameData = [],
                                    valueData = [],
                                    foregroundColor = '#1990FF',
                                    backgroundColor = '#f5f5f5',
                                    barWidth = 5;

                                var data = amountTopInvestment;

                                // Initialize the echarts instance based on the prepared dom
                                var myChart = echarts.init(document.getElementById('topInvestment'));
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
                                        left: '20%',
                                        right: '20%',
                                        bottom: '3%',
                                        containLabel: false
                                    },
                                    xAxis: {
                                        show: false
                                    },

                                    yAxis: [{
                                            type: 'category',
                                            data: userTopInvestment,
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
                                            data: currency,
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
                                            name: 'Total Amount',

                                            type: 'bar',
                                            stack: 'total',
                                            data: amountTopInvestment,
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

                            });
                            // Main vars
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>
        <div class="col-xl-6">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Top 10 Continuity Investor </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="continuity"></div>
                        <script type="text/javascript">
                            const userContinuity = [];
                            const totalInvestment = [];
                            $.get('/get-top-continuity', function(data) {
                                const continuity = JSON.parse(data);
                                for (var i = 0; i < continuity.length; i++) {
                                    userContinuity.push(continuity[i]['fullname']);
                                    totalInvestment.push(continuity[i]['total']);
                                }
                                var nameData = [],
                                    valueData = [],
                                    foregroundColor = '#1990FF',
                                    backgroundColor = '#f5f5f5',
                                    barWidth = 5;

                                var data = totalInvestment;

                                // Initialize the echarts instance based on the prepared dom
                                var myChart = echarts.init(document.getElementById('continuity'));
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
                                        left: '20%',
                                        right: '20%',
                                        bottom: '3%',
                                        containLabel: false
                                    },
                                    xAxis: {
                                        show: false
                                    },

                                    yAxis: [{
                                            type: 'category',
                                            data: userContinuity,
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
                                            data: totalInvestment,
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
                                            name: 'Total Investment',

                                            type: 'bar',
                                            stack: 'total',
                                            data: totalInvestment,
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
                            });
                        </script>
                    </div>
                </div>
            </div>
            <!-- /multi level donut chart -->

        </div>

    </div>
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
    <div class="row">
        <div class="col-xl-12">
            <!-- Multi level donut chart -->
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title"> Cost Left Under $1000</h5>
                </div>
                <div class="card-body">
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
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($getAllReports->getNumRows() > 0) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($costUnderOnek->getResultArray() as $row) : ?>
                                    <?php if ($row['status'] == 'complete') : ?>
                                        <tr class="table-info">
                                        <?php else : ?>
                                        <tr class="table-active">
                                        <?php endif ?>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row['fullname'] ?></td>
                                        <td><?= $row['company'] ?></td>
                                        <td class="text-center font-weight-bold">
                                            <?php $newDate = date("M-d-Y", strtotime($row['investment_date'])); ?>
                                            <?= strtoupper($newDate) ?>
                                        </td>
                                        <td class="text-center"><?= $row['total_unit'] ?? 0 ?></td>
                                        <td class="text-center">$ <?= number_format($row['total_retail'], 2) ?></td>
                                        <td class="text-center">$ <?= number_format($row['client_cost'], 2) ?></td>
                                        <td class="text-center">$ <?= number_format($row['total_fulfilled'], 2) ?></td>
                                        <td class="text-center">$ <?= number_format($row['cost_left'], 2) ?></td>
                                        <?php if ($row['status'] == 'complete') : ?>
                                            <td class="text-center font-weight-bold"><span class="badge badge-primary">COMPLETE</span></td>
                                        <?php else : ?>
                                            <td class="text-center font-weight-bold"><span class="badge badge-secondary">WORKING</span></td>
                                        <?php endif ?>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                        </tbody>
                    </table>
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