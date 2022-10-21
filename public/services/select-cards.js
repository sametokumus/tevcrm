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
		initCards();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCards(){
	let data = await serviceGetCards();
	$("#card-datatable").dataTable().fnDestroy();
	$('#card-datatable tbody > tr').remove();

	$.each(data.credit_cards, function (i, card) {
		let refundItem = '<tr>\n' +
			'              <td>'+ card.id +'</td>\n' +
			'              <td>'+ card.member_no +'</td>\n' +
			'              <td>'+ card.bank_name + '</td>\n' +
			'              <td>'+ card.card_name + '</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="update-card.php?id='+ card.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Düzenle</span></a>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#card-datatable tbody').append(refundItem);
	});
	$('#card-datatable').DataTable({
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