@include('include.header')
<?php
$extra_js='
<script src="services/potential-customers.js"></script>
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
                        Potansiyel Müşteriler
                    </h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">Firma Ekle</button>
                    </div>
                </div>
            </div>

            <div class="row" id="company-grid">
            </div>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_company_form">
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="add_company_is_potential_customer" />
                            <label class="form-check-label" for="add_company_is_potential_customer">Potansiyel Müşteri</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="add_company_is_customer" />
                            <label class="form-check-label" for="add_company_is_customer">Müşteri</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="add_company_is_supplier" />
                            <label class="form-check-label" for="add_company_is_supplier">Tedarikçi</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="add_company_name" placeholder="Firma Adı" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_email" placeholder="Eposta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_website" placeholder="Website">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_phone" placeholder="Telefon" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_fax" placeholder="Faks">
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="add_company_address" placeholder="Adres">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_tax_office" placeholder="Vergi Dairesi">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="add_company_tax_number" placeholder="Vergi Numarası">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" class="form-control" id="add_company_logo" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-outline-theme">Kaydet</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="updateCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_company_form">
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_potential_customer" />
                            <label class="form-check-label" for="update_company_is_potential_customer">Potansiyel Müşteri</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_customer" />
                            <label class="form-check-label" for="update_company_is_customer">Müşteri</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_supplier" />
                            <label class="form-check-label" for="update_company_is_supplier">Tedarikçi</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="update_company_name" placeholder="Firma Adı" required>
                        <input type="hidden" class="form-control" id="update_company_id" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_email" placeholder="Eposta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_website" placeholder="Website" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_fax" placeholder="Faks" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="update_company_address" placeholder="Adres" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Logo <a href="#" id="update_company_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                        <input type="file" class="form-control" id="update_company_logo" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-outline-theme">Kaydet</button>
            </div>
            </form>
        </div>
    </div>
</div>



@include('include.footer')
