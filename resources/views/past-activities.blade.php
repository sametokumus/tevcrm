@include('include.header')
<?php
$extra_js='
<script src="services/past-activities.js"></script>
<script>
$(".datepicker").datepicker({
    autoclose: true,
    format: "dd-mm-yyyy"
});
$(".timepicker").timepicker({
    minuteStep: 15,
    showMeridian: false
});
</script>
';
?>

    <!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid overflow-auto">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Filtreleme
                    </h1>
                </div>
            </div>

            <form method="post" action="#" id="sale_filter_form">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card border-theme mb-3">
                            <div class="card-body">
                                <div class="row p-3">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Talep Gelen Firma</label>
                                        <select class="form-control" id="sale_filter_owner">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Yetkili Satış Temsilcisi</label>
                                        <select class="form-control" id="sale_filter_authorized_personnel">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Satın Alma Sorumlusu</label>
                                        <select class="form-control" id="sale_filter_purchasing_staff">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Müşteri</label>
                                        <select class="form-control form-select" id="sale_filter_company" onchange="initEmployeeSelect();">
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Müşteri Yetkilisi</label>
                                        <select class="form-control" id="sale_filter_company_employee">

                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Durum</label>
                                        <select class="form-control" id="sale_filter_status">
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-theme w-100">Filtrele</button>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="button" class="btn btn-danger w-100" onclick="removeFilter();">Filtreyi Kaldır</button>
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

            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">
                        Aktiviteler
                    </h1>
                </div>
            </div>

            <table id="activities-datatable" class="table table-bordered nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0 bg-dark" data-priority="1">N#</th>
                    <th class="border-bottom-0 bg-dark">Firma</th>
                    <th class="border-bottom-0 bg-dark">Firma Yetkilisi</th>
                    <th class="border-bottom-0 bg-dark">Aktivite Sorumlusu</th>
                    <th class="border-bottom-0">Tür</th>
                    <th class="border-bottom-0">Konu</th>
                    <th class="border-bottom-0">Başlangıç</th>
                    <th class="border-bottom-0">Bitiş</th>
                    <th class="border-bottom-0">Alt Görev</th>
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
