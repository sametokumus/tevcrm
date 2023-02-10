@include('include.header')
<?php
$extra_js='
<script src="services/proforma-invoice-print.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-7">

                    <div class="wrapper">
                        <!-- Main content -->
                        <section id="quote-print" class="print-color">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="contact-col">
                                        <h6>Supplier</h6>
                                        <address>

                                        </address>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h2 class="logo-header">
                                        <div id="logo"></div>
                                        <small class="date"></small>
                                        <div class="offer-id"></div>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h1 class="page-header">
                                        Proforma Invoice
                                    </h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 buyer-col">
                                    <h6>Customer</h6>
                                    <address>
                                        <span id="buyer_name"></span><br>
                                        <span id="buyer_address"></span><br>
                                        <span id="payment_term"></span><br>
                                    </address>
                                </div>
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table id="sale-detail" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Ref. Code</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Pcs. Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <div class="col-6 offset-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr id="sub_total">
                                                <th>SUB TOTAL</th>
                                                <td></td>
                                            </tr>
                                            <tr id="freight">
                                                <th>FREIGHT</th>
                                                <td></td>
                                            </tr>
                                            <tr id="vat">
                                                <th>VAT</th>
                                                <td></td>
                                            </tr>
                                            <tr id="grand_total">
                                                <th>SUB TOTAL</th>
                                                <td></td>
                                            </tr>
                                            <tr id="shipping">
                                                <th>SHIPPING</th>
                                                <td></td>
                                            </tr>
                                            <tr id="grand_total_with_shipping">
                                                <th>GRAND TOTAL</th>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="margin-top: 15px; margin-bottom: 0;">
                                        Note:
                                        <button id="addNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openAddNoteModal();">Not Ekle</button>
                                        <button id="updateNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openUpdateNoteModal();">Not Güncelle</button>
                                    </p>
                                    <div id="note" class="text-muted">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="margin-top: 15px; margin-bottom: 0;">
                                        Bank Details:
                                        <button type="button" class="btn btn-outline-secondary btn-sm no-print" onclick="openAddBankInfoModal();">Banka Bilgisi Ekle</button>
                                    </p>
                                    <div id="bank-details" class="text-muted">

                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <button onclick="openUpdateDetailModal();" class="btn btn-theme btn-block w-100 mb-2 no-print">Bilgileri Güncelle</button>
                                <button onclick="printOffer();" class="btn btn-theme btn-block w-100 no-print">Yazdır</button>
                            </div>
                            <div class="col-md-12 mt-3">
                                <img src="img/owner/printable-footer.jpg" alt="" class="w-100">
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

<div class="modal modal-cover fade" id="updateDetailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">BİLGİ GÜNCELLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_detail_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Payment Terms</label>
                            <input type="text" class="form-control" id="update_sale_payment_term">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="text" class="summernote" id="update_sale_note" title="Contents"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Shipping Price</label>
                            <input type="text" class="form-control" id="update_sale_shipping_price">
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

<div class="modal modal-cover fade" id="addBankInfoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">BANKA BİLGİSİ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_quote_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <select onchange="changeBankInfo();" name="select_bank_info" class="form-control form-select" id="select_bank_info" required>
                                <option value="0">Bilgi Yok</option>
                            </select>
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

@include('include.footer')
