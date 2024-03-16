@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/dashboard.js"></script>
';
?>

<main class="main mainheight px-4">
    <!-- title bar -->
    <div class="container-fluid">
        <div class="row align-items-center page-title bg-light-blue">
            <div class="col-12 col-md mb-2 mb-sm-0">
                <h5 class="mb-0">Dashboard</h5>
                <p class="text-secondary">Potansiyel Gelir - Gider Takibi</p>
            </div>
        </div>
    </div>

    {{--    <!-- content -->--}}
    {{--    <div class="container mt-4">--}}
    {{--        <div class="row">--}}
    {{--            <!-- Balance card -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-3 mb-4 column-set">--}}
    {{--                <div class="card border-0 bg-theme h-100">--}}
    {{--                    <div class="card-header bg-none">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium">--}}
    {{--                                    <i class="bi bi-wallet2 h5 me-1 avatar avatar-30 rounded"></i>--}}
    {{--                                    Wallet--}}
    {{--                                </h6>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-white dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body bg-none text-white">--}}
    {{--                        <div class="text-center mb-3">--}}
    {{--                            <figure class="avatar avatar-100 coverimg rounded-circle mb-3 mx-auto">--}}
    {{--                                <img src="img/user-1.jpg" class="ususerphotoonboarding" alt="">--}}
    {{--                            </figure>--}}
    {{--                            <h5 class="fw-normal mb-0 username">Maxartkiller</h5>--}}
    {{--                            <h3 class="fw-medium">$28,000.00</h3>--}}
    {{--                        </div>--}}

    {{--                        <div class="row">--}}
    {{--                            <div class="col-6">--}}
    {{--                                <div class="row align-items-center">--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <div class="avatar avatar-40 rounded-circle bg-green">--}}
    {{--                                            <i class="bi bi-arrow-down-left"></i>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col ps-0">--}}
    {{--                                        <p class="small text-muted mb-1">Income</p>--}}
    {{--                                        <p class="">1525 <small>k</small></p>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-6 text-end border-left-dashed">--}}
    {{--                                <div class="row align-items-center">--}}
    {{--                                    <div class="col pe-0">--}}
    {{--                                        <p class="small text-muted mb-1">Expense</p>--}}
    {{--                                        <p class="">1321 <small>k</small></p>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <div class="avatar avatar-40 rounded-circle bg-red">--}}
    {{--                                            <i class="bi bi-arrow-up-right"></i>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!--charts category summary-->--}}
    {{--            <div class="col-12 col-md-6 col-lg-3 col-xxl-2">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressblue1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Education</p>--}}
    {{--                                <p>1075 <small>USD</small></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="smallchart65 mb-2">--}}
    {{--                            <canvas id="areachartblue1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressgreen1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Food</p>--}}
    {{--                                <p>1542 <small>USD</small></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="smallchart65 mb-2">--}}
    {{--                            <canvas id="areachartgreen1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-12 col-md-6 col-lg-3 col-xxl-2">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressyellow1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Shipment</p>--}}
    {{--                                <p>821 <small>USD</small></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="smallchart65 mb-2">--}}
    {{--                            <canvas id="areachartyellow1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressred1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Grocery</p>--}}
    {{--                                <p>2530 <small>USD</small></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="smallchart65 mb-2">--}}
    {{--                            <canvas id="areachartred1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-2 mb-4 ">--}}
    {{--                <div class="card border-0 h-100">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressblue2"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Sent Money</p>--}}
    {{--                                <p>1024 <small>USD</small></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="smallchart90 mb-3">--}}
    {{--                            <canvas id="barchart"></canvas>--}}
    {{--                        </div>--}}

    {{--                        <ul class="list-group list-group-flush bg-none">--}}
    {{--                            <li class="list-group-item">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-auto align-self-center">--}}
    {{--                                        <figure class="avatar avatar-40 rounded-circle coverimg vm">--}}
    {{--                                            <img src="img/user-4.jpg" alt="">--}}
    {{--                                        </figure>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col px-0 align-self-center">--}}
    {{--                                        <p class="mb-0">David Warner</p>--}}
    {{--                                        <p class="small text-secondary">$ 410.00</p>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto ps-0 align-self-center">--}}
    {{--                                        <i class="bi bi-chevron-right text-muted small vm"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </li>--}}
    {{--                            <li class="list-group-item">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-auto align-self-center">--}}
    {{--                                        <figure class="avatar avatar-40 rounded-circle coverimg vm">--}}
    {{--                                            <img src="img/user-3.jpg" alt="">--}}
    {{--                                        </figure>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col px-0 align-self-center">--}}
    {{--                                        <p class="mb-0">Shelvey Doe</p>--}}
    {{--                                        <p class="small text-secondary">$ 180.00</p>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto ps-0 align-self-center">--}}
    {{--                                        <i class="bi bi-chevron-right text-muted small vm"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </li>--}}
    {{--                        </ul>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- Credit cards carousel card -->--}}
    {{--            <!-- add column-set class to parent when customize column width dropdown added -->--}}
    {{--            <div class="col-12 col-md-12 col-lg-6 col-xxl-3 mb-4 column-set">--}}
    {{--                <!-- finance card -->--}}
    {{--                <div class="card border-0 h-100">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="bi bi-cash h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col ps-0">--}}
    {{--                                <h6 class="fw-medium mb-0">My Cards</h6>--}}
    {{--                                <p class="small text-secondary">Recently Used</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body px-0">--}}
    {{--                        <div class="swiper-container creditcards">--}}
    {{--                            <div class="swiper-wrapper">--}}
    {{--                                <div class="swiper-slide">--}}
    {{--                                    <div class="card border-0 mb-3 theme-blue">--}}
    {{--                                        <div class="card-body">--}}
    {{--                                            <div class="row align-items-center mb-4">--}}
    {{--                                                <div class="col-auto align-self-center">--}}
    {{--                                                    <img src="img/visa.png" alt="">--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end">--}}
    {{--                                                    <p class="size-12">--}}
    {{--                                                        <span class="text-muted small">City Bank</span><br>--}}
    {{--                                                        <span class="">Credit Card</span>--}}
    {{--                                                    </p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <p class="fw-medium h6 mb-4">--}}
    {{--                                                000 0000 0001 546598--}}
    {{--                                            </p>--}}
    {{--                                            <div class="row">--}}
    {{--                                                <div class="col-auto size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Expiry</p>--}}
    {{--                                                    <p>09/023</p>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Card Holder</p>--}}
    {{--                                                    <p>Maxartkiller</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="row amount-data">--}}
    {{--                                        <div class="col">--}}
    {{--                                            <p class="text-secondary small mb-1">Expense</p>--}}
    {{--                                            <p>1500.00 <small class="text-success">18.0% <i class="bi bi-arrow-up"></i></small>--}}
    {{--                                            </p>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="col-auto text-end">--}}
    {{--                                            <p class="text-secondary small mb-1">Limit Remain</p>--}}
    {{--                                            <p>13500.00</p>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="swiper-slide">--}}
    {{--                                    <div class="card border-0 theme-green mb-3">--}}
    {{--                                        <div class="card-body">--}}
    {{--                                            <div class="row align-items-center mb-4">--}}
    {{--                                                <div class="col-auto align-self-center">--}}
    {{--                                                    <img src="img/visa.png" alt="">--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end">--}}
    {{--                                                    <p class="size-12">--}}
    {{--                                                        <span class="text-muted small">City Bank</span><br>--}}
    {{--                                                        <span class="">Credit Card</span>--}}
    {{--                                                    </p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <p class="fw-medium h6 mb-4">--}}
    {{--                                                000 0000 0001 546598--}}
    {{--                                            </p>--}}
    {{--                                            <div class="row">--}}
    {{--                                                <div class="col-auto size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Expiry</p>--}}
    {{--                                                    <p>09/023</p>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Card Holder</p>--}}
    {{--                                                    <p>Maxartkiller</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="row amount-data">--}}
    {{--                                        <div class="col">--}}
    {{--                                            <p class="text-secondary small mb-1">Expense</p>--}}
    {{--                                            <p>3650.00 <small class="text-danger">11.0% <i class="bi bi-arrow-down"></i></small>--}}
    {{--                                            </p>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="col-auto text-end">--}}
    {{--                                            <p class="text-secondary small mb-1">Limit Remain</p>--}}
    {{--                                            <p>35500.00</p>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="swiper-slide">--}}
    {{--                                    <div class="card border-0 theme-purple mb-3">--}}
    {{--                                        <div class="card-body">--}}
    {{--                                            <div class="row align-items-center mb-4">--}}
    {{--                                                <div class="col-auto align-self-center">--}}
    {{--                                                    <img src="img/visa.png" alt="">--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end">--}}
    {{--                                                    <p class="size-12">--}}
    {{--                                                        <span class="text-muted small">City Bank</span><br>--}}
    {{--                                                        <span class="">Credit Card</span>--}}
    {{--                                                    </p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <p class="fw-medium h6 mb-4">--}}
    {{--                                                000 0000 0001 546598--}}
    {{--                                            </p>--}}
    {{--                                            <div class="row">--}}
    {{--                                                <div class="col-auto size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Expiry</p>--}}
    {{--                                                    <p>09/023</p>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col text-end size-12">--}}
    {{--                                                    <p class="mb-0 text-muted small">Card Holder</p>--}}
    {{--                                                    <p>Maxartkiller</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="row amount-data">--}}
    {{--                                        <div class="col">--}}
    {{--                                            <p class="text-secondary small mb-1">Expense</p>--}}
    {{--                                            <p>1500.00 <small class="text-success">18.0 <i--}}
    {{--                                                        class="bi bi-arrow-up"></i></small></p>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="col-auto text-end">--}}
    {{--                                            <p class="text-secondary small mb-1">Limit Remain</p>--}}
    {{--                                            <p>13500.00</p>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row">--}}
    {{--            <!-- summary blocks -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-3">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressblue"></div>--}}
    {{--                                    <div class="avatar h5 bg-light-blue rounded-circle">--}}
    {{--                                        <i class="bi bi-receipt"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Total Bills</p>--}}
    {{--                                <h5 class="fw-medium">25486.00 <small>USD</small></h5>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"--}}
    {{--                                       aria-expanded="false" data-bs-display="static" role="button">--}}
    {{--                                        <i class="bi bi-three-dots-vertical"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <ul class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>--}}
    {{--                                        <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>--}}
    {{--                                        <li><a class="dropdown-item text-danger" href="javascript:void(0)">Delete</a>--}}
    {{--                                        </li>--}}
    {{--                                    </ul>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-3">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressyellow"></div>--}}
    {{--                                    <div class="avatar h5 bg-light-yellow text-yellow rounded-circle">--}}
    {{--                                        <i class="bi bi-credit-card"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Paid Bills</p>--}}
    {{--                                <h5 class="fw-medium">12550<small>USD</small></h5>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"--}}
    {{--                                       aria-expanded="false" data-bs-display="static" role="button">--}}
    {{--                                        <i class="bi bi-three-dots-vertical"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <ul class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>--}}
    {{--                                        <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>--}}
    {{--                                        <li><a class="dropdown-item text-danger" href="javascript:void(0)">Delete</a>--}}
    {{--                                        </li>--}}
    {{--                                    </ul>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-3">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogressredsum"></div>--}}
    {{--                                    <div class="avatar h5 bg-light-red text-red rounded-circle">--}}
    {{--                                        <i class="bi bi-receipt-cutoff"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-secondary small mb-1">Unpaid Bills</p>--}}
    {{--                                <h5 class="fw-medium">1525<small>k</small></h5>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto avatar-group">--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Just Demo">--}}
    {{--                                    <img src="img/company2.png" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Purpose">--}}
    {{--                                    <img src="img/company3.jpg" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="to look real">--}}
    {{--                                    <img src="img/company4.jpg" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-3">--}}
    {{--                <div class="card bg-theme border-0 mb-4">--}}
    {{--                    <div class="card-body bg-none">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="circle-small">--}}
    {{--                                    <div id="circleprogresswhite"></div>--}}
    {{--                                    <div class="avatar h5 bg-light-white text-white rounded-circle">--}}
    {{--                                        <i class="bi bi-envelope-check"></i>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <p class="text-muted small mb-1">Sent Bills</p>--}}
    {{--                                <h5 class="fw-medium">1545 <small>k USD</small></h5>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto ps-0 avatar-group">--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Shelvey Doe">--}}
    {{--                                    <img src="img/user-2.jpg" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Maria Smith">--}}
    {{--                                    <img src="img/user-3.jpg" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                                <figure class="avatar avatar-40 rounded-circle coverimg overlay-ms-15"--}}
    {{--                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Steve Branden">--}}
    {{--                                    <img src="img/user-4.jpg" alt=""/>--}}
    {{--                                </figure>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row">--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <!-- targets and progress -->--}}
    {{--                <div class="card border-0 position-relative mb-4">--}}
    {{--                    <div class="coverimg position-absolute end-0 top-0 h-100 w-30pct rounded">--}}
    {{--                        <img src="img/business-4.jpg" alt=""/>--}}
    {{--                    </div>--}}
    {{--                    <div class="row">--}}
    {{--                        <div class="col-9">--}}
    {{--                            <div class="card border-0 bg-white shadow-none">--}}
    {{--                                <div class="card-body">--}}
    {{--                                    <div class="row">--}}
    {{--                                        <div class="col-auto">--}}
    {{--                                            <div class="rounded bg-theme text-white p-3">--}}
    {{--                                                <i class="bi bi-building mb-1"></i>--}}
    {{--                                                <p class="text-muted small mb-1">--}}
    {{--                                                    Annual<br/>Income--}}
    {{--                                                </p>--}}
    {{--                                                <p>$2542</p>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="col ps-0 align-self-center">--}}
    {{--                                            <p class="text-secondary small mb-0">United States</p>--}}
    {{--                                            <p>New York</p>--}}
    {{--                                            <div class="mt-4">--}}
    {{--                                                <div class="progress h-5 mb-1 bg-light-theme">--}}
    {{--                                                    <div class="progress-bar bg-theme" role="progressbar"--}}
    {{--                                                         style="width: 45%" aria-valuenow="45" aria-valuemin="0"--}}
    {{--                                                         aria-valuemax="100"></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <p class="small text-secondary">Targeted <span--}}
    {{--                                                    class="float-end">$ 11600</span></p>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-3 position-relative">--}}
    {{--                            <div class="dropdown d-inline-block position-absolute end-0 top-0 m-2">--}}
    {{--                                <a class="btn btn-link bg-theme-light btn-square dd-arrow-none dropdown-toggle"--}}
    {{--                                   role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                   aria-expanded="false">--}}
    {{--                                    <i class="bi bi-columns"></i>--}}
    {{--                                </a>--}}
    {{--                                <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                    <div class="dropdown-item text-center">--}}
    {{--                                        <div class="row gx-3 mb-3">--}}
    {{--                                            <div class="col-6">--}}
    {{--                                                <div class="row select-column-size gx-1">--}}
    {{--                                                    <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                    <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-6">--}}
    {{--                                                <div class="row select-column-size gx-1">--}}
    {{--                                                    <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                    <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="row gx-3">--}}
    {{--                                            <div class="col-6">--}}
    {{--                                                <div class="row select-column-size gx-1">--}}
    {{--                                                    <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-6">--}}
    {{--                                                <div class="row select-column-size gx-1">--}}
    {{--                                                    <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card border-0 theme-green">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="rounded bg-theme text-white p-3">--}}
    {{--                                    <i class="bi bi-bank mb-1"></i>--}}
    {{--                                    <p class="text-muted small mb-1">--}}
    {{--                                        Assets<br/>Income--}}
    {{--                                    </p>--}}
    {{--                                    <p>$2542</p>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col ps-0 align-self-center">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col">--}}
    {{--                                        <p class="text-secondary small mb-0">United States</p>--}}
    {{--                                        <p>New York</p>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto text-end">--}}
    {{--                                        <p class="text-secondary small mb-0">New Sales</p>--}}
    {{--                                        <p>120 orders</p>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="mt-4">--}}
    {{--                                    <div class="progress h-5 mb-1 bg-light-theme">--}}
    {{--                                        <div class="progress-bar bg-theme" role="progressbar" style="width: 45%"--}}
    {{--                                             aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <p class="small text-secondary">Targeted Orders<span class="float-end">260</span></p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- Income green chart card -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <div class="card border-0 theme-green h-100">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="bi bi-arrow-down-left h5 avatar avatar-40 bg-light-theme rounded"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium mb-0">Income</h6>--}}
    {{--                                <p class="text-secondary small">Last 6 Months</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body">--}}
    {{--                        <h4>2,545.98 <small>USD <small class="text-green"><i class="bi bi-arrow-up"></i> 108.71 (0.73%)</small></small>--}}
    {{--                        </h4>--}}
    {{--                        <div class="mediumchart">--}}
    {{--                            <canvas id="mediumchartgreen1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- Expense red chart card -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <div class="card border-0 theme-red h-100">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="bi bi-arrow-up-right h5 avatar avatar-40 bg-light-theme rounded"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium mb-0">Expense</h6>--}}
    {{--                                <p class="text-secondary small">Last 6 Months</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body">--}}
    {{--                        <h4>1245.98 <small>USD <small class="text-green"><i class="bi bi-arrow-up"></i> 200.51--}}
    {{--                                    (0.73%)</small></small></h4>--}}
    {{--                        <div class="mediumchart">--}}
    {{--                            <canvas id="mediumchartred1"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row mb-4 py-2">--}}
    {{--            <div class="col text-center">--}}
    {{--                <h4>Special <span class="text-gradient">Widgets</span> flexible & elastic</h4>--}}
    {{--                <p class="text-secondary">Pick the best which is useful to your customer</p>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row">--}}
    {{--            <!-- Paybill -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <div class="card border-0 theme-blue h-100 overflow-hidden">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="bi bi-receipt h5 avatar avatar-40 bg-light-theme rounded"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium mb-0">Pay: Sci-Lindsey Coffee</h6>--}}
    {{--                                <p class="text-secondary small">AB-658, Wall Street, NY 3654, USA</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="row gx-0 h-100">--}}
    {{--                            <div class="col bg-light-theme text-center py-4">--}}
    {{--                                <h4 class="mb-1">$ 16.00</h4>--}}
    {{--                                <p class="text-secondary small mb-3">6 Coffee, Bread Butter, 2 more...</p>--}}
    {{--                                <div class="avatar avatar-100 coverimg mb-3 rounded">--}}
    {{--                                    <img src="img/getwindoors-qr.png" alt="" class="w-100"/>--}}
    {{--                                </div>--}}
    {{--                                <br>--}}
    {{--                                <a class="btn btn-theme btn-md" href="payment.html">Pay online</a>--}}
    {{--                            </div>--}}
    {{--                            <div class="col position-relative text-white text-center">--}}
    {{--                                <div class="coverimg position-absolute end-0 top-0 w-100 h-100">--}}
    {{--                                    <img src="img/payment.png" alt="">--}}
    {{--                                </div>--}}
    {{--                                <div class="row h-100">--}}
    {{--                                    <div class="col-12 align-self-center">--}}
    {{--                                        <div class="row align-items-center gx-2 mb-2">--}}
    {{--                                            <div class="col text-end h4">50%</div>--}}
    {{--                                            <div class="col text-start">Instant<br/>Discount</div>--}}
    {{--                                        </div>--}}
    {{--                                        <p>Get most popular discounts with easy Google Pay. Just scan QR an pay it.</p>--}}
    {{--                                        <a class="btn btn-light btn-md p-0" href="#">--}}
    {{--                                            <img src="img/Google-Pay-logo-1024x512.png" alt=""/>--}}
    {{--                                        </a>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- Subscribe upgrade plan -->--}}
    {{--            <div class="col-12 col-md-6 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <div class="card border-0 theme-blue overflow-hidden h-100">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="bi bi-award h5 avatar avatar-40 bg-green text-white rounded"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium mb-0">Subscribe PRO account</h6>--}}
    {{--                                <p class="text-secondary small">60 USD per year</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <ul class="list-group list-group-flush bg-none">--}}
    {{--                            <li class="list-group-item text-secondary">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <i class="bi bi-check-circle text-muted"></i>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto ps-0">--}}
    {{--                                        Unlimited Send Money--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </li>--}}
    {{--                            <li class="list-group-item text-secondary">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <i class="bi bi-check-circle text-muted"></i>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto ps-0">--}}
    {{--                                        Multiple Currencies Support--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </li>--}}
    {{--                            <li class="list-group-item text-secondary">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <i class="bi bi-check-circle text-muted"></i>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto ps-0">--}}
    {{--                                        Unlimited Send Money--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </li>--}}
    {{--                        </ul>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-6">--}}
    {{--                                <div class="form-check form-check-circle">--}}
    {{--                                    <input class="form-check-input" type="radio" name="priceupgrade" value=""--}}
    {{--                                           id="priceupgrade1" checked>--}}
    {{--                                    <label class="form-check-label" for="priceupgrade1">--}}
    {{--                                        <span class="h4">251.00 <small>USD</small></span><br/>--}}
    {{--                                        <span class="text-secondary small">5 Years <span--}}
    {{--                                                class="text-green">13% Off</span></span>--}}
    {{--                                    </label>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-6">--}}
    {{--                                <div class="form-check form-check-circle">--}}
    {{--                                    <input class="form-check-input" type="radio" name="priceupgrade" value=""--}}
    {{--                                           id="priceupgrade2">--}}
    {{--                                    <label class="form-check-label" for="priceupgrade2">--}}
    {{--                                        <span class="h4">108.00 <small>USD</small></span><br/>--}}
    {{--                                        <span class="text-secondary small">2 Years <span--}}
    {{--                                                class="text-green">10% Off</span></span>--}}
    {{--                                    </label>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-footer">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col">--}}
    {{--                                <div class="input-group border">--}}
    {{--                                    <span class="input-group-text text-theme"><i class="bi bi-tag h5"></i></span>--}}
    {{--                                    <input type="text" class="form-control" placeholder="Coupon Code"--}}
    {{--                                           value="13DISCNEW21">--}}
    {{--                                    <span class="input-group-text text-green"><i--}}
    {{--                                            class="bi bi-patch-check h5"></i></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <a class="btn btn-theme btn-md" href="payment.html">Pay Now</a>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <!-- event and offer -->--}}
    {{--            <div class="col-12 col-md-12 col-lg-6 col-xxl-4 mb-4 column-set">--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row align-items-center">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <i class="comingsoonbi bi-calendar-event h5 avatar avatar-40 bg-light-green text-green text-green rounded "></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col">--}}
    {{--                                <h6 class="fw-medium mb-0">Personal Finance Management</h6>--}}
    {{--                                <p class="text-secondary small">Go with trend, Protect your future</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto ">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-9" data-title="9"><span></span></div>--}}
    {{--                                                        <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <div class="avatar avatar-44 coverimg rounded-circle me-1">--}}
    {{--                                    <img src="img/user-2.jpg" alt=""/>--}}
    {{--                                </div>--}}
    {{--                                <div class="avatar avatar-44 coverimg rounded-circle me-1">--}}
    {{--                                    <img src="img/user-3.jpg" alt=""/>--}}
    {{--                                </div>--}}
    {{--                                <div class="avatar avatar-44 coverimg rounded-circle me-1">--}}
    {{--                                    <img src="img/user-4.jpg" alt=""/>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col ps-0">--}}
    {{--                                <p class="text-secondary small mb-1">Joined</p>--}}
    {{--                                <p>1525 k</p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto">--}}
    {{--                                <a class="btn btn-theme btn-md" href="payment.html">Pay Now</a>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card border-0 overflow-hidden">--}}
    {{--                    <div class="row mx-0">--}}
    {{--                        <div class="col-6 pe-0 bg-theme text-white half-circle-vertical text-center py-4 z-index-1">--}}
    {{--                            <div class="position-relative">--}}
    {{--                                <h2 class="mb-2">15% OFF</h2>--}}
    {{--                                <p class="text-muted small mb-3">Price including with our launch offer get 5% Extra</p>--}}
    {{--                                <button class="copy-text btn btn-sm btn-rounded btn-outline-dashed text-white border-white">--}}
    {{--                                    GOREELLAUNCH--}}
    {{--                                </button>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-6 position-relative">--}}
    {{--                            <figure class="coverimg position-absolute w-100 h-100 start-0 top-0 m-0">--}}
    {{--                                <img src="img/news-3.jpg" class="mw-100" alt=""/>--}}
    {{--                            </figure>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="row">--}}
    {{--            <!-- sort summary block -->--}}
    {{--            <div class="col-12 col-lg-12 col-xl-12 col-xxl-2 column-set">--}}
    {{--                <div class="row">--}}
    {{--                    <div class="col-12 col-sm-6 col-md-6 col-xxl-12">--}}
    {{--                        <div class="card border-0 bg-green text-white mb-4">--}}
    {{--                            <div class="card-header bg-none">--}}
    {{--                                <div class="row align-items-center">--}}
    {{--                                    <div class="col">--}}
    {{--                                        <i class="bi bi-bank h5 me-1 avatar avatar-40 bg-light-white text-white rounded me-2"></i>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto">--}}
    {{--                                        <div class="dropdown d-inline-block">--}}
    {{--                                            <a class="btn btn-link btn-square text-white dd-arrow-none dropdown-toggle"--}}
    {{--                                               role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                               aria-expanded="false">--}}
    {{--                                                <i class="bi bi-columns"></i>--}}
    {{--                                            </a>--}}
    {{--                                            <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                                <div class="dropdown-item text-center">--}}
    {{--                                                    <div class="row gx-3 mb-3">--}}
    {{--                                                        <div class="col-6">--}}
    {{--                                                            <div class="row select-column-size gx-1">--}}
    {{--                                                                <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                                <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                                <div class="col-2" data-title="2"><span></span></div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col-6">--}}
    {{--                                                            <div class="row select-column-size gx-1">--}}
    {{--                                                                <div class="col-7" data-title="7"><span></span></div>--}}
    {{--                                                                <div class="col-3" data-title="3"><span></span></div>--}}
    {{--                                                                <div class="col-2" data-title="2"><span></span></div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                    </div>--}}
    {{--                                                    <div class="row gx-3">--}}
    {{--                                                        <div class="col-6">--}}
    {{--                                                            <div class="row select-column-size gx-1">--}}
    {{--                                                                <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                                <div class="col-5" data-title="5"><span></span></div>--}}
    {{--                                                                <div class="col-1" data-title="1"><span></span></div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col-6">--}}
    {{--                                                            <div class="row select-column-size gx-1">--}}
    {{--                                                                <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="card-body bg-none">--}}
    {{--                                <p class="mb-2">Bank Balance</p>--}}
    {{--                                <p class="small text-muted mb-0">4% Annual Interest</p>--}}
    {{--                                <p class="small text-muted mb-4">6% Lease Earning</p>--}}
    {{--                                <h5 class="mb-1">12546.00 <small>USD</small></h5>--}}
    {{--                                <p class="small text-muted">16 transactions this month</p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-12 col-sm-6 col-md-6 col-xxl-12">--}}
    {{--                        <div class="card border-0 theme-green mb-4">--}}
    {{--                            <div class="card-header">--}}
    {{--                                <div class="row align-items-center">--}}
    {{--                                    <div class="col">--}}
    {{--                                        <i class="bi bi-bank h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-auto">--}}

    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="card-body">--}}
    {{--                                <p class="mb-2">Crypto Assets</p>--}}
    {{--                                <p class="small text-muted mb-4">8 Crypto</p>--}}

    {{--                                <h5 class="mb-1">12580.00 <small>USD</small></h5>--}}
    {{--                                <p class="small text-theme"><i class="bi bi-arrow-up"></i> 10.15% Today</p>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-10 position-relative column-set">--}}
    {{--                <!-- Grid table-->--}}
    {{--                <div class="card border-0 mb-4">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <div class="row">--}}
    {{--                            <div class="col-auto mb-2">--}}
    {{--                                <i class="bi bi-shop h5 avatar avatar-40 bg-light-theme rounded"></i>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto align-self-center mb-2">--}}
    {{--                                <h5 class="d-inline-block">Expense</h5>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-12 col-md-5 col-lg-4 col-xl-3 col-xxl-2 mb-2">--}}
    {{--                                <div class="rounded border">--}}
    {{--                                    <select class="chosenoptgroup w-100">--}}
    {{--                                        <option>All Categories</option>--}}
    {{--                                        <optgroup label="Food">--}}
    {{--                                            <option>- Work</option>--}}
    {{--                                            <option>- Home</option>--}}
    {{--                                        </optgroup>--}}
    {{--                                        <optgroup label="Children">--}}
    {{--                                            <option>- Fees</option>--}}
    {{--                                            <option>- Enjoyment</option>--}}
    {{--                                        </optgroup>--}}
    {{--                                        <optgroup label="Transportation">--}}
    {{--                                            <option>- Personal</option>--}}
    {{--                                            <option>- Work</option>--}}
    {{--                                        </optgroup>--}}
    {{--                                        <optgroup label="Home">--}}
    {{--                                            <option>- Rent</option>--}}
    {{--                                            <option>- Maintenance</option>--}}
    {{--                                        </optgroup>--}}
    {{--                                        <option>Personal</option>--}}
    {{--                                        <option>Other</option>--}}
    {{--                                    </select>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto ms-auto">--}}
    {{--                                <div class="input-group border">--}}
    {{--                                    <span class="input-group-text text-theme"><i class="bi bi-search"></i></span>--}}
    {{--                                    <input type="text" class="form-control" placeholder="Search...">--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-auto ps-0">--}}
    {{--                                <div class="dropdown d-inline-block">--}}
    {{--                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle"--}}
    {{--                                       role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
    {{--                                       aria-expanded="false">--}}
    {{--                                        <i class="bi bi-columns"></i>--}}
    {{--                                    </a>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                        <div class="dropdown-item text-center">--}}
    {{--                                            <div class="row gx-3 mb-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-10" data-title="10"><span></span></div>--}}
    {{--                                                        <div class="col-2" data-title="2"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-8" data-title="8"><span></span></div>--}}
    {{--                                                        <div class="col-4" data-title="4"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="row gx-3">--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                        <div class="col-6" data-title="6"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col-6">--}}
    {{--                                                    <div class="row select-column-size gx-1">--}}
    {{--                                                        <div class="col-12" data-title="12"><span></span></div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body p-0">--}}
    {{--                        <div class="row gx-0 h-100">--}}
    {{--                            <div class="col-12 col-lg-12 col-xl-8 col-xxl-7">--}}
    {{--                                <table class="table table-borderless footable" data-show-toggle="true">--}}
    {{--                                    <thead>--}}
    {{--                                    <tr class="text-muted">--}}
    {{--                                        <th class="w-12"></th>--}}
    {{--                                        <th class="">Expense</th>--}}
    {{--                                        <th data-breakpoints="xs">Amount</th>--}}
    {{--                                        <th data-breakpoints="xs">Category</th>--}}
    {{--                                        <th data-breakpoints="all">Status</th>--}}
    {{--                                        <th data-breakpoints="all" data-title="Deliver to">Deliver to</th>--}}
    {{--                                        <th data-breakpoints="all" data-title="Address">Address</th>--}}
    {{--                                        <th data-breakpoints="all" data-title="Location">Location</th>--}}
    {{--                                        <th data-breakpoints="xs">Payment</th>--}}
    {{--                                        <th>Action</th>--}}
    {{--                                    </tr>--}}
    {{--                                    </thead>--}}
    {{--                                    <tbody>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company6.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">BitCoins</p>--}}
    {{--                                                    <p class="text-secondary small">1 way trip</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">0.000015</p>--}}
    {{--                                            <p class="text-secondary small">BTC</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Traveling</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-green">Paid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company2.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">Starbucks</p>--}}
    {{--                                                    <p class="text-secondary small">2 Coffee</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">6.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Food</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-blue">Waiting</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company3.jpg" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">EasyMall</p>--}}
    {{--                                                    <p class="text-secondary small">12 Items</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">180.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Grocery</p>--}}
    {{--                                            <p class="text-secondary small">Home</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-red">Unpaid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company4.jpg" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">Zomaatos</p>--}}
    {{--                                                    <p class="text-secondary small">Meal</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">12.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Food</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-green">Paid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company5.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">UserCars</p>--}}
    {{--                                                    <p class="text-secondary small">Round Trip</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">251.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Travelling</p>--}}
    {{--                                            <p class="text-secondary small">Home</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-blue">Waiting</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}

    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company4.jpg" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">Zomaatos</p>--}}
    {{--                                                    <p class="text-secondary small">Meal</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">12.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Food</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-green">Paid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company5.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">UserCars</p>--}}
    {{--                                                    <p class="text-secondary small">Round Trip</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">251.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Travelling</p>--}}
    {{--                                            <p class="text-secondary small">Home</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-blue">Waiting</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company6.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">BitCoins</p>--}}
    {{--                                                    <p class="text-secondary small">1 way trip</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">0.000015</p>--}}
    {{--                                            <p class="text-secondary small">BTC</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Traveling</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-green">Paid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company2.png" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">Starbucks</p>--}}
    {{--                                                    <p class="text-secondary small">2 Coffee</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">6.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Food</p>--}}
    {{--                                            <p class="text-secondary small">Work</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-blue">Waiting</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="row align-items-center">--}}
    {{--                                                <div class="col-auto">--}}
    {{--                                                    <figure class="avatar avatar-40 mb-0 coverimg rounded-circle">--}}
    {{--                                                        <img src="img/company3.jpg" alt=""/>--}}
    {{--                                                    </figure>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="col ps-0">--}}
    {{--                                                    <p class="mb-0">EasyMall</p>--}}
    {{--                                                    <p class="text-secondary small">12 Items</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">180.00</p>--}}
    {{--                                            <p class="text-secondary small">USD</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-0">Grocery</p>--}}
    {{--                                            <p class="text-secondary small">Home</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            Delivered--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            John McGra--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            2356, Street-5, New York 4586, US--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <p class="mb-1">Lat: 5.678167, Long: 12.078613</p>--}}
    {{--                                            <p class="text-secondary small">This can be a map also</p>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <span class="badge badge-sm bg-red">Unpaid</span>--}}
    {{--                                        </td>--}}
    {{--                                        <td>--}}
    {{--                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"--}}
    {{--                                                    data-bs-target="#edittable"><i class="bi bi-pencil"></i></button>--}}
    {{--                                            <button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i>--}}
    {{--                                            </button>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    </tbody>--}}
    {{--                                </table>--}}

    {{--                                <div class="row align-items-center mx-0 mb-3">--}}
    {{--                                    <div class="col-6 ">--}}
    {{--                                            <span class="hide-if-no-paging">--}}
    {{--                                                Showing <span id="footablestot"></span> page--}}
    {{--                                            </span>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-6" id="footable-pagination"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-12 col-lg-12 col-xl-4 col-xxl-5 bg-light-theme">--}}
    {{--                                <div class="row h-100 mx-0">--}}
    {{--                                    <div class="col-12 ">--}}
    {{--                                        <div class="doughnutchart my-4">--}}
    {{--                                            <canvas id="doughnutchart"></canvas>--}}
    {{--                                            <div class="countvalue shadow">--}}
    {{--                                                <div class="w-100">--}}
    {{--                                                    <h5 class="mb-1">2,55.98K</h5>--}}
    {{--                                                    <p class="text-success small">18.71 (0.73%)</p>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-12 bg-light-white mt-auto px-5 py-4 position-relative">--}}
    {{--                                        <div class="swiper-container categorychartswiper">--}}
    {{--                                            <div class="swiper-wrapper">--}}
    {{--                                                <div class="swiper-slide">--}}
    {{--                                                    <div class="row amount-data">--}}
    {{--                                                        <div class="col-auto">--}}
    {{--                                                                <span class="avatar avatar-40 bg-green rounded text-white">--}}
    {{--                                                                    <i class="bi bi-truck h5"></i>--}}
    {{--                                                                </span>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col ps-0">--}}
    {{--                                                            <h6 class="text-dark mb-0">120 <small>USD</small></h6>--}}
    {{--                                                            <p class="text-secondary small">Transport</p>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col-12 mt-2">--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-4">--}}
    {{--                                                                    <p class="text-secondary small">Home</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-8">--}}
    {{--                                                                    <p class="text-green small">8.30 %</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-4">--}}
    {{--                                                                    <p class="text-secondary small">Work</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-8">--}}
    {{--                                                                    <p class="text-green small">2.65 %</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="swiper-slide">--}}
    {{--                                                    <div class="row amount-data">--}}
    {{--                                                        <div class="col-auto">--}}
    {{--                                                                <span class="avatar avatar-40 bg-yellow text-white rounded">--}}
    {{--                                                                    <i class="bi bi-egg-fried h5"></i>--}}
    {{--                                                                </span>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col ps-0">--}}
    {{--                                                            <h6 class="text-dark mb-0">168 <small>USD</small></h6>--}}
    {{--                                                            <p class="text-secondary small">Food</p>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col-12 mt-2">--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-4">--}}
    {{--                                                                    <p class="text-secondary small">Home</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-8">--}}
    {{--                                                                    <p class="text-green small">5.30 %</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-4">--}}
    {{--                                                                    <p class="text-secondary small">Work</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-8">--}}
    {{--                                                                    <p class="text-danger small">2.00 %</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                                <div class="swiper-slide">--}}
    {{--                                                    <div class="row amount-data">--}}
    {{--                                                        <div class="col-auto">--}}
    {{--                                                                <span class="avatar avatar-40 bg-red text-white rounded">--}}
    {{--                                                                    <i class="bi bi-person h5"></i>--}}
    {{--                                                                </span>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col ps-0">--}}
    {{--                                                            <h6 class="text-dark mb-0">200 <small>USD</small></h6>--}}
    {{--                                                            <p class="text-secondary small">Children</p>--}}
    {{--                                                        </div>--}}
    {{--                                                        <div class="col-12 mt-2">--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-6">--}}
    {{--                                                                    <p class="text-secondary small">Fees</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-6">--}}
    {{--                                                                    <p class="text-green small">530 USD</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                            <div class="row gx-2">--}}
    {{--                                                                <div class="col-6">--}}
    {{--                                                                    <p class="text-secondary small">Enjoyment</p>--}}
    {{--                                                                </div>--}}
    {{--                                                                <div class="col-6">--}}
    {{--                                                                    <p class="text-danger small">10.00 %</p>--}}
    {{--                                                                </div>--}}
    {{--                                                            </div>--}}
    {{--                                                        </div>--}}
    {{--                                                    </div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

</main>


@include('include.footer')
