@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="js/demo/dashboard.demo.js"></script>
<script src="services/accounting-detail.js"></script>
';
?>

<div id="content" class="app-content">

    <div class="row">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Müşteri</span>
                        <h3 id="customer-name"></h3>
                        <div id="customer-employee"></div>
                        <div id="owner-employee"></div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-gem fa-4x"></i>
                    </div>
                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Toplam Satış Tutarı</span>
                        <h3 id="total-price"></h3>
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-wallet fa-4x"></i>
                    </div>
                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Kalan Ödeme Tutarı</span>
                        <h3 id="remaining-price"></h3>
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-wallet fa-4x"></i>
                    </div>
                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Talep Tarihi</span>
                        <h3 id="sale-date"></h3>
                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-calendar-day fa-4x"></i>
                    </div>
                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                Ödeme Listesi
            </h1>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="card border-theme mb-3">
                <div class="card-body p-3 overflow-auto">
                    <table id="payments" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                        <thead>
                        <tr>
                            <th class="border-bottom-0">N#</th>
                            <th class="border-bottom-0">ID</th>
                            <th class="border-bottom-0">Ödeme Türü</th>
                            <th class="border-bottom-0">Ödeme Yöntemi</th>
                            <th class="border-bottom-0">Vade (Gün)</th>
                            <th class="border-bottom-0">Vade Tarihi</th>
                            <th class="border-bottom-0">Ödenecek Tutar</th>
                            <th class="border-bottom-0">Para Birimi</th>
                            <th class="border-bottom-0">Durum</th>
                            <th class="border-bottom-0"></th>
                        </tr>
                        </thead>
                        <tbody id="sales-detail-body">

                        </tbody>
                    </table>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

</div>

    <div class="modal modal-cover fade" id="addPaymentModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">İPTAL SEBEBİ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="#" id="add_payment_form">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Açıklama :</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" id="add_payment_sale_id" required>
                                <select class="form-control" id="add_payment_payment_type">
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Açıklama :</label>
                            <div class="col-md-9">
                                <select class="form-control" id="add_payment_payment_method">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-theme">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@include('include.footer')
