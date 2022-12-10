@include('include.header')
<?php
$extra_js='
<script src="services/customers.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-header">
                        Müşteriler
                    </h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-outline-secondary">Müşteri Ekle</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card border-theme mb-3">
                        <div class="card-body">
                            <div class="row gx-0 align-items-center">
                                <div class="col-md-3">
                                    <img src="https://via.placeholder.com/480x360/c9d2e3/212837" alt="" class="card-img rounded-0" />
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <h5 class="card-title">Firma</h5>
                                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- arrow -->
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
            </div>


            <form method="post" action="#" id="add_customer_form">

                <!-- ROW-1 OPEN -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Müşteri Ekle</div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Müşteri Adı :</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="add_customer_name" required>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <!--Row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Ekle</button>
                                    </div>
                                </div>
                                <!--End Row-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /ROW-1 CLOSED -->

            </form>

            <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Müşteriler</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="customer-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0" data-priority="1">#</th>
                                        <th class="border-bottom-0" data-priority="2">Müşteri Adı</th>
                                        <th class="border-bottom-0">İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->


@include('include.footer')
