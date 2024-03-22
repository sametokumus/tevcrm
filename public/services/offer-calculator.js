(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#offer_category').on('change', function (e){
            e.preventDefault();
            let category_id = document.getElementById('offer_category').value;
            console.log(category_id)
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
