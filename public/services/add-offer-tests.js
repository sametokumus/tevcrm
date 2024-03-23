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

        $('#offer_category').on('change', function (e){
            e.preventDefault();
            let category_id = document.getElementById('offer_category').value;
            console.log(category_id)
            if (category_id == 'Kategori Seçiniz'){
                $('#offer_test option').remove();
            }else{
                getTestsByCategoryAddSelectId(category_id, 'offer_test');
            }
        });

        $('#offer_test_btn').on('click', function (e){
            let test_id = document.getElementById('offer_test').value;
            addTestToOffer(test_id);
        });

        $('#offer_info_form').submit(function (e){
            e.preventDefault();
            updateOffer();
        });
	});

    $(window).on('load', async function () {

		checkLogin();
		checkRole();
        initOffer();
        getCategoriesAddSelectId('offer_category');
	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function updateOffer(){
    let offer_id = getPathVariable('add-offer-tests');

    let formData = JSON.stringify({
        "offer_id": offer_id,
        "customer": document.getElementById('offer_customer').value,
        "employee": document.getElementById('offer_employee').value,
        "manager": document.getElementById('offer_manager').value,
        "lab_manager": document.getElementById('offer_lab_manager').value,
        "description": document.getElementById('offer_description').value
    });
    console.log(formData);

    let data = await servicePostUpdateOffer(formData);
    // if (data.status == "success"){
    //     window.location = "add-offer-tests/" + data.object.offer_id;
    // }
}
async function initOffer(){
    let offer_id = getPathVariable('add-offer-tests');

    let data = await serviceGetOfferInfoById(offer_id);
    let offer = data.offer;

    await getCustomersAddSelectId('offer_customer');
    document.getElementById('offer_customer').value = offer.customer_id;
    await getAdminsAddSelectId('offer_manager');
    document.getElementById('offer_manager').value = offer.manager_id;
    await getAdminsAddSelectId('offer_lab_manager');
    document.getElementById('offer_lab_manager').value = offer.lab_manager_id;
    await getEmployeesAddSelectId(offer.customer_id, 'offer_employee');
    document.getElementById('offer_employee').value = offer.employee_id;

    document.getElementById('offer_description').value = offer.description;

}
async function addTestToOffer(test_id){
    let data = await serviceGetTestById(test_id);
    let test = data.test;
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) + parseFloat(test.price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    let item = '<tr class="test-item">\n' +
        '                  <td></td>\n' +
        '                  <td>'+ checkNull(test.name) +'</td>\n' +
        '                  <td>'+ checkNull(test.sample_count) +'</td>\n' +
        '                  <td>'+ checkNull(test.sample_description) +'</td>\n' +
        '                  <td>'+ checkNull(test.total_day) +' Gün</td>\n' +
        '                  <td>'+ changeCommasToDecimal(test.price) +' ₺</td>\n' +
        '                  <td><button type="button" onclick="removeTestItem(this, '+test.price+')" class="btn btn-sm btn-theme offer_remove_test_btn w-100">Tekliften Çıkar</button></td>\n' +
        '              </tr>';

    $('#tests-table tbody').append(item);
}
async function removeTestItem(element, price){
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) - parseFloat(price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    $(element).closest('.test-item').remove();
}

