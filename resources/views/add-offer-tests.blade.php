@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/add-offer-tests.js"></script>
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
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-globe"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Website" id="offer_description" class="form-control border-start-0">
                                        <label>Teklif Açıklaması</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Test Ekle</h6>
                <form id="add_test_form">
                    <div class="row">
                        <div class="col-12 col-md-5 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_category">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Kategori</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-5 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0 select2-show-search" id="offer_test">
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Test</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-2 mb-2">
                            <button type="button" id="offer_test_btn" class="btn btn-theme w-100 h-60">Teklife Ekle</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Testler</h6>
                <div class="card border-0 mb-4">
                    <div class="card-body p-4">
                        <table id="tests-table" class="table table-bordered nowrap key-buttons border-bottom">
                            <thead>
                            <tr>
                                <th>Test Yapılacak Ürün</th>
                                <th>Test Adı</th>
                                <th>Numune Sayısı</th>
                                <th>Numune Açıklama</th>
                                <th>Test Süresi</th>
                                <th>Test Bedeli</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
            <div class="col-12 col-md-2 mb-2">
                <button type="button" id="save_offer_btn" class="btn btn-theme w-100 h-60">Teklifi Kaydet</button>
            </div>
        </div>
    </div>

</main>


@include('include.footer')
