(function($) {
	"use strict";

	$(document).ready(function() {

		createSession();
		if(sessionStorage.getItem('userLogin') == null || sessionStorage.getItem('userLogin') == 'false'){
			$('#login-bar').append('<a href="giris-yap" class="d-lg-show login"><i class="w-icon-account"></i>Giriş Yap!</a>\n' +
				'                        <span class="delimiter d-lg-show">/</span>\n' +
				'                        <a href="kayit-ol" class="ml-0 d-lg-show login">Kayıt Ol!</a>');
		}else{
			if(sessionStorage.getItem('userType') == 1){
				$('#login-bar').append('<a href="hesabim" class="d-lg-show mr-4">Hesabım</a>\n' +
					'                        <a href="#" class="d-lg-show navbar-logout">Çıkış Yap</a>');
			}else if(sessionStorage.getItem('userType') == 2){
				$('#login-bar').append('<a href="bayi-hesabim" class="d-lg-show mr-4">Hesabım</a>\n' +
					'                        <a href="#" class="d-lg-show navbar-logout">Çıkış Yap</a>');
			}
		}

		$('.navbar-logout').click(function(event){
			event.preventDefault();
			fetchDataGet('/v1/logout', 'application/json').then(data=>{

				if(data.status == "success"){

					showAlert(data.message);
					removeSession();
					window.location.href = "/";

				}else{
					showAlert("İstek Başarısız.");
				}

			});

		});


	});


	$(window).load( function() {

		checkLogin();

	});



})(window.jQuery);



async function fetchDataGet(apiURL, contentType){

	var returnData;
	var token = sessionStorage.getItem('appToken');
	var data = await fetch('https://lenis-crm.wimco.com.tr/api'+apiURL, {
		method: 'get',
		headers:{
			'Content-Type': contentType,
			'Authorization': 'Bearer '+token,
		}
	});

	var returnData = await data.json();
	return returnData;

}

async function fetchDataPost (apiURL, body, contentType) {

	var returnData;
	var token = sessionStorage.getItem('appToken');
	var data = await fetch('https://lenis-crm.wimco.com.tr/api'+apiURL, {
		method: 'post',
		headers:{
			'Content-Type': contentType,
			'Authorization': 'Bearer '+token,
		},
		body:body
	});

	var returnData = await data.json();
	return returnData;

}

function xhrDataPost (apiURL, body, callBackFunction) {

	let xhr = new XMLHttpRequest();
	var token = sessionStorage.getItem('appToken');
	var returnData;
	xhr.timeout = 5000;
	xhr.addEventListener("readystatechange", function() {
		if(this.readyState === 4) {
			callBackFunction(this);
		}
	});
	xhr.open("POST", 'https://lenis-crm.wimco.com.tr/api'+apiURL);
	xhr.setRequestHeader("Authorization", 'Bearer '+token);
	xhr.send(body);

}



/* CUSTOM FUNCTIONS */

function showAlert(message){
	$(".alert-container .alert").text(message);
	var alert = $(".alert-container");
	alert.show("slide", {direction: "right"}, 200);
	window.setTimeout(function() {
		alert.hide("slide", {direction: "right"}, 200);
	}, 3000);
}

async function createSession () {
	var userLogin = await sessionStorage.getItem('userLogin');
	if(userLogin == null){
		sessionStorage.setItem('userLogin','false');
	}
}

function removeSession () {
	sessionStorage.setItem('userLogin','false');
	sessionStorage.removeItem('userRole');
	sessionStorage.removeItem('userId');
	sessionStorage.removeItem('userEmail');
	sessionStorage.removeItem('appToken');
}

function getQueryString(param){
	var value;
	var queryString = window.location.search;
	var urlParams = new URLSearchParams(queryString);
	value = urlParams.get(param);

	return value;
}

function getURLParam(name){
	const params = new URLSearchParams(window.location.search);
	return params.get(name);
}

function formatDateASC(date, slicer) {
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();

	return day + slicer + month + slicer + year;
}

function formatDateZeroASC(date, slicer, splitter) {
	var dates = date.split(splitter);
	date = dates[2]+'-'+dates[1]+'-'+dates[0];
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();

	return day + slicer + month + slicer + year;
}

function formatDateDESC(date, slicer) {
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();

	return year + slicer + month + slicer + day;
}
function formatDateDESC(date, slicer, splitter) {
	var dates = date.split(splitter);
	date = dates[2]+'-'+dates[1]+'-'+dates[0];
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();

	return year + slicer + month + slicer + day;
}

function formatTime(time) {
	time = new Date(time);

	var hour = ('0' + time.getHours()).slice(-2);
	var minute = ('0' + time.getMinutes()).slice(-2);

	return hour + ':' + minute;
}

function formatDateAndTime(date, slicer) {
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();
	var hour = date.getHours();
	var minute = ('0' + date.getMinutes()).slice(-2);

	return year + slicer + month + slicer + day + ' ' + hour + ':' + minute;
}

function formatDateAndTimeDESC(date, slicer) {
	date = new Date(date);

	var day = ('0' + date.getDate()).slice(-2);
	var month = ('0' + (date.getMonth() + 1)).slice(-2);
	var year = date.getFullYear();
	var hour = date.getHours();
	var minute = ('0' + date.getMinutes()).slice(-2);

	return day + slicer + month + slicer + year + ' ' + hour + ':' + minute;
}

function cryptText(text){
	var cText = text.charAt(0);
	for(let i=2; i<=text.length; i++){
		cText = cText+'*';
	}
	return cText;
}

function xmlToJson(xml) {

	// Create the return object
	var obj = {};

	if (xml.nodeType == 1) { // element
		// do attributes
		if (xml.attributes.length > 0) {
			obj["@attributes"] = {};
			for (var j = 0; j < xml.attributes.length; j++) {
				var attribute = xml.attributes.item(j);
				obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
			}
		}
	} else if (xml.nodeType == 3) { // text
		obj = xml.nodeValue;
	}

	// do children
	if (xml.hasChildNodes()) {
		for(var i = 0; i < xml.childNodes.length; i++) {
			var item = xml.childNodes.item(i);
			var nodeName = item.nodeName;
			if (typeof(obj[nodeName]) == "undefined") {
				obj[nodeName] = xmlToJson(item);
			} else {
				if (typeof(obj[nodeName].push) == "undefined") {
					var old = obj[nodeName];
					obj[nodeName] = [];
					obj[nodeName].push(old);
				}
				obj[nodeName].push(xmlToJson(item));
			}
		}
	}
	return obj;
};

function changePriceToDecimal(price){
	price = price.replace(".", "");
	price = price.replace(",", ".");
	return price;
}
function changeCommasToDecimal(price) {
	var parts = price.toString().split(".");
	const numberPart = parts[0];
	const decimalPart = parts[1];
	const thousands = /\B(?=(\d{3})+(?!\d))/g;
	return numberPart.replace(thousands, ".") + (decimalPart ? "," + decimalPart : "");
}
function changeDecimalToCommas(price) {
	var parts = price.toString().split(",");
	const numberPart = parts[0];
	const decimalPart = parts[1];
	const thousands = /\B(?=(\d{3})+(?!\d))/g;
	return numberPart.replace(thousands, ",") + (decimalPart ? "." + decimalPart : "");
}


/* SERVICE INIT DATA FUNCTIONS */

async function checkLogin () {
	var userLogin = await sessionStorage.getItem('userLogin');
	if(userLogin == null || userLogin == 'false'){
		window.location.href = "login";
	}else{
		//verify bcrypt
		var userRole = sessionStorage.getItem('userRole');
		var userId = sessionStorage.getItem('userId');
		var userEmail = sessionStorage.getItem('userEmail');
		var userLogin = sessionStorage.getItem('userLogin');
		var hash = userRole+userId+userEmail;


		try{
			checkpw(
				hash,
				userLogin,
				function(res){
					if(res == false){
						window.location.href = "login";
					}
				},
				function() {});
		}catch(err){
			//alert(err);
			return;
		}
	}
}
async function getCitiesAddSelectId(selectId){
	let data = await serviceGetCitiesByCountryId(1);
	$.each(data.cities, function(i, city){
		var optionRow = '<option value="'+city.id+'">'+city.name+'</option>';
		$('#'+selectId).append(optionRow);
	});
}
async function getDistrictsAddSelect(parentSelectId, selectId){
	let cityId = document.getElementById(parentSelectId).value;
	let data = await serviceGetDistrictsByCityId(cityId);
	$('#'+selectId+' option').remove();
	$.each(data.districts, function(i, district){
		var optionRow = '<option value="'+district.id+'">'+district.name+'</option>';
		$('#'+selectId).append(optionRow);
	});
}
async function getDistrictsAddSelectAutoUpdate(cityId, selectId){
	let data = await serviceGetDistrictsByCityId(cityId);
	$('#'+selectId+' option').remove();
	$.each(data.districts, function(i, district){
		var optionRow = '<option value="'+district.id+'">'+district.name+'</option>';
		$('#'+selectId).append(optionRow);
	});
}


/* SERVICE FUNCTIONS */

async function serviceGetCitiesByCountryId(countryId) {
	const data = await fetchDataGet('/v1/cities/getCitiesByCountryId/' + countryId, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetAdmins() {
	const data = await fetchDataGet('/admin/adminRole/getAdmins', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetAdminById(id) {
	const data = await fetchDataGet('/admin/adminRole/getAdminById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddAdmin(formData) {
	const data = await fetchDataPost('/admin/adminRole/addAdmin', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateAdmin(id, formData) {
	const data = await fetchDataPost('/admin/adminRole/updateAdmin/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteAdmin(id) {
	const data = await fetchDataGet('/admin/adminRole/deleteAdmin/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetAdminRoles() {
	const data = await fetchDataGet('/admin/adminRole/getAdminRoles', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetAdminRoleById(id) {
	const data = await fetchDataGet('/admin/adminRole/getAdminRoleById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddAdminRole(formData) {
	const data = await fetchDataPost('/admin/adminRole/addAdminRole', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateAdminRole(id, formData) {
	const data = await fetchDataPost('/admin/adminRole/updateAdminRole/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteAdminRole(id) {
	const data = await fetchDataGet('/admin/adminRole/deleteAdminRole/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetAdminRolePermissions(role_id) {
	const data = await fetchDataGet('/admin/adminRole/getAdminRolePermissions/' + role_id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetAddAdminRolePermission(role_id, permission_id) {
	const data = await fetchDataGet('/admin/adminRole/addAdminRolePermission/' + role_id + '/' + permission_id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteAdminRolePermission(role_id, permission_id) {
	const data = await fetchDataGet('/admin/adminRole/deleteAdminRolePermission/' + role_id + '/' + permission_id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetAdminPermissions() {
	const data = await fetchDataGet('/admin/adminPermission/getAdminPermissions', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCustomers() {
	const data = await fetchDataGet('/admin/customer/getCustomers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetCustomerById(id) {
	const data = await fetchDataGet('/admin/customer/getCustomerById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddCustomer(formData) {
	const data = await fetchDataPost('/admin/customer/addCustomer', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateCustomer(id, formData) {
	const data = await fetchDataPost('/admin/customer/updateCustomer/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteCustomer(id) {
	const data = await fetchDataGet('/admin/customer/deleteCustomer/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetCustomerAddresses(id) {
	const data = await fetchDataGet('/admin/customer/getCustomerAddresses/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetCustomerAddressById(id) {
	const data = await fetchDataGet('/admin/customer/getCustomerAddressById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddCustomerAddress(formData) {
	const data = await fetchDataPost('/admin/customer/addCustomerAddress', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateCustomerAddress(id, formData) {
	const data = await fetchDataPost('/admin/customer/updateCustomerAddress/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteCustomerAddress(id) {
	const data = await fetchDataGet('/admin/customer/deleteCustomerAddress/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetCustomerContacts(id) {
	const data = await fetchDataGet('/admin/customer/getCustomerContacts/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetCustomerContactById(id) {
	const data = await fetchDataGet('/admin/customer/getCustomerContactById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddCustomerContact(formData) {
	const data = await fetchDataPost('/admin/customer/addCustomerContact', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateCustomerContact(id, formData) {
	const data = await fetchDataPost('/admin/customer/updateCustomerContact/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteCustomerContact(id) {
	const data = await fetchDataGet('/admin/customer/deleteCustomerContact/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetSuppliers() {
	const data = await fetchDataGet('/admin/supplier/getSuppliers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetSupplierById(id) {
	const data = await fetchDataGet('/admin/supplier/getSupplierById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddSupplier(formData) {
	const data = await fetchDataPost('/admin/supplier/addSupplier', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateSupplier(id, formData) {
	const data = await fetchDataPost('/admin/supplier/updateSupplier/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteSupplier(id) {
	const data = await fetchDataGet('/admin/supplier/deleteSupplier/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetSupplierAddresses(id) {
    const data = await fetchDataGet('/admin/supplier/getSupplierAddresses/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetSupplierAddressById(id) {
    const data = await fetchDataGet('/admin/supplier/getSupplierAddressById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddSupplierAddress(formData) {
    const data = await fetchDataPost('/admin/supplier/addSupplierAddress', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateSupplierAddress(id, formData) {
    const data = await fetchDataPost('/admin/supplier/updateSupplierAddress/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteSupplierAddress(id) {
    const data = await fetchDataGet('/admin/supplier/deleteSupplierAddress/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetSupplierContacts(id) {
    const data = await fetchDataGet('/admin/supplier/getSupplierContacts/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetSupplierContactById(id) {
    const data = await fetchDataGet('/admin/supplier/getSupplierContactById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddSupplierContact(formData) {
    const data = await fetchDataPost('/admin/supplier/addSupplierContact', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateSupplierContact(id, formData) {
    const data = await fetchDataPost('/admin/supplier/updateSupplierContact/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteSupplierContact(id) {
    const data = await fetchDataGet('/admin/supplier/deleteSupplierContact/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
