(function($) {
    "use strict";

	 $(document).ready(function() {

		 $("#add_admin_form").submit(async function( event ) {
			 event.preventDefault();

			 let email = document.getElementById('admin_email').value;
			 let password = document.getElementById('admin_password').value;
			 let name = document.getElementById('admin_name').value;
			 let surname = document.getElementById('admin_surname').value;
			 let phone = document.getElementById('admin_phone').value;
			 let role = document.getElementById('admin_role').value;
			 let formData = JSON.stringify({
				 "admin_role_id":role,
				 "email":email,
				 "name":name,
				 "surname":surname,
				 "phone_number":phone,
				 "password":password
			 });
			 let returned = await servicePostAddAdmin(formData);
			 if(returned){
				 $("#add_admin_form").trigger("reset");
				 initAdminsTable();
			 }

		 });

		 $("#update_admin_form").submit(async function( event ) {
			 event.preventDefault();

			 let admin_id = document.getElementById('update_admin_id').value;
			 let email = document.getElementById('update_admin_email').value;
			 let name = document.getElementById('update_admin_name').value;
			 let surname = document.getElementById('update_admin_surname').value;
			 let phone = document.getElementById('update_admin_phone').value;
			 let role = document.getElementById('update_admin_role').value;
			 let formData = JSON.stringify({
				 "admin_role_id":role,
				 "email":email,
				 "name":name,
				 "surname":surname,
				 "phone_number":phone
			 });

			 let returned = await servicePostUpdateAdmin(admin_id, formData);
			 if(returned){
				 $("#update_admin_form").trigger("reset");
				 $('#updateAdminModal').modal('hide');
				 initAdminsTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initRolesToSelect('admin_role');
		initAdminsTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initRolesToSelect(select_id){
	$('#'+select_id +' option').remove();
	let data = await serviceGetAdminRoles();
	$.each(data.admin_roles, function (i, role) {
		let roleItem = '<option value="'+ role.id +'">'+ role.name +'</option>';
		$('#'+select_id).append(roleItem);
	});
}

function openAdminModal(admin_id){
	$('#updateAdminModal').modal('show');
	initAdminModal(admin_id);
}

async function initAdminsTable(){
	$("#admin-datatable").dataTable().fnDestroy();
	$('#admin-datatable tbody > tr').remove();

	let data = await serviceGetAdmins();
	$.each(data.admins, function (i, admin) {
		let adminItem = '<tr>\n' +
			'              <td>'+ admin.id +'</td>\n' +
			'              <td>'+ admin.admin_role_name +'</td>\n' +
			'              <td>'+ admin.name +'</td>\n' +
			'              <td>'+ admin.surname +'</td>\n' +
			'              <td>'+ admin.email +'</td>\n' +
			'              <td>'+ admin.phone_number +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openAdminModal(\''+ admin.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteAdmin(\''+ admin.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#admin-datatable tbody').append(adminItem);
	});

	$('#admin-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
		buttons: ['excel', 'pdf'],
		pageLength : -1,
		language: {
			url: "services/Turkish.json"
		}
	});



}

async function initAdminModal(admin_id){
	await initRolesToSelect('update_admin_role');
	let data = await serviceGetAdminById(admin_id);
	let admin = data.admin;
	document.getElementById('update_admin_id').value = admin.id;
	document.getElementById('update_admin_email').value = admin.email;
	document.getElementById('update_admin_name').value = admin.name;
	document.getElementById('update_admin_surname').value = admin.surname;
	document.getElementById('update_admin_phone').value = admin.phone_number;
	document.getElementById('update_admin_role').value = admin.admin_role_id;

}

async function deleteAdmin(admin_id){
	let returned = await serviceGetDeleteAdmin(admin_id);
	if(returned){
		initAdminsTable();
	}
}
