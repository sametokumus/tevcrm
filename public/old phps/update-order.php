<?php
include('header.php');
$extra_js='
<script src="plugins/treeview/treeview.js"></script>
<!-- GALLERY JS -->
<script src="plugins/gallery/picturefill.js"></script>
<script src="plugins/gallery/lightgallery.js"></script>
<script src="plugins/gallery/lg-pager.js"></script>
<script src="plugins/gallery/lg-autoplay.js"></script>
<script src="plugins/gallery/lg-fullscreen.js"></script>
<script src="plugins/gallery/lg-zoom.js"></script>
<script src="plugins/gallery/lg-hash.js"></script>
<script src="plugins/gallery/lg-share.js"></script>

<script src="services/update-order.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Sipariş Güncelle</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Sipariş</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <div class="row">

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_payment_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ödeme Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ödeme :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_payment_type" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Taksit :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_installment" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Banka :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_bank" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">İşlem Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_transaction_id" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Kart :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_card_name" required readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Sipariş Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Sipariş Durumu :</label>
                                                                <div class="col-md-9">
                                                                    <select name="order_status" class="form-control form-select" id="order_status" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Teslimat Türü :</label>
                                                                <div class="col-md-9">
                                                                    <select name="order_shipping_type" class="form-control form-select" id="order_shipping_type" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Sipariş No :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_number" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Sipariş ID :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_id" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Sipariş Tarihi :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_date" required readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Kaydet</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_user_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Kullanıcı Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ad Soyad :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_user_name" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">E-posta :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_user_email" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Telefon :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_user_phone" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">TC Kimlik Numarası :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_user_tc" required readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_shipment_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Kargo Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Firma :</label>
                                                                <div class="col-md-9">
                                                                    <select name="carrier" class="form-control form-select" id="order_carrier" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Gönderi Takip Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_number">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Kaydet</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_billing_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Fatura Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ad Soyad :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Telefon :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_phone" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Adres :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_address" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Posta Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_postal_code" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">İlçe :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_district" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">İl :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_city" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ülke :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_invoice_country" required>
                                                                </div>
                                                            </div>
                                                            <div class="corporate">
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Firma Adı :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_invoice_company_name">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Vergi Dairesi :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_invoice_tax_office">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Vergi Numarası :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_invoice_tax_number">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Kaydet</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_shipping_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Teslimat Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ad Soyad :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Telefon :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_phone" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Adres :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_address" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Posta Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_postal_code" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">İlçe :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_district" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">İl :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_city" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ülke :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="order_shipping_country" required>
                                                                </div>
                                                            </div>
                                                            <div class="corporate">
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Firma Adı :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_shipping_company_name">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Vergi Dairesi :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_shipping_tax_office">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label class="col-md-3 form-label">Vergi Numarası :</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control" id="order_shipping_tax_number">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Kaydet</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_shipping_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive push">
                                                        <table class="table table-bordered table-hover mb-0 text-nowrap" id="order-detail-table">
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
<!--                                                            <button type="button" class="btn btn-danger mb-1" onclick="javascript:window.print();"><i class="si si-printer"></i> Ürünleri Yazdır</button>-->
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>



                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->


    <div class="modal fade" id="updateProductTabModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_product_tab_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Açıklama Sekmesi Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Sekme Açıklaması :</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" id="update_product_tab_name" required>
                                <textarea class="tinyMce" id="update_product_tab_text"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="updateProductVariationModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="update_product_variation_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Varyasyon Güncelle</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-7">

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Varyasyon Grubu :</label>
                                    <div class="col-md-9">
                                        <select name="update_variation_product_variation_group" class="form-control form-select" id="update_variation_product_variation_group" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Varyasyon Stok Kodu :</label>
                                    <div class="col-md-9">
                                        <input type="hidden" class="form-control" id="update_product_variation_id" required>
                                        <input type="text" class="form-control" id="update_product_variation_sku" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Varyasyon Adı :</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="update_product_variation_name" required>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label class="col-md-3 form-label mb-4">Varyasyon Açıklama :</label>
                                    <div class="col-md-9 mb-4">
                                        <textarea class="tinyMce" id="update_product_variation_description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Adet(Maks) :</label>
                                        <input type="number" class="form-control" id="update_product_variation_quantity_stock" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Adet(Min) :</label>
                                        <input type="number" class="form-control" id="update_product_variation_quantity_min" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Adet(Step) :</label>
                                        <input type="number" class="form-control" id="update_product_variation_quantity_step" required>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label class="col-md-6 form-label">Ücretsiz Kargo :</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox-md mb-0">
                                                <input type="checkbox" class="custom-control-input" id="update_product_variation_is_free_shipping" value="1">
                                                <span class="custom-control-label"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6">
                                        <label class="form-label">İndirim Oranı :</label>
                                        <input type="text" class="form-control" id="update_product_variation_discount_rate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Vergi Oranı :</label>
                                        <input type="text" class="form-control" id="update_product_variation_tax_rate" required>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6">
                                        <label class="form-label">Normal Fiyat :</label>
                                        <input type="text" class="form-control" id="update_product_variation_regular_price" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Normal Fiyat Vergi :</label>
                                        <input type="text" class="form-control" id="update_product_variation_regular_tax" required>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6">
                                        <label class="form-label">İndirimli Fiyat :</label>
                                        <input type="text" class="form-control" id="update_product_variation_discounted_price" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">İndirimli Fiyat Vergi :</label>
                                        <input type="text" class="form-control" id="update_product_variation_discounted_tax" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include('footer.php'); ?>