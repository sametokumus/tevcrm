<?php
include('header.php');
$extra_js='
<script src="services/default-delivery.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Default Kargo Tutarları</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Default Tutarlar</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_default_form">

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Default Tutarlar</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Min Desi :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="delivery_price_min" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Max Desi :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="delivery_price_max" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Fiyat :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="delivery_price_price" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <!--Row-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Ekle</button>
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
                                        <h3 class="card-title">Mevcut Kuponlar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="delivery-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">ID</th>
                                                    <th class="border-bottom-0">Min</th>
                                                    <th class="border-bottom-0">Max</th>
                                                    <th class="border-bottom-0">Tutar</th>
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

    <div class="modal fade" id="updateDeliveryPriceModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_default_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Default Tutar Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Min Desi :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_delivery_price_min" required>
                                <input type="hidden" class="form-control" id="update_delivery_price_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Max Desi :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_delivery_price_max" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Fiyat :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_delivery_price_price" required>
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