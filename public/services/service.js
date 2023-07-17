(function($) {
	"use strict";

	$(document).ready(function() {

		createSession();
		if(localStorage.getItem('userLogin') == null || localStorage.getItem('userLogin') == 'false'){
            // window.location.href = "/login";
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


	$(window).on('load',async function() {
		// checkLogin();
        $('#header_user_name').text(localStorage.getItem('userName'));

        await createNavbar();
	});


    let lng = $('meta[name="current-locale"]').attr('content');
    Lang.setLocale(lng);

})(window.jQuery);

const api_url = 'https://lenis-crm.wimco.com.tr';

$(function() {
    // Listen for the language dropdown change event
    $('select#lang').change(function() {
        var lang = $(this).val();
        // Make an Ajax request to change the language
        Lang.setLocale(lang);
        console.log(lang)

        $.ajax({
            url: '/lang',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { lang: lang },
            success: function(data) {
                // Reload the page to apply the new locale
                location.reload();
            }
        });
    });
});


async function fetchDataGet(apiURL, contentType){

	var returnData;
	var token = localStorage.getItem('appToken');
	var data = await fetch(api_url + '/api'+apiURL, {
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
	var token = localStorage.getItem('appToken');
	var data = await fetch(api_url + '/api'+apiURL, {
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
	var token = localStorage.getItem('appToken');
	var returnData;
	xhr.timeout = 5000;
	xhr.addEventListener("readystatechange", function() {
		if(this.readyState === 4) {
			callBackFunction(this);
		}
	});
	xhr.open("POST", api_url + '/api'+apiURL);
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
	var userLogin = await localStorage.getItem('userLogin');
	if(userLogin == null){
		localStorage.setItem('userLogin','false');
	}
}

function removeSession () {
	localStorage.setItem('userLogin','false');
	localStorage.removeItem('userRole');
	localStorage.removeItem('userId');
	localStorage.removeItem('userEmail');
	localStorage.removeItem('appToken');
}

function getSession () {
    console.log(localStorage.getItem('userLogin'));
    console.log(localStorage.getItem('userRole'));
    console.log(localStorage.getItem('userId'));
    console.log(localStorage.getItem('userEmail'));
    console.log(localStorage.getItem('appToken'));
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

function getPathVariable(variable) {
    let path = location.pathname;
    let parts = path.substr(1).split('/'), value;

    while(parts.length) {
        if (parts.shift() === variable) value = parts.shift();
        else parts.shift();
    }

    return value;
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

function formatDateSplit(date, slicer, splitter) {
	let dates = date.split(splitter);
	return dates[0] + slicer + dates[1] + slicer + dates[2];
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

function formatDateAndTimeDESC2(date, slicer) {

    const parts = date.split(/[\-T:Z]/);
    const day = parts[2];
    const month = parts[1];
    const year = parts[0];
    const hour = parts[3];
    const minute = parts[4];

    return day + slicer + month + slicer + year + ' ' + hour + ':' + minute;
}

function checkNull(data){
	if (data == null || data == 'null'){
        return "";
    }else {
        return data;
    }
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

function changePriceToDecimal(str) {
    if (typeof str === 'string') {
        str = str.replace(/\./g, '');
        str = str.replace(',', '.');
    }
    return str;
}
function changeDecimalToPrice(num) {
    // Convert the number to a string with a fixed number of decimal places
    const str = num.toFixed(2);

    // Replace the decimal separator with a comma
    const strWithComma = str.replace('.', ',');

    // Replace the thousands separator with a dot
    const parts = strWithComma.split(',');
    const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    const decimalPart = parts[1];

    // Combine the integer and decimal parts with a comma separator
    return integerPart + ',' + decimalPart;
}
function changeCommasToDecimal(price) {
    if (price == null){
       return "";
    }else {
        var parts = price.toString().split(".");
        const numberPart = parts[0];
        const decimalPart = parts[1];
        const thousands = /\B(?=(\d{3})+(?!\d))/g;
        return numberPart.replace(thousands, ".") + (decimalPart ? "," + decimalPart : "");
    }
}
function changeDecimalToCommas(price) {
	var parts = price.toString().split(",");
	const numberPart = parts[0];
	const decimalPart = parts[1];
	const thousands = /\B(?=(\d{3})+(?!\d))/g;
	return numberPart.replace(thousands, ",") + (decimalPart ? "." + decimalPart : "");
}

// Select2
$('.select2').select2({
    minimumResultsForSearch: Infinity,
    width: '100%'
});

// Select2 by showing the search
$('.select2-show-search').select2({
    minimumResultsForSearch: '',
    width: '100%'
});

$('.select2').on('click', () => {
    let selectField = document.querySelectorAll('.select2-search__field')
    selectField.forEach((element, index) => {
        element.focus();
    })
});

/* SERVICE INIT DATA FUNCTIONS */

async function createNavbar(){
   let user_role = await localStorage.getItem('userRole');
    let data = await serviceGetAdminRolePermissions(user_role);
    $.each(data.role_permissions, function(i, role_permission){
        $('#nav-'+role_permission.permission_key).removeClass('d-none');
    });
    $('.menu-item.d-none').remove();

}

async function checkLogin () {
    // localStorage.setItem('userLogin', 'true');
	var userLogin = await localStorage.getItem('userLogin');
	if(userLogin == null || userLogin == 'false'){
		window.location.href = "login";
	}else{
		//verify bcrypt
		var userRole = localStorage.getItem('userRole');
		var userId = localStorage.getItem('userId');
		var userEmail = localStorage.getItem('userEmail');
		var userLogin = localStorage.getItem('userLogin');
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

async function getAdminsAddSelectId(selectId){
    let data = await serviceGetAdmins();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Yetkili Seçiniz</option>');
    $.each(data.admins, function(i, admin){
        let optionRow = '<option value="'+admin.id+'">'+admin.name+' '+admin.surname+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getOwnersAddSelectId(selectId){
    let data = await serviceGetContacts();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
    $.each(data.contacts, function(i, contact){
        let optionRow = '<option value="'+contact.id+'">'+contact.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCompaniesAddSelectId(selectId){
    let data = await serviceGetCompanies();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Müşteri Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getSuppliersAddSelectId(selectId){
    let data = await serviceGetSuppliers();
    $('#'+selectId+' option').remove();
    // $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCustomersAddSelectId(selectId){
    let data = await serviceGetCustomers();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCustomersAndPotentialsAddSelectId(selectId){
    let data = await serviceGetCustomers();
    let data2 = await serviceGetPotentialCustomers();
    $('#'+selectId+' option').remove();
    // $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
    $.each(data2.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCustomersAndPotentialsAddSelectIdWithZero(selectId){
    let data = await serviceGetCustomers();
    let data2 = await serviceGetPotentialCustomers();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Müşteri Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
    $.each(data2.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getEmployeesAddSelectId(companyId, selectId){
    let data = await serviceGetEmployeesByCompanyId(companyId);
    $('#'+selectId+' option').remove();
    $.each(data.employees, function(i, employee){
        let optionRow = '<option value="'+employee.id+'">'+employee.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getEmployeesAddSelectIdWithZero(companyId, selectId){
    let data = await serviceGetEmployeesByCompanyId(companyId);
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Yetkili Seçiniz</option>');
    $.each(data.employees, function(i, employee){
        let optionRow = '<option value="'+employee.id+'">'+employee.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getMeasurementsAddSelectId(selectId){
    let data = await serviceGetMeasurements();
    $('#'+selectId+' option').remove();
    $.each(data.measurements, function(i, measurement){
        let optionRow = '<option value="'+measurement.name_tr+'">'+measurement.name_tr+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getParentCategoriesAddSelectId(selectId){
    let data = await serviceGetCategories();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getActivityTypesAddSelectId(selectId){
    let data = await serviceGetActivityTypes();
    $('#'+selectId+' option').remove();
    $.each(data.activity_types, function(i, activity_type){
        let optionRow = '<option value="'+activity_type.id+'">'+activity_type.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getPaymentTypesAddSelectId(selectId){
    let data = await serviceGetPaymentTypes();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Ödeme Türü Seçiniz</option>');
    $.each(data.types, function(i, type){
        let optionRow = '<option value="'+type.id+'">'+type.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}
async function getPaymentMethodsAddSelectId(selectId){
    let data = await serviceGetPaymentMethods();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Ödeme Yöntemi Seçiniz</option>');
    $.each(data.methods, function(i, method){
        let optionRow = '<option value="'+method.id+'">'+method.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getPaymentTermsAddSelectId(selectId){
    let data = await serviceGetPaymentTerms();
    $('#'+selectId+' option').remove();
    $.each(data.payment_terms, function(i, payment_term){
        let optionRow = '<option value="'+payment_term.name+'">'+payment_term.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getDeliveryTermsAddSelectId(selectId){
    let data = await serviceGetDeliveryTerms();
    $('#'+selectId+' option').remove();
    $.each(data.delivery_terms, function(i, delivery_term){
        let optionRow = '<option value="'+delivery_term.name+'">'+delivery_term.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCountriesAddSelectId(selectId){
    let data = await serviceGetCountries();
    $('#'+selectId+' option').remove();
    $.each(data.countries, function(i, country){
        let optionRow = '<option value="'+country.id+'">'+country.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}
async function getStatesAddSelectId(selectId, countryId){
    let data = await serviceGetStatesByCountryId(countryId);
    $('#'+selectId+' option').remove();
    $.each(data.states, function(i, state){
        let optionRow = '<option value="'+state.id+'">'+state.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}async function getStatesAddSelectIdWithParent(selectId, parentSelectId){
    let countryId = document.getElementById(parentSelectId).value;
    let data = await serviceGetStatesByCountryId(countryId);
    $('#'+selectId+' option').remove();
    $.each(data.states, function(i, state){
        let optionRow = '<option value="'+state.id+'">'+state.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}
async function getCitiesAddSelectId(selectId, stateId){
	let data = await serviceGetCitiesByStateId(stateId);
    $('#'+selectId+' option').remove();
	$.each(data.cities, function(i, city){
		var optionRow = '<option value="'+city.id+'">'+city.name+'</option>';
		$('#'+selectId).append(optionRow);
	});
}
async function getCitiesAddSelectIdWithParent(selectId, parentSelectId){
    let stateId = document.getElementById(parentSelectId).value;
	let data = await serviceGetCitiesByStateId(stateId);
    $('#'+selectId+' option').remove();
	$.each(data.cities, function(i, city){
		var optionRow = '<option value="'+city.id+'">'+city.name+'</option>';
		$('#'+selectId).append(optionRow);
	});
}
async function getBrandsAddSelectId(selectId){
    let data = await serviceGetBrands();
    $('#'+selectId+' option').remove();
    var optionRow = '<option value="">Marka Seçiniz</option>';
    $('#'+selectId).append(optionRow);
    $.each(data.brands, function(i, brand){
        var optionRow = '<option value="'+brand.id+'">'+brand.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}
async function getCategoriesAddSelectId(selectId){
    let data = await serviceGetCategories();
    $('#'+selectId+' option').remove();
    var optionRow = '<option value="">Ürün Grubu Seçiniz</option>';
    $('#'+selectId).append(optionRow);
    $.each(data.categories, function(i, category){
        var optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#'+selectId).append(optionRow);
        $.each(category.sub_categories, function(i, category2){
            var optionRow = '<option value="'+category2.id+'">'+category2.name+'</option>';
            $('#'+selectId).append(optionRow);

        });
    });
}

async function fastChangeStatus(status_id, sale_id){
    let user_id = localStorage.getItem('userId');
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "status_id": status_id,
        "user_id": user_id
    });
    let data = await servicePostUpdateSaleStatus(formData);
    if(data.status == "success"){
        window.location.href = '/sales';
    }
}



/* SERVICE FUNCTIONS */

async function serviceGetCountries() {
	const data = await fetchDataGet('/admin/countries/getCountries', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetStatesByCountryId(countryId) {
	const data = await fetchDataGet('/admin/states/getStatesByCountryId/' + countryId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCitiesByStateId(stateId) {
	const data = await fetchDataGet('/admin/cities/getCitiesByStateId/' + stateId, 'application/json');
	if (data.status == "success") {
		return data.object;
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
async function servicePostUpdateUser(id, formData) {
	const data = await fetchDataPost('/admin/adminRole/updateUser/' + id, formData, 'application/json');
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
async function serviceGetCheckAdminRolePermission(admin_id, permission_id) {
	const data = await fetchDataGet('/admin/adminRole/getCheckAdminRolePermission/' + admin_id + '/' + permission_id, 'application/json');
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

async function serviceGetAdminRoleStatuses(role_id) {
    const data = await fetchDataGet('/admin/adminRole/getAdminRoleStatuses/' + role_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetAddAdminRoleStatus(role_id, status_id) {
    const data = await fetchDataGet('/admin/adminRole/addAdminRoleStatus/' + role_id + '/' + status_id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteAdminRoleStatus(role_id, status_id) {
    const data = await fetchDataGet('/admin/adminRole/deleteAdminRoleStatus/' + role_id + '/' + status_id, 'application/json');
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

async function serviceGetCompanies() {
	const data = await fetchDataGet('/admin/company/getCompanies', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetPotentialCustomers() {
    const data = await fetchDataGet('/admin/company/getPotentialCustomers', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetCustomers() {
	const data = await fetchDataGet('/admin/company/getCustomers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetSuppliers() {
    const data = await fetchDataGet('/admin/company/getSuppliers', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetCompanyById(id) {
	const data = await fetchDataGet('/admin/company/getCompanyById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddCompany(formData) {
    await xhrDataPost('/admin/company/addCompany', formData, addCompanyCallback);
}

async function servicePostUpdateCompany(id, formData) {
    await xhrDataPost('/admin/company/updateCompany/' + id, formData, updateCompanyCallback);
}
async function serviceGetDeleteCompany(id) {
	const data = await fetchDataGet('/admin/company/deleteCompany/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}


async function serviceGetEmployeesByCompanyId(id) {
    const data = await fetchDataGet('/admin/employee/getEmployeesByCompanyId/'+ id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetEmployeeById(id) {
    const data = await fetchDataGet('/admin/employee/getEmployeeById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddEmployee(formData) {
    await xhrDataPost('/admin/employee/addEmployee', formData, addEmployeeCallback);
}

async function servicePostUpdateEmployee(id, formData) {
    await xhrDataPost('/admin/employee/updateEmployee/' + id, formData, updateEmployeeCallback);
}
async function serviceGetDeleteEmployee(id) {
    const data = await fetchDataGet('/admin/employee/deleteEmployee/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetNotesByCompanyId(id) {
    const data = await fetchDataGet('/admin/note/getNotesByCompanyId/'+ id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetNoteById(id) {
    const data = await fetchDataGet('/admin/note/getNoteById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddNote(formData) {
    await xhrDataPost('/admin/note/addNote', formData, addNoteCallback);
}
async function servicePostUpdateNote(id, formData) {
    await xhrDataPost('/admin/note/updateNote/' + id, formData, updateNoteCallback);
}
async function serviceGetDeleteNote(id) {
    const data = await fetchDataGet('/admin/note/deleteNote/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetActivityTypes() {
    const data = await fetchDataGet('/admin/activity/getActivityTypes', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetActivityTypeById(id) {
    const data = await fetchDataGet('/admin/activity/getActivityTypeById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddActivityType(formData) {
    const data = await fetchDataPost('/admin/activity/addActivityType', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateActivityType(id, formData) {
    const data = await fetchDataPost('/admin/activity/updateActivityType/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteActivityType(id) {
    const data = await fetchDataGet('/admin/activity/deleteActivityType/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetActivitiesByCompanyId(id) {
    const data = await fetchDataGet('/admin/activity/getActivitiesByCompanyId/'+ id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetActivityById(id) {
    const data = await fetchDataGet('/admin/activity/getActivityById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddActivity(formData) {
    const data = await fetchDataPost('/admin/activity/addActivity', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateActivity(formData, id) {
    const data = await fetchDataPost('/admin/activity/updateActivity/'+ id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteActivity(id) {
    const data = await fetchDataGet('/admin/activity/deleteActivity/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteActivityTask(id) {
    const data = await fetchDataGet('/admin/activity/deleteActivityTask/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetCompleteActivityTask(id) {
    const data = await fetchDataGet('/admin/activity/completeActivityTask/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetUnCompleteActivityTask(id) {
    const data = await fetchDataGet('/admin/activity/unCompleteActivityTask/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostAddActivityTask(formData) {
    const data = await fetchDataPost('/admin/activity/addActivityTask', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetOfferRequests() {
    const data = await fetchDataGet('/admin/offerRequest/getOfferRequests', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetOfferRequestById(id) {
    const data = await fetchDataGet('/admin/offerRequest/getOfferRequestById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetOfferRequestProductsById(id) {
    const data = await fetchDataGet('/admin/offerRequest/getOfferRequestProductsById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostCreateOfferRequest(formData) {
    const data = await fetchDataPost('/admin/offerRequest/createOfferRequest', formData, 'application/json');
    if (data.status == "success") {
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostAddOfferRequest(formData) {
    const data = await fetchDataPost('/admin/offerRequest/addOfferRequest', formData, 'application/json');
    if (data.status == "success") {
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostOfferRequestProducts(formData, id) {
    const data = await fetchDataPost('/admin/offerRequest/offerRequestProducts/' + id, formData, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateOfferRequest(id, formData) {
    const data = await fetchDataPost('/admin/offerRequest/updateOfferRequest/' + id, formData, 'application/json');
    console.log(data)
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostAddProductToOfferRequest(id, formData) {
    const data = await fetchDataPost('/admin/offerRequest/addProductToOfferRequest/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteProductToOfferRequest(id) {
    const data = await fetchDataGet('/admin/offerRequest/deleteProductToOfferRequest/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetOfferRequestsByCompanyId(id) {
    const data = await fetchDataGet('/admin/offerRequest/getOfferRequestsByCompanyId/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}




async function serviceGetNewOffersByRequestId(id) {
    const data = await fetchDataGet('/admin/offer/getNewOffersByRequestId/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetOffersByRequestId(id) {
    const data = await fetchDataGet('/admin/offer/getOffersByRequestId/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetOfferById(id) {
    const data = await fetchDataGet('/admin/offer/getOfferById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddOffer(formData) {
    const data = await fetchDataPost('/admin/offer/addOffer', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetOfferProductById(offer_id, product_id) {
    const data = await fetchDataGet('/admin/offer/getOfferProductById/' + offer_id + '/' + product_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddOfferProduct(formData, offer_id) {
    const data = await fetchDataPost('/admin/offer/addOfferProduct/' + offer_id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateOfferProduct(formData, offer_id, product_id) {
    const data = await fetchDataPost('/admin/offer/updateOfferProduct/' + offer_id + '/' + product_id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteOffer(id) {
    const data = await fetchDataGet('/admin/offer/deleteOffer/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetContacts() {
    const data = await fetchDataGet('/admin/contact/getContacts', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetContactById(id) {
    const data = await fetchDataGet('/admin/contact/getContactById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostUpdateContact(formData) {
    await xhrDataPost('/admin/contact/updateContact', formData, updateContactCallback);
}
async function serviceGetDeleteContact(id) {
    const data = await fetchDataGet('/admin/contact/deleteContact/' + id, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetSales() {
    const data = await fetchDataGet('/admin/sale/getSales', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetActiveSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/sale/getActiveSales/'+ userId, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetApprovedSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/sale/getApprovedSales/'+ userId, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetCancelledSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/sale/getCancelledSales/'+ userId, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostFilterSales(formData) {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataPost('/admin/sale/getFilteredSales/'+ userId, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetSaleById(id) {
    const data = await fetchDataGet('/admin/sale/getSaleById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetApproveOfferBySaleId(id, revize) {
    let user_id = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/sale/getApproveOfferBySaleId/' + id + '/' + user_id + '/' + revize, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        return false;
    }
}

async function serviceGetRejectOfferBySaleId(id, revize) {
    let user_id = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/sale/getRejectOfferBySaleId/' + id + '/' + user_id + '/' + revize, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        return false;
    }
}

async function serviceGetSaleOfferById(id) {
    const data = await fetchDataGet('/admin/sale/getSaleOfferById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetSaleOffersByOfferId(id) {
    const data = await fetchDataGet('/admin/sale/getSaleOffersByOfferId/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddSale(formData) {
    const data = await fetchDataPost('/admin/sale/addSale', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteSale(id) {
    const data = await fetchDataGet('/admin/sale/deleteSale/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostAddSaleOfferPrice(formData) {
    const data = await fetchDataPost('/admin/sale/addSaleOfferPrice', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateSaleOfferPrice(formData) {
    const data = await fetchDataPost('/admin/sale/updateSaleOfferPrice', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetRfqDetailById(id) {
    const data = await fetchDataGet('/admin/sale/getRfqDetailById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostUpdateRfqDetail(formData) {
    const data = await fetchDataPost('/admin/sale/updateRfqDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateSaleStatus(formData) {
    const data = await fetchDataPost('/admin/sale/updateSaleStatus', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostAddCancelSaleNote(formData) {
    const data = await fetchDataPost('/admin/sale/addCancelSaleNote', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetQuoteBySaleId(id) {
    const data = await fetchDataGet('/admin/sale/getQuoteBySaleId/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostUpdateQuote(formData) {
    const data = await fetchDataPost('/admin/sale/updateQuote', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetSaleNotes(id) {
    const data = await fetchDataGet('/admin/sale/getSaleNotes/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddSaleNote(formData) {
    const data = await fetchDataPost('/admin/sale/addSaleNote', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetLiveCurrencyLog() {
    const data = await fetchDataGet('/admin/sale/getLiveCurrencyLog', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return true;
    } else {
        return false;
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetLastCurrencyLog() {
    const data = await fetchDataGet('/admin/sale/getLastCurrencyLog', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetCheckSaleCurrencyLog(request_id) {
    const data = await fetchDataGet('/admin/sale/getCheckSaleCurrencyLog/'+ request_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddSaleCurrencyLog(formData, request_id) {
    const data = await fetchDataPost('/admin/sale/addSaleCurrencyLog/'+ request_id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetSaleByRequestId(request_id) {
    const data = await fetchDataGet('/admin/sale/getSaleByRequestId/'+ request_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetCurrencyLogs() {
    const data = await fetchDataGet('/admin/sale/getCurrencyLogs', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddCurrencyLog(formData) {
    const data = await fetchDataPost('/admin/sale/addCurrencyLog', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteCurrencyLog(log_id) {
    const data = await fetchDataGet('/admin/sale/deleteCurrencyLog/'+ log_id, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        return false;
        showAlert('İstek Başarısız.');
    }
}




async function serviceGetStatuses() {
    const data = await fetchDataGet('/admin/status/getStatuses', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetChangeableStatuses() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/status/getChangeableStatuses', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetMeasurements() {
    const data = await fetchDataGet('/admin/measurement/getMeasurements', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}



async function serviceGetBankInfos() {
    const data = await fetchDataGet('/admin/owner/getBankInfos', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetBankInfoById(id) {
    const data = await fetchDataGet('/admin/owner/getBankInfoById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddBankInfo(formData) {
    const data = await fetchDataPost('/admin/owner/addBankInfo', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateBankInfo(formData) {
    const data = await fetchDataPost('/admin/owner/updateBankInfo', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetDeleteBankInfo(id) {
    const data = await fetchDataGet('/admin/owner/deleteBankInfo/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetPurchasingOrderDetailById(id) {
    const data = await fetchDataGet('/admin/sale/getPurchasingOrderDetailById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddPurchasingOrderDetail(formData) {
    const data = await fetchDataPost('/admin/sale/addPurchasingOrderDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdatePurchasingOrderDetail(formData) {
    const data = await fetchDataPost('/admin/sale/updatePurchasingOrderDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetOrderConfirmationDetailById(id) {
    const data = await fetchDataGet('/admin/sale/getOrderConfirmationDetailById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddOrderConfirmationDetail(formData) {
    const data = await fetchDataPost('/admin/sale/addOrderConfirmationDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateOrderConfirmationDetail(formData) {
    const data = await fetchDataPost('/admin/sale/updateOrderConfirmationDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetProformaInvoiceDetailById(id) {
    const data = await fetchDataGet('/admin/sale/getProformaInvoiceDetailById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostUpdateProformaInvoiceDetail(formData) {
    const data = await fetchDataPost('/admin/sale/updateProformaInvoiceDetail', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateShippingPrice(formData) {
    const data = await fetchDataPost('/admin/sale/updateShippingPrice', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetSaleHistoryActions() {
    const data = await fetchDataGet('/admin/newsFeed/getSaleHistoryActions', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetTopRequestedProducts() {
    const data = await fetchDataGet('/admin/newsFeed/getTopRequestedProducts', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetSaleStats() {
    const data = await fetchDataGet('/admin/newsFeed/getSaleStats', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetLanguage(lang) {
    const data = await fetchDataGet('/admin/language/changeLanguage/' + lang, 'application/json');
    if (data.status == "success") {
        return data;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetBrands() {
    const data = await fetchDataGet('/admin/brand/getBrands', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetBrandById(brandId) {
    const data = await fetchDataGet('/admin/brand/getBrandById/' + brandId, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddBrand(formData) {
    await xhrDataPost('/admin/brand/addBrand', formData, addBrandCallback);
}

async function servicePostUpdateBrand(brandId, formData) {
    await xhrDataPost('/admin/brand/updateBrand/' + brandId, formData, updateBrandCallback);
}

async function serviceGetDeleteBrand(brandId) {
    const data = await fetchDataGet('/admin/brand/deleteBrand/' + brandId, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetCategories() {
    const data = await fetchDataGet('/admin/category/getCategory', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetCategoryById(category_id) {
    const data = await fetchDataGet('/admin/category/getCategoryById/' + category_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetCategoriesByParentId(parent_id) {
    const data = await fetchDataGet('/admin/category/getCategoryByParentId/' + parent_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddCategory(formData) {
    const data = await fetchDataPost('/admin/category/addCategory', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateCategory(formData, category_id) {
    const data = await fetchDataPost('/admin/category/updateCategory/' + category_id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetDeleteCategory(categoryId) {
    const data = await fetchDataGet('/admin/category/deleteCategory/' + categoryId, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetProducts() {
    const data = await fetchDataGet('/admin/product/getProducts', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetProductById(id) {
    const data = await fetchDataGet('/admin/product/getProductById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddImportedProducts(formData) {
    const data = await fetchDataPost('/admin/product/addImportedProducts', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostAddProduct(formData) {
    const data = await fetchDataPost('/admin/product/addProduct', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateProduct(formData, id) {
    const data = await fetchDataPost('/admin/product/updateProduct/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetDeleteProduct(id) {
    const data = await fetchDataGet('/admin/product/deleteProduct/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateProductName(formData, id) {
    const data = await fetchDataPost('/admin/product/updateProductName/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetSaleDetailInfo(sale_id) {
    const data = await fetchDataGet('/admin/sale/getSaleDetailInfo/' + sale_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetSaleStatusHistory(sale_id) {
    const data = await fetchDataGet('/admin/sale/getSaleStatusHistory/' + sale_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetSaleSuppliers(sale_id) {
    const data = await fetchDataGet('/admin/sale/getSaleSuppliers/' + sale_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}



async function servicePostAddPackingList(formData) {
    const data = await fetchDataPost('/admin/sale/addPackingList', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function serviceGetPackingableProductsBySaleId(sale_id) {
    const data = await fetchDataGet('/admin/sale/getPackingableProductsBySaleId/' + sale_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetPackingListsBySaleId(sale_id) {
    const data = await fetchDataGet('/admin/sale/getPackingListsBySaleId/' + sale_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetPackingListProductsById(packing_list_id) {
    const data = await fetchDataGet('/admin/sale/getPackingListProductsById/' + packing_list_id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetDeletePackingList(id) {
    const data = await fetchDataGet('/admin/sale/deletePackingList/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdatePackingNote(formData) {
    const data = await fetchDataPost('/admin/sale/updatePackingListNote', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}








async function serviceGetPaymentTypes() {
    const data = await fetchDataGet('/admin/accounting/getPaymentTypes', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetPaymentMethods() {
    const data = await fetchDataGet('/admin/accounting/getPaymentMethods', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetPaymentTerms() {
    const data = await fetchDataGet('/admin/setting/getPaymentTerms', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetPaymentTermById(id) {
    const data = await fetchDataGet('/admin/setting/getPaymentTermById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddPaymentTerm(formData) {
    const data = await fetchDataPost('/admin/setting/addPaymentTerm', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdatePaymentTerm(id, formData) {
    const data = await fetchDataPost('/admin/setting/updatePaymentTerm/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeletePaymentTerm(id) {
    const data = await fetchDataGet('/admin/setting/deletePaymentTerm/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}


async function serviceGetDeliveryTerms() {
    const data = await fetchDataGet('/admin/setting/getDeliveryTerms', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetDeliveryTermById(id) {
    const data = await fetchDataGet('/admin/setting/getDeliveryTermById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function servicePostAddDeliveryTerm(formData) {
    const data = await fetchDataPost('/admin/setting/addDeliveryTerm', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateDeliveryTerm(id, formData) {
    const data = await fetchDataPost('/admin/setting/updateDeliveryTerm/' + id, formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function serviceGetDeleteDeliveryTerm(id) {
    const data = await fetchDataGet('/admin/setting/deleteDeliveryTerm/' + id, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



async function serviceGetPendingAccountingSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/accounting/getPendingAccountingSales/'+ userId, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetOngoingAccountingSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/accounting/getOngoingAccountingSales/'+ userId, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetCompletedAccountingSales() {
    let userId = localStorage.getItem('userId');
    const data = await fetchDataGet('/admin/accounting/getCompletedAccountingSales/'+ userId, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetAccountingPayments(sale_id) {
    const data = await fetchDataGet('/admin/accounting/getAccountingPayments/'+ sale_id, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
async function serviceGetAccountingPaymentById(payment_id) {
    const data = await fetchDataGet('/admin/accounting/getAccountingPaymentById/'+ payment_id, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function servicePostAddAccountingPayment(formData) {
    const data = await fetchDataPost('/admin/accounting/addAccountingPayment', formData, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateAccountingPayment(formData) {
    const data = await fetchDataPost('/admin/accounting/updateAccountingPayment', formData, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}

async function servicePostUpdateAccountingPaymentStatus(formData) {
    const data = await fetchDataPost('/admin/accounting/updateAccountingPaymentStatus', formData, 'application/json');
    if (data.status == "success") {
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}



/* DASHBOARD SERVICES */

async function serviceGetTotalSales() {
    const data = await fetchDataGet('/admin/dashboard/getTotalSales', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetApprovedMonthlySales() {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyApprovedSalesLastTwelveMonths', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetCompletedMonthlySales() {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyCompletedSalesLastTwelveMonths', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetPotentialSales() {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyPotentialSalesLastTwelveMonths', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetCancelledPotentialSales() {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyCancelledSalesLastTwelveMonths', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}


async function serviceGetMonthlyApprovedSalesLastTwelveMonthsByAdmins() {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyApprovedSalesLastTwelveMonthsByAdmins', 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}

async function serviceGetMonthlyApprovedSalesLastTwelveMonthsByAdminId(id) {
    const data = await fetchDataGet('/admin/dashboard/getMonthlyApprovedSalesLastTwelveMonthsByAdminId/'+ id, 'application/json');
    console.log(data)
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
