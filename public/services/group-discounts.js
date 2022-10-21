(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#product_discount_form').submit(function (e){
			 e.preventDefault();
			 initProducts();
		 });

		 $('#update_discount_form').submit(function (e){
			 e.preventDefault();
			 updateProductDiscount();
		 });


	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initTypeList();
		initBrandList();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initBrandList(){
	let data = await serviceGetBrands();
	$.each(data.brands, function (i, brand) {
		var brandItem = '<option value="'+ brand.id +'">'+ brand.name +'</option>';

		$('#brand').append(brandItem);
	});
}
async function initTypeList(){
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		var typeItem = '<option value="'+ product_type.id +'">'+ product_type.name +'</option>';

		$('#type').append(typeItem);
	});
}
async function initTypeList(){
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		var typeItem = '<option value="'+ product_type.id +'">'+ product_type.name +'</option>';

		$('#type').append(typeItem);
	});
}


async function addProductDiscount(){
	let brand = document.getElementById('brand').value;
	let type = document.getElementById('type').value;
	let formData = new FormData(document.getElementById('product_discount_form'));

	let brands = "";
	let brand_objs = $('#brand').find(':selected');
	for (let i = 0; i < brand_objs.length; i++) {
		brands = brands + brand_objs[i].value;
		console.log(brand_objs[i].value)
	}
	console.log(brands)
	// console.log(brands)
	// let discount = document.getElementById('discount').value;
	// let formData = JSON.stringify({
	// 	"category_id": category,
	// 	"brand_id": brand,
	// 	"type_id": type,
	// 	"discount_rate": discount
	// });
	// await servicePostUpdateDiscountRate(formData);
}
async function updateProductDiscount(){
	let ids = document.getElementById('update_product_ids').value;
	let discount_rate = document.getElementById('update_discount_rate').value;
	let formData = JSON.stringify({
		"ids": ids,
		"discount_rate": discount_rate
	});

	await servicePostUpdateDiscountRate(formData);
	$('#updateDiscountModal').modal('hide');
	$("#update_discount_form").trigger("reset");
}
async function initProducts(filter){
	$("#product-datatable").dataTable().fnDestroy();
	$('#product-datatable tbody > tr').remove();

	let brands = "";
	let brand_objs = $('#brand').find(':selected');
	for (let i = 0; i < brand_objs.length; i++) {
		brands = brands + brand_objs[i].value + ",";
	}
	brands = brands.slice(0, -1);

	let types = "";
	let type_objs = $('#type').find(':selected');
	for (let i = 0; i < type_objs.length; i++) {
		types = types + type_objs[i].value + ",";
	}
	types = types.slice(0, -1);


	let formData = JSON.stringify({
		"brands": brands,
		"types": types
	});
	console.log(formData)
	let data = await serviceGetProductsByFilter(formData);

	console.log(data.products)
	$.each(data.products, function (i, product) {
		let productItem = '<tr data-id="'+ product.id +'">\n' +
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
	let productDatatable = $('#product-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Blfrtip',
		buttons: [
			'selectAll',
			'selectNone',
			'excel',
			'pdf',
			{
				text: 'İskonto Güncelle',
				action: function ( e, dt, node, config ) {
					checkDiscountModal(productDatatable.rows( { selected: true } ));
				}
			}
		],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		},
		select: {
			style: 'multi'
		}
	});
}

async function checkDiscountModal(data){
	let ids = "";
	if (data.count() == 0){
		showAlert("Öncelikle seçim yapmalısınız.")
	}else {
		data.every(function (rowIdx, tableLoop, rowLoop) {
			ids = ids + this.data()[0] + ",";
		});
		ids = ids.slice(0, -1);
		document.getElementById('update_product_ids').value = ids;
		$('#updateDiscountModal').modal('show');
	}
}


