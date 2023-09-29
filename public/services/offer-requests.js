(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_status_form').submit(function (e){
			e.preventDefault();
		});
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initOfferRequests();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initOfferRequests(){
	let data = await serviceGetOfferRequests();
	$("#offer-requests-datatable").dataTable().fnDestroy();
	$('#offer-requests-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.offer_requests, function (i, offer_request) {
		let requestItem = '<tr>\n' +
			'              <td>'+ offer_request.id +'</td>\n' +
			'              <td>'+ offer_request.request_id +'</td>\n' +
			'              <td>'+ offer_request.authorized_personnel.name +' '+ offer_request.authorized_personnel.surname +'</td>\n' +
			'              <td>'+ offer_request.company.name +'</td>\n' +
			'              <td>'+ offer_request.company_employee.name +'</td>\n' +
			'              <td>'+ offer_request.product_count +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="offer-request/'+ offer_request.request_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Talebi Güncelle</span></a>\n' +
			'                      <a href="offer/'+ offer_request.request_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklifler</span></a>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#offer-requests-datatable tbody').append(requestItem);
	});
	$('#offer-requests-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
        paging: false,
		// buttons: [
        //     'excel',
        //     'pdf',
        //     {
        //         text: 'Talep Oluştur',
        //         action: function ( e, dt, node, config ) {
        //             window.location = '/offer-request';
        //         }
        //     }
        // ],
		pageLength : -1,
		language: {
			url: "services/Turkish.json"
		},
		order: [[0, 'desc']],
	});
}
