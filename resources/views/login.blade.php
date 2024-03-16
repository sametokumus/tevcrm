<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Technical Universal Verification</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json"/>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <!-- bootstrap icons -->
    <link rel="stylesheet" href="vendor/bootstrap-icons%401.8.1/font/bootstrap-icons.css">

    <!-- swiper carousel css -->
    <link rel="stylesheet" href="vendor/swiper-7.3.1/swiper-bundle.min.css">

    <!-- style css for this template -->
    <link href="scss/style.css" rel="stylesheet" id="style">
</head>

<body class="d-flex flex-column h-100 sidebar-pushcontent" data-sidebarstyle="sidebar-pushcontent">
<!-- sidebar-pushcontent, sidebar-overlay , sidebar-fullscreen  are classes to switch UI here-->

<!-- page loader -->
<div class="container-fluid h-100 position-fixed loader-wrap bg-blur">
    <div class="row justify-content-center h-100">
        <div class="col-auto align-self-center text-center">
            <div class="doors animated mb-4">
                <div class="left-door"></div>
                <div class="right-door"></div>
            </div>
            <h5 class="mb-0">Sayfa yükleniyor</h5>
            <p class="text-secondary small">Anlayışınız için teşekkürler</p>
            <div class="spinner-border text-primary mt-3" role="status">
                <span class="visually-hidden">Yükleniyor...</span>
            </div>
        </div>
    </div>
</div>
<!-- page loader ends -->

<!-- background -->
<div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
    <img src="img/bg-1.jpg" alt="" class="w-100"/>
</div>
<!-- background ends  -->


<!-- Begin page content -->
<main class="main h-100 container-fluid bg-blur rounded-0">
    <div class="row h-100">
        <!-- left block-->
        <div class="col-12 col-md-6 h-100 overflow-y-auto">
            <div class="row h-100">
                <div class="col-12 mb-auto">
                    <!-- header -->
                    <header class="header">
                        <!-- Fixed navbar -->
                        <nav class="navbar">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="#">
                                    <div class="row">
                                        <div class="col-auto"><img src="img/favicon48.png" class="mx-100"
                                                                   alt=""/></div>
                                        <div class="col ps-0 align-self-center">
                                            <h5 class="fw-normal text-dark">CRM</h5>
                                            <p class="small text-secondary">Technical Universal Verification</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </nav>
                    </header>
                    <!-- header ends -->
                </div>
                <div class="col-12  align-self-center py-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-11 col-lg-10 col-xl-8 col-xxl-6">
                            <p class="h4 fw-light mb-4">Hoşgeldiniz</p>

                            <form class="mb-4">
                                <!-- alert messages -->
                                <div class="alert alert-danger fade show d-none mb-2 global-alert" role="alert">
                                    <div class="row">
                                        <div class="col"><strong>Zorunlu!</strong> Lütfen eposta adresinizi ve şifrenizi eksiksiz giriniz.</div>
                                    </div>
                                </div>
                                <div class="alert alert-danger fade show d-none mb-2 service-alert" role="alert">
                                    <div class="row">
                                        <div class="col" id="service-message"></div>
                                    </div>
                                </div>
                                <div class="alert alert-success fade show d-none mb-2 global-success" role="alert">
                                    <div class="row">
                                        <div class="col-auto align-self-center">
                                            <div class="spinner-border spinner-border-sm text-success me-2"
                                                 role="status">
                                                <span class="visually-hidden">Yükleniyor...</span>
                                            </div>
                                        </div>
                                        <div class="col ps-0">
                                            <strong>Giriş Başarılı!</strong> Uygulamaya yönlendiriliyorsunuz.
                                        </div>
                                    </div>
                                </div>
                                <!-- Form elements -->
                                <div class="form-group mb-2 position-relative check-valid validate-email">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text text-theme bg-white border-end-0"><i
                                                class="bi bi-envelope"></i></span>
                                        <div class="form-floating">
                                            <input type="email" placeholder="Email Address" required class="form-control border-start-0" id="email">
                                            <label>Eposta Adresi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback mb-3">Eposta adresiniz .com veya benzer başka bir uzantı ile bitmeli.</div>



                                <!-- Form elements -->
                                <div class="form-group mb-2 position-relative check-valid validate-pass">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text text-theme bg-white border-end-0"><i class="bi bi-key"></i></span>
                                        <div class="form-floating">
                                            <input type="password" placeholder="Enter Password" required class="form-control border-start-0" id="password">
                                            <label for="password">Şifre</label>
                                        </div>
                                        <span class="input-group-text text-secondary bg-white border-end-0" id="viewpassword"><i class="bi bi-eye"></i></span>
                                        <!-- submit button -->
                                        <button class="btn btn-lg btn-theme top-0 end-0 z-index-5 btn-square-lg" type="button" id="submitbtn"><i class="bi bi-arrow-right"></i></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-12 mt-auto">
                    <!-- footer -->
                    <footer class="footer row">
                        <div class="col-12 col-md-12 col-lg py-2">
                            <span class="text-secondary small">Copyright @2024, Designed by <a href="#" target="_blank">Samet Okumuş</a></span>
                        </div>
                    </footer>
                    <!-- footer ends -->
                </div>
            </div>
        </div>

        <!-- right block-->
        <div class="col-12 col-md-6 vh-100">
            <div class="row h-100">
                <div class="col-12 h-50 position-relative">
                    <!-- time and temperature -->
                    <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                        <img src="img/bg-3.jpg" alt="" id="image-daytime" class="w-100"/>
                    </div>
                    <div class="row text-white mt-2">
                        <div class="col">
                            <p class="display-3 mb-0"><span id="time"></span><small><span class="h4 text-uppercase"
                                                                                          id="ampm"></span></small></p>
                            <p class="lead fw-normal" id="date"></p>
                        </div>
                        <div class="col-auto text-end">
                            <p class="display-3 mb-0">
                                <img src="img/cloud-sun.png" alt="" class="vm me-0 tempimage" id="tempimage"/>
                                <span id="temperature">46</span><span class="h4 text-uppercase"> <sup>0</sup>C</span>
                            </p>

                            <a href="javascript:void('');" class="btn btn-link text-white dd-arrow-none dropdown-toggle"
                               id="selectCity" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="h5 fw-normal" id="city">Ankara</span> <i
                                    class="bi bi-pencil-square small fw-light"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="selectCity" id="citychange">
                                <li class="dropdown-item active" data-value="Ankara">Ankara</li>
                                <li class="dropdown-item" data-value="İstanbul">İstanbul</li>
                                <li class="dropdown-item" data-value="New York">New York</li>
                                <li class="dropdown-item" data-value="London">London</li>
                                <li class="dropdown-item" data-value="Qatar">Qatar</li>
                                <li class="dropdown-item" data-value="Delhi">Delhi</li>
                                <li class="dropdown-item" data-value="Sydney">Sydney</li>
                            </ul>
                        </div>
                    </div>
                    <!-- time and temperature ends -->
                </div>
                <div class="col-12 col-md-12 col-lg-7 col-xl-6 h-50 position-relative px-0">
                    <!-- news swiper -->
                    <div class="swiper news-swiper h-100 w-100 text-white">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="overlay"></div>
                                <div class="row h-100 position-relative mx-0 pb-5">
                                    <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                        <img src="img/login/1.jpg" alt="" class="w-100"/>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="overlay"></div>
                                <div class="row h-100 position-relative mx-0 pb-5">
                                    <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                        <img src="img/login/2.jpg" alt="" class="w-100"/>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="overlay"></div>
                                <div class="row h-100 position-relative mx-0 pb-5">
                                    <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                        <img src="img/login/3.jpg" alt=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination pagination-smallline white text-start px-2"></div>
                    </div>
                    <!-- news swiper ends -->
                </div>
                <div class="col-12 col-md-6 col-lg-5 col-xl-6 d-none d-lg-block h-50 position-relative px-0">
                    <!-- image swiper -->
                    <div class="swiper image-swiper h-100 w-100">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                    <img src="img/login/5.jpg" alt=""/>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                    <img src="img/login/4.jpg" alt=""/>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="coverimg h-100 w-100 top-0 start-0 position-absolute">
                                    <img src="img/login/6.jpg" alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- image swiper ends -->
                </div>
            </div>
        </div>
    </div>

</main>


<!-- Required jquery and libraries -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap-5/dist/js/bootstrap.bundle.js"></script>

<!-- Customized jquery file  -->
<script src="js/main.js"></script>
<script src="js/color-scheme.js"></script>

<!-- PWA app service registration and works -->
<script src="js/pwa-services.js"></script>

<!-- Chart js script -->
<script src="vendor/chart-js-3.3.1/chart.min.js"></script>

<!-- Progress circle js script -->
<script src="vendor/progressbar-js/progressbar.min.js"></script>

<!-- swiper js script -->
<script src="vendor/swiper-7.3.1/swiper-bundle.min.js"></script>

<!-- VENDOR -->
<script src="vendor/fileupload.js"></script>
<script src="vendor/bCrypt.js"></script>
<script src="vendor/inputmask/jquery.inputmask.bundle.js"></script>

<!-- SERVICE JS -->
<script src="messages.js"></script>
<script src="services/service.js"></script>
<script src="services/login.js"></script>


</body>


</html>

