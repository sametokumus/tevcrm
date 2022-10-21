(function($) {
    "use strict";
	
	$(document).ready(function() {
		$('#filter_product_form').submit(function (e){
			e.preventDefault();

			let filter = true;
			initProducts(filter);
		});
	});

	$(window).load( function() {

		let filter = false;
		checkLogin();
		checkRole();
		initBrandList();
		initTypeList();
		initProducts(filter);

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initProducts(filter){
	$("#product-datatable").dataTable().fnDestroy();
	$('#product-datatable tbody > tr').remove();
	let data;
	if(filter){
		let formData = JSON.stringify({
			"brand_id": document.getElementById('filter_brand').value,
			"type_id": document.getElementById('filter_type').value
		});
		data = await serviceGetFilterProducts(formData);
		console.log(1)
	}else{
		data = await serviceGetProducts();
		console.log(2)
	}
	console.log(data.products)
	$.each(data.products, function (i, product) {
		let productItem = '<tr>\n' +
			'              <td>'+ product.id +'</td>\n' +
			'              <td>'+ product.sku +'</td>\n' +
			'              <td>'+ product.name +'</td>\n' +
			'              <td>'+ product.brand_name +'</td>\n' +
			'              <td>'+ product.type_name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="update-product.php?id='+ product.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> </span></a>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteProduct(\''+ product.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#product-datatable tbody').append(productItem);
	});
	$('#product-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
		buttons: ['excel', 'pdf'],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		}
	});
}
async function deleteProduct(type_id){
	let returned = await serviceGetDeleteProduct(type_id);
	if(returned){
		initProducts();
	}
}
async function initBrandList(){
	let data = await serviceGetBrands();
	$.each(data.brands, function (i, brand) {
		var brandItem = '<option value="'+ brand.id +'">'+ brand.name +'</option>';

		$('#filter_brand').append(brandItem);
	});
}
async function initTypeList(){
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		var typeItem = '<option value="'+ product_type.id +'">'+ product_type.name +'</option>';

		$('#filter_type').append(typeItem);
	});
}