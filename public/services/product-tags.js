(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#add_tag_form').submit(function (e){
			 e.preventDefault();
			 addTag();
		 });
		 $('#update_tag_form').submit(function (e){
			 e.preventDefault();
			 updateTag();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initTagView();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openTagModal(tag_id){
	$('#updateTagModal').modal('show');
	initTagModal(tag_id);
}
async function initTagView(){
	$("#tag-datatable").dataTable().fnDestroy();
	$('#tag-datatable tbody > tr').remove();
	let data = await serviceGetTags();
	$.each(data.tags, function (i, tag) {

		let tagItem = '<tr>\n' +
			'              <td>'+ tag.id +'</td>\n' +
			'              <td>'+ tag.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openTagModal(\''+ tag.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span>\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteTag(\''+ tag.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';


		$('#tag-datatable tbody').append(tagItem);
	});
	$('#tag-datatable').DataTable({
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
async function initTagModal(tag_id){
	let data = await serviceGetTagById(tag_id);
	let tag = data.tag;
	document.getElementById('update_tag_id').value = tag.id;
	document.getElementById('update_tag_name').value = tag.name;
}



async function addTag(){
	let tag_name = document.getElementById('tag_name').value;
	let formData = JSON.stringify({
		"name": tag_name
	});
	let returned = await servicePostAddTag(formData);
	if(returned){
		$("#add_tag_form").trigger("reset");
		initTagView();
	}
}
async function updateTag(){
	let tag_id = document.getElementById('update_tag_id').value;
	let tag_name = document.getElementById('update_tag_name').value;
	let formData = JSON.stringify({
		"name": tag_name
	});
	let returned = await servicePostUpdateTag(formData, tag_id);
	if(returned){
		$("#update_tag_form").trigger("reset");
		$('#updateTagModal').modal('hide');
		initTagView();
	}
}
async function deleteTag(tag_id){
	let returned = await serviceGetDeleteTag(tag_id);
	if(returned){
		initTagView();
	}
}
