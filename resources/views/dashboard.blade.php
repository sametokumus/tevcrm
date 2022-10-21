@include('include.header')
<?php
$extra_js='
<script src="services/dashboard.js"></script>
    <!-- INTERNAL APEXCHART JS -->
    <script src="js/apexcharts.js"></script>
    <script src="plugins/apexchart/irregular-data-series.js"></script>

    <!-- C3 CHART JS -->
    <script src="plugins/charts-c3/d3.v5.min.js"></script>
    <script src="plugins/charts-c3/c3-chart.js"></script>

    <!-- CHART-DONUT JS -->
    <script src="js/charts.js"></script>

    <!-- INTERNAL Flot JS -->
    <script src="plugins/flot/jquery.flot.js"></script>
    <script src="plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="plugins/flot/chart.flot.sampledata.js"></script>
    <script src="plugins/flot/dashboard.sampledata.js"></script>

    <!-- INTERNAL Vector js -->
    <script src="plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- INTERNAL INDEX JS -->
    <script src="js/index1.js"></script>
';
?>



    <!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Anasayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->



@include('include.footer')
