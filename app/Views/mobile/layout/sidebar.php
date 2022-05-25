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
                    <a href="<?= base_url('/mobile/get-started/'. $user['id']) ?>" class="nav-link">
                        <i class="icon-rocket"></i>
                        <span>
                            Get Started
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/mobile/purchase-inventory') ?>" class="nav-link">
                        <i class="icon-cart4"></i>
                        <span>
                            Purchase Inventory
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/mobile/brand-approvals') ?>" class="nav-link">
                        <i class="fa fa-amazon" style="margin-top: 4px"></i>
                        <span style="margin-left: 2px;">
                            Brand Approvals
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/mobile/dashboard') ?>" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>
                            Manifests
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/mobile/pl-report') ?>" class="nav-link">
                        <i class="icon-clipboard5"></i>
                        <span>
                            P&L Report
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/mobile/news') ?>" class="nav-link">
                        <i class="icon-megaphone"></i>
                        <span>
                            Announcements
                        </span>
                    </a>
                </li>

                <!-- /main -->
                <li class="nav-item" style="margin-top: 150px;">
                    <h3 class="text-center font-weight-bold">Our Official Channel</h3>
                    <a href="https://www.youtube.com/watch?v=SbyUfvZDqoU&t" class="nav-link" target="_blank" style="justify-content: center;">
                        <img src="/assets/images/youtube-channel.png" style="width: 80%;">
                    </a>
                    <div class="effect aeneas" style="padding-top: 0px;">
                        <div class="buttons" style="bottom: 0; margin: 5px 35px 20px">
                            <a href="https://www.facebook.com/SmartFBA" target="_blank" class="fb" title="Find us on Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="https://www.youtube.com/c/smartfba" target="_blank" class="pinterest" title="Find us on Youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                            <a href="https://www.instagram.com/smartfba/" target="_blank" class="insta" title="Find us on Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            <a href="https://www.linkedin.com/company/smartfba/" target="_blank" class="in" title="Find us on Linked In"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </li>

            </ul>

        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>