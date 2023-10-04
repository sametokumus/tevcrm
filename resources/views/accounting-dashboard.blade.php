@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="services/accounting-dashboard.js"></script>
';
?>

<div id="content" class="app-content">
    <div class="row">
        <div class="col-12 mb-5">
            <select class="form-control" id="dash_currency" onchange="changeDashCurrency();">
                <option value="TRY">TRY</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">TOPLAM ÖDENEN</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="total-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="total-text">

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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">BEKLEYEN ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="pending-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors"
                                 data-height="30"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="pending-text">

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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">GECİKEN ÖDEMELER</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="late-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="late-text">

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


        <div class="col-xl-3 col-lg-6">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">ORTALAMA KARLILIK</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row align-items-center mb-2" id="profit-box">
                        <div class="col-9">
                            <h4 class="mb-0"></h4>
                        </div>
                        <div class="col-3">
                            <div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors"
                                 data-height="45"></div>
                        </div>
                    </div>


                    <div class="text-white text-opacity-80 text-truncate" id="profit-text">

                        <div>&nbsp;</div>
                        <div>&nbsp;</div>
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

        <div class="col-xl-12 col-lg-12">

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex fw-bold small mb-3">
                        <span class="flex-grow-1">NAKİT AKIŞI</span>
                        <a href="#" data-toggle="card-expand"
                           class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                    </div>


                    <div class="row mb-2" id="cashflow-box">

                        <div class="col-xl-4 col-lg-6">

                            <div class="card mb-3">

                                <div class="card-header d-flex align-items-center bg-white bg-opacity-15">
                                    <span class="flex-grow-1 fw-400">To Do (5)</span>
                                    <a href="#" class="text-white text-opacity-50 text-decoration-none me-3"><i class="fa fa-fw fa-redo"></i></a>
                                    <a href="#" class="text-white text-opacity-50 text-decoration-none"><i class="fa fa-fw fa-trash"></i></a>
                                </div>


                                <div class="list-group list-group-flush">

                                    <div class="list-group-item d-flex px-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">Enable open search</div>
                                            <div class="small text-white text-opacity-50 mb-2">#29949 opened yesterday by Terry</div>
                                            <div>
                                                <span class="badge border border-gray-300 text-white text-opacity-50">docs</span>
                                                <span class="badge border border-theme text-theme">feature</span>
                                            </div>
                                            <hr class="my-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="fw-400 me-2">
                                                    Task (2/3)
                                                </div>
                                                <div>
                                                    <a href="#" class="text-white text-opacity-50" data-bs-toggle="collapse" data-bs-target="#todoBoard">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </a>
                                                </div>
                                                <div class="progress progress-xs w-100px me-2 ms-auto" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped bg-theme" style="width: 66%;"></div>
                                                </div>
                                                <div class="fs-12px">66%</div>
                                            </div>
                                            <div class="form-group mb-0 small">
                                                <div class="collapse show" id="todoBoard">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="customCheck1" checked="">
                                                        <label class="form-check-label" for="customCheck1">create ui for autocomplete</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="customCheck2" checked="">
                                                        <label class="form-check-label" for="customCheck2">integrate jquery autocomplete with ui</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="customCheck3">
                                                        <label class="form-check-label" for="customCheck3">backend search return as json data</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <a href="#" class="list-group-item list-group-item-action d-flex ps-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">Investigate adding markdownlint</div>
                                            <div class="small text-white text-opacity-50 mb-2">#29919 opened 9 days ago by xMediaKron</div>
                                            <div class="mb-1">
                                                <span class="badge border border-gray-300 text-white text-opacity-50">build</span>
                                                <span class="badge border border-indigo text-indigo">v5</span>
                                            </div>
                                        </div>
                                    </a>


                                    <a href="#" class="list-group-item list-group-item-action d-flex ps-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">Add a "Submit a Resource" form</div>
                                            <div class="small text-white text-opacity-50 mb-2">#29916 opened 9 days ago by Wasbbok</div>
                                            <div class="mb-1 d-flex align-items-center">
                                                <div class="me-2"><span class="badge border border-theme text-theme">enhancement</span></div>
                                                <div class="widget-user-list">
                                                    <div class="widget-user-list-item w-30px h-30px"><div class="widget-user-list-link w-30px h-30px"><img src="assets/img/user/user-1.jpg" alt=""></div></div>
                                                    <div class="widget-user-list-item w-30px h-30px ms-n2"><div class="widget-user-list-link w-30px h-30px"><img src="assets/img/user/user-2.jpg" alt=""></div></div>
                                                    <div class="widget-user-list-item w-30px h-30px ms-n2"><div class="widget-user-list-link w-30px h-30px"><img src="assets/img/user/user-3.jpg" alt=""></div></div>
                                                    <div class="widget-user-list-item w-30px h-30px ms-n2"><div class="widget-user-list-link w-30px h-30px"><img src="assets/img/user/user-4.jpg" alt=""></div></div>
                                                    <div class="widget-user-list-item w-30px h-30px ms-n2"><div class="widget-user-list-link w-30px h-30px"><img src="assets/img/user/user-5.jpg" alt=""></div></div>
                                                    <div class="widget-user-list-item w-30px h-30px ms-n2"><div class="widget-user-list-link w-30px h-30px bg-gray-200 text-gray-500 fs-12px fw-400">+2</div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>


                                    <a href="#" class="list-group-item list-group-item-action d-flex ps-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">Custom control border color missing on focus</div>
                                            <div class="small text-white text-opacity-50 mb-2">#29796 opened 29 days ago by mdo</div>
                                            <div class="mb-1">
                                                <span class="badge border border-pink text-pink">docs</span>
                                            </div>
                                        </div>
                                    </a>


                                    <a href="#" class="list-group-item list-group-item-action d-flex ps-3">
                                        <div class="me-3 pt-1">
                                            <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="fw-400">New design for corporate page</div>
                                            <div class="mb-2 small text-white text-opacity-50">#29919 opened 19 days ago by sean</div>
                                            <div class="mb-1">
                                                <span class="badge border border-gray-300 text-white text-opacity-50">design</span>
                                                <span class="badge border border-theme text-theme">v5</span>
                                            </div>
                                        </div>
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


@include('include.footer')
