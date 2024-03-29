@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/offer-detail.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                        <div class="card border-0 bg-radial-gradiant text-white h-100">
                            <div class="card-body bg-none">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-buildings h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <h5 class="fw-medium mb-0" id="customer-name"></h5>
                                        <p id="global-id"></p>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-person h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Müşteri Yetkilisi</p>
                                        <h5 class="fw-medium mb-0" id="employee"></h5>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-person h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Proje Sorumlusu</p>
                                        <h5 class="fw-medium mb-0" id="manager"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                        <div class="card border-0 bg-radial-gradiant text-white h-100">
                            <div class="card-body bg-none">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-cash h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Teklif Tutarı</p>
                                        <h6 class="fw-medium mb-0" id="offer-price"></h6>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-node-plus h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Test Adedi</p>
                                        <h6 class="fw-medium mb-0" id="offer-test-count"></h6>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <i class="bi bi-calendar-event h5 avatar avatar-40 bg-light-white rounded"></i>
                                    </div>
                                    <div class="col">
                                        <p class="mb-1">Teklif Tarihi</p>
                                        <h6 class="fw-medium mb-0" id="offer-date"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">

                <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
                    <div class="card border-0 theme-green">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class=" col-auto">
                                    <i class="bi bi-list-task h5 avatar avatar-40 bg-light-theme rounded"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">Teklif Durum Geçmişi</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="status-history-table" class="table table-striped table-borderless mb-2px small text-nowrap">
                                <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>İşlem Tarihi</th>
                                    <th class="text-right">Önceki Durum</th>
                                    <th><i class="bi bi-arrow-90deg-right"></i> Güncel Durum</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3 mb-4">
                    <div class="card border-0 theme-green">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class=" col-auto">
                                    <i class="bi bi-list-task h5 avatar avatar-40 bg-light-theme rounded"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">Task list</h6>
                                    <p class="text-secondary small">Do your best</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush bg-none">
                                <li class="list-group-item">
                                    <div class="row text-secondary">
                                        <div class="col-auto">01</div>
                                        <div class="col"><s>Create UI design</s></div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..." checked></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row text-secondary">
                                        <div class="col-auto">02</div>
                                        <div class="col"><s>Create HTML Structure</s></div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..." checked></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">03</div>
                                        <div class="col">Start Development</div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..."></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">04</div>
                                        <div class="col">Customize 3rd parties</div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..."></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">05</div>
                                        <div class="col">Start reviewing</div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..."></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">06</div>
                                        <div class="col">Update Development</div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..."></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">07</div>
                                        <div class="col">Maintain project</div>
                                        <div class="col-auto"><input class="form-check-input" type="checkbox" value=""
                                                                     aria-label="..."></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3 mb-4">
                    <div class="card border-0 theme-purple h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                            <span class="avatar avatar-40 rounded mb-0 coverimg">
                                                <img src="img/favicon152.png" alt="">
                                            </span>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium mb-0">Project Progress</h6>
                                    <p class="text-secondary small">Currently</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <p class="mb-1">webgetdoors</p>
                                    <h5 class="fw-medium mb-0">2152 hrs <small class="fw-normal">(50.83%)</small></h5>
                                    <p class="small text-secondary">Currently 6 developer, 1 Manager are working on
                                        this.</p>
                                    <span class="btn btn-sm btn-link bg-light-green text-green">Web App</span>
                                    <span class="btn btn-sm btn-link bg-light-yellow text-yellow ms-1">Medium priority</span>
                                </div>
                            </div>
                            <div class="row align-items-center gx-2">
                                <div class="col-auto mb-2 avatar-group">
                                    <div class="avatar avatar-40 coverimg rounded-circle">
                                        <img src="img/user-2.jpg" alt="">
                                    </div>
                                    <div class="avatar avatar-40 coverimg rounded-circle">
                                        <img src="img/user-3.jpg" alt="">
                                    </div>
                                    <div class="avatar avatar-40 coverimg rounded-circle">
                                        <img src="img/user-4.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <p class="text-secondary small mb-0">5 more</p>
                                    <p class="small">Working</p>
                                </div>
                                <div class="col-auto mb-3">
                                    <button class="btn btn-square btn-outline-theme">
                                        <i class="bi bi-person-plus "></i>
                                    </button>
                                </div>
                            </div>
                            <div class="progress bg-light-theme h-5 mb-2">
                                <div class="progress-bar bg-theme" role="progressbar" style="width: 50%" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="small text-secondary mb-3">Project Status <span class="float-end">50%</span></p>
                            <span class="btn btn-sm btn-link bg-light-theme">Due date: 22 March 2022</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3 mb-4">
                    <div class="card border-0 theme-yellow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class=" col-auto">
                                    <i class="bi bi-calendar-event h5 avatar avatar-40 bg-light-theme rounded"></i>
                                </div>
                                <div class="col">
                                    <h6 class="fw-medium todaysdate mb-0">todaysdate</h6>
                                    <p class="text-secondary small">Selected Date</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="calendardisplayinline">
                                <input type="text" id="daterangepickerinline" class="form-control">
                                <div class="calendarinline"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</main>


@include('include.footer')
