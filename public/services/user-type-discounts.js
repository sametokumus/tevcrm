(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $(":input").inputmask();
		 $("#discount").maskMoney({thousands:''});
		 $("#update_discount").maskMoney({thousands:''});

		 $('#add_discount_form').submit(function (e){
			 e.preventDefault();
			 addUserTypeDiscount();
		 });

		 $('#update_discount_form').submit(function (e){
			 e.preventDefault();
			 updateUserTypeDiscount();
		 });


	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initUserTypeList();
		initTypeList();
		initBrandList();

		initUserTypeDiscounts();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openUserTypeDiscountModal(discount_id){
	$('#updateUserTypeDiscountModal').modal('show');
	initUserTypeDiscountModal(discount_id);
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
async function initUserTypeList(){
	let data = await serviceGetUserTypes();
	$.each(data.user_types, function (i, user_type) {
		var typeItem = '<option value="'+ user_type.id +'">'+ user_type.name +'</option>';

		$('#user_type').append(typeItem);
	});
}


async function addUserTypeDiscount(){
	let user_type = document.getElementById('user_type').value;
	let discount = document.getElementById('discount').value;
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
		"user_type": user_type,
		"discount": discount,
		"brands": brands,
		"types": types
	});

	await servicePostAddUserTypeDiscount(formData);
	initUserTypeDiscounts();
}
async function updateUserTypeDiscount(){
	let id = document.getElementById('update_discount_id').value;
	let discount = document.getElementById('update_discount').value;
	let formData = JSON.stringify({
		"discount": discount
	});

	await servicePostUpdateUserTypeDiscount(formData, id);
	$('#updateUserTypeDiscountModal').modal('hide');
	$("#update_discount_form").trigger("reset");
	initUserTypeDiscounts();
}

async function initUserTypeDiscounts(){
	$("#discount-datatable").dataTable().fnDestroy();
	$('#discount-datatable tbody > tr').remove();

	let data = await serviceGetUserTypeDiscounts();

	$.each(data.user_type_discounts, function (i, discount) {
		let discountItem = '<tr>\n' +
			'              <td>'+ discount.id +'</td>\n' +
			'              <td>'+ discount.user_type_name +'</td>\n' +
			'              <td>'+ discount.brand_name +'</td>\n' +
			'              <td>'+ discount.type_name +'</td>\n' +
			'              <td>'+ discount.discount +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openUserTypeDiscountModal(\''+ discount.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteUserTypeDiscount(\''+ discount.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#discount-datatable tbody').append(discountItem);
	});
	$('#discount-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Blfrtip',
		buttons: [
			'excel',
			'pdf'
		],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		}
	});
}

async function deleteUserTypeDiscount(discount_id){
	let returned = await serviceGetDeleteUserTypeDiscount(discount_id);
	if(returned){
		initUserTypeDiscounts();
	}
}

async function initUserTypeDiscountModal(discount_id){
	let data = await serviceGetUserTypeDiscountById(discount_id);
	console.log(data)
	let discount = data.user_type_discount;
	document.getElementById('update_user_type').value = discount.user_type_name;
	document.getElementById('update_type').value = discount.type_name;
	document.getElementById('update_brand').value = discount.brand_name;
	document.getElementById('update_discount').value = discount.discount;
	document.getElementById('update_discount_id').value = discount_id;

}


