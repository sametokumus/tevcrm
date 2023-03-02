@include('include.header')
<?php
$extra_js='
<script src="services/roles.js"></script>
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
                        Rol Ekle
                    </h3>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body">
                            <form method="post" action="#" id="add_role_form">
                                <div class="row p-3">
                                    <div class="col-md-12">
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Rol Adı :</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="role_name" required>
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
                        Roller
                    </h3>
                </div>
            </div>

            <table id="role-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Ad</th>
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

<div class="modal modal-cover fade" id="updateRoleModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rol Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_role_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Rol Adı :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_role_name" required>
                            <input type="hidden" class="form-control" id="update_role_id" required>
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

<div class="modal modal-cover fade" id="updateRolePermissionsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yetkileri Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_role_permissions_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <input type="hidden" class="form-control" id="update_role_permissions_id" required>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="form-label">Yetkiler</label>
                                <div class="selectgroup selectgroup-pills" id="admin_permissions">

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="form-label">Sipariş Durumu Yetkileri</label>
                                <div class="selectgroup selectgroup-pills" id="admin_status_permissions">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="modal-footer">--}}
{{--                    <button type="submit" class="btn btn-theme">Kaydet</button>--}}
{{--                </div>--}}
            </form>
        </div>
    </div>
</div>

@include('include.footer')
