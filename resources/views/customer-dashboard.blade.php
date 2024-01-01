@include('include.header')
<?php
$extra_js='
<script src="services/customer-dashboard.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
$(".timepicker").timepicker({
    minuteStep: 15,
    showMeridian: false
});
</script>
';
?>

<div id="content" class="app-content">

    <div class="card">
        <div class="card-body p-0">

            <div class="profile">

                <div class="profile-container">

                    <div class="profile-sidebar">
                        <div class="desktop-sticky-top">
                            <div id="sidebar-info">

                            </div>
                        </div>
                    </div>


                    <div class="profile-content">
                        <ul class="profile-tab nav nav-tabs nav-tabs-v2">
                            <li class="nav-item">
                                <a href="#info-tab" class="nav-link active" data-bs-toggle="tab">
                                    <div class="nav-value">Firma Bilgileri</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#employees-tab" class="nav-link" data-bs-toggle="tab">
                                    <div class="nav-value">Yetkililer</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#notes-tab" class="nav-link" data-bs-toggle="tab">
                                    <div class="nav-value">Notlar</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#activities-tab" class="nav-link" data-bs-toggle="tab">
                                    <div class="nav-value">Aktiviteler</div>
                                </a>
                            </li>
                        </ul>
                        <div class="profile-content-container">
                            <div class="row gx-4">
                                <div class="col-xl-8">
                                    <div class="tab-content p-0">

                                        <div class="tab-pane fade show active" id="info-tab">
                                            <div class="card mb-3">
                                                <div class="card-body">

                                                    <form method="post" action="#" id="update_company_form">
                                                        <div class="row mb-4">
                                                            <div class="col-md-12 mb-3">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" value="" id="update_company_is_potential_customer" />
                                                                    <label class="form-check-label" for="update_company_is_potential_customer">Potansiyel Müşteri</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" value="" id="update_company_is_customer" />
                                                                    <label class="form-check-label" for="update_company_is_customer">Müşteri</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" value="" id="update_company_is_supplier" />
                                                                    <label class="form-check-label" for="update_company_is_supplier">Tedarikçi</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Firma Adı</label>
                                                                <input type="text" class="form-control" id="update_company_name" placeholder="Firma Adı" required>
                                                                <input type="hidden" class="form-control" id="update_company_id" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Eposta</label>
                                                                <input type="text" class="form-control" id="update_company_email" placeholder="Eposta" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Website</label>
                                                                <input type="text" class="form-control" id="update_company_website" placeholder="Website">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Telefon</label>
                                                                <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Faks</label>
                                                                <input type="text" class="form-control" id="update_company_fax" placeholder="Faks">
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Adres</label>
                                                                <input type="text" class="form-control" id="update_company_address" placeholder="Adres">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Ülke</label>
                                                                <select class="form-control" id="update_company_country">

                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Registration Number</label>
                                                                <input type="text" class="form-control" id="update_company_registration_number" placeholder="Registration Number">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Vergi Dairesi</label>
                                                                <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Vergi Numarası</label>
                                                                <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">LinkedIn</label>
                                                                <input type="text" class="form-control" id="update_company_linkedin" placeholder="LinkedIn">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Skype</label>
                                                                <input type="text" class="form-control" id="update_company_skype" placeholder="Skype">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Payment Term</label>
                                                                <select class="form-control" id="update_company_payment_term">

                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Diğer</label>
                                                                <input type="text" class="form-control" id="update_company_online" placeholder="Diğer">
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label">Logo <a href="#" id="update_company_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                                                                <input type="file" class="form-control" id="update_company_logo" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <button type="submit" class="btn btn-outline-theme float-end">Kaydet</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                                <div class="card-arrow">
                                                    <div class="card-arrow-top-left"></div>
                                                    <div class="card-arrow-top-right"></div>
                                                    <div class="card-arrow-bottom-left"></div>
                                                    <div class="card-arrow-bottom-right"></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="employees-tab">
                                            <div class="list-group">
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-1.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Ethel
                                                                Wilkes</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">North
                                                            Raundspic
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-2.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Shanaya
                                                                Hansen</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">North
                                                            Raundspic
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-3.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">James
                                                                Allman</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">North
                                                            Raundspic
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-4.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Marie
                                                                Welsh</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">
                                                            Crencheporford
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-5.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Lamar
                                                                Kirkland</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Prince
                                                            Ewoodswan
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-6.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Bentley
                                                                Osborne</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Red
                                                            Suvern
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-7.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Ollie
                                                                Goulding</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Doa</div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-8.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Hiba
                                                                Calvert</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Stemunds
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-9.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Rivka
                                                                Redfern</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Fallnee
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                                <div class="list-group-item d-flex align-items-center">
                                                    <img src="img/user/user-10.jpg" alt width="50"
                                                         class="rounded-sm ms-n2">
                                                    <div class="flex-fill px-3">
                                                        <div><a href="#"
                                                                class="text-inverse fw-bold text-decoration-none">Roshni
                                                                Fernandez</a></div>
                                                        <div class="text-inverse text-opacity-50 fs-13px">Mount
                                                            Lerdo
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn btn-outline-theme">Follow</a>
                                                </div>
                                            </div>
                                            <div class="text-center p-3"><a href="#"
                                                                            class="text-inverse text-decoration-none">Show
                                                    more <b class="caret"></b></a></div>
                                        </div>


                                        <div class="tab-pane fade" id="notes-tab">
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold bg-transparent">May 20</div>
                                                <div class="card-body">
                                                    <div class="widget-img-list">
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-1.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-1.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-2.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-2.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-3.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-3.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-4.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-4.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-5.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-5.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-6.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-6.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-7.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-7.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-8.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-8.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-9.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-9.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-10.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-10.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-11.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-11.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-12.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-12.jpg)"></span></a>
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
                                            <div class="card">
                                                <div class="card-header fw-bold bg-transparent">May 16</div>
                                                <div class="card-body">
                                                    <div class="widget-img-list">
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-13.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-13.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-14.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-14.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-15.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-15.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-16.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-16.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-17.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-17.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-18.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-18.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-19.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-19.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-20.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-20.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-21.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-21.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-22.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-22.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-23.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-23.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-24.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-24.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-25.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-25.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-26.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-26.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-27.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-27.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-28.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-28.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-29.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-29.jpg)"></span></a>
                                                        </div>
                                                        <div class="widget-img-list-item"><a
                                                                href="img/gallery/gallery-30.jpg"
                                                                data-lity><span class="img"
                                                                                style="background-image: url(img/gallery/gallery-30.jpg)"></span></a>
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
                                            <div class="text-center p-3"><a href="#"
                                                                            class="text-inverse text-decoration-none">Show
                                                    more <b class="caret"></b></a></div>
                                        </div>


                                        <div class="tab-pane fade" id="activities-tab">
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold bg-transparent">Collections #1</div>
                                                <div class="card-body">
                                                    <div class="row gx-1">
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=RQ5ljyGg-ig"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/RQ5ljyGg-ig/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=5lWkZ-JaEOc"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/5lWkZ-JaEOc/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=9ZfN87gSjvI"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/9ZfN87gSjvI/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=w2H07DRv2_M"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/w2H07DRv2_M/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=PntG8KEVjR8"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/PntG8KEVjR8/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=q8kxKvSQ7MI"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/q8kxKvSQ7MI/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=cutu3Bw4ep4"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/cutu3Bw4ep4/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=gCspUXGrraM"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/gCspUXGrraM/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
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
                                            <div class="card mb-3">
                                                <div class="card-header fw-bold bg-transparent">Collections #2</div>
                                                <div class="card-body">
                                                    <div class="row gx-1">
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=COtpTM1MpAA"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/COtpTM1MpAA/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=8NVkGHVOazc"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/8NVkGHVOazc/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=QgQ7MWLsw1w"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/QgQ7MWLsw1w/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=Dmw0ucCv8aQ"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/Dmw0ucCv8aQ/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=r1d7ST2TG2U"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/r1d7ST2TG2U/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=WUR-XWBcHvs"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/WUR-XWBcHvs/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=A7sQ8RWj0Cw"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/A7sQ8RWj0Cw/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 mb-1">
                                                            <a href="https://www.youtube.com/watch?v=IMN2VfiXls4"
                                                               data-lity>
                                                                <img src="../../img.youtube.com/vi/IMN2VfiXls4/mqdefault.jpg"
                                                                     alt class="d-block w-100">
                                                            </a>
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
                                <div class="col-xl-4">
                                    <div class="desktop-sticky-top d-none d-lg-block">
                                        <div class="card mb-3">
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item fw-bold px-3 d-flex">
                                                    <span class="flex-fill">Trends for you</span>
                                                    <a href="#" class="text-inverse text-opacity-50"><i
                                                            class="fa fa-cog"></i></a>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending
                                                                Worldwide</strong></small></div>
                                                    <div class="fw-bold mb-2">#BreakingNews</div>
                                                    <a href="#" class="card text-inverse text-decoration-none mb-1">
                                                        <div class="card-body">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-8">
                                                                    <div class="small text-inverse text-opacity-50 mb-1 mt-n1">
                                                                        Space
                                                                    </div>
                                                                    <div class="h-40px fs-13px overflow-hidden mb-n1">
                                                                        Distant star explosion is brightest ever
                                                                        seen, study finds
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 d-flex">
                                                                    <div class="h-100 w-100"
                                                                         style="background: url(img/gallery/news-1.jpg) center; background-size: cover;"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-arrow">
                                                            <div class="card-arrow-top-left"></div>
                                                            <div class="card-arrow-top-right"></div>
                                                            <div class="card-arrow-bottom-left"></div>
                                                            <div class="card-arrow-bottom-right"></div>
                                                        </div>
                                                    </a>
                                                    <div><small class="text-inverse text-opacity-50">1.89m
                                                            share</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="fw-bold mb-2">#TrollingForGood</div>
                                                    <div class="fs-13px mb-1">Be a good Troll and spread some
                                                        positivity on HUD today.
                                                    </div>
                                                    <div><small class="text-inverse text-opacity-50"><i
                                                                class="fa fa-external-link-square-alt"></i> Promoted by
                                                            HUD Trolls</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending
                                                                Worldwide</strong></small></div>
                                                    <div class="fw-bold mb-2">#CronaOutbreak</div>
                                                    <div class="fs-13px mb-1">The coronavirus is affecting 210
                                                        countries around the world and 2 ...
                                                    </div>
                                                    <div><small class="text-inverse text-opacity-50">49.3m
                                                            share</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending
                                                                in New York</strong></small></div>
                                                    <div class="fw-bold mb-2">#CoronavirusPandemic</div>
                                                    <a href="#" class="card mb-1 text-inverse text-decoration-none">
                                                        <div class="card-body">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-8">
                                                                    <div class="fs-12px text-inverse text-opacity-50 mt-n1">
                                                                        Coronavirus
                                                                    </div>
                                                                    <div class="h-40px fs-13px overflow-hidden mb-n1">
                                                                        Coronavirus: US suspends travel from Europe
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 d-flex">
                                                                    <div class="h-100 w-100"
                                                                         style="background: url(img/gallery/news-2.jpg) center; background-size: cover;"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-arrow">
                                                            <div class="card-arrow-top-left"></div>
                                                            <div class="card-arrow-top-right"></div>
                                                            <div class="card-arrow-bottom-left"></div>
                                                            <div class="card-arrow-bottom-right"></div>
                                                        </div>
                                                    </a>
                                                    <div><small class="text-inverse text-opacity-50">821k
                                                            share</small></div>
                                                </div>
                                                <a href="#" class="list-group-item list-group-action text-center">
                                                    Show more
                                                </a>
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

<div class="modal modal-cover fade" id="addCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_company_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_potential_customer" />
                                <label class="form-check-label" for="add_company_is_potential_customer">Potansiyel Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_customer" />
                                <label class="form-check-label" for="add_company_is_customer">Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="add_company_is_supplier" />
                                <label class="form-check-label" for="add_company_is_supplier">Tedarikçi</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_company_name" placeholder="Firma Adı" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_email" placeholder="Eposta" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_website" placeholder="Website" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_phone" placeholder="Telefon" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_fax" placeholder="Faks" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_company_address" placeholder="Adres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_tax_office" placeholder="Vergi Dairesi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_company_tax_number" placeholder="Vergi Numarası" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" id="add_company_logo" />
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

<div class="modal modal-cover fade" id="updateCompanyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_company_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_potential_customer" />
                                <label class="form-check-label" for="update_company_is_potential_customer">Potansiyel Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_customer" />
                                <label class="form-check-label" for="update_company_is_customer">Müşteri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="" id="update_company_is_supplier" />
                                <label class="form-check-label" for="update_company_is_supplier">Tedarikçi</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_company_name" placeholder="Firma Adı" required>
                            <input type="hidden" class="form-control" id="update_company_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_email" placeholder="Eposta" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_website" placeholder="Website" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_phone" placeholder="Telefon" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_fax" placeholder="Faks" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_company_address" placeholder="Adres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_tax_office" placeholder="Vergi Dairesi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_company_tax_number" placeholder="Vergi Numarası" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Logo <a href="#" id="update_company_current_logo" target="_blank">'yu görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_company_logo" />
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

<div class="modal modal-cover fade" id="addCompanyEmployeeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">YETKİLİ EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="add_employee_name" placeholder="Adı" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_title" placeholder="Ünvanı" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_email" placeholder="Eposta" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_phone" placeholder="Telefon" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="add_employee_mobile" placeholder="Cep Telefonu" >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fotoğraf</label>
                            <input type="file" class="form-control" id="add_employee_photo" />
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
<div class="modal modal-cover fade" id="updateCompanyEmployeeModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">YETKİLİ GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_employee_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="text" class="form-control" id="update_employee_name" placeholder="Adı" required>
                            <input type="hidden" class="form-control" id="update_employee_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_title" placeholder="Ünvanı" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_email" placeholder="Eposta" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_phone" placeholder="Telefon" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" id="update_employee_mobile" placeholder="Cep Telefonu" >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fotoğraf <a href="#" id="update_employee_current_photo" target="_blank">'ı görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_employee_photo" />
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

<div class="modal modal-cover fade" id="addCompanyNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea class="form-control" rows="3" id="add_note_description" placeholder="Not" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_note_employee">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görsel</label>
                            <input type="file" class="form-control" id="add_note_image" />
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
<div class="modal modal-cover fade" id="updateCompanyNoteModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOT GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_note_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notunuz</label>
                            <textarea class="form-control" rows="3" id="update_note_description" placeholder="Not" required></textarea>
                            <input type="hidden" class="form-control" id="update_note_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_note_employee">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görsel <a href="#" id="update_note_current_image" target="_blank">'i görüntülemek için tıklayınız...</a></label>
                            <input type="file" class="form-control" id="update_note_image" />
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

<div class="modal modal-cover fade" id="addCompanyActivityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="add_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Konu</label>
                            <input type="text" class="form-control" id="add_activity_title">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="add_activity_description"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="add_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="add_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_end_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görevler <button type="button" class="btn btn-default btn-sm mx-3" id="add-activity-new-task-btn">Görev Ekle +</button></label>
                        </div>
                        <div id="add-activity-tasks-body" class="d-none mb-3">

                        </div>
                        <div id="add-activity-new-tasks-body" class="mb-3">
                            <input type="hidden" id="add-activity-new-task-count" value="0">
                        </div>
                        <div class="row mb-3 d-none" id="add-activity-new-tasks-input">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control input-sm" id="add-activity-task" placeholder="Yeni Görev" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-default btn-sm" id="add-activity-task-button">Ekle</button>
                            </div>
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
<div class="modal modal-cover fade" id="updateCompanyActivityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="update_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Konu</label>
                            <input type="text" class="form-control" id="update_activity_title">
                            <input type="hidden" class="form-control" id="update_activity_id" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" rows="3" id="update_activity_description"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="update_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="update_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Saat</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_end_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Görevler <button type="button" class="btn btn-default btn-sm mx-3" id="update-activity-new-task-btn">Görev Ekle +</button></label>
                        </div>
                        <div id="update-activity-tasks-body" class="mb-3">
                            <input type="hidden" id="update-activity-task-count" value="0">

                        </div>
                        <div id="update-activity-new-tasks-body" class="d-none mb-3">
                        </div>
                        <div class="row mb-3 d-none" id="update-activity-new-tasks-input">
                            <div class="col-md-4 mb-3">
                                <input type="text" class="form-control input-sm" id="update-activity-task" placeholder="Yeni Görev" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-default btn-sm" id="update-activity-task-button">Ekle</button>
                            </div>
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
