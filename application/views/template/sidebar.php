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
                    <li class="pcoded-hasmenu <?= menu(1,["orders"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-sort"></i></span>
                            <span class="pcoded-mtext">Orders</span>
                        </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["new"])[0]; ?>">
                                <a href="<?= base_url('orders/new') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">New</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["ongoing"])[0]; ?>">
                                <a href="<?= base_url('orders/ongoing') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Ongoing</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["completed"])[0]; ?>">
                                <a href="<?= base_url('orders/completed') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Completed</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["subscriptions"])[0]; ?>">
                        <a href="<?= base_url('subscriptions') ?>">
                            <span class="pcoded-micon"><i class="fa fa-repeat"></i></span>
                            <span class="pcoded-mtext">Subscriptions</span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["ordersupport"])[0]; ?>">
                        <a href="<?= base_url('ordersupport') ?>">
                            <span class="pcoded-micon"><i class="fa fa-ticket"></i></span>
                            <span class="pcoded-mtext">Order Support</span>
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
                    <li class="<?= menu(1,["areas"])[0]; ?>">
                        <a href="<?= base_url('areas') ?>">
                            <span class="pcoded-micon"><i class="fa fa-map"></i></span>
                            <span class="pcoded-mtext">Areas</span>
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
                <div class="pcoded-navigatio-lavel">Users</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="<?= menu(1,["customers"])[0]; ?>">
                        <a href="<?= base_url('customers') ?>">
                            <span class="pcoded-micon"><i class="fa fa-address-card-o"></i></span>
                            <span class="pcoded-mtext">Customers</span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["delivery"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-car"></i></span>
                            <span class="pcoded-mtext">Delivery</span>
                         </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["new"])[0]; ?>">
                                <a href="<?= base_url('delivery/new') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">New</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["approved"])[0]; ?>">
                                <a href="<?= base_url('delivery/approved') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Approved</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["online"])[0]; ?>">
                                <a href="<?= base_url('delivery/online') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Online</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["offline"])[0]; ?>">
                                <a href="<?= base_url('delivery/offline') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Offline</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["rejected"])[0]; ?>">
                                <a href="<?= base_url('delivery/rejected') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Rejected</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["service"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-wrench"></i></span>
                            <span class="pcoded-mtext">Service</span>
                         </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["new"])[0]; ?>">
                                <a href="<?= base_url('service/new') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">New</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["approved"])[0]; ?>">
                                <a href="<?= base_url('service/approved') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Approved</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["online"])[0]; ?>">
                                <a href="<?= base_url('service/online') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Online</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["offline"])[0]; ?>">
                                <a href="<?= base_url('service/offline') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Offline</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["rejected"])[0]; ?>">
                                <a href="<?= base_url('service/rejected') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Rejected</span>
                                </a>
                            </li>
                        </ul>
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
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["customercms"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-address-card-o"></i></span>
                            <span class="pcoded-mtext">Customer App</span>
                         </a>
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["banner"])[0]; ?>">
                                <a href="<?= base_url('customercms/banner') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Home Banner</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["terms"])[0]; ?>">
                                <a href="<?= base_url('customercms/terms') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Terms and Conditions</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["privacy"])[0]; ?>">
                                <a href="<?= base_url('customercms/privacy') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Privacy Policy</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["about"])[0]; ?>">
                                <a href="<?= base_url('customercms/about') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">About App</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["how"])[0]; ?>">
                                <a href="<?= base_url('customercms/how') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Cancellation and Refund Policy</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["faq"])[0]; ?>">
                                <a href="<?= base_url('customercms/faq') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">FAQ's</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["deliverycms"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-car"></i></span>
                            <span class="pcoded-mtext">Delivery App</span>
                         </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["terms"])[0]; ?>">
                                <a href="<?= base_url('deliverycms/terms') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Terms and Conditions</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["privacy"])[0]; ?>">
                                <a href="<?= base_url('deliverycms/privacy') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Privacy Policy</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["about"])[0]; ?>">
                                <a href="<?= base_url('deliverycms/about') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">About App</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["faq"])[0]; ?>">
                                <a href="<?= base_url('deliverycms/faq') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">FAQ's</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["servicecms"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-wrench"></i></span>
                            <span class="pcoded-mtext">Service App</span>
                         </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["terms"])[0]; ?>">
                                <a href="<?= base_url('servicecms/terms') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Terms and Conditions</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["privacy"])[0]; ?>">
                                <a href="<?= base_url('servicecms/privacy') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Privacy Policy</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["about"])[0]; ?>">
                                <a href="<?= base_url('servicecms/about') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">About App</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["faq"])[0]; ?>">
                                <a href="<?= base_url('servicecms/faq') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">FAQ's</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="pcoded-navigatio-lavel">Web</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu <?= menu(1,["webcms"])[2]; ?>">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="fa fa-globe fa-spin"></i></span>
                            <span class="pcoded-mtext">Web Cms</span>
                         </a>   
                        <ul class="pcoded-submenu">
                            <li class="<?= menu(2,["terms"])[0]; ?>">
                                <a href="<?= base_url('webcms/terms') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Terms and Conditions</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["privacy"])[0]; ?>">
                                <a href="<?= base_url('webcms/privacy') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Privacy Policy</span>
                                </a>
                            </li>
                            <li class="<?= menu(2,["refund"])[0]; ?>">
                                <a href="<?= base_url('webcms/refund') ?>">
                                    <span class="pcoded-micon"><i class="fa fa-list"></i></span>
                                    <span class="pcoded-mtext">Refund and Cancellation</span>
                                </a>
                            </li>
                        </ul>
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