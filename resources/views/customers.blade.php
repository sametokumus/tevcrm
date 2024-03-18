@include('include.header')
<?php
$extra_js='
<script src="services/header-title.js"></script>
<script src="services/customers.js"></script>
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
                            <div class="card-body p-0">
                                <table id="customer-datatable" class="table table-borderless" data-show-toggle="true">
                                    <thead>
                                    <tr class="text-muted">
                                        <th class="">Firma</th>
                                        <th data-breakpoints="xs md">Eposta</th>
                                        <th data-breakpoints="xs">Telefon</th>
                                        <th data-breakpoints="xs md">Faks</th>
                                        <th data-breakpoints="xs md">Adres</th>
                                        <th class="w-auto">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

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
