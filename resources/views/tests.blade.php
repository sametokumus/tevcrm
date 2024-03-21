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
                <h6 class="title">Testler</h6>

                <div class="row">
                    <!-- timesheet -->
                    <div class="col-12 col-md-12 position-relative">
                        <div class="card border-0 mb-4">
                            <div class="card-body p-4">
                                <table id="test-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                                    <thead>
                                    <tr class="text-muted">
                                        <th class="">Kategori</th>
                                        <th class="">Test Adı</th>
                                        <th data-breakpoints="xs md">Numune Sayısı</th>
                                        <th data-breakpoints="xs">Numune Açıklama</th>
                                        <th data-breakpoints="xs md">Test Süresi</th>
                                        <th data-breakpoints="xs md">Test Bedeli</th>
                                        <th class="w-50px"></th>
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
        </div>
    </div>

</main>


@include('include.footer')
