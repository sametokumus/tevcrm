(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#product_discount_form').submit(function (e){
			 e.preventDefault();
			 addProductDiscount();
		 });
		 $('#category_discount_form').submit(function (e){
			 e.preventDefault();
			 addCategoryDiscount();
		 });
		 $('#type_discount_form').submit(function (e){
			 e.preventDefault();
			 addTypeDiscount();
		 });
		 $('#brand_discount_form').submit(function (e){
			 e.preventDefault();
			 addBrandDiscount();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initCategoryList();
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

async function initCategoryList(){
	$('#parent_category option').remove();
	let data = await serviceGetCategories();
	$.each(data.categories, function (i, category) {

		$.each(category.sub_categories, function (i, sub_category) {

			var categoryItem = '<option value="'+ sub_category.id +'">'+ sub_category.name +'</option>';
			$('#category').append(categoryItem);
		});

	});
}

async function addProductDiscount(){
	let category = document.getElementById('category').value;
	let brand = document.getElementById('brand').value;
	let type = document.getElementById('type').value;
	let discount = document.getElementById('discount').value;
	let formData = JSON.stringify({
		"category_id": category,
		"brand_id": brand,
		"type_id": type,
		"discount_rate": discount
	});
	await servicePostUpdateDiscountRate(formData);
}

async function addCategoryDiscount(){
	let category = document.getElementById('category').value;
	let category_discount = document.getElementById('category_discount').value;
	let formData = JSON.stringify({
		"discount_rate": category_discount
	});
	await servicePostUpdateDiscountRateByCategoryId(category, formData);
}

async function addTypeDiscount(){
	let type = document.getElementById('type').value;
	let type_discount = document.getElementById('type_discount').value;
	let formData = JSON.stringify({
		"discount_rate": type_discount
	});
	await servicePostUpdateDiscountRateByTypeId(type, formData);
}

async function addBrandDiscount(){
	let brand = document.getElementById('brand').value;
	let brand_discount = document.getElementById('brand_discount').value;
	let formData = JSON.stringify({
		"discount_rate": brand_discount
	});
	await servicePostUpdateDiscountRateByBrandId(brand, formData);
}
