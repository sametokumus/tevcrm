(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $(":input").inputmask();
		 $("#type_discount").maskMoney({thousands:'', allowZero: true});
		 $("#update_type_discount").maskMoney({thousands:'', allowZero: true});

		 $("#add_user_type_form").submit(async function( event ) {
			 event.preventDefault();

			 let name = document.getElementById('type_name').value;
			 let formData = JSON.stringify({
				 "name":name
			 });
			 let returned = await servicePostAddUserType(formData);
			 if(returned){
				 $("#add_user_type_form").trigger("reset");
				 initUserTypesTable();
			 }

		 });

		 $("#update_user_type_form").submit(async function( event ) {
			 event.preventDefault();

			 let type_id = document.getElementById('update_type_id').value;
			 let name = document.getElementById('update_type_name').value;
			 let formData = JSON.stringify({
				 "name":name
			 });

			 let returned = await servicePostUpdateUserType(formData, type_id);
			 if(returned){
				 $("#update_user_type_form").trigger("reset");
				 $('#updateUserTypeModal').modal('hide');
				 initUserTypesTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initUserTypesTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openUserTypeModal(type_id){
	$('#updateUserTypeModal').modal('show');
	initUserTypeModal(type_id);
}

async function initUserTypesTable(){
	$("#user-type-datatable").dataTable().fnDestroy();
	$('#user-type-datatable tbody > tr').remove();

	let data = await serviceGetUserTypes();
	$.each(data.user_types, function (i, type) {
		let typeItem = '<tr>\n' +
			'              <td>'+ type.id +'</td>\n' +
			'              <td>'+ type.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openUserTypeModal(\''+ type.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'						<button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteUserType(\'' + type.id + '\')">\n' +
			'							<span class="fe fe-trash-2"> </span> Sil\n' +
			'                   	</button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#user-type-datatable tbody').append(typeItem);
	});

	$('#user-type-datatable').DataTable({
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

async function initUserTypeModal(type_id){
	let data = await serviceGetUserTypeById(type_id);
	let type = data.user_type;
	document.getElementById('update_type_id').value = type.id;
	document.getElementById('update_type_name').value = type.name;

}

async function deleteUserType(type_id){
	let returned = await serviceGetDeleteUserType(type_id);
	if(returned){
		initUserTypesTable();
	}
}
