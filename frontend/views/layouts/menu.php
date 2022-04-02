<?php
use yii\helpers\Url;
?>
<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <a href="<?=Url::to(["site/"])?>" class="logo">
                        <span>
                            <img src="<?=Yii::getAlias("@web")?>/images/r_logo.png" alt="" height="30">
                        </span>
            <i>
                <img src="<?=Yii::getAlias("@web")?>/images/r_logo.png" alt="" height="22">
            </i>
        </a>
    </div>

    <nav class="navbar-custom">

        <ul class="navbar-right d-flex list-inline float-right mb-0">

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ti-bell noti-icon"></i>
                    <span class="badge badge-pill badge-danger noti-icon-badge" id="pending_count"></span>
                </a>
            </li>
            <li class="dropdown notification-list">
                <div class="dropdown notification-list nav-pro-img">
                    <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span style="font-size: 1.8rem" class="mdi mdi-account-circle"></span>       </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <!--                            <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5"></i> Profile</a>-->
                        <!--                            <a class="dropdown-item" href="#"><i class="mdi mdi-wallet m-r-5"></i> My Wallet</a>-->
                        <!--                            <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings m-r-5"></i> Settings</a>-->
                        <!--                            <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5"></i> Lock screen</a>-->

                        <a class="dropdown-item text-danger" href="<?=Url::to(["site/logout"])?>"><i class="mdi mdi-power text-danger"></i> Logout</a>
                    </div>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>

        </ul>

    </nav>

</div>
<!-- Top Bar End -->

<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Main</li>
                <li>
                    <a href="<?=Url::to(["site/"])?>" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i><span> Home </span>
                    </a>
                </li>
                <li>
                    <a href="<?=Url::to(["post/"])?>" class="waves-effect"><i class="mdi mdi-buffer"></i><span> KDS kitchen </span></a>
                </li>
                <li>
                    <a href="<?=Url::to(["site/finish"])?>" class="waves-effect"><i class="mdi mdi-checkbox-marked-outline"></i><span> Completed Orders</span></a>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>