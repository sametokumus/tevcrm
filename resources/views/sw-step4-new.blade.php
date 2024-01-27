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

                                            <h4 id="customer-name" class="mb-1"></h4>
                                            <h4 id="sale-global-id" class="mb-3"></h4>

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
                                            <button type="button" class="btn d-block w-100 btn-danger fs-13px mb-1" onclick="openRejectOfferModal();">Teklifi Reddet</button>

                                        </div>
                                    </div>


                                    <div class="profile-content">
                                        <div class="profile-content-container">
                                            <div class="row gx-4">
                                                <div class="col-xl-12">
                                                    <div id="offer-products" class="desktop-sticky-top d-none d-lg-block">



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
