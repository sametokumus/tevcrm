@include('include.header')
<?php
$extra_js='
<script src="services/sales.js"></script>
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
                        Satışlar
                    </h1>
                </div>
            </div>

            <table id="sales-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Satış Kodu</th>
                    <th class="border-bottom-0">Satış Temsilcisi</th>
                    <th class="border-bottom-0">Firma</th>
                    <th class="border-bottom-0">Firma Yetkilisi</th>
                    <th class="border-bottom-0">Ürün Adedi</th>
                    <th class="border-bottom-0">Durum</th>
                    <th class="border-bottom-0">Talep Tarihi</th>
                    <th class="border-bottom-0">Son Güncelleme</th>
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
