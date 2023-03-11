<?php
include('header.php');
$extra_js='
<script src="services/campaign-product.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- Row -->
                        <div class="row row-sm mt-5">
                            <div class="col-lg-12">
                                <form method="post" action="" id="add_campaign_product_form">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Kampanyalı Ürün Ekle</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Ürün SKU :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="add_campaign_product_sku" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-start">Ekle</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                        <h3 class="card-title">Kampanyalı Ürünler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="product-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0">Sıra</th>
                                                    <th class="border-bottom-0">Ürün ID</th>
                                                    <th class="border-bottom-0" data-priority="1">SKU</th>
                                                    <th class="border-bottom-0">Ürün Adı</th>
                                                    <th class="border-bottom-0">Marka</th>
                                                    <th class="border-bottom-0">Tür</th>
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

    <div class="modal fade" id="updateTypeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_type_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Tür Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Tür Adı :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_type_name" required>
                                <input type="hidden" class="form-control" id="update_type_id" required>
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