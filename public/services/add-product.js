(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#add_product_form').submit(function (e){
			 e.preventDefault();
			 checkProductSku();
		 });
		 $('#check_product_sku_form').submit(function (e){
			 e.preventDefault();
			 $('#checkProductSkuModal').modal('hide');
			 addProduct();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initBrandList();
		initTypeList();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initBrandList(){
	let data = await serviceGetBrands();
	$.each(data.brands, function (i, brand) {
		var brandItem = '<option value="'+ brand.id +'">'+ brand.name +'</option>';

		$('#product_brand').append(brandItem);
	});
}
async function initTypeList(){
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		var typeItem = '<option value="'+ product_type.id +'">'+ product_type.name +'</option>';

		$('#product_type').append(typeItem);
	});
}
async function checkProductSku(){
	let sku = document.getElementById('product_sku').value;
	let data = await serviceGetCheckProductSku(sku);
	if (data.useSku){
		$('#checkProductSkuModal').modal('show');
	}else{
		addProduct();
	}
}
async function addProduct(){
	let sku = document.getElementById('product_sku').value;
	let brand = document.getElementById('product_brand').value;
	let type = document.getElementById('product_type').value;
	let name = document.getElementById('product_name').value;
	let description = tinymce.get('product_description').getContent();
	let formData = JSON.stringify({
		"brand_id": brand,
		"type_id": type,
		"name": name,
		"description": description,
		"sku": sku
	});
	let data = await servicePostAddProduct(formData);
	if(data.product_id > 0){
		window.location.href = "update-product.php?id="+data.product_id;
	}
}
