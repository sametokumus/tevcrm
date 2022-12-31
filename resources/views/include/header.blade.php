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

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>HUD | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>

    <base href="http://127.0.0.1:8000/">
    <link href="css/vendor.min.css" rel="stylesheet"/>
    <link href="css/app.min.css" rel="stylesheet"/>
    <!-- required css -->
    <link href="plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link href="plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />

    <link href="css/customize.css" rel="stylesheet"/>


    <link href="plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet"/>

</head>
<body>

<div id="app" class="app">

    <div id="header" class="app-header">

        <div class="desktop-toggler">
            <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed"
                    data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>


        <div class="mobile-toggler">
            <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled"
                    data-toggle-target=".app">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>


        <div class="brand">
            <a href="#" class="brand-logo">
<span class="brand-img">
<span class="brand-img-text text-theme">S</span>
</span>
                <span class="brand-text">CRM DASH</span>
            </a>
        </div>


        <div class="menu">
            <div class="menu-item dropdown">
                <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"
                   class="menu-link">
                    <div class="menu-icon"><i class="bi bi-search nav-icon"></i></div>
                </a>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-icon"><i class="bi bi-grid-3x3-gap nav-icon"></i></div>
                </a>
                <div class="dropdown-menu fade dropdown-menu-end w-300px text-center p-0 mt-1">
                    <div class="row row-grid gx-0">
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div class="position-relative">
                                    <i class="bi bi-circle-fill position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i>
                                    <i class="bi bi-envelope h2 opacity-5 d-block my-1"></i>
                                </div>
                                <div class="fw-500 fs-10px text-white">INBOX</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="bi bi-hdd-network h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-white">DISK DRIVE</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="bi bi-calendar4 h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-white">CALENDAR</div>
                            </a>
                        </div>
                    </div>
                    <div class="row row-grid gx-0">
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="bi bi-terminal h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-white">TERMINAL</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div class="position-relative">
                                    <i class="bi bi-circle-fill position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i>
                                    <i class="bi bi-sliders h2 opacity-5 d-block my-1"></i>
                                </div>
                                <div class="fw-500 fs-10px text-white">SETTINGS</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="dropdown-item text-decoration-none p-3 bg-none">
                                <div><i class="bi bi-collection-play h2 opacity-5 d-block my-1"></i></div>
                                <div class="fw-500 fs-10px text-white">LIBRARY</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-icon"><i class="bi bi-bell nav-icon"></i></div>
                    <div class="menu-badge bg-theme"></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end mt-1 w-300px fs-11px pt-1">
                    <h6 class="dropdown-header fs-10px mb-1">NOTIFICATIONS</h6>
                    <div class="dropdown-divider mt-1"></div>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px">
                            <i class="bi bi-bag text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-white">NEW ORDER RECEIVED ($1,299)</div>
                            <div class="small">JUST NOW</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-person-circle text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-white">3 NEW ACCOUNT CREATED</div>
                            <div class="small">2 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-gear text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-white">SETUP COMPLETED</div>
                            <div class="small">3 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-grid text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-white">WIDGET INSTALLATION DONE</div>
                            <div class="small">5 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap">
                        <div class="fs-20px w-20px">
                            <i class="bi bi-credit-card text-theme"></i>
                        </div>
                        <div class="flex-1 flex-wrap ps-3">
                            <div class="mb-1 text-white">PAYMENT METHOD ENABLED</div>
                            <div class="small">10 MINUTES AGO</div>
                        </div>
                        <div class="ps-2 fs-16px">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                    <hr class="bg-white-transparent-5 mb-0 mt-2"/>
                    <div class="py-10px mb-n2 text-center">
                        <a href="#" class="text-decoration-none fw-bold">SEE ALL</a>
                    </div>
                </div>
            </div>
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-img online">
                        <img src="img/user/profile.jpg" height="60px"/>
                    </div>
                    <div class="menu-text d-sm-block d-none"><span class="__cf_email__"
                                                                   data-cfemail="05707660776b646860456466666a706b712b666a68">[email&#160;protected]</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-lg-3 fs-11px mt-1">
                    <a class="dropdown-item d-flex align-items-center" href="#">PROFILE <i
                            class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="#">INBOX <i
                            class="bi bi-envelope ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="#">CALENDAR <i
                            class="bi bi-calendar ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="#">SETTINGS <i
                            class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center" href="#">LOGOUT <i
                            class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
                </div>
            </div>
        </div>


        <form class="menu-search" method="POST" name="header_search_form">
            <div class="menu-search-container">
                <div class="menu-search-icon"><i class="bi bi-search"></i></div>
                <div class="menu-search-input">
                    <input type="text" class="form-control form-control-lg" placeholder="Search menu..."/>
                </div>
                <div class="menu-search-icon">
                    <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i
                            class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>

    </div>


    <div id="sidebar" class="app-sidebar">

        <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">

            <div class="menu">
                <div class="menu-header">Haber Kaynağı</div>
                <div class="menu-item active">
                    <a href="index-2.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-cpu"></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="/customers" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-columns-gap"></i></span>
                        <span class="menu-text">Haber Kaynağı</span>
                    </a>
                </div>
                <div class="menu-header">Navigation</div>
                <div class="menu-item">
                    <a href="/customers" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gem"></i></span>
                        <span class="menu-text">Müşteriler</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="/suppliers" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gem"></i></span>
                        <span class="menu-text">Tedarikçiler</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="/potential-customers" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gem"></i></span>
                        <span class="menu-text">Potansiyel Müşteriler</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="analytics.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-bar-chart"></i></span>
                        <span class="menu-text">Analytics</span>
                    </a>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
<span class="menu-icon">
<i class="bi bi-envelope"></i>
</span>
                        <span class="menu-text">Email</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="email_inbox.html" class="menu-link">
                                <span class="menu-text">Inbox</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="email_compose.html" class="menu-link">
                                <span class="menu-text">Compose</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="email_detail.html" class="menu-link">
                                <span class="menu-text">Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-header">Components</div>
                <div class="menu-item">
                    <a href="widgets.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-columns-gap"></i></span>
                        <span class="menu-text">Widgets</span>
                    </a>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-controller"></i></span>
                        <span class="menu-text">UI Kits</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="ui_bootstrap.html" class="menu-link">
                                <span class="menu-text">Bootstrap</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_buttons.html" class="menu-link">
                                <span class="menu-text">Buttons</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_card.html" class="menu-link">
                                <span class="menu-text">Card</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_icons.html" class="menu-link">
                                <span class="menu-text">Icons</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_modal_notification.html" class="menu-link">
                                <span class="menu-text">Modal & Notification</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_typography.html" class="menu-link">
                                <span class="menu-text">Typography</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_tabs_accordions.html" class="menu-link">
                                <span class="menu-text">Tabs & Accordions</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-pen"></i></span>
                        <span class="menu-text">Forms</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="form_elements.html" class="menu-link">
                                <span class="menu-text">Form Elements</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_plugins.html" class="menu-link">
                                <span class="menu-text">Form Plugins</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_wizards.html" class="menu-link">
                                <span class="menu-text">Wizards</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-grid-3x3"></i></span>
                        <span class="menu-text">Tables</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="table_elements.html" class="menu-link">
                                <span class="menu-text">Table Elements</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="table_plugins.html" class="menu-link">
                                <span class="menu-text">Table Plugins</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-pie-chart"></i></span>
                        <span class="menu-text">Charts</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="chart_js.html" class="menu-link">
                                <span class="menu-text">Chart.js</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="chart_apex.html" class="menu-link">
                                <span class="menu-text">Apexcharts.js</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <a href="map.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-compass"></i></span>
                        <span class="menu-text">Map</span>
                    </a>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-layout-sidebar"></i></span>
                        <span class="menu-text">Layout</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="layout_starter.html" class="menu-link">
                                <span class="menu-text">Starter Page</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="layout_fixed_footer.html" class="menu-link">
                                <span class="menu-text">Fixed Footer</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="layout_full_height.html" class="menu-link">
                                <span class="menu-text">Full Height</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="layout_full_width.html" class="menu-link">
                                <span class="menu-text">Full Width</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="layout_boxed_layout.html" class="menu-link">
                                <span class="menu-text">Boxed Layout</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="layout_collapsed_sidebar.html" class="menu-link">
                                <span class="menu-text">Collapsed Sidebar</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-collection"></i></span>
                        <span class="menu-text">Pages</span>
                        <span class="menu-caret"><b class="caret"></b></span>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="page_scrum_board.html" class="menu-link">
                                <span class="menu-text">Scrum Board</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_products.html" class="menu-link">
                                <span class="menu-text">Products</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_orders.html" class="menu-link">
                                <span class="menu-text">Orders</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_gallery.html" class="menu-link">
                                <span class="menu-text">Gallery</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_search_results.html" class="menu-link">
                                <span class="menu-text">Search Results</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_coming_soon.html" class="menu-link">
                                <span class="menu-text">Coming Soon Page</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_404_error.html" class="menu-link">
                                <span class="menu-text">404 Error Page</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_login.html" class="menu-link">
                                <span class="menu-text">Login</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_register.html" class="menu-link">
                                <span class="menu-text">Register</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-divider"></div>
                <div class="menu-header">Users</div>
                <div class="menu-item">
                    <a href="profile.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-people"></i></span>
                        <span class="menu-text">Profile</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="calendar.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-calendar4"></i></span>
                        <span class="menu-text">Calendar</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="settings.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gear"></i></span>
                        <span class="menu-text">Settings</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="helper.html" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gem"></i></span>
                        <span class="menu-text">Helper</span>
                    </a>
                </div>


                <div class="menu-header">S-CRM</div>
                <div class="menu-item">
                    <a href="/settings" class="menu-link">
                        <span class="menu-icon"><i class="bi bi-gear"></i></span>
                        <span class="menu-text">Ayarlar</span>
                    </a>
                </div>
            </div>

            <div class="p-3 px-4 mt-auto">
                <a href="documentation/index.html" target="_blank" class="btn d-block btn-outline-theme">
                    <i class="fa fa-code-branch me-2 ms-n2 opacity-5"></i> Documentation
                </a>
            </div>
        </div>

    </div>


    <button class="app-sidebar-mobile-backdrop" data-toggle-target=".app"
            data-toggle-class="app-sidebar-mobile-toggled"></button>
