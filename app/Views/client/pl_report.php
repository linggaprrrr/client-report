<?= $this->extend('client/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Well done!</span> Ticket Successfully Created! <a href="#" class="alert-link"></a>
        </div>
    <?php endif ?>

    <?php
    $no = 0;
    ?>
    <div class="text-right mb-4" >
        <?php if (!empty($file)) : ?>
            <a href="<?= base_url('files/' . $file->file) ?>" download="<?= $file->file ?>" class=" btn btn-teal"><i class="icon-file-download mr-2"></i> Download Report</a>
            <!-- <button type="button" class="btn btn-teal"><i class="icon-file-download mr-2"></i>Download Report</button> -->
        <?php endif ?>
    </div>
    <div class="row">
        <?php if ($plReport->getNumRows() > 0) : ?>
            <?php foreach ($plReport->getResultArray() as $row) : ?>
                <?php if (fmod($no, 2) == 0) : ?>
                    <div class="col-xl-12">
                        <!-- Multi level donut chart -->
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

                                    $data = array($row['jan'], $row['feb'], $row['mar'], $row['apr'], $row['may'], $row['jun'], $row['jul'], $row['aug'], $row['sep'], $row['oct'], $row['nov'], $row['dec'], round($avg, 0));
                                    $chartData = json_encode($data);
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
                                                    subtext: '<?= number_format($total, 0) ?> %'
                                                <?php else : ?>
                                                    subtext: '<?= $total ?>'
                                                    <?php endif ?>,
                                                left: 'right',    
                                                textStyle: {
                                                    color: '#252b36',
                                                    fontStyle: 'italic'
                                                    
                                                },
                                                subtextStyle: {
                                                    fontWeight: 'bold'
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
                                                    fontSize: 12,
                                                    fontWeight: 'bold'
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
                        <!-- /multi level donut chart -->

                    </div>
                <?php endif ?>
                <?php $no++; ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/demo_pages/datatables_basic.js"></script>


<?= $this->endSection() ?>