(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#offer_customer').on('change', function (e){
            e.preventDefault();
            let customer_id = document.getElementById('offer_customer').value;
            console.log(customer_id)
            if (customer_id == '0'){
                $('#offer_employee option').remove();
            }else{
                getEmployeesAddSelectId(customer_id, 'offer_employee');
            }
        });

        $('#offer_info_form').submit(function (e){
            e.preventDefault();
            addOffer();
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getCustomersAddSelectId('offer_customer');
        getAdminsAddSelectId('offer_manager');
        getAdminsAddSelectId('offer_lab_manager');

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addOffer(){

    let formData = JSON.stringify({
        "customer": document.getElementById('offer_customer').value,
        "employee": document.getElementById('offer_employee').value,
        "manager": document.getElementById('offer_manager').value,
        "lab_manager": document.getElementById('offer_lab_manager').value,
        "description": document.getElementById('offer_description').value
    });
    console.log(formData);

    let data = await servicePostAddOffer(formData);
    if (data.status == "success"){
        window.location = "add-offer-tests/" + data.object.offer_id;
    }
}

