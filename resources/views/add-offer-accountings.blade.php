@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/add-offer-accountings.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12 col-md-9">
                <h6 class="title">Teklif Bilgileri</h6>
                <form id="offer_summary_form">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Test Toplamı" id="offer_test_total" class="form-control border-start-0" disabled>
                                        <label>Test Toplamı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Default radio
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Default checked radio
                                    </label>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating bg-white">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Default radio
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Default checked radio
                                            </label>
                                        </div>
                                        <label>İndirim</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="İndirim" id="offer_discount" class="form-control border-start-0">
                                        <label>İndirim</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="KDV Oranı" id="offer_vat_rate" class="form-control border-start-0">
                                        <label>KDV Oranı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <button type="submit" id="save_offer_btn" class="btn btn-theme w-100 h-60">Teklif Oluştur</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-3">
                <div class="card border-0 mb-4 theme-blue bg-gradient-theme-light">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-40 rounded bg-light-theme">
                                    <i class="bi bi-cash h5"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="fw-medium mb-0">Teklif Özeti</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6 class="text-body">Toplam Tutar: <span id="summary_test_total"></span> <small>₺</small></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6 class="text-body">İskonto: <span id="summary_discount"></span> <small>₺</small></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6 class="text-body">Ara Toplam: <span id="summary_sub_total"></span> <small>₺</small></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6 class="text-body">KDV: <span id="summary_vat"></span> <small>₺</small></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0"><span id="summary_grand_total"></span> <small class="h6">₺</small></h5>
                                <p class="small text-secondary">Genel Toplam (KDV Dahil)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>


@include('include.footer')
