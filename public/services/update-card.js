(function($) {
    "use strict";
	
	 $(document).ready(function() {
		 $('#update_installment_form').submit(function (e){
			 e.preventDefault();
			 updateInstallment();
		 });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		let id = getURLParam('id');
		initBank(id);

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initBank(id){
	let data = await serviceGetCardById(id);
	let card = data.credit_card;
	console.log(data)

	/*INIT BANK INFO*/
	document.getElementById('card_bank_id').value = card.member_no;
	document.getElementById('card_bank_name').value = card.bank_name;
	document.getElementById('card_card_name').value = card.card_name;

	$("#card-datatable").dataTable().fnDestroy();
	$('#card-datatable tbody > tr').remove();

	$.each(card.card_installment, function (i, card) {
		let refundItem = '<tr>\n' +
			'              <td>'+ card.installment +'</td>\n' +
			'              <td>'+ card.installment_plus +'</td>\n' +
			'              <td>'+ card.discount + '</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-success" onclick="openInstallmentModal(\''+ card.id +'\')">\n' +
			'                          <span class="fe fe-arrow-down"> Güncelle\n' +
			'                      </button>\n' +
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

function openInstallmentModal(id){
	$('#updateInstallmentModal').modal('show');
	initInstallmentModal(id);
}
async function updateInstallment(){
	let id = document.getElementById('update_installment_id').value;
	let installment = document.getElementById('update_installment').value;
	let installment_plus = document.getElementById('update_installment_plus').value;
	let discount = document.getElementById('update_installment_discount').value;

	let formData = JSON.stringify({
		"installment": installment,
		"installment_plus": installment_plus,
		"discount": discount
	});
	let returned = await servicePostUpdateCardInstallment(id, formData);
	if(returned){
		$("#update_installment_form").trigger("reset");
		$('#updateInstallmentModal').modal('hide');
		let bank_id = getURLParam('id');
		initBank(bank_id);
	}
}
async function initInstallmentModal(id){
	let data = await serviceGetInstallmentById(id);
	console.log(data)
	let installment = data.credit_card_installment;
	document.getElementById('update_installment_id').value = installment.id;
	document.getElementById('update_installment').value = installment.installment;
	document.getElementById('update_installment_plus').value = installment.installment_plus;
	document.getElementById('update_installment_discount').value = installment.discount;
}