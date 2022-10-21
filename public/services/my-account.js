(function($) {
    "use strict";
	
	 $(document).ready(function() {
		 
		 $(":input").inputmask();
		 $("#user_phone_number").inputmask({"mask": "9999999999"});
		 $("#user_email").inputmask("email");

		 $('.get_user_profile').click(function (e){
			 getUserProfile();
		 });

		 $('.get_user_addresses').click(function (e){
			 getUserAddresses();
		 });

		 $('.get_user_contact_rules').click(function (e){
			 getContactRules();
		 });


		 $("#account-details-form").submit(function( event ) {
			 event.preventDefault();
			 let userId = sessionStorage.getItem('userId');
			 let userName = document.getElementById('user_firstname').value;
			 let userSurname = document.getElementById('user_lastname').value;
			 let userEmail = document.getElementById('user_email').value;
			 let userPhone = document.getElementById('user_phone_number').value;
			 let userBirthDate = document.getElementById('user_birth_date').value;
			 let userTcNo = document.getElementById('user_tc_no').value;
			 let genderRadios = document.getElementsByName('user_gender');
			 let userGender = 0;
			 for (let i = 0, length = genderRadios.length; i < length; i++) {
				 if (genderRadios[i].checked) {
					 userGender = genderRadios[i].value;
					 break;
				 }
			 }

			 let userIsTc = 0;
			 if ($('#tc_toggle').hasClass('checked')) {
				 userIsTc = 1;
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

			 let profile = JSON.stringify({
				 "user_name":"",
				 "email": userEmail,
				 "phone_number": userPhone,
				 "name": userName,
				 "surname": userSurname,
				 "birthday": userBirthDate,
				 "gender": userGender,
				 "tc_citizen": userIsTc,
				 "tc_number": userTcNo,
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
			 console.log(profile);
			 var formData = new FormData();

			 formData.append('profile', profile);
			 formData.append('profile_photo', document.getElementById('user_profile_image').files[0]);

			 xhrDataPost('/v1/user/updateUser/'+userId, formData, userProfileUpdateCallback);

		 });

		 $('#tc_toggle').click(function (e){
			 if($('#tc_toggle').hasClass('checked')){
				 $('#tc_toggle').removeClass('checked');
				 $('.tc_toggle_content').removeClass('open');
			 }else{
				 $('#tc_toggle').addClass('checked');
				 $('.tc_toggle_content').addClass('open');
			 }
		 });


		 $("#change-password-form").submit(function( event ) {
			 event.preventDefault();

			 var userPass1 = document.getElementById('cur_password').value;
			 var userPass2 = document.getElementById('new_password').value;
			 var userPass3 = document.getElementById('conf_password').value;
			 let userId = sessionStorage.getItem('userId');

			 let formData = JSON.stringify({
				 "password":userPass2
			 });

			 if(userPass2 == userPass3) {

				 fetchDataPost('/v1/user/changePassword/' + userId, formData, 'application/json').then(data=>{
					 if(data.status == "success"){
						 $("#change-password-form").trigger("reset");
						 showAlert(data.message);
					 }else{
						 showAlert(data.message);
					 }
				 });
			 }else{
				 showAlert("Şifreler aynı değil!");
			 }
		 });

		 $('#conf_password').on('keyup', function (e){
			 e.preventDefault();
			 let val1 = document.getElementById('new_password').value;
			 let val2 = document.getElementById('conf_password').value;
			 if(val2 == ''){
				 $('#conf_password').removeClass('valid');
				 $('#conf_password').removeClass('invalid');
			 }else{
				 if(val1 == val2){
					 $('#conf_password').removeClass('invalid');
					 $('#conf_password').addClass('valid');
				 }else{
					 $('#conf_password').addClass('invalid');
				 }
			 }
		 });
	});

	$(window).load( function() {

		checkLogin();
		isUser();

	});

})(window.jQuery);

var userProfileImageFile = function (event) {
	var image = document.getElementById("output_user_profile_image");
	image.src = URL.createObjectURL(event.target.files[0]);
};

function userProfileUpdateCallback(xhttp){
	let jsonData = xhttp.responseText;
	const obj = JSON.parse(jsonData);
	console.log(obj)
	// $('#patientProfileModal').modal('hide');
	// showAlert(obj.message);
	// setTimeout(function(){location.reload();},3000);
}

function isUser(){
	if(sessionStorage.getItem('userType') != 1){
		showAlert('Yetkisiz kullanıcı');
		removeSession();
		window.location.href = "giris-yap";
	}
}

async function getUserProfile(){
	let userId = sessionStorage.getItem('userId');
	let data = await serviceGetUserProfile(userId);

	let user = data.user;
	let user_profile = data.user_profile;
	let userType = sessionStorage.getItem('userType');
	console.log(user, user_profile)

	if(user.user_type == userType){

		var userPhoto = '';
		if(user_profile.profile_photo == null){
			userPhoto = 'images/default-profile-image.png';
		}else{
			userPhoto = 'https://api-kablocu.wimco.com.tr'+user_profile.profile_photo;
		}
		$('#output_user_profile_image').attr('src',userPhoto);

		document.getElementById('user_firstname').value = user_profile.name;
		document.getElementById('user_lastname').value = user_profile.surname;
		document.getElementById('user_email').value = user.email;
		document.getElementById('user_phone_number').value = user.phone_number;
		if(user_profile.birthday != null) {
			document.getElementById('user_birth_date').value = user_profile.birthday;
		}

		if(user_profile.gender == 1){
			document.getElementById('user_gender_woman').checked = true;
		}else if(user_profile.gender == 2){
			document.getElementById('user_gender_man').checked = true;
		}

		if(user_profile.tc_citizen == 1){
			$('#tc_toggle').click();
			document.getElementById('user_tc_no').value = user_profile.tc_number;
		}

		document.getElementById('register_document_1').checked = true;
		document.getElementById('register_document_2').checked = true;
		document.getElementById('register_document_3').checked = true;

	}else{
		showAlert('Kullanıcı Türü Hatalı.');
	}

}

let addressAddCorporateContainer = function (e){
	e.preventDefault();
	if($('#add_address_corporate_toggle').hasClass('checked')){
		$('#add_address_corporate_toggle_btn .custom-checkbox').removeClass('checked');
		$('.add_address_corporate_content').removeClass('open');
	}else{
		$('#add_address_corporate_toggle_btn .custom-checkbox').addClass('checked');
		$('.add_address_corporate_content').addClass('open');
	}
}

let addressUpdateCorporateContainer = function (e){
	e.preventDefault();
	if($('#update_address_corporate_toggle').hasClass('checked')){
		$('#update_address_corporate_toggle_btn .custom-checkbox').removeClass('checked');
		$('.update_address_corporate_content').removeClass('open');
	}else{
		$('#update_address_corporate_toggle_btn .custom-checkbox').addClass('checked');
		$('.update_address_corporate_content').addClass('open');
	}
}

function addAddressFormSubmit(event){
	event.preventDefault();

	let userId = sessionStorage.getItem('userId');
	let title = document.getElementById('add_address_title').value;
	let name = document.getElementById('add_address_firstname').value;
	let surname = document.getElementById('add_address_lastname').value;
	let city = document.getElementById('add_address_city').value;
	let district = document.getElementById('add_address_district').value;
	let phone = document.getElementById('add_address_phone').value;
	let postal_code = document.getElementById('add_address_postal_code').value;
	let address1 = document.getElementById('add_address_address_1').value;
	let address2 = document.getElementById('add_address_address_2').value;
	let comment = document.getElementById('add_address_comment').value;
	let type = 1;
	if ($('#add_address_corporate_toggle').hasClass('checked')) {
		type = 2;
	}
	let company = document.getElementById('add_address_company_name').value;
	let vd = document.getElementById('add_address_vd').value;
	let vkn = document.getElementById('add_address_vkn').value;

	let formData = JSON.stringify({
		"country_id":1,
		"city_id":city,
		"district_id":district,
		"title":title,
		"name":name,
		"surname":surname,
		"address_1":address1,
		"address_2":address2,
		"postal_code":postal_code,
		"phone":phone,
		"comment":comment,
		"type":type,
		"tax_number":vkn,
		"tax_office":vd,
		"company_name":company
	});

	fetchDataPost('/v1/addresses/addUserAddresses/'+userId, formData, 'application/json').then(data=>{
		if(data.status == "success"){
			$("#add-address-form").trigger("reset");
			$("#add_address_district option").remove();
			$(".mfp-close").click();
			showAlert(data.message);

		}else{
			showAlert(data.message);
		}
	});
}

async function getUserAddresses(){

	let userId = sessionStorage.getItem('userId');
	let addressHTML = document.getElementById('account_address_list').innerHTML.trim();
	if (addressHTML == '') {

		let data = await serviceGetUserAddresses(userId);
		$.each(data.addresses, function (i, address) {
			var addressItem = '<div class="col-md-4 col-sm-6" id="address-item-'+address.id+'">\n' +
				'                  <div class="address-item">\n' +
				'                      <div class="address-content">\n' +
				'                          <h4 class="address-title">' + address.title + '</h4>\n' +
				'                          <p class="address-name">' + address.name + ' ' + address.surname + '</p>\n' +
				'                          <p class="address">\n' +
				'                              ' + address.address_1 + ' ' + address.address_2 + '\n' +
				'                          </p>\n' +
				'                          <p class="address-city">' + address.district.name + '-' + address.city.name + '/' + address.country.name + '</p>\n' +
				'                          <p class="address-phone">' + address.phone + '</p>\n' +
				'                      </div>\n' +
				'                      <div class="address-actions text-right">\n' +
				'                          <button onclick="deleteAccountAddress(' + address.id + ');" class="btn btn-link btn-rounded">Sil</button>\n' +
				'                          <button onclick="updateAccountAddress(' + address.id + ');" class="btn btn-primary btn-rounded btn-outline">Düzenle</button>\n' +
				'                      </div>\n' +
				'                  </div>\n' +
				'              </div>';

			$('#account_address_list').append(addressItem);

		});

	}

}

function deleteAccountAddress(address_id){
	fetchDataGet('/v1/addresses/deleteUserAddresses/' + address_id, 'application/json').then(data => {
		if (data.status == "success") {

			$('#address-item-'+address_id).remove();
			showAlert(data.message);

		} else {
			showAlert('İstek Başarısız.');
		}
	});
}

async function updateAccountAddress(address_id){
	$('.btn-update-address').click();
	let userId = sessionStorage.getItem('userId');
	let data = await serviceGetUserAddressById(userId,address_id);

	let address = data.address;

	document.getElementById('update_address_id').value = address.id;

	$('#update_address_corporate_toggle').removeClass('checked');
	$('.update_address_corporate_content').removeClass('open');

	document.getElementById('update_address_title').value = address.title;
	document.getElementById('update_address_firstname').value = address.name;
	document.getElementById('update_address_lastname').value = address.surname;
	document.getElementById('update_address_phone').value = address.phone;
	document.getElementById('update_address_postal_code').value = address.postal_code;
	document.getElementById('update_address_address_1').value = address.address_1;
	document.getElementById('update_address_address_2').value = address.address_2;
	document.getElementById('update_address_comment').value = address.comment;
	if (address.type == 2) {
		$('#update_address_corporate_toggle').addClass('checked');
		$('.update_address_corporate_content').addClass('open');

		document.getElementById('update_address_company_name').value = address.company_name;
		document.getElementById('update_address_vd').value = address.tax_office;
		document.getElementById('update_address_vkn').value = address.tax_number;
	}else{
		document.getElementById('update_address_company_name').value = '';
		document.getElementById('update_address_vd').value = '';
		document.getElementById('update_address_vkn').value = '';
	}

	await getCitiesAddSelectId('update_address_city');
	document.getElementById('update_address_city').addEventListener('change', () => {getDistrictsAddSelect('update_address_city', 'update_address_district');}, false);
	document.getElementById('update_address_city').value = address.city_id;
	await getDistrictsAddSelectAutoUpdate(address.city_id, 'update_address_district');
	document.getElementById('update_address_district').value = address.district_id;

}

async function updateAddressFormSubmit(event){
	event.preventDefault();

	let userId = sessionStorage.getItem('userId');
	let addressId = document.getElementById('update_address_id').value;
	let title = document.getElementById('update_address_title').value;
	let name = document.getElementById('update_address_firstname').value;
	let surname = document.getElementById('update_address_lastname').value;
	let city = document.getElementById('update_address_city').value;
	let district = document.getElementById('update_address_district').value;
	let phone = document.getElementById('update_address_phone').value;
	let postal_code = document.getElementById('update_address_postal_code').value;
	let address1 = document.getElementById('update_address_address_1').value;
	let address2 = document.getElementById('update_address_address_2').value;
	let comment = document.getElementById('update_address_comment').value;
	let type = 1;
	if ($('#update_address_corporate_toggle').hasClass('checked')) {
		type = 2;
	}
	let company = document.getElementById('update_address_company_name').value;
	let vd = document.getElementById('update_address_vd').value;
	let vkn = document.getElementById('update_address_vkn').value;

	let formData = JSON.stringify({
			"country_id":1,
			"city_id":city,
			"district_id":district,
			"title":title,
			"name":name,
			"surname":surname,
			"address_1":address1,
			"address_2":address2,
			"postal_code":postal_code,
			"phone":phone,
			"comment":comment,
			"type":type,
			"tax_number":vkn,
			"tax_office":vd,
			"company_name":company
		});

	await servicePostUpdateUserAddress(userId, addressId, formData);

	$("#update-address-form").trigger("reset");
	$("#update_address_district option").remove();
	$(".mfp-close").click();
	$('#account_address_list > div').remove();
	await getUserAddresses();
}

async function getContactRules(){

	let userId = sessionStorage.getItem('userId');

	let ruleHTML = document.getElementById('contact_rules_list').innerHTML.trim();
	if (ruleHTML == '') {

		let data = await serviceGetContactRules();
		$.each(data.contact_rules, function (i, rules) {
			let ruleItem = 	'<div class="contact-rule-item">\n' +
							'    <div class="row">\n' +
							'        <div class="col-md-10">\n' +
							'            <h4 class="rule-title">' + rules.name + '</h4>\n' +
							'            <p class="rule-description">' + rules.description + '</p>\n' +
							'        </div>\n' +
							'        <div class="col-md-2">\n' +
							'            <ul id="contact-rules-block" class="mt-0 mb-0">\n' +
							'                <li>\n' +
							'                    <div class="custom-radio" onclick="updateUserContactRule('+rules.id+', 1);">\n' +
							'                        <input type="radio" id="account_rule' + i + 'yes" class="custom-control-input" name="contactRules' + i + '">\n' +
							'                        <label for="account_rule' + i + 'yes" class="custom-control-label color-dark">Evet</label>\n' +
							'                    </div>\n' +
							'                </li>\n' +
							'                <li>\n' +
							'                    <div class="custom-radio" onclick="updateUserContactRule('+rules.id+', 0);">\n' +
							'                        <input type="radio" id="account_rule' + i + 'no" class="custom-control-input" name="contactRules' + i + '">\n' +
							'                        <label for="account_rule' + i + 'no" class="custom-control-label color-dark">Hayır</label>\n' +
							'                    </div>\n' +
							'                </li>\n' +
							'            </ul>\n' +
							'        </div>\n' +
							'    </div>\n' +
							'</div>';
			$('#contact_rules_list').append(ruleItem);
		});

		let data2 = await serviceGetUserContactRules(userId);
		$.each(data2.user_contact_rules, function (j, userRules) {
			if(userRules.value == 1){
				$('#account_rule'+j+'yes').prop("checked", true);
			}else{
				$('#account_rule'+j+'no').prop("checked", true);
			}
		});
	}
}

async function updateUserContactRule(ruleId, value){
	let userId = sessionStorage.getItem('userId');
	let formData = JSON.stringify({
		"value":value
	});
	await servicePostUserContactRules(userId, ruleId, formData);
}