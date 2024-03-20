(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#update_category_form').submit(function (e){
            e.preventDefault();
            updateCategory()
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initCategory();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCategory(){
    let category_id = getPathVariable('update-category');
    let data = await serviceGetCategoryById(category_id);
    document.getElementById('update_category_name').value = data.category.name;
}
async function updateCategory(){

    let category_id = getPathVariable('update-category');
    let formData = JSON.stringify({
        "name": document.getElementById('update_category_name').value
    });
    console.log(formData);

    let returned = await servicePostUpdateCategory(formData, category_id);
    if (returned){
        window.location = "/categories";
    }
}
