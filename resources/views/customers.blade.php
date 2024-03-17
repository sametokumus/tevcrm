@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/customers.js"></script>
<script src="js/project-dashboard.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Müşteri Ekle</h6>

                <div class="row">
                    <!-- timesheet -->
                    <div class="col-12 col-md-12 position-relative">
                        <div class="card border-0 mb-4">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-auto">
                                        <i class="bi bi-clock h5 avatar avatar-40 bg-light-theme rounded"></i>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <h6 class="fw-medium mb-0">Timesheet Submitted</h6>
                                        <p class="text-secondary small">Logged time by user</p>
                                    </div>
                                    <div class="col-auto ms-auto">
                                        <div class="input-group border">
                                            <span class="input-group-text text-theme"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" placeholder="Search...">
                                        </div>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <div class="dropdown d-inline-block">
                                            <a class="btn btn-sqaure btn-link text-secondary dd-arrow-none dropdown-toggle"
                                               data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"
                                               role="button">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0)">Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-borderless footable" data-show-toggle="true">
                                    <thead>
                                    <tr class="text-muted">
                                        <th class="">Project</th>
                                        <th data-breakpoints="xs md">Date/Time</th>
                                        <th data-breakpoints="xs">Effort</th>
                                        <th data-breakpoints="xs md">Approved by</th>
                                        <th data-breakpoints="xs md">Approved Date</th>
                                        <th data-breakpoints="xs">Task</th>
                                        <th class="w-auto">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-4.jpg" alt="" class="w-100"/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Divine soul apps</p>
                                                    <p class="text-secondary small mb-1">Requirement Understanding</p>
                                                    <p class="text-secondary small">By <a href="#">Avnit Preet</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">5.00</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">John Johnson</p>
                                            <p class="text-secondary small">john@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-green">Completed</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/business-4.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Kitchen World app</p>
                                                    <p class="text-secondary small mb-1">UI Design for Home page</p>
                                                    <p class="text-secondary small">By <a href="#">Maria Carlos</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">7.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Mirae Jackson</p>
                                            <p class="text-secondary small">mirae@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">10:05 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-green">Completed</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-5.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">AutoD-vine App</p>
                                                    <p class="text-secondary small mb-1">Data updates for release</p>
                                                    <p class="text-secondary small">By <a href="#">Steve Brandan</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">9.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Millie Danial</p>
                                            <p class="text-secondary small">millie@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-blue">In Progress</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/bg-10.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Hotel Atlantisia</p>
                                                    <p class="text-secondary small mb-1">Requirement gathering</p>
                                                    <p class="text-secondary small">By <a href="#">Shelvey Doe</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">7.15</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Nick Vedhaa</p>
                                            <p class="text-secondary small">vedhaa@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-red">Bug</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-5.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">AutoD-vine App</p>
                                                    <p class="text-secondary small mb-1">Data updates for release</p>
                                                    <p class="text-secondary small">By <a href="#">Jacob Jackson</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">7.45</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Mirae Jackson</p>
                                            <p class="text-secondary small">mirae@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">10:05 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-green">Completed</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/bg-10.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Hotel Atlantisia</p>
                                                    <p class="text-secondary small mb-1">Requirement gathering</p>
                                                    <p class="text-secondary small">By <a href="#">Max Wildson</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">8.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Millie Danial</p>
                                            <p class="text-secondary small">millie@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-blue">New Task</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-3.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Web payment extensions</p>
                                                    <p class="text-secondary small mb-1">Development of services</p>
                                                    <p class="text-secondary small">By <a href="#">Jack Dan</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">7.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">Nick Vedhaa</p>
                                            <p class="text-secondary small">vedhaa@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-red">Bug Assigned</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-3.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Web payment extensions</p>
                                                    <p class="text-secondary small mb-1">Development of services</p>
                                                    <p class="text-secondary small">By <a href="#">Shrivalli Johnson</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">8.00</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">John Johnson</p>
                                            <p class="text-secondary small">john@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-yellow">In Progress</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/news-4.jpg" alt="" class="w-100"/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Divine soul apps</p>
                                                    <p class="text-secondary small mb-1">Requirement Understanding</p>
                                                    <p class="text-secondary small">By <a href="#">Maria Smith</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">7.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">John Johnson</p>
                                            <p class="text-secondary small">john@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-yellow">In Progress</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <figure class="avatar avatar-50 border mb-0 coverimg rounded">
                                                        <img src="img/business-4.jpg" alt=""/>
                                                    </figure>
                                                </div>
                                                <div class="col ps-0">
                                                    <p class="mb-0">Kitchen World app</p>
                                                    <p class="text-secondary small mb-1">UI Design for Home page</p>
                                                    <p class="text-secondary small">By <a href="#">Shelvey Doe</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">6.30</p>
                                            <p class="text-secondary small">hours</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">John Johnson</p>
                                            <p class="text-secondary small">john@gmailtestid.com</p>
                                        </td>
                                        <td>
                                            <p class="mb-0">20-1-2022</p>
                                            <p class="text-secondary small">9:00 am</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-green">Completed</span>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <a class="text-secondary dd-arrow-none" data-bs-toggle="dropdown"
                                                   aria-expanded="false" data-bs-display="static" role="button">
                                                    <i class="bi bi-three-dots"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Move</a></li>
                                                    <li><a class="dropdown-item text-danger"
                                                           href="javascript:void(0)">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="row align-items-center mx-0 mb-3">
                                    <div class="col-6 ">
                                    <span class="hide-if-no-paging">
                                        Showing <span id="footablestot">1st</span> page
                                    </span>
                                    </div>
                                    <div class="col-6" id="footable-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>


@include('include.footer')
