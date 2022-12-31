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

    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        let company_id = getPathVariable('company-detail');
        initActivityTypes()

    });

})(window.jQuery);

function checkRole(){
    return true;
}


async function initActivityTypes(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetActivityTypes();
    $('#datatableActivityTypes tbody tr').remove();
    let logged_user_id = sessionStorage.getItem('userId');

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
