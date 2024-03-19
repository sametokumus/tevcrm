@include('include.header')
<?php
$extra_js='
<script src="services/customer-dashboard.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
$(".timepicker").timepicker({
    minuteStep: 15,
    showMeridian: false,
    template: false
});
</script>
';
?>

<main class="main mainheight px-4">

    <div class="w-100 pt-5 position-relative bg-theme  z-index-0">
        <div class="coverimg w-100 h-100 position-absolute top-0 start-0 opacity-3 z-index-0">
            <img src="img/bg-14.jpg" class="" alt=""/>
        </div>
        <div class="container my-3 my-md-5 z-index-1 position-relative">
            <div class="row mb-2 mb-lg-3 align-items-start">
                <div class="col py-2">
                    <h2 class="mb-3" id="info-name"></h2>
                </div>
            </div>
            <div class="row text-white gx-md-4 gx-lg-5">
                <div class="col-6 col-md-auto py-2">
                    <p class="text-muted small">Eposta</p>
                    <p id="info-email"></p>
                </div>
                <div class="col-6 col-md-auto py-2">
                    <p class="text-muted small">Telefon</p>
                    <p id="info-phone"></p>
                </div>
                <div class="col-12 col-md-auto py-2">
                    <p class="text-muted small">Faks</p>
                    <p id="info-fax"></p>
                </div>
                <div class="col-auto py-2">
                    <p class="text-muted small">Website</p>
                    <p id="info-web"></p>
                </div>
            </div>
        </div>
        <br>
    </div>

    <div class="container top-30 z-index-1 position-relative mb-4">
        <div class="card bg-white border-0 z-index-1">
            <div class="card-body">
                <ul class="nav nav-tabs nav-WinDOORS border-0" id="companynavigation" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overviewcompany-tab" data-bs-toggle="tab"
                                data-bs-target="#overviewcompany" type="button" role="tab"
                                aria-controls="overviewcompany" aria-selected="true">Bilgiler
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="authorizescompany-tab" data-bs-toggle="tab"
                                data-bs-target="#authorizescompany" type="button" role="tab"
                                aria-controls="authorizescompany" aria-selected="false">Yetkililer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teamcompany-tab" data-bs-toggle="tab" data-bs-target="#teamcompany"
                                type="button" role="tab" aria-controls="teamcompany" aria-selected="false">Geçmiş
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card border-0 mb-4">
            <div class="card-body bg-none py-3 py-lg-4">
                <div class="tab-content" id="companynavigationContent">
                    <div class="tab-pane fade show active" id="overviewcompany" role="tabpanel"
                         aria-labelledby="overviewcompany-tab">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10">
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
                                            <input type="text" class="form-control" id="update_company_website" placeholder="Website">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Telefon</label>
                                            <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Faks</label>
                                            <input type="text" class="form-control" id="update_company_fax" placeholder="Faks">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Adres</label>
                                            <input type="text" class="form-control" id="update_company_address" placeholder="Adres">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ülke</label>
                                            <select class="form-control" id="update_company_country">

                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Registration Number</label>
                                            <input type="text" class="form-control" id="update_company_registration_number" placeholder="Registration Number">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Vergi Dairesi</label>
                                            <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Vergi Numarası</label>
                                            <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">LinkedIn</label>
                                            <input type="text" class="form-control" id="update_company_linkedin" placeholder="LinkedIn">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Skype</label>
                                            <input type="text" class="form-control" id="update_company_skype" placeholder="Skype">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Term</label>
                                            <select class="form-control" id="update_company_payment_term">

                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Diğer</label>
                                            <input type="text" class="form-control" id="update_company_online" placeholder="Diğer">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Logo</label>
                                            <input type="file" class="form-control" id="update_company_logo" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <button type="submit" class="btn btn-outline-theme float-end">Kaydet</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="authorizescompany" role="tabpanel"
                         aria-labelledby="authorizescompany-tab">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="img/tour-guide-5.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Creative UI Design</h4>
                                                <p class="text-secondary">We create unique and creative user interfaces
                                                    for website and mobile applications.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="img/tour-guide-1.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Awesome UX flow</h4>
                                                <p class="text-secondary">We keep everything on intuitive and simplest
                                                    manner to make sure everything covered and easy to access.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-center mb-4 mt-3 mb-lg-5">You are at awesome space.<br>Just <span
                                        class="text-gradient">create a difference</span>.</h4>
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="img/tour-guide-2.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Customization</h4>
                                                <p class="text-secondary">To improve day by day customization for new
                                                    requirements. We follow best practices to make development flawless
                                                    and elastic.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="img/tour-guide-4.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Multi-device Centric</h4>
                                                <p class="text-secondary">Human in race of different medium to
                                                    communicate with each other and we also keep eye on different screen
                                                    sizes and responsiveness</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="add_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma</label>
                            <select class="form-control" id="add_activity_company_id" onchange="initActivityAddModalEmployee();">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Sorumlusu</label>
                            <select class="form-control" id="add_activity_user_id">

                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Katılımcılar</label>
                            <select name="add_activity_participants[]" class="form-control form-select select2" multiple="multiple" id="add_activity_participants">
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Konu</label>
                            <input type="text" class="form-control" id="add_activity_title">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="add_activity_description"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="add_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="add_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="update_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma</label>
                            <select class="form-control" id="update_activity_company_id" onchange="initActivityUpdateModalEmployee();">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Sorumlusu</label>
                            <select class="form-control" id="update_activity_user_id">

                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Katılımcılar</label>
                            <select name="update_activity_participants[]" class="form-control form-select select2" multiple="multiple" id="update_activity_participants">
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Konu</label>
                            <input type="text" class="form-control" id="update_activity_title">
                            <input type="hidden" class="form-control" id="update_activity_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="update_activity_description"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="update_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="update_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
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

<div class="modal modal-cover fade" id="addDeliveryAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEVK ADRESİ EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_delivery_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres Başlığı</label>
                            <input type="text" class="form-control" id="add_delivery_address_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" id="add_delivery_address">
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
<div class="modal modal-cover fade" id="updateDeliveryAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEVK ADRESİ GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_delivery_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres Başlığı</label>
                            <input type="text" class="form-control" id="update_delivery_address_name">
                            <input type="hidden" class="form-control" id="update_delivery_address_id">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" id="update_delivery_address">
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

<div class="modal modal-cover fade" id="addCompanyPointModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Müşteri Puanları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form method="post" action="#" id="add_company_point_form">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" class="form-control" id="add_point_company_id" required>
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
                        <table id="company-points-table" class="table table-striped table-borderless mb-2px small">
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
