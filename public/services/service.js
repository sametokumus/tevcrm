(function($) {
	"use strict";

	$(document).ready(function() {

		createSession();
		if(sessionStorage.getItem('userLogin') == null || sessionStorage.getItem('userLogin') == 'false'){
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

function getSession () {
    console.log(sessionStorage.getItem('userLogin'));
    console.log(sessionStorage.getItem('userRole'));
    console.log(sessionStorage.getItem('userId'));
    console.log(sessionStorage.getItem('userEmail'));
    console.log(sessionStorage.getItem('appToken'));
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

function checkNull(data){
	if (data == null){
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

async function getAdminsAddSelectId(selectId){
    let data = await serviceGetAdmins();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Yetkili Seçiniz</option>');
    $.each(data.admins, function(i, admin){
        let optionRow = '<option value="'+admin.id+'">'+admin.name+' '+admin.surname+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getCompaniesAddSelectId(selectId){
    let data = await serviceGetCompanies();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
    $.each(data.companies, function(i, company){
        let optionRow = '<option value="'+company.id+'">'+company.name+'</option>';
        $('#'+selectId).append(optionRow);
    });
}

async function getSuppliersAddSelectId(selectId){
    let data = await serviceGetSuppliers();
    $('#'+selectId+' option').remove();
    $('#'+selectId).append('<option value="0">Firma Seçiniz</option>');
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

async function getEmployeesAddSelectId(companyId, selectId){
    let data = await serviceGetEmployeesByCompanyId(companyId);
    $('#'+selectId+' option').remove();
    $.each(data.employees, function(i, employee){
        let optionRow = '<option value="'+employee.id+'">'+employee.name+'</option>';
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
async function servicePostAddOfferRequest(formData) {
    const data = await fetchDataPost('/admin/offerRequest/addOfferRequest', formData, 'application/json');
    if (data.status == "success") {
        showAlert(data.message);
        return true;
    } else {
        showAlert('İstek Başarısız.');
        return false;
    }
}
async function servicePostUpdateOfferRequest(id, formData) {
    const data = await fetchDataPost('/admin/offerRequest/updateOfferRequest/' + id, formData, 'application/json');
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



async function serviceGetContactById(id) {
    const data = await fetchDataGet('/admin/contact/getContactById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
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

async function serviceGetSaleById(id) {
    const data = await fetchDataGet('/admin/sale/getSaleById/' + id, 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
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

async function servicePostUpdateSaleStatus(formData) {
    const data = await fetchDataPost('/admin/sale/updateSaleStatus', formData, 'application/json');
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





async function serviceGetStatuses() {
    const data = await fetchDataGet('/admin/status/getStatuses', 'application/json');
    if (data.status == "success") {
        return data.object;
    } else {
        showAlert('İstek Başarısız.');
    }
}
