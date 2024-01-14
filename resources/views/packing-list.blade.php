@include('include.header')
<?php
$extra_js='
<script src="services/packing-list.js"></script>
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
                        Gönderi Listeleri
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="packing-lists" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0">Ürün Kalemi</th>
                                    <th class="border-bottom-0">Durum</th>
                                    <th class="border-bottom-0">Sevkiyat Adresi</th>
                                    <th class="border-bottom-0"></th>
                                </tr>
                                </thead>
                                <tbody>

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
                        Gönderi Listesi
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="packing-list-detail" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0"></th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0 d-none">Gönderilen Adet</th>
                                    <th class="border-bottom-0 d-none">Kalan Adet</th>
                                    <th class="border-bottom-0">Gönderi Adedi</th>
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
                    <h1 class="page-header">
                        Gönderilmeyen Ürünler
                    </h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card border-theme mb-3">
                        <div class="card-body p-3 overflow-auto">
                            <table id="packingable-list-detail" class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">N#</th>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0"></th>
                                    <th class="border-bottom-0">Ürün Adı</th>
                                    <th class="border-bottom-0">Ref. Code</th>
                                    <th class="border-bottom-0">Teslimat Süresi</th>
                                    <th class="border-bottom-0">Teklif Miktar</th>
                                    <th class="border-bottom-0">Gönderilen Adet</th>
                                    <th class="border-bottom-0">Kalan Adet</th>
                                    <th class="border-bottom-0 d-none">Gönderi Adedi</th>
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

<div class="modal modal-cover fade" id="addProductCountModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color: #d0d6dd;">
            <div class="modal-header">
                <h5 class="modal-title">Gönderi Adedi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_product_count_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Gönderi Adedi</label>
                            <input type="number" class="form-control" id="add_product_count">
                            <input type="hidden" class="form-control" id="add_product_id">
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

<div class="modal modal-cover fade" id="updateStatusModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DURUM GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_status_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Durum :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_packing_list_id" required>
                            <select name="update_packing_status" id="update_packing_status" class="form-control form-control-md" required>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="addPackingAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADRES EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_packing_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="add_packing_address_packing_list_id" required>
                            <select name="add_packing_address_addresses" id="add_packing_address_addresses" class="form-control form-control-md" required>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-cover fade" id="updatePackingAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADRES GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_packing_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Adres :</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" id="update_packing_address_packing_list_id" required>
                            <select name="update_packing_address_addresses" id="update_packing_address_addresses" class="form-control form-control-md" required>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('include.footer')
