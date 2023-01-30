(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_status_form').submit(function (e){
			e.preventDefault();
		});
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        initSales();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initSales(){
	let data = await serviceGetSales();
	$("#sales-datatable").dataTable().fnDestroy();
	$('#sales-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.sales, function (i, sale) {
		let saleItem = '<tr>\n' +
			'              <td>'+ sale.id +'</td>\n' +
			'              <td>'+ sale.sale_id +'</td>\n' +
			'              <td>'+ sale.request.authorized_personnel.name +' '+ sale.request.authorized_personnel.surname +'</td>\n' +
			'              <td>'+ sale.request.customer.name +'</td>\n' +
			'              <td>'+ sale.request.customer_employee.name +'</td>\n' +
			'              <td>'+ sale.request.product_count +'</td>\n' +
			'              <td>'+ sale.status_name +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="offer-request/'+ sale.request_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Talebi Güncelle</span></a>\n' +
			'                      <a href="offer/'+ sale.request_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklifler</span></a>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#sales-datatable tbody').append(saleItem);
	});
	$('#sales-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
		buttons: [
            'excel',
            'pdf',
            {
                text: 'Talep Oluştur',
                action: function ( e, dt, node, config ) {
                    window.location = '/offer-request';
                }
            }
        ],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		},
		order: [[0, 'desc']],
	});
}
