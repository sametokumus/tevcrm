(function($) {
    "use strict";

	$(document).ready(function() {
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initCustomers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCustomers(){
	let data = await serviceGetCustomers();
    $("#customer-datatable").dataTable().fnDestroy();
    $('#customer-datatable tbody > tr').remove();

    $.each(data.customers, function (i, customer) {

        let item = '<tr>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(customer.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(customer.email) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(customer.phone) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(customer.fax) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(customer.address) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="customer-dashboard/'+ customer.id +'" id="bDel" type="button" class="btn  btn-sm btn-theme">\n' +
            '                          <span class="bi bi-pencil-square"></span>\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteCompany(\''+ customer.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#customer-datatable tbody').append(item);
    });

    $('#customer-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Yeni Müşteri',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location = '/add-customer';
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function deleteCompany(customer_id){
    let returned = await serviceGetDeleteCustomer(customer_id);
    if(returned){
        initCustomers();
    }
}
