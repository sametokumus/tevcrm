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
		initRefunds();

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
	let status_id = document.getElementById('update_refund_status').value;
	let order_id = document.getElementById('update_order_id').value;
	let formData = JSON.stringify({
		"status": status_id
	});
	let returned = await servicePostUpdateRefundStatus(order_id, formData);
	if(returned){
		$("#update_status_form").trigger("reset");
		$('#updateStatusModal').modal('hide');
		initRefunds();
	}
}
async function initStatusModal(order_id, status_id){
	let data = await serviceGetRefundStatuses();
	console.log(data)
	let statuses = data.order_refund_statuses;
	$('#update_order_status option').remove();
	$.each(statuses, function (i, status){
		let selected = '';
		if(status.id == status_id){selected = 'selected';}
		$('#update_refund_status').append('<option value="'+ status.id +'" '+ selected +'>'+ status.name +'</option>');
	});
	document.getElementById('update_order_id').value = order_id;
}

async function initRefunds(){
	let data = await serviceGetRefundRequests();
	$("#refund-datatable").dataTable().fnDestroy();
	$('#refund-datatable tbody > tr').remove();

	$.each(data.order_refunds, function (i, refund) {
		let refundItem = '<tr>\n' +
			'              <td>'+ refund.status_name +'</td>\n' +
			'              <td>'+ refund.name + ' ' + refund.surname +'</td>\n' +
			'              <td>KB'+ refund.order_id +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC(refund.created_at, '.') +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="openStatusModal(\''+ refund.order_id +'\', \''+ refund.status_id +'\')">\n' +
			'                          <span class="fe fe-arrow-down"> Durum Değiştir\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#refund-datatable tbody').append(refundItem);
	});
	$('#refund-datatable').DataTable({
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
		order: [[3, 'desc']],
	});
}