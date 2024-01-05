@include('include.header')
<?php
$extra_js='
<script src="services/sw-step4-new.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-0">

                            <div class="profile">

                                <div class="profile-container">

                                    <div class="profile-sidebar">
                                        <div class="desktop-sticky-top">
                                            <div class="profile-img">
                                                <img id="customer-logo" src="" alt="">
                                            </div>

                                            <h4 id="employee-name"></h4>
                                            <div class="mb-1" id="employee-phone">

                                            </div>
                                            <div class="mb-3" id="employee-email">

                                            </div>
                                            <button type="button" id="update-profile-button" class="btn btn-sm btn-outline-theme fs-11px">Profili Güncelle</button>
                                            <hr class="mt-4 mb-4">


                                        </div>
                                    </div>


                                    <div class="profile-content">
                                        <ul class="profile-tab nav nav-tabs nav-tabs-v2">
                                            <li class="nav-item">
                                                <a href="#info-tab" class="nav-link active" data-bs-toggle="tab">
                                                    <div class="nav-value fs-16px">Firma Bilgileri</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#delivery-address-tab" class="nav-link" data-bs-toggle="tab">
                                                    <div class="nav-value fs-16px">Sevk Adresleri</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#employees-tab" class="nav-link" data-bs-toggle="tab">
                                                    <div class="nav-value fs-16px">Yetkililer</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#notes-tab" class="nav-link" data-bs-toggle="tab">
                                                    <div class="nav-value fs-16px">Notlar</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#activities-tab" class="nav-link" data-bs-toggle="tab">
                                                    <div class="nav-value fs-16px">Aktiviteler</div>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="profile-content-container">
                                            <div class="row gx-4">
                                                <div class="col-xl-8">
                                                    <div class="tab-content p-0">

                                                        <div class="tab-pane fade show active" id="info-tab">
                                                            <div class="card mb-3">
                                                                <div class="card-body p-4">

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
                                                                <div class="card-arrow">
                                                                    <div class="card-arrow-top-left"></div>
                                                                    <div class="card-arrow-top-right"></div>
                                                                    <div class="card-arrow-bottom-left"></div>
                                                                    <div class="card-arrow-bottom-right"></div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="tab-pane fade" id="delivery-address-tab">
                                                            <div class="card mb-3">
                                                                <div class="card-body p-4">

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <div class="btn-group float-end">
                                                                                <button type="button" class="btn btn-theme" onclick="openAddDeliveryAddressModal();">Sevk Adresi Ekle</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <table id="datatableDeliveryAddresses" class="table table-bordered nowrap key-buttons border-bottom">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="border-bottom-0">#</th>
                                                                                    <th class="border-bottom-0">Başlık</th>
                                                                                    <th class="border-bottom-0">Adres</th>
                                                                                    <th class="border-bottom-0">İşlemler</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>
                                                                            </table>
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


                                                        <div class="tab-pane fade" id="employees-tab">
                                                            <div class="card mb-3">
                                                                <div class="card-body p-4">

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
                                                                <div class="card-arrow">
                                                                    <div class="card-arrow-top-left"></div>
                                                                    <div class="card-arrow-top-right"></div>
                                                                    <div class="card-arrow-bottom-left"></div>
                                                                    <div class="card-arrow-bottom-right"></div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="tab-pane fade" id="notes-tab">
                                                            <div class="card mb-3">
                                                                <div class="card-body p-4">

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <div class="btn-group float-end">
                                                                                <button type="button" class="btn btn-theme" onclick="openAddCompanyNoteModal();">Not Ekle</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="note-list">

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


                                                        <div class="tab-pane fade" id="activities-tab">
                                                            <div class="card mb-3">
                                                                <div class="card-body p-4">

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <div class="btn-group float-end">
                                                                                <button type="button" class="btn btn-theme" onclick="openAddCompanyActivityModal();">Aktivite Ekle</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <table id="datatableActivities" class="table text-nowrap w-100">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th scope="col">#</th>
                                                                                    <th scope="col">Yetkili</th>
                                                                                    <th scope="col">Tür</th>
                                                                                    <th scope="col">Konu</th>
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
                                                                <div class="card-arrow">
                                                                    <div class="card-arrow-top-left"></div>
                                                                    <div class="card-arrow-top-right"></div>
                                                                    <div class="card-arrow-bottom-left"></div>
                                                                    <div class="card-arrow-bottom-right"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="desktop-sticky-top d-none d-lg-block">
                                                        <div class="card mb-3">
                                                            <div class="list-group list-group-flush">
                                                                <div class="list-group-item fw-bold px-3 d-flex">
                                                                    <span class="flex-fill">Satış Yapılan Ürünler</span>
                                                                </div>
                                                                <div class="list-group-item px-3">
                                                                    <div class="table-responsive">
                                                                        <table id="saled-products-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ürün Adı</th>
                                                                                <th>Adet</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
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
                                                        <div class="card mb-3">
                                                            <div class="list-group list-group-flush">
                                                                <div class="list-group-item fw-bold px-3 d-flex">
                                                                    <span class="flex-fill">Satışlar</span>
                                                                </div>
                                                                <div class="list-group-item px-3">
                                                                    <div class="table-responsive">
                                                                        <table id="sales-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Sipariş No</th>
                                                                                <th>Detay</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
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
                                            </div>
                                        </div>
                                    </div>

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

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Müşteri Teklifi (<span id="sw_customer_name"></span>)
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">
                            <table id="sales-detail" class="table table-bordered key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0 d-none">Offer ID</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
                                    <th class="border-bottom-0 d-none">Supplier ID</th>
                                    <th class="border-bottom-0">Tedarikçi</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0 d-none">DC</th>
                                    <th class="border-bottom-0 d-none">Paketleme</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">İstek Miktar</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0">Birim</th>
                                    <th class="border-bottom-0">Birim Fiyat</th>
                                    <th class="border-bottom-0">Toplam Fiyat</th>
                                    <th class="border-bottom-0">İndirim Oranı</th>
                                    <th class="border-bottom-0">İndirimli Fiyat</th>
                                    <th class="border-bottom-0">Vergi Oranı</th>
                                    <th class="border-bottom-0">Tedarik Para Birimi</th>
                                    <th class="border-bottom-0">Tedarik Fiyatı (Satış para birimi cinsinden)</th>
                                    <th class="border-bottom-0">Teklif Birim Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Para Birimi</th>
                                    <th class="border-bottom-0">Teklif Teslimat Süresi</th>
                                    <th class="border-bottom-0">Karlılık</th>
                                </tr>
                                </thead>
                                <tbody id="sales-detail-body">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="23" class="border-bottom-0"></th>
                                </tr>
                                </tfoot>
                            </table>
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


        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addRejectOfferNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİFİN REDDEDİLME NEDENİ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_reject_offer_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea name="text" class="summernote" id="add_sale_note_description" title="Contents"></textarea>
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
