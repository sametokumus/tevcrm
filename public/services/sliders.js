(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#add_slider_form").submit(function( event ) {
			 event.preventDefault();

			 var formData = new FormData();
			 formData.append('content_text', document.getElementById('slider_content_text').value);
			 formData.append('order', document.getElementById('slider_order').value);
			 formData.append('bg_url', document.getElementById('slider_bg_url').files[0]);
			 formData.append('image_url', document.getElementById('slider_image_url').files[0]);

			 servicePostAddSlider(formData);

		 });

		 $("#update_slider_form").submit(function( event ) {
			 event.preventDefault();

			 let slider_id = document.getElementById('update_slider_id').value;
			 var formData = new FormData();
			 formData.append('content_text', document.getElementById('update_slider_content_text').value);
			 formData.append('order', document.getElementById('update_slider_order').value);
			 formData.append('bg_url', document.getElementById('update_slider_bg_url').files[0]);
			 formData.append('image_url', document.getElementById('update_slider_image_url').files[0]);

			 servicePostUpdateSlider(slider_id, formData);

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initSlidersTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function addSliderCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#add_slider_form").trigger("reset");
	initSlidersTable();
}
async function updateSliderCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#update_slider_form").trigger("reset");
	$('#updateSliderModal').modal('hide');
	initSlidersTable();
}

function openSliderModal(slider_id){
	$('#updateSliderModal').modal('show');
	initSliderModal(slider_id);
}

async function initSlidersTable(){
	$("#slider-datatable").dataTable().fnDestroy();
	$('#slider-datatable tbody > tr').remove();

	let data = await serviceGetSliders();
	console.log(data)
	$.each(data.sliders, function (i, slider) {
		let sliderItem = '<tr>\n' +
			'              <td>'+ slider.id +'</td>\n' +
			'              <td><img src="https://api-kablocu.wimco.com.tr'+ slider.bg_url +'" style="height: 80px;"></td>\n' +
			'              <td><img src="https://api-kablocu.wimco.com.tr'+ slider.image_url +'" style="height: 80px;"></td>\n' +
			'              <td>'+ slider.content_text +'</td>\n' +
			'              <td>'+ slider.order +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openSliderModal(\''+ slider.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteSlider(\''+ slider.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#slider-datatable tbody').append(sliderItem);
	});

	$('#slider-datatable').DataTable({
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

async function initSliderModal(slider_id){
	let data = await serviceGetSliderById(slider_id);
	let slider = data.sliders;
	document.getElementById('update_slider_id').value = slider.id;
	document.getElementById('update_slider_content_text').value = slider.content_text;
	document.getElementById('update_slider_order').value = slider.order;
}

async function deleteSlider(slider_id){
	let returned = await serviceGetDeleteSlider(slider_id);
	if(returned.status == 'success'){
		initSlidersTable();
	}
}