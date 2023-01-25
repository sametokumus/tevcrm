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
        <div class="main-container container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-7">

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
                                        Order Form
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

{{--                            <div class="row">--}}
{{--                                <div class="col-6">--}}
{{--                                    <p class="lead">Payment Methods:</p>--}}
{{--                                    <img src="../../dist/img/credit/visa.png" alt="Visa">--}}
{{--                                    <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">--}}
{{--                                    <img src="../../dist/img/credit/american-express.png" alt="American Express">--}}
{{--                                    <img src="../../dist/img/credit/paypal2.png" alt="Paypal">--}}

{{--                                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">--}}
{{--                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr--}}
{{--                                        jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                                <div class="col-6">--}}
{{--                                    <p class="lead">Amount Due 2/22/2014</p>--}}

{{--                                    <div class="table-responsive">--}}
{{--                                        <table class="table">--}}
{{--                                            <tr>--}}
{{--                                                <th style="width:50%">Subtotal:</th>--}}
{{--                                                <td>$250.30</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <th>Tax (9.3%)</th>--}}
{{--                                                <td>$10.34</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <th>Shipping:</th>--}}
{{--                                                <td>$5.80</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <th>Total:</th>--}}
{{--                                                <td>$265.24</td>--}}
{{--                                            </tr>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
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
