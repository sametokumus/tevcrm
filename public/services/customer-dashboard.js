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
        $('#update_note_form').submit(function (e){
            e.preventDefault();
            updateNote();
        });



        $('#add_activity_form').submit(function (e){
            e.preventDefault();
            addActivity();
        });
        $('#update_activity_form').submit(function (e){
            e.preventDefault();
            updateActivity();
        });

        $('#add-activity-new-task-btn').click(function (){
           $('#add-activity-new-tasks-input').removeClass('d-none');
        });

        $('#add-activity-task-button').click(function (){
           let task = document.getElementById('add-activity-task').value;
           if (task == ''){
               alert('Lütfen görev için içerik ekleyiniz.')
           }else{
               let count = document.getElementById('add-activity-new-task-count').value;
               count = parseInt(count) + 1;
               document.getElementById('add-activity-new-task-count').value = count;
               let checkInput = '<div class="form-check">\n' +
                   '                 <input class="form-check-input" type="checkbox" value="1" data-task-id="0" id="add_activity_new_task_'+ count +'" />\n' +
                   '                 <label class="form-check-label" for="add_activity_new_task_'+ count +'" id="add_activity_new_task_'+ count +'_label">'+ task +'</label>\n' +
                   '             </div>';
               $('#add-activity-new-tasks-body').append(checkInput);
               document.getElementById('add-activity-task').value = '';
               $('#add-activity-new-tasks-input').addClass('d-none');
           }
        });

        $('#update-activity-new-task-btn').click(function (){
           $('#update-activity-new-tasks-input').removeClass('d-none');
        });

        $('#update-activity-task-button').click(function (){
           updateActivityNewTask();
        });

    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        let company_id = getPathVariable('customer-dashboard');
        initSidebarInfo(company_id);
        initNotes();
        initActivities();

    });

})(window.jQuery);



function checkRole(){
    return true;
}
async function initSidebarInfo(company_id){
    $('.profile-sidebar #sidebar-info *').remove();

    let data = await serviceGetCompanyById(company_id);
    let company = data.company;
    console.log(company)

    let logo = 'img/company/empty.jpg';
    if (company.logo != null){
        logo = company.logo;
    }
    let type = '';
    if (company.is_supplier == 1){
        type += ' Tedarikçi,';
    }
    if (company.is_customer == 1){
        type += ' Müşteri,';
    }
    if (company.is_potential_customer == 1){
        type += ' Potansiyel Müşteri,';
    }
    type = type.slice(0, -1);

    let sidebar = '<div class="profile-img">\n' +
        '              <img src="'+ logo +'" alt>\n' +
        '          </div>\n' +
        '          <h4>'+ company.name +'</h4>\n' +
        '          <div class="mb-3 text-inverse text-opacity-50 fw-bold mt-n2">'+ type +'</div>\n' +
        '          <div class="mb-1">\n' +
        '              <i class="fa fa-map-marker-alt fa-fw text-inverse text-opacity-50"></i> '+ company.address +'\n' +
        '          </div>\n' +
        '          <div class="mb-1">\n' +
        '              <i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i> '+ company.email +'\n' +
        '          </div>\n' +
        '          <div class="mb-1">\n' +
        '              <i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i> '+ company.phone +'\n' +
        '          </div>\n' +
        '          <div class="mb-1">\n' +
        '              <i class="fa fa-fax fa-fw text-inverse text-opacity-50"></i> '+ company.fax +'\n' +
        '          </div>\n' +
        '          <div class="mb-3">\n' +
        '              <i class="fa fa-link fa-fw text-inverse text-opacity-50"></i> '+ company.website +'\n' +
        '          </div>\n' +
        '          <hr class="mt-4 mb-4">\n' +
        '          <h6>Yetkililer</h6>\n';

    $('.profile-sidebar #sidebar-info').append(sidebar);


    let data2 = await serviceGetEmployeesByCompanyId(company_id);
    let employees = data2.employees;
    $.each(employees, function (i, employee) {
        let photo = "img/user/null-profile-picture.png";
        if (employee.photo != null){photo = employee.photo;}

        let item = '<div>\n' +
            '                  <a href="#" class="company-info d-flex align-items-center mb-3 text-decoration-none fs-12px pointer-event-none">\n' +
            '                  <img src="'+ photo +'" alt="" width="30" class="rounded-circle">\n' +
            '                  <div class="flex-fill px-3">\n' +
            '                      <div class="fw-bold text-truncate w-100px company-name">'+ employee.name +'</div>\n' +
            '                      <div class="fs-12px text-inverse text-opacity-50">'+ employee.email +'</div>\n' +
            '                      <div class="fs-12px text-inverse text-opacity-50">'+ employee.mobile +'</div>\n' +
            '                  </div>\n' +
            '                  </a>\n' +
            '              </div>\n';
        $('.profile-sidebar #sidebar-info').append(item);
    });


    initCompany(company);
    initEmployees(employees);
}

async function initCompany(company){
    await getCountriesAddSelectId('update_company_country');
    await getPaymentTermsAddSelectId('update_company_payment_term');

    document.getElementById('update_company_form').reset();
    document.getElementById('update_company_id').value = company.id;
    document.getElementById('update_company_name').value = company.name;
    document.getElementById('update_company_email').value = company.email;
    document.getElementById('update_company_website').value = company.website;
    document.getElementById('update_company_phone').value = company.phone;
    document.getElementById('update_company_fax').value = company.fax;
    document.getElementById('update_company_address').value = company.address;
    document.getElementById('update_company_country').value = company.country_id;
    document.getElementById('update_company_tax_office').value = company.tax_office;
    document.getElementById('update_company_tax_number').value = company.tax_number;
    document.getElementById('update_company_linkedin').value = company.linkedin;
    document.getElementById('update_company_skype').value = company.skype;
    document.getElementById('update_company_online').value = company.online;
    document.getElementById('update_company_registration_number').value = company.registration_number;
    document.getElementById('update_company_payment_term').value = company.payment_term;
    if (company.is_customer == 1){ document.getElementById('update_company_is_customer').checked = true; }
    if (company.is_potential_customer == 1){ document.getElementById('update_company_is_potential_customer').checked = true; }
    if (company.is_supplier == 1){ document.getElementById('update_company_is_supplier').checked = true; }
}
async function updateCompanyCallback(xhttp){
    let jsonData = await xhttp.responseText;
    console.log(jsonData)
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_company_form").trigger("reset");
    let company_id = getPathVariable('customer-dashboard');
    initSidebarInfo(company_id);
}
async function updateCompany(){
    let isPotential = 0;
    let isCustomer = 0;
    let isSupplier = 0;
    if(document.getElementById('update_company_is_potential_customer').checked){
        isPotential = 1;
    }
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
    formData.append('country', document.getElementById('update_company_country').value);
    formData.append('tax_office', document.getElementById('update_company_tax_office').value);
    formData.append('tax_number', document.getElementById('update_company_tax_number').value);
    formData.append('is_potential_customer', isPotential);
    formData.append('is_customer', isCustomer);
    formData.append('is_supplier', isSupplier);
    formData.append('linkedin', document.getElementById('update_company_linkedin').value);
    formData.append('skype', document.getElementById('update_company_skype').value);
    formData.append('online', document.getElementById('update_company_online').value);
    formData.append('registration_number', document.getElementById('update_company_registration_number').value);
    formData.append('payment_term', document.getElementById('update_company_payment_term').value);
    formData.append('logo', document.getElementById('update_company_logo').files[0]);
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]);
    }

    let company_id = getPathVariable('customer-dashboard');
    await servicePostUpdateCompany(company_id, formData);
}

async function initEmployees(employees){
    $('#employees-tab .grid-item').remove();

    $.each(employees, function (i, employee) {
        let photo = "img/employee/empty.jpg";
        if (employee.photo != null){photo = employee.photo;}

        let item = '<div class="card grid-item">\n' +
            '                <div class="m-1 bg-inverse bg-opacity-15">\n' +
            '                  <div class="position-relative overflow-hidden" style="height: 165px">\n' +
            '                    <img src="assets/img/gallery/widget-cover-1.jpg" class="card-img rounded-0" alt="" />\n' +
            '                    <div class="card-img-overlay text-white text-center bg-gray-600 bg-opacity-75">\n' +
            '                      <div class="my-2">\n' +
            '                        <img src="'+ photo +'" alt="" width="80" class="rounded-circle" />\n' +
            '                      </div>\n' +
            '                      <div>\n' +
            '                        <div class="fw-bold">'+ employee.name +'</div>\n' +
            '                        <div class="small">Ünvan/Pozisyon: '+ employee.title +'</div>\n' +
            '                        <div class="small">Eposta: '+ employee.email +'</div>\n' +
            '                        <div class="small">Telefon: '+ employee.phone +'</div>\n' +
            '                        <div class="small">Cep Telefonu: '+ employee.mobile +'</div>\n' +
            '                      </div>\n' +
            '                    </div>\n' +
            '                  </div>\n' +
            '                  <div class="card-body py-2 px-3">\n' +
            '                    <div class="row text-center">\n' +
            '                      <div class="col-12">\n' +
            '                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="openUpdateCompanyEmployeeModal(\''+ employee.id +'\');">Düzenle</button>\n' +
            '                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="deleteEmployee(\''+ employee.id +'\');">Sil</button>\n' +
            '                      </div>\n' +
            '                    </div>\n' +
            '                  </div>\n' +
            '                </div>\n' +
            '                <div class="card-arrow">\n' +
            '                  <div class="card-arrow-top-left"></div>\n' +
            '                  <div class="card-arrow-top-right"></div>\n' +
            '                  <div class="card-arrow-bottom-left"></div>\n' +
            '                  <div class="card-arrow-bottom-right"></div>\n' +
            '                </div>\n' +
            '              </div>';
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
    let company_id = getPathVariable('customer-dashboard');
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
    let company_id = getPathVariable('customer-dashboard');
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




async function initNotes(company_id){
    let data = await serviceGetNotesByCompanyId(company_id);
    $('#note-list .note-list-item').remove();
    let logged_user_id = localStorage.getItem('userId');

    $.each(data.notes, function (i, note) {
        console.log(note)
        let image = '';
        if(note.image != null && note.image != ''){
            image = '<div class="card-body">\n' +
            '            <img src="'+ api_url + '/'+ note.image +'" alt="" class="card-img-top" />\n' +
            '        </div>';
        }
        let updated_at = "";
        if (note.updated_at != null){
            updated_at = "(Son güncelleme: " + formatDateAndTimeDESC(note.updated_at, "/") + ")";
        }

        let actions = "";
        if (logged_user_id == note.user_id){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="openUpdateCompanyNoteModal(\''+ note.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm mt-2" onclick="deleteNote(\''+ note.id +'\');">Sil</button>\n';
        }

        let item = '<div class="row mb-3 note-list-item">\n' +
            '           <div class="col-md-4">\n' +
            '               <div class="card mb-3">\n' +
            '                   '+ image +'\n' +
            '                   <div class="card-body">\n' +
            '                       <h6 class="card-title"><strong>'+ note.user_name +'</strong> tarafından; '+ note.company.name +' ve '+ note.employee.name +' hakkında</h6>\n' +
            '                       <p class="card-text fw-500">'+ note.description +'</p>\n' +
            '                       <p class="card-text"><small>Oluşturulma: '+ formatDateAndTimeDESC(note.created_at, "/") +' '+ updated_at +'</small></p>\n' +
            '                       '+ actions +
            '                   </div>\n' +
            '                   <div class="card-arrow">\n' +
            '                       <div class="card-arrow-top-left"></div>\n' +
            '                       <div class="card-arrow-top-right"></div>\n' +
            '                       <div class="card-arrow-bottom-left"></div>\n' +
            '                       <div class="card-arrow-bottom-right"></div>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '       </div>';
        $('#note-list').append(item);
    });

}
async function openAddCompanyNoteModal(){
    let company_id = getPathVariable('customer-dashboard');
    getEmployeesAddSelectId(company_id, 'add_note_employee');
    $("#addCompanyNoteModal").modal('show');
}
async function addNoteCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_note_form").trigger("reset");
    $("#addCompanyNoteModal").modal('hide');
    initNotes();
}
async function addNote(){
    let company_id = getPathVariable('customer-dashboard');
    let user_id = localStorage.getItem('userId');
    let formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('company_id', company_id);
    formData.append('description', document.getElementById('add_note_description').value);
    formData.append('employee_id', document.getElementById('add_note_employee').value);
    formData.append('image', document.getElementById('add_note_image').files[0]);
    console.log(formData);

    await servicePostAddNote(formData);
}
async function openUpdateCompanyNoteModal(note_id){
    let company_id = getPathVariable('customer-dashboard');
    getEmployeesAddSelectId(company_id, 'update_note_employee');
    $("#updateCompanyNoteModal").modal('show');
    initUpdateCompanyNoteModal(note_id)
}
async function initUpdateCompanyNoteModal(note_id){
    document.getElementById('update_note_form').reset();
    document.getElementById('update_note_id').value = note_id;
    let data = await serviceGetNoteById(note_id);
    let note = data.note;
    document.getElementById('update_note_description').value = note.description;
    document.getElementById('update_note_employee').value = note.employee_id;
    $('#update_note_current_image').attr('href', note.image);
}
async function updateNoteCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_note_form").trigger("reset");
    $("#updateCompanyNoteModal").modal('hide');
    initNotes();
}
async function updateNote(){
    let company_id = getPathVariable('customer-dashboard');
    let user_id = localStorage.getItem('userId');
    let formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('company_id', company_id);
    formData.append('description', document.getElementById('update_note_description').value);
    formData.append('employee_id', document.getElementById('update_note_employee').value);
    formData.append('image', document.getElementById('update_note_image').files[0]);

    await servicePostUpdateNote(document.getElementById('update_note_id').value, formData);
}
async function deleteNote(note_id){
    let returned = await serviceGetDeleteNote(note_id);
    if(returned){
        initNotes();
    }
}

async function initActivities(company_id){
    let data = await serviceGetActivitiesByCompanyId(company_id);
    console.log(data)
    $('#datatableActivities tbody tr').remove();
    let logged_user_id = localStorage.getItem('userId');

    $.each(data.activities, function (i, activity) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateCompanyActivityModal(\''+ activity.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteActivity(\''+ activity.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th scope="row">'+ activity.id +'</th>\n' +
            '           <td>'+ activity.user.name +' '+ activity.user.surname +'</td>\n' +
            '           <td>'+ activity.type.name +'</td>\n' +
            '           <td>'+ activity.title +'</td>\n' +
            '           <td>'+ activity.employee.name +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.start, "/") +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.end, "/") +'</td>\n' +
            '           <td>'+ activity.task_count +' görev ('+ activity.completed_task_count +' tamamlanan)</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#datatableActivities tbody').append(item);
    });

}
async function openAddCompanyActivityModal(){
    let company_id = getPathVariable('customer-dashboard');
    getEmployeesAddSelectId(company_id, 'add_activity_employee_id');
    getActivityTypesAddSelectId('add_activity_type_id');
    $("#addCompanyActivityModal").modal('show');
}
async function addActivity(){
    let company_id = getPathVariable('customer-dashboard');
    let user_id = localStorage.getItem('userId');

    let task_count = document.getElementById('add-activity-new-task-count').value;
    let tasks = [];
    for (let i = 1; i <= task_count; i++) {
        let title = document.getElementById('add_activity_new_task_'+i+'_label').textContent;
        let is_completed = 0;
        let task_id = document.getElementById('add_activity_new_task_'+i).getAttribute('data-task-id');
        let item = {
            "task_id": parseInt(task_id),
            "title": title,
            "is_completed": parseInt(is_completed)
        }
        tasks.push(item);
    }

    let start = formatDateDESC2(document.getElementById('add_activity_start_date').value, "-", "-") + " " + document.getElementById('add_activity_start_time').value + ":00";
    let end = formatDateDESC2(document.getElementById('add_activity_end_date').value, "-", "-")  + " " + document.getElementById('add_activity_end_time').value + ":00";

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "type_id": document.getElementById('add_activity_type_id').value,
        "title": document.getElementById('add_activity_title').value,
        "description": document.getElementById('add_activity_description').value,
        "company_id": company_id,
        "employee_id": document.getElementById('add_activity_employee_id').value,
        "start": start,
        "end": end,
        "tasks": tasks
    });

    console.log(formData);

    let returned = await servicePostAddActivity(formData);
    if (returned){
        $("#add_activity_form").trigger("reset");
        $("#addCompanyActivityModal").modal('hide');
        // initActivities();
    }else{
        alert("Hata Oluştu");
    }
}
async function openUpdateCompanyActivityModal(activity_id){
    let company_id = getPathVariable('customer-dashboard');
    getEmployeesAddSelectId(company_id, 'update_activity_employee_id');
    getActivityTypesAddSelectId('update_activity_type_id');
    $("#updateCompanyActivityModal").modal('show');
    initUpdateCompanyActivityModal(activity_id)
}
async function initUpdateCompanyActivityModal(activity_id){
    document.getElementById('update_activity_form').reset();
    $('#update-activity-tasks-body .form-check').remove();
    document.getElementById('update_activity_id').value = activity_id;
    let data = await serviceGetActivityById(activity_id);
    let activity = data.activity;
    document.getElementById('update_activity_type_id').value = activity.type_id;
    document.getElementById('update_activity_title').value = activity.title;
    document.getElementById('update_activity_description').value = activity.description;
    document.getElementById('update_activity_employee_id').value = activity.employee_id;
    document.getElementById('update_activity_start_date').value = formatDateASC(activity.start, "-");
    document.getElementById('update_activity_start_time').value = formatTime(activity.start);
    document.getElementById('update_activity_end_date').value = formatDateASC(activity.end, "-");
    document.getElementById('update_activity_end_time').value = formatTime(activity.end);

    let count = 0;
    $.each(activity.tasks, function (i, task) {
        count += 1;
        let is_completed = '';
        let task_status = 1;
        if (task.is_completed == 1){
            is_completed = 'checked';
            task_status = 0;
        }
        let checkInput = '<div class="form-check" id="form-check-'+ task.id +'">\n' +
            '                 <input class="form-check-input" type="checkbox" value="1" data-task-id="'+ task.id +'" id="update_activity_task_'+ count +'" '+ is_completed +' onclick="changeTaskStatus(this, '+ task.id +' ,'+ task_status +')" />\n' +
            '                 <label class="form-check-label" for="add_activity_task_'+ count +'" id="add_activity_task_'+ count +'_label">'+ task.title +'</label>\n' +
            '                 (<a href="#" onclick="deleteActivityTask(event, '+ task.id +');">Görevi Sil</a>)\n' +
            '             </div>';
        $('#update-activity-tasks-body').append(checkInput);
    });
    document.getElementById('update-activity-task-count').value = count;
}
async function updateActivityCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_note_form").trigger("reset");
    $("#updateCompanyActivityModal").modal('hide');
    initActivities();
}
async function updateActivity(){
    let company_id = getPathVariable('customer-dashboard');
    let user_id = localStorage.getItem('userId');
    let activity_id = document.getElementById('update_activity_id').value;

    let start = formatDateDESC2(document.getElementById('update_activity_start_date').value, "-", "-") + " " + document.getElementById('update_activity_start_time').value + ":00";
    let end = formatDateDESC2(document.getElementById('update_activity_end_date').value, "-", "-")  + " " + document.getElementById('update_activity_end_time').value + ":00";
    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "type_id": document.getElementById('update_activity_type_id').value,
        "title": document.getElementById('update_activity_title').value,
        "description": document.getElementById('update_activity_description').value,
        "company_id": company_id,
        "employee_id": document.getElementById('update_activity_employee_id').value,
        "start": start,
        "end": end
    });
    let returned = await servicePostUpdateActivity(formData, activity_id);
    if (returned){
        $("#update_activity_form").trigger("reset");
        $("#updateCompanyActivityModal").modal('hide');
        initActivities();
    }else{
        alert('Güncelleme yapılırken bir hata oluştu. Lütfen tekrar deneyiniz!');
    }
}
async function deleteActivity(activity_id){
    let returned = await serviceGetDeleteActivity(activity_id);
    if(returned){
        initActivities();
    }
}
async function deleteActivityTask(event, task_id){
    event.preventDefault();
    console.log(task_id)
    let returned = await serviceGetDeleteActivityTask(task_id);
    if(returned){
        $('#form-check-'+task_id).remove();
    }
}
async function changeTaskStatus(element, task_id, task_status){
    $(element).attr('disabled', 'disabled');
    let returned;
    let new_status;
    if (task_status == 0){
        returned = await serviceGetUnCompleteActivityTask(task_id);
        new_status = 1;
    }else{
        returned = await serviceGetCompleteActivityTask(task_id);
        new_status = 0;
    }
    if(returned){
        $(element).attr('onclick', "changeTaskStatus(this, '"+ task_id + "' ,'"+ new_status +"')");
        $(element).removeAttr('disabled');
    }
}
async function updateActivityNewTask(){
    let task = document.getElementById('update-activity-task').value;
    if (task == ''){
        alert('Lütfen görev için içerik ekleyiniz.')
    }else{

        let formData = JSON.stringify({
            "activity_id": parseInt(document.getElementById('update_activity_id').value),
            "title": task
        });

        let returned = await servicePostAddActivityTask(formData);
        if (returned == false) {
            alert('Görev eklenirken bir hata oluştu. Lütfen tekrar deneyiniz!');
        }else{
            let count = document.getElementById('update-activity-task-count').value;
            count = parseInt(count) + 1;
            document.getElementById('update-activity-task-count').value = count;
            let checkInput = '<div class="form-check" id="form-check-'+ returned.task_id +'">\n' +
                '                 <input class="form-check-input" type="checkbox" value="1" data-task-id="'+ returned.task_id +'" id="update_activity_task_'+ count +'" onclick="changeTaskStatus(this, '+ returned.task_id +', 1)" />\n' +
                '                 <label class="form-check-label" for="add_activity_task_'+ count +'" id="add_activity_task_'+ count +'_label">'+ task +'</label>\n' +
                '                 (<a href="#" onclick="deleteActivityTask(event, '+ returned.task_id +');">Görevi Sil</a>)\n' +
                '             </div>';
            $('#update-activity-tasks-body').append(checkInput);
            document.getElementById('update-activity-task').value = '';
            $('#update-activity-new-tasks-input').addClass('d-none');
        }
    }
}



