@include('include.header')
<?php
$extra_js='
<script src="services/staff-targets.js"></script>
</script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid overflow-auto">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Hedef Ekle
                    </h1>
                </div>
            </div>

            <form method="post" action="#" id="add_staff_target_form">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Personel</label>
                                        <select class="form-control" id="add_target_admin_id">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Tür</label>
                                        <select class="form-control" id="add_target_type_id" onchange="addTargetChangeType();">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Hedef</label>
                                        <input type="text" value="" class="form-control" id="add_target_target" />
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Birim</label>
                                        <select class="form-control form-select" id="add_target_currency">
                                            <option value="%">%</option>
                                            <option value="TRY">TRY</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Hedef Ay</label>
                                        <select class="form-control" id="add_target_month">
                                            <option value="0">Tüm Yıl</option>
                                            <option value="1">Ocak</option>
                                            <option value="2">Şubat</option>
                                            <option value="3">Mart</option>
                                            <option value="4">Nisan</option>
                                            <option value="5">Mayıs</option>
                                            <option value="6">Haziran</option>
                                            <option value="7">Temmuz</option>
                                            <option value="8">Ağustos</option>
                                            <option value="9">Eylül</option>
                                            <option value="10">Ekim</option>
                                            <option value="11">Kasım</option>
                                            <option value="12">Aralık</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Hedef Yıl</label>
                                        <select class="form-control" id="add_target_year">
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Kaydet</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Satış Hedefleri
                    </h1>
                </div>
            </div>

            <table id="targets-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">N#</th>
                    <th class="border-bottom-0">Personel</th>
                    <th class="border-bottom-0">Tür</th>
                    <th class="border-bottom-0">Hedef</th>
                    <th class="border-bottom-0">Ay</th>
                    <th class="border-bottom-0">Yıl</th>
                    <th class="border-bottom-0">Durum</th>
                    <th class="border-bottom-0" data-priority="2">İşlem</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->
<div class="modal modal-cover fade" id="updateStaffTargetModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hedef Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_staff_target_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" value="" class="form-control" id="update_target_id" />
                            <label class="form-label">Tür</label>
                            <select class="form-control" id="update_target_type_id" onchange="updateTargetChangeType();">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hedef</label>
                            <input type="text" value="" class="form-control" id="update_target_target" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Birim</label>
                            <select class="form-control form-select" id="update_target_currency">
                                <option value="%">%</option>
                                <option value="TRY">TRY</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hedef Ay</label>
                            <select class="form-control" id="update_target_month">
                                <option value="0">Tüm Yıl</option>
                                <option value="1">Ocak</option>
                                <option value="2">Şubat</option>
                                <option value="3">Mart</option>
                                <option value="4">Nisan</option>
                                <option value="5">Mayıs</option>
                                <option value="6">Haziran</option>
                                <option value="7">Temmuz</option>
                                <option value="8">Ağustos</option>
                                <option value="9">Eylül</option>
                                <option value="10">Ekim</option>
                                <option value="11">Kasım</option>
                                <option value="12">Aralık</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hedef Yıl</label>
                            <select class="form-control" id="update_target_year">
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
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
