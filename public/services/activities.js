(function($) {
    "use strict";

	$(document).ready(function() {

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

	$(window).load(async function() {

		checkLogin();
		checkRole();

        initActivities();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initActivities(){

    let data = await serviceGetActivities();

    console.log(data)
	$("#activities-datatable").dataTable().fnDestroy();
	$('#activities-datatable tbody > tr').remove();

    $.each(data.activities, function (i, activity) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateCompanyActivityModal(\''+ activity.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteActivity(\''+ activity.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th class="bg-dark">'+ activity.id +'</th>\n' +
            '           <td class="bg-dark">'+ activity.company.name +'</td>\n' +
            '           <td class="bg-dark">'+ activity.employee.name +'</td>\n' +
            '           <td class="bg-dark">'+ activity.user.name +' '+ activity.user.surname +'</td>\n' +
            '           <td>'+ activity.type.name +'</td>\n' +
            '           <td>'+ activity.title +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.start, "/") +'</td>\n' +
            '           <td>'+ formatDateAndTimeDESC(activity.end, "/") +'</td>\n' +
            '           <td>'+ activity.task_count +' görev ('+ activity.completed_task_count +' tamamlanan)</td>\n' +
            '           <td></td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#activities-datatable tbody').append(item);
    });

	$('#activities-datatable').DataTable({
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
                targets: 6,
                render: function(data, type, row) {
                    return moment(data, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                }
            },
            {
                type: 'date',
                targets: 7,
                render: function(data, type, row) {
                    return moment(data, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                }
            }
		],
		dom: 'Bfrtip',
        paging: false,
		buttons: [
            {
                text: 'Aktivite Oluştur',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    openAddCompanyActivityModal();
                }
            }
        ],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: false,
        fixedColumns: {
        left: 4
        }
	});
}

async function openAddCompanyActivityModal(){
    getActivityTypesAddSelectId('add_activity_type_id');
    await getCompaniesAddSelectId('add_activity_company_id');
    $("#addCompanyActivityModal").modal('show');
}
async function initActivityAddModalEmployee(){
    let company_id = document.getElementById('add_activity_company_id').value;
    if (company_id != 0) {
        getEmployeesAddSelectId(company_id, 'add_activity_employee_id');
    }else{
        $('#add_activity_employee_id option').remove();
    }
}
async function addActivity(){
    let company_id = document.getElementById('add_activity_company_id').value;
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
        initActivities();
    }else{
        alert("Hata Oluştu");
    }
}
async function openUpdateCompanyActivityModal(activity_id){
    getActivityTypesAddSelectId('update_activity_type_id');
    await getCompaniesAddSelectId('add_activity_company_id');
    $("#updateCompanyActivityModal").modal('show');
    initUpdateCompanyActivityModal(activity_id)
}
async function initActivityUpdateModalEmployee(){
    let company_id = document.getElementById('update_activity_company_id');
    if (company_id != 0) {
        getEmployeesAddSelectId(company_id, 'update_activity_employee_id');
    }else{
        $('#update_activity_employee_id option').remove();
    }
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
    document.getElementById('update_activity_start_date').value = formatDateASC(activity.start, "-");
    document.getElementById('update_activity_start_time').value = formatTime(activity.start);
    document.getElementById('update_activity_end_date').value = formatDateASC(activity.end, "-");
    document.getElementById('update_activity_end_time').value = formatTime(activity.end);
    document.getElementById('update_activity_company_id').value = activity.company_id;
    await getEmployeesAddSelectId(activity.company_id, 'update_activity_employee_id');
    // document.getElementById('update_activity_employee_id').value = activity.employee_id;

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
    let company_id = document.getElementById('update_activity_company_id').value;
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
