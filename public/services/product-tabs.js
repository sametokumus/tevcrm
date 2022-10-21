(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $('#add_tab_form').submit(function (e){
			 e.preventDefault();
			 addTab();
		 });
		 $('#update_tab_form').submit(function (e){
			 e.preventDefault();
			 updateTab();
		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initTabView();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openTabModal(tab_id){
	$('#updateTabModal').modal('show');
	initTabModal(tab_id);
}
async function initTabView(){
	$("#tab-datatable").dataTable().fnDestroy();
	$('#tab-datatable tbody > tr').remove();
	let data = await serviceGetTabs();
	$.each(data.tabs, function (i, tab) {
		let tabItem = '<tr>\n' +
			'              <td>'+ tab.title +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openTabModal(\''+ tab.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span>\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteTab(\''+ tab.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span>\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#tab-datatable tbody').append(tabItem);
	});
	$('#tab-datatable').DataTable({
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
async function initTabModal(tab_id){
	let data = await serviceGetTabById(tab_id);
	let tab = data.tabs;
	document.getElementById('update_tab_id').value = tab.id;
	document.getElementById('update_tab_title').value = tab.title;
}



async function addTab(){
	let tab_title = document.getElementById('tab_title').value;
	let formData = JSON.stringify({
		"title": tab_title
	});
	let returned = await servicePostAddTab(formData);
	if(returned){
		$("#add_tab_form").trigger("reset");
		initTabView();
	}
}
async function updateTab(){
	let tab_id = document.getElementById('update_tab_id').value;
	let tab_title = document.getElementById('update_tab_title').value;
	let formData = JSON.stringify({
		"title": tab_title
	});
	let returned = await servicePostUpdateTab(formData, tab_id);
	if(returned){
		$("#update_tab_form").trigger("reset");
		$('#updateTabModal').modal('hide');
		initTabView();
	}
}
async function deleteTab(tab_id){
	let returned = await serviceGetDeleteTab(tab_id);
	if(returned){
		initTabView();
	}
}
