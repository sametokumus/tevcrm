(function($) {
	"use strict";

	$(document).ready(function() {

		$(":input").inputmask();
        $("#add_contact_email").inputmask("email");
        $("#update_contact_email").inputmask("email");

        $('#update_customer_form').submit(function (e){
            e.preventDefault();
            updateCustomer();
        });

		$('#add_address_form').submit(function (e){
			e.preventDefault();
			addAddress();
		});

		$('#add_contact_form').submit(function (e){
			e.preventDefault();
			addContact();
		});

		$('#add_appointment_form').submit(function (e){
			e.preventDefault();
			addAppointment();
		});

		$('#update_address_form').submit(function (e){
			e.preventDefault();
			updateAddress();
		});

		$('#update_contact_form').submit(function (e){
			e.preventDefault();
			updateContact();
		});

		$('#update_appointment_form').submit(function (e){
			e.preventDefault();
			updateAppointment();
		});




	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		// initProduct();
        initCustomer();
        initCustomerAddresses();
        initCustomerContacts();
        initAppointments();
	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCustomer(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerById(customer_id);
    let customer = data.customer;
    let customer_name =  customer.name + '\n' +
        '                    <div class="d-inline-block m-3">\n' +
        '                        <button onclick="openCustomerModal();" class="btn btn-sm btn-default"><span class="fe fe-edit"></span></button>\n' +
        '                    </div>';

    $('#customer-name').html('');
    $('#customer-name').append(customer_name);
}
async function openCustomerModal(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerById(customer_id);
    let customer = data.customer;
    document.getElementById('update_customer_id').value = customer.id;
    document.getElementById('update_customer_name').value = customer.name;

    $('#updateCustomerModal').modal('show');
}
async function updateCustomer(){
    let customer_id = document.getElementById('update_customer_id').value;
    let customer_name = document.getElementById('update_customer_name').value;
    let formData = JSON.stringify({
        "name": customer_name
    });

    let returned = await servicePostUpdateCustomer(customer_id, formData);
    if(returned){
        $("#update_customer_form").trigger("reset");
        $('#updateCustomerModal').modal('hide');
        initCustomer();
    }
}
async function deleteCustomer(){
    let customer_id = getPathVariable('customer-detail');
    let returned = await serviceGetDeleteCustomer(customer_id);
    if(returned){
        window.location.href = "/customers";
    }
}

async function initCustomerAddresses(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerAddresses(customer_id);
    $("#address-datatable").dataTable().fnDestroy();
    $('#address-datatable tbody > tr').remove();

    $('#add_appointment_address option').remove();
    $('#update_appointment_address option').remove();

    $.each(data.addresses, function (i, address) {
        let userItem = '<tr>\n' +
            '              <td>'+ address.id +'</td>\n' +
            '              <td>'+ address.name +'</td>\n' +
            '              <td>'+ address.address +'</td>\n' +
            '              <td>'+ address.country +'</td>\n' +
            '              <td>'+ address.state +'</td>\n' +
            '              <td>'+ address.city +'</td>\n' +
            '              <td>'+ address.phone +'</td>\n' +
            '              <td>'+ address.fax +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button class="btn btn-sm btn-primary" onclick="openUpdateAddressModal('+ address.id +')"><span class="fe fe-edit"></span> Düzenle</button>\n' +
            '                      <button class="btn btn-sm btn-danger" onclick="deleteAddress('+ address.id +')"><span class="fe fe-trash-2"></span> Sil</button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#address-datatable tbody').append(userItem);

        let optionRow = '<option value="'+address.id+'">'+address.name+'</option>';
        $('#add_appointment_address').append(optionRow);
        $('#update_appointment_address').append(optionRow);
    });
    $('#address-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "/services/Turkish.json"
        },
        order: [[0, 'asc']],
    });
}
async function openAddAddressModal(){
    $('#addAddressModal').modal('show');
    await getCountriesAddSelectId('add_address_country');
    document.getElementById('add_address_country').addEventListener('change', () => {getStatesAddSelectIdWithParent('add_address_state', 'add_address_country'); $('#add_address_city option').remove();}, false);
    document.getElementById('add_address_state').addEventListener('change', () => {getCitiesAddSelectIdWithParent('add_address_city', 'add_address_state');}, false);

}
async function addAddress(){
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('add_address_name').value,
        "address": document.getElementById('add_address_address').value,
        "country_id": document.getElementById('add_address_country').value,
        "state_id": document.getElementById('add_address_state').value,
        "city_id": document.getElementById('add_address_city').value,
        "phone": document.getElementById('add_address_phone').value,
        "fax": document.getElementById('add_address_fax').value
    });

    let returned = await servicePostAddCustomerAddress(formData);
    if(returned){
        $("#add_address_form").trigger("reset");
        $('#addAddressModal').modal('hide');
        initCustomerAddresses();
    }
}
async function openUpdateAddressModal(address_id){
    $('#updateAddressModal').modal('show');
    document.getElementById('update_address_id').value = address_id;
    let data = await serviceGetCustomerAddressById(address_id);
    let address = data.address;

    await getCountriesAddSelectId('update_address_country');
    document.getElementById('update_address_country').addEventListener('change', () => {getStatesAddSelectIdWithParent('update_address_state', 'update_address_country'); $('#update_address_city option').remove();}, false);

    await getStatesAddSelectId('update_address_state', address.country_id);
    document.getElementById('update_address_state').addEventListener('change', () => {getCitiesAddSelectIdWithParent('update_address_city', 'update_address_state');}, false);

    await getCitiesAddSelectId('update_address_city', address.state_id);

    document.getElementById('update_address_name').value = address.name;
    document.getElementById('update_address_address').value = address.address;
    document.getElementById('update_address_country').value = address.country_id;
    document.getElementById('update_address_city').value = address.city_id;
    document.getElementById('update_address_state').value = address.state_id;
    document.getElementById('update_address_phone').value = address.phone;
    document.getElementById('update_address_fax').value = address.fax;
}
async function updateAddress(){
    let address_id = document.getElementById('update_address_id').value;
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('update_address_name').value,
        "address": document.getElementById('update_address_address').value,
        "country_id": document.getElementById('update_address_country').value,
        "state_id": document.getElementById('update_address_state').value,
        "city_id": document.getElementById('update_address_city').value,
        "phone": document.getElementById('update_address_phone').value,
        "fax": document.getElementById('update_address_fax').value
    });
    console.log(formData)
    let returned = await servicePostUpdateCustomerAddress(address_id, formData);
    if(returned){
        $("#update_address_form").trigger("reset");
        $('#updateAddressModal').modal('hide');
        initCustomerAddresses();
    }
}
async function deleteAddress(address_id){
    let returned = await serviceGetDeleteCustomerAddress(address_id);
    if(returned){
        initCustomerAddresses();
    }
}


async function initCustomerContacts(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerContacts(customer_id);
    $("#contacts-datatable").dataTable().fnDestroy();
    $('#contacts-datatable tbody > tr').remove()

    $('#add_appointment_contact option').remove();
    $('#update_appointment_contact option').remove();

    $.each(data.contacts, function (i, contact) {
        let userItem = '<tr>\n' +
            '              <td>'+ contact.id +'</td>\n' +
            '              <td>'+ contact.title +'</td>\n' +
            '              <td>'+ contact.name +'</td>\n' +
            '              <td>'+ contact.phone +'</td>\n' +
            '              <td>'+ contact.email +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button class="btn btn-sm btn-primary" onclick="openUpdateContactModal('+ contact.id +')"><span class="fe fe-edit"></span> Düzenle</button>\n' +
            '                      <button class="btn btn-sm btn-danger" onclick="deleteContact('+ contact.id +')"><span class="fe fe-trash-2"></span> Sil</button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#contacts-datatable tbody').append(userItem);

        let optionRow = '<option value="'+contact.id+'">'+contact.name+'</option>';
        $('#add_appointment_contact').append(optionRow);
        $('#update_appointment_contact').append(optionRow);
    });
    $('#contacts-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "/services/Turkish.json"
        },
        order: [[0, 'asc']],
    });
}
async function openAddContactModal(){
    $('#addContactModal').modal('show');
}
async function addContact(){
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "title": document.getElementById('add_contact_title').value,
        "name": document.getElementById('add_contact_name').value,
        "phone": document.getElementById('add_contact_phone').value,
        "email": document.getElementById('add_contact_email').value
    });

    let returned = await servicePostAddCustomerContact(formData);
    if(returned){
        $("#add_contact_form").trigger("reset");
        $('#addContactModal').modal('hide');
        initCustomerContacts();
    }
}
async function openUpdateContactModal(contact_id){
    $('#updateContactModal').modal('show');
    document.getElementById('update_contact_id').value = contact_id;
    let data = await serviceGetCustomerContactById(contact_id);
    let contact = data.contact;

    document.getElementById('update_contact_title').value = contact.title;
    document.getElementById('update_contact_name').value = contact.name;
    document.getElementById('update_contact_phone').value = contact.phone;
    document.getElementById('update_contact_email').value = contact.email;
}
async function updateContact(){
    let contact_id = document.getElementById('update_contact_id').value;
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "title": document.getElementById('update_contact_title').value,
        "name": document.getElementById('update_contact_name').value,
        "phone": document.getElementById('update_contact_phone').value,
        "email": document.getElementById('update_contact_email').value
    });
    let returned = await servicePostUpdateCustomerContact(contact_id, formData);
    if(returned){
        $("#update_contact_form").trigger("reset");
        $('#updateContactModal').modal('hide');
        initCustomerContacts();
    }
}
async function deleteContact(contact_id){
    let returned = await serviceGetDeleteCustomerContact(contact_id);
    if(returned){
        initCustomerContacts();
    }
}


async function initAppointments(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetAppointments(customer_id);
    $("#appointments-datatable").dataTable().fnDestroy();
    $('#appointments-datatable tbody > tr').remove()

    $('#add_appointment_contact option').remove();

    $.each(data.appointments, function (i, appointment) {
        let userItem = '<tr>\n' +
            '              <td>'+ appointment.id +'</td>\n' +
            '              <td>'+ appointment.staff +'</td>\n' +
            '              <td>'+ appointment.contact +'</td>\n' +
            '              <td>'+ formatDateAndTimeDESC(appointment.date, "/") +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button class="btn btn-sm btn-primary" onclick="openUpdateAppointmentModal('+ appointment.id +')"><span class="fe fe-edit"></span> Düzenle</button>\n' +
            '                      <button class="btn btn-sm btn-danger" onclick="deleteAppointment('+ appointment.id +')"><span class="fe fe-trash-2"></span> Sil</button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#appointments-datatable tbody').append(userItem);
    });
    $('#appointments-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "/services/Turkish.json"
        },
        order: [[0, 'desc']],
    });
}
async function openAddAppointmentModal(){
    $('#addAppointmentModal').modal('show');
}
async function addAppointment(){
    let customer_id = getPathVariable('customer-detail');
    let staff_id = sessionStorage.getItem('userId');
    let date = formatDateDESC(document.getElementById('add_appointment_date').value, "-", "/") + " " + document.getElementById('add_appointment_time').value + ":00";
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "address_id": document.getElementById('add_appointment_address').value,
        "contact_id": document.getElementById('add_appointment_contact').value,
        "staff_id": staff_id,
        "date": date
    });
    let returned = await servicePostAddAppointment(formData);
    if(returned){
        $("#add_appointment_form").trigger("reset");
        $('#addAppointmentModal').modal('hide');
        initAppointments();
    }
}
async function openUpdateAppointmentModal(appointment_id){
    $('#updateAppointmentModal').modal('show');
    document.getElementById('update_appointment_id').value = appointment_id;
    let data = await serviceGetAppointmentById(appointment_id);
    let appointment = data.appointment;

    document.getElementById('update_appointment_customer').value = appointment.customer_id;
    document.getElementById('update_appointment_staff').value = appointment.staff_id;
    document.getElementById('update_appointment_address').value = appointment.address_id;
    document.getElementById('update_appointment_contact').value = appointment.contact_id;
    document.getElementById('update_appointment_notes').value = appointment.notes;
    document.getElementById('update_appointment_date').value = formatDateASC(appointment.date, "/");
    document.getElementById('update_appointment_time').value = formatTime(appointment.date);
}
async function updateAppointment(){
    let appointment_id = document.getElementById('update_appointment_id').value;
    let date = formatDateDESC(document.getElementById('update_appointment_date').value, "-", "/") + " " + document.getElementById('update_appointment_time').value + ":00";
    let formData = JSON.stringify({
        "customer_id": document.getElementById('update_appointment_customer').value,
        "staff_id": document.getElementById('update_appointment_staff').value,
        "address_id": document.getElementById('update_appointment_address').value,
        "contact_id": document.getElementById('update_appointment_contact').value,
        "date": date,
        "notes": document.getElementById('update_appointment_notes').value,
    });
    let returned = await servicePostUpdateAppointment(appointment_id, formData);
    if(returned){
        $("#update_appointment_form").trigger("reset");
        $('#updateAppointmentModal').modal('hide');
        initAppointments();
    }
}
async function deleteAppointment(appointment_id){
    let returned = await serviceGetDeleteAppointment(appointment_id);
    if(returned){
        initAppointments();
    }
}

