<?php
include('header.php');
$extra_js='
<script src="services/update-user.js"></script>
';
?>



            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Kullanıcı Görüntüle</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Kullanıcı</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <div class="row">

                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="#" id="update_order_payment_form">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Kullanıcı Bilgileri</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="row mb-4">
                                                                <div id="user_profile_image" style="width: 250px; height: 250px; background-size: cover;background-position: center center; background-repeat: no-repeat;">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Ad :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_name" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Soyad :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_surname" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Eposta :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_email" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Telefon :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_phone" required readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Doğum Tarihi :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_birthdate" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Cinsiyet :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_gender" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">TC :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_tc" required readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label class="col-md-3 form-label">Kayıt Tarihi :</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="user_register_date" required readonly>
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

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Siparişleri İncele</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="order-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                                <thead>
                                                <tr>
                                                    <th class="border-bottom-0" data-priority="1">Durum</th>
                                                    <th class="border-bottom-0">Sipariş Numarası</th>
                                                    <th class="border-bottom-0">Sipariş Tarihi</th>
                                                    <th class="border-bottom-0">Ödeme Yöntemi</th>
                                                    <th class="border-bottom-0">Tutar</th>
<!--                                                    <th class="border-bottom-0" data-priority="2">İşlem</th>-->
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
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

<?php include('footer.php'); ?>