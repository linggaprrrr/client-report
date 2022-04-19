<div class="sidebar sidebar-light sidebar-main sidebar-expand-xl">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
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
                    <a href="<?= base_url('/admin/dashboard') ?>" class="nav-link" id="dashboard">
                        <i class="icon-home4"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/admin/client-activities') ?>" class="nav-link" id="ca">
                        <i class="icon-clipboard3"></i>
                        <span>
                            Client Activities
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/admin/user-management') ?>" class="nav-link" id="um">
                        <i class="icon-users"></i>
                        <span>
                            User Management
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/admin/brand-approvals') ?>" class="nav-link">
                        <i class="fa fa-amazon" style="margin-top: 4px"></i>
                        <span style="margin-left: 2px;">
                            Brand Approvals
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/admin/news') ?>" class="nav-link" id="news">
                        <i class="icon-newspaper"></i>
                        <span>
                            Announcements
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="pl" href="<?= base_url('/admin/p-and-l-report') ?>" class="nav-link">
                        <i class="icon-clipboard5"></i>
                        <span>
                            P&L Reports
                        </span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu" id="assignment-menu">
                    <a href="#" class="nav-link">
                        <i class="icon-copy"></i>
                        <span>
                            Assignment Reports
                        </span>
                    </a>
                    <ul class="nav nav-group-sub" data-submenu-title="Assignment Report">
                        <li class="nav-item"><a href="<?= base_url('/admin/checklist-report') ?>" class="nav-link" id="cr">Report Checklist</a></li>
                        <li class="nav-item"><a href="<?= base_url('/admin/assignment-report') ?>" class="nav-link" id="ar">Assignment Report</a></li>
                        <li class="nav-item"><a href="<?= base_url('/admin/assignment-history') ?>" class="nav-link" id="ah">Assignment History</a></li>
                        <li class="nav-item"><a href="<?= base_url('/admin/completed-investments') ?>" class="nav-link" id="ci">Completed Assignments</a></li>
                    </ul>
                </li>
                <!-- /main -->
            </ul>
        </div>
        
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>