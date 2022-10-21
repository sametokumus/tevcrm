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
		initOrder(id);

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initOrder(id){
	let data = await serviceGetOrderById(id);
	let order = data.order;
	console.log(data)
	/*INIT LISTS*/
	let data_order_statuses = await serviceGetOrderStatuses();
	let data_carriers = await  serviceGetCarriers();
	let data_shipping_types = await serviceGetShippingTypes();

	$.each(data_order_statuses.order_statuses, function (i, order_status){
		$('#order_status').append('<option value="'+ order_status.id +'">'+ order_status.name +'</option>');
	});
	$.each(data_carriers.carriers, function (i, carrier){
		$('#order_carrier').append('<option value="'+ carrier.id +'">'+ carrier.name +'</option>');
	});
	$.each(data_shipping_types.shipping_types, function (i, shipping_type){
		$('#order_shipping_type').append('<option value="'+ shipping_type.id +'">'+ shipping_type.name +'</option>');
	});

	/*INIT PAYMENT INFO*/
	document.getElementById('order_payment_type').value = order.payment_name;


	/*INIT ORDER INFO*/
	document.getElementById('order_status').value = order.status_id;
	document.getElementById('order_shipping_type').value = order.shipping_type;
	document.getElementById('order_number').value = 'KB'+order.id;
	document.getElementById('order_id').value = order.order_id;
	document.getElementById('order_date').value = formatDateAndTimeDESC(order.created_at, '.');


	/*INIT USER INFO*/
	let user_data = await serviceGetUserProfile(order.user_id);
	document.getElementById('order_user_name').value = user_data.user_profile.name + ' ' + user_data.user_profile.surname;
	document.getElementById('order_user_email').value = user_data.user.email;
	document.getElementById('order_user_phone').value = user_data.user.phone_number;
	document.getElementById('order_user_tc').value = user_data.user_profile.tc_number;



	/*INIT SHIPMENT INFO*/
	document.getElementById('order_carrier').value = order.carrier_id;
	document.getElementById('order_shipping_number').value = order.shipping_number;



	/*INIT BILLING INFO*/
	let billing_array = order.billing_address.split("-");
	let billing_location_array = billing_array[4].split("/");
	document.getElementById('order_invoice_name').value = billing_array[0];
	document.getElementById('order_invoice_phone').value = billing_array[3];
	document.getElementById('order_invoice_address').value = billing_array[1];
	document.getElementById('order_invoice_postal_code').value = billing_array[2];
	document.getElementById('order_invoice_district').value = billing_location_array[0];
	document.getElementById('order_invoice_city').value = billing_location_array[1];
	document.getElementById('order_invoice_country').value = billing_location_array[2];
	if (billing_array.length > 5) {
		document.getElementById('order_invoice_company_name').value = billing_array[7];
		document.getElementById('order_invoice_tax_office').value = billing_array[6];
		document.getElementById('order_invoice_tax_number').value = billing_array[5];
	}else{
		$('#update_order_billing_form .corporate').css('display', 'none');
	}



	/*INIT SHIPPING INFO*/
	let shipping_array = order.shipping_address.split("-");
	let shipping_location_array = shipping_array[4].split("/");
	document.getElementById('order_shipping_name').value = shipping_array[0];
	document.getElementById('order_shipping_phone').value = shipping_array[3];
	document.getElementById('order_shipping_address').value = shipping_array[1];
	document.getElementById('order_shipping_postal_code').value = shipping_array[2];
	document.getElementById('order_shipping_district').value = shipping_location_array[0];
	document.getElementById('order_shipping_city').value = shipping_location_array[1];
	document.getElementById('order_shipping_country').value = shipping_location_array[2];
	if (shipping_array.length > 5) {
		document.getElementById('order_shipping_company_name').value = shipping_array[7];
		document.getElementById('order_shipping_tax_office').value = shipping_array[6];
		document.getElementById('order_shipping_tax_number').value = shipping_array[5];
	}else{
		$('#update_order_shipping_form .corporate').css('display', 'none');
	}


	/*INIT ORDER DETAIL*/
	let order_details = order.order_details;
	$('#order-detail-table tbody tr').remove();
	let item = '<tr class=" ">\n' +
		'           <th class="text-center"></th>\n' +
		'           <th>Stok Kodu</th>\n' +
		'           <th>Marka</th>\n' +
		'           <th>Ürün</th>\n' +
		'           <th class="text-center">Adet</th>\n' +
		'           <th class="text-end">Fiyat</th>\n' +
		'       </tr>';
	$('#order-detail-table tbody').append(item);
	$.each(order_details, function (i, order_detail){
		let item_price = parseFloat(order_detail.sub_total_price) + parseFloat(order_detail.sub_total_tax);
		let item = '<tr>\n' +
			'           <td class="text-center">'+ (i+1) +'</td>\n' +
			'           <td class="text-center">'+ order_detail.product.variation.sku +'</td>\n' +
			'           <td class="text-center">'+ order_detail.product.brand_name +'</td>\n' +
			'           <td>\n' +
			'               <p class="font-w600 mb-1">'+ order_detail.product.name +'</p>\n' +
			'               <div class="text-muted">\n' +
			'                   <div class="text-muted">'+ order_detail.product.variation.name +'</div>\n' +
			'               </div>\n' +
			'           </td>\n' +
			'           <td class="text-center">'+ order_detail.quantity +'</td>\n' +
			'           <td class="text-end">'+ changeCommasToDecimal(item_price.toFixed(2)) +'</td>\n' +
			'       </tr>';
		$('#order-detail-table tbody').append(item);
	});
	let sub_total_price = parseFloat(order.total_price) + parseFloat(order.total_tax);
	let item2 = '<tr>\n' +
		'            <td colspan="5" class="fw-bold text-uppercase text-end">Ara Toplam</td>\n' +
		'            <td class="fw-bold text-end h4">'+ changeCommasToDecimal(sub_total_price.toFixed(2)) +' TL</td>\n' +
		'        </tr>';
	$('#order-detail-table tbody').append(item2);
	let item3 = '<tr>\n' +
		'            <td colspan="5" class="fw-bold text-uppercase text-end">Kargo Ücreti</td>\n' +
		'            <td class="fw-bold text-end h4">('+ order.total_weight.toFixed(2) +' KG) '+ changeCommasToDecimal(parseFloat(order.total_delivery.price).toFixed(2)) +' TL</td>\n' +
		'        </tr>';
	$('#order-detail-table tbody').append(item3);
	let total_price = parseFloat(order.total_price) + parseFloat(order.total_tax) + parseFloat(order.total_delivery.price);
	let item4 = '<tr>\n' +
		'            <td colspan="5" class="fw-bold text-uppercase text-end">Toplam</td>\n' +
		'            <td class="fw-bold text-end h4">'+ changeCommasToDecimal(total_price.toFixed(2)) +' TL</td>\n' +
		'        </tr>';
	$('#order-detail-table tbody').append(item4);


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