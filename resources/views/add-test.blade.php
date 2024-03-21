@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/add-test.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Test Ekle</h6>
                <form id="add_test_form">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-buildings"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Test Adı" id="add_test_name" required class="form-control border-start-0">
                                        <label>Test Adı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="add_test_category" required>
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Kategori</label>
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
                                        <input type="text" placeholder="Numune Adedi" id="add_test_sample_count" required class="form-control border-start-0">
                                        <label>Numune Adedi</label>
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
                                        <input type="text" placeholder="Website" id="add_test_sample_description" class="form-control border-start-0">
                                        <label>Numune Açıklaması</label>
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
                                        <input type="text" placeholder="Test Süresi" id="add_test_total_day" required class="form-control border-start-0">
                                        <label>Test Süresi</label>
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
                                        <input type="text" placeholder="Test Bedeli" id="add_test_price" class="form-control border-start-0">
                                        <label>Test Bedeli</label>
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
