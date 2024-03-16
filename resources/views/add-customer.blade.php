@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/add-customer.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Müşteri Ekle</h6>
                <form id="add_customer_form">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-buildings"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Firma Adı" id="add_company_name" required class="form-control border-start-0">
                                        <label>Firma Adı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-envelope"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Email address" id="add_company_email" required class="form-control border-start-0">
                                        <label>Eposta Adresi</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-globe"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Website" id="add_company_website" required class="form-control border-start-0">
                                        <label>Website</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-telephone"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Telefon" id="add_company_phone" required class="form-control border-start-0">
                                        <label>Telefon</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-telephone-inbound"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Faks" id="add_company_fax" required class="form-control border-start-0">
                                        <label>Faks</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-geo-alt"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Adres" id="add_company_address" required class="form-control border-start-0">
                                        <label>Adres</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="add_company_country" required>
                                            <option value="">Seçiniz...</option>
                                            <option>Türkiye</option>
                                        </select>
                                        <label for="country">Ülke</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-building"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Vergi Dairesi" id="add_company_tax_office" required class="form-control border-start-0">
                                        <label>Vergi Dairesi</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-upc"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Vergi No" id="add_company_tax_number" required class="form-control border-start-0">
                                        <label>Vergi No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <button type="submit" class="btn btn-theme">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>


@include('include.footer')
