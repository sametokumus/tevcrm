(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $(":input").inputmask();
		 $("#delivery_price_min").maskMoney({thousands:''});
		 $("#delivery_price_max").maskMoney({thousands:''});
		 $("#delivery_price_price").maskMoney({thousands:''});

		 $("#add_default_form").submit(async function( event ) {
			 event.preventDefault();

			 let min = document.getElementById('delivery_price_min').value;
			 let max = document.getElementById('delivery_price_max').value;
			 let price = document.getElementById('delivery_price_price').value;
			 let formData = JSON.stringify({
				 "min_value":min,
				 "max_value":max,
				 "price":price
			 });
			 let returned = await servicePostAddDeliveryPrice(formData);
			 if(returned){
				 $("#add_default_form").trigger("reset");
				 initDeliveryPricesTable();
			 }

		 });

		 $("#update_default_form").submit(async function( event ) {
			 event.preventDefault();

			 let price_id = document.getElementById('update_delivery_price_id').value;
			 let min = document.getElementById('update_delivery_price_min').value;
			 let max = document.getElementById('update_delivery_price_max').value;
			 let price = document.getElementById('update_delivery_price_price').value;
			 let formData = JSON.stringify({
				 "min_value":min,
				 "max_value":max,
				 "price":price
			 });

			 let returned = await servicePostUpdateDeliveryPrice(price_id, formData);
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
		initDeliveryPricesTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openDeliveryPriceModal(delivery_price_id){
	$('#updateDeliveryPriceModal').modal('show');
	initDeliveryPriceModal(delivery_price_id);
}

async function initDeliveryPricesTable(){
	$("#delivery-datatable").dataTable().fnDestroy();
	$('#delivery-datatable tbody > tr').remove();

	let data = await serviceGetDeliveryPrices();
	$.each(data.delivery_prices, function (i, price) {
		let couponItem = '<tr>\n' +
			'              <td>'+ price.id +'</td>\n' +
			'              <td>'+ price.min_value +'</td>\n' +
			'              <td>'+ price.max_value +'</td>\n' +
			'              <td>'+ price.price +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openDeliveryPriceModal(\''+ price.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteDeliveryPrice(\''+ price.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
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

async function initDeliveryPriceModal(delivery_price_id){
	let data = await serviceGetDeliveryPriceById(delivery_price_id);
	let delivery_price = data.delivery_price;
	document.getElementById('update_delivery_price_id').value = delivery_price.id;
	document.getElementById('update_delivery_price_min').value = delivery_price.min_value;
	document.getElementById('update_delivery_price_max').value = delivery_price.max_value;
	document.getElementById('update_delivery_price_price').value = delivery_price.price;
}

async function deleteDeliveryPrice(delivery_price_id){
	let returned = await serviceGetDeleteDeliveryPrice(delivery_price_id);
	if(returned){
		initDeliveryPricesTable();
	}
}
