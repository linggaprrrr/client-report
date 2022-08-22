<?= $this->extend('administrator/layout/template') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="card">
    <form action="<?= base_url('/admin/logs') ?>" method="post">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">             
                                <div class="header-elements text-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                        </span>
                                       
                                        <?php if (!empty($login)) : ?>
                                            <select name="login" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($login == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= ($login == 1) ? 'selected' : ''; ?>>January</option>
                                            <option value="2" <?= ($login == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= ($login == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= ($login == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= ($login == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= ($login == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= ($login == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= ($login == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= ($login == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= ($login == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= ($login == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= ($login == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php else : ?>
                                            <select name="login" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($login == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= (date('n') == 1) ? 'selected' : ''; ?>>January</option>
                                            <option value="2" <?= (date('n') == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= (date('n') == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= (date('n') == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= (date('n') == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= (date('n') == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= (date('n') == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= (date('n') == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= (date('n') == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= (date('n') == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= (date('n') == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= (date('n') == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php endif ?>
                                    </div>

                                </div>
                                <br>
                                <div class="chart has-fixed-height" id="login"></div>
                                <?php 
                                 
                                    $broswer = $userLoginGraphBrowser[0]->total;
                                    $android = $userLoginGraphAndroid[0]->total;
                                    $iOS = $userLoginGraphiOS[0]->total;
                                ?>
                                <script type="text/javascript">
                                    // Initialize the echarts instance based on the prepared dom
                                    var myChart = echarts.init(document.getElementById('login'));
                                    // Specify the configuration items and data for the chart
                                    option = {
                                        title: {
                                            text: 'User Login',
                                            subtext: '',
                                            left: 'center'
                                        },
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            orient: 'vertical',
                                            left: 'left'
                                        },
                                        series: [
                                            {
                                            name: 'Access From',
                                            type: 'pie',
                                            radius: '70%',
                                            data: [
                                                { value: <?= $broswer ?>, name: 'Browser' },
                                                { value: <?= $iOS ?>, name: 'iOS' },
                                                { value: <?= $android ?>, name: 'Android' },
                                            ],
                                            emphasis: {
                                                itemStyle: {
                                                shadowBlur: 10,
                                                shadowOffsetX: 0,
                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                                }
                                            }
                                            }
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
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                            <div style="float: right">                           
                                <?php if (empty($login)) : ?>
                                    <a href="<?= base_url('/export-log-login') ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php else:  ?>
                                    <a href="<?= base_url('/export-log-login'). '/' .$login ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table datatable-basic" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Date</th>
                                        <th>Media</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($userLogin->getNumRows() > 0) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($userLogin->getResultObject() as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->fullname ?></td>
                                            <td><?= $row->ip_address ?></td>
                                            <td><?= date('F j, Y - g:i:s a', strtotime($row->date))  ?></td>
                                            <td><?= $row->media ?></td>
                                            <td></td>
                                        </tr>        
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                           
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <div class="header-elements text-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                        </span>
                                        <?php if (!empty($page2)) : ?>
                                            <select name="page" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($page2 == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= ($page2 == 1) ? 'selected' : ''; ?>>January</option>
                                            <option value="2" <?= ($page2 == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= ($page2 == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= ($page2 == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= ($page2 == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= ($page2 == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= ($page2 == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= ($page2 == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= ($page2 == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= ($page2 == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= ($page2 == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= ($page2 == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php else : ?>
                                            <select name="page" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($login == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= (date('n') == 1) ? 'page2' : ''; ?>>January</option>
                                            <option value="2" <?= (date('n') == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= (date('n') == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= (date('n') == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= (date('n') == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= (date('n') == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= (date('n') == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= (date('n') == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= (date('n') == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= (date('n') == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= (date('n') == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= (date('n') == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php endif ?>
                                    </div>

                                </div>
                                <br>
                                <div class="chart has-fixed-height" id="click"></div>
                               <?php 
                                    $click = array();
                                    $legend = array();
                                    foreach($userClick->getResultObject() as $page) {
                                        array_push($legend, strtoupper($page->page));
                                        array_push($click, $page->total);
                                    }
                                ?>
                                <script type="text/javascript">
                                    // Initialize the echarts instance based on the prepared dom
                                    var myChart = echarts.init(document.getElementById('click'));
                                    // Specify the configuration items and data for the chart
                                    option = {
                                        title: {
                                            text: 'User Page Click'
                                        },
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [
                                            {
                                            name: 'User Access',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            itemStyle: {
                                                borderRadius: 10,
                                                borderColor: '#fff',
                                                borderWidth: 2
                                            },
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                show: true,
                                                fontSize: '14',
                                                fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [
                                                <?php for ($i=0; $i < count($legend); $i++) : ?>
                                                    { value: <?= $click[$i] ?>, name: '<?= $legend[$i] ?>' },
                                                <?php endfor ?>                                         
                                            ]
                                            }
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
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                            <div style="float: right">
                                <?php if (empty($page)) : ?>
                                    <a href="<?= base_url('/export-log-page') ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php else:  ?>
                                    <a href="<?= base_url('/export-log-page'). '/' .$page2 ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php endif ?>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table datatable-basic" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User</th>
                                        <th>Page</th>
                                        <th>Date</th>
                                        <th>Click</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($getUserClick->getNumRows() > 0) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($getUserClick->getResultObject() as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->fullname ?></td>
                                            <td><?= $row->page ?></td>
                                            <td><?= date('F j, Y - g:i:s a', strtotime($row->date))  ?></td>
                                            <td><?= $row->click ?></td>
                                            <td></td>
                                        </tr>        
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <div class="header-elements text-right">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-calendar22"></i></span>
                                        </span>
                                        <?php if (!empty($social2)) : ?>
                                            <select name="social" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($social2 == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= ($social2 == 1) ? 'selected' : ''; ?>>January</option>
                                            <option value="2" <?= ($social2 == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= ($social2 == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= ($social2 == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= ($social2 == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= ($social2 == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= ($social2 == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= ($social2 == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= ($social2 == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= ($social2 == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= ($social2 == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= ($social2 == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php else : ?>
                                            <select name="social" class="form-control" onchange="this.form.submit()" >
                                            <option value="<?= date('Y') ?>" <?= ($social2 == date('Y')) ? 'selected' : ''; ?>>This Year</option>
                                            <option value="1" <?= (date('n') == 1) ? 'selected' : ''; ?>>January</option>
                                            <option value="2" <?= (date('n') == 2) ? 'selected' : ''; ?>>February</option>
                                            <option value="3" <?= (date('n') == 3) ? 'selected' : ''; ?>>March</option>
                                            <option value="4" <?= (date('n') == 4) ? 'selected' : ''; ?>>April</option>
                                            <option value="5" <?= (date('n') == 5) ? 'selected' : ''; ?>>May</option>
                                            <option value="6" <?= (date('n') == 6) ? 'selected' : ''; ?>>June</option>
                                            <option value="7" <?= (date('n') == 7) ? 'selected' : ''; ?>>July</option>
                                            <option value="8" <?= (date('n') == 8) ? 'selected' : ''; ?>>August</option>
                                            <option value="9" <?= (date('n') == 9) ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?= (date('n') == 10) ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?= (date('n') == 11) ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?= (date('n') == 12) ? 'selected' : ''; ?>>December</option>
                                        </select>
                                        <?php endif ?>
                                    </div>

                                </div>
                                <br>
                                <div class="chart has-fixed-height" id="media"></div>
                               <?php 
                                    $socialClick = array();
                                    $socialLegend = array();
                                    foreach($userClickMedia->getResultObject() as $social) {
                                        array_push($socialLegend, strtoupper($social->social));
                                        array_push($socialClick, $social->total);
                                    }
                                ?>
                                <script type="text/javascript">
                                    // Initialize the echarts instance based on the prepared dom
                                    var myChart = echarts.init(document.getElementById('media'));
                                    // Specify the configuration items and data for the chart
                                    option = {
                                        title: {
                                            text: 'Social Media & Credit Click '
                                        },
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [
                                            {
                                            name: 'User Access',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            itemStyle: {
                                                borderRadius: 10,
                                                borderColor: '#fff',
                                                borderWidth: 2
                                            },
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                show: true,
                                                fontSize: '14',
                                                fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [
                                                <?php for ($i=0; $i < count($socialLegend); $i++) : ?>
                                                    { value: <?= $socialClick[$i] ?>, name: '<?= $socialLegend[$i] ?>' },
                                                <?php endfor ?>
                                            ]
                                            }
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
                <div class="col-xl-6">
                    <!-- Multi level donut chart -->
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title"> </h5>
                            <div style="float: right">
          
                            <?php if (empty($social2)) : ?>
                                    <a href="<?= base_url('/export-log-social') ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php else:  ?>
                                    <a href="<?= base_url('/export-log-social'). '/' .$social2 ?>" class="btn btn-secondary" target="_blank"><i class="fa fa-file-excel mr-2"></i>Export</a>
                                <?php endif ?>                                
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table datatable-basic" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User</th>
                                        <th>Link</th>
                                        <th>Date</th>
                                        <th>Click</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($getUserClickMedia->getNumRows() > 0) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($getUserClickMedia->getResultObject() as $row) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->fullname ?></td>
                                            <td><?= $row->social ?></td>
                                            <td><?= date('F j, Y - g:i:s a', strtotime($row->date))  ?></td>
                                            <td>1</td>
                                            <td></td>
                                        </tr>        
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </form>
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
    $('.applyBtn').click(function(){
        
    })
</script>
<?= $this->endSection() ?>