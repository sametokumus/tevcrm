<?php
include('header.php');
$extra_js='
<script src="services/brands.js"></script>
<script type="text/javascript">
$(function() {
    //fancyfileuplod
//    $("#brand_logo").FancyFileUpload({
//        params: {
//            action: "fileuploader"
//        },
//        maxfilesize: 1000000
//    });
});
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
                            <h1 class="page-title">Markalar</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Markalar</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_brand_form">

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Marka Ekle</div>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Marka Adı :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="brand_name" required>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Marka Slug :</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="brand_slug" required>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Marka Logo :</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="file" id="brand_logo">
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

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mevcut Markalar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="brand-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Id</th>
                                                    <th class="border-bottom-0">Logo</th>
                                                    <th class="border-bottom-0">Marka</th>
                                                    <th class="border-bottom-0">Slug</th>
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

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Mevcut Markalar</h1>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW OPEN -->
                        <div class="row" id="brand_view">

                        </div>
                        <!-- ROW CLOSED -->
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

    <div class="modal fade" id="updateBrandModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_brand_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Marka Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Marka Adı :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_brand_name" required>
                                <input type="hidden" class="form-control" id="update_brand_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Marka Slug :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_brand_slug" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Marka Logo :</label>
                            <div class="col-md-9">
                                <input class="form-control" type="file" id="update_brand_logo">
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