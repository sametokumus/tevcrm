@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/update-category.js"></script>
';
?>

<main class="main mainheight px-4">

    <!-- content -->
    <div class="container-fluid mt-4">
        <div class="row justify-content-center mb-2">
            <div class="col-12">
                <h6 class="title">Kategori Güncelle</h6>
                <form id="update_category_form">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <div class="form-group mb-3 position-relative check-valid">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text text-theme bg-white border-end-0"><i
                                            class="bi bi-buildings"></i></span>
                                    <div class="form-floating">
                                        <input type="text" placeholder="Kategori Adı" id="update_category_name" required class="form-control border-start-0">
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
