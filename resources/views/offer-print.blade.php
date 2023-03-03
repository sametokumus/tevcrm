@include('include.header')
<?php
$extra_js='
<script src="services/offer-print.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid printable">


            <div class="row justify-content-center mb-5 no-print">
                <div class="col-md-7">
                    <label class="form-label">Firma</label>
                    <select class="form-control" id="update_company_country">
                        <option>{{__('Anasayfa')}} {{ \Illuminate\Support\Facades\App::getLocale() }}</option>
                    </select>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-7 bg-white p-md-50">

                    <div class="wrapper">

                        <!-- Main content -->
                        <section id="offer-print" class="print-color">

                            <!-- title row -->
                            <div class="row">
                                <div class="col-4">
                                    <div class="contact-col">
{{--                                        <h6>Supplier</h6>--}}
                                        <address>

                                        </address>
                                    </div>
                                </div>
                                <div class="col-8">
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
                                        Request for Quotation
                                    </h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 supplier-col">
                                    <h6>Supplier</h6>
                                    <address>

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
                                            <th>Qty</th>
                                            <th>Ref. Code</th>
                                            <th>Product Name</th>
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

                        </section>
                        <div class="row">
                            <div class="col-12">
                                <button onclick="openUpdateDetailModal();" class="btn btn-theme btn-block w-100 mb-2 no-print">Bilgileri Güncelle</button>
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
                            <label class="form-label">Note</label>
                            <textarea name="text" class="summernote" id="update_offer_note" title="Contents"></textarea>
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
