@include('include.header')
<?php
$extra_js='
<script src="services/sw-step1.js"></script>
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
                            <a class="nav-link active" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Step 1</div>
                                <div class="nav-text">Firma / İstek Seçimi</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link disabled" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Step 2</div>
                                <div class="nav-text">Teklif Oluşturma</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link disabled" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Step 3</div>
                                <div class="nav-text">Fiyat Güncelleme</div>
                            </a>
                        </div>
                        <div class="nav-item col">
                            <a class="nav-link disabled" href="#">
                                <div class="nav-dot"></div>
                                <div class="nav-title">Step 4</div>
                                <div class="nav-text">Proforma Görüntüleme</div>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tedarikçi</label>
                                    <select class="form-control" id="select_sales_company">

                                    </select>
                                </div>
                            </div>
                            <table id="offer-request-products" class="table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ID</th>
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

<div class="modal modal-cover fade" id="offerDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF DETAYI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="offer-detail-modal-offer-id">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <table id="offer-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                            <tr>
                                <th class="border-bottom-0">ID</th>
                                <th class="border-bottom-0">Ref. Code</th>
                                <th class="border-bottom-0">DC</th>
                                <th class="border-bottom-0">Paketleme</th>
                                <th class="border-bottom-0">Adet</th>
                                <th class="border-bottom-0">Birim Fiyat</th>
                                <th class="border-bottom-0">Toplam Fiyat</th>
                                <th class="border-bottom-0">İndirim Oranı</th>
                                <th class="border-bottom-0">İndirimli Fiyat</th>
                                <th class="border-bottom-0"></th>
                            </tr>
                            </thead>
                            <tbody id="offer-detail-body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-outline-theme">Kaydet</button>
            </div>
        </div>
    </div>
</div>



<div class="modal modal-cover fade" id="addOfferProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF ÜRÜN EKLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_offer_product_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ref. Code</label>
                            <input type="text" class="form-control" id="add_offer_product_ref_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="add_offer_product_product_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Date Code</label>
                            <input type="text" class="form-control" id="add_offer_product_date_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Paketleme</label>
                            <input type="text" class="form-control" id="add_offer_product_package_type">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adet</label>
                            <input type="text" class="form-control" id="add_offer_product_quantity">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Birim Fiyat</label>
                            <input type="text" class="form-control" id="add_offer_product_pcs_price">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Toplam Fiyat</label>
                            <input type="text" class="form-control" id="add_offer_product_total_price">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İndirim Oranı</label>
                            <input type="text" class="form-control" id="add_offer_product_discount_rate" placeholder="%">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İndirimli Fiyat</label>
                            <input type="text" class="form-control" id="add_offer_product_discounted_price">
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

<div class="modal modal-cover fade" id="updateOfferProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF ÜRÜN GÜNCELLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_offer_product_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ref. Code</label>
                            <input type="text" class="form-control" id="update_offer_product_ref_code" readonly>
                            <input type="hidden" class="form-control" id="update_offer_product_id">
                            <input type="hidden" class="form-control" id="update_offer_id">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="update_offer_product_product_name" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Date Code</label>
                            <input type="text" class="form-control" id="update_offer_product_date_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Paketleme</label>
                            <input type="text" class="form-control" id="update_offer_product_package_type">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adet</label>
                            <input type="text" class="form-control" id="update_offer_product_quantity">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Birim Fiyat</label>
                            <input type="text" class="form-control" id="update_offer_product_pcs_price">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Toplam Fiyat</label>
                            <input type="text" class="form-control" id="update_offer_product_total_price">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İndirim Oranı</label>
                            <input type="text" class="form-control" id="update_offer_product_discount_rate" placeholder="%">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">İndirimli Fiyat</label>
                            <input type="text" class="form-control" id="update_offer_product_discounted_price">
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
