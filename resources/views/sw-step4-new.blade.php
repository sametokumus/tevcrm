@include('include.header')
<?php
$extra_js='
<script src="services/sw-step4-new.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-0">

                            <div class="profile">

                                <div class="profile-container">

                                    <div class="profile-sidebar">
                                        <div class="desktop-sticky-top">
                                            <div class="profile-img">
                                                <img id="customer-logo" src="" alt="">
                                            </div>

                                            <h4 id="customer-name" class="mb-3"></h4>

                                            <div class="mb-1 text-inverse text-opacity-50 fw-bold mt-n2"> Yetkili</div>
                                            <div class="mb-1" id="employee-name">

                                            </div>
                                            <div class="mb-1" id="employee-phone">

                                            </div>
                                            <div class="mb-3" id="employee-email">

                                            </div>

                                            <hr class="mt-4 mb-4">

                                            <div class="mb-3" id="offer-price">

                                            </div>

                                            <div class="mb-3" id="supply-price">

                                            </div>

                                            <div class="mb-3" id="profit-price">

                                            </div>

                                            <div class="mb-3" id="profit-rate">

                                            </div>

                                            <hr class="mt-4 mb-4">

                                            <button type="button" class="btn d-block w-100 btn-theme fs-13px mb-1" onclick="approveOffer();">Teklifi Onayla</button>
                                            <button type="button" class="btn d-block w-100 btn-outline-default fs-13px mb-1" onclick="openRejectOfferModal();">Teklifi Reddet</button>

                                        </div>
                                    </div>


                                    <div class="profile-content">
                                        <div class="profile-content-container">
                                            <div class="row gx-4">
                                                <div class="col-xl-12">
                                                    <div id="offer-products" class="desktop-sticky-top d-none d-lg-block">

                                                        <div class="card mb-3">
                                                            <div class="list-group list-group-flush">
                                                                <div class="list-group-item fw-bold px-3 d-flex bg-theme text-white">
                                                                    <div class="flex-fill">
                                                                        Ürün: LNX-003   |   Tedarik Fiyatı: 13.806,00 TRY   |   Satış Fiyatı: 19.840,00 TRY   |   Kar: 6.034,00 TRY   |   Kar Oranı: %43,71   |   Teslimat Süresi: 14
                                                                    </div>
                                                                </div>
                                                                <div class="list-group-item px-3">
                                                                    <div class="table-responsive">
                                                                        <table id="offer-products-table-1" class="table table-striped table-borderless mb-2px small text-nowrap">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Tedarikçi</th>
                                                                                <th>Miktar</th>
                                                                                <th>Birim Fiyat</th>
                                                                                <th>Toplam Fiyat</th>
                                                                                <th>Satış Para Birimi Cinsinden</th>
                                                                                <th>Teslimat Süresi</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
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

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                    <th class="border-bottom-0">Tedarik Para Birimi</th>
                                    <th class="border-bottom-0">Tedarik Fiyatı (Satış para birimi cinsinden)</th>
                                    <th class="border-bottom-0">Teklif Birim Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Fiyatı</th>
                                    <th class="border-bottom-0">Teklif Para Birimi</th>
                                    <th class="border-bottom-0">Teklif Teslimat Süresi</th>
                                    <th class="border-bottom-0">Karlılık</th>
                                </tr>
                                </thead>
                                <tbody id="sales-detail-body">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="23" class="border-bottom-0"></th>
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


        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

<div class="modal modal-cover fade" id="addRejectOfferNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TEKLİFİN REDDEDİLME NEDENİ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_reject_offer_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea name="text" class="summernote" id="add_sale_note_description" title="Contents"></textarea>
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
