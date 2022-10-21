(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#add_group_form').submit(function (e){
			 e.preventDefault();
			 addGroupType();
		 });
		 $('#update_group_form').submit(function (e){
			 e.preventDefault();
			 updateGroupType();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initGroupView();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openGroupModal(group_id){
	$('#updateGroupModal').modal('show');
	initGroupModal(group_id);
}
async function initGroupView(){
	$("#group-datatable").dataTable().fnDestroy();
	$('#group-datatable tbody > tr').remove();
	let data = await serviceGetVariationGroupTypes();
	$.each(data.variation_group_types, function (i, variation_group_type) {
		let typeItem = '<tr>\n' +
			'              <td>'+ variation_group_type.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openGroupModal(\''+ variation_group_type.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span>\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteGroupType(\''+ variation_group_type.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#group-datatable tbody').append(typeItem);

	});
	$('#group-datatable').DataTable({
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
async function initGroupModal(group_id){
	let data = await serviceGetVariationGroupTypeById(group_id);
	let variation_group_type = data.variation_group_type;
	document.getElementById('update_group_id').value = variation_group_type.id;
	document.getElementById('update_group_name').value = variation_group_type.name;
}



async function addGroupType(){
	let group_name = document.getElementById('group_name').value;
	let formData = JSON.stringify({
		"name": group_name
	});
	let returned = await servicePostAddVariationGroupType(formData);
	if(returned){
		$("#add_type_form").trigger("reset");
		initGroupView();
	}
}
async function updateGroupType(){
	let group_id = document.getElementById('update_group_id').value;
	let type_name = document.getElementById('update_group_name').value;
	let formData = JSON.stringify({
		"name": type_name
	});
	let returned = await servicePostUpdateVariationGroupType(formData, group_id);
	if(returned){
		$("#update_group_form").trigger("reset");
		$('#updateGroupModal').modal('hide');
		initGroupView();
	}
}
async function deleteGroupType(group_id){
	let returned = await serviceGetDeleteVariationGroupType(group_id);
	if(returned){
		initGroupView();
	}
}
