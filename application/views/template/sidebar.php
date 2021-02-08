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
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(2,["send_app_notification"])[0]; ?>">
                        <a href="<?= base_url('other/send_app_notification') ?>">
                            <span class="pcoded-micon"><i class="fa fa-send"></i></span>
                            <span class="pcoded-mtext">Send App Notifications</span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li>
                        <a href="<?= base_url('login/logout') ?>">
                            <span class="pcoded-micon"><i class="feather icon-log-out"></i></span>
                            <span class="pcoded-mtext">Logout</span>
                        </a>
                    </li>
                </ul>

                <div class="pcoded-navigatio-lavel">App CMS</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["business_category"])[0]; ?>">
                        <a href="<?= base_url('business_category') ?>">
                            <span class="pcoded-micon"><i class="fa fa-briefcase"></i></span>
                            <span class="pcoded-mtext">Business Categories</span>
                        </a>
                    </li>
                </ul>
                <div class="pcoded-navigatio-lavel">Others</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["setting"])[0]; ?>">
                        <a href="<?= base_url('setting') ?>">
                            <span class="pcoded-micon"><i class="fa fa-gear fa-spin"></i></span>
                            <span class="pcoded-mtext">Setting</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">