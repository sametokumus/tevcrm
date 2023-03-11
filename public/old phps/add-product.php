<?php
include('header.php');
$extra_js='
<script src="services/add-product.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Ürün Ekle</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ürün</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_product_form">
                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Yeni Ürün</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Stok Kodu :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="product_sku" required>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Marka :</label>
                                                <div class="col-md-9">
                                                    <select name="brand" class="form-control form-select select2" id="product_brand" required>
                                                        <option>Marka Seçiniz</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Tür :</label>
                                                <div class="col-md-9">
                                                    <select name="type" class="form-control form-select select2" id="product_type" required>
                                                        <option>Tür Seçiniz</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Ürün Adı :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="product_name" required>
                                                </div>
                                            </div>

                                            <!-- Row -->
                                            <div class="row">
                                                <label class="col-md-3 form-label mb-4">Ürün Açıklaması :</label>
                                                <div class="col-md-9 mb-4">
                                                    <textarea class="tinyMce" id="product_description"></textarea>
                                                </div>
                                            </div>
                                            <!--End Row-->
                                        </div>
                                        <div class="card-footer">
                                            <!--Row-->
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
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

                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

    <div class="modal fade" id="checkProductSkuModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="check_product_sku_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Stok Kodu</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <h6>Eklediğiniz ürün stok kodu sistemde bulunmaktadır. Devam etmek istediğinize emin misiniz?</h6>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</a>
                        <button type="submit" class="btn btn-danger">Yine de Kaydet</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include('footer.php'); ?>