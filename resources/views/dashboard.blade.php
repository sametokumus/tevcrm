@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="plugins/masonry-layout/dist/masonry.pkgd.min.js"></script>
<script src="services/dashboard.js"></script>
';
?>

<div id="content" class="app-content">
    <div class="row">
        <div class="col-6 mb-5">
            <select class="form-control" id="dash_owner" onchange="changeDashOwner();">
            </select>
        </div>
        <div class="col-6 mb-5">
            <select class="form-control" id="dash_currency" onchange="changeDashCurrency();">
                <option value="TRY">TRY</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOPLAM ONAYLANAN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="approved-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOPLAM TAMAMLANAN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="completed-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors"
                                 data-height="30"></div>
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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOPLAM POTANSİYEL</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="potential-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors"
                                 data-height="45"></div>
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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOPLAM İPTAL EDİLEN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="cancelled-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors"
                                 data-height="45"></div>
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

    <div class="row sparkboxes">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">BU AY ONAYLANAN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="row align-items-center mb-2" id="monthly-approved-box">
                        <div class="col-12">
                            <h4 class="mb-0"></h4>
                        </div>
                    </div>

                    <div class="box box1">
                        <div id="spark1"></div>
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
                        <span class="flex-grow-1">BU AY TAMAMLANAN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="row align-items-center mb-2" id="monthly-completed-box">
                        <div class="col-12">
                            <h4 class="mb-0"></h4>
                        </div>
                    </div>

                    <div class="box box2">
                        <div id="spark2"></div>
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
                        <span class="flex-grow-1">BU AY POTANSİYEL</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="row align-items-center mb-2" id="monthly-continue-box">
                        <div class="col-12">
                            <h4 class="mb-0"></h4>
                        </div>
                    </div>

                    <div class="box box3">
                        <div id="spark3"></div>
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
                        <span class="flex-grow-1">BU AY İPTAL EDİLEN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="row align-items-center mb-2" id="monthly-cancelled-box">
                        <div class="col-12">
                            <h4 class="mb-0"></h4>
                        </div>
                    </div>

                    <div class="box box4">
                        <div id="spark4"></div>
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

    <div class="row">
        <div class="col-xl-6">
    <div class="row masonry-layout">

        <div class="col-xl-3">

            <!-- onaylanan satışlar -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ONAYLANAN SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-approved-monthly"></div>
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

        <div class="col-xl-3">

            <!-- tamamlanan satışlar -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TAMAMLANAN SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-completed-monthly"></div>
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

        <div class="col-xl-3">

            <!-- potansiyel satışlar -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">POTANSİYEL SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-potential-sales"></div>
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

        <div class="col-xl-3">

            <!-- iptal edilen satışlar -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">İPTAL EDİLEN SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-cancelled-potential-sales"></div>
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
        <div class="col-xl-6">
    <div class="row masonry-layout">

        <div class="col-xl-4">

            <!-- en iyi müşteri -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">EN İYİ MÜŞTERİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="table-responsive">
                        <table id="best-customers-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Müşteri</td>
                                <td>Puan</td>
                            </tr>
                            </thead>
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

        <div class="col-xl-4">

            <!-- en iyi personel -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">EN İYİ PERSONEL</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="table-responsive">
                        <table id="best-staffs-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Ad Soyad</td>
                                <td>Puan</td>
                            </tr>
                            </thead>
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

        <div class="col-xl-4">

            <!-- ekip satışları -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">EKİP SATIŞLARI (Son 12 Ay)</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="table-responsive">
                        <table id="admins-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Ad Soyad</td>
                                <td>Satış Adedi</td>
                                <td>Tutar</td>
                            </tr>
                            </thead>
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

        <div class="col-xl-4">

            <!-- en çok satılan ürünler -->
            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">EN ÇOK SATILAN ÜRÜNLER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="table-responsive">
                        <table id="top-products-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <thead>
                            <tr>
                                <td>Ürün Adı</td>
                                <td>Adet</td>
                            </tr>
                            </thead>
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
    </div>

</div>


@include('include.footer')
