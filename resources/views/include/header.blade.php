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
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="current-locale" content="{{ app()->getLocale() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Technical Universal Verification</title>
    <base href="/">

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json"/>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <!-- bootstrap icons -->
{{--    <link rel="stylesheet" href="vendor/bootstrap-icons%401.8.1/font/bootstrap-icons.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- chosen css -->
    <link rel="stylesheet" href="vendor/chosen_v1.8.7/chosen.min.css">

    <!-- date range picker -->
    <link rel="stylesheet" href="vendor/daterangepicker/daterangepicker.css">

    <!-- swiper carousel css -->
    <link rel="stylesheet" href="vendor/swiper-7.3.1/swiper-bundle.min.css">

    <!-- simple lightbox css -->
    <link rel="stylesheet" href="vendor/simplelightbox/simple-lightbox.min.css">

    <!-- app tour css -->
    <link rel="stylesheet" href="vendor/Product-Tour-Plugin-jQuery/lib.css">

    <!-- Footable table master css -->
{{--    <link rel="stylesheet" href="vendor/fooTable/css/footable.bootstrap.min.css">--}}

    <!-- datatable css -->
    <link href="vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />

    <link href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css" />
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet" />
    <link href="vendor/datatables.net/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet" />

    <!-- style css for this template -->
    <link href="scss/style.css" rel="stylesheet">
    <link href="scss/customize.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100 sidebar-pushcontent" data-sidebarstyle="sidebar-pushcontent">
<!-- sidebar-pushcontent, sidebar-overlay , sidebar-fullscreen  are classes to switch UI here-->

<!-- page loader -->
<div class="container-fluid h-100 position-fixed loader-wrap bg-blur">
    <div class="row justify-content-center h-100">
        <div class="col-auto align-self-center text-center">
            <div class="doors animated mb-4">
                <div class="left-door"></div>
                <div class="right-door"></div>
            </div>
            <h5 class="mb-0">Sayfa yükleniyor</h5>
            <p class="text-secondary small">Anlayışınız için teşekkürler</p>
            <div class="spinner-border text-primary mt-3" role="status">
                <span class="visually-hidden">Yükleniyor...</span>
            </div>
        </div>
    </div>
</div>
<!-- page loader ends -->

<!-- background -->
<div class="coverimg h-100 w-100 top-0 start-0 main-bg">
    <div class="bg-blur main-bg-overlay"></div>
    <img src="img/bg-14.jpg" alt=""/>
</div>
<!-- background ends  -->

<!-- Header -->
<header class="header">
    <!-- Fixed navbar -->
    <nav class="navbar fixed-top">
        <div class="container-fluid">
            <div class="sidebar-width">
                <button class="btn btn-link btn-square menu-btn" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a class="navbar-brand ms-2" href="my-dashboard">
                    <div class="row">
                        <div class="col-auto"><img src="img/favicon48.png" class="mx-100" alt=""/></div>
                        <div class="col ps-0 align-self-center">
                            <h5 class="fw-normal text-dark">CRM</h5>
                            <p class="small text-secondary">Technical Universal <br>Verification</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="search-header d-none d-xl-block">
                <div class="input-group input-group-md w-300">
                    <span class="input-group-text text-theme"><i class="bi bi-search"></i></span>
                    <input class="form-control pe-0" type="search" placeholder="Arama..."
                           id="searchglobal">
                    <div class="dropdown input-group-text">
                        <button class="btn btn-link dd-arrow-none dropdown-toggle" type="button" id="searchfilter"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sliders"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-dontclose mt-2 w-300"
                             aria-labelledby="searchfilter">
                            <ul class="nav nav-WinDOORS" id="searchtab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="searchall-tab" data-bs-toggle="tab"
                                            data-bs-target="#searchall" type="button" role="tab"
                                            aria-controls="searchall" aria-selected="true">All
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="searchorders-tab" data-bs-toggle="tab"
                                            data-bs-target="#searchorders" type="button" role="tab"
                                            aria-controls="searchorders" aria-selected="false">Orders
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="searchcontacts-tab" data-bs-toggle="tab"
                                            data-bs-target="#searchcontacts" type="button" role="tab"
                                            aria-controls="searchcontacts" aria-selected="false">Contacts
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content py-3" id="searchtabContent">
                                <div class="tab-pane fade show active" id="searchall" role="tabpanel"
                                     aria-labelledby="searchall-tab">
                                    <div class="dropdown-item mb-3">
                                        <div class="input-group input-group-md border rounded">
                                            <span class="input-group-text text-theme"><i class="bi bi-code-square"></i></span>
                                            <select class="form-control simplechosen">
                                                <option>Successful Order</option>
                                                <option>Full-filled Order</option>
                                                <option>Rejected Order</option>
                                                <option>Delivery Staff</option>
                                                <option>PM Employees</option>
                                            </select>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Search apps</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch1">
                                                        <label class="form-check-label" for="searchswitch1"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Include Pages</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch2" checked>
                                                        <label class="form-check-label" for="searchswitch2"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Internet resource</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch3" checked>
                                                        <label class="form-check-label" for="searchswitch3"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">News and Blogs</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch4">
                                                        <label class="form-check-label" for="searchswitch4"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="searchorders" role="tabpanel"
                                     aria-labelledby="searchorders-tab">
                                    <div class="dropdown-item mb-3">
                                        <div class="input-group input-group-md border rounded">
                                            <span class="input-group-text text-theme"><i class="bi bi-box"></i></span>
                                            <select class="form-control" id="searchfilterlist" multiple>
                                                <option value="San Francisco">San Francisco</option>
                                                <option value="New York">New York</option>
                                                <option value="Seattle" selected>Seattle</option>
                                                <option value="Los Angeles">Los Angeles</option>
                                                <option value="Chicago">Chicago</option>
                                                <option value="India">India</option>
                                                <option value="Sydney" selected>Sydney</option>
                                                <option value="London">London</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Los Angeles">Los Angeles</option>
                                                <option value="Chicago">Chicago</option>
                                                <option value="India">India</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback">You have already selected maximum option
                                            allowed.(This is Configurable)
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Show order ID</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch5">
                                                        <label class="form-check-label" for="searchswitch5"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">International Order</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch6" checked>
                                                        <label class="form-check-label" for="searchswitch6"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Taxable Product</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch7" checked>
                                                        <label class="form-check-label" for="searchswitch7"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Published Product</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch8">
                                                        <label class="form-check-label" for="searchswitch8"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="searchcontacts" role="tabpanel"
                                     aria-labelledby="searchcontacts-tab">
                                    <div class="dropdown-item mb-3">
                                        <div class="input-group input-group-md border rounded">
                                            <span class="input-group-text text-theme"><i
                                                    class="bi bi-person-lines-fill"></i></span>
                                            <input class="form-control" type="search" placeholder="Contact Include">
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush bg-none">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Have email ID</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch9">
                                                        <label class="form-check-label" for="searchswitch9"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Have phone number</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch10" checked>
                                                        <label class="form-check-label" for="searchswitch10"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Photo available</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch11" checked>
                                                        <label class="form-check-label" for="searchswitch11"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Referral</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="searchswitch12">
                                                        <label class="form-check-label" for="searchswitch12"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="dropdown-item">
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-outline-secondary border">Reset</button>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-theme">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="input-group-text d-flex d-xl-none" id="searchclose"><i
                            class="bi bi-x-lg vm"></i></span>
                </div>
                <div class="dropdown-menu dropdown-dontclose mt-2 mw-600 w-auto" id="searchresultglobal">
                    <ul class="nav nav-WinDOORS" id="searchtab1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="searchall1-tab" data-bs-toggle="tab"
                                    data-bs-target="#searchall1" type="button" role="tab" aria-controls="searchall1"
                                    aria-selected="true">All <span
                                    class="badge rounded-pill bg-success ml-2 vm">12</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="searchorders1-tab" data-bs-toggle="tab"
                                    data-bs-target="#searchorders1" type="button" role="tab"
                                    aria-controls="searchorders1" aria-selected="false">Orders <span
                                    class="badge rounded-pill bg-primary ml-2 vm">8</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="searchcontacts1-tab" data-bs-toggle="tab"
                                    data-bs-target="#searchcontacts1" type="button" role="tab"
                                    aria-controls="searchcontacts1" aria-selected="false">Contacts <span
                                    class="badge rounded-pill bg-warning ml-2 vm">4</span></button>
                        </li>
                    </ul>
                    <div class="tab-content py-3" id="searchtabContent1">
                        <div class="tab-pane fade show active mh-500 overflow-y-auto" id="searchall1" role="tabpanel"
                             aria-labelledby="searchall1-tab">
                            <div class="container-fluid">
                                <div class="row mb-2">
                                    <div class="col align-self-center">
                                        <h6>Application</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-outline-secondary border">View all</a>
                                    </div>
                                </div>
                                <div class="row g-0 text-center mb-3">
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-finance.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-bank fs-4"></i>
                                            </div>
                                            <p class="mb-0">Finance</p>
                                            <p class="fs-12 text-muted">Accounting</p>
                                        </a>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-network.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-globe fs-4"></i>
                                            </div>
                                            <p class="mb-0">Network</p>
                                            <p class="fs-12 text-muted">Stabilize</p>
                                        </a>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-ecommerce.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-box fs-4"></i>
                                            </div>
                                            <p class="mb-0">Inventory</p>
                                            <p class="fs-12 text-muted">Assuring</p>
                                        </a>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-project.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-folder fs-4"></i>
                                            </div>
                                            <p class="mb-0">Project</p>
                                            <p class="fs-12 text-muted">Management</p>
                                        </a>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-social.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-people fs-4"></i>
                                            </div>
                                            <p class="mb-0">Social</p>
                                            <p class="fs-12 text-muted">Tracking</p>
                                        </a>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2">
                                        <a class="dropdown-item square-item" href="app-learning.html">
                                            <div class="avatar avatar-40 rounded mb-2">
                                                <i class="bi bi-journal-bookmark fs-4"></i>
                                            </div>
                                            <p class="mb-0">Learning</p>
                                            <p class="fs-12 text-muted">Make-easy</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col align-self-center">
                                        <h6>Orders Placed</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-outline-secondary border">View all</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-bag fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0021 by John Merchant</a>
                                                <p class="text-truncate text-secondary small">2 items, $250.00, 09
                                                    December 2021</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-basket fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0026 by Will Smith</a>
                                                <p class="text-truncate text-secondary small">4 items, $530.00, 18
                                                    December 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-cart fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0030 by Switty David</a>
                                                <p class="text-truncate text-secondary small">1 items, $50.00, 20
                                                    December 2021</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-cart4 fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0041 by Mr.Walk Wolf</a>
                                                <p class="text-truncate text-secondary small">3 items, $130.00, 16
                                                    December 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col align-self-center">
                                        <h6>Contacts</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-outline-secondary border">View all</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-2.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Ms. Switty David</a>
                                                <p class="text-truncate text-secondary small">US, UK Recruiter</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-3.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Dyna Roosevelt</a>
                                                <p class="text-truncate text-secondary small">Marketing Head at
                                                    Linmongas</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-4.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Mr. Freddy Johnson</a>
                                                <p class="text-truncate text-secondary small">Project Manager</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-1.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">The Maxartkiller</a>
                                                <p class="text-truncate text-secondary small">CEO Maxartkiller</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="searchorders1" role="tabpanel"
                             aria-labelledby="searchorders1-tab">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-bag fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0021 by John Merchant</a>
                                                <p class="text-truncate text-secondary small">2 items, $250.00, 09
                                                    December 2021</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-basket fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0026 by Will Smith</a>
                                                <p class="text-truncate text-secondary small">4 items, $530.00, 18
                                                    December 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-cart fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0030 by Switty David</a>
                                                <p class="text-truncate text-secondary small">1 items, $50.00, 20
                                                    December 2021</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme">
                                                    <i class="bi bi-cart4 fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">#EDR0041 by Mr.Walk Wolf</a>
                                                <p class="text-truncate text-secondary small">3 items, $130.00, 16
                                                    December 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="searchcontacts1" role="tabpanel"
                             aria-labelledby="searchcontacts1-tab">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-2.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Ms. Switty David</a>
                                                <p class="text-truncate text-secondary small">US, UK Recruiter</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-3.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Dyna Roosevelt</a>
                                                <p class="text-truncate text-secondary small">Marketing Head at
                                                    Linmongas</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-4.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">Mr. Freddy Johnson</a>
                                                <p class="text-truncate text-secondary small">Project Manager</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="avatar avatar-50 rounded bg-light-theme coverimg">
                                                    <img src="img/user-1.jpg" alt=""/>
                                                </div>
                                            </div>
                                            <div class="col-8 ps-0 align-self-center">
                                                <a href="#" class="text-truncate">The Maxartkiller</a>
                                                <p class="text-truncate text-secondary small">CEO Maxartkiller</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-expand-xl d-none d-xxxl-block ms-3">
                <div class="collapse navbar-collapse" id="mainheaderNavbar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link btn btn-theme" href="#" role="button"><b>Proje Oluştur</b> <i class="bi bi-plus"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="ms-auto">
                <div class="row">
                    <div class="col-auto">
                        <button class="btn btn-square btn-link search-btn d-inline-block d-xl-none " id="searchtoggle">
                            <i class="bi bi-search"></i>
                        </button>
                        <button type="button" class="btn btn-square btn-link text-center d-none d-lg-inline-block"
                                id="gofullscreen" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="Fullscreen"><i class="bi bi-fullscreen"></i></button>
                    </div>
                    <div class="col-auto align-self-center px-0  d-none d-xxxl-block">
                        <i class="bi bi-three-dots-vertical opacity-3 text-secondary"></i>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown">
                            <a class="dd-arrow-none dropdown-toggle tempdata" id="userprofiledd"
                               data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                <div class="row">
                                    <div class="col-auto align-self-center">
                                        <figure class="avatar avatar-40 rounded-circle coverimg vm">
                                            <img src="img/user-1.jpg" alt="" id="userphotoonboarding2"/>
                                        </figure>
                                    </div>
                                    <div class="col ps-0 align-self-center d-none d-lg-block">
                                        <p class="mb-0">
                                            <span class="text-dark username">Samet Okumuş</span>
                                        </p>
                                    </div>
                                    <div class="col ps-0 align-self-center d-none d-lg-block">
                                        <i class="bi bi-chevron-down small vm"></i>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end w-300" aria-labelledby="userprofiledd">
                                <div class="dropdown-info bg-theme">
                                    <div class="row">
                                        <div class="col-auto">
                                            <figure class="avatar avatar-50 rounded-circle coverimg vm">
                                                <img src="img/user-1.jpg" alt="" id="userphotoonboarding3"/>
                                            </figure>
                                        </div>
                                        <div class="col align-self-center ps-0">
                                            <h6 class="mb-0"><span class="username">Samet Okumuş</span></h6>
                                            <p class="text-muted small">Süper Admin</p>
                                        </div>
                                    </div>
                                </div>
                                <div><a class="dropdown-item" href="my-dashboard">Hesabım</a></div>
                                <div><a class="dropdown-item" href="dashboard">Dashboard</a></div>
                                <div><a class="dropdown-item navbar-logout" href="#">Logout</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- Header ends -->

<!-- Sidebar -->
<div class="sidebar-wrap">
    <div class="sidebar">
        <div class="container">
            <div class="row mb-2">
                <div class="col align-self-center">
                    <h6>Yönetim</h6>
                </div>
            </div>

            <!-- user menu navigation -->
            <div class="row mb-4">
                <div class="col-12 px-0">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="my-dashboard">
                                <div class="avatar avatar-40 icon"><i class="bi bi-house-door"></i></div>
                                <div class="col">Bilgilerim</div>
                                <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="dashboard">
                                <div class="avatar avatar-40 icon"><i class="bi bi-bar-chart-steps"></i></div>
                                <div class="col">Dashboard</div>
                                <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- applications -->
            <div class="row mb-2">
                <div class="col align-self-center">
                    <h6>Projeler</h6>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 px-0">
                    <ul class="nav nav-pills nav-iconic">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="projects">
                                <div class="avatar avatar-40 icon"><i class="bi bi-house-door"></i></div>
                                <div class="col">Devam Eden</div>
                                <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="past-projects">
                                <div class="avatar avatar-40 icon"><i class="bi bi-house-door"></i></div>
                                <div class="col">Geçmiş</div>
                                <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- user secondary menu navigation -->
            <div class="row mb-2">
                <div class="col align-self-center">
                    <h6>Diğer İşlemler</h6>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 px-0">
                    <ul class="nav nav-pills">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                               data-bs-display="static" href="#" role="button" aria-expanded="false">
                                <div class="avatar avatar-40 icon"><i class="bi bi-person-circle"></i></div>
                                <div class="col">Muhasebe</div>
                                <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i
                                        class="bi bi-chevron-up minus"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-briefcase"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 1</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-people"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 2</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                               data-bs-display="static" href="#" role="button" aria-expanded="false">
                                <div class="avatar avatar-40 icon"><i class="bi bi-card-checklist"></i></div>
                                <div class="col">Test Yönetimi</div>
                                <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i
                                        class="bi bi-chevron-up minus"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item nav-link" href="add-test">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-clipboard-plus"></i>
                                        </div>
                                        <div class="col align-self-center">Test Ekle</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="tests">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-list-check"></i>
                                        </div>
                                        <div class="col align-self-center">Tüm Testler</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="add-category">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-tag"></i>
                                        </div>
                                        <div class="col align-self-center">Kategori Ekle</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="categories">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-tags"></i>
                                        </div>
                                        <div class="col align-self-center">Tüm Kategoriler</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                               data-bs-display="static" href="#" role="button" aria-expanded="false">
                                <div class="avatar avatar-40 icon"><i class="bi bi-buildings-fill"></i></div>
                                <div class="col">Müşteriler</div>
                                <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i
                                        class="bi bi-chevron-up minus"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item nav-link" href="add-customer">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-building-add"></i>
                                        </div>
                                        <div class="col align-self-center">Müşteri Ekle</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="customers">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-buildings-fill"></i>
                                        </div>
                                        <div class="col align-self-center">Tüm Müşteriler</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                               data-bs-display="static" href="#" role="button" aria-expanded="false">
                                <div class="avatar avatar-40 icon"><i class="bi bi-window-stack"></i></div>
                                <div class="col">Ayarlar</div>
                                <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i
                                        class="bi bi-chevron-up minus"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-briefcase"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 1</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-people"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 2</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                               data-bs-display="static" href="#" role="button" aria-expanded="false">
                                <div class="avatar avatar-40 icon"><i class="bi bi-layers"></i></div>
                                <div class="col">Kullanıcı Yönetimi</div>
                                <div class="arrow"><i class="bi bi-chevron-down plus"></i> <i
                                        class="bi bi-chevron-up minus"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-briefcase"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 1</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link" href="#">
                                        <div class="avatar avatar-40 icon"><i class="bi bi-people"></i>
                                        </div>
                                        <div class="col align-self-center">Sub 2</div>
                                        <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>


        </div>
    </div>
</div>
<!-- Sidebar ends -->
