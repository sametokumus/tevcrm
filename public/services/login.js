(function($) {
    "use strict";
	
	 $(document).ready(function() {
		 
		 $(":input").inputmask();
		 $("#login_email").inputmask("email");
		 
		 
		 $( "#login_form" ).submit(function( event ) {
		 	event.preventDefault();
			let userEmail = document.getElementById('login_email').value;
			let userPass = document.getElementById('login_password').value;
			
			let formData = JSON.stringify({
				"email": userEmail,
				"password": userPass
			});
			 
			fetchDataPost('/admin/login', formData, 'application/json').then(data=>{

				if(data.status == "success"){
					let __userInfo = data.object.admin;

						console.log(__userInfo);
						sessionStorage.setItem('userRole',__userInfo.admin_role_id);
						sessionStorage.setItem('userId',__userInfo.id);
						sessionStorage.setItem('userEmail',__userInfo.email);
						sessionStorage.setItem('appToken',__userInfo.token);

						try{
							var hash = __userInfo.admin_role_id.toString()+(__userInfo.id).toString()+__userInfo.email;
							var salt = gensalt(5);
							function result(newhash){
								sessionStorage.setItem('userLogin',newhash);

								var rel = getURLParam('rel');
								console.log(__userInfo.user_type)
								if(rel != null && rel=="xxx"){
									window.location.href = "xxx?id=";
								}else{
									window.location.href = "dashboard";
								}
							}
							hashpw(hash, salt, result, function() {});


						}catch(err){
							showAlert(err);
							return;
						}

				}else{
					showAlert(data.message);
				}
			});
			
		 });
		 
	});

})(window.jQuery);