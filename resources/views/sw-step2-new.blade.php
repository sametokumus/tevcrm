@include('include.header')
<?php
$extra_js='
<script src="services/sw-step2-new.js"></script>
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
                            <a class="nav-link active">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Adım 2</div>
                                <div class="nav-text">Teklif Oluşturma</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link disabled">
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
                        Döviz Kurları
                    </h1>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <form method="post" action="#" id="update_currency_rate_form">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Satış Para Birimi</label>
                                        <select class="form-control" id="update_currency_rate_currency">
                                            <option value="TRY">TRY</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">1 TL Karşılığı Dolar Kuru</label>
                                        <input type="text" value="" class="form-control" id="update_currency_rate_usd" required />
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">1 TL Karşılığı Euro Kuru</label>
                                        <input type="text" value="" class="form-control" id="update_currency_rate_eur" required />
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">1 TL Karşılığı Sterlin Kuru</label>
                                        <input type="text" value="" class="form-control" id="update_currency_rate_gbp" required />
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-theme w-100">Döviz Kurunu Onayla</button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-warning w-100" onclick="getCurrencyLog();">Güncel Döviz Kurunu Getir</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Müşteri Teklifi (<span id="sw_customer_name"></span>)
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="sales-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0"></th>
                                    <th class="border-bottom-0">En Ucuz, En Hızlı</th>
                                    <th class="border-bottom-0 d-none">Offer ID</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
                                    <th class="border-bottom-0 d-none">Supplier ID</th>
                                    <th class="border-bottom-0">Tedarikçi</th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">Birim</th>
                                    <th class="border-bottom-0">Birim Fiyat</th>
                                    <th class="border-bottom-0">Toplam Fiyat</th>
                                    <th class="border-bottom-0">İndirim Oranı</th>
                                    <th class="border-bottom-0">İndirimli Fiyat</th>
                                    <th class="border-bottom-0">Vergi Oranı</th>
                                    <th class="border-bottom-0">Para Birimi</th>
                                    <th class="border-bottom-0">İstek Miktar</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0 d-none">Converted Price</th>
                                    <th class="border-bottom-0 d-none">Converted Currency</th>
                                </tr>
                                </thead>
                                <tbody id="sales-detail-body">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="20" class="border-bottom-0"></th>
                                </tr>
                                </tfoot>
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
                    <h1 class="page-header">
                        Tedarikçi Teklifleri
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="new-offer-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>

                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot></tfoot>
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

            <div class="row d-none">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="offer-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0"></th>
                                    <th class="border-bottom-0">En Ucuz, En Hızlı</th>
                                    <th class="border-bottom-0 d-none">Offer ID</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
                                    <th class="border-bottom-0 d-none">Supplier ID</th>
                                    <th class="border-bottom-0">Tedarikçi</th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">Birim</th>
                                    <th class="border-bottom-0">Birim Fiyat</th>
                                    <th class="border-bottom-0">Toplam Fiyat</th>
                                    <th class="border-bottom-0">İndirim Oranı</th>
                                    <th class="border-bottom-0">İndirimli Fiyat</th>
                                    <th class="border-bottom-0">Vergi Oranı</th>
                                    <th class="border-bottom-0">Para Birimi</th>
                                    <th class="border-bottom-0">İstek Miktar</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0 d-none">Converted Price</th>
                                    <th class="border-bottom-0 d-none">Converted Currency</th>
                                </tr>
                                </thead>
                                <tbody id="offer-detail-body">

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

@include('include.footer')
