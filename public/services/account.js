(function($) {
    "use strict";

	 $(document).ready(function() {

		 $("#update_account_form").submit(async function( event ) {
			 event.preventDefault();

			 let user_id = localStorage.getItem('userId');
			 let email = document.getElementById('update_admin_email').value;
			 let name = document.getElementById('update_admin_name').value;
			 let surname = document.getElementById('update_admin_surname').value;
			 let phone = document.getElementById('update_admin_phone').value;
			 let password = document.getElementById('update_admin_password').value;
			 let formData = JSON.stringify({
				 "email":email,
				 "name":name,
				 "surname":surname,
				 "phone_number":phone,
				 "password":password
			 });

             console.log(formData)

			 await servicePostUpdateUser(user_id, formData);


		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initUser();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function updateAdminAccountCallback(xhttp){
    let jsonData = await xhttp.responseText;
    console.log(jsonData)
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    initUser();
}

async function initUser(){
    let userId = localStorage.getItem('userId');
	let data = await serviceGetAdminById(userId);
	let admin = data.admin;
	document.getElementById('update_admin_email').value = admin.email;
	document.getElementById('update_admin_name').value = admin.name;
	document.getElementById('update_admin_surname').value = admin.surname;
	document.getElementById('update_admin_phone').value = admin.phone_number;

    $('#update_admin_current_profile_photo').attr('href', admin.profile_photo);
}


