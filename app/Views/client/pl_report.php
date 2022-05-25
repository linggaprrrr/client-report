<?= $this->extend('mobile/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Well done!</span> Ticket Successfully Created! <a href="#" class="alert-link"></a>
        </div>
    <?php endif ?>

    <?php
    $totalGrossProfit = 0;
    $totalGrossSale = 0;
    $totalNetProfit = 0;
    $no = 0;
    ?>
    <div class="text-right mb-4">
        <?php if (!empty($file)) : ?>
            <a href="<?= $file->link ?>" target="_blank" class=" btn btn-teal"><i class="icon-file-download mr-2"></i> Download Report</a>
        <?php endif ?>
    </div>
    <div class="row"">
        <?php if ($plReport->getNumRows() > 0) : ?>
            <?php foreach ($plReport->getResultArray() as $row) : ?>
                <div class="col-xl-12">
                        <!-- Multi level donut chart -->
                        <?php if ($row['last_year'] != "0" ) :?>
                                <div class="card" >
                                    <div class="card-header">
                                        <h5 class="card-title"><b><?= strtoupper($row['chart']) ?></b> </h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="chart-container">
                                            <?php
                                            $temp = array($row['last_year'], $row['jan'], $row['feb'], $row['mar'], $row['apr'], $row['may'], $row['jun'], $row['jul'], $row['aug'], $row['sep'], $row['oct'], $row['nov'], $row['dec']);
                                            $total = array_sum($temp) - $row['last_year'];
                                            if ($total < 0) {
                                                $avg = 0;                                                
                                            } else {
                                                $avg = $total / count(array_filter($temp));
                                            }
                                            if ($row['type'] == 'percentage') {
                                                $total = round($avg, 2);
                                                $lastYear = round($row['last_year'], 2);
                                                $jan = ($row['jan'] == 0) ? null : number_format($row['jan'], 2);
                                                $feb = ($row['feb'] == 0) ? null : number_format($row['feb'], 2);
                                                $mar = ($row['mar'] == 0) ? null : number_format($row['mar'], 2);
                                                $apr = ($row['apr'] == 0) ? null : number_format($row['apr'], 2);
                                                $may = ($row['may'] == 0) ? null : number_format($row['may'], 2);
                                                $jun = ($row['jun'] == 0) ? null : number_format($row['jun'], 2);
                                                $jul = ($row['jul'] == 0) ? null : number_format($row['jul'], 2);
                                                $aug = ($row['aug'] == 0) ? null : number_format($row['aug'], 2);
                                                $sep = ($row['sep'] == 0) ? null : number_format($row['sep'], 2);
                                                $oct = ($row['oct'] == 0) ? null : number_format($row['oct'], 2);
                                                $nov = ($row['nov'] == 0) ? null : number_format($row['nov'], 2);
                                                $dec = ($row['dec'] == 0) ? null : number_format($row['dec'], 2);
                                            } else {
                                                $avg = round($avg);
                                                $lastYear = round($row['last_year']);
                                                $jan = ($row['jan'] == 0) ? null : round($row['jan']);
                                                $feb = ($row['feb'] == 0) ? null : round($row['feb']);
                                                $mar = ($row['mar'] == 0) ? null : round($row['mar']);
                                                $apr = ($row['apr'] == 0) ? null : round($row['apr']);
                                                $may = ($row['may'] == 0) ? null : round($row['may']);
                                                $jun = ($row['jun'] == 0) ? null : round($row['jun']);
                                                $jul = ($row['jul'] == 0) ? null : round($row['jul']);
                                                $aug = ($row['aug'] == 0) ? null : round($row['aug']);
                                                $sep = ($row['sep'] == 0) ? null : round($row['sep']);
                                                $oct = ($row['oct'] == 0) ? null : round($row['oct']);
                                                $nov = ($row['nov'] == 0) ? null : round($row['nov']);
                                                $dec = ($row['dec'] == 0) ? null : round($row['dec']);
                                            }
                                           
                                            $data = array("{value: ". $lastYear .", itemStyle: {color: '#a90000'}}", $jan, $feb, $mar, $apr, $may, $jun, $jul, $may, $sep, $oct, $nov, $dec, round($avg, 2));
                                            $chartData = json_encode($data);                                            
                                            $chartData = str_replace('"','', (string) $chartData);
                                            $chartId = "viz_" . $no;
                                            $color = [
                                                '#1990FF', '#618685',
                                                '#3b3a30', '#618685',
                                                '#563f46', '#618685',
                                                '#838060', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                            ];
                                            
                                            ?>
                                            <div class="chart has-fixed-height" id="<?= $chartId ?>"></div>
                                            <script type="text/javascript">
                                                var nameData = [],
                                                    valueData = [],
                                                    foregroundColor = '<?= $color[$no] ?>';
                                                // Initialize the echarts instance based on the prepared dom
                                                var myChart = echarts.init(document.getElementById('<?= $chartId ?>'));
                                                // Specify the configuration items and data for the chart
                                                option = {
                                                    title: {
                                                        text: 'Total',
                                                        <?php if ($row['type'] == 'currency') : ?>
                                                            subtext: '$ <?= number_format($total, 0) ?>'
                                                        <?php elseif ($row['type'] == 'percentage') : ?>
                                                            subtext: '<?= number_format($total, 2) ?> %'
                                                        <?php else : ?>
                                                            subtext: '<?= $total ?>'
                                                        <?php endif ?>,
                                                        left: 'right',
                                                        textStyle: {
                                                            color: '#252b36',
                                                            fontStyle: 'italic',
                                                            fontSize: 24

                                                        },
                                                        subtextStyle: {
                                                            fontWeight: 'bolder',
                                                            fontSize: 18
                                                        }
                                                    },
                                                    textStyle: {
                                                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                                                        fontSize: 11
                                                    },
                                                    tooltip: {
                                                        trigger: 'axis',
                                                        axisPointer: {
                                                            type: 'cross'
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
                                                        data: ['AVG LY', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'AVG'],
                                                        axisTick: {
                                                            alignWithLabel: true
                                                        },
                                                        axisLabel: {
                                                            fontSize: 11,
                                                            fontWeight: 'bold',
                                                            rotate: 60
                                                        }


                                                    }],
                                                    yAxis: [{
                                                        type: 'value',

                                                        axisLabel: {
                                                            fontSize: 11,
                                                            fontWeight: 'bold',
                                                            <?php if ($row['type'] == "percentage") : ?>
                                                                formatter: '{value}%'
                                                            <?php elseif ($row['type'] == "currency") : ?>
                                                                formatter: '$ {value}'
                                                            <?php else : ?>
                                                                formatter: '{value}'
                                                            <?php endif ?>

                                                        }
                                                    }],
                                                    series: [{
                                                        name: 'Total',
                                                        type: 'bar',

                                                        data: <?= $chartData ?>,

                                                        label: {
                                                            verticalAlign: 'top',
                                                            fontWeight: 'bold',
                                                            position: 'insideTop',
                                                            <?php if ($row['type'] == "percentage") : ?>
                                                                show: true,
                                                                formatter: '{c}%'
                                                            <?php elseif ($row['type'] == "currency") : ?>
                                                                show: true,
                                                                formatter: '$ {c}'
                                                            <?php else : ?>
                                                                show: true,
                                                            <?php endif ?>
                                                        },
                                                        yaxis: { 
                                                            min: 0.5 
                                                        },

                                                        itemStyle: {
                                                            color: foregroundColor,
                                                            barBorderRadius: 0
                                                        },
                                                        z: 10,
                                                        showBackground: false,

                                                    }]
                                                };

                                                // Display the chart using the configuration items and data just specified.
                                                myChart.setOption(option);
                                            </script>
                                        </div>
                                    </div>
                                </div>   
                            <?php else : ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title"><b><?= strtoupper($row['chart']) ?></b> </h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="chart-container">
                                            <?php
                                            $temp = array($row['jan'], $row['feb'], $row['mar'], $row['apr'], $row['may'], $row['jun'], $row['jul'], $row['aug'], $row['sep'], $row['oct'], $row['nov'], $row['dec']);
                                            $total = array_sum($temp);
                                            if ($total < 0) {
                                                $avg = 0;                                                
                                            } else {
                                                $avg = $total / count(array_filter($temp));
                                            }
                                                                                       
                                            if ($row['type'] == 'percentage') {
                                                $total = round($avg, 2);
                                                $jan = ($row['jan'] == 0) ? null : number_format($row['jan'], 2);
                                                $feb = ($row['feb'] == 0) ? null : number_format($row['feb'], 2);
                                                $mar = ($row['mar'] == 0) ? null : number_format($row['mar'], 2);
                                                $apr = ($row['apr'] == 0) ? null : number_format($row['apr'], 2);
                                                $may = ($row['may'] == 0) ? null : number_format($row['may'], 2);
                                                $jun = ($row['jun'] == 0) ? null : number_format($row['jun'], 2);
                                                $jul = ($row['jul'] == 0) ? null : number_format($row['jul'], 2);
                                                $aug = ($row['aug'] == 0) ? null : number_format($row['aug'], 2);
                                                $sep = ($row['sep'] == 0) ? null : number_format($row['sep'], 2);
                                                $oct = ($row['oct'] == 0) ? null : number_format($row['oct'], 2);
                                                $nov = ($row['nov'] == 0) ? null : number_format($row['nov'], 2);
                                                $dec = ($row['dec'] == 0) ? null : number_format($row['dec'], 2);
                                            } else {
                                                $avg = round($avg);
                                                $jan = ($row['jan'] == 0) ? null : round($row['jan']);
                                                $feb = ($row['feb'] == 0) ? null : round($row['feb']);
                                                $mar = ($row['mar'] == 0) ? null : round($row['mar']);
                                                $apr = ($row['apr'] == 0) ? null : round($row['apr']);
                                                $may = ($row['may'] == 0) ? null : round($row['may']);
                                                $jun = ($row['jun'] == 0) ? null : round($row['jun']);
                                                $jul = ($row['jul'] == 0) ? null : round($row['jul']);
                                                $aug = ($row['aug'] == 0) ? null : round($row['aug']);
                                                $sep = ($row['sep'] == 0) ? null : round($row['sep']);
                                                $oct = ($row['oct'] == 0) ? null : round($row['oct']);
                                                $nov = ($row['nov'] == 0) ? null : round($row['nov']);
                                                $dec = ($row['dec'] == 0) ? null : round($row['dec']);
                                            }
                                            $data = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $may, $sep, $oct, $nov, $dec, round($avg, 2));
                                            $chartData = json_encode($data);
                                            $chartData = str_replace('"','', (string) $chartData);
                                            $chartId = "viz_" . $no;
                                            $color = [
                                                '#1990FF', '#618685',
                                                '#3b3a30', '#618685',
                                                '#563f46', '#618685',
                                                '#838060', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                            ];
                                            ?>
                                            <div class="chart has-fixed-height" id="<?= $chartId ?>"></div>
                                            <script type="text/javascript">
                                                var nameData = [],
                                                    valueData = [],
                                                    foregroundColor = '<?= $color[$no] ?>';
                                                // Initialize the echarts instance based on the prepared dom
                                                var myChart = echarts.init(document.getElementById('<?= $chartId ?>'));
                                                // Specify the configuration items and data for the chart
                                                option = {
                                                    title: {
                                                        text: 'Total',
                                                        <?php if ($row['type'] == 'currency') : ?>
                                                            subtext: '$ <?= number_format($total, 0) ?>'
                                                        <?php elseif ($row['type'] == 'percentage') : ?>
                                                            subtext: '<?= number_format($total, 2) ?> %'
                                                        <?php else : ?>
                                                            subtext: '<?= $total ?>'
                                                        <?php endif ?>,
                                                        left: 'right',
                                                        textStyle: {
                                                            color: '#252b36',
                                                            fontStyle: 'italic',
                                                            fontSize: 24

                                                        },
                                                        subtextStyle: {
                                                            fontWeight: 'bolder',
                                                            fontSize: 18
                                                        }
                                                    },
                                                    textStyle: {
                                                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                                                        fontSize: 14
                                                    },
                                                    tooltip: {
                                                        trigger: 'axis',
                                                        axisPointer: {
                                                            type: 'cross'
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
                                                        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'AVERAGE'],
                                                        
                                                        axisLabel: {
                                                            fontSize: 12,
                                                            fontWeight: 'bold',
                                                            overflow: "truncate",
                                                        }


                                                    }],
                                                    yAxis: [{
                                                        type: 'value',

                                                        axisLabel: {
                                                            fontSize: 14,
                                                            fontWeight: 'bold',
                                                            <?php if ($row['type'] == "percentage") : ?>
                                                                formatter: '{value}%'
                                                            <?php elseif ($row['type'] == "currency") : ?>
                                                                formatter: '$ {value}'
                                                            <?php else : ?>
                                                                formatter: '{value}'
                                                            <?php endif ?>

                                                        }
                                                    }],
                                                    series: [{
                                                        name: 'Total',
                                                        type: 'bar',

                                                        data: <?= $chartData ?>,

                                                        label: {
                                                            verticalAlign: 'top',
                                                            fontWeight: 'bold',
                                                            position: 'insideTop',
                                                            <?php if ($row['type'] == "percentage") : ?>
                                                                show: true,
                                                                formatter: '{c}%'
                                                            <?php elseif ($row['type'] == "currency") : ?>
                                                                show: true,
                                                                formatter: '$ {c}'
                                                            <?php else : ?>
                                                                show: true,
                                                            <?php endif ?>
                                                        },
                                                        yaxis: { 
                                                            min: 0.5 
                                                        },

                                                        itemStyle: {
                                                            color: foregroundColor,
                                                            barBorderRadius: 0
                                                        },
                                                        z: 10,
                                                        showBackground: false,

                                                    }]
                                                };

                                                // Display the chart using the configuration items and data just specified.
                                                myChart.setOption(option);
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>    
                        
                        <!-- /multi level donut chart -->

                    </div>
                <?php $no++; ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>


<?= $this->endSection() ?>