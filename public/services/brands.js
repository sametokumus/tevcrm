(function($) {
    "use strict";

	 $(document).ready(function() {

		 $("#add_brand_form").submit(function( event ) {
			 event.preventDefault();

			 var formData = new FormData();
			 formData.append('name', document.getElementById('brand_name').value);

			 servicePostAddBrand(formData);

		 });

		 $("#update_brand_form").submit(function( event ) {
			 event.preventDefault();

			 let brand_id = document.getElementById('update_brand_id').value;
			 var formData = new FormData();
			 formData.append('name', document.getElementById('update_brand_name').value);

			 servicePostUpdateBrand(brand_id, formData);

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initBrandView();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function addBrandCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#add_brand_form").trigger("reset");
	initBrandView();
}
async function updateBrandCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#update_brand_form").trigger("reset");
	$('#updateBrandModal').modal('hide');
	initBrandView();
}

function openBrandModal(brand_id){
	$('#updateBrandModal').modal('show');
	initBrandModal(brand_id);
}

async function initBrandView(){
	$("#brand-datatable").dataTable().fnDestroy();
	$('#brand-datatable tbody > tr').remove();

	let data = await serviceGetBrands();
	$.each(data.brands, function (i, brand) {
		let typeItem = '<tr>\n' +
			'              <td>'+ brand.id +'</td>\n' +
			'              <td>'+ brand.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openBrandModal(\''+ brand.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteBrand(\''+ brand.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#brand-datatable tbody').append(typeItem);
	});

	$('#brand-datatable').DataTable({
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

async function initBrandModal(brand_id){
	let data = await serviceGetBrandById(brand_id);
	let brand = data.brands;
	document.getElementById('update_brand_id').value = brand.id;
    document.getElementById('update_brand_name').value = brand.name;
}

async function deleteBrand(brand_id){
	let returned = await serviceGetDeleteBrand(brand_id);
	if(returned){
		initBrandView();
	}
}
