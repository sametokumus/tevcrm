<?php
include('header.php');
$extra_js='
<script src="services/update-card.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Banka Güncelle</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Sipariş</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <div class="row">

                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_payment_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Banka Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Banka Id :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="card_bank_id" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Banka :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="card_bank_name" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Kart Adı :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="card_card_name" required readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Taksitler</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="card-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Taksit</th>
                                                    <th class="border-bottom-0">Artı Taksit</th>
                                                    <th class="border-bottom-0">Oran</th>
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



                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->


    <div class="modal fade" id="updateInstallmentModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_installment_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Durum Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Taksit :</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" id="update_installment_id" required>
                                <input type="text" class="form-control" id="update_installment" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Artı Taksit :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_installment_plus" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">İndirim :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_installment_discount" required>
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