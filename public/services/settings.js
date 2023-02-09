(function($) {
    "use strict";

    $(document).ready(function() {

        $('#add_activity_type_form').submit(function (e){
            e.preventDefault();
            addActivityType();
        });
        $('#update_activity_type_form').submit(function (e){
            e.preventDefault();
            updateActivityType();
        });

        $('#add_bank_info_form').submit(function (e){
            e.preventDefault();
            addBankInfo();
        });
        $('#update_bank_info_form').submit(function (e){
            e.preventDefault();
            updateBankInfo();
        });

    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        initBankInfos();
        initActivityTypes();

    });

})(window.jQuery);

function checkRole(){
    return true;
}


async function initActivityTypes(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetActivityTypes();
    $('#datatableActivityTypes tbody tr').remove();
    let logged_user_id = localStorage.getItem('userId');

    $.each(data.activity_types, function (i, activity_type) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateActivityTypeModal(\''+ activity_type.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteActivityType(\''+ activity_type.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th scope="row">'+ activity_type.id +'</th>\n' +
            '           <td>'+ activity_type.name +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#datatableActivityTypes tbody').append(item);
    });

}
async function openAddActivityTypeModal(){
    $("#addActivityTypeModal").modal('show');
}
async function addActivityType(){
    let formData = JSON.stringify({
        "name":document.getElementById('add_activity_type_name').value
    });

    let returned = await servicePostAddActivityType(formData);
    if (returned){
        $("#add_activity_type_form").trigger("reset");
        $("#addActivityTypeModal").modal('hide');
        initActivityTypes();
    }
}
async function openUpdateActivityTypeModal(activity_type_id){
    let company_id = getPathVariable('company-detail');
    $("#updateActivityTypeModal").modal('show');
    initUpdateActivityTypeModal(activity_type_id)
}
async function initUpdateActivityTypeModal(activity_type_id){
    document.getElementById('update_activity_type_form').reset();
    document.getElementById('update_activity_type_id').value = activity_type_id;
    let data = await serviceGetActivityTypeById(activity_type_id);
    let activity_type = data.activity_type;
    document.getElementById('update_activity_type_name').value = activity_type.name;
}
async function updateActivityType(){
    let formData = JSON.stringify({
        "name":document.getElementById('update_activity_type_name').value
    });

    let returned = await servicePostUpdateActivityType(document.getElementById('update_activity_type_id').value, formData);
    if (returned){
        $("#update_activity_type_form").trigger("reset");
        $("#updateActivityTypeModal").modal('hide');
        initActivityTypes();
    }
}
async function deleteActivityType(activity_type_id){
    let returned = await serviceGetDeleteActivityType(activity_type_id);
    if(returned){
        initActivityTypes();
    }
}

async function initBankInfos(){
    let data = await serviceGetBankInfos();
    $('#datatableBankInfos tbody tr').remove();

    $.each(data.bank_infos, function (i, bank_info) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateBankInfoModal(\''+ bank_info.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteBankInfo(\''+ bank_info.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th scope="row">'+ bank_info.id +'</th>\n' +
            '           <td>'+ bank_info.name +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#datatableBankInfos tbody').append(item);
    });
}
async function openAddBankInfoModal(){
    $("#addBankInfoModal").modal('show');
}
async function addBankInfo(){
    let formData = JSON.stringify({
        "name":document.getElementById('add_bank_info_name').value,
        "detail":document.getElementById('add_bank_info_detail').value
    });

    let returned = await servicePostAddBankInfo(formData);
    if (returned){
        $("#add_bank_info_form").trigger("reset");
        $("#addBankInfoModal").modal('hide');
        initBankInfos();
    }
}
async function openUpdateBankInfoModal(bank_info_id){
    $("#updateBankInfoModal").modal('show');
    initUpdateBankInfoModal(bank_info_id)
}
async function initUpdateBankInfoModal(bank_info_id){
    document.getElementById('update_bank_info_form').reset();
    document.getElementById('update_bank_info_id').value = bank_info_id;
    let data = await serviceGetBankInfoById(bank_info_id);
    let bank_info = data.bank_info;
    document.getElementById('update_bank_info_name').value = bank_info.name;
    $('#update_bank_info_detail').summernote('code', bank_info.detail);
}
async function updateBankInfo(){
    let formData = JSON.stringify({
        "info_id":document.getElementById('update_bank_info_id').value,
        "name":document.getElementById('update_bank_info_name').value,
        "detail":document.getElementById('update_bank_info_detail').value
    });

    let returned = await servicePostUpdateBankInfo(formData);
    if (returned){
        $("#update_bank_info_form").trigger("reset");
        $("#updateBankInfoModal").modal('hide');
        initBankInfos();
    }
}
async function deleteBankInfo(bank_info_id){
    let returned = await serviceGetDeleteBankInfo(bank_info_id);
    if(returned){
        initBankInfos();
    }
}
