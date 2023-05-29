(function($) {
    "use strict";

	$(document).ready(function() {
        $('#add_company_form').submit(function (e){
            e.preventDefault();
            let isPotential = false;
            let isCustomer = false;
            let isSupplier = false;
            if(document.getElementById('add_company_is_potential_customer').checked){
                isPotential = true;
            }
            if(document.getElementById('add_company_is_customer').checked){
                isCustomer = true;
            }
            if(document.getElementById('add_company_is_supplier').checked){
                isSupplier = true;
            }
            if (isPotential || isCustomer || isSupplier) {
                addCompany();
            }else{
                alert('Firma türü seçimi zorunludur.')
            }
        });

        $('#add_company_is_potential_customer').click(function (e){
            if(document.getElementById('add_company_is_potential_customer').checked){
                document.getElementById('add_company_is_customer').checked = false;
            }
        });

        $('#add_company_is_customer').click(function (e){
            if(document.getElementById('add_company_is_customer').checked){
                document.getElementById('add_company_is_potential_customer').checked = false;
            }
        });

        $('#update_company_form').submit(function (e){
            e.preventDefault();
            updateCompany();
        });

        $('#update_company_is_potential_customer').click(function (e){
            if(document.getElementById('update_company_is_potential_customer').checked){
                document.getElementById('update_company_is_customer').checked = false;
            }
        });

        $('#update_company_is_customer').click(function (e){
            if(document.getElementById('update_company_is_customer').checked){
                document.getElementById('update_company_is_potential_customer').checked = false;
            }
        });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        initCurrencyLogs();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCurrencyLogs(){
	let data = await serviceGetCurrencyLogs();
    console.log(data)
    $("#currency-datatable").dataTable().fnDestroy();
    $('#currency-datatable tbody > tr').remove();

    $.each(data.currency_logs, function (i, currency_log) {
        let typeItem = '<tr>\n' +
            '              <td>'+ currency_log.id +'</td>\n' +
            '              <td>'+ formatDateASC(currency_log.day, '.') +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.usd) +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.eur) +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.gbp) +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteCurrencyLog(\''+ contact.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#currency-datatable tbody').append(typeItem);
    });

    $('#currency-datatable-datatable').DataTable({
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


async function deleteContact(contact_id){
    let returned = await serviceGetDeleteContact(contact_id);
    if(returned){
        initContacts();
    }
}
