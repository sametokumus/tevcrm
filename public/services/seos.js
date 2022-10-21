(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#update_seo_form").submit(async function( event ) {
			 event.preventDefault();

			 let seo_id = document.getElementById('update_seo_id').value;
			 let page = document.getElementById('update_seo_page').value;
			 let title = document.getElementById('update_seo_title').value;
			 let keywords = document.getElementById('update_seo_keywords').value;
			 let description = document.getElementById('update_seo_description').value;
			 let formData = JSON.stringify({
				 "page":page,
				 "title":title,
				 "keywords":keywords,
				 "description":description
			 });

			 let returned = await servicePostUpdateSeo(seo_id, formData);
			 if(returned){
				 $("#update_seo_form").trigger("reset");
				 $('#updateSeoModal').modal('hide');
				 initSeosTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initSeosTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openSeoModal(seo_id){
	$('#updateSeoModal').modal('show');
	initSeoModal(seo_id);
}

async function initSeosTable(){
	$("#seo-datatable").dataTable().fnDestroy();
	$('#seo-datatable tbody > tr').remove();

	let data = await serviceGetSeos();
	$.each(data.seos, function (i, seo) {
		let seoItem = '<tr>\n' +
			'              <td>'+ seo.id +'</td>\n' +
			'              <td>'+ seo.page +'</td>\n' +
			'              <td>'+ seo.title +'</td>\n' +
			'              <td>'+ seo.keywords +'</td>\n' +
			'              <td>'+ seo.description +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openSeoModal(\''+ seo.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#seo-datatable tbody').append(seoItem);
	});

	$('#seo-datatable').DataTable({
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

async function initSeoModal(seo_id){
	let data = await serviceGetSeoById(seo_id);
	let seo = data.seos;

	document.getElementById('update_seo_id').value = seo.id;
	document.getElementById('update_seo_page').value = seo.page;
	document.getElementById('update_seo_title').value = seo.title;
	document.getElementById('update_seo_keywords').value = seo.keywords;
	document.getElementById('update_seo_description').value = seo.description;
}

