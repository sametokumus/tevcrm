<?php
include('header.php');
$extra_js='
<script src="services/seos.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">SEO İçerikleri</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">SEO</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mevcut İçerikler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="seo-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">ID</th>
                                                    <th class="border-bottom-0">Sayfa</th>
                                                    <th class="border-bottom-0">Title</th>
                                                    <th class="border-bottom-0">Keywords</th>
                                                    <th class="border-bottom-0">Description</th>
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

    <div class="modal fade" id="updateSeoModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_seo_form">
                    <div class="modal-header">
                        <h5 class="modal-title">SEO Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Kod :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_seo_page" required>
                                <input type="hidden" class="form-control" id="update_seo_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Title :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_seo_title" >
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Keywords :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_seo_keywords" >
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Description :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_seo_description" >
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include('footer.php'); ?>