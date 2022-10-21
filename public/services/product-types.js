(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#add_type_form').submit(function (e){
			 e.preventDefault();
			 addType();
		 });
		 $('#update_type_form').submit(function (e){
			 e.preventDefault();
			 updateType();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initTypeView();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openTypeModal(type_id){
	$('#updateTypeModal').modal('show');
	initTypeModal(type_id);
}
async function initTypeView(){
	$("#type-datatable").dataTable().fnDestroy();
	$('#type-datatable tbody > tr').remove();
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		let typeItem = '<tr>\n' +
			'              <td>'+ product_type.id +'</td>\n' +
			'              <td>'+ product_type.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openTypeModal(\''+ product_type.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span>\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteType(\''+ product_type.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#type-datatable tbody').append(typeItem);
	});
	$('#type-datatable').DataTable({
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
async function initTypeModal(type_id){
	let data = await serviceGetTypeById(type_id);
	let product_type = data.product_type;
	document.getElementById('update_type_id').value = product_type.id;
	document.getElementById('update_type_name').value = product_type.name;
}



async function addType(){
	let type_name = document.getElementById('type_name').value;
	let formData = JSON.stringify({
		"name": type_name
	});
	let returned = await servicePostAddType(formData);
	if(returned){
		$("#add_type_form").trigger("reset");
		initTypeView();
	}
}
async function updateType(){
	let type_id = document.getElementById('update_type_id').value;
	let type_name = document.getElementById('update_type_name').value;
	let formData = JSON.stringify({
		"name": type_name
	});
	let returned = await servicePostUpdateType(formData, type_id);
	if(returned){
		$("#update_type_form").trigger("reset");
		$('#updateTypeModal').modal('hide');
		initTypeView();
	}
}
async function deleteType(type_id){
	let returned = await serviceGetDeleteType(type_id);
	if(returned){
		initTypeView();
	}
}
