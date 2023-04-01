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
        initCompanies();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCompanies(){
	let data = await serviceGetPotentialCustomers();
    $("#company-datatable").dataTable().fnDestroy();
    $('#company-datatable tbody > tr').remove();

    $.each(data.companies, function (i, company) {
        let typeItem = '<tr>\n' +
            '              <td>'+ company.id +'</td>\n' +
            '              <td>'+ company.name +'</td>\n' +
            '              <td>'+ checkNull(company.email) +'</td>\n' +
            '              <td>'+ checkNull(company.phone) +'</td>\n' +
            '              <td>'+ checkNull(company.fax) +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openUpdateCompanyModal(\''+ company.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Hızlı Düzenle\n' +
            '                      </button>\n' +
            '                      <a href="company-detail/'+ company.id +'" id="bDel" type="button" class="btn  btn-sm btn-warning">\n' +
            '                          <span class="fe fe-search"> </span> Detaylı İncele\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteCompany(\''+ company.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#company-datatable tbody').append(typeItem);
    });

    $('#company-datatable').DataTable({
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
async function addCompanyCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_company_form").trigger("reset");
    $("#addCompanyModal").modal('hide');
    initCompanies();
}
async function addCompany(){
    let isPotential = 0;
    let isCustomer = 0;
    let isSupplier = 0;
    if(document.getElementById('add_company_is_potential_customer').checked){
        isPotential = 1;
    }
    console.log(isPotential)
    if(document.getElementById('add_company_is_customer').checked){
        isCustomer = 1;
    }
    if(document.getElementById('add_company_is_supplier').checked){
        isSupplier = 1;
    }
    let formData = new FormData();
    formData.append('name', document.getElementById('add_company_name').value);
    formData.append('email', document.getElementById('add_company_email').value);
    formData.append('website', document.getElementById('add_company_website').value);
    formData.append('phone', document.getElementById('add_company_phone').value);
    formData.append('fax', document.getElementById('add_company_fax').value);
    formData.append('address', document.getElementById('add_company_address').value);
    formData.append('tax_office', document.getElementById('add_company_tax_office').value);
    formData.append('tax_number', document.getElementById('add_company_tax_number').value);
    formData.append('is_potential_customer', isPotential);
    formData.append('is_customer', isCustomer);
    formData.append('is_supplier', isSupplier);
    formData.append('logo', document.getElementById('add_company_logo').files[0]);
    console.log(formData);

    await servicePostAddCompany(formData);
}

async function openUpdateCompanyModal(company_id){
    $("#updateCompanyModal").modal('show');
    await initUpdateCompanyModal(company_id);
}
async function initUpdateCompanyModal(company_id){
    document.getElementById('update_company_form').reset();
    document.getElementById('update_company_id').value = company_id;
    let data = await serviceGetCompanyById(company_id);
    let company = data.company;
    console.log(company)
    document.getElementById('update_company_name').value = company.name;
    document.getElementById('update_company_email').value = company.email;
    document.getElementById('update_company_website').value = company.website;
    document.getElementById('update_company_phone').value = company.phone;
    document.getElementById('update_company_fax').value = company.fax;
    document.getElementById('update_company_address').value = company.address;
    document.getElementById('update_company_tax_office').value = company.tax_office;
    document.getElementById('update_company_tax_number').value = company.tax_number;
    $('#update_company_current_logo').attr('href', company.logo);
    if (company.is_customer == 1){ document.getElementById('update_company_is_customer').checked = true; }
    if (company.is_potential_customer == 1){ document.getElementById('update_company_is_potential_customer').checked = true; }
    if (company.is_supplier == 1){ document.getElementById('update_company_is_supplier').checked = true; }
}
async function updateCompanyCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_company_form").trigger("reset");
    $("#updateCompanyModal").modal('hide');
    initCompanies();
}
async function updateCompany(){
    let isPotential = 0;
    let isCustomer = 0;
    let isSupplier = 0;
    if(document.getElementById('update_company_is_potential_customer').checked){
        isPotential = 1;
    }
    console.log(isPotential)
    if(document.getElementById('update_company_is_customer').checked){
        isCustomer = 1;
    }
    if(document.getElementById('update_company_is_supplier').checked){
        isSupplier = 1;
    }
    let formData = new FormData();
    formData.append('name', document.getElementById('update_company_name').value);
    formData.append('website', document.getElementById('update_company_website').value);
    formData.append('email', document.getElementById('update_company_email').value);
    formData.append('phone', document.getElementById('update_company_phone').value);
    formData.append('fax', document.getElementById('update_company_fax').value);
    formData.append('address', document.getElementById('update_company_address').value);
    formData.append('tax_office', document.getElementById('update_company_tax_office').value);
    formData.append('tax_number', document.getElementById('update_company_tax_number').value);
    formData.append('is_potential_customer', isPotential);
    formData.append('is_customer', isCustomer);
    formData.append('is_supplier', isSupplier);
    formData.append('logo', document.getElementById('update_company_logo').files[0]);
    console.log(formData);

    let company_id = document.getElementById('update_company_id').value;
    await servicePostUpdateCompany(company_id, formData);
}


async function deleteCompany(company_id){
    let returned = await serviceGetDeleteCompany(company_id);
    if(returned){
        initCompanies();
    }
}
