<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Wimco Teknoloji A.Ş.">
    <meta name="author" content="Wimco Teknoloji A.Ş.">
    <meta name="keywords" content="">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/brand/kablocu-favicon.png" />

    <!-- TITLE -->
    <title>Leni's Technologies</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/dark-style.css" rel="stylesheet" />
    <link href="css/transparent-style.css" rel="stylesheet">
    <link href="css/skin-modes.css" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="css/icons.css" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="colors/color1.css" />

</head>

<body class="app sidebar-mini ltr">

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <img src="images/brand/logo.png" class="header-brand-img" alt="">
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                            <span class="login100-form-title pb-5">
                                Giriş
                            </span>
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li class="mx-0"><a href="#tab5" class="active" data-bs-toggle="tab">E-posta ile giriş</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <form method="post" action="#" id="login_form">
                                                <div class="wrap-input100 validate-input input-group">
                                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                        <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                                    </a>
                                                    <input class="input100 border-start-0 form-control ms-0" type="text" id="login_email" placeholder="E-posta" required>
                                                </div>
                                                <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                    </a>
                                                    <input class="input100 border-start-0 form-control ms-0" type="password" id="login_password" placeholder="Şifre" required>
                                                </div>
                                                <div class="container-login100-form-btn">
                                                    <button type="submit" class="login100-form-btn btn-primary">
                                                            Giriş
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="plugins/bootstrap/js/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="js/show-password.min.js"></script>

    <!-- GENERATE OTP JS -->
    <script src="js/generate-otp.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="plugins/p-scroll/perfect-scrollbar.js"></script>

    <!-- VENDOR -->
    <script src="vendor/fileupload.js"></script>
    <script src="vendor/bCrypt.js"></script>
    <script src="vendor/inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Color Theme js -->
    <script src="js/themeColors.js"></script>

    <!-- SERVICE JS -->
    <script src="services/service.js"></script>
    <script src="services/login.js"></script>

    <!-- CUSTOM JS -->
    <script src="js/custom.js"></script>

</body>

</html>
