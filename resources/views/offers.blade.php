@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/offers.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Teklifler</h6>

                <div class="row">
                    <div class="col-12 col-md-12 position-relative">
                        <div class="card border-0 mb-4">
                            <div class="card-body p-4">
                                <table id="offer-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr class="text-muted">
                                        <th class="bg-theme bg-opacity-50">N#</th>
                                        <th class="bg-theme bg-opacity-50">Teklif Kodu</th>
                                        <th class="bg-theme bg-opacity-50">Müşteri</th>
                                        <th>Müşteri Yetkilisi</th>
                                        <th>Proje Sorumlusu</th>
                                        <th>Teklif Fiyatı</th>
                                        <th>Teklif Tarihi</th>
                                        <th>Durum</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>


@include('include.footer')
