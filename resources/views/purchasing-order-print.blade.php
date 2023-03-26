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
        <div class="main-container container-fluid printable">

            <div class="row justify-content-center mb-3 no-print">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Dil</label>
                            <select class="form-control" id="lang">
                                <option value="tr" @if(app()->getLocale() == 'tr') selected="selected" @endif>Türkçe</option>
                                <option value="en" @if(app()->getLocale() == 'en') selected="selected" @endif>English</option>
                            </select>
                        </div>
                        <div class="col-6 d-none">
                            <label class="form-label">Firma</label>
                            <select class="form-control" id="owners" onchange="changeOwner();">

                            </select>
                        </div>
                    </div>
                </div>
            </div>

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
                <div class="col-md-7 bg-white p-md-50">

                    <div class="wrapper">

                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="border-0">

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
                                                <h1 class="page-header">
                                                    {{__('Purchasing Order')}}
                                                </h1>
                                                <div class="supplier-col">
                                                    <h6>{{__('Supplier')}}</h6>
                                                    <address>
                                                        <span id="supplier_name"></span><br>
                                                        <span id="supplier_address"></span><br>
                                                        <span id="payment_term"><b>{{__('Payment Terms')}}:</b> </span><br>
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

                                        <!-- Table row -->
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table id="offer-detail" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">N#</th>
                                                        <th class="text-center">{{__('Ref. Code')}}</th>
                                                        <th class="text-center">{{__('Product Name')}}</th>
                                                        <th class="text-center">{{__('Qty')}}</th>
                                                        <th class="text-center">{{__('Unit')}}</th>
                                                        <th class="text-center">{{__('Unit Price')}}</th>
                                                        <th class="text-center">{{__('Total Price')}}</th>
                                                        <th class="text-center">{{__('Lead Time')}}</th>
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
                                            <div class="col-md-12">
                                                <p style="margin-top: 15px; margin-bottom: 0;">
                                                    {{__('Note')}}:
                                                    <button id="addNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openAddNoteModal();">Not Ekle</button>
                                                    <button id="updateNoteBtn" type="button" class="btn btn-outline-secondary btn-sm no-print d-none" onclick="openUpdateNoteModal();">Not Güncelle</button>
                                                </p>
                                                <div id="note" class="text-muted">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-5">
                                                <div class="company-signature text-center">
                                                    <h6 class="title">{{__('Authorised Signature')}}</h6>
                                                    {{--                                        <img src="#" alt="" id="signature" class="signature">--}}
                                                    <div id="signature"></div>
                                                    <p class="name"></p>
                                                    <p class="info">{{__('Name Surname')}} / {{__('Signature')}}</p>
                                                </div>
                                            </div>
                                            <div class="col-5 offset-2">
                                                <div class="customer-signature text-center">
                                                    <h6 class="title">{{__('Supplier Confirmation')}}</h6>
                                                    <div class="signature"></div>
                                                    <p class="name"></p>
                                                    <p class="info">{{__('Name Surname')}} / {{__('Signature')}} / {{__('Date')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p style="margin-top: 15px; margin-bottom: 0;">
                                                    {{__('Bank Details:')}}
                                                    <button type="button" class="btn btn-outline-secondary btn-sm no-print" onclick="openAddBankInfoModal();">Banka Bilgisi Ekle</button>
                                                </p>
                                                <div id="bank-details" class="text-muted">

                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <div class="row">
                                        <div class="col-12 d-none" id="print-buttons">
                                            <button onclick="printOffer();" class="btn btn-theme btn-block w-100 no-print">Yazdır</button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 mt-3">
                                            <div id="print-footer">

                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <td class="border-0">
                                    <div class="footer-spacer"></div>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
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
