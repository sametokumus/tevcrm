<?php
include('header.php');
$extra_js='
<script src="services/product-categories.js"></script>
<script src="plugins/treeview/treeview.js"></script>
<script type="text/javascript">
//$(function(e) {
//    "use strict"
//    $("#update_parent_category").select2({
//        minimumResultsForSearch: Infinity,
//        width: "100%",
//        dropdownParent: "#updateCategoryModal"
//    });
//});
</script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Kategori</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kategoriler</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_category_form">

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Kategori Ekle</div>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Ana Kategori :</label>
                                                <div class="col-md-9">
                                                    <select name="parent_category" class="form-control form-select select2" id="parent_category" required>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Kategori Adı :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="category_name" required>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Kategori Slug :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="category_slug" required>
                                                </div>
                                            </div>

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

                        <!-- ROW OPEN -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            Mevcut Kategoriler
                                            <p class="card-sub-title fs-12 fw-normal text-muted">Ana kategorilere tıklayarak alt kategorileri görüntüleyebilirsiniz.</p>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- col -->
                                            <div class="col-lg-12">
                                                <ul id="category_view">
                                                    <li><a href="javascript:void(0)">TECH</a>
                                                        <ul>
                                                            <li>Company Maintenance</li>
                                                            <li>Employees
                                                                <ul>
                                                                    <li>Reports</li>
                                                                </ul>
                                                            </li>
                                                            <li>Human Resources</li>
                                                        </ul>
                                                    </li>
                                                    <li>XRP
                                                        <ul>
                                                            <li>Company Maintenance</li>
                                                            <li>Employees
                                                                <ul>
                                                                    <li>Reports</li>
                                                                </ul>
                                                            </li>
                                                            <li>Human Resources</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- /col -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ROW CLOSED -->
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

    <div class="modal fade" id="updateCategoryModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_category_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Kategori Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Ana Kategori :</label>
                            <div class="col-md-9">
                                <select name="update_parent_category" class="form-control form-select" id="update_parent_category" required>

                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Kategori Adı :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_category_name" required>
                                <input type="hidden" class="form-control" id="update_category_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Kategori Slug :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_category_slug" required>
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