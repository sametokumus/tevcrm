@include('include.header')
<?php
$extra_js='
<script src="/services/customer-detail.js"></script>
';
?>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title" id="customer-name">

                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Müşteri</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-6">
                <div class="header-nav">
                    <div class="btn-list">
                        <button class="btn btn-primary" onclick="openAddAddressModal();"><span class="fe fe-plus"></span> Adres Ekle</button>
                        <button class="btn btn-primary" onclick="openAddContactModal();"><span class="fe fe-plus"></span> Yetkili Ekle</button>
                    </div>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Adresler</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="address-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0" data-priority="1">#</th>
                                        <th class="border-bottom-0" data-priority="2">Adres Adı</th>
                                        <th class="border-bottom-0">Adres</th>
                                        <th class="border-bottom-0">Telefon</th>
                                        <th class="border-bottom-0">Faks</th>
                                        <th class="border-bottom-0">İşlem</th>
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
            <!-- End Row -->



        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->



<div class="modal fade" id="updateCustomerModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_customer_form">
                <div class="modal-header">
                    <h5 class="modal-title">Müşteri Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Müşteri Adı :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_customer_id" required>
                            <input type="text" class="form-control" id="update_customer_name" required>
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
<div class="modal fade" id="addAddressModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="add_address_form">
                <div class="modal-header">
                    <h5 class="modal-title">Adres Ekle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres Adı :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ülke :</label>
                        <div class="col-md-9">
                            <select name="brand" class="form-control form-select" id="add_address_country" required>
                                <option>Ülke Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Şehir :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="add_address_city" required>
                                <option>Şehir Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Faks :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_address_fax" required>
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
<div class="modal fade" id="updateAddressModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="update_address_form">
                <div class="modal-header">
                    <h5 class="modal-title">Adres Güncelle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres Adı :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_address_id" required>
                            <input type="text" class="form-control" id="update_address_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ülke :</label>
                        <div class="col-md-9">
                            <select name="brand" class="form-control form-select" id="update_address_country" required>
                                <option>Ülke Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Şehir :</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control form-select" id="update_address_city" required>
                                <option>Şehir Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Faks :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="update_address_fax" required>
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
<div class="modal fade" id="addContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form method="post" action="#" id="add_contact_form">
                <div class="modal-header">
                    <h5 class="modal-title">Yetkili Ekle</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Görev :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_title" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Ad Soyad :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_name" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Telefon :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_phone" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">E-posta :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="add_contact_email" required>
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

@include('include.footer')
