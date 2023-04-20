@include('include.header')
<?php
$extra_js='
<script src="services/offer-request-products.js"></script>
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
                        Talep Güncelle
                    </h1>
                </div>
            </div>


                <div class="row">

                    <div class="col-md-12">
                        <form method="post" action="#" id="update_offer_request_form">
                            <div class="card border-theme mb-3">
                                <div class="card-body">
                                        <input type="hidden" class="form-control" id="update_offer_request_product_count" value="0">
                                        <input type="hidden" class="form-control" id="update_offer_request_id">
                                        <div class="row p-3">
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Talep Gelen Firma</label>
                                                <select class="form-control" id="update_offer_request_owner">

                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Yetkili Satış Temsilcisi</label>
                                                <select class="form-control" id="update_offer_request_authorized_personnel">

                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Satın Alma Sorumlusu</label>
                                                <select class="form-control" id="update_offer_request_purchasing_staff">

                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Müşteri</label>
                                                <select class="form-control" id="update_offer_request_company" onchange="initEmployeeSelect();">

                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Müşteri Yetkilisi</label>
                                                <select class="form-control" id="update_offer_request_company_employee">

                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Müşteri Talep Kodu</label>
                                                <input type="text" value="" class="form-control" id="update_offer_request_company_request_code" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <button type="submit" class="btn btn-theme w-100">Talebi Güncelle</button>
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
                        </form>
                    </div>

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <table id="offer-request-products" class="table table-bordered key-buttons border-bottom w-100">
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th class="border-bottom-0">N#</th>--}}
{{--                                        <th class="border-bottom-0">ID</th>--}}
{{--                                        <th class="border-bottom-0">Firma Stok Kodu</th>--}}
{{--                                        <th class="border-bottom-0">Müşteri Stok Kodu</th>--}}
{{--                                        <th class="border-bottom-0">Ref. Code</th>--}}
{{--                                        <th class="border-bottom-0">Ürün Adı</th>--}}
{{--                                        <th class="border-bottom-0">Miktar</th>--}}
{{--                                        <th class="border-bottom-0">Birim</th>--}}
{{--                                        <th class="border-bottom-0">Marka</th>--}}
{{--                                        <th class="border-bottom-0">Ürün Grubu</th>--}}
{{--                                        <th class="border-bottom-0">Satın Alma Notu</th>--}}
{{--                                        <th class="border-bottom-0"></th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
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


                </div>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->
@include('include.footer')
