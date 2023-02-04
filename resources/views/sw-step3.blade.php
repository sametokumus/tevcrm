@include('include.header')
<?php
$extra_js='
<script src="services/sw-step3.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <nav class="nav nav-wizards-3 mb-2">
                        <div class="nav-item col">
                            <a class="nav-link completed" href="/sw-1">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 1</div>
                                <div class="nav-text">Firma / İstek Seçimi</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link completed" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 2</div>
                                <div class="nav-text">Teklif Oluşturma</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link active" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 3</div>
                                <div class="nav-text">Fiyat Güncelleme</div>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Müşteri Teklifi
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">
                            <table id="sales-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0 d-none">Offer ID</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
                                    <th class="border-bottom-0 d-none">Supplier ID</th>
                                    <th class="border-bottom-0">Tedarikçi</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">DC</th>
                                    <th class="border-bottom-0">Paketleme</th>
                                    <th class="border-bottom-0">İstek Adet</th>
                                    <th class="border-bottom-0">Teklif Adet</th>
                                    <th class="border-bottom-0">Birim Fiyat</th>
                                    <th class="border-bottom-0">Toplam Fiyat</th>
                                    <th class="border-bottom-0">İndirim Oranı</th>
                                    <th class="border-bottom-0">İndirimli Fiyat</th>
                                    <th class="border-bottom-0">Teklif Fiyatı</th>
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


        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addOfferPriceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF FİYAT EKLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_offer_price_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fiyat</label>
                            <input type="text" class="form-control" id="add_offer_price_price">
                            <input type="hidden" class="form-control" id="add_offer_price_offer_id">
                            <input type="hidden" class="form-control" id="add_offer_price_offer_product_id">
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

<div class="modal modal-cover fade" id="updateOfferPriceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF FİYAT GÜNCELLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_offer_price_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fiyat</label>
                            <input type="text" class="form-control" id="update_offer_price_price">
                            <input type="hidden" class="form-control" id="update_offer_price_offer_id">
                            <input type="hidden" class="form-control" id="update_offer_price_offer_product_id">
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
