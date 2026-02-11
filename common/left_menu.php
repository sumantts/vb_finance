<div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="?p=dashboard" class="logo logo-light">
                <span class="logo-lg">
                    <img src="assets/images/logo-sm.png" alt="logo" style="width: 120px; height: 50px; margin-top: 5px;">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>



            <!-- Brand Logo Dark -->
            <a href="?p=dashboard" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="assets/images/logo-sm.png" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <!-- Sidebar -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!-- Leftbar User -->
                <div class="leftbar-user">
                    <a href="pages-profile.html">
                        <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42" class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name mt-2"><?=$_SESSION['UsrNm']?></span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="side-nav">
                    <li class="side-nav-item">
                        <a href="?p=dashboard" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Dashboards </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="./events_calendar" target="_blank" class="side-nav-link">
                            <i class="uil-calender"></i>
                            <span> Calendar </span>
                        </a>
                    </li>

                    <!-- <li class="side-nav-title">Configuration</li> -->

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarConfiguration" aria-controls="sidebarConfiguration" class="side-nav-link" aria-expanded="false" >
                            <i class="uil-bright"></i>
                            <span> Configuration </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse <?php if($p == 'user-group' || $p == 'user-details' || $p == 'user-details' || $p == 'room-type' || $p == 'room-details' || $p == 'price-manager' || $p == 'special-price-manager' || $p == 'menu-category' || $p == 'menu-details' || $p == 'travel-agent' || $p == 'paid-services' || $p == 'employees' || $p == 'coupon-management' || $p == 'expensestype' || $p == 'resort-setup' || $p == 'settings' || $p == 'backup'){?>show<?php } ?>" id="sidebarConfiguration">
                            <ul class="side-nav-second-level">
                                <li <?php if($p == 'user-group'){?>class="active"<?php } ?>> <a href="?p=user-group">User Group</a> </li>
                                <li <?php if($p == 'user-details'){?>class="active"<?php } ?>> <a href="?p=user-details">User Details</a> </li>
                                <li <?php if($p == 'room-type'){?>class="active"<?php } ?>> <a href="?p=room-type">Room Type</a> </li>
                                <li <?php if($p == 'room-details'){?>class="active"<?php } ?>> <a href="?p=room-details">Room Details</a> </li>
                                <li <?php if($p == 'price-manager'){?>class="active"<?php } ?>> <a href="?p=price-manager">Price Manager</a> </li>
                                <li <?php if($p == 'special-price-manager'){?>class="active"<?php } ?>> <a href="?p=special-price-manager">Special Price Manager</a> </li>
                                <li  <?php if($p == 'menu-category'){?>class="active"<?php } ?>> <a href="?p=menu-category">Menu Category</a> </li>
                                <li  <?php if($p == 'menu-details'){?>class="active"<?php } ?>> <a href="?p=menu-details">Menu Details</a> </li>
                                <li  <?php if($p == 'travel-agent'){?>class="active"<?php } ?>> <a href="?p=travel-agent">Travel Agent</a> </li>
                                <li  <?php if($p == 'paid-services'){?>class="active"<?php } ?>> <a href="?p=paid-services">Paid Services</a> </li>
                                <li  <?php if($p == 'employees'){?>class="active"<?php } ?>> <a href="?p=employees">Employees</a> </li>
                                <li  <?php if($p == 'coupon-management'){?>class="active"<?php } ?>> <a href="?p=coupon-management">Coupon Management</a> </li>
                                <li  <?php if($p == 'expensestype'){?>class="active"<?php } ?>> <a href="?p=expensestype">Expenses Type</a> </li>
                                <li  <?php if($p == 'resort-setup'){?>class="active"<?php } ?>> <a href="?p=resort-setup">Resort Setup</a> </li>
                                <li  <?php if($p == 'settings'){?>class="active"<?php } ?>> <a href="?p=settings">Settings</a> </li>
                                <li  <?php if($p == 'backup'){?>class="active"<?php } ?>> <a href="">Backup</a> </li>
                            </ul>
                        </div>
                    </li> 

                    <!-- GUEST -->
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarGuest" aria-expanded="false" aria-controls="sidebarGuest" class="side-nav-link">
                            <i class="uil-users-alt"></i>
                            <span> Guest </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse <?php if($p == 'booking' || $p == 'reservation' || $p == 'add-payments' || $p == 'food-order' || $p == 'service-order' || $p == 'booking-extend' || $p == 'checkout-billing'){?>show<?php } ?>" id="sidebarGuest">
                            <ul class="side-nav-second-level">
                                <li <?php if($p == 'booking'){?>class="active"<?php } ?>> <a href="?p=booking">Booking</a> </li>
                                <li <?php if($p == 'reservation'){?>class="active"<?php } ?>> <a href="?p=reservation">Reservation/Allotment</a> </li>
                                <li <?php if($p == 'add-payments'){?>class="active"<?php } ?>> <a href="?p=add-payments">Add Payments</a> </li>
                                <li <?php if($p == 'food-order'){?>class="active"<?php } ?>> <a href="?p=food-order">Food Order</a> </li>
                                <li <?php if($p == 'service-order'){?>class="active"<?php } ?>> <a href="?p=service-order">Service Order</a> </li>
                                <li <?php if($p == 'booking-extend'){?>class="active"<?php } ?>> <a href="?p=booking-extend">Booking Extend</a> </li>
                                <li <?php if($p == 'checkout-billing'){?>class="active"<?php } ?>> <a href="?p=checkout-billing">Checkout & Billing</a> </li>
                                <li> <a href="">Invoices</a> </li> 
                            </ul>
                        </div>
                    </li> 

                    <!-- Inhouse -->
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarInhouse" aria-expanded="false" aria-controls="sidebarInhouse" class="side-nav-link">
                            <i class="uil-abacus"></i>
                            <span> Inhouse </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarInhouse">
                            <ul class="side-nav-second-level">
                                <li> <a href="">Housekeeping</a> </li>
                                <li> <a href="">Restaurant Order</a> </li>
                                <li> <a href="">Expenses</a> </li>
                                <li> <a href="">Stock</a> </li>
                                <li> <a href="">Complain</a> </li> 
                            </ul>
                        </div>
                    </li> 

                    <!-- Reports -->
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false" aria-controls="sidebarReports" class="side-nav-link">
                            <i class="uil-chart-pie-alt"></i>
                            <span> Reports </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse <?php if($p == 'booking-register' || $p == 'guest-register' || $p == 'collection-register' || $p == 'expenses-register'){?>show<?php } ?>" id="sidebarReports">
                            <ul class="side-nav-second-level">
                                <li <?php if($p == 'booking-register'){?>class="active"<?php } ?>> <a href="?p=booking-register">Booking Register</a> </li>
                                <li <?php if($p == 'guest-register'){?>class="active"<?php } ?>> <a href="?p=guest-register">Guest Register</a> </li>
                                <li <?php if($p == 'collection-register'){?>class="active"<?php } ?>> <a href="?p=collection-register">Collection Register</a> </li>
                                <li <?php if($p == 'expenses-register'){?>class="active"<?php } ?>> <a href="?p=expenses-register">Expenses Register</a> </li>

                                <!-- <li> <a href="">Occupancy Report</a> </li>
                                <li> <a href="">Availability Calendar</a> </li>
                                <li> <a href="">Expenses Ledger</a> </li>
                                <li> <a href="">Agent Commsssion</a> </li>
                                <li> <a href="">Car Hire Charges</a> </li>  -->
                            </ul>
                        </div>
                    </li> 

                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>