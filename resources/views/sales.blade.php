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

<div class="modal fade" id="updateStatusModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_status_form">
                <div class="modal-header">
                    <h5 class="modal-title">Durum Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
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
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>

@include('include.footer')
