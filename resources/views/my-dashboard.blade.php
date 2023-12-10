@include('include.header')
<?php
$extra_js='
<script src="plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
<script src="plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/apexcharts/dist/apexcharts.min.js"></script>
<script src="services/my-dashboard.js"></script>
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

                            <h4 id="staff-name"></h4>
                            <div class="mb-1" id="staff-phone">

                            </div>
                            <div class="mb-3" id="staff-email">

                            </div>
                            <button type="button" id="update-profile-button" class="btn btn-sm btn-outline-theme fs-11px">Profili Güncelle</button>
                            <hr class="mt-4 mb-4">

                            <div class="fw-bold mb-3 fs-16px">Firmalar</div>
                            <div class="d-flex align-items-center mb-3">
                                <img src="img/user/null-profile-picture.png" alt="" width="30" class="rounded-circle">
                                <div class="flex-fill px-3">
                                    <div class="fw-bold text-truncate w-100px">Noor Rowe</div>
                                    <div class="fs-12px text-inverse text-opacity-50">Müşteri</div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-theme fs-11px">İncele</a>
                            </div>
                        </div>
                    </div>


                    <div class="profile-content">
                        <ul class="profile-tab nav nav-tabs nav-tabs-v2" role="tablist">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <div class="nav-field">Posts</div>
                                    <div class="nav-value">382</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <div class="nav-field">Followers</div>
                                    <div class="nav-value">1.3m</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <div class="nav-field">Photos</div>
                                    <div class="nav-value">1,397</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <div class="nav-field">Videos</div>
                                    <div class="nav-value">120</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-followers" class="nav-link">
                                    <div class="nav-field">Following</div>
                                    <div class="nav-value">2,592</div>
                                </a>
                            </li>
                        </ul>
                        <div class="profile-content-container">
                            <div class="row gx-4">
                                <div class="col-xl-8">
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
                                                                <th class="border-bottom-0">Personel</th>
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

                                        <div class="row sparkboxes mt-0 mb-4">

                                            <div class="col-xl-3 col-lg-6">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">BU AY ONAYLANAN</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>

                                                        <div class="row align-items-center mb-2" id="monthly-approved-box">
                                                            <div class="col-12">
                                                                <h4 class="mb-0"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="text-white text-opacity-80 text-truncate" id="monthly-approved-text">

                                                        </div>

                                                        <div class="box box1">
                                                            <div id="spark1"></div>
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
                                                            <span class="flex-grow-1">BU AY TAMAMLANAN</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>

                                                        <div class="row align-items-center mb-2" id="monthly-completed-box">
                                                            <div class="col-12">
                                                                <h4 class="mb-0"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="text-white text-opacity-80 text-truncate" id="monthly-completed-text">

                                                        </div>

                                                        <div class="box box2">
                                                            <div id="spark2"></div>
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
                                                            <span class="flex-grow-1">BU AY POTANSİYEL</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>

                                                        <div class="row align-items-center mb-2" id="monthly-continue-box">
                                                            <div class="col-12">
                                                                <h4 class="mb-0"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="text-white text-opacity-80 text-truncate" id="monthly-continue-text">

                                                        </div>

                                                        <div class="box box3">
                                                            <div id="spark3"></div>
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
                                                            <span class="flex-grow-1">BU AY İPTAL EDİLEN</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>

                                                        <div class="row align-items-center mb-2" id="monthly-cancelled-box">
                                                            <div class="col-12">
                                                                <h4 class="mb-0"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="text-white text-opacity-80 text-truncate" id="monthly-cancelled-text">

                                                        </div>

                                                        <div class="box box4">
                                                            <div id="spark4"></div>
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

                                            <div class="col-xl-6">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">ONAYLANAN SATIŞLAR</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>


                                                        <div class="mb-3">
                                                            <div id="chart-approved-monthly"></div>
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

                                            <div class="col-xl-6">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">TAMAMLANAN SATIŞLAR</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>


                                                        <div class="mb-3">
                                                            <div id="chart-completed-monthly"></div>
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

                                            <div class="col-xl-6">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">POTANSİYEL SATIŞLAR</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>


                                                        <div class="mb-3">
                                                            <div id="chart-potential-sales"></div>
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

                                            <div class="col-xl-6">

                                                <div class="card mb-3">

                                                    <div class="card-body">

                                                        <div class="d-flex fw-bold small mb-3">
                                                            <span class="flex-grow-1">İPTAL EDİLEN SATIŞLAR</span>
                                                            <a href="#" data-toggle="card-expand"
                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                                                        </div>


                                                        <div class="mb-3">
                                                            <div id="chart-cancelled-potential-sales"></div>
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
                                <div class="col-xl-4">
                                    <div class="desktop-sticky-top d-none d-lg-block">
                                        <div class="card mb-3">
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item fw-bold px-3 d-flex">
                                                    <span class="flex-fill">Trends for you</span>
                                                    <a href="#" class="text-inverse text-opacity-50"><i class="fa fa-cog"></i></a>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending Worldwide</strong></small></div>
                                                    <div class="fw-bold mb-2">#BreakingNews</div>
                                                    <a href="#" class="card text-inverse text-decoration-none mb-1">
                                                        <div class="card-body">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-8">
                                                                    <div class="small text-inverse text-opacity-50 mb-1 mt-n1">Space</div>
                                                                    <div class="h-40px fs-13px overflow-hidden mb-n1">Distant star explosion is brightest ever seen, study finds</div>
                                                                </div>
                                                                <div class="col-md-4 d-flex">
                                                                    <div class="h-100 w-100" style="background: url(assets/img/gallery/news-1.jpg) center; background-size: cover;"></div>
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
                                                    <div><small class="text-inverse text-opacity-50">1.89m share</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="fw-bold mb-2">#TrollingForGood</div>
                                                    <div class="fs-13px mb-1">Be a good Troll and spread some positivity on HUD today.</div>
                                                    <div><small class="text-inverse text-opacity-50"><i class="fa fa-external-link-square-alt"></i> Promoted by HUD Trolls</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending Worldwide</strong></small></div>
                                                    <div class="fw-bold mb-2">#CronaOutbreak</div>
                                                    <div class="fs-13px mb-1">The coronavirus is affecting 210 countries around the world and 2 ...</div>
                                                    <div><small class="text-inverse text-opacity-50">49.3m share</small></div>
                                                </div>
                                                <div class="list-group-item px-3">
                                                    <div class="text-inverse text-opacity-50"><small><strong>Trending in New York</strong></small></div>
                                                    <div class="fw-bold mb-2">#CoronavirusPandemic</div>
                                                    <a href="#" class="card mb-1 text-inverse text-decoration-none">
                                                        <div class="card-body">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-8">
                                                                    <div class="fs-12px text-inverse text-opacity-50 mt-n1">Coronavirus</div>
                                                                    <div class="h-40px fs-13px overflow-hidden mb-n1">Coronavirus: US suspends travel from Europe</div>
                                                                </div>
                                                                <div class="col-md-4 d-flex">
                                                                    <div class="h-100 w-100" style="background: url(assets/img/gallery/news-2.jpg) center; background-size: cover;"></div>
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
                                                    <div><small class="text-inverse text-opacity-50">821k share</small></div>
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



<div class="modal modal-cover fade" id="updateProfileModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FİRMA GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_account_form">
                <div class="modal-body">
                    <div class="row mb-4">

                        <div class="row mb-4">
                            <label class="col-md-3 form-label">E-posta :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_admin_email" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Ad :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_admin_name" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Soyad :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_admin_surname" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Telefon :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="update_admin_phone" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Şifre :</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" id="update_admin_password">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Profil fotoğrafını <a href="#" id="update_admin_current_profile_photo" target="_blank">görüntülemek için tıklayınız...</a></label>
                                <input type="file" class="form-control" id="update_admin_profile_photo" />
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
