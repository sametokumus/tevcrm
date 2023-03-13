@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="js/demo/dashboard.demo.js"></script>
<script src="services/sale-detail.js"></script>
';
?>

<div id="content" class="app-content">

    <div class="row">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Müşteri</span>
                        <h3 id="customer-name"></h3>
                        <div id="customer-employee"></div>
                        <div id="owner-employee"></div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-gem fa-4x"></i>
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

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Toplam Satış Tutarı</span>
                        <h3 id="total-price"></h3>
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-wallet fa-4x"></i>
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

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Ürün</span>
                        <h3 id="product-count"></h3>
                        <div id="product-total-count"></div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-people-carry fa-4x"></i>
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

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Talep Tarihi</span>
                        <h3 id="sale-date"></h3>
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-calendar-day fa-4x"></i>
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
                        <span class="flex-grow-1">TEDARİKÇİLER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="suppliers-table" class="table table-striped table-borderless mb-2px small text-nowrap">
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


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SİPARİŞ SÜRECİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="status-history-table" class="table table-striped table-borderless mb-2px small text-nowrap">
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

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOP REQUESTED PRODUCTS</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="top-products-table" class="table table-striped table-borderless mb-2px small text-nowrap">
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
