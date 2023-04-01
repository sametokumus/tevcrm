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
        initContacts();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initContacts(){
	let data = await serviceGetContacts();
    console.log(data)
    $("#contact-datatable").dataTable().fnDestroy();
    $('#contact-datatable tbody > tr').remove();

    $.each(data.contacts, function (i, contact) {
        let typeItem = '<tr>\n' +
            '              <td>'+ contact.id +'</td>\n' +
            '              <td>'+ contact.name +'</td>\n' +
            '              <td>'+ contact.authorized_name +'</td>\n' +
            '              <td>'+ contact.phone +'</td>\n' +
            '              <td>'+ contact.email +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="contact-detail/'+ contact.id +'" id="bDel" type="button" class="btn  btn-sm btn-warning">\n' +
            '                          <span class="fe fe-search"> </span> Düzenle\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteContact(\''+ contact.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#contact-datatable tbody').append(typeItem);
    });

    $('#contact-datatable').DataTable({
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
