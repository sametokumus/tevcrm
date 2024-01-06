@include('include.header')
<?php
$extra_js='
<script src="services/staff-dashboard.js"></script>
';
?>

<div id="content" class="app-content">

    <div class="card">
        <div class="card-body p-0">

            <div class="profile">

                <div class="profile-container">

                    <div class="profile-sidebar">
                        <div class="desktop-sticky-top">
                            <div class="profile-img">
                                <img id="staff-image" src="" alt="">
                            </div>
                            <h4></h4>
                            <div class="mb-1"><i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i> 123123123</div>
                            <div class="mb-3"><i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i>admin@test.com</div>
                        </div>
                    </div>


                    <div class="profile-content">
                        <ul class="profile-tab nav nav-tabs nav-tabs-v2" role="tablist">
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Toplam Müşteri</div>
                                    <div class="nav-value" id="stat-1"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Eklenen Müşteri</div>
                                    <div class="nav-value" id="stat-2"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Yapılan Görüşme</div>
                                    <div class="nav-value" id="stat-3"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Toplam Teklif</div>
                                    <div class="nav-value" id="stat-4"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Toplam Sipariş</div>
                                    <div class="nav-value" id="stat-5"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" style="pointer-events:none;">
                                    <div class="nav-field">Sıralama ve Puan</div>
                                    <div class="nav-value" id="stat-6"></div>
                                </a>
                            </li>
                        </ul>
                        <div class="profile-content-container">
                            <div class="row gx-4">
                                <div class="col-xl-12">
                                    <div class="p-0">


                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">SATIŞ HEDEFLERİ</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>
                                                        <table id="targets-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                                                            <thead>
                                                            <tr>
                                                                <th class="border-bottom-0" data-priority="1">N#</th>
                                                                <th class="border-bottom-0">Tür</th>
                                                                <th class="border-bottom-0">Hedef</th>
                                                                <th class="border-bottom-0">Ay</th>
                                                                <th class="border-bottom-0">Yıl</th>
                                                                <th class="border-bottom-0">Durum</th>
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


@include('include.footer')
