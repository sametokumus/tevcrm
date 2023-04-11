(function($) {
    "use strict";

    $(document).ready(function() {


        $('#update_contact_form').submit(function (e){
            e.preventDefault();
            updateContact();
        });



    });

    $(window).load( function() {

        checkLogin();
        checkRole();
        let contact_id = getPathVariable('contact-detail');
        initContact(contact_id);

    });

})(window.jQuery);



function checkRole(){
    return true;
}
async function initContact(contact_id){

    document.getElementById('update_contact_form').reset();
    document.getElementById('update_contact_id').value = contact_id;
    let data = await serviceGetContactById(contact_id);
    let contact = data.contact;
    console.log(contact)
    document.getElementById('update_contact_name').value = contact.name;
    document.getElementById('update_contact_authorized_name').value = contact.authorized_name;
    document.getElementById('update_contact_email').value = contact.email;
    document.getElementById('update_contact_phone').value = contact.phone;
    document.getElementById('update_contact_address').value = contact.address;
    document.getElementById('update_contact_registration_no').value = contact.registration_no;
    document.getElementById('update_contact_registration_office').value = contact.registration_office;
    document.getElementById('update_contact_short_code').value = contact.short_code;
    $('#update_contact_current_logo').attr('href', contact.logo);
    $('#update_contact_current_footer').attr('href', contact.footer);
    $('#update_contact_current_signature').attr('href', contact.signature);
}
async function updateContactCallback(xhttp){
    let jsonData = await xhttp.responseText;
    console.log(jsonData)
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#update_contact_form").trigger("reset");
    let contact_id = getPathVariable('contact-detail');
    initContact(contact_id);
}
async function updateContact(){

    let contact_id = getPathVariable('contact-detail');
    let formData = new FormData();
    formData.append('contact_id', contact_id);
    formData.append('name', document.getElementById('update_contact_name').value);
    formData.append('authorized_name', document.getElementById('update_contact_authorized_name').value);
    formData.append('email', document.getElementById('update_contact_email').value);
    formData.append('phone', document.getElementById('update_contact_phone').value);
    formData.append('address', document.getElementById('update_contact_address').value);
    formData.append('registration_no', document.getElementById('update_contact_registration_no').value);
    formData.append('registration_office', document.getElementById('update_contact_registration_office').value);
    formData.append('short_code', document.getElementById('update_contact_short_code').value);
    formData.append('logo', document.getElementById('update_contact_logo').files[0]);
    formData.append('footer', document.getElementById('update_contact_footer').files[0]);
    formData.append('signature', document.getElementById('update_contact_signature').files[0]);
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]);
    }

    await servicePostUpdateContact(formData);
}

