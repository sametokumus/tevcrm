@include('include.header')
<?php
$extra_js='
<script src="services/admins.js"></script>
';
?>



    <!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Admin Hesapları</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Admin Hesapları</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <form method="post" action="#" id="add_admin_form">

                <!-- ROW-1 OPEN -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Admin Hesabı Oluştur</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">E-posta :</label>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" id="admin_email" required>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Ad :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_name" required>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Telefon :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_phone" required>
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
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Soyad :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="admin_surname" required>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Rol :</label>
                                            <div class="col-md-9">
                                                <select name="type" class="form-control form-select" id="admin_role" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <!--Row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Oluştur</button>
                                    </div>
                                </div>
                                <!--End Row-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /ROW-1 CLOSED -->

            </form>

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Mevcut Hesaplar</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
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

<div class="modal fade" id="updateAdminModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_admin_form">
                <div class="modal-header">
                    <h5 class="modal-title">Admin Hesabı Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
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
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        </div>
    </div>
</div>

@include('include.footer')
