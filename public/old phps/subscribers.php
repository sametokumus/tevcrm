<?php
include('header.php');
$extra_js='
<script src="services/subscribers.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Bülten Abonelikleri</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Bülten Abonelikleri</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mevcut Aboneler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="subscriber-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Id</th>
                                                    <th class="border-bottom-0">E-posta</th>
                                                    <th class="border-bottom-0">Referans Item</th>
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

    <div class="modal fade" id="updateSubscriberModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_subscriber_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Abonelik Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Eposta :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_subscriber_email" required>
                                <input type="hidden" class="form-control" id="update_subscriber_id" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Referans Item :</label>
                            <div class="col-md-9">
                                <select name="type" class="form-control form-select" id="update_subscriber_referrer" required>
                                    <option value="1">Page Item</option>
                                    <option value="2">Popup</option>
                                </select>
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