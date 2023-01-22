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
                    <a href="<?= base_url('/warehouse/upc') ?>" class="nav-link">
                        <i class="icon-search4"></i>
                        <span>
                            UPC Client
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/warehouse/upc-database') ?>" class="nav-link">
                        <i class="icon-folder-search"></i>
                        <span>
                            UPC Database
                        </span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="<?= base_url('/warehouse/scan-log') ?>" class="nav-link">
                        <i class="icon-copy"></i>
                        <span>
                            Scan Log
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/warehouse/history-box') ?>" class="nav-link">
                        <i class="icon-clipboard3"></i>
                        <span>
                            History Box
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/warehouse/input-item') ?>" class="nav-link">
                        <i class="icon-clipboard3"></i>
                        <span>
                            Input Item
                        </span>
                    </a>
                </li> -->
                <!-- /main -->



            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>