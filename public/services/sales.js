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
        initSales();

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

async function initSales(){
    // $.fn.dataTable.moment( 'DD/MM/YYYY' );

    let filter = localStorage.getItem('sale_filter');
    let data;
    if (filter == 'true'){
        let owner = localStorage.getItem('sale_filter_owner');
        let authorized_personnel = localStorage.getItem('sale_filter_authorized_personnel');
        let purchasing_staff = localStorage.getItem('sale_filter_purchasing_staff');
        let company = localStorage.getItem('sale_filter_company');
        let company_employee = localStorage.getItem('sale_filter_company_employee');
        let status = localStorage.getItem('sale_filter_status');
        let formData = JSON.stringify({
            "owner": owner,
            "authorized_personnel": authorized_personnel,
            "purchasing_staff": purchasing_staff,
            "company": company,
            "company_employee": company_employee,
            "status": status
        });
        console.log(formData)
        data = await servicePostFilterSales(formData);
    }else{
        data = await serviceGetActiveSales();
    }
    console.log(data)
	$("#sales-datatable").dataTable().fnDestroy();
	$('#sales-datatable tbody > tr').remove();

	$.each(data.sales, function (i, sale) {
        let updated_at = formatDateAndTimeDESC2(sale.created_at, "/");
        // let updated_at = sale.created_at;
        if (sale.updated_at != null){
            updated_at = formatDateAndTimeDESC(sale.updated_at, "/");
        }
        // let status_class = "border-theme text-theme";
        status_class = "bg-theme border-none text-white shadow-xl";
        let btn_list = '<div class="btn-list">\n';
        // btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-theme" onclick="openStatusModal(\''+ sale.sale_id +'\', \''+ sale.status_id +'\')">\n' +
        //     '           <span class="fe fe-refresh-cw"> Durum Değiştir\n' +
        //     '        </button>\n';

        btn_list += '<a href="sale-detail/'+ sale.sale_id +'" class="btn btn-sm btn-info">Satış Detayı</a>\n';

        let noteClass = 'btn-warning';
        if (sale.sale_notes.length != 0){
            noteClass = 'btn-lime';
        }
        btn_list += '<button id="bDel" type="button" class="btn btn-sm '+ noteClass +'" onclick="openSaleNoteModal(\''+ sale.sale_id +'\')">\n' +
            '           <span class="fe fe-refresh-cw"> Not\n' +
            '        </button>\n';
        if (localStorage.getItem('userRole') == 1) {
            btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-outline-danger" onclick="openDeleteSale(\'' + sale.sale_id + '\')">\n' +
                '           <span class="fe fe-refresh-cw"> Sil\n' +
                '        </button>\n';
        }

        if (sale.status.action == "rfq"){
            // status_class = "border-danger text-danger";
            status_class = "bg-danger border-none text-white shadow-xl";
            btn_list += '<a href="offer-request-products/'+ sale.request_id +'" class="btn btn-sm btn-danger">Talebi Güncelle</a>\n' +
                '        <a href="offer/'+ sale.request_id +'" class="btn btn-sm btn-danger">RFQ Oluştur</a>\n';
        }else if (sale.status.action == "rfq-update"){
            // status_class = "border-pink text-pink";
            status_class = "bg-pink border-none text-white shadow-xl";
            btn_list += '<a href="offer-request-products/'+ sale.request_id +'" class="btn btn-sm btn-pink">Talebi Güncelle</a>\n' +
                '        <a href="offer/'+ sale.request_id +'" class="btn btn-sm btn-pink">Tedarikçi Fiyatları Gir</a>\n';
        }else if (sale.status.action == "offer"){
            // status_class = "border-warning text-warning";
            status_class = "bg-warning border-none text-white shadow-xl";
            btn_list += '<a href="sw-2-new/'+ sale.request_id +'" class="btn btn-sm btn-warning">Teklif Oluştur</a>\n';
        }else if (sale.status.action == "offer-update"){
            // status_class = "border-yellow text-yellow";
            status_class = "bg-yellow border-none text-white shadow-xl";
            btn_list += '<a href="sw-3/'+ sale.sale_id +'" class="btn btn-sm btn-yellow">Fiyatları Güncelle</a>\n';
        }else if (sale.status.action == "offer-price-rev"){
            // status_class = "border-yellow text-yellow";
            status_class = "bg-yellow border-none text-white shadow-xl";
            btn_list += '<a href="offer-rev/'+ sale.request_id +'" class="btn btn-sm btn-yellow">Tedarik Fiyatlarını Güncelle</a>\n';
        }else if (sale.status.action == "quote"){
            // status_class = "border-lime text-lime";
            status_class = "bg-lime border-none text-white shadow-xl";
            btn_list += '<a href="quote-print/'+ sale.sale_id +'" class="btn btn-sm btn-lime">Quatotion PDF</a>\n';
        }else if (sale.status.action == "offer-rev"){
            // status_class = "border-warning text-warning";
            status_class = "bg-warning border-none text-white shadow-xl";
            btn_list += '<a href="sw-3-rev/'+ sale.sale_id +'" class="btn btn-sm btn-warning">Teklifi Revize Et</a>\n';
        }else if (sale.status.action == "admin-conf"){
            // status_class = "border-yellow text-yellow";
            status_class = "bg-yellow border-none text-white shadow-xl";
            btn_list += '<a href="sw-4/'+ sale.sale_id +'" class="btn btn-sm btn-yellow">Teklifi Onayla</a>\n';
        }else if (sale.status.action == "admin-conf-success"){
            // status_class = "border-default text-default";
            status_class = "bg-default border-none text-white shadow-xl";
            btn_list += '<a href="quote-print/'+ sale.sale_id +'" class="btn btn-sm btn-lime">Quatotion PDF</a>\n';
        }else if (sale.status.action == "admin-rev-conf"){
            // status_class = "border-yellow text-yellow";
            status_class = "bg-yellow border-none text-white shadow-xl";
            btn_list += '<a href="sw-4-rev/'+ sale.sale_id +'" class="btn btn-sm btn-yellow">Revize Teklifi Onayla</a>\n';
        }else if (sale.status.action == "admin-rev-conf-success"){
            // status_class = "border-default text-default";
            status_class = "bg-default border-none text-white shadow-xl";
            btn_list += '<a href="quote-print/'+ sale.sale_id +'" class="btn btn-sm btn-lime">Rv. Quatotion PDF</a>\n';
        }else if (sale.status.action == "oc-po"){
            // status_class = "border-green text-green";
            status_class = "bg-green border-none text-white shadow-xl";
            btn_list += '<a href="order-confirmation-print/'+ sale.sale_id +'" class="btn btn-sm btn-green">Order Conf. PDF</a>\n';
            let role = localStorage.getItem('userRole');
            if (role == 1 || role == 2 || role == 3){
                btn_list += '<a href="purchasing-order-print/'+ sale.sale_id +'" class="btn btn-sm btn-green">Supp. PO PDF</a>\n';
            }
        }else if (sale.status.action == "inv"){
            // status_class = "border-indigo text-indigo";
            status_class = "bg-indigo border-none text-white shadow-xl";

            btn_list += '<a href="proforma-invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Proforma INV. PDF</a>\n';
            btn_list += '<a href="invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Inv. PDF</a>\n';
        }else if (sale.status.action == "pl-inv"){
            // status_class = "border-indigo text-indigo";
            status_class = "bg-indigo border-none text-white shadow-xl";

            // btn_list += '<a href="packing-list-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Packing List PDF</a>\n';
            btn_list += '<a href="packing-list/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Packing Lists</a>\n';
            btn_list += '<a href="invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Inv. PDF</a>\n';
        }else if (sale.status.action == "pl-inv-exp"){
            // status_class = "border-indigo text-indigo";
            status_class = "bg-indigo border-none text-white shadow-xl";

            // btn_list += '<a href="packing-list-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Packing List PDF</a>\n';
            btn_list += '<a href="packing-list/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Packing Lists</a>\n';
            btn_list += '<a href="invoice-print/'+ sale.sale_id +'" class="btn btn-sm btn-indigo">Inv. PDF</a>\n';
            btn_list += '<button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openShipmentPrice(\'' + sale.sale_id + '\')">\n' +
                '           <span class="fe fe-refresh-cw"> Nakliye Tutarı\n' +
                '        </button>\n';
        }else if (sale.status.action == "admin-conf-failed" || sale.status.action == "admin-rev-conf-failed"){
            // status_class = "border-default text-default";
            status_class = "bg-default border-none text-white shadow-xl";
        }

        // if (sale.status.period == "approved" || sale.status.period == "completed"){
        //     btn_list += '<a href="mobile-documents/'+ sale.id +'" class="btn btn-sm btn-white">Mobil Dökümanlar</a>\n';
        // }

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
            authorization = 'disabled';
        }
        let pinned = '<a href="#" onclick="addSalePin(event, \''+ sale.sale_id +'\');"><span class="d-none">0</span><i class="far fa-fw fa-star"></i></a>';
        let pinned_class = '';
        if (sale.pinned == 1){
            pinned = '<a href="#" onclick="deleteSalePin(event, \''+ sale.sale_id +'\');"><span class="d-none">1</span><i class="fas fa-fw fa-star"></i></a>';
            pinned_class = 'is_pinned';
        }

        let saleItem = '<tr class="'+ authorization + ' ' + pinned_class +'">\n' +
            '              <td class="bg-dark-100">'+ pinned+'</td>\n' +
            '              <td class="bg-dark-100">'+ (i+1)+'</td>\n' +
			'              <td class="bg-dark-100">'+ sale.owner_short_code +'-'+ sale.id +'</td>\n' +
            '              <td class="bg-dark-100">'+ sale.request.company.name +'</td>\n' +
			'              <td class="bg-dark-100">'+ authorized_name +'</td>\n' +
			'              <td>'+ employee_name +'</td>\n' +
			'              <td>'+ sale.request.product_count +'</td>\n' +
			'              <td>'+ changeCommasToDecimal(price) +'</td>\n' +
			'              <td>'+ checkNull(sale.currency) +'</td>\n' +
			'              <td>'+ status +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC2(sale.created_at, "/") +'</td>\n' +
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
                targets: 3,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 30) + '...' :
                        data;
                }
            },
            {
                type: 'date',
                targets: 11,
                render: function(data, type, row) {
                    return moment(data, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                }
            }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: [
            'excel',
            'pdf',
            {
                text: 'Talep Oluştur',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    window.location = '/new-offer-request';
                }
            }
        ],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: [
            [0, 'desc'],
            [11, 'desc']
        ],
        fixedColumns: {
            left: 5
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
        let forced = '';
        if (status.forced == 1){forced = '(*)';}
        let selected = '';
        if(status.id == status_id){selected = 'selected';}
        $('#update_sale_status').append('<option value="'+ status.id +'" '+ selected +'>'+ status.name +' '+ forced +'</option>');
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

async function openShipmentPrice(sale_id){
    $('#addShipmentPriceModal').modal('show');
    document.getElementById('add_shipment_sale_id').value = sale_id;

    let data = await serviceGetSaleExpenseByCategoryId(sale_id, 1);
    console.log(data)

    if (data.expenses != null){
        document.getElementById('add_shipment_price').value = changeCommasToDecimal(data.expenses.price);
        document.getElementById('add_shipment_currency').value = data.expenses.currency;
    }else{
        document.getElementById('add_shipment_price').value = "";
        document.getElementById('add_shipment_currency').value = "TRY";
    }


}
async function addShipmentPrice() {
    let sale_id = document.getElementById('add_shipment_sale_id').value;
    let price = document.getElementById('add_shipment_price').value;
    let currency = document.getElementById('add_shipment_currency').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "category_id": 1,
        "price": changePriceToDecimal(price),
        "currency": currency
    });
    console.log(formData)

    let returned = await servicePostAddSaleExpense(formData);
    if (returned){
        $('#addShipmentPriceModal').modal('hide');
        document.getElementById('add_shipment_sale_id').value = '';
    }
}


async function addSalePin(event, sale_id){
    event.preventDefault();
    let returned = await serviceGetAddSalePin(sale_id);
    if(returned){
        await initSales();
    }
}
async function deleteSalePin(event, sale_id){
    event.preventDefault();
    let returned = await serviceGetDeleteSalePin(sale_id);
    if(returned){
        await initSales();
    }
}
