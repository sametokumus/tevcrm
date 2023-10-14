@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="services/accounting-dashboard.js"></script>
';
?>

<div id="content" class="app-content">
    <div class="row d-none">
        <div class="col-12 mb-5">
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
                        <span class="flex-grow-1">TOPLAM ÖDENEN</span>
                        <a href="#" data-toggle="card-expand" class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="total-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3 other-currencies">
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="total-text">

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
                        <span class="flex-grow-1">BEKLEYEN ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="pending-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors"
                                 data-height="30"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="pending-text">

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
                        <span class="flex-grow-1">GECİKEN ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="late-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="late-text">

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
                        <span class="flex-grow-1">ORTALAMA KARLILIK</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="profit-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="profit-text">

                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
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

        <div class="col-xl-12">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand" class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-cashflow"></div>
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

        <div class="col-xl-12 col-lg-12">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">NAKİT AKIŞI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row mb-2" id="cashflow-box">



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

        <div class="col-xl-12 col-lg-12">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row mb-2">

                        <table id="payments-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 bg-dark" data-priority="1">N#</th>
                                <th class="border-bottom-0 bg-dark">Satış Kodu</th>
                                <th class="border-bottom-0 bg-dark">Müşteri</th>
                                <th class="border-bottom-0 bg-dark">Durum</th>
                                <th class="border-bottom-0 bg-dark">Durum</th>
                                <th class="border-bottom-0">Ödeme Türü</th>
                                <th class="border-bottom-0">Ödeme Yöntemi</th>
                                <th class="border-bottom-0">Vade Tarihi</th>
                                <th class="border-bottom-0">Fatura Tutarı</th>
                                <th class="border-bottom-0">KDV Tutarı</th>
                                <th class="border-bottom-0">Toplam Tutar</th>
                                <th class="border-bottom-0">Para Birimi</th>
                                <th class="border-bottom-0 d-none">Order</th>
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


@include('include.footer')
