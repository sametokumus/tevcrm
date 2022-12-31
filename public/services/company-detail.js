(function($) {
    "use strict";

    $(document).ready(function() {

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



        $('#add_employee_form').submit(function (e){
            e.preventDefault();
            addEmployee();
        });

        $('#update_employee_form').submit(function (e){
            e.preventDefault();
            updateEmployee();
        });



        $('#add_note_form').submit(function (e){
            e.preventDefault();
            addNote();
        });


    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        let company_id = getPathVariable('company-detail');
        initCompany(company_id);
        initEmployees();
        initNotes()

    });

})(window.jQuery);

function checkRole(){
    return true;
}
async function initCompany(company_id){
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

async function initEmployees(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetEmployeesByCompanyId(company_id);
    $('#employees-grid .grid-item').remove();

    $.each(data.employees, function (i, employee) {
        let photo = "img/employee/empty.jpg";
        if (employee.photo != null){photo = "https://lenis-crm.wimco.com.tr"+employee.photo;}
        let item = '<div class="col-md-6 grid-item">\n' +
            '           <div class="card border-theme mb-3">\n' +
            '               <div class="card-body">\n' +
            '                   <div class="row gx-0 align-items-center">\n' +
            '                       <div class="col-md-4">\n' +
            '                           <img src="'+ photo +'" alt="" class="card-img rounded-0" />\n' +
            '                       </div>\n' +
            '                       <div class="col-md-8">\n' +
            '                           <div class="card-body">\n' +
            '                               <h5 class="card-title">'+ employee.name +'</h5>\n' +
            '                               <p class="card-text">Ünvan/Pozisyon: '+ employee.title +'</p>\n' +
            '                               <p class="card-text">Eposta: '+ employee.email +'</p>\n' +
            '                               <p class="card-text">Telefon: '+ employee.phone +'</p>\n' +
            '                               <p class="card-text">Cep Telefonu: '+ employee.mobile +'</p>\n' +
            '                               <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="openUpdateCompanyEmployeeModal(\''+ employee.id +'\');">Düzenle</button>\n' +
            '                               <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="deleteEmployee(\''+ employee.id +'\');">Sil</button>\n' +
            '                           </div>\n' +
            '                       </div>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '               <div class="card-arrow">\n' +
            '                   <div class="card-arrow-top-left"></div>\n' +
            '                   <div class="card-arrow-top-right"></div>\n' +
            '                   <div class="card-arrow-bottom-left"></div>\n' +
            '                   <div class="card-arrow-bottom-right"></div>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '       </div>';
        $('#employees-grid').append(item);
    });

}


async function addEmployeeCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_employee_form").trigger("reset");
    $("#addCompanyEmployeeModal").modal('hide');
    initEmployees();
}
async function addEmployee(){
    let company_id = getPathVariable('company-detail');
    let formData = new FormData();
    formData.append('company_id', company_id);
    formData.append('name', document.getElementById('add_employee_name').value);
    formData.append('title', document.getElementById('add_employee_title').value);
    formData.append('email', document.getElementById('add_employee_email').value);
    formData.append('phone', document.getElementById('add_employee_phone').value);
    formData.append('mobile', document.getElementById('add_employee_mobile').value);
    formData.append('photo', document.getElementById('add_employee_photo').files[0]);
    console.log(formData);

    await servicePostAddEmployee(formData);
}

async function openUpdateCompanyEmployeeModal(employee_id){
    $("#updateCompanyEmployeeModal").modal('show');
    await initUpdateCompanyEmployeeModal(employee_id);
}
async function initUpdateCompanyEmployeeModal(employee_id){
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
    $('#update_employee_current_photo').attr('href', employee.photo);
}
async function updateEmployeeCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_employee_form").trigger("reset");
    $("#updateCompanyEmployeeModal").modal('hide');
    initEmployees();
}
async function updateEmployee(){
    let id = document.getElementById('update_employee_id').value;
    let company_id = getPathVariable('company-detail');
    let formData = new FormData();
    formData.append('company_id', company_id);
    formData.append('name', document.getElementById('update_employee_name').value);
    formData.append('title', document.getElementById('update_employee_title').value);
    formData.append('email', document.getElementById('update_employee_email').value);
    formData.append('phone', document.getElementById('update_employee_phone').value);
    formData.append('mobile', document.getElementById('update_employee_mobile').value);
    formData.append('photo', document.getElementById('update_employee_photo').files[0]);
    console.log(formData);

    await servicePostUpdateEmployee(id, formData);
}

async function deleteEmployee(employee_id){
    let returned = await serviceGetDeleteEmployee(employee_id);
    if(returned){
        initEmployees();
    }
}




async function initNotes(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetNotesByCompanyId(company_id);
    $('#note-list .list-item').remove();

    $.each(data.notes, function (i, note) {
        let image = '';
        if(note.image != null && note.image != ''){
            image = '<div class="card-body">\n' +
            '            <img src="https://lenis-crm.wimco.com.tr/'+ note.image +'" alt="" class="card-img-top" />\n' +
            '        </div>';
        }
        let updated_at = "";
        if (note.updated_at != null){
            updated_at = "Son güncelleme: " + formatDateAndTimeDESC(note.updated_at, "/");
        }

        let item = '<div class="row mb-3 note-list-item">\n' +
            '           <div class="col-md-4">\n' +
            '               <div class="card mb-3">\n' +
            '                   '+ image +'\n' +
            '                   <div class="card-body">\n' +
            '                       <h6 class="card-title"><strong>'+ note.user_name +'</strong> tarafından; '+ note.company.name +' ve '+ note.employee.name +' hakkında</h6>\n' +
            '                       <p class="card-text fw-600">'+ note.description +'</p>\n' +
            '                       <p class="card-text"><small>Oluşturulma: '+ formatDateAndTimeDESC(note.created_at, "/") +' ('+ updated_at +')</small></p>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '       </div>';
        $('#note-list').append(item);
    });

}
async function openAddCompanyNoteModal(){
    let company_id = getPathVariable('company-detail');
    getEmployeesAddSelectId(company_id, 'add_note_employee');
    $("#addCompanyNoteModal").modal('show');
}
async function addNoteCallback(xhttp){
    let jsonData = await xhttp.responseText;
    console.log(jsonData)
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_note_form").trigger("reset");
    $("#addCompanyNoteModal").modal('hide');
    initNotes();
}
async function addNote(){
    let company_id = getPathVariable('company-detail');
    let user_id = sessionStorage.getItem('userId');
    let formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('company_id', company_id);
    formData.append('description', document.getElementById('add_note_description').value);
    formData.append('employee_id', document.getElementById('add_note_employee').value);
    formData.append('image', document.getElementById('add_note_image').files[0]);
    console.log(formData);

    await servicePostAddNote(formData);
}




async function initCompanies(){
    let data = await serviceGetPotentialCustomers();
    $('#company-grid .grid-item').remove();

    $.each(data.companies, function (i, company) {
        let logo = "img/company/empty.jpg";
        if (company.logo != null){logo = "https://lenis-crm.wimco.com.tr"+company.logo;}
        let item = '<div class="col-md-4 grid-item">\n' +
            '           <div class="card border-theme mb-3">\n' +
            '               <div class="card-body">\n' +
            '                   <div class="row gx-0 align-items-center">\n' +
            '                       <div class="col-md-4">\n' +
            '                           <img src="'+ logo +'" alt="" class="card-img rounded-0" />\n' +
            '                       </div>\n' +
            '                       <div class="col-md-8">\n' +
            '                           <div class="card-body">\n' +
            '                               <h5 class="card-title">'+ company.name +'</h5>\n' +
            '                               <p class="card-text">Eposta: '+ company.email +'</p>\n' +
            '                               <p class="card-text">Telefon: '+ company.phone +'</p>\n' +
            '                               <p class="card-text">Faks: '+ company.fax +'</p>\n' +
            '                               <a href="company-detail/'+ company.id +'" class="btn btn-outline-secondary btn-sm mt-2"">İncele</a>\n' +
            '                               <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="openUpdateCompanyModal(\''+ company.id +'\');">Düzenle</button>\n' +
            '                           </div>\n' +
            '                       </div>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '               <div class="card-arrow">\n' +
            '                   <div class="card-arrow-top-left"></div>\n' +
            '                   <div class="card-arrow-top-right"></div>\n' +
            '                   <div class="card-arrow-bottom-left"></div>\n' +
            '                   <div class="card-arrow-bottom-right"></div>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '       </div>';
        $('#company-grid').append(item);
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
    formData.append('website', document.getElementById('add_company_email').value);
    formData.append('email', document.getElementById('add_company_website').value);
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
    formData.append('website', document.getElementById('update_company_email').value);
    formData.append('email', document.getElementById('update_company_website').value);
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

    await servicePostUpdateCompany(formData);
}
