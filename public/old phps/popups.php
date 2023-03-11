<?php
include('header.php');
$extra_js='
<script src="services/popups.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Popuplar</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Popuplar</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <form method="post" action="#" id="add_popup_form">

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Popup Ekle</div>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Başlık :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="popup_title" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Altbaşlık :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="popup_subtitle" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Açıklama :</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="popup_description" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Görsel :</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" id="popup_image_url">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Başlangıç Tarihi :</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                                                </div>
                                                                <input class="form-control fc-datepicker" id="popup_start_date" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label class="col-md-3 form-label">Bitiş Tarihi :</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                                                </div>
                                                                <input class="form-control fc-datepicker" id="popup_end_date" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <div class="form-group m-0">
                                                            <div class="form-label">Görüntülenecek Sayfalar :</div>
                                                            <div class="custom-controls-inline" id="add_popup_pages">

                                                            </div>
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
                                        <h3 class="card-title">Mevcut Popuplar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="popup-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Id</th>
                                                    <th class="border-bottom-0">Görsel</th>
                                                    <th class="border-bottom-0">Başlık</th>
                                                    <th class="border-bottom-0">Altbaşlık</th>
                                                    <th class="border-bottom-0">Açıklama</th>
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

    <div class="modal fade" id="updatePopupModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_popup_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Popup Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Başlık :</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="update_popup_title" required>
                                        <input type="hidden" class="form-control" id="update_popup_id" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Başlık :</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="update_popup_subtitle" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Açıklama :</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="update_popup_description" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Görsel :</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="file" id="update_popup_image_url">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Başlangıç Tarihi :</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                            </div>
                                            <input class="form-control fc-datepicker" id="update_popup_start_date" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Bitiş Tarihi :</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar tx-14 lh-0 op-6"></i>
                                            </div>
                                            <input class="form-control fc-datepicker" id="update_popup_end_date" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="form-group m-0">
                                        <div class="form-label">Görüntülenecek Sayfalar :</div>
                                        <div class="custom-controls-inline" id="update_popup_pages">

                                        </div>
                                    </div>
                                </div>

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