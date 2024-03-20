(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#add_category_form').submit(function (e){
            e.preventDefault();
            addCategory()
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addCategory(){

    let formData = JSON.stringify({
        "name": document.getElementById('add_category_name').value
    });
    console.log(formData);

    let returned = await servicePostAddCategory(formData);
    if (returned){
        $("#add_category_form").trigger("reset");
    }
}
