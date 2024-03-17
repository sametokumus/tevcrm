(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_company_email").inputmask("email");

        $('#add_customer_form').submit(function (e){
            e.preventDefault();
            addCompany()
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getCountriesAddSelectId('add_company_country');

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addCompany(){

    let formData = JSON.stringify({
        "name": document.getElementById('add_company_name').value,
        "email": document.getElementById('add_company_email').value,
        "website": document.getElementById('add_company_website').value,
        "phone": document.getElementById('add_company_phone').value,
        "fax": document.getElementById('add_company_fax').value,
        "address": document.getElementById('add_company_address').value,
        "tax_office": document.getElementById('add_company_tax_office').value,
        "tax_number": document.getElementById('add_company_tax_number').value,
        "country": document.getElementById('add_company_country').value,
        "user_id": localStorage.getItem('userId')
    });
    console.log(formData);

    let returned = await servicePostAddCustomer(formData);
    if (returned){
        $("#add_company_form").trigger("reset");
    }
}
