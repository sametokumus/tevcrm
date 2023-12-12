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
        $('#update_staff_target_form').submit(function (e){
            e.preventDefault();
            updateStaffTarget();
        });

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        getStatusesAddSelectId('add_notify_status_id');
        getAdminRolesAddSelectId('add_notify_role_id');
        getAdminsAddSelectId('add_notify_staff_id');
        initNotifySettings();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initNotifySettings(){

    let data = await serviceGetNotifySettings();

    console.log(data)
	// $("#targets-datatable").dataTable().fnDestroy();
	// $('#targets-datatable tbody > tr').remove();
    //
    // $.each(data.targets, function (i, target) {
    //
    //     let actions = "";
    //     if (true){
    //         actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateStaffTargetModal(\''+ target.id +'\');">Düzenle</button>\n' +
    //             '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteStaffTarget(\''+ target.id +'\');">Sil</button>\n';
    //     }
    //
    //     let item = '<tr>\n' +
    //         '           <th>'+ target.id +'</th>\n' +
    //         '           <td>'+ target.admin.name +' '+ target.admin.surname +'</td>\n' +
    //         '           <td>'+ target.type_name +'</td>\n' +
    //         '           <td>'+ changeCommasToDecimal(target.target) +' '+ target.currency +'</td>\n' +
    //         '           <td>'+ target.month_name +'</td>\n' +
    //         '           <td>'+ target.year +'</td>\n' +
    //         '           <td>'+ target.status.rate +'%</td>\n' +
    //         '           <td>'+ actions +'</td>\n' +
    //         '       </tr>';
    //     $('#targets-datatable tbody').append(item);
    // });
    //
	// $('#targets-datatable').DataTable({
	// 	responsive: false,
	// 	columnDefs: [],
	// 	dom: 'Bfrtip',
    //     paging: false,
	// 	buttons: [],
    //     scrollX: true,
	// 	language: {
	// 		url: "services/Turkish.json"
	// 	},
	// 	order: false,
	// });
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
        // initStaffTargets();
    }else{
        alert("Hata Oluştu");
    }
}


async function openUpdateStaffTargetModal(target_id){
    await getStaffTargetTypesAddSelectId('update_target_type_id');
    $("#updateStaffTargetModal").modal('show');
    initUpdateStaffTargetModal(target_id)
}
async function initUpdateStaffTargetModal(target_id){
    document.getElementById('update_staff_target_form').reset();
    let data = await serviceGetStaffTargetById(target_id);
    let target = data.target;
    document.getElementById('update_target_id').value = target.id;
    document.getElementById('update_target_type_id').value = target.type_id;
    document.getElementById('update_target_target').value = changeCommasToDecimal(target.target);
    document.getElementById('update_target_currency').value = target.currency;
    document.getElementById('update_target_month').value = target.month;
    document.getElementById('update_target_year').value = target.year;
}
async function updateStaffTarget(){
    let id = document.getElementById('update_target_id').value;
    let type_id = document.getElementById('update_target_type_id').value;
    let target = changePriceToDecimal(document.getElementById('update_target_target').value);
    let currency = document.getElementById('update_target_currency').value;
    let month = document.getElementById('update_target_month').value;
    let year = document.getElementById('update_target_year').value;


    let formData = JSON.stringify({
        "id": id,
        "type_id": type_id,
        "target": target,
        "currency": currency,
        "month": month,
        "year": year
    });
    let returned = await servicePostUpdateStaffTarget(formData);
    if (returned){
        $("#update_staff_target_form").trigger("reset");
        $("#updateStaffTargetModal").modal('hide');
        initStaffTargets();
    }else{
        alert('Güncelleme yapılırken bir hata oluştu. Lütfen tekrar deneyiniz!');
    }
}

async function deleteStaffTarget(target_id){
    let returned = await serviceGetDeleteStaffTarget(target_id);
    if(returned){
        initStaffTargets();
    }
}
