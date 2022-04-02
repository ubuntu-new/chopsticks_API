<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <a href="index.html" class="logo">
            <span>
                <img src="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/assets/images/logo.svg" alt="" height="22">
            </span>
            <i>
                <img  src="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/assets/images/logo.svg"  src="assets/images/logo.svg" alt="" height="22">
            </i>
        </a>
    </div>
    <?php include "includes/connect_db.php"?>
    <nav class="navbar-custom">

        <ul class="navbar-right d-flex list-inline float-right mb-0">
            <li class="dropdown notification-list font-14 pt-3">
                <!-- item-->
                <a class="dropdown-item text-muted" href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/logoutAdmin.php"><i class="mdi mdi-logout font-18"></i> бѓ’бѓђбѓЎбѓ•бѓљбѓђ</a>
                </li>
        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>

        </ul>
        <ul class="list-inline menu-left mb-0">
            <?php
             if($_SERVER['REQUEST_URI'] == '/new/main.php' || $_SERVER['REQUEST_URI'] == '/new/job_time_new.php') :
            ?>
            <li class="d-none d-sm-block float-left" style="width: 50%">
                <div class="row">
                    <div class="col-4 pt-3 ">
                        <input  class="flatpickr waves-effect form-control mrgvlovani" type="text" data-mode="range" id="endDate" placeholder="бѓбѓњбѓўбѓ”бѓ бѓ•бѓђбѓљбѓбѓЎ бѓђбѓ бѓ©бѓ”бѓ•бѓђ">
                    </div>
                    <div class="col-md-3 pt-3">
                        <select class="custom-select " id="queue">
                            <?php
                            $stmt = $conn->query("SELECT * FROM queues");
                            while ($queue = $stmt->fetch_object()){
                                $checked = "";
                                if ($_SESSION["queue"] == $queue->queue_num)  $checked = "selected";
                                echo ' 
                                <option  value="'.$queue->queue_num.'" '.$checked.'>'.$queue->queue_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div style="width: 10%; float: left" class="pt-3 col-sm-1">
                        <div class="btn btn-primary" id="show_queue">бѓ«бѓ”бѓ‘бѓњбѓђ</div>
                    </div>
                </div>

            </li>
            <?php endif;
            if ($_SERVER['REQUEST_URI'] == '/new/oper_page.php') :
            ?>
            <li class="float-left operStatBtn">
                <div class="col-12 pt-3 d-inline-block">
                    <button type="button" class="changeStatus btn  btn-success waves-effect waves-light  operatorStatBtn"  data-stat="not_in_use">
                        <i class="mdi mdi-phone-incoming"></i> бѓЁбѓ”бѓ›бѓќбѓ›бѓђбѓ•бѓђбѓљбѓ
                    </button>
                </div>
            </li>
            <li class=" float-left operStatBtn">
                <div class="col-12 pt-3 d-inline-block">
                    <button type="button" class="changeStatus btn btn-success waves-effect waves-light operatorStatBtn"  data-stat="paused">
                        <i class="mdi mdi-pause"></i> бѓЁбѓ”бѓЎбѓ•бѓ”бѓњбѓ”бѓ‘бѓђ
                    </button>
                </div>
            </li>
            <li class="float-left operStatBtn">
                <div class="col-12 pt-3 d-inline-block">
                    <button type="button" class="changeStatus btn btn-success  waves-effect waves-light operatorStatBtn"  data-stat="outgoing">
                        <i class="mdi mdi-phone-outgoing"></i> бѓ’бѓђбѓ›бѓђбѓ•бѓђбѓљбѓ
                    </button>
                </div>
            </li>
            <?php endif; ?>
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
                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/main.php" class="waves-effect">
                        <i class="mdi mdi-home"></i> <span> бѓ›бѓ—бѓђбѓ•бѓђбѓ бѓ </span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="mdi mdi-account"></i>
                        <span> бѓќбѓћбѓ”бѓ бѓђбѓўбѓќбѓ бѓ
                                <span class="float-right menu-arrow">
                                    <i class="mdi mdi-chevron-right"></i>
                                </span>
                            </span>
                    </a>
                    <ul class="submenu">
                        <li><a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/oper_page.php">бѓќбѓћбѓ”бѓ бѓђбѓўбѓќбѓ бѓбѓЎ бѓ›бѓќбѓ“бѓЈбѓљбѓ</a></li>
                        <li><a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/userRegister.php">бѓ бѓ”бѓ’бѓбѓЎбѓўбѓ бѓђбѓЄбѓбѓђ</a></li>
                        <li><a href="#">бѓЎбѓўбѓђбѓўбѓбѓЎбѓўбѓбѓ™бѓђ</a></li>

                    </ul>
                </li>

                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/live" class="waves-effect"><i class="mdi mdi-rocket"></i><span> LIVE </span></a>
                </li>
                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/search.php" class="waves-effect"><i class="mdi mdi-magnify"></i><span> бѓ«бѓ”бѓ‘бѓњбѓђ </span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <span> CRM
                                <span class="float-right menu-arrow">
                                    <i class="mdi mdi-chevron-right"></i>
                                </span>
                            </span>
                    </a>
                    <ul class="submenu">
                        <li><a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/crm.php">бѓ бѓ”бѓћбѓќбѓ бѓўбѓ</a></li>
                        <li><a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/reason.php">бѓЁбѓ”бѓњбѓбѓЁбѓ•бѓњбѓбѓЎ бѓ“бѓђбѓ›бѓђбѓўбѓ”бѓ‘бѓђ</a></li>
                    </ul>
                </li>
                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/job_time_new.php" class="waves-effect"><i class=" mdi mdi-timetable "></i><span> бѓўбѓђбѓ‘бѓ”бѓљбѓ </span></a>
                </li>
                <li>
                    <a href="#" class="waves-effect"><i class=" mdi mdi-comment-question-outline "></i><span> FAQ </span></a>
                </li>

                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/phonebook.php" class="waves-effect bpgArial"><i class=" mdi mdi-book-open"></i><span> PhoneBook </span></a>
                </li>

                <li>
                    <a href="http://<?php echo   $_SERVER['SERVER_NAME']?>/new/timeConditions.php" class="waves-effect"><i class="mdi mdi-clock "></i><span> бѓЎбѓђбѓ›бѓЈбѓЁбѓђбѓќ бѓ“бѓ бѓќ </span></a>
                </li>


            </ul>

        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->