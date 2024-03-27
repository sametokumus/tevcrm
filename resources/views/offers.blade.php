@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/offers.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Teklifler</h6>

                <div class="row">
                    <div class="col-12 col-md-12 position-relative">
                        <div class="card border-0 mb-4">
                            <div class="card-body p-4">
                                <table id="offer-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="bg-green bg-opacity-50">Teklif Kodu</th>
                                        <th class="bg-green bg-opacity-50">Müşteri</th>
                                        <th>Müşteri Yetkilisi</th>
                                        <th>Proje Sorumlusu</th>
                                        <th>Teklif Fiyatı</th>
                                        <th>Teklif Tarihi</th>
                                        <th>Durum</th>
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

<div class="modal modal-cover fade" id="deleteOfferModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" action="#" id="delete_offer_form">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Teklif Silme</h6>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="hidden" class="form-control" id="delete_offer_id" required>
                            <label class="form-label">Teklifi silmek istediğinize emin misiniz?</label>
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
                </form>
            </div>
        </div>
    </div>
</div>


@include('include.footer')
