(function($) {
    "use strict";

	 $(document).ready(function() {
         $(":input").inputmask();
         $("#add_product_price").maskMoney({thousands:'.', decimal:','});
         $("#update_product_price").maskMoney({thousands:'.', decimal:','});

		 $("#add_product_form").submit(function( event ) {
			 event.preventDefault();

			 addProduct();

		 });

         $("#update_product_form").submit(function( event ) {
             event.preventDefault();

             updateProduct();

         });

         const form = document.getElementById('import_data_form');
         const fileInput = document.getElementById('import_file');
         // fileInput.addEventListener('change', (event) => {
         //     form.submit();
         // });

         $('#import_data_form').submit(function (e){
             e.preventDefault();

             var formData = new FormData(this);

             // Use SheetJS to read the uploaded Excel file
             let reader = new FileReader();
             reader.onload = function (e) {
                 let data = e.target.result;
                 let workbook = XLSX.read(data, { type: 'binary' });
                 let sheet_name_list = workbook.SheetNames;
                 let sheet_name = sheet_name_list[0]; // assume the first sheet is the one we want
                 let worksheet = workbook.Sheets[sheet_name];

                 // Convert the worksheet data to JSON
                 let json_data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                 // Remove the header row from the data (optional)
                 json_data.splice(0, 1);
                 console.log(json_data)
                 addImportedProducts(json_data);

                 //
                 // json_data.forEach(function(obj) {
                 //     console.log(obj)
                 //     table.row.add({
                 //         "id": "",
                 //         "product_stock_code": obj[0],
                 //         "customer_stock_code": obj[1],
                 //         "ref_code": obj[2],
                 //         "product_name": obj[3],
                 //         "quantity": obj[4],
                 //         "measurement_name_tr": obj[5],
                 //         "product_brand_name": "",
                 //         "product_category_id": "",
                 //         "note": obj[6]
                 //     }).draw();
                 //
                 // });
                 // document.getElementById("import_data_form").reset();
             };
             reader.readAsBinaryString(formData.get('import_file'));
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
        let price = '';
        if (product.price != 'null' && product.stock_quantity != 0){
            price = product.price * product.stock_quantity;
            price = changeCommasToDecimal(parseFloat(price).toFixed(2));
        }

        let typeItem = '<tr>\n' +
            '              <td>'+ formatDateASC(product.created_at, '.') +'</td>\n' +
            '              <td>'+ product.id +'</td>\n' +
            '              <td>'+ checkNull(product.product_name) +'</td>\n' +
            '              <td>'+ checkNull(product.ref_code) +'</td>\n' +
            '              <td>'+ checkNull(product.date_code) +'</td>\n' +
            '              <td>'+ checkNull(product.stock_code) +'</td>\n' +
            '              <td>'+ checkNull(product.brand_name) +'</td>\n' +
            '              <td>'+ checkNull(product.category_name) +'</td>\n' +
            '              <td>'+ product.stock_quantity +'</td>\n' +
            '              <td>'+ checkNull(changeCommasToDecimal(product.price)) +'</td>\n' +
            '              <td>'+ price +'</td>\n' +
            '              <td>'+ checkNull(product.currency) +'</td>\n' +
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
            { responsivePriority: 2, targets: -1 },
            {
                targets: 2,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 70 ?
                        data.substr(0, 70) + '...' :
                        data;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Ürün Ekle',
                action: function ( e, dt, node, config ) {
                    openAddProductModal();
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel olarak kaydet',
                title: function() {
                    return 'PRODUCTS';
                },
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
                }
            },
            {
                text: 'Ürünleri Excel\'den aktar',
                action: function(){
                    var fileSelector = document.getElementById('import_file');
                    fileSelector.click();
                    return false;
                }
            }
        ],
        pageLength : 30,
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [[1, 'desc']],
    });



}

function openAddProductModal(){
    $('#addProductModal').modal('show');
}

async function addProduct(){
    let price = document.getElementById('add_product_price').value;
    let currency = document.getElementById('add_product_currency').value;
    let date_code = document.getElementById('add_product_date_code').value;
    let stock_code = document.getElementById('add_product_stock_code').value;
    let stock_quantity = document.getElementById('add_product_stock_quantity').value;
    let ref_code = document.getElementById('add_product_ref_code').value;
    let product_name = document.getElementById('add_product_name').value;
    let brand_id = document.getElementById('add_product_brand').value;
    let category_id = document.getElementById('add_product_category').value;
    let formData = JSON.stringify({
        "date_code": date_code,
        "stock_code": stock_code,
        "stock_quantity": stock_quantity,
        "ref_code": ref_code,
        "product_name": product_name,
        "price": changePriceToDecimal(price),
        "currency": currency,
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
    document.getElementById('update_product_date_code').value = product.date_code;
    document.getElementById('update_product_price').value = changeCommasToDecimal(product.price);
    document.getElementById('update_product_currency').value = product.currency;

}

async function updateProduct(){
    let price = document.getElementById('update_product_price').value;
    let currency = document.getElementById('update_product_currency').value;
    let date_code = document.getElementById('update_product_date_code').value;
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
        "category_id": category_id,
        "date_code": date_code,
        "price": changePriceToDecimal(price),
        "currency": currency
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

async function addImportedProducts(data){
    let formData = JSON.stringify({
        "products": data
    });
    let returned = await servicePostAddImportedProducts(formData);
    if(returned){
        $("#add_product_form").trigger("reset");
        $('#addProductModal').modal('hide');
        initProducts();
    }
}
