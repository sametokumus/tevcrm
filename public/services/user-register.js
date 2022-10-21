(function($) {
    "use strict";
	
	 $(document).ready(function() {
		 
		 $(":input").inputmask();
		 $("#register_phone_number").inputmask({"mask": "9999999999"});
		 $("#register_email").inputmask("email");

		 $('#register_password_2').on('keyup', function (e){
			e.preventDefault();
			 let val1 = document.getElementById('register_password_1').value;
			 let val2 = document.getElementById('register_password_2').value;
			 if(val2 == ''){
				 $('#register_password_2').removeClass('valid');
				 $('#register_password_2').removeClass('invalid');
			 }else{
				 if(val1 == val2){
					 $('#register_password_2').removeClass('invalid');
					 $('#register_password_2').addClass('valid');
				 }else{
					 $('#register_password_2').addClass('invalid');
				 }
			 }

		 });
		 
		 // $('#policyModalBtn').click(function(event){
			//  event.preventDefault();
			//
			// fetchDataGet('/web/document/getDocument?documentCode=1', 'application/json').then(data=>{
		 //
			// 	var document = data.object;
			// 	if(data.success == true){
		 //
			// 		$('#policyModal .policyModalContent').append(document.document);
			// 		$('#policyModal').modal('show');
		 //
			// 	}else{
			// 		showAlert("İstek Başarısız.");
			// 	}
			// });
			//
		 // });
		 
		 $("#user-register-form").submit(function( event ) {
		 	event.preventDefault();

			 var userPass1 = document.getElementById('register_password_1').value;
			 var userPass2 = document.getElementById('register_password_2').value;

			 if(userPass1 == userPass2) {

				 var userName = document.getElementById('register_first_name').value;
				 var userSurname = document.getElementById('register_last_name').value;
				 var userEmail = document.getElementById('register_email').value;
				 var userPhone = document.getElementById('register_phone_number').value;

				 let rcr1 = 0;
				 if ($('#register_contact_rule_1').is(':checked')) {
					 rcr1 = 1;
				 }

				 let rcr2 = 0;
				 if ($('#register_contact_rule_2').is(':checked')) {
					 rcr2 = 1;
				 }

				 let rd1 = 0;
				 if ($('#register_document_1').is(':checked')) {
					 rd1 = 1;
				 }

				 let rd2 = 0;
				 if ($('#register_document_2').is(':checked')) {
					 rd2 = 1;
				 }

				 let rd3 = 0;
				 if ($('#register_document_3').is(':checked')) {
					 rd3 = 1;
				 }

				 var formData = JSON.stringify({
				 	"email": userEmail,
				 	"name": userName,
				 	"surname": userSurname,
				 	"phone_number": userPhone,
				 	"password": userPass1,
				 	"user_contact_rules": [
						{
							"contact_rule_id": 1,
							"value": rcr1
						},
						{
							"contact_rule_id": 2,
							"value": rcr2
						}
					],
				 	"user_document_checks": [
						{
							"document_id": 1,
							"value": rd1
						},
						{
							"document_id": 2,
							"value": rd2
						},
						{
							"document_id":3,
							"value": rd3
						}
					]
				 });

				 fetchDataPost('/v1/auth/register', formData, 'application/json').then(data=>{
					 console.log(data);
				 	if(data.status == "success"){
						$("#user-register-form").trigger("reset");
						showAlert(data.message);

				 		// var formData2 = JSON.stringify({
				 		// 	"userId": data.object.id,
				 		// 	"requiredDocument": 'KVKK',
				 		// 	"status": true
				 		// });
						//
				 		// fetchDataPost('/auth/approveRequiredDocuments', formData2, 'application/json').then(data2=>{});
						//
				 		// $('#sms-check-form #userId').val(data.object.id);
				 		// showAlert(data.message);
				 		// $('#smsCheckModal').modal('show');
				 		// smsCountDown();

				 	}else{
				 		showAlert(data.message);
				 	}

				 });
			 }else{
				 showAlert("Şifreler aynı değil!");
			 }
			
		 });
		 
		 
		 $( "#sms-check-form" ).submit(function( event ) {
		 	event.preventDefault();
			 
			var userId = document.getElementById('userId').value;
			var smsCode = document.getElementById('smscode').value;
			
			var formData = JSON.stringify({
				"userId": userId,
				"smsCode": smsCode
			});
			 
			fetchDataPost('/web/sms/check', formData, 'application/json').then(data=>{

				if(data.success == true){
					gtag('event', 'conversion', {'send_to': 'AW-10835074857/dn69CKGlqI4DEKm-yK4o'});
					showAlert(data.message);
					setTimeout(function(){window.location.href = "login.php";},3000);
										
				}else{
					showAlert("Doğrulama Başarısız");
				}
				
			});
			
		 });
		 
		 
		 $("#policyCheck").click(function() {
			 if(this.checked){
				 document.getElementById('patient-register-btn').removeAttribute('disabled');
			 }else{
				 document.getElementById('patient-register-btn').setAttribute('disabled','disabled');
			 }
		 });
		 
		
    });


	function smsCountDown() {
		var countdownNumberEl = document.getElementById("countdown-number");
		var countdown = 120;
		countdownNumberEl.textContent = countdown;
		var downloadTimer = setInterval(function() {
		  if (countdown > 0) {
			countdown--; // 120 to 0
		  }else if(countdown == 0){
			  $('#smsCheckModal').modal('hide');
		  }
		  countdownNumberEl.textContent = countdown;
		}, 1000);
	}


	

})(window.jQuery);

  
	