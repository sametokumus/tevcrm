@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/offer-calculator.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12 col-lg-12 col-xl-12 col-xxl-12 mb-4 column-set">
                <div class="card border-0">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-calculator h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                            </div>
                            <div class="col ps-0">
                                <h4 class="fw-medium mb-0">Teklif Tutarı</h4>
                            </div>
                            <div class="col-auto">
                                <h5 id="view-offer-price">0,00 ₺</h5>
                                <input type="hidden" id="offer_price" value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Test Ekle</h6>
                <form id="add_test_form">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="offer_category">
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
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Kategori Adı" id="add_category_name" required class="form-control border-start-0">
                                        <label>Kategori Adı</label>
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
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Testler</h6>
                <div id="test-block">
                    <div id="test-item">

                    </div>
                </div>
            </div>
        </div>
    </div>

</main>


@include('include.footer')
