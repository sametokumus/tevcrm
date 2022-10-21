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
                            <li class="sub-category">
                                <h3>kablocu.com.tr</h3>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="dashboard.php"><i
                                            class="side-menu__icon fe fe-home"></i><span
                                            class="side-menu__label">Dashboard</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-package"></i><span
                                            class="side-menu__label">Ürün</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="add-product.php" class="slide-item"> Ürün Ekle</a></li>
                                    <li><a href="select-product.php" class="slide-item"> Ürün Güncelle</a></li>
<!--                                    <li><a href="brands.php" class="slide-item"> Markalar</a></li>-->
                                    <li><a href="product-types.php" class="slide-item"> Tür</a></li>
                                    <li><a href="product-categories.php" class="slide-item"> Kategori</a></li>
                                    <li><a href="product-variation-group-types.php" class="slide-item"> Varyasyon Grupları</a></li>
                                    <li><a href="product-tabs.php" class="slide-item"> Ürün Açıklama Sekmeleri</a></li>
                                    <li><a href="product-tags.php" class="slide-item"> Etiketler</a></li>
                                </ul>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-package"></i><span
                                            class="side-menu__label">Sipariş</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="select-orders.php?type=ongoing" class="slide-item"> Aktif Siparişler</a></li>
                                    <li><a href="select-orders.php?type=completed" class="slide-item"> Geçmiş Siparişler</a></li>
                                </ul>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-users"></i><span
                                            class="side-menu__label">Kullanıcılar</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="select-users.php?type=all" class="slide-item"> Tüm Kullanıcılar</a></li>
                                    <li><a href="select-users.php?type=1" class="slide-item"> Standart Kullanıcılar</a></li>
                                    <li><a href="select-users.php?type=2" class="slide-item"> Bayi Kullanıcıları</a></li>
                                    <li><a href="user-types.php" class="slide-item"> Kullanıcı Türleri</a></li>
                                    <li><a href="user-type-discounts.php" class="slide-item"> Kullanıcı Türü İskonto</a></li>
                                </ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="select-refunds.php"><i
                                            class="side-menu__icon fe fe-repeat"></i><span
                                            class="side-menu__label">İade Talepleri</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="select-cards.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Ödeme Yöntemleri</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="brands.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Markalar</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="group-discounts.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Toplu İskonto Tanımlama</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="coupons.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Kupon Yönetimi</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-package"></i><span
                                            class="side-menu__label">Anasayfa İçerik Yönetimi</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="sliders.php" class="slide-item"> Slider</a></li>
                                    <li><a href="category-banners.php" class="slide-item"> Kategori Banner</a></li>
                                    <li><a href="campaign-product.php" class="slide-item"> Kampanyalı Ürünler</a></li>
                                </ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="seos.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">SEO Yönetimi</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-package"></i><span
                                            class="side-menu__label">Rol Yönetimi</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="admins.php" class="slide-item"> Kullanıcılar</a></li>
                                    <li><a href="roles.php" class="slide-item"> Roller</a></li>
                                </ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i
                                            class="side-menu__icon fe fe-package"></i><span
                                            class="side-menu__label">Kargo</span><i
                                            class="angle fe fe-chevron-right"></i></a>
                                <ul class="slide-menu">
                                    <li><a href="default-delivery.php" class="slide-item"> Default Değerler</a></li>
                                    <li><a href="regional-delivery.php" class="slide-item"> Bölgesel Kargo</a></li>
                                </ul>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="popups.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Popup Yönetimi</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="subscribers.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Bülten Abonelikleri</span></a>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="user-actions.php"><i
                                            class="side-menu__icon fe fe-check-circle"></i><span
                                            class="side-menu__label">Kullanıcı Hareketleri</span></a>
                            </li>

<!--                            <li class="sub-category">-->
<!--                                <h3>UI Kit</h3>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-slack"></i><span-->
<!--                                            class="side-menu__label">Apps</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>-->
<!--                                    <li><a href="cards.html" class="slide-item"> Cards design</a></li>-->
<!--                                    <li><a href="calendar.html" class="slide-item"> Default calendar</a></li>-->
<!--                                    <li><a href="calendar2.html" class="slide-item"> Full calendar</a></li>-->
<!--                                    <li><a href="chat.html" class="slide-item"> Chat</a></li>-->
<!--                                    <li><a href="notify.html" class="slide-item"> Notifications</a></li>-->
<!--                                    <li><a href="sweetalert.html" class="slide-item"> Sweet alerts</a></li>-->
<!--                                    <li><a href="rangeslider.html" class="slide-item"> Range slider</a></li>-->
<!--                                    <li><a href="scroll.html" class="slide-item"> Content Scroll bar</a></li>-->
<!--                                    <li><a href="loaders.html" class="slide-item"> Loaders</a></li>-->
<!--                                    <li><a href="counters.html" class="slide-item"> Counters</a></li>-->
<!--                                    <li><a href="rating.html" class="slide-item"> Rating</a></li>-->
<!--                                    <li><a href="timeline.html" class="slide-item"> Timeline</a></li>-->
<!--                                    <li><a href="treeview.html" class="slide-item"> Treeview</a></li>-->
<!--                                    <li><a href="chart.html" class="slide-item"> Charts</a></li>-->
<!--                                    <li><a href="footers.html" class="slide-item"> Footers</a></li>-->
<!--                                    <li><a href="users-list.html" class="slide-item"> User List</a></li>-->
<!--                                    <li><a href="search.html" class="slide-item">Search</a></li>-->
<!--                                    <li><a href="crypto-currencies.html" class="slide-item"> Crypto-currencies</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-package"></i><span-->
<!--                                            class="side-menu__label">Bootstrap</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Bootstrap</a></li>-->
<!--                                    <li><a href="alerts.html" class="slide-item"> Alerts</a></li>-->
<!--                                    <li><a href="buttons.html" class="slide-item"> Buttons</a></li>-->
<!--                                    <li><a href="colors.html" class="slide-item"> Colors</a></li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Avatars</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a href="avatarsquare.html" class="sub-slide-item"> Avatar-Square</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="avatar-round.html" class="sub-slide-item"> Avatar-Rounded</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="avatar-radius.html" class="sub-slide-item"> Avatar-Radius</a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li><a href="dropdown.html" class="slide-item"> Drop downs</a></li>-->
<!--                                    <li><a href="listgroup.html" class="slide-item"> List Group</a></li>-->
<!--                                    <li><a href="tags.html" class="slide-item"> Tags</a></li>-->
<!--                                    <li><a href="pagination.html" class="slide-item"> Pagination</a></li>-->
<!--                                    <li><a href="navigation.html" class="slide-item"> Navigation</a></li>-->
<!--                                    <li><a href="typography.html" class="slide-item"> Typography</a></li>-->
<!--                                    <li><a href="breadcrumbs.html" class="slide-item"> Breadcrumbs</a></li>-->
<!--                                    <li><a href="badge.html" class="slide-item"> Badges / Pills</a></li>-->
<!--                                    <li><a href="panels.html" class="slide-item"> Panels</a></li>-->
<!--                                    <li><a href="thumbnails.html" class="slide-item"> Thumbnails</a></li>-->
<!--                                    <li><a href="offcanvas.html" class="slide-item"> Offcanvas</a></li>-->
<!--                                    <li><a href="toast.html" class="slide-item"> Toast</a></li>-->
<!--                                    <li><a href="scrollspy.html" class="slide-item"> Scrollspy</a></li>-->
<!--                                    <li><a href="mediaobject.html" class="slide-item"> Media Object</a></li>-->
<!--                                    <li><a href="accordion.html" class="slide-item"> Accordions</a></li>-->
<!--                                    <li><a href="tabs.html" class="slide-item"> Tabs</a></li>-->
<!--                                    <li><a href="modal.html" class="slide-item"> Modal</a></li>-->
<!--                                    <li><a href="tooltipandpopover.html" class="slide-item"> Tooltip and popover</a>-->
<!--                                    </li>-->
<!--                                    <li><a href="progress.html" class="slide-item"> Progress</a></li>-->
<!--                                    <li><a href="carousel.html" class="slide-item"> Carousels</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="sub-category">-->
<!--                                <h3>Pre-build Pages</h3>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-layers"></i><span-->
<!--                                            class="side-menu__label">Pages</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Pages</a></li>-->
<!--                                    <li><a href="profile.html" class="slide-item"> Profile</a></li>-->
<!--                                    <li><a href="editprofile.html" class="slide-item"> Edit Profile</a></li>-->
<!--                                    <li><a href="notify-list.html" class="slide-item"> Notifications List</a></li>-->
<!--                                    <li><a href="email-compose.html" class="slide-item"> Mail-Compose</a></li>-->
<!--                                    <li><a href="email-inbox.html" class="slide-item"> Mail-Inbox</a></li>-->
<!--                                    <li><a href="email-read.html" class="slide-item"> Mail-Read</a></li>-->
<!--                                    <li><a href="gallery.html" class="slide-item"> Gallery</a></li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Forms</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a href="form-elements.html" class="sub-slide-item"> Form Elements</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="form-layouts.html" class="sub-slide-item"> Form Layouts</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="form-advanced.html" class="sub-slide-item"> Form Advanced</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="form-editor.html" class="sub-slide-item"> Form Editor</a></li>-->
<!--                                            <li><a href="form-wizard.html" class="sub-slide-item"> Form Wizard</a></li>-->
<!--                                            <li><a href="form-validation.html" class="sub-slide-item"> Form-->
<!--                                                    Validation</a></li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Tables</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a href="tables.html" class="sub-slide-item">Default table</a></li>-->
<!--                                            <li><a href="datatable.html" class="sub-slide-item"> Data Tables</a></li>-->
<!--                                            <li><a href="edit-table.html" class="sub-slide-item"> Edit Tables</a></li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Extension</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a href="about.html" class="sub-slide-item"> About Company</a></li>-->
<!--                                            <li><a href="services.html" class="sub-slide-item"> Services</a></li>-->
<!--                                            <li><a href="faq.html" class="sub-slide-item"> FAQS</a></li>-->
<!--                                            <li><a href="terms.html" class="sub-slide-item"> Terms</a></li>-->
<!--                                            <li><a href="invoice.html" class="sub-slide-item"> Invoice</a></li>-->
<!--                                            <li><a href="pricing.html" class="sub-slide-item"> Pricing Tables</a></li>-->
<!--                                            <li><a href="settings.html" class="sub-slide-item"> Settings</a></li>-->
<!--                                            <li><a href="blog.html" class="sub-slide-item"> Blog</a></li>-->
<!--                                            <li><a href="blog-details.html" class="sub-slide-item"> Blog Details</a>-->
<!--                                            </li>-->
<!--                                            <li><a href="blog-post.html" class="sub-slide-item"> Blog Post</a></li>-->
<!--                                            <li><a href="empty.html" class="sub-slide-item"> Empty Page</a></li>-->
<!--                                            <li><a href="construction.html" class="sub-slide-item"> Under-->
<!--                                                    Construction</a></li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Switcher</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a class="sub-slide-item" href="switcher-1.html">Switcher Style 1</a>-->
<!--                                            </li>-->
<!--                                            <li><a class="sub-slide-item" href="switcher-2.html">Switcher Style 2</a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-shopping-bag"></i><span-->
<!--                                            class="side-menu__label">E-Commerce</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">E-Commerce</a></li>-->
<!--                                    <li><a href="shop.html" class="slide-item"> Shop</a></li>-->
<!--                                    <li><a href="shop-description.html" class="slide-item"> Product Details</a></li>-->
<!--                                    <li><a href="cart.html" class="slide-item"> Shopping Cart</a></li>-->
<!--                                    <li><a href="add-product.html" class="slide-item"> Add Product</a></li>-->
<!--                                    <li><a href="wishlist.html" class="slide-item"> Wishlist</a></li>-->
<!--                                    <li><a href="checkout.html" class="slide-item"> Checkout</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-folder"></i><span class="side-menu__label">File-->
<!--                                        Manager</span><span class="badge bg-pink side-badge">4</span><i-->
<!--                                            class="angle fe fe-chevron-right hor-angle"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">File Manager</a></li>-->
<!--                                    <li><a href="file-manager.html" class="slide-item"> File Manager</a></li>-->
<!--                                    <li><a href="filemanager-list.html" class="slide-item"> File Manager List</a></li>-->
<!--                                    <li><a href="filemanager-details.html" class="slide-item"> File Details</a></li>-->
<!--                                    <li><a href="file-attachments.html" class="slide-item"> File Attachments</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="sub-category">-->
<!--                                <h3>Misc Pages</h3>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-users"></i><span-->
<!--                                            class="side-menu__label">Authentication</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Authentication</a></li>-->
<!--                                    <li><a href="login.php" class="slide-item"> Login</a></li>-->
<!--                                    <li><a href="register.html" class="slide-item"> Register</a></li>-->
<!--                                    <li><a href="forgot-password.html" class="slide-item"> Forgot Password</a></li>-->
<!--                                    <li><a href="lockscreen.html" class="slide-item"> Lock screen</a></li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Error Pages</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a href="400.html" class="sub-slide-item"> 400</a></li>-->
<!--                                            <li><a href="401.html" class="sub-slide-item"> 401</a></li>-->
<!--                                            <li><a href="403.html" class="sub-slide-item"> 403</a></li>-->
<!--                                            <li><a href="404.html" class="sub-slide-item"> 404</a></li>-->
<!--                                            <li><a href="500.html" class="sub-slide-item"> 500</a></li>-->
<!--                                            <li><a href="503.html" class="sub-slide-item"> 503</a></li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">-->
<!--                                    <i class="side-menu__icon fe fe-cpu"></i>-->
<!--                                    <span class="side-menu__label">Submenu items</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Submenu items</a></li>-->
<!--                                    <li><a href="javascript:void(0)" class="slide-item">Submenu-1</a></li>-->
<!--                                    <li class="sub-slide">-->
<!--                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)"><span-->
<!--                                                    class="sub-side-menu__label">Submenu-2</span><i-->
<!--                                                    class="sub-angle fe fe-chevron-right"></i></a>-->
<!--                                        <ul class="sub-slide-menu">-->
<!--                                            <li><a class="sub-slide-item" href="javascript:void(0)">Submenu-2.1</a></li>-->
<!--                                            <li><a class="sub-slide-item" href="javascript:void(0)">Submenu-2.2</a></li>-->
<!--                                            <li class="sub-slide2">-->
<!--                                                <a class="sub-side-menu__item2" href="javascript:void(0)"-->
<!--                                                   data-bs-toggle="sub-slide2"><span-->
<!--                                                            class="sub-side-menu__label2">Submenu-2.3</span><i-->
<!--                                                            class="sub-angle2 fe fe-chevron-right"></i></a>-->
<!--                                                <ul class="sub-slide-menu2">-->
<!--                                                    <li><a href="javascript:void(0)" class="sub-slide-item2">Submenu-2.3.1</a></li>-->
<!--                                                    <li><a href="javascript:void(0)" class="sub-slide-item2">Submenu-2.3.2</a></li>-->
<!--                                                    <li><a href="javascript:void(0)" class="sub-slide-item2">Submenu-2.3.3</a></li>-->
<!--                                                </ul>-->
<!--                                            </li>-->
<!--                                            <li><a class="sub-slide-item" href="javascript:void(0)">Submenu-2.4</a></li>-->
<!--                                            <li><a class="sub-slide-item" href="javascript:void(0)">Submenu-2.5</a></li>-->
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="sub-category">-->
<!--                                <h3>General</h3>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a class="side-menu__item" href="widgets.html"><i-->
<!--                                            class="side-menu__icon fe fe-grid"></i><span-->
<!--                                            class="side-menu__label">Widgets</span></a>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-map-pin"></i><span-->
<!--                                            class="side-menu__label">Maps</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Maps</a></li>-->
<!--                                    <li><a href="maps1.html" class="slide-item">Leaflet Maps</a></li>-->
<!--                                    <li><a href="maps2.html" class="slide-item">Mapel Maps</a></li>-->
<!--                                    <li><a href="maps.html" class="slide-item">Vector Maps</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-bar-chart-2"></i><span-->
<!--                                            class="side-menu__label">Charts</span><span-->
<!--                                            class="badge bg-secondary side-badge">6</span><i-->
<!--                                            class="angle fe fe-chevron-right hor-angle"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Charts</a></li>-->
<!--                                    <li><a href="chart-chartist.html" class="slide-item">Chart Js</a></li>-->
<!--                                    <li><a href="chart-flot.html" class="slide-item"> Flot Charts</a></li>-->
<!--                                    <li><a href="chart-echart.html" class="slide-item"> ECharts</a></li>-->
<!--                                    <li><a href="chart-morris.html" class="slide-item"> Morris Charts</a></li>-->
<!--                                    <li><a href="chart-nvd3.html" class="slide-item"> Nvd3 Charts</a></li>-->
<!--                                    <li><a href="charts.html" class="slide-item"> C3 Bar Charts</a></li>-->
<!--                                    <li><a href="chart-line.html" class="slide-item"> C3 Line Charts</a></li>-->
<!--                                    <li><a href="chart-donut.html" class="slide-item"> C3 Donut Charts</a></li>-->
<!--                                    <li><a href="chart-pie.html" class="slide-item"> C3 Pie charts</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="slide">-->
<!--                                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i-->
<!--                                            class="side-menu__icon fe fe-wind"></i><span-->
<!--                                            class="side-menu__label">Icons</span><i-->
<!--                                            class="angle fe fe-chevron-right"></i></a>-->
<!--                                <ul class="slide-menu">-->
<!--                                    <li class="side-menu-label1"><a href="javascript:void(0)">Icons</a></li>-->
<!--                                    <li><a href="icons.html" class="slide-item"> Font Awesome</a></li>-->
<!--                                    <li><a href="icons2.html" class="slide-item"> Material Design Icons</a></li>-->
<!--                                    <li><a href="icons3.html" class="slide-item"> Simple Line Icons</a></li>-->
<!--                                    <li><a href="icons4.html" class="slide-item"> Feather Icons</a></li>-->
<!--                                    <li><a href="icons5.html" class="slide-item"> Ionic Icons</a></li>-->
<!--                                    <li><a href="icons6.html" class="slide-item"> Flag Icons</a></li>-->
<!--                                    <li><a href="icons7.html" class="slide-item"> pe7 Icons</a></li>-->
<!--                                    <li><a href="icons8.html" class="slide-item"> Themify Icons</a></li>-->
<!--                                    <li><a href="icons9.html" class="slide-item">Typicons Icons</a></li>-->
<!--                                    <li><a href="icons10.html" class="slide-item">Weather Icons</a></li>-->
<!--                                    <li><a href="icons11.html" class="slide-item">Bootstrap Icons</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
                        </ul>
                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                                                       width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                            </svg></div>
                    </div>
                </div>
                <!--/APP-SIDEBAR-->
            </div>
