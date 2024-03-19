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

    // $('#employees-tab #employees-grid .grid-item').remove();
    //
    // $.each(employees, function (i, employee) {
    //     let photo = "img/user/null-profile-picture.png";
    //     if (employee.photo != null){photo = employee.photo;}
    //
    //     let item = '<div class="col-sm-6 grid-item">\n' +
    //         '              <div class="card">\n' +
    //         '                <div class="m-1 bg-inverse bg-opacity-15">\n' +
    //         '                  <div class="position-relative overflow-hidden" style="height: 225px">\n' +
    //         '                    <img src="/img/gallery/widget-cover-1.jpg" class="card-img rounded-0" alt="" />\n' +
    //         '                    <div class="card-img-overlay text-white text-center bg-gray-600 bg-opacity-75">\n' +
    //         '                      <div class="my-2">\n' +
    //         '                        <img src="'+ photo +'" alt="" width="80" class="rounded-circle" />\n' +
    //         '                      </div>\n' +
    //         '                      <div>\n' +
    //         '                        <div class="fw-bold">'+ employee.name +'</div>\n' +
    //         '                        <div class="small">Ünvan/Pozisyon: '+ employee.title +'</div>\n' +
    //         '                        <div class="small">Eposta: '+ employee.email +'</div>\n' +
    //         '                        <div class="small">Telefon: '+ employee.phone +'</div>\n' +
    //         '                        <div class="small">Cep Telefonu: '+ employee.mobile +'</div>\n' +
    //         '                      </div>\n' +
    //         '                    </div>\n' +
    //         '                  </div>\n' +
    //         '                  <div class="card-body py-2 px-3">\n' +
    //         '                    <div class="row text-center">\n' +
    //         '                      <div class="col-12">\n' +
    //         '                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="openUpdateCompanyEmployeeModal(\''+ employee.id +'\');">Düzenle</button>\n' +
    //         '                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="deleteEmployee(\''+ employee.id +'\');">Sil</button>\n' +
    //         '                      </div>\n' +
    //         '                    </div>\n' +
    //         '                  </div>\n' +
    //         '                </div>\n' +
    //         '                <div class="card-arrow">\n' +
    //         '                  <div class="card-arrow-top-left"></div>\n' +
    //         '                  <div class="card-arrow-top-right"></div>\n' +
    //         '                  <div class="card-arrow-bottom-left"></div>\n' +
    //         '                  <div class="card-arrow-bottom-right"></div>\n' +
    //         '                </div>\n' +
    //         '              </div>\n' +
    //         '              </div>';
    //     $('#employees-tab #employees-grid').append(item);
    // });

}
async function openAddEmployeeModal(event){
    event.preventDefault();
    $("#addCompanyEmployeeModal").modal('show');
}
async function addEmployee(){
    let customer_id = getPathVariable('customer-dashboard');
    let formData = new FormData();
    formData.append('customer_id', customer_id);
    formData.append('name', document.getElementById('add_employee_name').value);
    formData.append('title', document.getElementById('add_employee_title').value);
    formData.append('email', document.getElementById('add_employee_email').value);
    formData.append('phone', document.getElementById('add_employee_phone').value);
    formData.append('mobile', document.getElementById('add_employee_mobile').value);
    console.log(formData);
    let returned = await servicePostAddEmployee(formData);
    if (returned){
        initEmployees(customer_id);
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
    let formData = new FormData();
    formData.append('customer_id', customer_id);
    formData.append('name', document.getElementById('update_employee_name').value);
    formData.append('title', document.getElementById('update_employee_title').value);
    formData.append('email', document.getElementById('update_employee_email').value);
    formData.append('phone', document.getElementById('update_employee_phone').value);
    formData.append('mobile', document.getElementById('update_employee_mobile').value);
    console.log(formData);

    let returned = await servicePostUpdateEmployee(id, formData);
    if (returned){
        initEmployees(customer_id);
    }
}

async function deleteEmployee(employee_id){
    let returned = await serviceGetDeleteEmployee(employee_id);
    if(returned){
        let customer_id = getPathVariable('customer-dashboard');
        initEmployees(customer_id);
    }
}



