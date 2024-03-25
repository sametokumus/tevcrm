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
            <div class="col-12">
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
        </div>
    </div>

</main>


@include('include.footer')
