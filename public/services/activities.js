(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_shipment_price").maskMoney({thousands:'.', decimal:','});

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
		$('#sale_filter_form').submit(async function (e){
			e.preventDefault();
            await localStorage.setItem('sale_filter', 'true');
            localStorage.setItem('sale_filter_owner', document.getElementById('sale_filter_owner').value);
            localStorage.setItem('sale_filter_authorized_personnel', document.getElementById('sale_filter_authorized_personnel').value);
            localStorage.setItem('sale_filter_purchasing_staff', document.getElementById('sale_filter_purchasing_staff').value);
            localStorage.setItem('sale_filter_company', document.getElementById('sale_filter_company').value);
            localStorage.setItem('sale_filter_company_employee', document.getElementById('sale_filter_company_employee').value);
            localStorage.setItem('sale_filter_status', document.getElementById('sale_filter_status').value);
            await initFilter();
            await initSales();
		});
        $('#add_shipment_price_form').submit(function (e){
            e.preventDefault();
            addShipmentPrice();
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        initFilter();
        initActivities();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initFilter() {
    await getOwnersAddSelectId('sale_filter_owner');
    await getAdminsAddSelectId('sale_filter_authorized_personnel');
    await getAdminsAddSelectId('sale_filter_purchasing_staff');
    await getCustomersAndPotentialsAddSelectIdWithZero('sale_filter_company');

    let data = await serviceGetStatuses();
    let statuses = data.statuses;
    $('#sale_filter_status option').remove();
    $('#sale_filter_status').append('<option value="0">Durum Seçiniz</option>');
    $.each(statuses, function (i, status){
        $('#sale_filter_status').append('<option value="'+ status.id +'">'+ status.name +'</option>');
    });

    let filter = localStorage.getItem('sale_filter');
    if (filter == 'true'){

        await getEmployeesAddSelectIdWithZero(localStorage.getItem('sale_filter_company'), 'sale_filter_company_employee');

        document.getElementById('sale_filter_owner').value = localStorage.getItem('sale_filter_owner');
        document.getElementById('sale_filter_authorized_personnel').value = localStorage.getItem('sale_filter_authorized_personnel');
        document.getElementById('sale_filter_purchasing_staff').value = localStorage.getItem('sale_filter_purchasing_staff');
        document.getElementById('sale_filter_company').value = localStorage.getItem('sale_filter_company');
        document.getElementById('sale_filter_company_employee').value = localStorage.getItem('sale_filter_company_employee');
        document.getElementById('sale_filter_status').value = localStorage.getItem('sale_filter_status');
    }


}

async function removeFilter(){
    localStorage.setItem('sale_filter', 'false');
    localStorage.removeItem('sale_filter_owner');
    localStorage.removeItem('sale_filter_authorized_personnel');
    localStorage.removeItem('sale_filter_purchasing_staff');
    localStorage.removeItem('sale_filter_company');
    localStorage.removeItem('sale_filter_company_employee');
    localStorage.removeItem('sale_filter_status');
    $('#sale_filter_company_employee option').remove();

    initFilter();
    initSales();
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('sale_filter_company').value;
    getEmployeesAddSelectIdWithZero(company_id, 'sale_filter_company_employee');
}

async function initActivities(){

    let filter = localStorage.getItem('sale_filter');
    let data = await serviceGetActivities();

    console.log(data)
	$("#activities-datatable").dataTable().fnDestroy();
	$('#activities-datatable tbody > tr').remove();

    $.each(data.activities, function (i, activity) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateCompanyActivityModal(\''+ activity.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteActivity(\''+ activity.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th class="bg-dark">'+ activity.id +'</th>\n' +
            '           <td class="bg-dark">'+ activity.company.name +'</td>\n' +
            '           <td class="bg-dark">'+ activity.employee.name +'</td>\n' +
            '           <td class="bg-dark">'+ activity.user.name +' '+ activity.user.surname +'</td>\n' +
            '           <td>'+ activity.type.name +'</td>\n' +
            '           <td>'+ activity.title +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.start, "/") +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.end, "/") +'</td>\n' +
            '           <td>'+ activity.task_count +' görev ('+ activity.completed_task_count +' tamamlanan)</td>\n' +
            '           <td></td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#activities-datatable tbody').append(item);
    });

	$('#activities-datatable').DataTable({
		responsive: false,
		columnDefs: [
            {
                targets: 3,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 30) + '...' :
                        data;
                }
            }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: [
            {
                text: 'Aktivite Oluştur',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    openAddCompanyActivityModal();
                }
            }
        ],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: false,
        fixedColumns: {
        left: 4
        }
	});
}

