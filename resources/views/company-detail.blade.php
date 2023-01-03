@include('include.header')
<?php
$extra_js='
<script src="services/company-detail.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
$(".timepicker").timepicker({
    minuteStep: 15,
    showMeridian: false
});
</script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row" id="company-detail-group">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-v2">
                        <li class="nav-item me-1"><a href="#company-detail" class="nav-link active" data-bs-toggle="tab">Firma Bilgileri</a></li>
                        <li class="nav-item me-1"><a href="#company-employees" class="nav-link" data-bs-toggle="tab">Yetkililer</a></li>
                        <li class="nav-item me-1"><a href="#company-notes" class="nav-link" data-bs-toggle="tab">Notlar</a></li>
                        <li class="nav-item me-1"><a href="#company-activities" class="nav-link" data-bs-toggle="tab">Aktiviteler</a></li>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="company-detail">

                            <form method="post" action="#" id="update_company_form">
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_potential_customer" />
                                            <label class="form-check-label" for="update_company_is_potential_customer">Potansiyel Müşteri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_customer" />
                                            <label class="form-check-label" for="update_company_is_customer">Müşteri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" value="" id="update_company_is_supplier" />
                                            <label class="form-check-label" for="update_company_is_supplier">Tedarikçi</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Firma Adı</label>
                                        <input type="text" class="form-control" id="update_company_name" placeholder="Firma Adı" required>
                                        <input type="hidden" class="form-control" id="update_company_id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Eposta</label>
                                        <input type="text" class="form-control" id="update_company_email" placeholder="Eposta" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" id="update_company_website" placeholder="Website" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Faks</label>
                                        <input type="text" class="form-control" id="update_company_fax" placeholder="Faks" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Adres</label>
                                        <input type="text" class="form-control" id="update_company_address" placeholder="Adres" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vergi Dairesi</label>
                                        <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vergi Numarası</label>
                                        <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Logo <a href="#" id="update_company_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                                        <input type="file" class="form-control" id="update_company_logo" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-outline-theme float-end">Kaydet</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="tab-pane fade" id="company-employees">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="btn-group float-end">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCompanyEmployeeModal">Yetkili Ekle</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="employees-grid">

                            </div>

                        </div>
                        <div class="tab-pane fade" id="company-notes">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="btn-group float-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="openAddCompanyNoteModal();">Not Ekle</button>
                                    </div>
                                </div>
                            </div>
                            <div id="note-list">

                            </div>

                        </div>
                        <div class="tab-pane fade" id="company-activities">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="btn-group float-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="openAddCompanyActivityModal();">Aktivite Ekle</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <table id="datatableActivities" class="table text-nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tür</th>
                                            <th scope="col">Başlık</th>
                                            <th scope="col">Firma Yetkilisi</th>
                                            <th scope="col">Başlangıç</th>
                                            <th scope="col">Bitiş</th>
                                            <th scope="col">Alt Görev</th>
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

<div class="modal modal-cover fade" id="addCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_company_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_potential_customer" />
                                <label class="form-check-label" for="add_company_is_potential_customer">Potansiyel Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_customer" />
                                <label class="form-check-label" for="add_company_is_customer">Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_supplier" />
                                <label class="form-check-label" for="add_company_is_supplier">Tedarikçi</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_company_name" placeholder="Firma Adı" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_email" placeholder="Eposta" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_website" placeholder="Website" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_phone" placeholder="Telefon" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_fax" placeholder="Faks" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_company_address" placeholder="Adres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_tax_office" placeholder="Vergi Dairesi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_tax_number" placeholder="Vergi Numarası" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" id="add_company_logo" />
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

<div class="modal modal-cover fade" id="updateCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_company_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_potential_customer" />
                                <label class="form-check-label" for="update_company_is_potential_customer">Potansiyel Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_customer" />
                                <label class="form-check-label" for="update_company_is_customer">Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_supplier" />
                                <label class="form-check-label" for="update_company_is_supplier">Tedarikçi</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_company_name" placeholder="Firma Adı" required>
                            <input type="hidden" class="form-control" id="update_company_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_email" placeholder="Eposta" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_website" placeholder="Website" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_fax" placeholder="Faks" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_company_address" placeholder="Adres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Logo <a href="#" id="update_company_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_company_logo" />
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

<div class="modal modal-cover fade" id="addCompanyEmployeeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">YETKİLİ EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_employee_name" placeholder="Adı" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_title" placeholder="Ünvanı" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_email" placeholder="Eposta" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_phone" placeholder="Telefon" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_mobile" placeholder="Cep Telefonu" >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fotoğraf</label>
                            <input type="file" class="form-control" id="add_employee_photo" />
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
<div class="modal modal-cover fade" id="updateCompanyEmployeeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">YETKİLİ GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_employee_name" placeholder="Adı" required>
                            <input type="hidden" class="form-control" id="update_employee_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_title" placeholder="Ünvanı" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_email" placeholder="Eposta" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_phone" placeholder="Telefon" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_mobile" placeholder="Cep Telefonu" >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fotoğraf <a href="#" id="update_employee_current_photo" target="_blank">'ı görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_employee_photo" />
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

<div class="modal modal-cover fade" id="addCompanyNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea class="form-control" rows="3" id="add_note_description" placeholder="Not" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_note_employee">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görsel</label>
                            <input type="file" class="form-control" id="add_note_image" />
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
<div class="modal modal-cover fade" id="updateCompanyNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea class="form-control" rows="3" id="update_note_description" placeholder="Not" required></textarea>
                            <input type="hidden" class="form-control" id="update_note_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_note_employee">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görsel <a href="#" id="update_note_current_image" target="_blank">'i görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_note_image" />
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

<div class="modal modal-cover fade" id="addCompanyActivityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="add_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Yapılacak Aktivite</label>
                            <input type="text" class="form-control" id="add_activity_title">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="add_activity_description"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="add_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="add_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_end_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görevler <button type="button" class="btn btn-default btn-sm mx-3" id="add-activity-new-task-btn">Görev Ekle +</button></label>
                        </div>
                        <div id="add-activity-tasks-body" class="d-none mb-3">

                        </div>
                        <div id="add-activity-new-tasks-body" class="mb-3">
                            <input type="hidden" id="add-activity-new-task-count" value="0">
                        </div>
                        <div class="row mb-3 d-none" id="add-activity-new-tasks-input">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control input-sm" id="add-activity-task" placeholder="Yeni Görev" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-default btn-sm" id="add-activity-task-button">Ekle</button>
                            </div>
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
<div class="modal modal-cover fade" id="updateCompanyActivityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="update_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Yapılacak Aktivite</label>
                            <input type="text" class="form-control" id="update_activity_title">
                            <input type="hidden" class="form-control" id="update_activity_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="update_activity_description"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="update_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="update_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_end_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görevler <button type="button" class="btn btn-default btn-sm mx-3" id="update-activity-new-task-btn">Görev Ekle +</button></label>
                        </div>
                        <div id="update-activity-tasks-body" class="mb-3">
                            <input type="hidden" id="update-activity-task-count" value="0">

                        </div>
                        <div id="update-activity-new-tasks-body" class="d-none mb-3">
                        </div>
                        <div class="row mb-3 d-none" id="update-activity-new-tasks-input">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control input-sm" id="update-activity-task" placeholder="Yeni Görev" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-default btn-sm" id="update-activity-task-button">Ekle</button>
                            </div>
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
