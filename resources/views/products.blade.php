@include('include.header')
<?php
$extra_js='
<script src="services/products.js"></script>
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
                        Ürünler
                    </h1>
                </div>
            </div>


            <table id="product-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                <thead>
                <tr>
                    <th class="border-bottom-0" data-priority="1">ID</th>
                    <th class="border-bottom-0">Stok Kodu</th>
                    <th class="border-bottom-0">Ref. Code</th>
                    <th class="border-bottom-0">Ürün Adı</th>
                    <th class="border-bottom-0">Marka</th>
                    <th class="border-bottom-0">Ürün Grubu</th>
                    <th class="border-bottom-0">Stok Miktarı</th>
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

<div class="modal modal-cover fade" id="addProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ÜRÜN EKLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="add_product_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Stok Kodu</label>
                            <input type="text" class="form-control" id="add_product_stock_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Stok Miktarı</label>
                            <input type="number" class="form-control" id="add_product_stock_quantity" value="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ref. Code</label>
                            <input type="text" class="form-control" id="add_product_ref_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="add_product_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Marka</label>
                            <select class="form-control" id="add_product_brand">
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Grubu</label>
                            <select class="form-control" id="add_product_category">
                            </select>
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

<div class="modal modal-cover fade" id="updateProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ÜRÜN GÜNCELLEME</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="#" id="update_product_form">
                <div class="modal-body">
                    <div class="row mb-4">
                        <input type="hidden" class="form-control" id="update_product_id">

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Stok Kodu</label>
                            <input type="text" class="form-control" id="update_product_stock_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Stok Miktarı</label>
                            <input type="text" class="form-control" id="update_product_stock_quantity">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ref. Code</label>
                            <input type="text" class="form-control" id="update_product_ref_code">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="update_product_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Marka</label>
                            <select class="form-control" id="update_product_brand">
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ürün Grubu</label>
                            <select class="form-control" id="update_product_category">
                            </select>
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
