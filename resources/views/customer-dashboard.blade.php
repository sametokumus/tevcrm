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
            <img src="img/login/3.jpg" class="" alt=""/>
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
                    <li class="nav-item ms-auto" role="presentation">
                        <button class="btn btn-theme theme-green" id="add-employee-btn" onclick="openAddEmployeeModal(event);" ><i class="bi bi-person-plus vm me-2"></i> Yetkili Ekle
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
                                <form id="update_customer_form">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-buildings"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Firma Adı" id="update_company_name" required class="form-control border-start-0">
                                                        <label>Firma Adı</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-envelope"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Email address" id="update_company_email" required class="form-control border-start-0">
                                                        <label>Eposta Adresi</label>
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
                                                        <input type="text" placeholder="Website" id="update_company_website" class="form-control border-start-0">
                                                        <label>Website</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-telephone"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Telefon" id="update_company_phone" required class="form-control border-start-0">
                                                        <label>Telefon</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-6 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-telephone-inbound"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Faks" id="update_company_fax" class="form-control border-start-0">
                                                        <label>Faks</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-12 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-geo-alt"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Adres" id="update_company_address" class="form-control border-start-0">
                                                        <label>Adres</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-flag"></i></span>
                                                    <div class="form-floating">
                                                        <select class="form-select border-0" id="update_company_country" required>
                                                            <option value="">Seçiniz...</option>
                                                            <option>Türkiye</option>
                                                        </select>
                                                        <label for="country">Ülke</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-building"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Vergi Dairesi" id="update_company_tax_office" class="form-control border-start-0">
                                                        <label>Vergi Dairesi</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-upc"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" placeholder="Vergi No" id="update_company_tax_number" class="form-control border-start-0">
                                                        <label>Vergi No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback mb-3">Add valid data</div>
                                        </div>
                                        <div class="col-12 col-md-4 mb-2">
                                            <button type="submit" class="btn btn-theme">Kaydet</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="authorizescompany" role="tabpanel"
                         aria-labelledby="authorizescompany-tab">
                        <div class="row mb-4">
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <div class="row align-items-start">
                                            <div class="col">
                                                <h6 class="text-truncate mb-0">Shrivally</h6>
                                                <p>Amsterdam, NL</p>
                                                <p class="text-secondary small">UI Designer</p>
                                            </div>
                                            <div class="col-auto">
                                                <button id="bEdit" type="button" class="btn btn-sm btn-light" onclick="openUpdateCustomerEmployeeModal('1')">
                                                    <span class="bi bi-pencil-square"></span>
                                                </button>
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

<div class="modal modal-cover fade" id="addCompanyEmployeeModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="add_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Yetkili Ekle</h6>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="add_employee_name" placeholder="Adı" required>
                                        <label for="add_employee_name">Adı</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="add_employee_title" placeholder="Ünvanı" >
                                        <label for="add_employee_title">Ünvanı</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="add_employee_email" placeholder="Eposta" >
                                        <label for="add_employee_email">Eposta</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="add_employee_phone" placeholder="Telefon" >
                                        <label for="add_employee_phone">Telefon</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="add_employee_mobile" placeholder="Cep Telefonu" >
                                        <label for="add_employee_mobile">Cep Telefonu</label>
                                    </div>
                                </div>
                            </div>
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
<div class="modal modal-cover fade" id="updateCompanyEmployeeModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="#" id="update_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <h6 class="title">Yetkili Güncelle</h6>
                            <input type="hidden" class="form-control" id="update_employee_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="update_employee_name" placeholder="Adı" required>
                                        <label for="update_employee_name">Adı</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="update_employee_title" placeholder="Ünvanı" >
                                        <label for="update_employee_title">Ünvanı</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="update_employee_email" placeholder="Eposta" >
                                        <label for="update_employee_email">Eposta</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="update_employee_phone" placeholder="Telefon" >
                                        <label for="update_employee_phone">Telefon</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3 position-relative">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-person"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="update_employee_mobile" placeholder="Cep Telefonu" >
                                        <label for="update_employee_mobile">Cep Telefonu</label>
                                    </div>
                                </div>
                            </div>
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
