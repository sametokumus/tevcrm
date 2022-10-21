(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#add_popup_form").submit(async function( event ) {
			 event.preventDefault();

			 let items = document.getElementsByClassName("add-form-page");
			 let pages = "";
			 for (let i = 0; i < items.length; i++) {
				 if ($('#'+items[i].id).prop("checked")){
					 pages = pages + $('#'+items[i].id).attr('data-id') + ",";
				 }
			 }
			 pages = pages.slice(0, -1);

			 let start_date = document.getElementById('popup_start_date').value;
			 start_date = formatDateDESC(start_date, '-', '/') + " 00:00:00";
			 let end_date = document.getElementById('popup_end_date').value;
			 end_date = formatDateDESC(end_date, '-', '/') + " 23:59:59";


			 let formData = new FormData();
			 formData.append('title', document.getElementById('popup_title').value);
			 formData.append('subtitle', document.getElementById('popup_subtitle').value);
			 formData.append('description', document.getElementById('popup_description').value);
			 formData.append('pages', pages);
			 formData.append('start_date', start_date);
			 formData.append('end_date', end_date);
			 formData.append('image_url', document.getElementById('popup_image_url').files[0]);

			 servicePostAddPopup(formData);

		 });

		 $("#update_popup_form").submit(function( event ) {
			 event.preventDefault();

			 let items = document.getElementsByClassName("update-form-page");
			 let pages = "";
			 for (let i = 0; i < items.length; i++) {
				 if ($('#'+items[i].id).prop("checked")){
					 pages = pages + $('#'+items[i].id).attr('data-id') + ",";
				 }
			 }
			 pages = pages.slice(0, -1);

			 let start_date = document.getElementById('update_popup_start_date').value;
			 start_date = formatDateDESC(start_date, '-', '/') + " 00:00:00";
			 let end_date = document.getElementById('update_popup_end_date').value;
			 end_date = formatDateDESC(end_date, '-', '/') + " 23:59:59";

			 let popup_id = document.getElementById('update_popup_id').value;
			 var formData = new FormData();
			 formData.append('title', document.getElementById('update_popup_title').value);
			 formData.append('subtitle', document.getElementById('update_popup_subtitle').value);
			 formData.append('description', document.getElementById('update_popup_description').value);
			 formData.append('pages', pages);
			 formData.append('start_date', start_date);
			 formData.append('end_date', end_date);
			 formData.append('image_url', document.getElementById('update_popup_image_url').files[0]);

			 servicePostUpdatePopup(popup_id, formData);

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initPopupPages();
		initPopupsTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function addPopupCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#add_popup_form").trigger("reset");
	initPopupsTable();
}
async function updatePopupCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#update_popup_form").trigger("reset");
	$('#updatePopupModal').modal('hide');
	initPopupsTable();
}

function openPopupModal(popup_id){
	$('#updatePopupModal').modal('show');
	initPopupModal(popup_id);
}

async function initPopupsTable(){
	$("#popup-datatable").dataTable().fnDestroy();
	$('#popup-datatable tbody > tr').remove();

	let data = await serviceGetPopups();
	console.log(data)
	$.each(data.popups, function (i, popup) {
		let popupItem = '<tr>\n' +
			'              <td>'+ popup.id +'</td>\n' +
			'              <td><img src="https://api-kablocu.wimco.com.tr'+ popup.image_url +'" style="height: 80px;"></td>\n' +
			'              <td>'+ popup.title +'</td>\n' +
			'              <td>'+ popup.subtitle +'</td>\n' +
			'              <td>'+ popup.description +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openPopupModal(\''+ popup.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deletePopup(\''+ popup.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n';
		if(popup.show_popup == 0) {
			popupItem += '             <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="showPopupOnSite(\'' + popup.id + '\')">\n' +
				'                          <span class="fe fe-trash-2"> </span> Sitede Göster\n' +
				'                      </button>\n';
		}else{
			popupItem += '             <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="hidePopupOnSite(\'' + popup.id + '\')">\n' +
				'                          <span class="fe fe-trash-2"> </span> Sitede Gizle\n' +
				'                      </button>\n';
		}
		if(popup.show_form == 0) {
			popupItem += '             <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="showPopupFormOnSite(\'' + popup.id + '\')">\n' +
				'                          <span class="fe fe-trash-2"> </span> Formu Göster\n' +
				'                      </button>\n';
		}else{
			popupItem += '             <button id="bDel" type="button" class="btn  btn-sm btn-warning" onclick="hidePopupFormOnSite(\'' + popup.id + '\')">\n' +
				'                          <span class="fe fe-trash-2"> </span> Formu Gizle\n' +
				'                      </button>\n';
		}
		popupItem += '         </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#popup-datatable tbody').append(popupItem);
	});

	$('#popup-datatable').DataTable({
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

async function initPopupPages(){
	$('#add_popup_pages .custom-control').remove();
	$('#update_popup_pages .custom-control').remove();

	let data = await serviceGetSeos();
	$.each(data.seos, function (i, seo) {
		let checkItem = '<label class="custom-control custom-checkbox">\n' +
			'                <input type="checkbox" class="custom-control-input add-form-page" id="popup_page'+ seo.id +'" data-id="'+ seo.id +'" value="1">\n' +
			'                <span class="custom-control-label">'+ seo.page +'</span>\n' +
			'            </label>';
		let checkItem2 = '<label class="custom-control custom-checkbox">\n' +
			'                <input type="checkbox" class="custom-control-input update-form-page" id="update_popup_page'+ seo.id +'" data-id="'+ seo.id +'" value="1">\n' +
			'                <span class="custom-control-label">'+ seo.page +'</span>\n' +
			'            </label>';
		$('#add_popup_pages').append(checkItem);
		$('#update_popup_pages').append(checkItem2);
	});
}

async function initPopupModal(popup_id){
	let data = await serviceGetPopupById(popup_id);
	let popup = data.popup;
	document.getElementById('update_popup_id').value = popup.id;
	document.getElementById('update_popup_title').value = popup.title;
	document.getElementById('update_popup_subtitle').value = popup.subtitle;
	document.getElementById('update_popup_description').value = popup.description;
	document.getElementById('update_popup_start_date').value = formatDateASC(popup.start_date, "/");
	document.getElementById('update_popup_end_date').value = formatDateASC(popup.end_date, "/");
	let pageArr = popup.pages.split(",");
	$.each(pageArr, function (i, page) {
		$('#update_popup_page'+page).prop("checked", true);
	});
}

async function deletePopup(popup_id){
	let returned = await serviceGetDeletePopup(popup_id);
	if(returned.status == 'success'){
		initPopupsTable();
	}
}

async function showPopupOnSite(popup_id){
	let returned = await serviceGetChangePopupStatus(popup_id, 1);
	if(returned.status == 'success'){
		initPopupsTable();
	}
}

async function hidePopupOnSite(popup_id){
	let returned = await serviceGetChangePopupStatus(popup_id, 0);
	if(returned.status == 'success'){
		initPopupsTable();
	}
}

async function showPopupFormOnSite(popup_id){
	let returned = await serviceGetChangePopupFormStatus(popup_id, 1);
	if(returned.status == 'success'){
		initPopupsTable();
	}
}

async function hidePopupFormOnSite(popup_id){
	let returned = await serviceGetChangePopupFormStatus(popup_id, 0);
	if(returned.status == 'success'){
		initPopupsTable();
	}
}