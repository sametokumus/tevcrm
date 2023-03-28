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

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <input type="hidden" class="form-control" id="add_offer_request_product_count" value="0">
                                <div class="row p-3">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Talep Gelen Firma</label>
                                        <select class="form-control" id="add_offer_request_owner">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Yetkili Satış Temsilcisi</label>
                                        <select class="form-control" id="add_offer_request_authorized_personnel">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Satın Alma Sorumlusu</label>
                                        <select class="form-control" id="add_offer_request_purchasing_staff">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Müşteri</label>
                                        <select class="form-control" id="add_offer_request_company" onchange="initEmployeeSelect();">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Müşteri Yetkilisi</label>
                                        <select class="form-control" id="add_offer_request_company_employee">

                                        </select>
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

                    <div class="col-md-12" style="position: relative; z-index: 9999;">
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Firma Stok Kodu</label>
                                        <input type="text" value="" class="form-control" id="add_offer_request_owner_stock_code" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Müşteri Stok Kodu</label>
                                        <input type="text" value="" class="form-control" id="add_offer_request_customer_stock_code" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Üretici Parça Numarası (Ref. Code)</label>
                                        <input type="text" class="form-control" id="add_offer_request_product_refcode">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ürün Adı</label>
                                        <input type="text" class="form-control" id="add_offer_request_product_name">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Miktar</label>
                                        <input type="number" class="form-control" id="add_offer_request_product_quantity" value="1" min="1">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Birim</label>
                                        <select class="form-control" id="add_offer_request_product_measurement">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Marka</label>
                                        <input type="text" value="" class="form-control" id="add_offer_request_brand" />
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Ürün Grubu (1. Seviye)</label>
                                        <select class="form-control" id="add_offer_request_product_category_1" onchange="initSecondCategory();">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Ürün Grubu (2. Seviye)</label>
                                        <select class="form-control" id="add_offer_request_product_category_2" onchange="initThirdCategory();">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Ürün Grubu (3. Seviye)</label>
                                        <select class="form-control" id="add_offer_request_product_category_3">

                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Satın Alma Notu</label>
                                        <input type="text" class="form-control" id="add_offer_request_note">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-theme w-100" id="add_offer_request_product_button">Ürünü Talep Listesine Ekle</button>
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

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <table id="offer-request-products" class="table table-bordered key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">N#</th>
                                        <th class="border-bottom-0">Firma Stok Kodu</th>
                                        <th class="border-bottom-0">Müşteri Stok Kodu</th>
                                        <th class="border-bottom-0">Ref. Code</th>
                                        <th class="border-bottom-0">Ürün Adı</th>
                                        <th class="border-bottom-0">Miktar</th>
                                        <th class="border-bottom-0">Birim</th>
                                        <th class="border-bottom-0">Marka</th>
                                        <th class="border-bottom-0">Ürün Grubu</th>
                                        <th class="border-bottom-0">Not</th>
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

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Talep Oluştur ve Devam Et</button>
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
