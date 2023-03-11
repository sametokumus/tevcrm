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

<script src="services/update-product.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Ürün Güncelle</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ürün</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <div class="row">

                            <div class="col-md-9">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_product_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label class="col-md-6 form-label">Tüm Varyasyon Görsellerini Göster :</label>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="custom-control custom-checkbox-md mb-0">
                                                                                    <input type="checkbox" class="custom-control-input" id="view_all_images" value="1">
                                                                                    <span class="custom-control-label"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label class="col-md-6 form-label">Ücretsiz Kargo :</label>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="custom-control custom-checkbox-md mb-0">
                                                                                    <input type="checkbox" class="custom-control-input" id="is_free_shipping" value="1">
                                                                                    <span class="custom-control-label"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Stok Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_sku" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Marka :</label>
                                                                <div class="col-md-9">
                                                                    <select name="brand" class="form-control form-select" id="product_brand" required>
                                                                        <option>Marka Seçiniz</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Tür :</label>
                                                                <div class="col-md-9">
                                                                    <select name="type" class="form-control form-select" id="product_type" required>
                                                                        <option>Tür Seçiniz</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ürün Adı :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label mb-4">Ürün Açıklaması :</label>
                                                                <div class="col-md-9 mb-4">
                                                                    <textarea class="tinyMce" id="product_description"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Güncelle</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_product_seo_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün SEO Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Title :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_seo_title">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Keywords :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_seo_keywords">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Description :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_seo_description">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Arama Kelimeleri :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_seo_search">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Güncelle</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="add_product_tab_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün Açıklama Sekmesi Ekle</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Sekme :</label>
                                                                <div class="col-md-9">
                                                                    <select name="add_product_tab_name" class="form-control form-select" id="add_product_tab_name" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <label class="col-md-3 form-label mb-4">Sekme Açıklaması :</label>
                                                                <div class="col-md-9 mb-4">
                                                                    <textarea class="tinyMce" id="add_product_tab_text"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Ekle</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Ürün Varyasyon Grupları
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <div class="tags" id="product_variation_groups">
                                                        </div>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Ürün Varyasyonları
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <div class="tags" id="product_variations">
                                                        </div>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="add_product_variation_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün Varyasyon Ekle</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-7">

                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Varyasyon Grubu :</label>
                                                                <div class="col-md-9">
                                                                    <select name="add_variation_product_variation_group" class="form-control form-select" id="add_variation_product_variation_group" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Varyasyon Stok Kodu :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_variation_sku" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Varyasyon Adı :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="product_variation_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <label class="col-md-3 form-label mb-4">Varyasyon Açıklama :</label>
                                                                <div class="col-md-9 mb-4">
                                                                    <textarea class="tinyMce" id="product_variation_description"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">

                                                            <div class="row mb-4">
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Adet(Maks) :</label>
                                                                    <input type="number" class="form-control" id="product_variation_quantity_stock" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Adet(Min) :</label>
                                                                    <input type="number" class="form-control" id="product_variation_quantity_min" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Adet(Step) :</label>
                                                                    <input type="number" class="form-control" id="product_variation_quantity_step" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <label class="col-md-6 form-label">Ücretsiz Kargo :</label>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="custom-control custom-checkbox-md mb-0">
                                                                            <input type="checkbox" class="custom-control-input" id="product_variation_is_free_shipping" value="1">
                                                                            <span class="custom-control-label"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">İndirim Oranı :</label>
                                                                    <input type="text" class="form-control" id="product_variation_discount_rate" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Vergi Oranı :</label>
                                                                    <input type="text" class="form-control" id="product_variation_tax_rate" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Normal Fiyat :</label>
                                                                    <input type="text" class="form-control" id="product_variation_regular_price" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Normal Fiyat Vergi :</label>
                                                                    <input type="text" class="form-control" id="product_variation_regular_tax" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-0">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">İndirimli Fiyat :</label>
                                                                    <input type="text" class="form-control" id="product_variation_discounted_price" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">İndirimli Fiyat Vergi :</label>
                                                                    <input type="text" class="form-control" id="product_variation_discounted_tax" required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Ekle</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="add_product_variation_image_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Ürün Varyasyon Görseli Ekle</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Varyasyon :</label>
                                                                <div class="col-md-9">
                                                                    <select name="add_variation_image_variation_name" class="form-control form-select" id="add_variation_image_variation_name" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <label class="col-md-3 form-label">Varyasyon Görseli :</label>
                                                                <div class="col-md-9">
                                                                    <input class="form-control" type="file" id="add_variation_image_upload_file" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <!--Row-->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary float-end">Ekle</button>
                                                        </div>
                                                    </div>
                                                    <!--End Row-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Ürün Varyasyon Görselleri
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <div id="product_variation_images">
                                                        </div>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Kategoriler
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <ul id="product_category_view">

                                                        </ul>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Etiketler
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <div class="tags" id="product_tags">

                                                        </div>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Dökümanlar
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="post" action="#" id="add_product_document_form">
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <input class="form-control" type="text" id="upload_document_name" placeholder="Döküman Adı" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <input class="form-control" type="file" id="upload_document" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary btn-block">Yükle</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- col -->
                                                    <div class="col-lg-12">
                                                        <div class="product_documents" id="product_documents">

                                                        </div>
                                                    </div>
                                                    <!-- /col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Açıklama Sekmesi Ekle
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="post" action="#" id="add_tab_form">
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <input class="form-control" type="text" id="add_tab_name" placeholder="Sekme Adı" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary btn-block">Ekle</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Ürün Açıklama Sekmesi Güncelle
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="post" action="#" id="select_product_tab_form">
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <select name="select_product_tab_name" class="form-control form-select" id="select_product_tab_name" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary">Güncelle</button>
                                                                    <button type="button" onclick="deleteProductTab()" class="btn btn-default float-end">Sil</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Ürün Varyasyon Grup Ekle
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="post" action="#" id="add_product_variation_group_form">
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <input class="form-control" type="number" id="order_product_variation_group" placeholder="Sıra" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <select name="select_product_variation_group" class="form-control form-select" id="select_product_variation_group" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary">Ekle</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    Öne Çıkan Varyasyon Seç
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <form method="post" action="#" id="update_product_featured_variation_form">
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <select name="select_product_featured_variation" class="form-control form-select" id="select_product_featured_variation" required>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary">Güncelle</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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

    <div class="modal fade" id="deleteProductVariationModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <form method="post" action="#" id="delete_product_variation_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Varyasyon Sil</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" id="delete_product_variation_id" required>
                                <h6>Ürün varyasyonunu silmek istediğinize emin misiniz?</h6>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</a>
                        <button type="submit" class="btn btn-danger">Sil</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include('footer.php'); ?>