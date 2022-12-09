<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('warehouse/layout/header'); ?>
    <title><?= $tittle ?></title>

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
</style>

<body>
    <!-- Main navbar -->
    <?= $this->include('warehouse/layout/main-navbar') ?>
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">
        <!-- Main sidebar -->
        <?= $this->include('warehouse/layout/sidebar') ?>
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
                                <h4 class="font-weight-semibold"><?= $menu ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content  -->
                <?= $this->renderSection('content'); ?>

                <?= $this->include('warehouse/layout/footer') ?>
            </div>
        </div>
        <?= $this->include('warehouse/layout/notif') ?>
</body>
<?= $this->renderSection('js') ?>
<div id="fb-root"></div>

<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>


</html>