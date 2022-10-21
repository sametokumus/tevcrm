<?php
include('header.php');
$extra_js='
<script src="services/user-type-discounts.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Kullanıcı Türü Bazlı İskonto Tanımlama</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">İskonto Tanımlama</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm mt-5">
                            <div class="col-lg-12">
                                <form method="post" action="" id="add_discount_form">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">İskonto Oluştur</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>Kullanıcı Türü Seçiniz</label>
                                                            <select name="user_type" class="form-control form-select select2" id="user_type" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>Marka Seçiniz</label>
                                                            <select name="brand[]" class="form-control form-select select2" multiple="multiple" id="brand" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>Tür Seçiniz</label>
                                                            <select name="type[]" class="form-control form-select select2" multiple="multiple" id="type" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>İndirim Oranı</label>
                                                            <input type="text" class="form-control" id="discount" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <!--Row-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary float-start">İskonto Uygula</button>
                                                </div>
                                            </div>
                                            <!--End Row-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Row -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">İskontolar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="discount-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0" data-priority="1">Kullanıcı Türü</th>
                                                    <th class="border-bottom-0">Marka</th>
                                                    <th class="border-bottom-0">Ürün Türü</th>
                                                    <th class="border-bottom-0">İskonto Oranı</th>
                                                    <th class="border-bottom-0" data-priority="2">İşlem</th>
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

    <div class="modal fade" id="updateUserTypeDiscountModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_discount_form">
                    <div class="modal-header">
                        <h5 class="modal-title">İskonto Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-6 form-label">Kullanıcı Türü :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="update_user_type" readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-6 form-label">Marka :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="update_brand" readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-6 form-label">Tür :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="update_type" readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-6 form-label">İskonto Oranı :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="update_discount" required>
                                <input type="hidden" class="form-control" id="update_discount_id" required>
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


<?php include('footer.php'); ?>