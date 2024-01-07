@include('include.header')
<?php
$extra_js='
<script src="services/staff-dashboard.js"></script>
<script src="/plugins/apexcharts/dist/apexcharts.min.js"/></script>
';
?>

<div id="content" class="app-content">


    <div class="row">
        <div class="col-md-3">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-1">
                        <span class="flex-grow-1">YILLIK SATIŞLARIN DAĞILIMI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="d-block fw-bold small mb-3" id="yearly-result">

                    </div>


                    <div class="mb-3">
                        <div id="chart-yearly-result">

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

                    <div class="d-flex fw-bold small mb-1">
                        <span class="flex-grow-1">BU AY SATIŞLARIN DAĞILIMI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="d-block fw-bold small mb-3" id="monthly-result">

                    </div>


                    <div class="mb-3">
                        <div id="chart-monthly-result">

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
                        <span class="flex-grow-1">AYLIK HEDEF BAŞARI GRAFİĞİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-yearly-targets">

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
