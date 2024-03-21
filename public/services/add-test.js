(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_test_price").maskMoney({thousands:'.', decimal:','});

        $('#add_test_form').submit(function (e){
            e.preventDefault();
            addTest()
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getCategoriesAddSelectId('add_test_category');

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addTest(){

    let formData = JSON.stringify({
        "category_id": document.getElementById('add_test_category').value,
        "name": document.getElementById('add_test_name').value,
        "sample_count": document.getElementById('add_test_sample_count').value,
        "sample_description": document.getElementById('add_test_sample_description').value,
        "total_day": document.getElementById('add_test_total_day').value,
        "price": changePriceToDecimal(document.getElementById('add_test_price').value),
    });
    console.log(formData);

    let returned = await servicePostAddTest(formData);
    if (returned){
        $("#add_test_form").trigger("reset");
    }
}
