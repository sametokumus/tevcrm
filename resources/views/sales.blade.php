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

            <table id="sales-datatable" class="table table-bordered key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Satış Kodu</th>
                    <th class="border-bottom-0">Satış Temsilcisi</th>
                    <th class="border-bottom-0">Müşteri</th>
                    <th class="border-bottom-0">Müşteri Yetkilisi</th>
                    <th class="border-bottom-0">Ürün Adedi</th>
                    <th class="border-bottom-0">Teklif Fiyatı</th>
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

<div class="modal modal-cover fade" id="updateStatusModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DURUM GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_status_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Durum :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_sale_id" required>
                            <select name="update_sale_status" id="update_sale_status" class="form-control form-control-md" required>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('include.footer')
