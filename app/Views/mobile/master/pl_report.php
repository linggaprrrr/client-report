<?= $this->extend('mobile/master/layout/template') ?>

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
                                            $avg = $total / (count(array_filter($temp)) - 1 );

                                            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                                            $usd = $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'EUR');
                                            $usd = $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
                                         
                                            if ($row['type'] == 'percentage') {
                            
                                                $total = round($avg, 2);
                                                $lastYear = round($row['last_year'] * 100);
                                                $avg = ($row['avg'] == 0) ? null : number_format($row['avg'] * 100, 0);
                                                $jan = ($row['jan'] == 0) ? null : number_format($row['jan'] * 100, 0);
                                                $feb = ($row['feb'] == 0) ? null : number_format($row['feb'] * 100, 0);
                                                $mar = ($row['mar'] == 0) ? null : number_format($row['mar'] * 100, 0);
                                                $apr = ($row['apr'] == 0) ? null : number_format($row['apr'] * 100, 0);
                                                $may = ($row['may'] == 0) ? null : number_format($row['may'] * 100, 0);
                                                $jun = ($row['jun'] == 0) ? null : number_format($row['jun'] * 100, 0);
                                                $jul = ($row['jul'] == 0) ? null : number_format($row['jul'] * 100, 0);
                                                $aug = ($row['aug'] == 0) ? null : number_format($row['aug'] * 100, 0);
                                                $sep = ($row['sep'] == 0) ? null : number_format($row['sep'] * 100, 0);
                                                $oct = ($row['oct'] == 0) ? null : number_format($row['oct'] * 100, 0);
                                                $nov = ($row['nov'] == 0) ? null : number_format($row['nov'] * 100, 0);
                                                $dec = ($row['dec'] == 0) ? null : number_format($row['dec'] * 100, 0);
                                            } else {
                                                $avg = ($row['avg'] == 0) ? null : round($row['avg']);  
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
                                           
                                            $data = array("{value: ". $lastYear .", itemStyle: {color: '#a90000'}}", $jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec, $avg);
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
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                            ];
                                            
                                            ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Last Year</th>
                                                    <th scope="col" class="text-center">Jan</th>
                                                    <th scope="col" class="text-center">Feb</th>
                                                    <th scope="col" class="text-center">Mar</th>
                                                    <th scope="col" class="text-center">Apr</th>
                                                    <th scope="col" class="text-center">May</th>
                                                    <th scope="col" class="text-center">Jun</th>
                                                    <th scope="col" class="text-center">Jul</th>
                                                    <th scope="col" class="text-center">Aug</th>
                                                    <th scope="col" class="text-center">Sep</th>
                                                    <th scope="col" class="text-center">Oct</th>
                                                    <th scope="col" class="text-center">Nov</th>
                                                    <th scope="col" class="text-center">Dec</th>
                                                    <th scope="col" class="text-center">Average</th>
                                                    <th scope="col" class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($row['type'] == 'currency') : ?>
                                                    <tr>
                                                        <td class="text-center"><?= ($row['last_year'] == 0) ? '-' : $fmt->formatCurrency($row['last_year'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : $fmt->formatCurrency($row['jan'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : $fmt->formatCurrency($row['feb'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : $fmt->formatCurrency($row['mar'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : $fmt->formatCurrency($row['apr'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= $fmt->formatCurrency($avg, 'USD'); ?></td>
                                                        <td class="text-center"><?= $fmt->formatCurrency($total, 'USD'); ?></td>
                                                        
                                                    </tr>
                                                <?php elseif ($row['type'] == 'percentage') : ?>
                                                    <tr>
                                                        <td class="text-center"><?= ($row['last_year'] == 0) ? '-' : number_format($row['last_year'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : number_format($row['jan'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : number_format($row['feb'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : number_format($row['mar'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : number_format($row['apr'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : number_format($row['may'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : number_format($row['jun'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : number_format($row['jul'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : number_format($row['aug'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : number_format($row['sep'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : number_format($row['oct'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : number_format($row['nov'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : number_format($row['dec'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= number_format($avg, 0) ?>%</td>
                                                        <td class="text-center">-</td>
                                                    </tr>
                                                <?php else : ?>
                                                    <tr>
                                                        <td class="text-center"><?= ($row['last_year'] == 0) ? '-' : round($row['last_year']) ?></td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : round($row['jan']) ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : round($row['feb']) ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : round($row['mar']) ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : round($row['apr']) ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : round($row['may']) ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : round($row['jun']) ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : round($row['jul']) ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : round($row['aug']) ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : round($row['sep']) ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : round($row['oct']) ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : round($row['nov']) ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : round($row['dec']) ?></td>
                                                        <td class="text-center"><?= round($avg) ?></td>
                                                        <td class="text-center"><?= round($total) ?></td>
                                                    </tr>
                                                <?php endif ?>
                                            </tbody>
                                        </table>
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
                                                        
                                                        <?php if ($row['type'] == 'currency') : ?>
                                                            text: 'Total',
                                                            subtext: '$ <?= number_format($total, 0) ?>'
                                                        <?php elseif ($row['type'] == 'percentage') : ?>
                                                            text: '',
                                                            subtext: ''
                                                        <?php else : ?>
                                                            text: 'Total',
                                                            subtext: '<?= ceil($total) ?>'
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
                                            $avg = $total / count(array_filter($temp));    
                                            
                                         
                                            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                                            $usd = $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'EUR');
                                            $usd = $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
                                            
                                      
                                         
                              
                                            
                                                                                       
                                            if ($row['type'] == 'percentage') {
                                                $total = round($avg, 2);
                                                $avg = ($row['avg'] == 0) ? null : number_format($row['avg'] * 100, 0);
                                                $jan = ($row['jan'] == 0) ? null : number_format($row['jan'] * 100, 0);
                                                $feb = ($row['feb'] == 0) ? null : number_format($row['feb'] * 100, 0);
                                                $mar = ($row['mar'] == 0) ? null : number_format($row['mar'] * 100, 0);
                                                $apr = ($row['apr'] == 0) ? null : number_format($row['apr'] * 100, 0);
                                                $may = ($row['may'] == 0) ? null : number_format($row['may'] * 100, 0);
                                                $jun = ($row['jun'] == 0) ? null : number_format($row['jun'] * 100, 0);
                                                $jul = ($row['jul'] == 0) ? null : number_format($row['jul'] * 100, 0);
                                                $aug = ($row['aug'] == 0) ? null : number_format($row['aug'] * 100, 0);
                                                $sep = ($row['sep'] == 0) ? null : number_format($row['sep'] * 100, 0);
                                                $oct = ($row['oct'] == 0) ? null : number_format($row['oct'] * 100, 0);
                                                $nov = ($row['nov'] == 0) ? null : number_format($row['nov'] * 100, 0);
                                                $dec = ($row['dec'] == 0) ? null : number_format($row['dec'] * 100, 0);
                                            } else {
                                                $avg = ($row['avg'] == 0) ? null : round($row['avg']);
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
                                            $data = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec, $avg);
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
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#d9ad7c', '#618685',
                                                '#667292', '#618685',
                                                '#96897f', '#618685',
                                                '#86af49', '#618685',
                                                '#d96459', '#618685',
                                                '#d9ad7c', '#618685',
                                            ];

                                            
                                            ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Last Year</th>
                                                    <th scope="col" class="text-center">Jan</th>
                                                    <th scope="col" class="text-center">Feb</th>
                                                    <th scope="col" class="text-center">Mar</th>
                                                    <th scope="col" class="text-center">Apr</th>
                                                    <th scope="col" class="text-center">May</th>
                                                    <th scope="col" class="text-center">Jun</th>
                                                    <th scope="col" class="text-center">Jul</th>
                                                    <th scope="col" class="text-center">Aug</th>
                                                    <th scope="col" class="text-center">Sep</th>
                                                    <th scope="col" class="text-center">Oct</th>
                                                    <th scope="col" class="text-center">Nov</th>
                                                    <th scope="col" class="text-center">Dec</th>
                                                    <th scope="col" class="text-center">Average</th>
                                                    <th scope="col" class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($row['type'] == 'currency') : ?>
                                                    <tr>
                                                        <td class="text-center">-</td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : $fmt->formatCurrency($row['jan'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : $fmt->formatCurrency($row['feb'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : $fmt->formatCurrency($row['mar'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : $fmt->formatCurrency($row['apr'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : $fmt->formatCurrency($row['may'], 'USD'); ?></td>
                                                        <td class="text-center"><?= $fmt->formatCurrency($avg, 'USD'); ?></td>
                                                        <td class="text-center"><?= $fmt->formatCurrency($total, 'USD'); ?></td>
                                                        
                                                    </tr>
                                                <?php elseif ($row['type'] == 'percentage') : ?>
                                                    <tr>
                                                        <td class="text-center">-</td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : number_format($row['jan'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : number_format($row['feb'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : number_format($row['mar'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : number_format($row['apr'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : number_format($row['may'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : number_format($row['jun'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : number_format($row['jul'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : number_format($row['aug'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : number_format($row['sep'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : number_format($row['oct'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : number_format($row['nov'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : number_format($row['dec'] * 100, 0). '%' ?></td>
                                                        <td class="text-center"><?= number_format($avg, 0) ?>%</td>
                                                        <td class="text-center">-</td>
                                                    </tr>
                                                <?php else : ?>
                                                    <tr>
                                                        <td class="text-center">-</td>
                                                        <td class="text-center"><?= ($row['jan'] == 0) ? '-' : round($row['jan']) ?></td>
                                                        <td class="text-center"><?= ($row['feb'] == 0) ? '-' : round($row['feb']) ?></td>
                                                        <td class="text-center"><?= ($row['mar'] == 0) ? '-' : round($row['mar']) ?></td>
                                                        <td class="text-center"><?= ($row['apr'] == 0) ? '-' : round($row['apr']) ?></td>
                                                        <td class="text-center"><?= ($row['may'] == 0) ? '-' : round($row['may']) ?></td>
                                                        <td class="text-center"><?= ($row['jun'] == 0) ? '-' : round($row['jun']) ?></td>
                                                        <td class="text-center"><?= ($row['jul'] == 0) ? '-' : round($row['jul']) ?></td>
                                                        <td class="text-center"><?= ($row['aug'] == 0) ? '-' : round($row['aug']) ?></td>
                                                        <td class="text-center"><?= ($row['sep'] == 0) ? '-' : round($row['sep']) ?></td>
                                                        <td class="text-center"><?= ($row['oct'] == 0) ? '-' : round($row['oct']) ?></td>
                                                        <td class="text-center"><?= ($row['nov'] == 0) ? '-' : round($row['nov']) ?></td>
                                                        <td class="text-center"><?= ($row['dec'] == 0) ? '-' : round($row['dec']) ?></td>
                                                        <td class="text-center"><?= round($avg) ?></td>
                                                        <td class="text-center"><?= round($total) ?></td>
                                                    </tr>
                                                <?php endif ?>
                                            </tbody>
                                        </table>
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
                                                        
                                                        <?php if ($row['type'] == 'currency') : ?>
                                                            text: 'Total',
                                                            subtext: '$ <?= number_format($total, 0) ?>'
                                                        <?php elseif ($row['type'] == 'percentage') : ?>
                                                            text: '',
                                                            subtext: ''
                                                        <?php else : ?>
                                                            text: 'Total',
                                                            subtext: '<?= ceil($total) ?>'
                                                            
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
<script src="/assets/js/demo_pages/form_select2.js"></script>
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>


<?= $this->endSection() ?>
