(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_status_form').submit(function (e){
			e.preventDefault();
            updateStatus();
		});
		$('#add_cancel_note_form').submit(function (e){
			e.preventDefault();
            addCancelNote();
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
	let data = await serviceGetActiveSales();
	$("#sales-datatable").dataTable().fnDestroy();
	$('#sales-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.sales, function (i, sale) {
        let updated_at = "-";
        if (sale.updated_at != null){
            updated_at = formatDateAndTimeDESC(sale.updated_at, "/");
        }
        let status_class = "border-theme text-theme";
        let btn_list = '<div class="btn-list">\n';
        btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-theme" onclick="openStatusModal(\''+ sale.sale_id +'\', \''+ sale.status_id +'\')">\n' +
            '           <span class="fe fe-refresh-cw"> Durum Değiştir\n' +
            '        </button>\n';
        if (sale.status.action == "rfq"){
            status_class = "border-danger text-danger";
            btn_list += '<a href="offer-request/'+ sale.request_id +'" class="btn btn-sm btn-danger">Talebi Güncelle</a>\n' +
                '        <a href="offer/'+ sale.request_id +'" class="btn btn-sm btn-danger">RFQ Oluştur</a>\n';
        }else if (sale.status.action == "rfq-update"){
            status_class = "border-pink text-pink";
            btn_list += '<a href="offer-request/'+ sale.request_id +'" class="btn btn-sm btn-pink">Talebi Güncelle</a>\n' +
                '        <a href="offer/'+ sale.request_id +'" class="btn btn-sm btn-pink">RFQ Güncelle</a>\n';
        }else if (sale.status.action == "offer"){
            status_class = "border-warning text-warning";
            btn_list += '<a href="sw-2/'+ sale.request_id +'" class="btn btn-sm btn-warning">Teklif Oluştur</a>\n';
        }else if (sale.status.action == "offer-update"){
            status_class = "border-yellow text-yellow";
            btn_list += '<a href="sw-3/'+ sale.sale_id +'" class="btn btn-sm btn-yellow">Fiyatları Güncelle</a>\n';
        }else if (sale.status.action == "quote"){
            status_class = "border-lime text-lime";
            btn_list += '<a href="quote-print/'+ sale.sale_id +'" class="btn btn-sm btn-lime">Quatotion PDF</a>\n';
        }else if (sale.status.action == "oc-po"){
            status_class = "border-green text-green";
            btn_list += '<a href="order-confirmation-print/'+ sale.sale_id +'" class="btn btn-sm btn-green">Order Conf. PDF</a>\n';
            btn_list += '<a href="purchasing-order-print/'+ sale.sale_id +'" class="btn btn-sm btn-green">PO PDF</a>\n';
        }else if (sale.status.action == "inv"){
            status_class = "border-indigo text-indigo";
            btn_list += '<a href="sale-detail/'+ sale.sale_id +'" class="btn btn-sm btn-info">Satış Detayı</a>\n';

            btn_list += '<a href="proforma-invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Proforma INV. PDF</a>\n';
            btn_list += '<a href="invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">INV. PDF</a>\n';
        }

        btn_list += '</div>';
        let status = '<span class="badge border '+ status_class +' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center" onclick="openStatusModal(\''+ sale.sale_id +'\', \''+ sale.status_id +'\')"><i class="fa fa-circle fs-9px fa-fw me-5px"></i> '+ sale.status_name +'</span>';

        let price = "";
        if (sale.grand_total_with_shipping != null){
            price = sale.grand_total_with_shipping;
        }else{
            if (sale.grand_total != null){
                price = sale.grand_total;
            }
        }
        let saleItem = '<tr>\n' +
			'              <td>'+ sale.id +'</td>\n' +
			'              <td>'+ sale.sale_id +'</td>\n' +
			'              <td>'+ sale.request.authorized_personnel.name +' '+ sale.request.authorized_personnel.surname +'</td>\n' +
			'              <td>'+ sale.request.company.name +'</td>\n' +
			'              <td>'+ sale.request.company_employee.name +'</td>\n' +
			'              <td>'+ sale.request.product_count +'</td>\n' +
			'              <td>'+ changeCommasToDecimal(price) +'</td>\n' +
			'              <td>'+ status +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC(sale.created_at, "/") +'</td>\n' +
			'              <td>'+ updated_at +'</td>\n' +
			'              <td>\n' +
			'                  '+ btn_list +'\n' +
			'              </td>\n' +
			'          </tr>';
		$('#sales-datatable tbody').append(saleItem);
	});
	$('#sales-datatable').DataTable({
		responsive: false,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 },
			{ responsivePriority: 3, targets: 6 },
			{ responsivePriority: 4, targets: 7 },
			{ responsivePriority: 5, targets: 8 },
			{ responsivePriority: 6, targets: 1 }
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

function openStatusModal(sale_id, status_id){
    $('#updateStatusModal').modal('show');
    initStatusModal(sale_id, status_id);
}
async function updateStatus(){
    let status_id = document.getElementById('update_sale_status').value;
    let sale_id = document.getElementById('update_sale_id').value;
    let user_id = localStorage.getItem('userId');
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "status_id": status_id,
        "user_id": user_id
    });
    let data = await servicePostUpdateSaleStatus(formData);
    if(data.status == "success"){
        if (data.object.period == "cancelled"){
            $("#update_status_form").trigger("reset");
            $('#updateStatusModal').modal('hide');
            $('#addCancelNoteModal').modal('show');
            document.getElementById('cancel_sale_id').value = sale_id;
        }else {
            $("#update_status_form").trigger("reset");
            $('#updateStatusModal').modal('hide');
            initSales();
        }
    }
}
async function initStatusModal(sale_id, status_id){
    let data = await serviceGetStatuses();
    console.log(data)
    let statuses = data.statuses;
    $('#update_sale_status option').remove();
    $.each(statuses, function (i, status){
        let selected = '';
        if(status.id == status_id){selected = 'selected';}
        $('#update_sale_status').append('<option value="'+ status.id +'" '+ selected +'>'+ status.name +'</option>');
    });
    document.getElementById('update_sale_id').value = sale_id;
}
async function addCancelNote(){
    let sale_id = document.getElementById('cancel_sale_id').value;
    let user_id = localStorage.getItem('userId');
    let note = tinymce.get('cancel_sale_note').getContent();
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "user_id": user_id,
        "note": note
    });
    let returned = await servicePostAddCancelSaleNote(formData);
    if(returned){
        $("#add_cancel_note_form").trigger("reset");
        $('#addCancelNoteModal').modal('hide');
        initSales();
    }
}
