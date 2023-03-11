@include('include.header')
<?php
$extra_js='
<script src="services/brands.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Markalar
                    </h1>
                </div>
            </div>

            <form method="post" action="#" id="add_brand_form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Marka Ekle</h5>
                            </div>
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Marka Adı</label>
                                        <input type="text" class="form-control" id="add_brand_name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-theme w-100">Kaydet</button>
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
            </form>

            <table id="brand-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Marka</th>
                    <th class="border-bottom-0" data-priority="2">İşlem</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

@include('include.footer')
