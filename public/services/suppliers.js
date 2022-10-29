(function($) {
    "use strict";

	$(document).ready(function() {

        $('#add_supplier_form').submit(function (e){
            e.preventDefault();
            addSupplier();
        });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        initSuppliers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initSuppliers(){
	let data = await serviceGetSuppliers();
	$("#supplier-datatable").dataTable().fnDestroy();
	$('#supplier-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.suppliers, function (i, supplier) {
		let userItem = '<tr>\n' +
			'              <td>'+ supplier.id +'</td>\n' +
			'              <td>'+ supplier.name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="supplier-detail/'+ supplier.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Tedarikçi Bilgileri</span></a>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#supplier-datatable tbody').append(userItem);
	});
	$('#supplier-datatable').DataTable({
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
		},
		order: [[0, 'asc']],
	});
}


async function addSupplier(){
    let supplier_name = document.getElementById('add_supplier_name').value;
    let formData = JSON.stringify({
        "name": supplier_name
    });

    let returned = await servicePostAddSupplier(formData);
    if(returned){
        $("#add_supplier_form").trigger("reset");
        initSuppliers();
    }
}

