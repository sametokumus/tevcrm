@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="js/demo/dashboard.demo.js"></script>
<script src="services/sale-detail.js"></script>
';
?>

<div id="content" class="app-content">

    <div class="row">

        <div class="col-xl-3 col-lg-6 mb-3">

            <div class="card h-100">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px" id="sale-code"></span>
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

        <div class="col-xl-3 col-lg-6 mb-3">

            <div class="card h-100">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Toplam Satış Tutarı</span>
                        <h3 id="total-price"></h3>
                        <div id="profit-rate-message">&nbsp;</div>
                        <div id="remaining-message">&nbsp;</div>
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

        <div class="col-xl-3 col-lg-6 mb-3">

            <div class="card h-100">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Ürün</span>
                        <h3 id="product-count"></h3>
                        <div id="product-total-count"></div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="opacity-5">
                        <i class="fa fa-people-carry fa-4x"></i>
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

        <div class="col-xl-3 col-lg-6 mb-3">

            <div class="card h-100">

                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">
                    <div class="flex-fill">
                        <span class="flex-grow-1 fw-600 fs-12px">Talep Tarihi</span>
                        <h3 id="sale-date"></h3>
                        <div id="delivery-message">&nbsp;</div>
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

    <div class="row" id="documents">



    </div>
    <div class="row">

        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SATIŞ SÜRECİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="mb-3">
                        <div id="chart-selling-process"></div>
                    </div>


                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SİPARİŞ ÖZETİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="sale-summary-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <tbody>
                            <tr>
                                <td>
                                   <span class="d-flex align-items-center">
                                       <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>
                                       <b>Atrax Medikal Limited Şirketi</b>
                                   </span>
                                </td>
                                <td>9.600,00 TRY</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">EK GİDERLER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <table id="expenses-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                        <thead>
                        <tr>
                            <th class="border-bottom-0" data-priority="1">ID</th>
                            <th class="border-bottom-0">İsim</th>
                            <th class="border-bottom-0">Tutar</th>
                            <th class="border-bottom-0">Para Birimi</th>
                            <th class="border-bottom-0" data-priority="2">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>

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

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">MOBİL DÖKÜMANLAR</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <table id="document-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                        <thead>
                        <tr>
                            <th class="border-bottom-0" data-priority="1">ID</th>
                            <th class="border-bottom-0">Tür</th>
                            <th class="border-bottom-0" data-priority="2">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>

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

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TEDARİKÇİLER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="suppliers-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <tbody>

                            </tbody>
                        </table>
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


        <div class="col-xl-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">SİPARİŞ SÜRECİ</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="table-responsive">
                        <table id="status-history-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                            <tbody>

                            </tbody>
                        </table>
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

</div>

<div class="modal modal-cover fade" id="addDocumentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DÖKÜMAN EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_document_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tür</label>
                            <select class="form-control" id="add_document_type">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Döküman</label>
                            <input type="file" class="form-control" id="add_document_file" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="addExpenseModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EK GİDERLER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form method="post" action="#" id="add_expense_form">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Gider Türü</label>
                            <select class="form-control" id="add_expense_category" onchange="initExpenseToModal();">
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tutar</label>
                            <input type="text" class="form-control" id="add_expense_price" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="add_expense_currency">
                                <option value="TRY">TRY</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>


@include('include.footer')
