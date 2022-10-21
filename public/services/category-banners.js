(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#update_banner_form").submit(function( event ) {
			 event.preventDefault();

			 let category_id = document.getElementById('update_category_id').value;
			 var formData = new FormData();
			 formData.append('title', document.getElementById('update_category_title').value);
			 formData.append('subtitle', document.getElementById('update_category_subtitle').value);
			 formData.append('btn_text', document.getElementById('update_category_btn_text').value);
			 formData.append('btn_link', document.getElementById('update_category_btn_link').value);
			 formData.append('image_url', document.getElementById('update_category_image_url').files[0]);

			 servicePostUpdateHomeCategoryBanner(category_id, formData);

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initBannersTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function updateHomeCategoryBannerCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#update_banner_form").trigger("reset");
	$('#updateBannerModal').modal('hide');
	initBannersTable();
}

function openBannerModal(category_id){
	$('#updateBannerModal').modal('show');
	initBannerModal(category_id);
}

async function initBannersTable(){
	$("#banner-datatable").dataTable().fnDestroy();
	$('#banner-datatable tbody > tr').remove();

	let data = await serviceGetParentCategories();
	console.log(data)
	$.each(data.categories, function (i, category) {
		let bannerItem = '<tr>\n' +
			'              <td>'+ category.id +'</td>\n' +
			'              <td style="width: 320px;"><img src="https://api-kablocu.wimco.com.tr'+ category.image_url +'" style="width: 300px;"></td>\n' +
			'              <td>'+ category.title +'</td>\n' +
			'              <td>'+ category.subtitle +'</td>\n' +
			'              <td>'+ category.btn_text +'</td>\n' +
			'              <td>'+ category.btn_link +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openBannerModal(\''+ category.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#banner-datatable tbody').append(bannerItem);
	});

	$('#banner-datatable').DataTable({
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

async function initBannerModal(category_id){
	let data = await serviceGetCategoryById(category_id);
	let category = data.category;
	document.getElementById('update_category_id').value = category.id;
	document.getElementById('update_category_title').value = category.title;
	document.getElementById('update_category_subtitle').value = category.subtitle;
	document.getElementById('update_category_btn_text').value = category.btn_text;
	document.getElementById('update_category_btn_link').value = category.btn_link;
}

