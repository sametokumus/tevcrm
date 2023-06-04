@include('include.header')
<?php
$extra_js='
<script src="services/new-offer-request.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
</script>
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
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Talep Gelen Firma</label>
                                        <select class="form-control" id="add_offer_request_owner">

                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Yetkili Satış Temsilcisi</label>
                                        <select class="form-control" id="add_offer_request_authorized_personnel">

                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Satın Alma Sorumlusu</label>
                                        <select class="form-control" id="add_offer_request_purchasing_staff">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Müşteri</label>
                                        <select name="add_offer_request_company[]" class="form-control form-select add_offer_request_company_select" id="add_offer_request_company" onchange="initEmployeeSelect();" required>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Müşteri Yetkilisi</label>
                                        <select class="form-control" id="add_offer_request_company_employee">

                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Müşteri Talep Kodu</label>
                                        <input type="text" value="" class="form-control" id="add_offer_request_company_request_code" />
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Talep Tarihi</label>
                                        <input type="text" class="form-control datepicker" id="add_offer_request_date" placeholder="dd-mm-yyyy" />
                                    </div>
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
