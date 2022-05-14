<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('administrator/layout/header'); ?>
    <title><?= $tittle ?></title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>

</head>
<style>
    .news-scroll a {
        text-decoration: none
    }

    .news {
        width: auto;

    }

    .martex {
        font-size: 18px;
        font-weight: 800;

    }

    .news-text {
        font-size: 18px;
        font-weight: 900;
    }

    #example1 {
        border: 1px solid;
        padding: 10px;
        box-shadow: 5px 10px;
        background-color: white;
    }
    * {
        box-sizing: border-box;
    }
    body.dark {
        background: #616161;
    }
    h1 {
        transition: color 0.2s linear;
    }
    h2 {
        transition: color 0.2s linear;
    }
    h3 {
        transition: color 0.2s linear;
    }
    h4 {
        transition: color 0.2s linear;
    }

    h1.dark {
        color: wheat
    }

    h2.dark {
        color: wheat
    }

    h3.dark {
        color: wheat
    }

    h4.dark {
        color: wheat
    }

    h5.dark {
        color: wheat
    }
    
    .carddark {
        background: #757575;
        color: wheat;
    }

    .tabledark {
        color: wheat;
    }
    .switch {
        opacity: 0;
        position: absolute;
    }

    .label {
        background: #359DE6;
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 3px;
        border-radius: 50px;
        position: relative;
        width: 55px;
        height: 26px;
    }

    .tabledark {
        font-weight: 900;
    }

    .label.dark {
        background: #111;
    }

    .card.dark {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #4B4C54;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.25rem;
    }
    .ball {
        background-color: #fff;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        width: 22px;
        height: 22px;
        transition: transform 0.3s linear;
    }
    .switch:checked + .label .ball {
        transform: translateX(29px);
    }

    .fa-moon {
        color: #cccc;
    }

    .fa-sun {
        color: #F5EC30;
    }

</style>

<body>
    <!-- Main navbar -->
    <?= $this->include('administrator/layout/main-navbar') ?>
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">
        <!-- Main sidebar -->
        <?= $this->include('administrator/layout/sidebar') ?>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">
                <!-- Page header -->
                <div class="page-header">
                    <?php if ((strpos($_SERVER['REQUEST_URI'], "dashboard") !== false) || strpos($_SERVER['REQUEST_URI'], "news") !== false) : ?>
                        <?php if (!is_null($news)) : ?>
                            <?php if ($news->type == "news") : ?>
                                <div class="mt-2 mx-3" id="example1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center breaking-news bg-white">
                                                <div class="d-flex flex-row flex-grow-1 flex-fill justify-content-center bg-danger py-2 text-white px-2 news">
                                                    <span class="d-flex align-items-center news-text">&nbsp;NEWS!&nbsp;</span>
                                                </div>
                                                <marquee class="news-scroll martex" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                                                    <?= $news->message ?>
                                                </marquee>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    <?php endif ?>
                    <div class="page-header-content container header-elements-md-inline  ml-0">
                        <div class="d-flex">
                            <div class="page-title pb-0">
                                <h4 id="title" class="font-weight-semibold"><?= $menu ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content  -->
                <?= $this->renderSection('content'); ?>

                <?= $this->include('administrator/layout/footer') ?>
            </div>
        </div>
        <?= $this->include('administrator/layout/notif') ?>
</body>
<?= $this->renderSection('js') ?>
<div id="fb-root"></div>

<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
    $(document).ready(function() {
        switch ("<?= uri_string() ?>") {
            case "admin/dashboard":
                $.get('/refresh-dashboard', function(data) {
                    var sum = JSON.parse(data);
                    $('.total_client_cost').html(sum['total_client_cost']);
                    $('.total_cost_left').html(sum['total_cost_left']);
                    $('.total_unit').html(sum['total_unit']);
                    $('.total_original').html(sum['total_original']);
                    $('.total_fulfilled').html(sum['total_fulfilled']);
                    $('.avg_retail').html(sum['avg_retail']);
                    $('.avg_client_cost').html(sum['avg_client_cost']);

                    totalFulfilled = Number(sum['total_fulfilled'].replace(/[^0-9.-]+/g, ""));
                    totalClientCost = Number(sum['total_client_cost'].replace(/[^0-9.-]+/g, ""));
                });



                $('.refresh').click(function() {
                    $('.total_client_cost').html("...");
                    $('.total_cost_left').html("...");
                    $('.total_unit').html("...");
                    $('.total_original').html("...");
                    $('.total_fulfilled').html("...");
                    $('.avg_retail').html("...");
                    $('.avg_client_cost').html("...");
                    $.get('/refresh-dashboard', function(data) {
                        var sum = JSON.parse(data);
                        $('.total_client_cost').html(sum['total_client_cost']);
                        $('.total_cost_left').html(sum['total_cost_left']);
                        $('.total_unit').html(sum['total_unit']);
                        $('.total_original').html(sum['total_original']);
                        $('.total_fulfilled').html(sum['total_fulfilled']);
                        $('.avg_retail').html(sum['avg_retail']);
                        $('.avg_client_cost').html(sum['avg_client_cost']);

                        totalFulfilled = Number(sum['total_fulfilled'].replace(/[^0-9.-]+/g, ""));
                        totalClientCost = Number(sum['total_client_cost'].replace(/[^0-9.-]+/g, ""));
                    });
                })
                $('#dashboard').addClass('active');
                break;
            case "admin/client-activities":
                $('#ca').addClass('active');
                break;

            case "admin/user-management":
                $('#um').addClass('active');
                break;

            case "admin/news":
                $('#news').addClass('active');
                break;

            case "admin/p-and-l-report":
                $('#pl').addClass('active');
                break;

            case "admin/checklist-report":
                $('#assignment-menu').addClass('nav-item-expanded nav-item-open"');
                $('#cr').addClass('active');
                break;

            case "admin/assignment-report":
                $('#assignment-menu').addClass('nav-item-expanded nav-item-open"');
                $('#ar').addClass('active');
                $.get('/get-summary-box', function(data) {
                    var sum = JSON.parse(data);
                    $('.total_box').html(sum['total_box']);
                    $('.total_unit').html(sum['total_unit']);
                    $('.total_box_onprocess').html(sum['onprocess']);
                    $('.total_box_completed').html(sum['complete']);
                    $('.total_client_cost').html(sum['client_cost']);
                });

                break;

            case "admin/assignment-history":
                $('#assignment-menu').addClass('nav-item-expanded nav-item-open"');
                $('#ah').addClass('active');
                break;
            case "admin/completed-investments":
                $('#assignment-menu').addClass('nav-item-expanded nav-item-open"');
                $('#ci').addClass('active');
                break;
            default:
                // code block
        }




    });



    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "106425448582832");
    chatbox.setAttribute("attribution", "biz_inbox");
</script>

<!-- Your SDK code -->

</html>