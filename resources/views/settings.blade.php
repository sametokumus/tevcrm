@include('include.header')
<?php
$extra_js='
<script src="services/settings.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">Not Ekle</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">Aktivite Ekle</button>
                    </div>
                </div>
            </div>

            <div class="row" id="settings-group">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-v2">
                        <li class="nav-item me-1"><a href="#activity-types" class="nav-link active" data-bs-toggle="tab">Aktivite Türleri</a></li>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="activity-types">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="btn-group float-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="openAddActivityTypeModal();">Tür Ekle</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <table id="datatableActivityTypes" class="table text-nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tür Adı</th>
                                            <th scope="col">İşlemler</th>
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
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addActivityTypeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE TÜRÜ EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_activity_type_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İsim</label>
                            <input type="text" class="form-control" id="add_activity_type_name" placeholder="Adı" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-cover fade" id="updateActivityTypeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE TÜRÜ GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_activity_type_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İsim</label>
                            <input type="text" class="form-control" id="update_activity_type_name" placeholder="Adı" required>
                            <input type="hidden" class="form-control" id="update_activity_type_id" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('include.footer')
