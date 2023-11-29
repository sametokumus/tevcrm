(function($) {
    "use strict";

	$(document).ready(function() {

		$('#delete_sale_form').submit(function (e){
			e.preventDefault();
            deleteSale();
		});
		$('#update_status_form').submit(function (e){
			e.preventDefault();
            updateStatus();
		});
		$('#add_sale_note_form').submit(function (e){
			e.preventDefault();
            addSaleNote();
		});
		$('#add_cancel_note_form').submit(function (e){
			e.preventDefault();
            addCancelNote();
		});
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        initSales();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('sale_filter_company').value;
    getEmployeesAddSelectIdWithZero(company_id, 'sale_filter_company_employee');
}

async function initSales(){

    let data = await serviceGetCompletedAccountingSales();
    console.log(data)
	$("#sales-datatable").dataTable().fnDestroy();
	$('#sales-datatable tbody > tr').remove();

	$.each(data.sales, function (i, sale) {
        let updated_at = formatDateAndTimeDESC2(sale.created_at, "/");
        // let updated_at = sale.created_at;
        if (sale.updated_at != null){
            updated_at = formatDateAndTimeDESC(sale.updated_at, "/");
        }
        let status_class = "border-theme text-theme";
        let btn_list = '<div class="btn-list">\n';

        btn_list += '<a href="accounting-detail/'+ sale.sale_id +'" class="btn btn-sm btn-info">Muhasebe Detayı</a>\n';
        btn_list += '<a href="sale-detail/'+ sale.sale_id +'" class="btn btn-sm btn-info">Satış Detayı</a>\n';

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
        let employee_name = '';
        if (sale.request.company_employee != null){
            employee_name = sale.request.company_employee.name;
        }
        let authorized_name = '';
        if (sale.request.authorized_personnel != null){
            authorized_name = sale.request.authorized_personnel.name + ' ' + sale.request.authorized_personnel.surname;
        }
        let authorization = '';
        if (sale.authorization == 0){
            authorization = 'class="disabled"';
        }

        let saleItem = '<tr '+ authorization +'>\n' +
            '              <td class="bg-dark-100">'+ (i+1)+'</td>\n' +
			'              <td class="bg-dark-100">'+ sale.owner_short_code +'-'+ sale.id +'</td>\n' +
            '              <td class="bg-dark-100">'+ sale.request.company.name +'</td>\n' +
			'              <td class="bg-dark-100">'+ authorized_name +'</td>\n' +
			'              <td>'+ employee_name +'</td>\n' +
			'              <td>'+ sale.request.product_count +'</td>\n' +
			'              <td>'+ changeCommasToDecimal(price) +'</td>\n' +
			'              <td>'+ checkNull(sale.currency) +'</td>\n' +
			'              <td>'+ status +'</td>\n' +
            '              <td class="d-none">'+ formatDateAndTimeDESC2(sale.created_at, "/") +'</td>\n' +
            '              <td>'+ updated_at +'</td>\n' +
            '              <td>'+ sale.diff_last_day +'</td>\n' +
			'              <td>\n' +
			'                  '+ btn_list +'\n' +
			'              </td>\n' +
			'          </tr>';
		$('#sales-datatable tbody').append(saleItem);
	});

	$('#sales-datatable').DataTable({
		responsive: false,
		columnDefs: [
            {
                targets: 2,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 30) + '...' :
                        data;
                }
            },
            {
                type: 'date',
                targets: 10,
                render: function(data, type, row) {
                    return moment(data, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                }
            }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: [],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: [[10, 'desc']],
        fixedColumns: {
        left: 4
        }
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
    let data = await serviceGetChangeableStatuses();
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
    let note = document.getElementById('cancel_sale_note').value;
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

async function openSaleNoteModal(sale_id){
    $('#addSaleNoteModal').modal('show');
    document.getElementById('add_note_sale_id').value = sale_id;

    let data = await serviceGetSaleNotes(sale_id);
    console.log(data)

    $('#sales-notes-table tbody tr').remove();

    $.each(data.sale_notes, function (i, note) {

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ note.user_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td>'+ note.note +'</td>\n' +
            '       </tr>';

        $('#sales-notes-table tbody').append(item);
    });

}
async function addSaleNote() {
    let user_id = localStorage.getItem('userId');
    let sale_id = document.getElementById('add_note_sale_id').value;
    let note = document.getElementById('add_sale_note_description').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "user_id": user_id,
        "note": note
    });


    let returned = await servicePostAddSaleNote(formData);
    if (returned){
        showAlert("Not Eklendi");
        $('#addSaleNoteModal').modal('hide');
        $('#add_sale_note_description').summernote('reset', true);
        document.getElementById('add_sale_note_id').value = '';
    }else{
        alert("Not Eklerken Hata Oluştu")
    }
}

async function openDeleteSale(sale_id){
    $('#deleteSaleModal').modal('show');
    document.getElementById('delete_sale_id').value = sale_id;
}

async function deleteSale(){
    let sale_id = document.getElementById('delete_sale_id').value;
    let returned = await serviceGetDeleteSale(sale_id);
    if(returned){
        $('#deleteSaleModal').modal('hide');
        await initSales();
    }
}
