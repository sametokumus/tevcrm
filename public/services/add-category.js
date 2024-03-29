(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#add_category_form').submit(function (e){
            e.preventDefault();
            addCategory();
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getParentCategoriesAddSelectId('add_category_parent');

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
