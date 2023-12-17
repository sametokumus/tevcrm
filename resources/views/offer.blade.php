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
                <div class="col-md-8">
                    <h1 class="page-header">
                        Talep
                    </h1>
                </div>
                <div class="col-md-4">
                    <div class="btn-group float-end">
                        <b>Sipariş Durumunu Değiştir: &nbsp;</b>
                        <button type="button" class="btn btn-sm btn-pink" onclick="changeStatus();">Tedarikçi Fiyatları Girildi</button>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">
                            <table id="offer-request-products" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0">Satın Alma Notu</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0">Miktar</th>
                                    <th class="border-bottom-0">Birim</th>
                                    <th class="border-bottom-0 d-none">Product ID</th>
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

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        RFQ's
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3">
                            <table id="offers" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">Teklif Kodu</th>
                                    <th class="border-bottom-0">Tedarikçi Firma</th>
                                    <th class="border-bottom-0">Ürün Kalemi</th>
                                    <th class="border-bottom-0"></th>
                                </tr>
                                </thead>
                                <tbody id="offers-body">

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

<div class="modal modal-cover fade" id="offerRequestNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SİPARİŞ NOTU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <p id="show_offer_request_note"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                </div>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="addOfferModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF ALMAK İÇİN FİRMA SEÇİMİ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_offer_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tedarikçi</label>
                            <select name="add_offer_company[]" class="form-control form-select select2" multiple="multiple" id="add_offer_company" required>
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

<div class="modal modal-cover fade" id="updateProductNameModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ürün Adı Güncelleme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_product_name_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="update_product_name">
                            <input type="hidden" class="form-control" id="update_product_name_id">
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
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİF DETAYI (<span class="text-uppercase" id="offer_detail_show_supplier_name"> </span>)</h5>
                <button type="button" class="btn-close d-none" data-bs-dismiss="modal"></button>
                <button type="button" class="btn btn-sm btn-pink" onclick="$('.btn-close').click();">Fiyatlar Girildi</button>
            </div>
            <div class="modal-body overflow-auto">
                <input type="hidden" id="offer-detail-modal-offer-id">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <table id="offer-detail" class="table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                            <tr>
                                <th class="border-bottom-0">ID</th>
                                <th class="border-bottom-0">Ref. Code</th>
                                <th class="border-bottom-0">Ürün Adı</th>
                                <th class="border-bottom-0">Miktar</th>
                                <th class="border-bottom-0">Birim</th>
                                <th class="border-bottom-0">Birim Fiyat</th>
                                <th class="border-bottom-0">Toplam Fiyat</th>
                                <th class="border-bottom-0">İndirim Oranı</th>
                                <th class="border-bottom-0">İndirimli Fiyat</th>
                                <th class="border-bottom-0">Para Birimi</th>
                                <th class="border-bottom-0">Teslimat Süresi</th>
                                <th class="border-bottom-0"></th>
                            </tr>
                            </thead>
                            <tbody id="offer-detail-body">

                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Vergi Oranı</label>
                            <input type="text" class="form-control" id="add_offer_product_vat_rate">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="add_offer_product_currency">
                                <option value="TRY">TRY</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Teslimat Süresi (Gün)</label>
                            <input type="text" class="form-control" id="add_offer_product_lead_time">
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Vergi Oranı</label>
                            <input type="text" class="form-control" id="update_offer_product_vat_rate">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Para Birimi</label>
                            <select class="form-control" id="update_offer_product_currency" required>
                                <option value="TRY">TRY</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Teslimat Süresi (Gün)</label>
                            <input type="text" class="form-control" id="update_offer_product_lead_time">
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


<div class="modal modal-cover fade" id="sendSupplierMailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEDARİKÇİLERE MAİL GÖNDER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="send_supplier_mail_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Şablon</label>
                            <div class="col-9">
                                <select class="form-control" id="send_mail_layouts" required>

                                </select>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-warning" onclick="setMailLayout();">Şablonu Kullan</button>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Gönderici</label>
                            <select class="form-control" id="send_mail_staff" required>

                            </select>
                            <input type="hidden" class="form-control" id="mail_request_id">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alıcı</label>
                            <ul id="send_mail_to_address" class="tagit form-control">

                            </ul>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Konu</label>
                            <input type="text" class="form-control" id="send_mail_subject" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Mesajınız</label>
                            <textarea name="text" class="summernote" id="send_mail_text" title="Contents"></textarea>
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
