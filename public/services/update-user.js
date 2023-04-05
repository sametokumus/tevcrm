(function($) {
    "use strict";

	 $(document).ready(function() {

		 $('#update_order_form').submit(function (e){
			 e.preventDefault();
			 updateOrder();
		 });

		 $('#update_order_shipment_form').submit(function (e){
			 e.preventDefault();
			 updateOrderShipment();
		 });

		 $('#update_order_billing_form').submit(function (e){
			 e.preventDefault();
			 updateOrderBilling();
		 });

		 $('#update_order_shipping_form').submit(function (e){
			 e.preventDefault();
			 updateOrderShipping();
		 });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		let id = getURLParam('id');
		initUser(id);
		initOrders(id);

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initUser(id){
	let data = await serviceGetUserProfile(id);
	let user = data.user;
	let user_profile = data.user_profile;
	console.log(data);

	$('#user_profile_image').css('background-image','url(https://api-kablocu.wimco.com.tr'+user_profile.profile_photo+')');
	document.getElementById('user_name').value = user_profile.name;
	document.getElementById('user_surname').value = user_profile.surname;
	document.getElementById('user_email').value = user.email;
	document.getElementById('user_phone').value = user.phone_number;
	document.getElementById('user_birthdate').value = formatDateASC(user_profile.birthday, '.');
	let gender = "";
	if (user_profile.gender == 1){gender = "Kadın";}else{gender = "Erkek";}
	document.getElementById('user_gender').value = gender;
	document.getElementById('user_tc').value = user_profile.tc_number;
	document.getElementById('user_register_date').value = formatDateASC(user_profile.created_at, '.');

}


async function initOrders(userId){
	let data = await serviceGetUserOrders(userId);
	$("#order-datatable").dataTable().fnDestroy();
	$('#order-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.orders, function (i, order) {
		let orderItem = '<tr>\n' +
			'              <td>'+ order.status_name +'</td>\n' +
			'              <td>KB'+ order.id +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC(order.order_date, '.') +'</td>\n' +
			'              <td>'+ order.payment_type +'</td>\n' +
			'              <td>'+ order.total +' TL</td>\n' +
			// '              <td>\n' +
			// '                  <div class="btn-list">\n' +
			// '                      <a href="update-order.php?id='+ order.order_id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Düzenle</span></a>\n' +
			// '                      <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="openStatusModal(\''+ order.order_id +'\', \''+ order.status_id +'\')">\n' +
			// '                          <span class="fe fe-arrow-down"> Durum Değiştir\n' +
			// '                      </button>\n' +
			// '                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="">\n' +
			// '                          <span class="fe fe-check-circle"> Ödemeyi Onayla\n' +
			// '                      </button>\n' +
			// '                  </div>\n' +
			// '              </td>\n' +
			'          </tr>';
		$('#order-datatable tbody').append(orderItem);
	});
	$('#order-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: ['excel', 'pdf'],
		pageLength : -1,
		language: {
			url: "services/Turkish.json"
		},
		order: [[2, 'desc']],
	});
}

async function updateOrder(){
	let order_id = getURLParam('id');
	let status_id = document.getElementById('order_status').value;
	let shipping_type = document.getElementById('order_shipping_type').value;

	let formData = JSON.stringify({
		"status_id":status_id,
		"shipping_type":shipping_type
	});
	await servicePostUpdateOrderInfo(order_id, formData);
}

async function updateOrderShipment(){
	let order_id = getURLParam('id');
	let carrier_id = document.getElementById('order_carrier').value;
	let shipping_number = document.getElementById('order_shipping_number').value;

	let formData = JSON.stringify({
		"carrier_id":carrier_id,
		"shipping_number":shipping_number
	});
	await servicePostUpdateOrderShipment(order_id, formData);
}

async function updateOrderBilling(){
	let order_id = getURLParam('id');
	let name = document.getElementById('order_invoice_name').value;
	let address = document.getElementById('order_invoice_address').value;
	let postal_code = document.getElementById('order_invoice_postal_code').value;
	let phone = document.getElementById('order_invoice_phone').value;
	let country = document.getElementById('order_invoice_country').value;
	let city = document.getElementById('order_invoice_city').value;
	let district = document.getElementById('order_invoice_district').value;
	let tax_number = document.getElementById('order_invoice_tax_number').value;
	let tax_office = document.getElementById('order_invoice_tax_office').value;
	let company_name = document.getElementById('order_invoice_company_name').value;

	let formData = JSON.stringify({
		"name":name,
		"address":address,
		"postal_code":postal_code,
		"phone":phone,
		"country":country,
		"city":city,
		"district":district,
		"tax_number":tax_number,
		"tax_office":tax_office,
		"company_name":company_name
	});
	await servicePostUpdateOrderBilling(order_id, formData);
}

async function updateOrderShipping(){
	let order_id = getURLParam('id');
	let name = document.getElementById('order_shipping_name').value;
	let address = document.getElementById('order_shipping_address').value;
	let postal_code = document.getElementById('order_shipping_postal_code').value;
	let phone = document.getElementById('order_shipping_phone').value;
	let country = document.getElementById('order_shipping_country').value;
	let city = document.getElementById('order_shipping_city').value;
	let district = document.getElementById('order_shipping_district').value;
	let tax_number = document.getElementById('order_shipping_tax_number').value;
	let tax_office = document.getElementById('order_shipping_tax_office').value;
	let company_name = document.getElementById('order_shipping_company_name').value;

	let formData = JSON.stringify({
		"name":name,
		"address":address,
		"postal_code":postal_code,
		"phone":phone,
		"country":country,
		"city":city,
		"district":district,
		"tax_number":tax_number,
		"tax_office":tax_office,
		"company_name":company_name
	});
	await servicePostUpdateOrderShipping(order_id, formData);
}
