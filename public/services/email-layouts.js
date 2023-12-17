(function($) {
    "use strict";

	$(document).ready(function() {
        $(":input").inputmask();
        $("#add_target_target").maskMoney({thousands:'.', decimal:','});
        $("#update_target_target").maskMoney({thousands:'.', decimal:','});

        $('#add_notify_form').submit(function (e){
            e.preventDefault();
            addNotifySetting();
        });
        $('#update_notify_form').submit(function (e){
            e.preventDefault();
            updateNotifySetting();
        });

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        getStatusesAddSelectId('add_notify_status_id');
        getAdminRolesAddSelectId('add_notify_role_id');
        getAdminsAddSelectId('add_notify_staff_id');
        initEmailLayouts();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initEmailLayouts(){

    let data = await serviceGetEmailLayouts();

    console.log(data)
	$("#layout-datatable").dataTable().fnDestroy();
	$('#layout-datatable tbody > tr').remove();

    $.each(data.layouts, function (i, layout) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateEmailLayoutModal(\''+ setting.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteEmailLayout(\''+ setting.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th>'+ layout.id +'</th>\n' +
            '           <td>'+ layout.name +'</td>\n' +
            '           <td>'+ layout.title +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#layout-datatable tbody').append(item);
    });

	$('#layout-datatable').DataTable({
		responsive: false,
		columnDefs: [],
		dom: 'Bfrtip',
        paging: false,
		buttons: [],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: false,
	});
}

async function addNotifySetting(){
    let to_notification = 0;
    let to_mail = 0;
    if(document.getElementById('add_notify_to_notification').checked){
        to_notification = 1;
    }
    if(document.getElementById('add_notify_to_mail').checked){
        to_mail = 1;
    }

    let status_id = document.getElementById('add_notify_status_id').value;
    let role_id = document.getElementById('add_notify_role_id').value;

    let receivers = [];
    let receiver_objs = $('#add_notify_staff_id').find(':selected');
    for (let i = 0; i < receiver_objs.length; i++) {
        receivers.push(receiver_objs[i].value);
    }


    let formData = JSON.stringify({
        "status_id": status_id,
        "role_id": role_id,
        "receivers": receivers,
        "to_notification": to_notification,
        "to_mail": to_mail
    });

    console.log(formData);

    let returned = await servicePostAddNotificationSetting(formData);
    if (returned){
        $("#add_notify_form").trigger("reset");
        $('.select2-selection__rendered li').remove();
        initNotifySettings();
    }else{
        alert("Hata Oluştu");
    }
}


async function openUpdateNotifySettingModal(setting_id){
    await getStatusesAddSelectId('update_notify_status_id');
    await getAdminRolesAddSelectId('update_notify_role_id');
    await getAdminsAddSelectId('update_notify_staff_id');
    $("#updateNotifySettingModal").modal('show');
    initUpdateNotifySettingModal(setting_id)
}
async function initUpdateNotifySettingModal(setting_id){
    document.getElementById('update_notify_form').reset();
    let data = await serviceGetNotifySettingById(setting_id);
    let setting = data.setting;
    document.getElementById('update_notify_id').value = setting.id;
    document.getElementById('update_notify_status_id').value = setting.status_id;
    document.getElementById('update_notify_role_id').value = setting.role_id;
    let values = JSON.parse(setting.receivers);
    $("#update_notify_staff_id").val(values).trigger("change");
    if (setting.is_notification == 1){ document.getElementById('update_notify_to_notification').checked = true; }
    if (setting.is_mail == 1){ document.getElementById('update_notify_to_mail').checked = true; }
}
async function updateNotifySetting(){
    let id = document.getElementById('update_notify_id').value;
    let to_notification = 0;
    let to_mail = 0;
    if(document.getElementById('update_notify_to_notification').checked){
        to_notification = 1;
    }
    if(document.getElementById('update_notify_to_mail').checked){
        to_mail = 1;
    }

    let status_id = document.getElementById('update_notify_status_id').value;
    let role_id = document.getElementById('update_notify_role_id').value;

    let receivers = [];
    let receiver_objs = $('#update_notify_staff_id').find(':selected');
    for (let i = 0; i < receiver_objs.length; i++) {
        receivers.push(receiver_objs[i].value);
    }


    let formData = JSON.stringify({
        "id": id,
        "status_id": status_id,
        "role_id": role_id,
        "receivers": receivers,
        "to_notification": to_notification,
        "to_mail": to_mail
    });
    let returned = await servicePostUpdateNotificationSetting(formData);
    if (returned){
        $("#update_notify_form").trigger("reset");
        $("#updateNotifySettingModal").modal('hide');
        initNotifySettings();
    }else{
        alert('Güncelleme yapılırken bir hata oluştu. Lütfen tekrar deneyiniz!');
    }
}

async function deleteNotifySetting(setting_id){
    let returned = await serviceGetDeleteNotificationSetting(setting_id);
    if(returned){
        initNotifySettings();
    }
}
