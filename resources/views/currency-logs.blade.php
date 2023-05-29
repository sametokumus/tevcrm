@include('include.header')
<?php
$extra_js='
<script src="services/contacts.js"></script>
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
                        Firma
                    </h1>
                </div>
            </div>

            <table id="currency-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Tarih</th>
                    <th class="border-bottom-0">USD</th>
                    <th class="border-bottom-0">EUR</th>
                    <th class="border-bottom-0">GBP</th>
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
