(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $(":input").inputmask();
		 $("#delivery_price_min").maskMoney({thousands:''});
		 $("#delivery_price_max").maskMoney({thousands:''});
		 $("#delivery_price_price").maskMoney({thousands:''});

		 $("#update_default_form").submit(async function( event ) {
			 event.preventDefault();

			 let city_id = document.getElementById('update_city_id').value;
			 let delivery_price_id = document.getElementById('update_delivery_price_id').value;
			 let min = document.getElementById('update_delivery_price_min').value;
			 let max = document.getElementById('update_delivery_price_max').value;
			 let price = document.getElementById('update_delivery_price_price').value;
			 let formData = JSON.stringify({
				 "min_value":min,
				 "max_value":max,
				 "price":price
			 });

			 let returned = await servicePostUpdateRegionalDeliveryPrice(city_id, delivery_price_id, formData);
			 if(returned){
				 $("#update_delivery_price_form").trigger("reset");
				 $('#updateDeliveryPriceModal').modal('hide');
				 initDeliveryPricesTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initRegionalDeliveryTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openDeliveryModal(city_id){
	$('#updateDeliveryModal').modal('show');
	initDeliveryModal(city_id);
}

async function initRegionalDeliveryTable(){
	$("#regional-delivery-datatable").dataTable().fnDestroy();
	$('#regional-delivery-datatable tbody > tr').remove();

	let data = await serviceGetCitiesByCountryId(1);
	$.each(data.cities, function (i, city) {
		let cityItem = '<tr>\n' +
			'              <td>'+ city.id +'</td>\n' +
			'              <td>'+ city.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openDeliveryModal(\''+ city.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-success" onclick="resetDefaultPrice(\''+ city.id +'\')">\n' +
			'                          <span class="fe fe-refresh-cw"> </span> Default Tutarlara Güncelle\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#regional-delivery-datatable tbody').append(cityItem);
	});

	$('#regional-delivery-datatable').DataTable({
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

async function initDeliveryModal(city_id){

	$("#delivery-datatable").dataTable().fnDestroy();
	$('#delivery-datatable tbody > tr').remove();

	let data = await serviceGetRegionalDeliveryPriceByCityId(city_id);
	console.log(data)
	$.each(data.regional_delivery_prices, function (i, price) {
		let couponItem = '<tr>\n' +
			'              <td>'+ price.id +'</td>\n' +
			'              <td>'+ price.delivery_price.min_value +'</td>\n' +
			'              <td>'+ price.delivery_price.max_value +'</td>\n' +
			'              <td>'+ price.price +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openDeliveryPriceModal(\''+ price.city_id +'\', \''+ price.delivery_price_id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#delivery-datatable tbody').append(couponItem);
	});

	$('#delivery-datatable').DataTable({
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

function openDeliveryPriceModal(city_id, delivery_price_id){
	$('#updateDeliveryPriceModal').modal('show');
	initDeliveryPriceModal(city_id, delivery_price_id);
}

async function initDeliveryPriceModal(city_id, delivery_price_id){
	let data = await serviceGetRegionalDeliveryPrice(city_id, delivery_price_id);
	let delivery_price = data.regional_delivery_price;
	document.getElementById('update_delivery_price_id').value = delivery_price_id;
	document.getElementById('update_city_id').value = city_id;
	document.getElementById('update_delivery_price_min').value = delivery_price.delivery_price.min_value;
	document.getElementById('update_delivery_price_max').value = delivery_price.delivery_price.max_value;
	document.getElementById('update_delivery_price_price').value = delivery_price.price;
}


async function resetDefaultPrice(city_id){
	let returned = await serviceGetResetPricesToDefaultByCityId(city_id);
	// if(returned){
	// 	initCouponsTable();
	// }
}

async function resetAllPricesToDefault(){
	let returned = await serviceGetResetAllPricesToDefault();
	// if(returned){
	// 	initCouponsTable();
	// }
}

async function syncCitiesToRegionalDelivery(){
	let returned = await serviceGetSyncCitiesToRegionalDelivery();
	// if(returned){
	// 	initCouponsTable();
	// }
}
