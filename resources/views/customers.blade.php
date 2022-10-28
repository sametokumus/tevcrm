@include('include.header')
<?php
$extra_js='
<script src="services/customers.js"></script>
';
?>

    <!--app-content open-->
<div class="main-content app-content mt-5">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

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
