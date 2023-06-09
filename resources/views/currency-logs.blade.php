@include('include.header')
<?php
$extra_js='
<script src="services/currency-logs.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Yeni Kayıt Oluştur
                    </h1>
                </div>
            </div>

            <form method="post" action="#" id="add_currency_log_form">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">USD</label>
                                        <input class="form-control" id="add_currency_log_usd">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">EUR</label>
                                        <input class="form-control" id="add_currency_log_eur">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">GBP</label>
                                        <input class="form-control" id="add_currency_log_gbp">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Kur Ekle</button>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="button" class="btn btn-warning w-100" onclick="getLiveCurrencyLog();">TCMB Güncel Kur Sorgula</button>
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
            </form>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Döviz Geçmişi
                    </h1>
                </div>
            </div>

            <table id="currency-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Tarih</th>
                    <th class="border-bottom-0">USD</th>
                    <th class="border-bottom-0">EUR</th>
                    <th class="border-bottom-0">GBP</th>
                    <th class="border-bottom-0" data-priority="2">İşlem</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->


@include('include.footer')
