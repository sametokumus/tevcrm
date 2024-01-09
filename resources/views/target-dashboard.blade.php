@include('include.header')
<?php
$extra_js='
<script src="services/target-dashboard.js"></script>
<script src="/plugins/apexcharts/dist/apexcharts.min.js"/></script>
';
?>

<div id="content" class="app-content">


    <div class="row">
        <div class="col-md-3">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-1">
                        <span class="flex-grow-1">YILLIK SATIŞLARIN DAĞILIMI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="d-block fw-bold small mb-3" id="yearly-result">

                    </div>


                    <div class="mb-3">
                        <div id="chart-yearly-result">

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
        <div class="col-md-3">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-1">
                        <span class="flex-grow-1">BU AY SATIŞLARIN DAĞILIMI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>

                    <div class="d-block fw-bold small mb-3" id="monthly-result">

                    </div>


                    <div class="mb-3">
                        <div id="chart-monthly-result">

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
        <div class="col-md-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">AYLIK HEDEF BAŞARI GRAFİĞİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-yearly-targets">

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

    <div id="staffs">



    </div>



</div>

<div class="modal modal-cover fade" id="addStaffTargetModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hedef Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_staff_target_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" value="" class="form-control" id="add_target_staff_id" />
                            <label class="form-label">Tür</label>
                            <select class="form-control" id="add_target_type_id" onchange="addTargetChangeType();">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hedef</label>
                            <input type="text" value="" class="form-control" id="add_target_target" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Birim</label>
                            <select class="form-control form-select" id="add_target_currency">
                                <option value="%">%</option>
                                <option value="TRY">TRY</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hedef Yıl</label>
                            <select class="form-control" id="add_target_year">
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
                        <div class="col-md-12 mb-3">
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

<div class="modal modal-cover fade" id="addStaffPointModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Personel Puanları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form method="post" action="#" id="add_staff_point_form">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" class="form-control" id="add_point_staff_id" required>
                            <label class="form-label">Yeni Puan Ekle</label>
                            <input type="number" class="form-control" id="add_point_point" min="1" max="10" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                        </div>
                    </div>
                </form>

                <div class="row mb-3">
                    <div class="table-responsive">
                        <table id="staff-points-table" class="table table-striped table-borderless mb-2px small">
                            <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Yönetici</th>
                                <th>Puan</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@include('include.footer')
