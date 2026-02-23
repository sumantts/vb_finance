<div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="?p=dashboard" class="logo logo-light">
                <span class="logo-lg">
                    <img src="assets/images/<?=$logo?>" alt="logo" style="width: 100%;height: 50px;margin-top: 5px;">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/<?=$logo?>" alt="small logo">
                </span>
            </a>



            <!-- Brand Logo Dark -->
            <a href="?p=dashboard" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="assets/images/<?=$logo?>" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/<?=$logo?>" alt="small logo">
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
                    <!-- <li class="side-nav-title">Navigation</li> -->                    
                    <li class="side-nav-item">
                        <a href="?p=dashboard" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Dashboards </span>
                        </a>
                    </li>

                    <!-- <li class="side-nav-title">Master</li> --> 

                    <!-- Admin -->
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarMaster" aria-expanded="false" aria-controls="sidebarMaster" class="side-nav-link">
                            <i class="uil-bright"></i>
                            <span> Master </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse <?php if($p == 'category' || $p == 'company'){?>show<?php } ?>" id="sidebarMaster">
                            <ul class="side-nav-second-level">
                                <li <?php if($p == 'company'){?>class="active"<?php } ?>> <a href="?p=company">Company</a> </li> 
                                <li <?php if($p == 'category'){?>class="active"<?php } ?>> <a href="?p=category">Category</a> </li>                                 
                            </ul>
                        </div>
                    </li>                     

                    <li class="side-nav-item <?php if($p == 'bank-data'){?>menuitem-active<?php } ?>">
                        <a href="?p=bank-data" class="side-nav-link">
                            <i class="uil-user-plus"></i>
                            <span> Bank Data </span>
                        </a>
                    </li>                     

                    <li class="side-nav-item <?php if($p == 'sales-data'){?>menuitem-active<?php } ?>">
                        <a href="?p=sales-data" class="side-nav-link">
                            <i class="uil-user-plus"></i>
                            <span> Sales Data </span>
                        </a>
                    </li> 
                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>