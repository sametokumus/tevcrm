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
    <div class="row">
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
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="total-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
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

        <div class="col-xl-12 col-lg-12">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">NAKİT AKIŞI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row mb-2" id="cashflow-box">

                        <div class="col-xl-4 col-lg-6">

                            <div class="card mb-3">

                                <div class="card-header d-flex align-items-center bg-white bg-opacity-15">
                                    <span class="flex-grow-1 fw-400">10/2023</span>
                                    <span class="text-white text-opacity-50 text-decoration-none me-3">12.300,00 TRY / 500,00 EUR / 550,00 USD</span>
                                </div>


                                <div class="list-group list-group-flush">

                                    <div class="list-group-item d-flex px-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-google-wallet text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">Müşteri</div>
                                            <div class="text-white">Ödeme Tutarı</div>
                                            <div class="small text-white text-opacity-50 mb-2">Satış Kurları</div>
                                            <div>
                                                <span class="badge border border-danger text-danger">gecikmede</span>
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
