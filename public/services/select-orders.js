(function($) {
    "use strict";
	
	$(document).ready(function() {

		$('#update_status_form').submit(function (e){
			e.preventDefault();
			updateStatus();
		});
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initOrders();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openStatusModal(order_id, status_id){
	$('#updateStatusModal').modal('show');
	initStatusModal(order_id, status_id);
}
async function updateStatus(){
	let status_id = document.getElementById('update_order_status').value;
	let order_id = document.getElementById('update_order_id').value;
	let formData = JSON.stringify({
		"order_id": order_id,
		"status_id": status_id
	});
	let returned = await servicePostUpdateStatus(formData);
	if(returned){
		$("#update_status_form").trigger("reset");
		$('#updateStatusModal').modal('hide');
		initOrders();
	}
}
async function initStatusModal(order_id, status_id){
	let data = await serviceGetOrderStatuses();
	console.log(data)
	let statuses = data.order_statuses;
	$('#update_order_status option').remove();
	$.each(statuses, function (i, status){
		let selected = '';
		if(status.id == status_id){selected = 'selected';}
		$('#update_order_status').append('<option value="'+ status.id +'" '+ selected +'>'+ status.name +'</option>');
	});
	document.getElementById('update_order_id').value = order_id;
}

async function initOrders(){
	let type = getURLParam('type');
	console.log(type)
	let data;
	if(type == 'ongoing'){
		data = await serviceGetOnGoingOrders();
	}else if(type == 'completed'){
		data = await serviceGetCompletedOrders();
	}
	$("#order-datatable").dataTable().fnDestroy();
	$('#order-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.orders, function (i, order) {
		let orderItem = '<tr>\n' +
			'              <td>'+ order.status_name +'</td>\n' +
			'              <td>KB'+ order.id +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC(order.order_date, '.') +'</td>\n' +
			'              <td>'+ order.user_profile.name + ' ' + order.user_profile.surname +'</td>\n' +
			'              <td>'+ order.payment_method_name +'</td>\n' +
			'              <td>'+ order.shipping_type_name +'</td>\n' +
			'              <td>'+ changeCommasToDecimal(order.total) +' TL</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="update-order.php?id='+ order.order_id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Düzenle</span></a>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="openStatusModal(\''+ order.order_id +'\', \''+ order.status_id +'\')">\n' +
			'                          <span class="fe fe-arrow-down"> Durum Değiştir\n' +
			'                      </button>\n';

		if(order.status_id == 3) {
			orderItem += '         <button id="bDel" type="button" class="btn  btn-sm btn-primary" onclick="completeOrder();">\n' +
				'                      <span class="fe fe-check-circle"> Siparişi Onayla\n' +
				'                  </button>\n';
		}

		if(order.status_id == 1) {
			orderItem += '         <button id="bDel" type="button" class="btn  btn-sm btn-success" onclick="completePayment();">\n' +
				'                      <span class="fe fe-check-circle"> Ödemeyi Onayla\n' +
				'                  </button>\n' +
				'                  <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="cancelOrder(order.order_id);">\n' +
				'                      <span class="fe fe-x"> Siparişi İptal Et\n' +
				'                  </button>\n';
		}


		orderItem += '         </div>\n' +
			'              </td>\n' +
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
		buttons: ['excel', 'pdf'],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		},
		order: [[2, 'desc']],
	});
}

async function cancelOrder(order_id){

}