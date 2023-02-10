@include('include.header')
<?php
$extra_js='
<script src="services/purchasing-order-print.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row justify-content-center no-print">
                <div class="col-md-7">
                    <form method="post" action="#" id="select_offer_form">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <select onchange="changeOffer();" name="select_offer" class="form-control form-select" id="select_offer" required>
                                    <option value="0">Teklif Seçiniz</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-7">

                    <div class="wrapper">
                        <!-- Main content -->
                        <section id="purchasing-order-print" class="print-color">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="contact-col">
{{--                                        <h6>Supplier</h6>--}}
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
                                        Purchasing Order
                                    </h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 supplier-col">
                                    <h6>Supplier</h6>
                                    <address>
                                        <span id="supplier_name"></span><br>
                                        <span id="supplier_address"></span><br>
                                        <span id="payment_term">Payment Terms: </span><br>
                                    </address>
                                </div>
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table id="offer-detail" class="table table-striped">
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
                                            <tr id="vat">
                                                <th>VAT</th>
                                                <td></td>
                                            </tr>
                                            <tr id="grand_total">
                                                <th>GRAND TOTAL</th>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p style="margin-top: 15px; margin-bottom: 0;">
                                        Note:
                                        <button id="addNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openAddNoteModal();">Not Ekle</button>
                                        <button id="updateNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openUpdateNoteModal();">Not Güncelle</button>
                                    </p>
                                    <div id="note" class="text-muted">

                                    </div>
                                </div>
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
                        <div class="row mt-2">
                            <div class="col-12 d-none" id="print-buttons">
                                <button onclick="printOffer();" class="btn btn-theme btn-block w-100 no-print">Yazdır</button>
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


<div class="modal modal-cover fade" id="addNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Bilgi</label>
                            <textarea name="text" class="summernote" id="add_purchasing_order_note" title="Contents"></textarea>
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
<div class="modal modal-cover fade" id="updateNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Bilgi</label>
                            <textarea name="text" class="summernote" id="update_purchasing_order_note" title="Contents"></textarea>
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
