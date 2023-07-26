@include('include.header')
<?php
$extra_js='
<script src="services/mobile-documents.js"></script>
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
                        Dökümanlar
                    </h1>
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addDocumentModal">Döküman Ekle</button>
                    </div>
                </div>
            </div>

            <table id="document-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Tür</th>
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

<div class="modal modal-cover fade" id="addDocumentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DÖKÜMAN EKLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_document_form">
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tür</label>
                        <select class="form-control" id="add_document_type">

                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Döküman</label>
                        <input type="file" class="form-control" id="add_document_file" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-outline-theme">Kaydet</button>
            </div>
            </form>
        </div>
    </div>
</div>

@include('include.footer')
