<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
<div class="sidebar sidebar-light sidebar-main sidebar-expand-xl">
    
    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Header -->
        <div class="sidebar-section sidebar-header">
            <div class="sidebar-section-body d-flex align-items-center justify-content-center py-2">
                <h5 class="sidebar-resize-hide flex-1 mb-0">Navigation</h5>
                <div class="my-1">
                    <button type="button" class="btn btn-outline-light text-body border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-main-resize d-none d-xl-inline-flex">
                        <i class="icon-transmission"></i>
                    </button>

                    <button type="button" class="btn btn-outline-light text-body border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-main-toggle d-xl-none">
                        <i class="icon-cross2"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /header -->


        <!-- User menu -->
        <div class="sidebar-section sidebar-section-body user-menu-vertical text-center pt-0">
            <div class="card-img-actions d-inline-block">
                <?php if (!empty($user['photo'])) : ?>
                    <img class="img-fluid rounded-circle" src="/img/<?= $user['photo'] ?>" alt="Profile Picture" style="width:100px; height:100px;object-fit: contain;">
                <?php else : ?>
                    <img class="img-fluid rounded-circle" src="/assets/images/placeholders/user.png" width="150" height="150" alt="Profile Picture">
                <?php endif ?>
            </div>

            <div class="sidebar-resize-hide mt-2">
                <h6 class="font-weight-semibold mb-0"><?= $user['fullname'] ?></h6>
                <span class="text-muted"><?= $user['company'] ?></span>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <li class="nav-item">
                    <a href="<?= base_url('/get-started') ?>" class="nav-link">
                        <i class="icon-rocket"></i>
                        <span>
                            Get Started
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/purchase-inventory') ?>" class="nav-link">
                        <i class="icon-cart4"></i>
                        <span>
                            Purchase Inventory
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/brand-approvals') ?>" class="nav-link">
                        <i class="fa fa-amazon" style="margin-top: 4px"></i>
                        <span style="margin-left: 2px;">
                            Brand Approvals
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/dashboard') ?>" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>
                            Manifests
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/pl-report') ?>" class="nav-link">
                        <i class="icon-clipboard5"></i>
                        <span>
                            P&L Report
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/news') ?>" class="nav-link">
                        <i class="icon-megaphone"></i>
                        <span>
                            Announcements
                        </span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <hr>
                    <a href="" data-toggle="modal" data-target="#exampleModal" class="nav-link reminder">
                        <i class="icon-reminder"></i>
                        <span>
                            Set Reminder
                        </span>
                    </a>
                </li>
                
                <!-- /main -->
                <li class="nav-item" style="margin-top: 150px;">
                    <h3 class="text-center font-weight-bold">Our Official Channel</h3>
                    <a href="https://www.youtube.com/watch?v=SbyUfvZDqoU&t" class="nav-link yt" target="_blank" style="justify-content: center;">
                        <img src="/assets/images/youtube-channel.png" style="width: 80%;">
                    </a>
                    <div class="effect aeneas" style="padding-top: 0px;">
                        <div class="buttons" style="bottom: 0; margin: 5px 35px 20px">
                            <a href="https://www.facebook.com/SmartFBA" target="_blank" class="fb" title="Find us on Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="https://www.youtube.com/c/smartfba" target="_blank" class="pinterest" title="Find us on Youtube"><i class="fa fa-youtube-play yt-click" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/smartfba/" target="_blank" class="insta" title="Find us on Instagram"><i class="fa fa-instagram ig-click" aria-hidden="true"></i></a>
                            <a href="https://www.linkedin.com/company/smartfba/" target="_blank" class="in" title="Find us on Linked In"><i class="fa fa-linkedin li-click" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>

            </ul>

        </div>
        
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/set-reminder" method="post">
            <?php csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reminder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Reminder Description:</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-info22"></i></span>
                            </span>
                            <input type="hidden" name="id" class="id-reminder">
                            <input type="text" class="form-control desc-reminder" name="desc" placeholder="Description ...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date:</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar22"></i></span>
                            </span>
                            <input type="text" class="form-control date-reminder daterange-single" name="date" value="<?= date("m/d/Y") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Continuity:</label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-calendar52"></i></span>
                            </span>
                            <select name="continuity" class="form-control cont-reminder" id="">
                                <option value="once">Just Once</option>
                                <option value="repeatedly">Rrepeatedly</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger">Clear Reminder</button>                    
                    <button type="submit" class="btn btn-primary">Save changes</button>                    
                </div>
            </div>
        </form>
    </div>
</div>
<button type="button" id="noty_created" style="display: none;"></button>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="/assets/js/demo_pages/picker_date.js"></script>
<script src="/assets/js/plugins/pickers/daterangepicker.js"></script>
<script>    
    $(document).ready(function() {
        <?php if (session()->getFlashdata('success')) : ?>
            $('#noty_created').click();
        <?php endif ?>
    });
    
    $('.reminder').click(function() {
        $.get('/get-reminder', {id: <?= session()->get('user_id') ?> }, function(data) {
            const resp = JSON.parse(data);
            $('.id-reminder').val(resp['id']);
            $('.desc-reminder').val(resp['desc']);
            $('.date-reminder').val(resp['date']);
            $('.cont-reminder').val(resp['continuity']);
        });
    });

    $('.clear-reminder').click(function() {
        const id = $('.id-reminder').val();
        $.post('/clear-reminder', {id: id}, function(data) {
            new Noty({
                text: 'Reminder has been cleared successfully',
                type: 'alert'
            }).show();
        }); 
    });

    $('#noty_created').on('click', function() {
        new Noty({
            text: 'Reminder has been added successfully',
            type: 'alert'
        }).show();
    });
    $(".fb").click(function() {   
        $.get('/fb-click', function(data) {

        });
    });

    $(".pinterest").click(function() {   
        $.get('/yt-click', function(data) {

        });
    });

    $(".yt").click(function() {   
        $.get('/yt-click', function(data) {

        });
    });

    $(".insta").click(function() {   
        $.get('/ig-click', function(data) {

        });
    });

    $(".in").click(function() {   
        $.get('/in-click', function(data) {

        });
    });
</script>