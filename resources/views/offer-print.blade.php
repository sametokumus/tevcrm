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
                                <button onclick="printOffer();" class="btn btn-theme btn-block w-100 no-print">Yazdır</button>
                            </div>
                        </div>
                    </div>
                    <script>
                        // window.addEventListener("load", window.print());
                    </script>

                </div>
            </div>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

@include('include.footer')
