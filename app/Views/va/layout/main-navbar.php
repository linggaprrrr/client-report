<div class="navbar navbar-expand-xl navbar-light navbar-static px-0">
    <div class="d-flex flex-1 pl-3">
        <div class="navbar-brand wmin-0 mr-1 p-1">
            <a href="<?= base_url('/') ?>" class="d-inline-block">
                <img src="/assets/images/fba-logo.png" class="d-none d-sm-block" alt="FBA Logo" style="width: 255px">
                <img src="/assets/images/fba-logo-only.png" class="d-sm-none" alt="" style="width: 100%;">
            </a>
        </div>

        <button type="button" class="navbar-toggler sidebar-mobile-main-toggle ml-2">
            <i class="icon-transmission"></i>
        </button>

        <button type="button" class="navbar-toggler sidebar-mobile-secondary-toggle">
            <i class="icon-arrow-left8"></i>
        </button>

        <button type="button" class="navbar-toggler sidebar-mobile-right-toggle">
            <i class="icon-arrow-right8"></i>
        </button>
    </div>
    <div class="d-flex flex-xl-1 justify-content-xl-end order-0 order-xl-1 pr-3">
        <ul class="navbar-nav navbar-nav-underline flex-row">
            <li class="nav-item" style="align-self: center;">
                <a href="javascript:window.location.reload(true)" class="navbar-nav-link navbar-nav-link-toggler">
                    <i class="icon-sync"></i>
                </a>
            </li>
            <!-- <li class="nav-item" style="align-self: center;">
                <a href="#notifications" class="navbar-nav-link navbar-nav-link-toggler" data-toggle="modal">
                    <i class="icon-bell2"></i>
                    <span class="badge badge-mark border-pink bg-pink"></span>
                </a>
            </li> -->
            <li class="nav-item" style="align-self: center;">
                <div class="tigle" style="margin-top: 5px">  
                    <input type="checkbox" id="switch" class="switch"/>
                    <label id="label" for="switch" class ="label">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                        <div class="ball"></div>
                    </label>
                </div>
            </li>
            <li class="nav-item nav-item-dropdown-xl dropdown dropdown-user h-100">
                <a href="#" class="navbar-nav-link navbar-nav-link-toggler d-flex align-items-center h-100 dropdown-toggle" data-toggle="dropdown">
                    <?php if (!empty($user['photo'])) : ?>
                        <img class="img-fluid rounded-circle" src="/img/<?= $user['photo'] ?>" alt="Profile Picture" style="width:38px; height:38px;object-fit: contain;">
                    <?php else : ?>
                        <img src="/assets/images/placeholders/user.png" class="rounded-circle" height="38" alt="">
                    <?php endif ?>

                    <span class="d-none d-xl-block ml-2"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?= base_url('/admin/account-setting') ?>" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
                    <a href="<?= base_url('/logout') ?>" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</div>