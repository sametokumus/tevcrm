(function($) {
    "use strict";

	$(document).ready(function() {
        $(":input").inputmask();
        $("#add_target_target").maskMoney({thousands:'.', decimal:','});
        $("#update_target_target").maskMoney({thousands:'.', decimal:','});

        $('#add_staff_target_form').submit(function (e){
            e.preventDefault();
            addStaffTarget();
        });
        $('#update_staff_target_form').submit(function (e){
            e.preventDefault();
            updateStaffTarget();
        });

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        getAdminsAddSelectId('add_target_admin_id');
        getStaffTargetTypesAddSelectId('add_target_type_id');
        initStaffTargets();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function addTargetChangeType(){
    let type_id = document.getElementById('add_target_type_id').value;
    if (type_id == 3){
        document.getElementById('add_target_currency').value = '%';
        $('#add_target_currency').attr('disabled', 'disabled');
    }else{
        $('#add_target_currency').removeAttr('disabled');
    }
}

function updateTargetChangeType(){
    let type_id = document.getElementById('update_target_type_id').value;
    if (type_id == 3){
        document.getElementById('update_target_currency').value = '%';
        $('#update_target_currency').attr('disabled', 'disabled');
    }else{
        $('#update_target_currency').removeAttr('disabled');
    }
}

async function initStaffTargets(){

    let data = await serviceGetStaffTargets();

    console.log(data)
	$("#targets-datatable").dataTable().fnDestroy();
	$('#targets-datatable tbody > tr').remove();

    $.each(data.targets, function (i, target) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateStaffTargetModal(\''+ target.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteStaffTarget(\''+ target.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th>'+ target.id +'</th>\n' +
            '           <td>'+ target.admin.name +' '+ target.admin.surname +'</td>\n' +
            '           <td>'+ target.type_name +'</td>\n' +
            '           <td>'+ changeCommasToDecimal(target.target) +' '+ target.currency +'</td>\n' +
            '           <td>'+ target.month_name +'</td>\n' +
            '           <td>'+ target.year +'</td>\n' +
            '           <td></td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#targets-datatable tbody').append(item);
    });

	$('#targets-datatable').DataTable({
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

async function addStaffTarget(){
    let user_id = localStorage.getItem('userId');
    let admin_id = document.getElementById('add_target_admin_id').value;
    let type_id = document.getElementById('add_target_type_id').value;
    let target = changePriceToDecimal(document.getElementById('add_target_target').value);
    let currency = document.getElementById('add_target_currency').value;
    let month = document.getElementById('add_target_month').value;
    let year = document.getElementById('add_target_year').value;


    let formData = JSON.stringify({
        "admin_id": admin_id,
        "type_id": type_id,
        "target": target,
        "currency": currency,
        "month": month,
        "year": year
    });

    console.log(formData);

    let returned = await servicePostAddStaffTarget(formData);
    if (returned){
        $("#add_staff_target_form").trigger("reset");
        initStaffTargets();
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
