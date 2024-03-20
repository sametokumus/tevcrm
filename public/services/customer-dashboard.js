(function($) {
    "use strict";

    $(document).ready(function() {


        $('#update_customer_form').submit(function (e){
            e.preventDefault();
            updateCustomer();
        });

        $('#add_employee_form').submit(function (e){
            e.preventDefault();
            addEmployee();
        });

        $('#update_employee_form').submit(function (e){
            e.preventDefault();
            updateEmployee();
        });



    });

    $(window).on('load', function () {

        checkLogin();
        checkRole();
        getCountriesAddSelectId('update_company_country');
        let customer_id = getPathVariable('customer-dashboard');
        initInfo(customer_id);
        initEmployees(customer_id);
        // initCompanyPoints(customer_id);
        // initSaledProducts(customer_id);
        // initSales(customer_id);
        // initDeliveryAddresses(customer_id);
        // initNotes(customer_id);
        // initActivities(customer_id);

    });

})(window.jQuery);



function checkRole(){
    return true;
}
async function initInfo(customer_id){
    let data = await serviceGetCustomerById(customer_id);
    let customer = data.customer;
    console.log(customer)

    $('#info-name').html(customer.name);
    $('#info-email').html(customer.email);
    $('#info-phone').html(customer.phone);
    $('#info-fax').html(customer.fax);
    $('#info-web').html(customer.web);

    initCustomer(customer);
}

async function initCustomer(customer){
    document.getElementById('update_customer_form').reset();
    document.getElementById('update_company_name').value = customer.name;
    document.getElementById('update_company_email').value = customer.email;
    document.getElementById('update_company_website').value = customer.website;
    document.getElementById('update_company_phone').value = customer.phone;
    document.getElementById('update_company_fax').value = customer.fax;
    document.getElementById('update_company_address').value = customer.address;
    document.getElementById('update_company_tax_office').value = customer.tax_office;
    document.getElementById('update_company_tax_number').value = customer.tax_number;
    document.getElementById('update_company_country').value = customer.country_id;
}
async function updateCustomer(){
    let formData = JSON.stringify({
        "name": document.getElementById('update_company_name').value,
        "email": document.getElementById('update_company_email').value,
        "website": document.getElementById('update_company_website').value,
        "phone": document.getElementById('update_company_phone').value,
        "fax": document.getElementById('update_company_fax').value,
        "address": document.getElementById('update_company_address').value,
        "tax_office": document.getElementById('update_company_tax_office').value,
        "tax_number": document.getElementById('update_company_tax_number').value,
        "country": document.getElementById('update_company_country').value
    });
    console.log(formData);

    let company_id = getPathVariable('customer-dashboard');

    let returned = await servicePostUpdateCustomer(company_id, formData);
    if (returned){
        initInfo(company_id);
    }
}

async function initEmployees(customer_id){
    let data = await serviceGetEmployeesByCustomerId(customer_id);
    let employees = data.employees;
    console.log(employees)

    $('#table-employees .employee-item').remove();

    $.each(employees, function (i, employee) {

        let item = '<div class="col-12 col-md-6 col-lg-4 mb-3 employee-item">\n' +
            '                  <div class="card shadow-none p-2">\n' +
            '                      <div class="card-body">\n' +
            '                          <div class="row align-items-start">\n' +
            '                              <div class="col">\n' +
            '                                  <h6 class="text-truncate mb-0">'+ employee.name +'</h6>\n' +
            '                                  <p class="mb-1">Ünvan/Pozisyon: '+ employee.title +'</p>\n' +
            '                                  <p class="mb-1">Eposta: '+ employee.email +'</p>\n' +
            '                                  <p class="mb-1">Telefon: '+ employee.phone +'</p>\n' +
            '                                  <p class="mb-1">Cep Telefonu: '+ employee.mobile +'</p>\n' +
            '                              </div>\n' +
            '                              <div class="col-auto">\n' +
            '                                  <button id="bEdit" type="button" class="btn btn-sm btn-light" onclick="openUpdateCustomerEmployeeModal(\''+ employee.id +'\')">\n' +
            '                                      <span class="bi bi-pencil-square"></span>\n' +
            '                                  </button>\n' +
            '                                  <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteEmployee(\''+ employee.id +'\')">\n' +
            '                                      <span class="bi bi-trash3"></span>\n' +
            '                                  </button>\n' +
            '                              </div>\n' +
            '                          </div>\n' +
            '                      </div>\n' +
            '                  </div>\n' +
            '              </div>';
        $('#table-employees').append(item);
    });

}
async function openAddEmployeeModal(event){
    event.preventDefault();
    $("#addCompanyEmployeeModal").modal('show');
}
async function addEmployee(){
    let customer_id = getPathVariable('customer-dashboard');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('add_employee_name').value,
        "title": document.getElementById('add_employee_title').value,
        "email": document.getElementById('add_employee_email').value,
        "phone": document.getElementById('add_employee_phone').value,
        "mobile": document.getElementById('add_employee_mobile').value
    });
    console.log(formData);
    let returned = await servicePostAddEmployee(formData);
    if (returned){
        initEmployees(customer_id);
        $("#addCompanyEmployeeModal").modal('hide');
    }
}

async function openUpdateCustomerEmployeeModal(employee_id){
    $("#updateCompanyEmployeeModal").modal('show');
    await initUpdateCustomerEmployeeModal(employee_id);
}
async function initUpdateCustomerEmployeeModal(employee_id){
    document.getElementById('update_employee_form').reset();
    document.getElementById('update_employee_id').value = employee_id;
    let data = await serviceGetEmployeeById(employee_id);
    let employee = data.employee;
    console.log(employee)
    document.getElementById('update_employee_name').value = employee.name;
    document.getElementById('update_employee_title').value = employee.title;
    document.getElementById('update_employee_email').value = employee.email;
    document.getElementById('update_employee_phone').value = employee.phone;
    document.getElementById('update_employee_mobile').value = employee.mobile;
}
async function updateEmployee(){
    let id = document.getElementById('update_employee_id').value;
    let customer_id = getPathVariable('customer-dashboard');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('update_employee_name').value,
        "title": document.getElementById('update_employee_title').value,
        "email": document.getElementById('update_employee_email').value,
        "phone": document.getElementById('update_employee_phone').value,
        "mobile": document.getElementById('update_employee_mobile').value
    });
    console.log(formData);

    let returned = await servicePostUpdateEmployee(id, formData);
    if (returned){
        initEmployees(customer_id);
        $("#updateCompanyEmployeeModal").modal('hide');
    }
}

async function deleteEmployee(employee_id){
    let returned = await serviceGetDeleteEmployee(employee_id);
    if(returned){
        let customer_id = getPathVariable('customer-dashboard');
        initEmployees(customer_id);
    }
}



