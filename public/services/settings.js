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

        $('#add_payment_term_form').submit(function (e){
            e.preventDefault();
            addPaymentTerm();
        });
        $('#update_payment_term_form').submit(function (e){
            e.preventDefault();
            updatePaymentTerm();
        });

        $('#add_delivery_term_form').submit(function (e){
            e.preventDefault();
            addDeliveryTerm();
        });
        $('#update_delivery_term_form').submit(function (e){
            e.preventDefault();
            updateDeliveryTerm();
        });

    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        initBankInfos();
        initActivityTypes();
        initPaymentTerms();
        initDeliveryTerms();

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
        "name": document.getElementById('add_bank_info_name').value,
        "detail": $('#add_bank_info_detail').summernote('code')
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


async function initPaymentTerms(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetPaymentTerms();
    $('#datatablePaymentTerms tbody tr').remove();
    let logged_user_id = localStorage.getItem('userId');

    $.each(data.payment_terms, function (i, payment_term) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdatePaymentTermModal(\''+ payment_term.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deletePaymentTerm(\''+ payment_term.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th scope="row">'+ payment_term.id +'</th>\n' +
            '           <td>'+ payment_term.name +'</td>\n' +
            '           <td>'+ payment_term.payment_type_name +'</td>\n' +
            '           <td>'+ checkNull(payment_term.expiry) +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#datatablePaymentTerms tbody').append(item);
    });

}
async function openAddPaymentTermModal(){
    await getPaymentTypesAddSelectId('add_payment_term_payment_type');
    $("#addPaymentTermModal").modal('show');
}
async function addPaymentTerm(){
    let formData = JSON.stringify({
        "name":document.getElementById('add_payment_term_name').value,
        "payment_type":document.getElementById('add_payment_term_payment_type').value,
        "expiry":document.getElementById('add_payment_term_expiry').value
    });

    let returned = await servicePostAddPaymentTerm(formData);
    if (returned){
        $("#add_payment_term_form").trigger("reset");
        $("#addPaymentTermModal").modal('hide');
        initPaymentTerms();
    }
}
async function openUpdatePaymentTermModal(payment_term_id){
    let company_id = getPathVariable('company-detail');
    $("#updatePaymentTermModal").modal('show');
    initUpdatePaymentTermModal(payment_term_id)
}
async function initUpdatePaymentTermModal(payment_term_id){
    document.getElementById('update_payment_term_form').reset();
    document.getElementById('update_payment_term_id').value = payment_term_id;
    let data = await serviceGetPaymentTermById(payment_term_id);
    let payment_term = data.payment_term;
    document.getElementById('update_payment_term_name').value = payment_term.name;
    document.getElementById('update_payment_term_payment_type').value = payment_term.payment_type_id;
    document.getElementById('update_payment_term_expiry').value = checkNull(payment_term.expiry);
}
async function updatePaymentTerm(){
    let formData = JSON.stringify({
        "name":document.getElementById('update_payment_term_name').value,
        "payment_type":document.getElementById('update_payment_term_payment_type').value,
        "expiry":document.getElementById('update_payment_term_expiry').value
    });

    let returned = await servicePostUpdatePaymentTerm(document.getElementById('update_payment_term_id').value, formData);
    if (returned){
        $("#update_payment_term_form").trigger("reset");
        $("#updatePaymentTermModal").modal('hide');
        initPaymentTerms();
    }
}
async function deletePaymentTerm(payment_term_id){
    let returned = await serviceGetDeletePaymentTerm(payment_term_id);
    if(returned){
        initPaymentTerms();
    }
}
async function addPaymentPaymentTermWithButton(day){
    document.getElementById('add_payment_term_expiry').value = day;
}
async function updatePaymentPaymentTermWithButton(day){
    document.getElementById('update_payment_term_expiry').value = day;
}


async function initDeliveryTerms(){
    let company_id = getPathVariable('company-detail');
    let data = await serviceGetDeliveryTerms();
    $('#datatableDeliveryTerms tbody tr').remove();
    let logged_user_id = localStorage.getItem('userId');

    $.each(data.delivery_terms, function (i, delivery_term) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateDeliveryTermModal(\''+ delivery_term.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteDeliveryTerm(\''+ delivery_term.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th scope="row">'+ delivery_term.id +'</th>\n' +
            '           <td>'+ delivery_term.name +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#datatableDeliveryTerms tbody').append(item);
    });

}
async function openAddDeliveryTermModal(){
    $("#addDeliveryTermModal").modal('show');
}
async function addDeliveryTerm(){
    let formData = JSON.stringify({
        "name":document.getElementById('add_delivery_term_name').value
    });

    let returned = await servicePostAddDeliveryTerm(formData);
    if (returned){
        $("#add_delivery_term_form").trigger("reset");
        $("#addDeliveryTermModal").modal('hide');
        initDeliveryTerms();
    }
}
async function openUpdateDeliveryTermModal(delivery_term_id){
    let company_id = getPathVariable('company-detail');
    $("#updateDeliveryTermModal").modal('show');
    initUpdateDeliveryTermModal(delivery_term_id)
}
async function initUpdateDeliveryTermModal(delivery_term_id){
    document.getElementById('update_delivery_term_form').reset();
    document.getElementById('update_delivery_term_id').value = delivery_term_id;
    let data = await serviceGetDeliveryTermById(delivery_term_id);
    let delivery_term = data.delivery_term;
    document.getElementById('update_delivery_term_name').value = delivery_term.name;
}
async function updateDeliveryTerm(){
    let formData = JSON.stringify({
        "name":document.getElementById('update_delivery_term_name').value
    });

    let returned = await servicePostUpdateDeliveryTerm(document.getElementById('update_delivery_term_id').value, formData);
    if (returned){
        $("#update_delivery_term_form").trigger("reset");
        $("#updateDeliveryTermModal").modal('hide');
        initDeliveryTerms();
    }
}
async function deleteDeliveryTerm(delivery_term_id){
    let returned = await serviceGetDeleteDeliveryTerm(delivery_term_id);
    if(returned){
        initDeliveryTerms();
    }
}
