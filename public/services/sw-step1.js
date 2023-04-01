(function($) {
    "use strict";

	$(document).ready(function() {
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		await initPage();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initPage(){
    await getCustomersAddSelectId('select_sales_company');
}

async function onChangeCompany(){
    let company_id = document.getElementById('select_sales_company').value;
    if (company_id != 0){
        await initOfferRequests(company_id);
    }else{
        $("#offer-requests-datatable").dataTable().fnDestroy();
        $('#offer-requests-datatable tbody > tr').remove();
        return false;
    }
}

async function initOfferRequests(company_id){
    let data = await serviceGetOfferRequestsByCompanyId(company_id);
    $("#offer-requests-datatable").dataTable().fnDestroy();
    $('#offer-requests-datatable tbody > tr').remove();

    console.log(data)
    $.each(data.offer_requests, function (i, offer_request) {
        let requestItem = '<tr>\n' +
            '              <td>'+ offer_request.id +'</td>\n' +
            '              <td>'+ offer_request.request_id +'</td>\n' +
            '              <td>'+ offer_request.authorized_personnel.name +' '+ offer_request.authorized_personnel.surname +'</td>\n' +
            '              <td>'+ offer_request.company.name +'</td>\n' +
            '              <td>'+ offer_request.company_employee.name +'</td>\n' +
            '              <td>'+ offer_request.product_count +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="sw-2/'+ offer_request.request_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Oluştur</span></a>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#offer-requests-datatable tbody').append(requestItem);
    });
    $('#offer-requests-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            'excel',
            'pdf'
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'desc']],
    });
}
