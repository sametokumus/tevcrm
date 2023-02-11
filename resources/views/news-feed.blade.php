@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="js/demo/dashboard.demo.js"></script>
<script src="services/news-feed.js"></script>
';
?>

<div id="content" class="app-content">

    <div class="row">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SITE VISITORS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">4.2m</h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors"
                                 data-height="30"></div>
                        </div>
                    </div>


                    <div class="small text-white text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 33.3% more than last week<br/>
                        <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br/>
                        <i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">STORE SALES</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">$35.2K</h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors"
                                 data-height="30"></div>
                        </div>
                    </div>


                    <div class="small text-white text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 20.4% more than last week<br/>
                        <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br/>
                        <i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">NEW MEMBERS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">4,490</h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="small text-white text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 59.5% more than last week<br/>
                        <i class="fab fa-facebook-f fa-fw me-1"></i> 45.5% from facebook<br/>
                        <i class="fab fa-youtube fa-fw me-1"></i> 15.25% from youtube
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">BANDWIDTH</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2">
                        <div class="col-7">
                            <h3 class="mb-0">4.5TB</h3>
                        </div>
                        <div class="col-5">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="small text-white text-opacity-50 text-truncate">
                        <i class="fa fa-chevron-up fa-fw me-1"></i> 5.3% more than last week<br/>
                        <i class="far fa-hdd fa-fw me-1"></i> 10.5% from total usage<br/>
                        <i class="far fa-hand-point-up fa-fw me-1"></i> 2MB per visit
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SERVER STATS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="ratio ratio-21x9 mb-3">
                        <div id="chart-server"></div>
                    </div>


                    <div class="row">

                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">

                                <div class="w-50px h-50px">
                                    <div data-render="apexchart" data-type="donut" data-title="Visitors"
                                         data-height="50"></div>
                                </div>


                                <div class="ps-3 flex-1">
                                    <div class="fs-10px fw-bold text-white text-opacity-50 mb-1">DISK USAGE</div>
                                    <div class="mb-2 fs-5 text-truncate">20.04 / 256 GB</div>
                                    <div class="progress h-3px bg-white-transparent-2 mb-1">
                                        <div class="progress-bar bg-theme" style="width: 20%"></div>
                                    </div>
                                    <div class="fs-11px text-white text-opacity-50 mb-2 text-truncate">
                                        Last updated 1 min ago
                                    </div>
                                    <div class="d-flex align-items-center small">
                                        <i class="bi bi-circle-fill fs-6px me-2 text-theme"></i>
                                        <div class="flex-1">DISK C</div>
                                        <div>19.56GB</div>
                                    </div>
                                    <div class="d-flex align-items-center small">
                                        <i class="bi bi-circle-fill fs-6px me-2 text-theme text-opacity-50"></i>
                                        <div class="flex-1">DISK D</div>
                                        <div>0.50GB</div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="d-flex">

                                <div class="w-50px pt-3">
                                    <div data-render="apexchart" data-type="donut" data-title="Visitors"
                                         data-height="50"></div>
                                </div>


                                <div class="ps-3 flex-1">
                                    <div class="fs-10px fw-bold text-white text-opacity-50 mb-1">BANDWIDTH</div>
                                    <div class="mb-2 fs-5 text-truncate">83.76GB / 10TB</div>
                                    <div class="progress h-3px bg-white-transparent-2 mb-1">
                                        <div class="progress-bar bg-theme" style="width: 10%"></div>
                                    </div>
                                    <div class="fs-11px text-white text-opacity-50 mb-2 text-truncate">
                                        Last updated 1 min ago
                                    </div>
                                    <div class="d-flex align-items-center small">
                                        <i class="bi bi-circle-fill fs-6px me-2 text-theme"></i>
                                        <div class="flex-1">HTTP</div>
                                        <div>35.47GB</div>
                                    </div>
                                    <div class="d-flex align-items-center small">
                                        <i class="bi bi-circle-fill fs-6px me-2 text-theme text-opacity-50"></i>
                                        <div class="flex-1">FTP</div>
                                        <div>1.25GB</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TRAFFIC ANALYTICS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="ratio ratio-21x9 mb-3">
                        <div id="world-map" class="jvectormap-without-padding"></div>
                    </div>


                    <div class="row gx-4">

                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <table class="w-100 small mb-0 text-truncate text-white text-opacity-60">
                                <thead>
                                <tr class="text-white text-opacity-75">
                                    <th class="w-50">COUNTRY</th>
                                    <th class="w-25 text-end">VISITS</th>
                                    <th class="w-25 text-end">PCT%</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>FRANCE</td>
                                    <td class="text-end">13,849</td>
                                    <td class="text-end">40.79%</td>
                                </tr>
                                <tr>
                                    <td>SPAIN</td>
                                    <td class="text-end">3,216</td>
                                    <td class="text-end">9.79%</td>
                                </tr>
                                <tr class="text-theme fw-bold">
                                    <td>MEXICO</td>
                                    <td class="text-end">1,398</td>
                                    <td class="text-end">4.26%</td>
                                </tr>
                                <tr>
                                    <td>UNITED STATES</td>
                                    <td class="text-end">1,090</td>
                                    <td class="text-end">3.32%</td>
                                </tr>
                                <tr>
                                    <td>BELGIUM</td>
                                    <td class="text-end">1,045</td>
                                    <td class="text-end">3.18%</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="col-lg-6">

                            <div class="card">

                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="w-70px">
                                            <div data-render="apexchart" data-type="donut" data-height="70"></div>
                                        </div>
                                        <div class="flex-1 ps-2">
                                            <table class="w-100 small mb-0 text-white text-opacity-60">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-95"></div>
                                                            FEED
                                                        </div>
                                                    </td>
                                                    <td class="text-end">25.70%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-75"></div>
                                                            ORGANIC
                                                        </div>
                                                    </td>
                                                    <td class="text-end">24.30%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-55"></div>
                                                            REFERRAL
                                                        </div>
                                                    </td>
                                                    <td class="text-end">23.05%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-35"></div>
                                                            DIRECT
                                                        </div>
                                                    </td>
                                                    <td class="text-end">14.85%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-15"></div>
                                                            EMAIL
                                                        </div>
                                                    </td>
                                                    <td class="text-end">7.35%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-arrow">
                                    <div class="card-arrow-top-left"></div>
                                    <div class="card-arrow-top-right"></div>
                                    <div class="card-arrow-bottom-left"></div>
                                    <div class="card-arrow-bottom-right"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOP PRODUCTS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table class="w-100 mb-0 small align-middle text-nowrap">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="position-relative mb-2">
                                            <div class="bg-center bg-cover bg-no-repeat w-80px h-60px"
                                                 style="background-image: url(assets/img/dashboard/product-1.jpg);">
                                            </div>
                                            <div class="position-absolute top-0 start-0">
                                                <span class="badge bg-theme text-theme-900 rounded-0 d-flex align-items-center justify-content-center w-20px h-20px">1</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 ps-3">
                                            <div class="mb-1"><small
                                                    class="fs-9px fw-500 lh-1 d-inline-block rounded-0 badge bg-white bg-opacity-25 text-white text-opacity-75 pt-5px">SKU90400</small>
                                            </div>
                                            <div class="fw-500 text-white">Huawei Smart Watch</div>
                                            $399.00
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table class="mb-2">
                                        <tr>
                                            <td class="pe-3">QTY:</td>
                                            <td class="text-white text-opacity-75 fw-500">129</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3">REVENUE:</td>
                                            <td class="text-white text-opacity-75 fw-500">$51,471</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 text-nowrap">PROFIT:</td>
                                            <td class="text-white text-opacity-75 fw-500">$15,441</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a href="#" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex mb-2 align-items-center">
                                        <div class="position-relative">
                                            <div class="bg-center bg-cover bg-no-repeat w-80px h-60px"
                                                 style="background-image: url(assets/img/dashboard/product-2.jpg);">
                                            </div>
                                            <div class="position-absolute top-0 start-0">
                                                <span class="badge bg-theme text-theme-900 rounded-0 d-flex align-items-center justify-content-center w-20px h-20px">2</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 ps-3">
                                            <div class="mb-1"><small
                                                    class="fs-9px fw-500 lh-1 d-inline-block rounded-0 badge bg-white bg-opacity-25 text-white text-opacity-75 pt-5px">SKU85999</small>
                                            </div>
                                            <div class="text-white fw-500">Nike Shoes Black Version</div>
                                            $99.00
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table class="mb-2">
                                        <tr>
                                            <td class="pe-3">QTY:</td>
                                            <td class="text-white text-opacity-75 fw-500">108</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3">REVENUE:</td>
                                            <td class="text-white text-opacity-75 fw-500">$10,692</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 text-nowrap">PROFIT:</td>
                                            <td class="text-white text-opacity-75 fw-500">$5,346</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a href="#" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex mb-2 align-items-center">
                                        <div class="position-relative">
                                            <div class="bg-center bg-cover bg-no-repeat w-80px h-60px"
                                                 style="background-image: url(assets/img/dashboard/product-3.jpg);">
                                            </div>
                                            <div class="position-absolute top-0 start-0">
                                                <span class="badge bg-theme text-theme-900 rounded-0 d-flex align-items-center justify-content-center w-20px h-20px">3</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 ps-3">
                                            <div class="mb-1"><small
                                                    class="fs-9px fw-500 lh-1 d-inline-block rounded-0 badge bg-white bg-opacity-25 text-white text-opacity-75 pt-5px">SKU20400</small>
                                            </div>
                                            <div class="text-white fw-500">White Sony PS4</div>
                                            $599
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table class="mb-2">
                                        <tr>
                                            <td class="pe-3">QTY:</td>
                                            <td class="text-white text-opacity-75 fw-500">72</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3">REVENUE:</td>
                                            <td class="text-white text-opacity-75 fw-500">$43,128</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 text-nowrap">PROFIT:</td>
                                            <td class="text-white text-opacity-75 fw-500">$4,312</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a href="#" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex mb-2 align-items-center">
                                        <div class="position-relative">
                                            <div class="bg-center bg-cover bg-no-repeat w-80px h-60px"
                                                 style="background-image: url(assets/img/dashboard/product-4.jpg);">
                                            </div>
                                            <div class="position-absolute top-0 start-0">
                                                <span class="badge bg-black bg-opacity-50 rounded-0 d-flex align-items-center justify-content-center w-20px h-20px">4</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 ps-3">
                                            <div class="mb-1"><small
                                                    class="fs-9px fw-500 lh-1 d-inline-block rounded-0 badge bg-white bg-opacity-25 text-white text-opacity-75 pt-5px">SKU19299</small>
                                            </div>
                                            <div class="text-white fw-500">Apple Watch Series 5</div>
                                            $1,099
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table class="mb-2">
                                        <tr>
                                            <td class="pe-3">QTY:</td>
                                            <td class="text-white text-opacity-75 fw-500">53</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3">REVENUE:</td>
                                            <td class="text-white text-opacity-75 fw-500">$58,247</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 text-nowrap">PROFIT:</td>
                                            <td class="text-white text-opacity-75 fw-500">$2,912</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a href="#" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <div class="bg-center bg-cover bg-no-repeat w-80px h-60px"
                                                 style="background-image: url(assets/img/dashboard/product-5.jpg);">
                                            </div>
                                            <div class="position-absolute top-0 start-0">
                                                <span class="badge bg-black bg-opacity-50 rounded-0 d-flex align-items-center justify-content-center w-20px h-20px">5</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 ps-3">
                                            <div class="mb-1"><small
                                                    class="fs-9px fw-500 lh-1 d-inline-block rounded-0 badge bg-white bg-opacity-25 text-white text-opacity-75 pt-5px">SKU19299</small>
                                            </div>
                                            <div class="text-white fw-500">Black Nikon DSLR</div>
                                            1,899
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td class="pe-3">QTY:</td>
                                            <td class="text-white text-opacity-75 fw-500">50</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3">REVENUE:</td>
                                            <td class="text-white text-opacity-75 fw-500">$90,950</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-3 text-nowrap">PROFIT:</td>
                                            <td class="text-white text-opacity-75 fw-500">$2,848</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><a href="#" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">LATEST SALES HISTORY</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="sales-history-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>

    </div>

</div>


@include('include.footer')
