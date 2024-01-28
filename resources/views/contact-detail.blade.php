@include('include.header')
<?php
$extra_js='
<script src="services/contact-detail.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
$(".timepicker").timepicker({
    minuteStep: 15,
    showMeridian: false,
    template: false
});
</script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row" id="company-detail-group">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-tabs-v2">
                        <li class="nav-item me-1"><a href="#contact-detail" class="nav-link active" data-bs-toggle="tab">Firma Bilgileri</a></li>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="contact-detail">

                            <form method="post" action="#" id="update_contact_form">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Firma Adı</label>
                                        <input type="text" class="form-control" id="update_contact_name" placeholder="Firma Adı" required>
                                        <input type="hidden" class="form-control" id="update_contact_id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Yetkili Adı</label>
                                        <input type="text" class="form-control" id="update_contact_authorized_name" placeholder="Yetkili Adı">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Eposta</label>
                                        <input type="text" class="form-control" id="update_contact_email" placeholder="Eposta" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control" id="update_contact_phone" placeholder="Telefon" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Adres</label>
                                        <input type="text" class="form-control" id="update_contact_address" placeholder="Adres">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Vergi No</label>
                                        <input type="text" class="form-control" id="update_contact_registration_no" placeholder="Vergi No">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Vergi Dairesi</label>
                                        <input type="text" class="form-control" id="update_contact_registration_office" placeholder="Vergi Dairesi">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kısa Kod</label>
                                        <input type="text" class="form-control" id="update_contact_short_code" placeholder="Kısa Kod">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Logo <a href="#" id="update_contact_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                                        <input type="file" class="form-control" id="update_contact_logo" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Fatura vb. Sayfa Sonu Görseli <a href="#" id="update_contact_current_footer" target="_blank">'ni görüntülemek için tıklayınız...</a></label>
                                        <input type="file" class="form-control" id="update_contact_footer" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Yetkili İmzası <a href="#" id="update_contact_current_signature" target="_blank">'nı görüntülemek için tıklayınız...</a></label>
                                        <input type="file" class="form-control" id="update_contact_signature" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-outline-theme float-end">Kaydet</button>
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



@include('include.footer')
