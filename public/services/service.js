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
async function serviceGetAccountVerify(token) {
	const data = await fetchDataGet('/v1/auth/verify/' + token, 'application/json');
	return data;
}
async function serviceGetCitiesByCountryId(countryId) {
	const data = await fetchDataGet('/v1/cities/getCitiesByCountryId/' + countryId, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetDistrictsByCityId(cityId) {
	const data = await fetchDataGet('/v1/cities/getDistrictsByCityId/' + cityId, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUsers() {
	const data = await fetchDataGet('/admin/user/getUsers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUserProfile(userId) {
	const data = await fetchDataGet('/v1/user/getUser/' + userId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUsersByTypeId(typeId) {
	const data = await fetchDataGet('/admin/user/getUsersByTypeId/' + typeId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUserAddresses(userId) {
	const data = await fetchDataGet('/v1/addresses/getAddressesByUserId/' + userId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUserAddressById(userId, addressId) {
	const data = await fetchDataGet('/v1/addresses/getAddressByUserIdAddressId/' + userId + '/' + addressId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateUserAddress(userId, addressId, formData) {
	const data = await fetchDataPost('/v1/addresses/updateUserAddresses/' + userId + '/' + addressId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUserOrders(userId) {
	const data = await fetchDataGet('/v1/order/getOrdersByUserId/' + userId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetOrderById(orderId) {
	const data = await fetchDataGet('/v1/order/getOrderById/' + orderId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetContactRules() {
	const data = await fetchDataGet('/v1/contactRules/getContactRules', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetUserContactRules(userId) {
	const data = await fetchDataGet('/v1/contactRules/getContactRulesByUserId/' + userId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUserContactRules(userId, ruleId, formData) {
	const data = await fetchDataPost('/v1/contactRules/updateContactRulesByUserId/' + userId + '/' + ruleId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCategories() {
	const data = await fetchDataGet('/v1/category/getCategory', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCategoryById(category_id) {
	const data = await fetchDataGet('/v1/category/getCategoryById/' + category_id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetParentCategories() {
	const data = await fetchDataGet('/v1/category/getParentCategory', 'application/json');
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

async function servicePostUpdateHomeCategoryBanner(id, formData) {
	await xhrDataPost('/admin/category/updateHomeCategoryBanner/' + id, formData, updateHomeCategoryBannerCallback);
}

async function serviceGetTypes() {
	const data = await fetchDataGet('/v1/productType/getProductTypes', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetTypeById(typeId) {
	const data = await fetchDataGet('/v1/productType/getProductTypeById/' + typeId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddType(formData) {
	const data = await fetchDataPost('/admin/productType/addProductType', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateType(formData, typeId) {
	const data = await fetchDataPost('/admin/productType/updateProductType/' + typeId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteType(typeId) {
	const data = await fetchDataGet('/admin/productType/deleteProductType/' + typeId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetVariationGroupTypes() {
	const data = await fetchDataGet('/v1/productVariationGroupType/getProductVariationGroupTypes', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetVariationGroupTypeById(typeId) {
	const data = await fetchDataGet('/v1/productVariationGroupType/getProductVariationGroupTypeById/' + typeId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddVariationGroupType(formData) {
	const data = await fetchDataPost('/admin/productVariationGroupType/addProductVariationGroupType', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateVariationGroupType(formData, typeId) {
	const data = await fetchDataPost('/admin/productVariationGroupType/updateProductVariationGroupType/' + typeId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteVariationGroupType(typeId) {
	const data = await fetchDataGet('/admin/productVariationGroupType/deleteProductVariationGroupType/' + typeId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetTabs() {
	const data = await fetchDataGet('/v1/tab/getTabs', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetTabById(tabId) {
	const data = await fetchDataGet('/v1/tab/getTabById/' + tabId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddTab(formData) {
	const data = await fetchDataPost('/admin/tab/addTab', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateTab(formData, typeId) {
	const data = await fetchDataPost('/admin/tab/updateTab/' + typeId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteTab(typeId) {
	const data = await fetchDataGet('/admin/tab/deleteTab/' + typeId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetTags() {
	const data = await fetchDataGet('/v1/tag/getTags', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetTagById(typeId) {
	const data = await fetchDataGet('/v1/tag/getTagById/' + typeId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddTag(formData) {
	const data = await fetchDataPost('/admin/tag/addTag', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateTag(formData, typeId) {
	const data = await fetchDataPost('/admin/tag/updateTag/' + typeId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteTag(typeId) {
	const data = await fetchDataGet('/admin/tag/deleteTag/' + typeId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetBrands() {
	const data = await fetchDataGet('/v1/brand/getBrands', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetDeletedBrands() {
	const data = await fetchDataGet('/admin/brand/getBrandPassive', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetBrandById(brandId) {
	const data = await fetchDataGet('/v1/brand/getBrandById/' + brandId, 'application/json');
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

async function serviceGetActivateBrand(brandId) {
	const data = await fetchDataGet('/admin/brand/activeBrand/' + brandId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProducts() {
	const data = await fetchDataGet('/v1/product/getProduct', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetFilterProducts(formData) {
	const data = await fetchDataPost('/v1/product/getFilteredProduct', formData,'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductsByFilter(formData) {
	const data = await fetchDataPost('/v1/product/getProductsByFilter', formData,'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductsBySlug(slug) {
	const data = await fetchDataGet('/v1/product/getProductsBySlug/'+ slug, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductsByType(slug) {
	const data = await fetchDataGet('/v1/product/getProductsByType/'+ slug, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductsByBrand(slug) {
	const data = await fetchDataGet('/v1/product/getProductsByBrand/'+ slug, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetAllCampaignProducts() {
	const data = await fetchDataGet('/v1/product/getAllCampaignProducts', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCampaignProductsByLimit(limit) {
	const data = await fetchDataGet('/v1/product/getCampaignProductsByLimit/'+ limit, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetBrandsWithProductsAndLimit(limit) {
	const data = await fetchDataGet('/v1/product/getBrandsWithProductsAndLimit/'+ limit, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetFeaturedProducts() {
	const data = await fetchDataGet('/v1/product/getFeaturedProducts', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetNewProducts() {
	const data = await fetchDataGet('/v1/product/getNewProducts', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetSimilarProducts($product_id) {
	const data = await fetchDataGet('/v1/product/getSimilarProducts'+ $product_id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductsWithParentCategory() {
	const data = await fetchDataGet('/v1/product/getProductsWithParentCategory', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductById(productId) {
	const data = await fetchDataGet('/v1/product/getProductById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetAllProductById(productId) {
	const data = await fetchDataGet('/v1/product/getAllProductById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetAllProductWithVariationById(productId, variation_id) {
	const data = await fetchDataGet('/v1/product/getAllProductWithVariationById/' + productId + '/' + variation_id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProduct(formData) {
	const data = await fetchDataPost('/admin/product/addProduct', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateProduct(productId, formData) {
	const data = await fetchDataPost('/admin/product/updateProduct/' + productId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteProduct(productId) {
	const data = await fetchDataGet('/admin/product/deleteProduct/' + productId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductCategoryById(productId) {
	const data = await fetchDataGet('/v1/product/getProductCategoryById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProductCategory(formData) {
	const data = await fetchDataPost('/admin/product/addProductCategory', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostDeleteProductCategory(formData) {
	const data = await fetchDataPost('/admin/product/deleteProductCategory', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}async function servicePostAddCampaignProduct(formData) {
	const data = await fetchDataPost('/admin/product/addCampaignProduct', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteCampaignProduct(productId) {
	const data = await fetchDataGet('/admin/product/deleteCampaignProduct/' + productId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductTagById(productId) {
	const data = await fetchDataGet('/v1/product/getProductTagById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProductTag(formData) {
	const data = await fetchDataPost('/admin/product/addProductTag', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostDeleteProductTag(formData) {
	const data = await fetchDataPost('/admin/product/deleteProductTag', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductDocuments(productId) {
	const data = await fetchDataGet('/v1/product/getProductDocumentById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProductDocument(formData) {
	await xhrDataPost('/admin/product/addProductDocument', formData, addProductDocumentCallback);
}

async function serviceGetDeleteProductDocument(documentId) {
	const data = await fetchDataGet('/admin/product/deleteProductDocument/' + documentId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductTabsById(productId) {
	const data = await fetchDataGet('/v1/product/getProductTabsById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductTabById(tabId) {
	const data = await fetchDataGet('/v1/product/getProductTabById/' + tabId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProductTab(formData) {
	const data = await fetchDataPost('/admin/product/addProductTab', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateProductTab(formData) {
	const data = await fetchDataPost('/admin/product/updateProductTab', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteProductTabById(tabId) {
	const data = await fetchDataGet('/admin/product/deleteProductTab/' + tabId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostAddProductVariationGroup(formData) {
	const data = await fetchDataPost('/admin/product/addProductVariationGroup', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductVariationGroupById(productId) {
	const data = await fetchDataGet('/v1/product/getProductVariationGroupById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductVariationById(variationId) {
	const data = await fetchDataGet('/v1/product/getProductVariationById/' + variationId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductVariationsById(productId) {
	const data = await fetchDataGet('/v1/product/getProductVariationsById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddProductVariation(formData) {
	const data = await fetchDataPost('/admin/product/addProductVariation', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateProductVariation(variationId, formData) {
	const data = await fetchDataPost('/admin/product/updateProductVariation/' + variationId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteProductVariation(variationId) {
	const data = await fetchDataGet('/admin/product/deleteProductVariation/' + variationId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductFeaturedVariationById(productId) {
	const data = await fetchDataGet('/admin/product/getProductFeaturedVariationById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateProductFeaturedVariation(productId, formData) {
	const data = await fetchDataPost('/admin/product/updateProductFeaturedVariationById/' + productId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetProductVariationsImageById(productId) {
	const data = await fetchDataGet('/v1/product/getVariationsImageById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetProductSeo(productId) {
	const data = await fetchDataGet('/v1/productSeo/getProductSeoById/' + productId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateProductSeo(formData) {
	const data = await fetchDataPost('/admin/productSeo/addProductSeo', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.status;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostAddProductImage(formData) {
	await xhrDataPost('/admin/product/addProductImage', formData, addProductImageCallback);
}

async function serviceGetCheckProductSku(sku) {
	const data = await fetchDataGet('/v1/product/getCheckProductSku/' + sku, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCheckProductVariationSku(sku) {
	const data = await fetchDataGet('/v1/product/getCheckProductVariationSku/' + sku, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}


async function serviceGetCartById(cartId) {
	const data = await fetchDataGet('/v1/cart/getCartById/' + cartId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddCart(formData) {
	const data = await fetchDataPost('/v1/cart/addCart', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateCart(formData) {
	const data = await fetchDataPost('/v1/cart/updateCartProduct', formData, 'application/json');
	if (data.status == "success") {
		// showAlert(data.message);
		return data;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostRemoveCartProduct(formData) {
	const data = await fetchDataPost('/v1/cart/deleteCartProduct', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetClearCartById(cartId) {
	const data = await fetchDataGet('/v1/cart/getClearCartById/' + cartId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}


async function servicePostAddOrder(formData) {
	const data = await fetchDataPost('/v1/order/addOrder', formData, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetOnGoingOrders() {
	const data = await fetchDataGet('/admin/order/getOnGoingOrders', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCompletedOrders() {
	const data = await fetchDataGet('/admin/order/getCompletedOrders', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetOrderStatuses() {
	const data = await fetchDataGet('/admin/orderStatus/getOrderStatuses', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateStatus(formData, typeId) {
	const data = await fetchDataPost('/admin/order/updateOrderStatus', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetShippingTypes() {
	const data = await fetchDataGet('/admin/shippingType/getShippingTypes', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCarriers() {
	const data = await fetchDataGet('/admin/carrier/getCarriers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateOrderInfo(orderId, formData) {
	const data = await fetchDataPost('/admin/order/updateOrderInfo/' + orderId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateOrderShipment(orderId, formData) {
	const data = await fetchDataPost('/admin/order/updateOrderShipment/' + orderId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateOrderBilling(orderId, formData) {
	const data = await fetchDataPost('/admin/order/updateOrderBilling/' + orderId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateOrderShipping(orderId, formData) {
	const data = await fetchDataPost('/admin/order/updateOrderShipping/' + orderId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetRefundRequests() {
	const data = await fetchDataGet('/admin/order/getRefundOrders', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetRefundStatuses() {
	const data = await fetchDataGet('/admin/order/getOrderRefundStatuses', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateRefundStatus(orderId, formData) {
	const data = await fetchDataPost('/admin/order/updateRefundStatus/' + orderId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetCards() {
	const data = await fetchDataGet('/admin/creditCard/getCreditCards', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCardById(cardId) {
	const data = await fetchDataGet('/admin/creditCard/getCreditCardById/' + cardId, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetInstallmentById(id) {
	const data = await fetchDataGet('/admin/creditCard/getCreditCardInstallmentById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostUpdateCardInstallment(id, formData) {
	const data = await fetchDataPost('/admin/creditCard/postCreditInstallmentUpdate/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateDiscountRate(formData) {
	const data = await fetchDataPost('/admin/product/updateProductDiscountRate', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateDiscountRateByCategoryId(id, formData) {
	const data = await fetchDataPost('/admin/product/updateCategoryIdDiscountRate/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateDiscountRateByTypeId(id, formData) {
	const data = await fetchDataPost('/admin/product/updateTypeIdDiscountRate/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateDiscountRateByBrandId(id, formData) {
	const data = await fetchDataPost('/admin/product/updateBrandIdDiscountRate/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetSliders() {
	const data = await fetchDataGet('/v1/slider/getSliders', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetSliderById(id) {
	const data = await fetchDataGet('/v1/slider/getSliderById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddSlider(formData) {
	await xhrDataPost('/admin/slider/addSlider', formData, addSliderCallback);
}

async function servicePostUpdateSlider(id, formData) {
	await xhrDataPost('/admin/slider/updateSlider/' + id, formData, updateSliderCallback);
}
async function serviceGetDeleteSlider(id) {
	const data = await fetchDataGet('/admin/slider/deleteSlider/' + id, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetCoupons() {
	const data = await fetchDataGet('/admin/coupon/getCoupons', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetCouponById(id) {
	const data = await fetchDataGet('/admin/coupon/getCouponById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostUseCoupon(formData) {
	const data = await fetchDataPost('/v1/coupon/useCoupon', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return data;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostAddCoupon(formData) {
	const data = await fetchDataPost('/admin/coupon/addCoupon', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateCoupon(id, formData) {
	const data = await fetchDataPost('/admin/coupon/updateCoupon/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteCoupon(id) {
	const data = await fetchDataGet('/admin/coupon/deleteCoupon/' + id, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		if (data.status == "error-002"){
			showAlert(data.message);
		}else {
			showAlert('İstek Başarısız.');
		}
	}
}

async function serviceGetSeos() {
	const data = await fetchDataGet('/v1/seo/getSeos', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetSeoById(id) {
	const data = await fetchDataGet('/v1/seo/getSeoById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostUpdateSeo(id, formData) {
	const data = await fetchDataPost('/admin/seo/updateSeo/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
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

async function serviceGetDeliveryPrices() {
	const data = await fetchDataGet('/admin/delivery/getDeliveryPrices', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetDeliveryPriceById(id) {
	const data = await fetchDataGet('/admin/delivery/getDeliveryPriceById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddDeliveryPrice(formData) {
	const data = await fetchDataPost('/admin/delivery/addDeliveryPrice', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateDeliveryPrice(id, formData) {
	const data = await fetchDataPost('/admin/delivery/updateDeliveryPrice/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteDeliveryPrice(id) {
	const data = await fetchDataGet('/admin/delivery/deleteDeliveryPrice/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetRegionalDeliveryPriceByCityId(id) {
	const data = await fetchDataGet('/admin/delivery/getRegionalDeliveryPriceByCityId/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetRegionalDeliveryPrice(city_id, delivery_price_id) {
	const data = await fetchDataGet('/admin/delivery/getRegionalDeliveryPrice/' + city_id + '/' + delivery_price_id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostUpdateRegionalDeliveryPrice(city_id, delivery_price_id, formData) {
	const data = await fetchDataPost('/admin/delivery/updateRegionalDeliveryPrice/' + city_id + '/' + delivery_price_id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetResetPricesToDefaultByCityId(id) {
	const data = await fetchDataGet('/admin/delivery/resetPricesToDefaultByCityId/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetResetAllPricesToDefault() {
	const data = await fetchDataGet('/admin/delivery/resetAllPricesToDefault', 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetSyncCitiesToRegionalDelivery(id) {
	const data = await fetchDataGet('/admin/delivery/syncCitiesToRegionalDelivery', 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetPopups() {
	const data = await fetchDataGet('/v1/popup/getPopups', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetPopupById(id) {
	const data = await fetchDataGet('/v1/popup/getPopupById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function servicePostAddPopup(formData) {
	await xhrDataPost('/admin/popup/addPopup', formData, addPopupCallback);
}

async function servicePostUpdatePopup(id, formData) {
	await xhrDataPost('/admin/popup/updatePopup/' + id, formData, updatePopupCallback);
}
async function serviceGetDeletePopup(id) {
	const data = await fetchDataGet('/admin/popup/deletePopup/' + id, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetChangePopupStatus(id, status) {
	const data = await fetchDataGet('/admin/popup/changePopupStatus/' + id + '/' + status, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetChangePopupFormStatus(id, status) {
	const data = await fetchDataGet('/admin/popup/changePopupFormStatus/' + id + '/' + status, 'application/json');
	if (data.status == "success") {
		return data;
	} else {
		showAlert('İstek Başarısız.');
	}
}

async function serviceGetSubscribers() {
	const data = await fetchDataGet('/admin/subscribe/getSubscribers', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetSubscriberById(id) {
	const data = await fetchDataGet('/admin/subscribe/getSubscriberById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddSubscriber(formData) {
	const data = await fetchDataPost('/v1/subscribe/addSubscriber', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateSubscriber(formData, subscriberId) {
	const data = await fetchDataPost('/admin/subscribe/updateSubscriber/' + subscriberId, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteSubscriber(subscriberId) {
	const data = await fetchDataGet('/admin/subscribe/deleteSubscriber/' + subscriberId, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetUserTypes() {
	const data = await fetchDataGet('/admin/user/getUserTypes', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetUserTypeById(id) {
	const data = await fetchDataGet('/admin/user/getUserTypeById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddUserType(formData) {
	const data = await fetchDataPost('/admin/user/addUserType', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function servicePostUpdateUserType(formData, id) {
	const data = await fetchDataPost('/admin/user/updateUserType/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}

async function serviceGetDeleteUserType(id) {
	const data = await fetchDataGet('/admin/user/deleteUserType/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}



async function serviceGetUserTypeDiscounts() {
	const data = await fetchDataGet('/admin/user/getUserTypeDiscounts', 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function serviceGetUserTypeDiscountById(id) {
	const data = await fetchDataGet('/admin/user/getUserTypeDiscountById/' + id, 'application/json');
	if (data.status == "success") {
		return data.object;
	} else {
		showAlert('İstek Başarısız.');
	}
}
async function servicePostAddUserTypeDiscount(formData) {
	const data = await fetchDataPost('/admin/user/addUserTypeDiscount', formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function servicePostUpdateUserTypeDiscount(formData, id) {
	const data = await fetchDataPost('/admin/user/updateUserTypeDiscount/' + id, formData, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
async function serviceGetDeleteUserTypeDiscount(id) {
	const data = await fetchDataGet('/admin/user/deleteUserTypeDiscount/' + id, 'application/json');
	if (data.status == "success") {
		showAlert(data.message);
		return true;
	} else {
		showAlert('İstek Başarısız.');
		return false;
	}
}
