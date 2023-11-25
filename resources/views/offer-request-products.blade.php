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
                <div class="col-md-6">
                    <h1 class="page-header">
                        Talep Oluştur (<span id="title_sale_global_id"> </span>)
                    </h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end">
                        <a href="/sales" id="go-supplier-price" class="btn btn-pink">Talebi Satın Almaya Gönder</a>
{{--                        <button type="button" class="btn btn-outline-theme" onclick="removeFilter();">Filtreyi Kaldır</button>--}}
                    </div>
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
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Talep Gelen Firma</label>
                                                <select class="form-control" id="update_offer_request_owner">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Yetkili Satış Temsilcisi</label>
                                                <select class="form-control" id="update_offer_request_authorized_personnel">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Satın Alma Sorumlusu</label>
                                                <select class="form-control" id="update_offer_request_purchasing_staff">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Satış Türü</label>
                                                <select class="form-control" id="update_offer_request_sale_type">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Müşteri</label>
                                                <select class="form-control" id="update_offer_request_company" onchange="initEmployeeSelect();">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Müşteri Yetkilisi</label>
                                                <select class="form-control" id="update_offer_request_company_employee">

                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Müşteri Talep Kodu</label>
                                                <input type="text" value="" class="form-control" id="update_offer_request_company_request_code" />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Talep Tarihi</label>
                                                <input type="text" class="form-control datepicker" id="update_offer_request_date" placeholder="dd-mm-yyyy" readonly />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <button type="submit" class="btn btn-theme w-100">Talep Bilgilerini Güncelle</button>
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
                                <h6><a href="img/sample-import.xlsx" class="fw-400 text-white-50">Örnek Import dökümanını buradan indirebilirsiniz</a></h6>
                                <form action="#" method="post" id="import_data_form" enctype="multipart/form-data" style="display: none;">
                                    <input type="file" name="import_file" id="import_file" onchange="$('#import_submit_btn').click();">
                                    <button id="import_submit_btn" type="submit">import</button>
                                </form>
                                <table id="offer-request-products" class="table table-bordered key-buttons border-bottom w-100">

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
