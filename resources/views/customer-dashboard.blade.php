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
    showMeridian: false,
    template: false
});
</script>
';
?>

<main class="main mainheight px-4">
    <div class="container-fluid">
        <div class="row align-items-center page-title">
            <div class="col-12 col-md mb-2 mb-sm-0">
                <h5 class="mb-0">Company Profile</h5>
                <p class="text-secondary">All about company and its background</p>
            </div>
            <div class="col col-sm-auto">
                <div class="input-group input-group-md">
                    <input type="text" class="form-control bg-none px-0" value="" id="titlecalendar"/>
                    <span class="input-group-text text-secondary bg-none" id="titlecalandershow"><i
                            class="bi bi-calendar-event"></i></span>
                </div>
            </div>
            <div class="col-auto ps-0">
                <div class="dropdown d-inline-block">
                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle" href="#"
                       role="button" id="filterintitle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                       aria-expanded="false">
                        <i class="bi bi-filter"></i>
                    </a>
                    <div class="dropdown-menu w-300" aria-labelledby="filterintitle">
                        <div class="dropdown-item">
                            <div class="input-group input-group-md border rounded">
                                <span class="input-group-text text-theme"><i class="bi bi-box"></i></span>
                                <select class="form-control" id="titltfilterlist" multiple>
                                    <option value="San Francisco">San Francisco</option>
                                    <option value="New York">New York</option>
                                    <option value="London">London</option>
                                    <option value="Chicago">Chicago</option>
                                    <option value="India" selected>India</option>
                                    <option value="Sydney">Sydney</option>
                                    <option value="Seattle">Seattle</option>
                                    <option value="Los Angeles">Los Angeles</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Los Angeles">Los Angeles</option>
                                    <option value="Chicago">Chicago</option>
                                    <option value="India">India</option>
                                </select>
                            </div>
                            <div class="invalid-feedback">You have already selected maximum option allowed. (This is
                                Configurable)
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <h6 class="mb-0">Orders:</h6>
                            <p class="text-secondary small">1256 orders last week</p>
                        </div>
                        <ul class="list-group list-group-flush bg-none mb-2">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col">Online Orders</div>
                                    <div class="col-auto">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="titleswitch1">
                                            <label class="form-check-label" for="titleswitch1"></label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col">Offline Orders</div>
                                    <div class="col-auto">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="titleswitch2" checked="">
                                            <label class="form-check-label" for="titleswitch2"></label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="dropdown-item">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-outline-secondary border">cancel</button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-theme">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="help-center.html" class="btn btn-link btn-square text-secondary" data-bs-toggle="tooltip"
                   data-bs-placement="top" title="Support">
                    <i class="bi bi-life-preserver"></i>
                </a>
                <a href="personalization.html" class="btn btn-link btn-square text-secondary" data-bs-toggle="tooltip"
                   data-bs-placement="top" title="Personalize">
                    <i class="bi bi-palette"></i>
                </a>
                <a href="app-pricing.html" class="btn btn-link btn-square text-secondary" data-bs-toggle="tooltip"
                   data-bs-placement="top" title="Buy this">
                        <span class="bi bi-basket position-relative">
                            <span class="position-absolute top-0 start-100 p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        </span>
                </a>
            </div>
        </div>
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-theme">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.html">WinDOORS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Company Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="w-100 pt-5 position-relative bg-theme  z-index-0">
        <div class="coverimg w-100 h-100 position-absolute top-0 start-0 opacity-3 z-index-0">
            <img src="assets/img/bg-14.jpg" class="" alt=""/>
        </div>
        <div class="container my-3 my-md-5 pt-0 py-lg-5 z-index-1 position-relative">
            <div class="row mb-4 mb-lg-5 align-items-start">
                <div class="col-auto position-relative">
                    <figure class="avatar avatar-140 coverimg rounded-10 shadow-md border-3 border-light bg-white">
                        <img src="assets/img/maxartkiller.png" alt=""/>
                    </figure>
                </div>
                <div class="col py-2">
                    <h2 class="mb-3">Maxartkiller <span class="badge bg-green rounded vm fw-normal fs-12"><i
                                class="bi bi-check-circle me-1 vm"></i>Active</span></h2>
                    <p>The Best creative design company. Mostly product based and small size services based company. Its
                        independent company running and providing creative solutions to the UI UX design domain.</p>
                    <p>UI UX Design, IT services</p>
                </div>
                <div class="col-auto py-2">
                    <div>
                        <button class="btn btn-outline-light me-2"><i class="bi bi-plus vm me-2"></i> Follow</button>
                        <button class="btn btn-theme theme-green"><i class="bi bi-person-plus vm me-2"></i> Connect
                        </button>
                    </div>
                </div>
            </div>
            <div class="row text-white gx-md-4 gx-lg-5">
                <div class="col-6 col-md-auto py-2">
                    <p class="text-muted small">Website</p>
                    <p>https://maxartkiller.com</p>
                </div>
                <div class="col-6 col-md-auto py-2">
                    <p class="text-muted small">Company Size</p>
                    <p>1-5</p>
                </div>
                <div class="col-12 col-md-auto py-2">
                    <p class="text-muted small">Financial Stability</p>
                    <p>
                        <i class="bi bi-exclamation-triangle text-yellow vm"></i> Unstable
                        <span class="mx-1 text-muted">|</span>
                        <a href="#" class="text-white"><i class="bi bi-heart-fill text-red vm"></i> Need Support <i
                                class="bi bi-box-arrow-up-right vm mx-1"></i></a>
                    </p>
                </div>
                <div class="col-auto py-2">
                    <p class="text-muted small">Business Domain</p>
                    <p>UI UX Design</p>
                </div>
            </div>
        </div>
        <br>
    </div>

    <div class="container top-30 z-index-1 position-relative mb-4">
        <div class="card bg-white border-0 z-index-1">
            <div class="card-body">
                <ul class="nav nav-tabs nav-WinDOORS border-0" id="companynavigation" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overviewcompany-tab" data-bs-toggle="tab"
                                data-bs-target="#overviewcompany" type="button" role="tab"
                                aria-controls="overviewcompany" aria-selected="true">Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teamcompany-tab" data-bs-toggle="tab" data-bs-target="#teamcompany"
                                type="button" role="tab" aria-controls="teamcompany" aria-selected="false">Team
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="servicescompany-tab" data-bs-toggle="tab"
                                data-bs-target="#servicescompany" type="button" role="tab"
                                aria-controls="servicescompany" aria-selected="false">Services
                        </button>
                    </li>
                    <li class="nav-item ms-auto" role="presentation">
                        <button class="btn btn-theme" id="contactus-tab" data-bs-toggle="tab"
                                data-bs-target="#contactus" type="button" role="tab" aria-controls="contactus"
                                aria-selected="false">Get more info
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card border-0 mb-4">
            <div class="card-body bg-none py-3 py-lg-4">
                <div class="tab-content" id="companynavigationContent">
                    <div class="tab-pane fade show active" id="overviewcompany" role="tabpanel"
                         aria-labelledby="overviewcompany-tab">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10">
                                <div class="row mb-4 mb-lg-5 align-items-center justify-content-center">
                                    <div class="col-12 col-md-7 py-2">
                                        <h2 class="mb-3 mb-md-4">We design UX UI for <span class="text-gradient">Creative & Unique</span>
                                            Digital Products</h2>
                                        <p class="text-secondary">We create HTML templates for Enterprise applications,
                                            Business applications, eCommerce application, Admin Dashboard Applications,
                                            Mobile application, Mobile Websites, Micro websites, Cordova apps etc.
                                            Technology you can choose from our latest builds Bootstrap 5 HTML template,
                                            Framework7 templates, Angular 12 starter kits.</p>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div class="coverimg h-250 rounded overflow-hidden shadow">
                                            <img src="assets/img/bg-14.jpg" alt=""/>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 text-center mb-4 mb-lg-5 rounded">
                                    <div class="card-body p-4 p-md-5">
                                        <h4 class="mb-2 mb-md-3">Our Goal and Vision</h4>
                                        <p class="text-secondary">We believe to create best ethics practices around the
                                            design corner. We always comes with futuristic approach to solve customers
                                            problem.
                                            We evaluate progress day by day and our vision is to make beautiful, usable,
                                            creative and good user experienced design.</p>
                                        <br>
                                        <h4 class="text-gradient mb-3">Winners don't do different things,<br>they do
                                            things differently</h4>
                                        <p class="fw-light">Shiv Khera <cite title=" Source Title">You Can Win: A Step
                                                by Step Tool for Top Achievers</cite>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-4 text-center">
                                        <i class="bi bi-bullseye h1 avatar avatar-80 bg-light-theme rounded-circle mb-3 mb-md-4"></i>
                                        <h4 class="mb-2 mb-md-3">Our Mission</h4>
                                        <p class="text-secondary">Our mission is to provide great UI UX design with
                                            flexibility of development at the reliable cost. This will give you best by
                                            saving time and money. So, for our customer its all about the Win Win
                                            situation.</p>
                                    </div>
                                    <div class="col-12 col-md-6 mb-4 text-center ">
                                        <i class="bi bi-window-stack h1 avatar avatar-80 bg-light-theme rounded-circle mb-3 mb-md-4"></i>
                                        <h4 class="mb-2 mb-md-3">What we do</h4>
                                        <p class="text-secondary">We do best creative and usable digital products for
                                            web and mobile devices, website and universal app development. We create
                                            HTML templates and starter kits with different technologies like Angular,
                                            React, Laravel etc.</p>
                                    </div>
                                </div>
                                <hr class="mb-4 mb-md-5 border-top">
                                <div class="row mb-4 mb-md-5">
                                    <div class="col-12 col-md-12 col-lg-6 mb-5 mb-xxl-0">
                                        <h4 class="mb-4">Clean & Trending UI design with a great user experience</h4>
                                        <p class="text-secondary">WinDOORS is creative and multipurpose template. You
                                            can use it for CRM, Business application, Intranet Application, Portal
                                            service and Many more.
                                            It comes with unlimited possibilities and 10+ predefined styles which you
                                            can also mix up and create new. <b>Do support and spread a word for us</b>.
                                        </p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">

                                        <p class="mb-3"><b>Main office:</b></p>
                                        <p class="mb-1"><a href="https://maxartkiller.com/" target="_blank">www.maxartkiller.com</a>
                                        </p>
                                        <p class="mb-4 text-secondary">Test data 103909 Witamer CR, Niagara Falls, NY
                                            14305, United States</p>
                                        <ul class="nav ">
                                            <li class="nav-item">
                                                <a class="nav-link text-secondary px-2"
                                                   href="https://www.facebook.com/maxartkiller/" target="_blank">
                                                    <i class="bi bi-facebook h5"></i>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-secondary px-2"
                                                   href="https://twitter.com/maxartkiller" target="_blank">
                                                    <i class="bi bi-twitter h5"></i>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-secondary px-2"
                                                   href="https://linkedin.com/company/maxartkiller" target="_blank">
                                                    <i class="bi bi-linkedin h5"></i>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-secondary px-2"
                                                   href="https://www.instagram.com/maxartkiller/" target="_blank">
                                                    <i class="bi bi-instagram h5"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <p class="mb-3"><b>Contact information:</b></p>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-auto"><i class="bi bi-clock text-theme"></i></div>
                                            <div class="col ps-0">0441-215-518625<br><span class="text-secondary">Mon-Sat, 9:00am - 10:00pm</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-auto"><i class="bi bi-telephone text-theme"></i></div>
                                            <div class="col ps-0">+1-000 000 100000</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="servicescompany" role="tabpanel"
                         aria-labelledby="servicescompany-tab">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="assets/img/tour-guide-5.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Creative UI Design</h4>
                                                <p class="text-secondary">We create unique and creative user interfaces
                                                    for website and mobile applications.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="assets/img/tour-guide-1.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Awesome UX flow</h4>
                                                <p class="text-secondary">We keep everything on intuitive and simplest
                                                    manner to make sure everything covered and easy to access.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-center mb-4 mt-3 mb-lg-5">You are at awesome space.<br>Just <span
                                        class="text-gradient">create a difference</span>.</h4>
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="assets/img/tour-guide-2.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Customization</h4>
                                                <p class="text-secondary">To improve day by day customization for new
                                                    requirements. We follow best practices to make development flawless
                                                    and elastic.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 mb-4">
                                            <img src="assets/img/tour-guide-4.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h4>Multi-device Centric</h4>
                                                <p class="text-secondary">Human in race of different medium to
                                                    communicate with each other and we also keep eye on different screen
                                                    sizes and responsiveness</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="teamcompany" role="tabpanel" aria-labelledby="teamcompany-tab">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10">
                                <h4 class="text-center">Meet our all set <span class="text-gradient">Team</span>.</h4>
                                <p class="text-secondary text-center mb-4">We work hard, we do it creatively and we like
                                    to see you here!</p>
                                <div class="h-300 mb-4 position-relative text-center bg-theme rounded overflow-hidden">
                                    <figure class="coverimg h-100 w-100 z-index-0 position-absolute top-0 start-0 m-0">
                                        <img src="assets/img/bg-15.jpg" alt=""/>
                                    </figure>
                                    <div class="row h-100 z-index-1 align-items-center justify-content-center">
                                        <div class="col-12 col-md-8 col-lg-6">
                                            <h2>Team build with Trust & Transparency</h2>
                                            <p class="text-opac">We always prefer to have clear communication less
                                                headache and only creative thoughts in mind. That is why we prefer to
                                                have good working culture across the organization.</p>
                                            <button class="btn btn-theme">Contact us for more</button>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="text-center mb-4">Our great team is our<br> <span class="text-gradient">strength and source</span>
                                    of growth.</h4>
                                <div class="row mb-4">
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card border-0 text-center">
                                            <div class="card-body">
                                                <div class="h-250 overflow-hidden rounded mb-3">
                                                    <figure class="h-100 w-100 coverimg zoomout">
                                                        <img src="assets/img/bg-6.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <h5 class="text-truncate">Aditi Johnson</h5>
                                                <p>London, UK</p>
                                                <p class="text-secondary small">Founder</p>
                                                <ul class="nav justify-content-center">
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.facebook.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-facebook h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://twitter.com/maxartkiller" target="_blank">
                                                            <i class="bi bi-twitter h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://linkedin.com/company/maxartkiller"
                                                           target="_blank">
                                                            <i class="bi bi-linkedin h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.instagram.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-instagram h5"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card border-0 text-center">
                                            <div class="card-body">
                                                <div class="h-250 overflow-hidden rounded mb-3">
                                                    <figure class="h-100 w-100 coverimg zoomout">
                                                        <img src="assets/img/user-4.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <h5 class="text-truncate">Steven Thomson</h5>
                                                <p>New York, USA</p>
                                                <p class="text-secondary small">CEO</p>
                                                <ul class="nav justify-content-center">
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.facebook.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-facebook h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://twitter.com/maxartkiller" target="_blank">
                                                            <i class="bi bi-twitter h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://linkedin.com/company/maxartkiller"
                                                           target="_blank">
                                                            <i class="bi bi-linkedin h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.instagram.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-instagram h5"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card border-0 text-center">
                                            <div class="card-body">
                                                <div class="h-250 overflow-hidden rounded mb-3">
                                                    <figure class="h-100 w-100 coverimg zoomout">
                                                        <img src="assets/img/user-3.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <h5 class="text-truncate">Nicky Lambaa</h5>
                                                <p>Wembley, UK</p>
                                                <p class="text-secondary small">CTO</p>
                                                <ul class="nav justify-content-center">
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.facebook.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-facebook h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://twitter.com/maxartkiller" target="_blank">
                                                            <i class="bi bi-twitter h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://linkedin.com/company/maxartkiller"
                                                           target="_blank">
                                                            <i class="bi bi-linkedin h5"></i>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-secondary px-2"
                                                           href="https://www.instagram.com/maxartkiller/"
                                                           target="_blank">
                                                            <i class="bi bi-instagram h5"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/user-2.jpg" alt=""/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Shrivally</h6>
                                                        <p>Amsterdam, NL</p>
                                                        <p class="text-secondary small">UI Designer</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/user-1.jpg" alt=""/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Max Doe</h6>
                                                        <p>London, UK</p>
                                                        <p class="text-secondary small">UX Designer</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/bg-8.jpg" alt=""/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Aditi Johnson</h6>
                                                        <p>London, UK</p>
                                                        <p class="text-secondary small">Product Designer</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/bg-11.jpg" alt=""/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Aditi Johnson</h6>
                                                        <p>London, UK</p>
                                                        <p class="text-secondary small">Project Manager</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/bg-3.jpg" alt=""/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Preeti Dwivedi</h6>
                                                        <p>Kashi, IN</p>
                                                        <p class="text-secondary small">Head of Design</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card shadow-none">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-80 overflow-hidden rounded">
                                                            <figure class="h-100 w-100 coverimg zoomout">
                                                                <img src="assets/img/bg-2.jpg" alt="" class="w-100"/>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-truncate mb-0">Aditi Johnson</h6>
                                                        <p>London, UK</p>
                                                        <p class="text-secondary small">Development Support</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="text-center">Wanted to experience adventure? <span class="text-gradient">Join us</span>
                                    now!.</h4>
                                <p class="text-secondary text-center mb-4">We will be happy to make ou part of our
                                    team. </p>
                                <div class="text-center">
                                    <button class="btn btn-theme">Apply now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contactus" role="tabpanel" aria-labelledby="contactus-tab">...</div>
                </div>
            </div>
        </div>
    </div>

</main>

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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="add_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma</label>
                            <select class="form-control" id="add_activity_company_id" onchange="initActivityAddModalEmployee();">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="add_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Sorumlusu</label>
                            <select class="form-control" id="add_activity_user_id">

                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Katılımcılar</label>
                            <select name="add_activity_participants[]" class="form-control form-select select2" multiple="multiple" id="add_activity_participants">
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
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="add_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="add_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="add_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AKTİVİTE GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_activity_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Türü</label>
                            <select class="form-control" id="update_activity_type_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma</label>
                            <select class="form-control" id="update_activity_company_id" onchange="initActivityUpdateModalEmployee();">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Firma Yetkilisi</label>
                            <select class="form-control" id="update_activity_employee_id">

                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Aktivite Sorumlusu</label>
                            <select class="form-control" id="update_activity_user_id">

                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Katılımcılar</label>
                            <select name="update_activity_participants[]" class="form-control form-select select2" multiple="multiple" id="update_activity_participants">
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
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Başlangıç</label>
                            <input type="text" class="form-control datepicker" id="update_activity_start_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" class="form-control timepicker" id="update_activity_start_time" />
                                <span class="input-group-addon input-group-text">
                                    <i class="fa fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bitiş</label>
                            <input type="text" class="form-control datepicker" id="update_activity_end_date" placeholder="dd-mm-yyyy" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
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

<div class="modal modal-cover fade" id="addDeliveryAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEVK ADRESİ EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_delivery_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres Başlığı</label>
                            <input type="text" class="form-control" id="add_delivery_address_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" id="add_delivery_address">
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
<div class="modal modal-cover fade" id="updateDeliveryAddressModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SEVK ADRESİ GÜNCELLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_delivery_address_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres Başlığı</label>
                            <input type="text" class="form-control" id="update_delivery_address_name">
                            <input type="hidden" class="form-control" id="update_delivery_address_id">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adres</label>
                            <input type="text" class="form-control" id="update_delivery_address">
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

<div class="modal modal-cover fade" id="addCompanyPointModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Müşteri Puanları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form method="post" action="#" id="add_company_point_form">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" class="form-control" id="add_point_company_id" required>
                            <label class="form-label">Yeni Puan Ekle</label>
                            <input type="number" class="form-control" id="add_point_point" min="1" max="10" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="submit" class="btn btn-outline-theme">Kaydet</button>
                        </div>
                    </div>
                </form>

                <div class="row mb-3">
                    <div class="table-responsive">
                        <table id="company-points-table" class="table table-striped table-borderless mb-2px small">
                            <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Yönetici</th>
                                <th>Puan</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@include('include.footer')
