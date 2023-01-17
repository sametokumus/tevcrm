@include('include.header')
<?php
$extra_js='
<script src="services/offer.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Talep
                    </h1>
                </div>
            </div>


                <div class="row">

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body p-3">
                                <table id="offer-request-products" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">Ref. Code</th>
                                        <th class="border-bottom-0">Ürün Adı</th>
                                        <th class="border-bottom-0">Adet</th>
                                    </tr>
                                    </thead>
                                    <tbody id="offer-request-products-body">

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
        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addOfferModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF FİRMA SEÇİMİ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_offer_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tedarikçi</label>
                            <select class="form-control" id="add_offer_company">

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
