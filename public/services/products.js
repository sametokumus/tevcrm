(function($) {
    "use strict";

	 $(document).ready(function() {

		 $("#add_product_form").submit(function( event ) {
			 event.preventDefault();

			 addProduct();

		 });

         $("#update_product_form").submit(function( event ) {
             event.preventDefault();

             updateProduct();

         });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initProducts();
        getBrandsAddSelectId('add_product_brand');
        getCategoriesAddSelectId('add_product_category');
        getBrandsAddSelectId('update_product_brand');
        getCategoriesAddSelectId('update_product_category');

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initProducts(){
    $("#product-datatable").dataTable().fnDestroy();
    $('#product-datatable tbody > tr').remove();

    let data = await serviceGetProducts();
    $.each(data.products, function (i, product) {
        let typeItem = '<tr>\n' +
            '              <td>'+ product.id +'</td>\n' +
            '              <td>'+ checkNull(product.stock_code) +'</td>\n' +
            '              <td>'+ checkNull(product.ref_code) +'</td>\n' +
            '              <td>'+ checkNull(product.product_name) +'</td>\n' +
            '              <td>'+ checkNull(product.brand_name) +'</td>\n' +
            '              <td>'+ checkNull(product.category_name) +'</td>\n' +
            '              <td>'+ product.stock_quantity +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openUpdateProductModal(\''+ product.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Düzenle\n' +
            '                      </button>\n' +
            '                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteProduct(\''+ product.id +'\')">\n' +
            '                          <span class="fe fe-trash-2"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#product-datatable tbody').append(typeItem);
    });

    $('#product-datatable').DataTable({
        responsive: false,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            'excel',
            'pdf',
            {
                text: 'Ürün Ekle',
                action: function ( e, dt, node, config ) {
                    openAddProductModal();
                }
            }
        ],
        paging: false,
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'desc']],
    });



}

function openAddProductModal(){
    $('#addProductModal').modal('show');
}

async function addProduct(){
    let stock_code = document.getElementById('add_product_stock_code').value;
    let stock_quantity = document.getElementById('add_product_stock_quantity').value;
    let ref_code = document.getElementById('add_product_ref_code').value;
    let product_name = document.getElementById('add_product_name').value;
    let brand_id = document.getElementById('add_product_brand').value;
    let category_id = document.getElementById('add_product_category').value;
    let formData = JSON.stringify({
        "stock_code": stock_code,
        "stock_quantity": stock_quantity,
        "ref_code": ref_code,
        "product_name": product_name,
        "brand_id": brand_id,
        "category_id": category_id
    });
    let returned = await servicePostAddProduct(formData);
    if(returned){
        $("#add_product_form").trigger("reset");
        $('#addProductModal').modal('hide');
        initProducts();
    }
}

function openUpdateProductModal(product_id){
    $('#updateProductModal').modal('show');
    initUpdateProductModal(product_id);
}

async function initUpdateProductModal(product_id){
    let data = await serviceGetProductById(product_id);
    let product = data.product;

    document.getElementById('update_product_id').value = product.id;
    document.getElementById('update_product_stock_code').value = product.stock_code;
    document.getElementById('update_product_stock_quantity').value = product.stock_quantity;
    document.getElementById('update_product_ref_code').value = product.ref_code;
    document.getElementById('update_product_name').value = product.product_name;
    document.getElementById('update_product_brand').value = product.brand_id;
    document.getElementById('update_product_category').value = product.category_id;

}

async function updateProduct(){
    let product_id = document.getElementById('update_product_id').value;
    let stock_code = document.getElementById('update_product_stock_code').value;
    let stock_quantity = document.getElementById('update_product_stock_quantity').value;
    let ref_code = document.getElementById('update_product_ref_code').value;
    let product_name = document.getElementById('update_product_name').value;
    let brand_id = document.getElementById('update_product_brand').value;
    let category_id = document.getElementById('update_product_category').value;
    let formData = JSON.stringify({
        "stock_code": stock_code,
        "stock_quantity": stock_quantity,
        "ref_code": ref_code,
        "product_name": product_name,
        "brand_id": brand_id,
        "category_id": category_id
    });
    let returned = await servicePostUpdateProduct(formData, product_id);
    if(returned){
        $("#update_product_form").trigger("reset");
        $('#updateProductModal').modal('hide');
        initProducts();
    }
}

async function deleteProduct(product_id){
    let returned = await serviceGetDeleteProduct(product_id);
    if(returned){
        initProducts();
    }
}

