@include('include.header')
<?php
$extra_js='
<script src="/services/supplier-detail.js"></script>
';
?>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title" id="supplier-name">

                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tedarikçi</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-6">
                <div class="header-nav">
                    <div class="btn-list">
                        <button class="btn btn-primary" onclick="openAddAddressModal();"><span class="fe fe-plus"></span> Adres Ekle</button>
                        <button class="btn btn-primary" onclick="openAddContactModal();"><span class="fe fe-plus"></span> Yetkili Ekle</button>
                        <button class="btn btn-danger" onclick="deleteSupplier();"><span class="fe fe-trash-2"></span> Tedarikçiyi Sil</button>
                    </div>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Adresler</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="address-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0" data-priority="1">#</th>
                                        <th class="border-bottom-0" data-priority="2">Adres Adı</th>
                                        <th class="border-bottom-0">Adres</th>
                                        <th class="border-bottom-0">Ülke</th>
                                        <th class="border-bottom-0">Bölge</th>
                                        <th class="border-bottom-0">Şehir</th>
                                        <th class="border-bottom-0">Telefon</th>
                                        <th class="border-bottom-0">Faks</th>
                                        <th class="border-bottom-0">İşlem</th>
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
            <!-- End Row -->

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Firma Yetkilileri</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="contacts-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0" data-priority="1">#</th>
                                        <th class="border-bottom-0" data-priority="2">Görev</th>
                                        <th class="border-bottom-0">Ad Soyad</th>
                                        <th class="border-bottom-0">Telefon</th>
                                        <th class="border-bottom-0">E-posta</th>
                                        <th class="border-bottom-0">İşlem</th>
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
            <!-- End Row -->



        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->



<div class="modal fade" id="updateSupplierModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_supplier_form">
                <div class="modal-header">
                    <h5 class="modal-title">Tedarikçi Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Tedarikçi Adı :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_supplier_id" required>
                            <input type="text" class="form-control" id="update_supplier_name" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="addAddressModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="add_address_form">
                <div class="modal-header">
                    <h5 class="modal-title">Adres Ekle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres Adı :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ülke :</label>
                        <div class="col-md-9">
                            <select name="brand" class="form-control form-select" id="add_address_country" required>
                                <option>Ülke Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Bölge :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="add_address_state">
                                <option>Şehir Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Şehir :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="add_address_city">
                                <option>Şehir Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Faks :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_fax" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="updateAddressModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_address_form">
                <div class="modal-header">
                    <h5 class="modal-title">Adres Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres Adı :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_address_id" required>
                            <input type="text" class="form-control" id="update_address_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ülke :</label>
                        <div class="col-md-9">
                            <select name="brand" class="form-control form-select" id="update_address_country" required>
                                <option>Ülke Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Bölge :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="update_address_state">
                                <option>Bölge Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Şehir :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="update_address_city">
                                <option>Şehir Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Faks :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_fax" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="addContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="add_contact_form">
                <div class="modal-header">
                    <h5 class="modal-title">Yetkili Ekle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Görev :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_title" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ad Soyad :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">E-posta :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_email" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="updateContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_contact_form">
                <div class="modal-header">
                    <h5 class="modal-title">Yetkili Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Görev :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_contact_title" required>
                            <input type="hidden" class="form-control" id="update_contact_id" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ad Soyad :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_contact_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_contact_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">E-posta :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_contact_email" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>



@include('include.footer')
