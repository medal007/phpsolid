<?php

$request_uri = $_SERVER['REQUEST_URI'];
// echo $request_uri;

$whole = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = rtrim($request_uri, '/');
$url = filter_var($request_uri, FILTER_SANITIZE_URL);
$url = explode('/', $url);


$currentpage = (string) $url[2];

if($currentpage == "viewmultipager" || $currentpage == "viewonepager" || $currentpage == "viewrestrictedpager"){
    $currentpage = (string) $url[3];
}


$getroleadmin = dbquery("SELECT * FROM ".$table1." WHERE id = '".dbescape($psid)."'")->rows;
$roleadmin = $getroleadmin[0]['role'];


$getgrouprole = dbquery("SELECT * FROM ".$table6." WHERE id = '".dbescape($roleadmin)."'")->rows;

$roles = $getgrouprole[0]['role'];

$roles = explode(", ", $roles);


if(in_array($currentpage, $roles)){
    
} else{
    echo '<script language="javascript">';
    echo 'alert("You do not have access to this page!!!")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../dashboard/" />';
    exit;
}

?>
<!-- Topbar Start -->
            <div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-right mb-0">
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="badge badge-danger rounded-circle noti-icon-badge"><?php echo $countnotification ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="font-16 m-0">
                                    Notification
                                </h5>
                            </div>

                            <div class="slimscroll noti-scroll">
                                <?php 
                                    foreach($getnotification as $notification){
                                        $message = $notification['message'];
                                        $date = $notification['date_created'];
                                        $ago = getdiff($date);
                                ?>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <p data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $message ?>" class="notify-details"><?php echo $message ?> <small class="text-muted"><?php echo $ago ?></small></p>
                                </a>
                        
                                <?php } ?>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="<?php echo $image ?>" alt="user-image" class="rounded-circle">
                            <span class="pro-user-name ml-1">
                                    <?php echo $fullname ?> <i class="fas fa-chevron-down fa-xs"></i> 
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="../profile/" class="dropdown-item notify-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="../logout/" class="dropdown-item notify-item">
                                <i class="fas fa-power-off"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a href="../settings/" class="nav-link right-bar-toggle">
                            <i class="fas fa-cog fa-lg"></i>
                        </a>
                    </li>


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    
                    <a href="../dashboard/" class="logo text-center logo-dark">
                        <span class="logo-lg">
                            <img src="<?php echo $icon ?>" alt="" height="56">
                            <!-- <span class="logo-lg-text-dark">Simple</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">S</span> -->
                            <img src="<?php echo $icon ?>" alt="" height="52">
                        </span>
                    </a>

                    <a href="../dashboard/" class="logo text-center logo-light">
                        <span class="logo-lg">
                            <img src="<?php echo $icon ?>" alt="" height="56">
                            <!-- <span class="logo-lg-text-light">Simple</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-light">S</span> -->
                            <img src="<?php echo $icon ?>" alt="" height="52">
                        </span>
                    </a>
                </div>

            </div>
            <!-- end Topbar --> <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">


                <div class="user-box">
                        <div class="float-left">
                            <img src="<?php echo $image ?>" alt="" class="avatar-md rounded-circle">
                        </div>
                        <div class="user-info">
                            <a href="#"><?php echo $fullname ?></a>
                            <p class="text-muted m-0"><?php echo $email ?></p>
                        </div>
                    </div>
    
            <!--- Sidemenu -->
            <div id="sidebar-menu">
    
                <ul class="metismenu" id="side-menu">
                    <hr>
                    <li class="menu-title">Menus</li>
                    <hr>
                    
                    <?php if(in_array("dashboard", $roles)){ ?>
    
                    <li>
                        <a href="../dashboard/">
                            <i class="fas fa-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    
                    <?php } ?>
                    
                    
                    <?php if(in_array("adminusers", $roles)){ ?>
                    
                    <li>
                        <a href="../adminusers/">
                            <i class="fas fa-users"></i>
                            <span> Admin Users </span>
                        </a>
                    </li>
                    
                    <?php } ?>
                    
                    <?php if(in_array("admingroups", $roles)){ ?>
                    
                    <li>
                        <a href="../admingroups/">
                            <i class="fas fa-users-cog"></i>
                            <span> User Groups </span>
                        </a>
                    </li>
                    
                    <?php } ?>
                    
                    <?php if(in_array("adminpages", $roles)){ ?>
                    
                    <li>
                        <a href="../adminpages/">
                            <i class="fas fa-layer-group"></i>
                            <span>Manage Pages</span>
                        </a>
                    </li>
                    
                    <?php } ?>
                    
                    <?php 
                        $getadmin = dbquery("SELECT * FROM ".$table3." ORDER BY pagenumber ASC")->rows;
                        foreach($getadmin as $admin){
                            $pid = $admin['id'];
                            $pagename = $admin['pagename'];
                            $pagenumber = $admin['pagenumber'];
                            $pagestatus = $admin['pagestatus'];
                            $pageicon = $admin['pageicon'];
                            $date = $admin['date_created'];
                            $ago = getdiff($date);
                            
                            if(in_array("{$pid}", $roles)){ 
                    ?>
                    
                    <?php if($pagestatus == "onepager"){ ?>
                    
                    <li>
                        <a href="../viewonepager/<?php echo $pid ?>">
                            <i class="<?php echo $pageicon ?>"></i>
                            <span><?php echo $pagename ?></span>
                        </a>
                    </li>
                    <?php } else if($pagestatus == "multipager") { ?>
                    
                    <li>
                        <a href="../viewmultipager/<?php echo $pid ?>">
                            <i class="<?php echo $pageicon ?>"></i>
                            <span><?php echo $pagename ?></span>
                        </a>
                    </li>
                    
                    
                    <?php } else if($pagestatus == "restrictedpager") { ?>
                    
                    <li>
                        <a href="../viewrestrictedpager/<?php echo $pid ?>">
                            <i class="<?php echo $pageicon ?>"></i>
                            <span><?php echo $pagename ?></span>
                        </a>
                    </li>
                    
                    <?php }}} ?>
    
                </ul>
    
            </div>
            <!-- End Sidebar -->
    
            <div class="clearfix"></div>

    
    </div>
    <!-- Left Sidebar End -->