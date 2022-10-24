(function($) {
    "use strict";

	$(document).ready(function() {

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        initCustomers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCustomers(){
	let data = await serviceGetCustomers();
	$("#customer-datatable").dataTable().fnDestroy();
	$('#customer-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.customers, function (i, customer) {
		let userItem = '<tr>\n' +
			'              <td>'+ customer.id +'</td>\n' +
			'              <td>'+ customer.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="customer-detail/'+ customer.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Müşteri Bilgileri</span></a>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#customer-datatable tbody').append(userItem);
	});
	$('#customer-datatable').DataTable({
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
		order: [[0, 'asc']],
	});
}


