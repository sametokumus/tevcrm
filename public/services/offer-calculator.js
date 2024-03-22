(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

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
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getCategoriesAddSelectId('offer_category');

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addTestToOffer(test_id){
    let data = await serviceGetTestById(test_id);
    let test = data.test;
    console.log(test)
}
async function addCategory(){

    let formData = JSON.stringify({
        "parent_id": document.getElementById('add_category_parent').value,
        "name": document.getElementById('add_category_name').value
    });
    console.log(formData);

    let returned = await servicePostAddCategory(formData);
    if (returned){
        $("#add_category_form").trigger("reset");
    }
}
