<?php
ob_start();
session_start();
if(isset($_SESSION['userLogin'])){
    if($_SESSION['userLogin'] != false && $_SESSION['userLogin'] != null){
        $isLogin = true;
    }else{
        $isLogin = false;
    }
}else{
    $isLogin = true;
}

$extra_js="";
?>
    <!doctype html>
<html lang="tr" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Wimco Teknoloji A.Ş.">
    <meta name="author" content="Wimco Teknoloji A.Ş.">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/brand/favicon.png" />

    <!-- TITLE -->
    <title>Leni's Technologies</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.min.css">

    <!-- STYLE CSS -->
    <link href="css/style.css" rel="stylesheet" />
    <!--    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" />-->
    <link href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css" />
    <link href="css/dark-style.css" rel="stylesheet" />
    <link href="css/transparent-style.css" rel="stylesheet">
    <link href="css/skin-modes.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="css/icons.css" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="colors/color1.css" />

</head>

<body class="app sidebar-mini ltr light-mode">

<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="images/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        <!-- app-Header -->
        <div class="app-header header sticky">
            <div class="container-fluid main-container">
                <div class="d-flex">
                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
                    <!-- sidebar-toggle-->
                    <a class="logo-horizontal " href="dashboard.php">
                        <!--                            <img src="images/brand/logo.png" class="header-brand-img desktop-logo" alt="logo">-->
                        <!--                            <img src="images/brand/logo-3.png" class="header-brand-img light-logo1"-->
                        <!--                                 alt="logo">-->
                    </a>
                    <!-- LOGO -->
                    <div class="main-header-center ms-3 d-none d-lg-block">
                        <input class="form-control" placeholder="Search for results..." type="search">
                        <button class="btn px-0 pt-2"><i class="fe fe-search" aria-hidden="true"></i></button>
                    </div>
                    <div class="d-flex order-lg-2 ms-auto header-right-icons">
                        <div class="dropdown d-none">
                            <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                <i class="fe fe-search"></i>
                            </a>
                            <div class="dropdown-menu header-search dropdown-menu-start">
                                <div class="input-group w-100 p-2">
                                    <input type="text" class="form-control" placeholder="Search....">
                                    <div class="input-group-text btn btn-primary">
                                        <i class="fe fe-search" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- SEARCH -->
                        <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                        </button>
                        <div class="navbar navbar-collapse responsive-navbar p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2">
                                    <div class="dropdown d-lg-none d-flex">
                                        <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                                            <i class="fe fe-search"></i>
                                        </a>
                                        <div class="dropdown-menu header-search dropdown-menu-start">
                                            <div class="input-group w-100 p-2">
                                                <input type="text" class="form-control" placeholder="Search....">
                                                <div class="input-group-text btn btn-primary">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- COUNTRY -->
                                    <div class="d-flex country">
                                        <a class="nav-link icon text-center" data-bs-target="#country-selector"
                                           data-bs-toggle="modal">
                                            <i class="fe fe-globe"></i><span
                                                class="fs-16 ms-2 d-none d-xl-block">English</span>
                                        </a>
                                    </div>
                                    <!-- SEARCH -->
                                    <div class="dropdown  d-flex">
                                        <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                            <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                            <span class="light-layout"><i class="fe fe-sun"></i></span>
                                        </a>
                                    </div>
                                    <!-- Theme-Layout -->
                                    <div class="dropdown d-flex">
                                        <a class="nav-link icon full-screen-link nav-link-bg">
                                            <i class="fe fe-minimize fullscreen-button"></i>
                                        </a>
                                    </div>
                                    <!-- FULL-SCREEN -->
                                    <div class="dropdown  d-flex notifications">
                                        <a class="nav-link icon" data-bs-toggle="dropdown"><i
                                                class="fe fe-bell"></i><span class=" pulse"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading border-bottom">
                                                <div class="d-flex">
                                                    <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="notifications-menu">
                                                <a class="dropdown-item d-flex" href="notify-list.html">
                                                    <div
                                                        class="me-3 notifyimg  bg-primary brround box-shadow-primary">
                                                        <i class="fe fe-mail"></i>
                                                    </div>
                                                    <div class="mt-1">
                                                        <h5 class="notification-label mb-1">New Application received
                                                        </h5>
                                                        <span class="notification-subtext">3 days ago</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="notify-list.html">
                                                    <div
                                                        class="me-3 notifyimg  bg-secondary brround box-shadow-secondary">
                                                        <i class="fe fe-check-circle"></i>
                                                    </div>
                                                    <div class="mt-1">
                                                        <h5 class="notification-label mb-1">Project has been
                                                            approved</h5>
                                                        <span class="notification-subtext">2 hours ago</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="notify-list.html">
                                                    <div
                                                        class="me-3 notifyimg  bg-success brround box-shadow-success">
                                                        <i class="fe fe-shopping-cart"></i>
                                                    </div>
                                                    <div class="mt-1">
                                                        <h5 class="notification-label mb-1">Your Product Delivered
                                                        </h5>
                                                        <span class="notification-subtext">30 min ago</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="notify-list.html">
                                                    <div class="me-3 notifyimg bg-pink brround box-shadow-pink">
                                                        <i class="fe fe-user-plus"></i>
                                                    </div>
                                                    <div class="mt-1">
                                                        <h5 class="notification-label mb-1">Friend Requests</h5>
                                                        <span class="notification-subtext">1 day ago</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a href="notify-list.html"
                                               class="dropdown-item text-center p-3 text-muted">View all
                                                Notification</a>
                                        </div>
                                    </div>
                                    <!-- NOTIFICATIONS -->
                                    <div class="dropdown  d-flex message">
                                        <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                                            <i class="fe fe-message-square"></i><span class="pulse-danger"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading border-bottom">
                                                <div class="d-flex">
                                                    <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">You have 5
                                                        Messages</h6>
                                                    <div class="ms-auto">
                                                        <a href="javascript:void(0)" class="text-muted p-0 fs-12">make all unread</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="message-menu">
                                                <a class="dropdown-item d-flex" href="chat.html">
                                                        <span
                                                            class="avatar avatar-md brround me-3 align-self-center cover-image"
                                                            data-bs-image-src="images/users/1.jpg"></span>
                                                    <div class="wd-90p">
                                                        <div class="d-flex">
                                                            <h5 class="mb-1">Peter Theil</h5>
                                                            <small class="text-muted ms-auto text-end">
                                                                6:45 am
                                                            </small>
                                                        </div>
                                                        <span>Commented on file Guest list....</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="chat.html">
                                                        <span
                                                            class="avatar avatar-md brround me-3 align-self-center cover-image"
                                                            data-bs-image-src="images/users/15.jpg"></span>
                                                    <div class="wd-90p">
                                                        <div class="d-flex">
                                                            <h5 class="mb-1">Abagael Luth</h5>
                                                            <small class="text-muted ms-auto text-end">
                                                                10:35 am
                                                            </small>
                                                        </div>
                                                        <span>New Meetup Started......</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="chat.html">
                                                        <span
                                                            class="avatar avatar-md brround me-3 align-self-center cover-image"
                                                            data-bs-image-src="images/users/12.jpg"></span>
                                                    <div class="wd-90p">
                                                        <div class="d-flex">
                                                            <h5 class="mb-1">Brizid Dawson</h5>
                                                            <small class="text-muted ms-auto text-end">
                                                                2:17 pm
                                                            </small>
                                                        </div>
                                                        <span>Brizid is in the Warehouse...</span>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="chat.html">
                                                        <span
                                                            class="avatar avatar-md brround me-3 align-self-center cover-image"
                                                            data-bs-image-src="images/users/4.jpg"></span>
                                                    <div class="wd-90p">
                                                        <div class="d-flex">
                                                            <h5 class="mb-1">Shannon Shaw</h5>
                                                            <small class="text-muted ms-auto text-end">
                                                                7:55 pm
                                                            </small>
                                                        </div>
                                                        <span>New Product Realease......</span>
                                                    </div>
                                                </a>

                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a href="javascript:void(0)" class="dropdown-item text-center p-3 text-muted">See all
                                                Messages</a>
                                        </div>
                                    </div>
                                    <!-- MESSAGE-BOX -->
                                    <div class="dropdown d-flex header-settings">
                                        <a href="javascript:void(0);" class="nav-link icon"
                                           data-bs-toggle="sidebar-right" data-target=".sidebar-right">
                                            <i class="fe fe-align-right"></i>
                                        </a>
                                    </div>
                                    <!-- SIDE-MENU -->
                                    <div class="dropdown d-flex profile-1">
                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                            <img src="images/users/21.jpg" alt="profile-user"
                                                 class="avatar  profile-user brround cover-image">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading">
                                                <div class="text-center">
                                                    <h5 class="text-dark mb-0 fs-14 fw-semibold">Percy Kewshun</h5>
                                                    <small class="text-muted">Senior Admin</small>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider m-0"></div>
                                            <a class="dropdown-item" href="profile.html">
                                                <i class="dropdown-icon fe fe-user"></i> Profile
                                            </a>
                                            <a class="dropdown-item" href="email-inbox.html">
                                                <i class="dropdown-icon fe fe-mail"></i> Inbox
                                                <span class="badge bg-primary rounded-pill float-end">5</span>
                                            </a>
                                            <a class="dropdown-item" href="lockscreen.html">
                                                <i class="dropdown-icon fe fe-lock"></i> Lockscreen
                                            </a>
                                            <a class="dropdown-item" href="login.php">
                                                <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /app-Header -->

        <!--APP-SIDEBAR-->
        <div class="sticky">
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            <div class="app-sidebar">
                <div class="side-header">
                    <a class="header-brand1" href="dashboard.php">
                        <img src="images/brand/logo.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="images/brand/logo-1.png" class="header-brand-img toggle-logo" alt="logo">
                        <img src="images/brand/logo-2.png" class="header-brand-img light-logo" alt="logo">
                        <img src="images/brand/logo-3.png" class="header-brand-img light-logo1" alt="logo">
                    </a>
                    <!-- LOGO -->
                </div>
                <div class="main-sidemenu">
                    <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                                                                          fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                        </svg></div>
                    <ul class="side-menu">
                        <li class="slide">
                            <a class="side-menu__item" data-bs-toggle="slide" href="/dashboard"><i
                                    class="side-menu__icon fe fe-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>

                        <li class="slide">
                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                    class="side-menu__icon fe fe-package"></i><span
                                    class="side-menu__label">Rol Yönetimi</span><i
                                    class="angle fe fe-chevron-right"></i></a>
                            <ul class="slide-menu">
                                <li><a href="/admins" class="slide-item"> Kullanıcılar</a></li>
                                <li><a href="/roles" class="slide-item"> Roller</a></li>
                            </ul>
                        </li>


                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                                                   width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                        </svg></div>
                </div>
            </div>
            <!--/APP-SIDEBAR-->
        </div>
