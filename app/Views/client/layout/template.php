<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('client/layout/header'); ?>
    <title><?= $title ?></title>

</head>

<body>
    <!-- Main navbar -->
    <?= $this->include('client/layout/main-navbar') ?>
    <!-- /main navbar -->


    <!-- Page content -->
    <div class="page-content">
        <!-- Main sidebar -->
        <?= $this->include('client/layout/sidebar') ?>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">
                <!-- Page header -->
                <div class="page-header">
                    <div class="page-header-content container header-elements-md-inline ml-0">
                        <div class="d-flex">
                            <div class="page-title">
                                <h4 class="font-weight-semibold"><?= $menu ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content  -->
                <?= $this->renderSection('content'); ?>

                <?= $this->include('client/layout/footer') ?>
            </div>
        </div>
        <?= $this->include('client/layout/notif') ?>
</body>
<?= $this->renderSection('js') ?>

</html>