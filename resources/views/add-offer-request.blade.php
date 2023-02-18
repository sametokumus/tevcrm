@include('include.header')
<?php
$extra_js='
<script src="services/add-offer-requests.js"></script>
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
                        Talep Oluştur
                    </h1>
                </div>
            </div>

            <form method="post" action="#" id="add_offer_request_form">
                <div class="row">

                    <div class="col-md-8">
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Ref. Code</label>
                                        <input type="text" class="form-control" id="add_offer_request_product_refcode">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label">Ürün Adı</label>
                                        <input type="text" class="form-control" id="add_offer_request_product_name">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Adet</label>
                                        <input type="number" class="form-control" id="add_offer_request_product_quantity" value="0">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-theme w-100" id="add_offer_request_product_button">Ekle</button>
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
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <table id="offer-request-products" class="table table-bordered key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">Ref. Code</th>
                                        <th class="border-bottom-0">Ürün Adı</th>
                                        <th class="border-bottom-0">Adet</th>
                                        <th class="border-bottom-0"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="offer-request-products-body">

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <input type="hidden" class="form-control" id="add_offer_request_product_count" value="0">
                                <div class="row p-3">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Yetkili Satış Temsilcisi</label>
                                        <select class="form-control" id="add_offer_request_authorized_personnel">

                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Firma</label>
                                        <select class="form-control" id="add_offer_request_company" onchange="initEmployeeSelect();">

                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Firma Yetkilisi</label>
                                        <select class="form-control" id="add_offer_request_company_employee">

                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Kaydet</button>
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
        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->
@include('include.footer')
