<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <nav class="pcoded-navbar">
            <div class="pcoded-inner-navbar main-menu">
                <div class="pcoded-navigatio-lavel">Navigation</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["dashboard"])[0]; ?>">
                        <a href="<?= base_url('dashboard') ?>">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>
                </ul>
                <?php if($this->rights->check([1])){ ?>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="<?= menu(2,["send_app_notification"])[0]; ?>">
                            <a href="<?= base_url('other/send_app_notification') ?>">
                                <span class="pcoded-micon"><i class="fa fa-send"></i></span>
                                <span class="pcoded-mtext">Send App Notifications</span>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
                
                <ul class="pcoded-item pcoded-left-item">
                    <li>
                        <a href="<?= base_url('login/logout') ?>">
                            <span class="pcoded-micon"><i class="feather icon-log-out"></i></span>
                            <span class="pcoded-mtext">Logout</span>
                        </a>
                    </li>
                </ul>

                <?php if($this->rights->check([3])){ ?>
                <div class="pcoded-navigatio-lavel">App Users</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu <?= menu(1,["service_provider"])[2]; ?>">
                            <a href="javascript:void(0)">
                                <span class="pcoded-micon"><i class="fa fa-user-secret"></i></span>
                                <span class="pcoded-mtext">Service Providers</span>
                            </a>   
                            <ul class="pcoded-submenu">
                                <li class="<?= menu(2,["new"])[0]; ?>">
                                    <a href="<?= base_url('service_provider/new') ?>">
                                        <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                        <span class="pcoded-mtext">New</span>
                                    </a>
                                </li>
                                <li class="<?= menu(2,["approved"])[0]; ?>">
                                    <a href="<?= base_url('service_provider/approved') ?>">
                                        <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                        <span class="pcoded-mtext">Approved</span>
                                    </a>
                                </li>
                                <li class="<?= menu(2,["rejected"])[0]; ?>">
                                    <a href="<?= base_url('service_provider/rejected') ?>">
                                        <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                        <span class="pcoded-mtext">Rejected</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>

                <div class="pcoded-navigatio-lavel">App CMS</div>
                <?php if($this->rights->check([2])){ ?>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu <?= menu(1,["categories"])[2]; ?>">
                            <a href="javascript:void(0)">
                                <span class="pcoded-micon"><i class="fa fa-briefcase"></i></span>
                                <span class="pcoded-mtext">Categories</span>
                            </a>   
                            <ul class="pcoded-submenu">
                                <li class="<?= menu(2,["main","edit_category"])[0]; ?>">
                                    <a href="<?= base_url('categories/main') ?>">
                                        <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                        <span class="pcoded-mtext">Categories</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
                <?php if(get_user()['user_type'] == '0'){ ?>
                    <div class="pcoded-navigatio-lavel">Others</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="<?= menu(1,["users"])[0]; ?>">
                            <a href="<?= base_url('users') ?>">
                                <span class="pcoded-micon"><i class="fa fa-user-md"></i></span>
                                <span class="pcoded-mtext">Users</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="<?= menu(1,["setting"])[0]; ?>">
                            <a href="<?= base_url('setting') ?>">
                                <span class="pcoded-micon"><i class="fa fa-gear fa-spin"></i></span>
                                <span class="pcoded-mtext">Setting</span>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </nav>
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">