@include('include.header')
<?php
$extra_js='
<script src="services/staff-dashboard.js"></script>
';
?>

<div id="content" class="app-content">


    <div class="row">
        <div class="col-md-3">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">YILLIK SATIŞLARIN DAĞILIMI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                        <div class="d-block">
                            Hedef: 2.000.000,00 TRY | Satış: 1.600.000,00 TRY
                        </div>
                    </div>


                    <div class="mb-3">
                        <div id="chart-sales-monthly">

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
        <div class="col-md-3">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ONAYLANAN SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-approved-monthly">

                            <div class="spinners">
                                <div class="spinner spinner-grow spinner-grow-1 m-1 text-success"></div>
                                <div class="spinner spinner-grow spinner-grow-2 m-1 text-success"></div>
                                <div class="spinner spinner-grow spinner-grow-3 m-1 text-success"></div>
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
        <div class="col-md-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ONAYLANAN SATIŞLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-approved-monthly">

                            <div class="spinners">
                                <div class="spinner spinner-grow spinner-grow-1 m-1 text-success"></div>
                                <div class="spinner spinner-grow spinner-grow-2 m-1 text-success"></div>
                                <div class="spinner spinner-grow spinner-grow-3 m-1 text-success"></div>
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

    <div id="staffs">



    </div>



</div>


@include('include.footer')
