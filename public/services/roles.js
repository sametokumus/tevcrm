(function($) {
    "use strict";

	 $(document).ready(function() {

		 $("#add_role_form").submit(async function( event ) {
			 event.preventDefault();

			 let name = document.getElementById('role_name').value;
			 let formData = JSON.stringify({
				 "name":name
			 });
			 let returned = await servicePostAddAdminRole(formData);
			 if(returned){
				 $("#add_role_form").trigger("reset");
				 initRolesTable();
			 }

		 });

		 $("#update_role_form").submit(async function( event ) {
			 event.preventDefault();

			 let role_id = document.getElementById('update_role_id').value;
			 let name = document.getElementById('update_role_name').value;
			 let formData = JSON.stringify({
				 "name":name
			 });

			 let returned = await servicePostUpdateAdminRole(role_id, formData);
			 if(returned){
				 $("#update_role_form").trigger("reset");
				 $('#updateRoleModal').modal('hide');
				 initRolesTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initRolesTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openRoleModal(role_id){
	$('#updateRoleModal').modal('show');
	initRoleModal(role_id);
}

function openRolePermissionsModal(role_id){
	$('#updateRolePermissionsModal').modal('show');
	initRolePermissionsModal(role_id);
}

async function initRolesTable(){
	$("#role-datatable").dataTable().fnDestroy();
	$('#role-datatable tbody > tr').remove();

	let data = await serviceGetAdminRoles();
	$.each(data.admin_roles, function (i, role) {
		let roleItem = '<tr>\n' +
			'              <td>'+ role.id +'</td>\n' +
			'              <td>'+ role.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openRoleModal(\''+ role.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-success" onclick="openRolePermissionsModal(\''+ role.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Yetkileri Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteRole(\''+ role.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#role-datatable tbody').append(roleItem);
	});

	$('#role-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: ['excel', 'pdf'],
		pageLength : -1,
		language: {
			url: "services/Turkish.json"
		}
	});



}

async function initRoleModal(role_id){
	let data = await serviceGetAdminRoleById(role_id);
	let role = data.admin_role;
	console.log(role.id)
	document.getElementById('update_role_id').value = role.id;
	document.getElementById('update_role_name').value = role.name;

}

async function initRolePermissionsModal(role_id){
	await initPermissionsToRolePermissionsModal();
    await initStatusesToRolePermissionsModal();
	let data = await serviceGetAdminRolePermissions(role_id);
	document.getElementById('update_role_permissions_id').value = role_id;
	$.each(data.role_permissions, function (i, role_permission) {
		console.log('#admin_permission_'+ role_permission.admin_permission_id);
		document.getElementById('admin_permission_'+ role_permission.admin_permission_id).checked = true;
	});
	let data2 = await serviceGetAdminRoleStatuses(role_id);
	document.getElementById('update_role_permissions_id').value = role_id;
	$.each(data2.role_statuses, function (i, role_status) {
		console.log('#admin_status_permission_'+ role_status.status_id);
		document.getElementById('admin_status_permission_'+ role_status.status_id).checked = true;
	});

}

async function initPermissionsToRolePermissionsModal(){
	$('#admin_permissions div.form-check').remove();
	let data = await serviceGetAdminPermissions();
	$.each(data.admin_permissions, function (i, permission) {
		let permissionItem = '<div class="form-check form-switch">\n' +
            '                     <input type="checkbox" data-id="'+ permission.id +'" id="admin_permission_'+ permission.id +'" value="'+ permission.name +'" class="form-check-input admin_permission_inputs">\n' +
            '                     <label class="form-check-label" for="admin_permission_'+ permission.id +'">'+ permission.name +'</label>\n' +
            '                 </div>';
		$('#admin_permissions').append(permissionItem);
	});
	let items = document.getElementsByClassName("admin_permission_inputs");
	for (let i = 0; i < items.length; i++) {
		items[i].addEventListener('click', clickPermissionItem, false);
	}
}

async function initStatusesToRolePermissionsModal(){
	$('#admin_status_permissions div.form-check').remove();
	let data = await serviceGetStatuses();
	$.each(data.statuses, function (i, status) {
		let permissionItem = '<div class="form-check form-switch">\n' +
            '                     <input type="checkbox" data-id="'+ status.id +'" id="admin_status_permission_'+ status.id +'" value="'+ status.name +'" class="form-check-input admin_status_permission_inputs">\n' +
            '                     <label class="form-check-label" for="admin_status_permission_'+ status.id +'">'+ status.name +'</label>\n' +
            '                 </div>';
		$('#admin_status_permissions').append(permissionItem);
	});
	let items = document.getElementsByClassName("admin_status_permission_inputs");
	for (let i = 0; i < items.length; i++) {
		items[i].addEventListener('click', clickStatusPermissionItem, false);
	}
}

let clickPermissionItem = async function() {
	let role_id = document.getElementById('update_role_permissions_id').value;
	let permission_id = $(this).data('id');
	let item_id = $(this).attr('id');
	let isChecked = document.getElementById(item_id).checked;
	if(isChecked){
		await serviceGetAddAdminRolePermission(role_id, permission_id);
	}else{
		await serviceGetDeleteAdminRolePermission(role_id, permission_id);
	}
};

let clickStatusPermissionItem = async function() {
	let role_id = document.getElementById('update_role_permissions_id').value;
	let status_id = $(this).data('id');
	let item_id = $(this).attr('id');
	let isChecked = document.getElementById(item_id).checked;
	if(isChecked){
		await serviceGetAddAdminRoleStatus(role_id, status_id);
	}else{
		await serviceGetDeleteAdminRoleStatus(role_id, status_id);
	}
};

async function deleteRole(role_id){
	let returned = await serviceGetDeleteAdminRole(role_id);
	if(returned){
		initRolesTable();
	}
}
