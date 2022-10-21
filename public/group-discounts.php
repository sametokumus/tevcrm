<?php
include('header.php');
$extra_js='
<script src="services/group-discounts.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Toplu İskonto Tanımlama</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Toplu İskonto Tanımlama</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm mt-5">
                            <div class="col-lg-12">
                                <form method="post" action="" id="product_discount_form">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Filtreleme</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>Marka Seçiniz</label>
                                                            <select name="brand[]" class="form-control form-select select2" multiple="multiple" id="brand" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <label>Tür Seçiniz</label>
                                                            <select name="type[]" class="form-control form-select select2" multiple="multiple" id="type" required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
<!--                                                <div class="col-sm-3">-->
<!--                                                    <div class="row mb-4">-->
<!--                                                        <div class="col-md-12">-->
<!--                                                            <input type="text" class="form-control" id="discount" placeholder="İndirim Oranı" required>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <!--Row-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary float-start">Filtreyi Uygula</button>
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
                                        <h3 class="card-title">Ürün Güncelle</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="product-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
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

    <div class="modal fade" id="updateDiscountModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
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
                            <label class="col-md-6 form-label">İskonto Oranı :</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="update_discount_rate" required>
                                <input type="hidden" class="form-control" id="update_product_ids" required>
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