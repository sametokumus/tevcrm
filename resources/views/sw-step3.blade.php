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
                            <a class="nav-link completed">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 1</div>
                                <div class="nav-text">Firma / İstek Seçimi</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link completed">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 2</div>
                                <div class="nav-text">Teklif Oluşturma</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link active">
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
                            <table id="sales-detail" class="table table-bordered key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0 d-none">Offer ID</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
                                    <th class="border-bottom-0 d-none">Supplier ID</th>
                                    <th class="border-bottom-0">Tedarikçi</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0 d-none">DC</th>
                                    <th class="border-bottom-0 d-none">Paketleme</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">İstek Miktar</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0">Birim</th>
                                    <th class="border-bottom-0">Birim Fiyat</th>
                                    <th class="border-bottom-0">Toplam Fiyat</th>
                                    <th class="border-bottom-0">İndirim Oranı</th>
                                    <th class="border-bottom-0">İndirimli Fiyat</th>
                                    <th class="border-bottom-0">Vergi Oranı</th>
                                    <th class="border-bottom-0">Para Birimi</th>
                                    <th class="border-bottom-0">Teklif Birim Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Para Birimi</th>
                                    <th class="border-bottom-0">Teklif Teslimat Süresi</th>
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

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">

                            <form method="post" action="#" id="update_quote_form">
                                <div class="row mb-4">
                                    <h5 class="px-2">
                                        Teklif Detayları
                                    </h5>
                                    <input type="hidden" class="form-control" id="update_quote_id">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Payment Term</label>
                                        <select class="form-control" id="update_quote_payment_term">

                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Delivery Terms</label>
                                        <select class="form-control" id="update_quote_delivery_term">

                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-label">Insurance</label>
                                        <input type="text" class="form-control" id="update_quote_lead_time">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-label">Country of Destination</label>
                                        <input type="text" class="form-control" id="update_quote_country_of_destination">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-label">Freight Price</label>
                                        <input type="text" class="form-control" id="update_quote_freight">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Note</label>
                                        <textarea name="text" class="summernote" id="update_quote_note" title="Contents"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-outline-theme d-block w-100">Kaydet</button>
                                    </div>
                                </div>
                            </form>

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

<div class="modal modal-cover fade" id="addBatchProcessModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #202b36;">
            <div class="modal-header">
                <h5 class="modal-title">TOPLU İŞLEM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_batch_process_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kar Oranı</label>
                            <input type="text" class="form-control" id="add_batch_offer_profit_rate" placeholder="% olarak hesaplanır (Örn: 20 giriniz)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teslimat Süresi</label>
                            <input type="text" class="form-control" id="add_batch_offer_lead_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="add_batch_offer_currency">
                                <option value="TRY">TRY</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Para Birimi Dönüşüm Oranı</label>
                            <input type="text" class="form-control" id="add_batch_offer_currency_change" value="1,00">
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="add_offer_price_currency">
                                <option value="TRY">TRY</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="update_offer_price_currency">
                                <option value="TRY">TRY</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
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
