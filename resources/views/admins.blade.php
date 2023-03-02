@include('include.header')
<?php
$extra_js='
<script src="services/admins.js"></script>
';
?>

<!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-header">
                        Yeni Ekip Üyesi Ekle
                    </h3>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body">
                            <form method="post" action="#" id="add_admin_form">
                                <div class="row p-3">
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">E-posta :</label>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" id="admin_email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Şifre :</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" id="admin_password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Ad :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Soyad :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_surname" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Telefon :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_phone" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Rol :</label>
                                            <div class="col-md-9">
                                                <select name="type" class="form-control form-select" id="admin_role" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Kaydet</button>
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

            </div>


            <div class="row mt-5">
                <div class="col-md-12">
                    <h3 class="page-header">
                        Ekip Üyeleri
                    </h3>
                </div>
            </div>

            <table id="admin-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Rol</th>
                    <th class="border-bottom-0">Ad</th>
                    <th class="border-bottom-0">Soyad</th>
                    <th class="border-bottom-0">E-posta</th>
                    <th class="border-bottom-0">Telefon</th>
                    <th class="border-bottom-0" data-priority="2">İşlemler</th>
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

<div class="modal modal-cover fade" id="updateAdminModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ekip Üyesi Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_admin_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">E-posta :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_admin_email" required>
                            <input type="hidden" class="form-control" id="update_admin_id" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ad :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_admin_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Soyad :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_admin_surname" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_admin_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Rol :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="update_admin_role" required>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('include.footer')
