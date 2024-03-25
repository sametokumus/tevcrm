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
                <form id="offer_info_form">
                    <div class="row">
                        <div class="col-12 col-md-3 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_customer">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Müşteri</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-3 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_employee">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Müşteri Yetkilisi</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-3 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_manager">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Proje Yöneticisi</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-3 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_lab_manager">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Laboratuvar Sorumlusu</label>
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
                                        <input type="text" placeholder="Teklif Açıklaması" id="offer_description" class="form-control border-start-0">
                                        <label>Teklif Açıklaması</label>
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
                                    <i class="bi bi-send h5"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h6 class="fw-medium mb-0">Send Money</h6>
                                <p class="small text-secondary">Across the platform</p>
                            </div>
                            <div class="col-auto">
                                <div class="dropdown d-inline-block">
                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bi bi-columns"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="dropdown-item text-center">
                                            <div class="row gx-3 mb-3">
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-8" data-title="8"><span></span></div>
                                                        <div class="col-4" data-title="4"><span></span></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-9" data-title="9"><span></span></div>
                                                        <div class="col-3" data-title="3"><span></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-6" data-title="6"><span></span></div>
                                                        <div class="col-6" data-title="6"><span></span></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-12" data-title="12"><span></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <p class="text-secondary small">Toplam Tutar: <span id="test_total">21321</span> <small>₺</small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <p class="text-secondary small">İskonto: <span id="test_total">21321</span> <small>₺</small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <p class="text-secondary small">Ara Toplam: <span id="test_total">21321</span> <small>₺</small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <p class="text-secondary small">KDV: <span id="test_total">21321</span> <small>₺</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0"><span id="total_price">12322</span> <small class="h6">₺</small></h5>
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
