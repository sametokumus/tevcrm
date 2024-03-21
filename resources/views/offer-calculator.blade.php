@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/add-category.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12 col-lg-12 col-xl-4 col-xxl-4 mb-4 column-set">
                <div class="card border-0">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-newspaper h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                            </div>
                            <div class="col ps-0">
                                <h6 class="fw-medium mb-0">Great news</h6>
                                <p class="text-secondary small">Change world is a combine effort.</p>
                            </div>
                            <div class="col-auto">
                                <div class="dropdown d-inline-block">
                                    <a class="btn btn-link btn-square text-secondary dd-arrow-none dropdown-toggle" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bi bi-columns"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="dropdown-item text-center">
                                            <div class="row gx-3 mb-3">
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-8" data-title="8"><span></span></div>
                                                        <div class="col-4" data-title="4"><span></span></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-9" data-title="9"><span></span></div>
                                                        <div class="col-3" data-title="3"><span></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-6" data-title="6"><span></span></div>
                                                        <div class="col-6" data-title="6"><span></span></div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row select-column-size gx-1">
                                                        <div class="col-12" data-title="12"><span></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="coverimg w-100 h-180 position-relative" style="background-image: url(&quot;assets/img/bg-20.jpg&quot;);">
                        <div class="position-absolute bottom-0 start-0 w-100 mb-3 px-3 z-index-1">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-outline-light btn-rounded">Share this</button>
                                </div>
                                <div class="col-auto">
                                    <div class="dropup d-inline-block">
                                        <a class="btn btn-square btn-sm rounded-circle btn-outline-light dd-arrow-none" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static" role="button">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-hand-thumbs-up me-1 text-green"></i> Recommendation this</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-hand-thumbs-down me-1 text-danger"></i> Don't recommend</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-star text-yellow"></i> Add to favorite</a></li>
                                            <li><a class="dropdown-item text-danger" href="javascript:void(0)">Report this</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img src="assets/img/bg-20.jpg" class="mw-100" alt="" style="display: none;">
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">We all are artist in our field. We all are able to find symmetry in our routine</h5>
                        <h6 class="fw-medium mb-2">Make it clutter free and create better world</h6>
                        <p class="text-secondary">We have added useful and wider-range of widgets fully flexible with wrapper container. If you still reading this, you are in love with this design. <a href="blog-4.html">Read more...</a> </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Kategori Ekle</h6>
                <form id="add_category_form">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <select class="form-select border-0" id="add_category_parent" required>
                                            <option value="">Seçiniz...</option>
                                        </select>
                                        <label>Ana Kategori</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-highlighter"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Kategori Adı" id="add_category_name" required class="form-control border-start-0">
                                        <label>Kategori Adı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback mb-3">Add valid data</div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <button type="submit" class="btn btn-theme">Kaydet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>


@include('include.footer')
