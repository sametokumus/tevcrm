(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#update_test_price").maskMoney({thousands:'.', decimal:','});

        $('#update_test_form').submit(function (e){
            e.preventDefault();
            updateTest()
        });
	});

    $(window).on('load', async function () {

		checkLogin();
		checkRole();
        await getCategoriesAddSelectId('update_test_category');
        initTest();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initTest(){
    let test_id = getPathVariable('update-test');
    let data = await serviceGetTestById(test_id);
    let test = data.test;
    document.getElementById('update_test_category').value = test.category_id;
    document.getElementById('update_test_name').value = test.name;
    document.getElementById('update_test_sample_count').value = test.sample_count;
    document.getElementById('update_test_sample_description').value = test.sample_description;
    document.getElementById('update_test_total_day').value = test.total_day;
    document.getElementById('update_test_price').value = changeCommasToDecimal(test.price);
}
async function updateTest(){

    let formData = JSON.stringify({
        "category_id": document.getElementById('update_test_category').value,
        "name": document.getElementById('update_test_name').value,
        "sample_count": document.getElementById('update_test_sample_count').value,
        "sample_description": document.getElementById('update_test_sample_description').value,
        "total_day": document.getElementById('update_test_total_day').value,
        "price": changePriceToDecimal(document.getElementById('update_test_price').value),
    });
    console.log(formData);

    let test_id = getPathVariable('update-test');
    let returned = await servicePostUpdateTest(test_id, formData);
    if (returned){
        window.location = "/tests";
    }
}
