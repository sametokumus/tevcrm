@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/labs.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Laboratuvarlar</h6>

                <div class="row">
                    <!-- timesheet -->
                    <div class="col-12 col-md-12 position-relative">
                        <div class="card border-0 mb-4">
                            <div class="card-body p-4">
                                <table id="labs-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr class="text-muted">
                                        <th class="">Laboratuvar Adı</th>
                                        <th class="">Laboratuvar Kodu</th>
                                        <th class="">Son İşlem</th>
                                        <th class="w-50px"></th>
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

<div class="modal modal-cover fade" id="addLabModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="add_lab_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Laboratuvar Ekle</h6>
                        </div>
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Laboratuvar Adı" id="add_lab_name" required class="form-control border-start-0">
                                        <label>Laboratuvar Adı</label>
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
                                        <input type="text" placeholder="Laboratuvar Kodu" id="add_lab_lab_code" required class="form-control border-start-0">
                                        <label>Laboratuvar Kodu</label>
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
                                        <input type="number" placeholder="Son İşlem" id="add_lab_last_no" required class="form-control border-start-0">
                                        <label>Son İşlem</label>
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

<div class="modal modal-cover fade" id="updateLabModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="update_lab_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Laboratuvar Güncelle</h6>
                        </div>
                        <input type="hidden" placeholder="Laboratuvar Id" id="update_lab_id" required class="form-control border-start-0">
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Laboratuvar Adı" id="update_lab_name" required class="form-control border-start-0">
                                        <label>Laboratuvar Adı</label>
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
                                        <input type="text" placeholder="Laboratuvar Kodu" id="update_lab_lab_code" required class="form-control border-start-0">
                                        <label>Laboratuvar Kodu</label>
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
                                        <input type="number" placeholder="Son İşlem" id="update_lab_last_no" required class="form-control border-start-0">
                                        <label>Son İşlem</label>
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
