@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/offer-detail.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                        <div class="card border-0 bg-radial-gradiant text-white h-100">
                            <div class="card-body bg-none">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-buildings h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <h5 class="fw-medium mb-0" id="customer-name"></h5>
                                        <p id="global-id"></p>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-person h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Müşteri Yetkilisi</p>
                                        <h5 class="fw-medium mb-0" id="employee"></h5>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-0">
                                    <div class="col-auto">
                                        <i class="bi bi-person h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Proje Sorumlusu</p>
                                        <h5 class="fw-medium mb-0" id="manager"></h5>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-0">
                                    <div class="col">
                                        <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="generatePDF()">
                                            <span class="bi bi-node-plus-fill"> </span>FR38
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                        <div class="card border-0 bg-radial-gradiant text-white h-100">
                            <div class="card-body bg-none">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-cash h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Teklif Tutarı</p>
                                        <h6 class="fw-medium mb-0" id="offer-price"></h6>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-node-plus h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Test Adedi</p>
                                        <h6 class="fw-medium mb-0" id="offer-test-count"></h6>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-0">
                                    <div class="col-auto">
                                        <i class="bi bi-calendar-event h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Teklif Tarihi</p>
                                        <h6 class="fw-medium mb-0" id="offer-date"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
                        <div class="card border-0 theme-primary">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class=" col-auto">
                                        <i class="bi bi-node-plus h5 avatar avatar-40 bg-light-theme rounded"></i>
                                    </div>
                                    <div class="col">
                                        <h6 class="fw-medium mb-0">Test Listesi</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush bg-none">
                                    <li class="list-group-item">
                                        <div class="row text-secondary">
                                            <div class="col-auto">1</div>
                                            <div class="col">Test Adı</div>
                                            <div class="col">
                                                <span class="badge badge-sm bg-warning p-2"><i class="fa fa-circle fs-9px fa-fw me-5px"></i> Beklemede</span>
                                            </div>
                                            <div class="col-auto">
                                                <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="sendLab('1')">
                                                    <span class="bi bi-node-plus-fill"> </span> Laboratuvara Gönder
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">

                <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
                    <div class="card border-0 theme-green">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class=" col-auto">
                                    <i class="bi bi-list-task h5 avatar avatar-40 bg-light-theme rounded"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">Teklif Durum Geçmişi</h6>
                                </div>
                                <div class="col-auto" id="update-status-col">

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="status-history-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                                <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>İşlem Tarihi</th>
                                    <th class="text-right">Önceki Durum</th>
                                    <th><i class="bi bi-arrow-90deg-right"></i> Güncel Durum</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
                    <div class="card border-0 theme-purple">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class=" col-auto">
                                    <i class="bi bi-list-task h5 avatar avatar-40 bg-light-theme rounded"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">Teklif Dökümanları</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="document-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">Döküman Türü</th>
                                    <th class="border-bottom-0"></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</main>

<div class="modal modal-cover fade" id="updateStatusModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="update_status_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Durum Güncelle</h6>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <input type="hidden" class="form-control" id="update_offer_id" required>
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="update_offer_status" required>
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Durum</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-link text-danger" type="button" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-trash h4 me-2"></i> Vazgeç</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-theme" type="submit"><i class="bi bi-send me-2"></i> Kaydet</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="addDocumentModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="add_document_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                        </div>
                        <h6 class="title">Döküman Ekle</h6>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="add_document_type_id" required>
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Döküman Türü</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <div class="form-floating">
                                        <input type="file" class="form-control" id="add_document_file" />
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-link text-danger" type="button" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-trash h4 me-2"></i> Vazgeç</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-theme" type="submit"><i class="bi bi-send me-2"></i> Kaydet</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@include('include.footer')
