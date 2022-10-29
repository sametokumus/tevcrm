(function($) {
	"use strict";

	$(document).ready(function() {

		$(":input").inputmask();
        $("#add_contact_email").inputmask("email");

		$("#delivery_price").maskMoney({thousands:''});
		$("#product_variation_regular_price").maskMoney({thousands:''});
		$("#product_variation_regular_tax").maskMoney({thousands:''});
		$("#product_variation_discounted_price").maskMoney({thousands:''});
		$("#product_variation_discounted_tax").maskMoney({thousands:''});
		$("#update_product_variation_regular_price").maskMoney({thousands:''});
		$("#update_product_variation_regular_tax").maskMoney({thousands:''});
		$("#update_product_variation_discounted_price").maskMoney({thousands:''});
		$("#update_product_variation_discounted_tax").maskMoney({thousands:''});

        $('#update_customer_form').submit(function (e){
            e.preventDefault();
            updateCustomer();
        });

		$('#add_address_form').submit(function (e){
			e.preventDefault();
			addAddress();
		});

		$('#add_contact_form').submit(function (e){
			e.preventDefault();
			addContact();
		});

		$('#update_address_form').submit(function (e){
			e.preventDefault();
			updateAddress();
		});

		$('#update_contact_form').submit(function (e){
			e.preventDefault();
			updateContact();
		});




	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		// initProduct();
        initCustomer();
        initCustomerAddresses();
        initCustomerContacts();
	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCustomer(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerById(customer_id);
    let customer = data.customer;
    let customer_name =  customer.name + '\n' +
        '                    <div class="d-inline-block m-3">\n' +
        '                        <button onclick="openCustomerModal();" class="btn btn-sm btn-default"><span class="fe fe-edit"></span></button>\n' +
        '                    </div>';

    $('#customer-name').html('');
    $('#customer-name').append(customer_name);
}
async function openCustomerModal(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerById(customer_id);
    let customer = data.customer;
    document.getElementById('update_customer_id').value = customer.id;
    document.getElementById('update_customer_name').value = customer.name;

    $('#updateCustomerModal').modal('show');
}
async function updateCustomer(){
    let customer_id = document.getElementById('update_customer_id').value;
    let customer_name = document.getElementById('update_customer_name').value;
    let formData = JSON.stringify({
        "name": customer_name
    });

    let returned = await servicePostUpdateCustomer(customer_id, formData);
    if(returned){
        $("#update_customer_form").trigger("reset");
        $('#updateCustomerModal').modal('hide');
        initCustomer();
    }
}

async function initCustomerAddresses(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerAddresses(customer_id);
    $("#address-datatable").dataTable().fnDestroy();
    $('#address-datatable tbody > tr').remove();

    console.log(data)
    $.each(data.addresses, function (i, address) {
        let userItem = '<tr>\n' +
            '              <td>'+ address.id +'</td>\n' +
            '              <td>'+ address.name +'</td>\n' +
            '              <td>'+ address.address +'</td>\n' +
            '              <td>'+ address.phone +'</td>\n' +
            '              <td>'+ address.fax +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button class="btn btn-sm btn-primary" onclick="openUpdateAddressModal('+ address.id +')"><span class="fe fe-edit"></span> Düzenle</button>\n' +
            '                      <button class="btn btn-sm btn-danger" onclick="deleteAddress('+ address.id +')"><span class="fe fe-trash-2"></span> Sil</button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#address-datatable tbody').append(userItem);
    });
    $('#address-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "/services/Turkish.json"
        },
        order: [[0, 'asc']],
    });
}
async function openAddAddressModal(){
    $('#addAddressModal').modal('show');
    await getCountriesAddSelectId('add_address_country');
}
async function addAddress(){
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('add_address_name').value,
        "address": document.getElementById('add_address_address').value,
        "country_id": document.getElementById('add_address_country').value,
        "state_id": document.getElementById('add_address_state').value,
        "city_id": document.getElementById('add_address_city').value,
        "phone": document.getElementById('add_address_phone').value,
        "fax": document.getElementById('add_address_fax').value
    });

    let returned = await servicePostAddCustomerAddress(formData);
    if(returned){
        $("#add_address_form").trigger("reset");
        $('#addAddressModal').modal('hide');
        initCustomerAddresses();
    }
}
async function openUpdateAddressModal(address_id){
    $('#updateAddressModal').modal('show');
    document.getElementById('update_address_id').value = address_id;
    let data = await serviceGetCustomerAddressById(address_id);
    let address = data.address;

    await getCountriesAddSelectId('update_address_country');
    document.getElementById('update_address_country').addEventListener('change', () => {getStatesAddSelectIdWithParent('update_address_state', 'update_address_country'); $('#update_address_city option').remove();}, false);

    await getStatesAddSelectId('update_address_state', address.country_id);
    document.getElementById('update_address_state').addEventListener('change', () => {getCitiesAddSelectIdWithParent('update_address_city', 'update_address_state');}, false);

    await getCitiesAddSelectId('update_address_city', address.state_id);

    document.getElementById('update_address_name').value = address.name;
    document.getElementById('update_address_address').value = address.address;
    document.getElementById('update_address_country').value = address.country_id;
    document.getElementById('update_address_city').value = address.city_id;
    document.getElementById('update_address_state').value = address.state_id;
    document.getElementById('update_address_phone').value = address.phone;
    document.getElementById('update_address_fax').value = address.fax;
}
async function updateAddress(){
    let address_id = document.getElementById('update_address_id').value;
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "name": document.getElementById('update_address_name').value,
        "address": document.getElementById('update_address_address').value,
        "country_id": document.getElementById('update_address_country').value,
        "state_id": document.getElementById('update_address_state').value,
        "city_id": document.getElementById('update_address_city').value,
        "phone": document.getElementById('update_address_phone').value,
        "fax": document.getElementById('update_address_fax').value
    });
    console.log(formData)
    let returned = await servicePostUpdateCustomerAddress(address_id, formData);
    if(returned){
        $("#update_address_form").trigger("reset");
        $('#updateAddressModal').modal('hide');
        initCustomerAddresses();
    }
}
async function deleteAddress(address_id){
    let returned = await serviceGetDeleteCustomerAddress(address_id);
    if(returned){
        initCustomerAddresses();
    }
}


async function initCustomerContacts(){
    let customer_id = getPathVariable('customer-detail');
    let data = await serviceGetCustomerContacts(customer_id);
    $("#contacts-datatable").dataTable().fnDestroy();
    $('#contacts-datatable tbody > tr').remove();

    console.log(data)
    $.each(data.contacts, function (i, contact) {
        let userItem = '<tr>\n' +
            '              <td>'+ contact.id +'</td>\n' +
            '              <td>'+ contact.title +'</td>\n' +
            '              <td>'+ contact.name +'</td>\n' +
            '              <td>'+ contact.phone +'</td>\n' +
            '              <td>'+ contact.email +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button class="btn btn-sm btn-primary" onclick="openUpdateContactModal('+ contact.id +')"><span class="fe fe-edit"></span> Düzenle</button>\n' +
            '                      <button class="btn btn-sm btn-danger" onclick="deleteContact('+ contact.id +')"><span class="fe fe-trash-2"></span> Sil</button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#contacts-datatable tbody').append(userItem);
    });
    $('#contacts-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : 20,
        language: {
            url: "/services/Turkish.json"
        },
        order: [[0, 'asc']],
    });
}
async function openAddContactModal(){
    $('#addContactModal').modal('show');
}
async function addContact(){
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "title": document.getElementById('add_contact_title').value,
        "name": document.getElementById('add_contact_name').value,
        "phone": document.getElementById('add_contact_phone').value,
        "email": document.getElementById('add_contact_email').value
    });

    let returned = await servicePostAddCustomerContact(formData);
    if(returned){
        $("#add_contact_form").trigger("reset");
        $('#addContactModal').modal('hide');
        initCustomerContacts();
    }
}
async function openUpdateContactModal(contact_id){
    $('#updateContactModal').modal('show');
    document.getElementById('update_contact_id').value = contact_id;
    let data = await serviceGetCustomerContactById(contact_id);
    let contact = data.contact;

    document.getElementById('update_contact_title').value = contact.title;
    document.getElementById('update_contact_name').value = contact.name;
    document.getElementById('update_contact_phone').value = contact.phone;
    document.getElementById('update_contact_email').value = contact.email;
}
async function updateContact(){
    let contact_id = document.getElementById('update_contact_id').value;
    let customer_id = getPathVariable('customer-detail');
    let formData = JSON.stringify({
        "customer_id": customer_id,
        "title": document.getElementById('update_contact_title').value,
        "name": document.getElementById('update_contact_name').value,
        "phone": document.getElementById('update_contact_phone').value,
        "email": document.getElementById('update_contact_email').value
    });
    let returned = await servicePostUpdateCustomerContact(contact_id, formData);
    if(returned){
        $("#update_contact_form").trigger("reset");
        $('#updateContactModal').modal('hide');
        initCustomerContacts();
    }
}
async function deleteContact(contact_id){
    let returned = await serviceGetDeleteCustomerContact(contact_id);
    if(returned){
        initCustomerContacts();
    }
}



let clickCategoryListItem = async function() {

	let product_id = getURLParam('id');
	let category_id = $(this).data('category-id');
	let formData = JSON.stringify({
		"product_id": product_id,
		"category_id": category_id
	});
	if($('#product_category_'+category_id).hasClass('selected')){
		let status = await servicePostDeleteProductCategory(formData);
		if(status == "success"){
			$('#product_category_'+category_id).removeClass('selected');
		}
	}else{
		let status = await servicePostAddProductCategory(formData);
		if(status == "success"){
			$('#product_category_'+category_id).addClass('selected');
		}
	}
};
let clickTagListItem = async function() {

	let product_id = getURLParam('id');
	let tag_id = $(this).data('tag-id');
	let formData = JSON.stringify({
		"product_id": product_id,
		"tag_id": tag_id
	});
	if($('#product_tag_'+tag_id).hasClass('selected')){
		let status = await servicePostDeleteProductTag(formData);
		if(status == "success"){
			$('#product_tag_'+tag_id).removeClass('selected');
		}
	}else{
		let status = await servicePostAddProductTag(formData);
		if(status == "success"){
			$('#product_tag_'+tag_id).addClass('selected');
		}
	}
};
let clickVariationItem = async function() {

	let variation_id = $(this).data('id');
	$('#updateProductVariationModal').modal('show');
	initUpdateProductVariationModal(variation_id);

};
let clickDeleteVariationItem = async function() {

	let variation_id = $(this).data('id');
	$('#deleteProductVariationModal').modal('show');
	document.getElementById('delete_product_variation_id').value = variation_id;
};

async function addProductDocumentCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#add_product_document_form").trigger("reset");
	initProductDocumentList();
}
async function addProductImageCallback(xhttp){
	let jsonData = await xhttp.responseText;
	const obj = JSON.parse(jsonData);
	showAlert(obj.message);
	$("#add_product_variation_image_form").trigger("reset");
	initProductVariationImages();
}

async function initProduct(){

	await initBrandList();
	await initTypeList();
	await initCategoryView();
	await initProductSeo();
	await initTagList();
	await initProductDocumentList();
	await initTabList();
	await initSelectProductTabList();
	await initVariationGroupTypes();
	await initProductVariationGroups();
	await initProductVariations();
	await initProductVariationImages();

	let product_id = getURLParam('id');
	let data = await serviceGetProductById(product_id);
	let product = data.products;
	document.getElementById('product_sku').value = product.sku;
	document.getElementById('product_brand').value = product.brand_id;
	document.getElementById('product_type').value = product.type_id;
	document.getElementById('product_name').value = product.name;
	tinymce.get('product_description').setContent(product.description);
	if (product.view_all_images == 1){$('#view_all_images').prop("checked", true);}
	if (product.is_free_shipping == 1){$('#is_free_shipping').prop("checked", true);}

	await setProductCategory(product_id);
	await setProductTag(product_id);

}

async function initCategoryView(){
	$('#product_category_view > li').remove();
	let data = await serviceGetCategories();
	$.each(data.categories, function (i, category) {
		if ((category.sub_categories.length == 0)){
			let categoryItem = '<li>'+ category.name +'</li>';
			$('#product_category_view').append(categoryItem);
		}else{
			let categoryItem = '<li>'+ category.name +'\n' +
				'               	<ul>';
			$.each(category.sub_categories, function (i, sub_category) {
				categoryItem = categoryItem + '<li class="product_category" id="product_category_'+ sub_category.id +'" data-category-id="'+ sub_category.id +'">'+ sub_category.name +
					'</li>';
			});
			categoryItem = categoryItem + '</ul>\n' +
				'       </li>';
			$('#product_category_view').append(categoryItem);
		}
	});
	$('#product_category_view').treed();
}
async function setProductCategory(product_id){
	let product_category_data = await serviceGetProductCategoryById(product_id);
	console.log(product_category_data)
	$.each(product_category_data.product_categories, function (i, product_category) {
		$('#product_category_'+product_category.category_id).addClass('selected');
	});

	var items = document.getElementsByClassName("product_category");
	for (var i = 0; i < items.length; i++) {
		items[i].addEventListener('click', clickCategoryListItem, false);
	}
}
async function initBrandList(){
	let data = await serviceGetBrands();
	$.each(data.brands, function (i, brand) {
		var brandItem = '<option value="'+ brand.id +'">'+ brand.name +'</option>';

		$('#product_brand').append(brandItem);
	});
}
async function initTypeList(){
	let data = await serviceGetTypes();
	$.each(data.product_type, function (i, product_type) {
		var typeItem = '<option value="'+ product_type.id +'">'+ product_type.name +'</option>';

		$('#product_type').append(typeItem);
	});
}
async function initTagList(){
	let data = await serviceGetTags();
	$.each(data.tags, function (i, tag) {
		var tagItem = '<span class="tag product_tag" id="product_tag_'+ tag.id +'" data-tag-id="'+ tag.id +'">'+ tag.name +'</span>';

		$('#product_tags').append(tagItem);
	});
}
async function setProductTag(product_id){
	let product_tag_data = await serviceGetProductTagById(product_id);
	console.log(product_tag_data)
	$.each(product_tag_data.product_tags, function (i, product_tag) {
		$('#product_tag_'+product_tag.tag_id).addClass('selected');
	});

	var items = document.getElementsByClassName("product_tag");
	for (var i = 0; i < items.length; i++) {
		items[i].addEventListener('click', clickTagListItem, false);
	}
}
async function initProductDocumentList(){
	let product_id = getURLParam('id');
	$('#product_documents .document_item').remove();
	let data = await serviceGetProductDocuments(product_id);
	console.log(data)
	$.each(data.product_documents, function (i, product_document) {
		let documentItem = '<div class="pt-2 document_item">\n' +
			'                   <div class="media mt-0 border">\n' +
			'                       <div class="media-body">\n' +
			'                           <div class="d-flex align-items-center">\n' +
			'                               <div class="mt-0"><h5 class="mb-0 fs-13 fw-semibold text-dark"> '+ product_document.title +'</h5></div>\n' +
			'                               <span class="ms-auto fs-14">\n' +
			'                                   <span class="float-end">\n' +
			'                                       <button onclick="deleteProductDocument(\''+ product_document.id +'\')"><span class="op-7 text-muted mx-1"><i class="fe fe-trash-2"></i></span></button>\n' +
			'                                   </span>\n' +
			'                                   <span class="float-end">\n' +
			'                                       <a href="https://api-kablocu.wimco.com.tr'+ product_document.file +'" target="_blank"><span class="op-7 text-muted mx-1"><i class="fe fe-download"></i></span></a>\n' +
			'                                   </span>\n' +
			'                               </span>\n' +
			'                           </div>\n' +
			'                       </div>\n' +
			'                   </div>\n' +
			'               </div>';

		$('#product_documents').append(documentItem);
	});
}
async function initTabList(){
	$('#add_product_tab_name option').remove();
	let data = await serviceGetTabs();
	console.log(data)
	$.each(data.tabs, function (i, tab) {
		var tabItem = '<option value="'+ tab.id +'">'+ tab.title +'</option>';

		$('#add_product_tab_name').append(tabItem);
	});
}
async function initSelectProductTabList(){
	let product_id = getURLParam('id');
	$('#select_product_tab_name option').remove();
	let data = await serviceGetProductTabsById(product_id);
	$.each(data.product_tabs, function (i, product_tab) {
		var tabItem = '<option value="'+ product_tab.id +'">'+ product_tab.tab.title +'</option>';

		$('#select_product_tab_name').append(tabItem);
	});
}
async function initUpdateProductTabModal(tab_id){
	let data = await serviceGetProductTabById(tab_id);
	let product_tab = data.product_tab;
	document.getElementById('update_product_tab_name').value = product_tab.id;
	tinymce.get('update_product_tab_text').setContent(product_tab.content_text);
}
async function initVariationGroupTypes(){
	$('#select_product_variation_group option').remove();
	let data = await serviceGetVariationGroupTypes();
	console.log(data)
	$.each(data.variation_group_types, function (i, type) {
		let typeItem = '<option value="'+ type.id +'">'+ type.name +'</option>';

		$('#select_product_variation_group').append(typeItem);
	});
}
async function initProductVariationGroups(){
	$('#product_variation_groups .tag').remove();
	let product_id = getURLParam('id');
	let data = await serviceGetProductVariationGroupById(product_id);
	console.log(data)
	$.each(data.product_variation_groups, function (i, group) {
		var tagItem = '<span class="tag">'+ group.variation_group_type.name +'</span>';
		var optionItem = '<option value="'+ group.id +'">'+ group.variation_group_type.name +'</option>';

		$('#product_variation_groups').append(tagItem);
		$('#add_variation_product_variation_group').append(optionItem);
		$('#update_variation_product_variation_group').append(optionItem);
	});
}
async function initProductVariations(){
	$('#product_variations .label-content').remove();
	let product_id = getURLParam('id');
	let data = await serviceGetProductVariationsById(product_id);
	console.log(data)

	$('#select_product_featured_variation').append('<option value="0">Seçim Yapılmadı</option>');
	$.each(data.product_variations, function (i, variation) {
		let tagItem = '	<div class="label-content">' +
			'				<span class="tag update_product_variation" data-id="'+ variation.id +'">'+ variation.name +'</span>'+
			'				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger delete_product_variation" data-id="'+ variation.id +'">x\n</span>' +
			'			</div>';
		let variationItem = '<option value="'+ variation.id +'">'+ variation.name +'</option>';

		$('#product_variations').append(tagItem);
		$('#add_variation_image_variation_name').append(variationItem);
		$('#select_product_featured_variation').append(variationItem);
	});
	var items = document.getElementsByClassName("update_product_variation");
	for (var i = 0; i < items.length; i++) {
		items[i].addEventListener('click', clickVariationItem, false);
	}
	var items2 = document.getElementsByClassName("delete_product_variation");
	for (var i = 0; i < items.length; i++) {
		items2[i].addEventListener('click', clickDeleteVariationItem, false);
	}

	let data2 = await serviceGetProductFeaturedVariationById(product_id);
	document.getElementById('select_product_featured_variation').value = data2.featured_variation;

}
async function initUpdateProductVariationModal(variation_id){
	$("#update_product_variation_form").trigger("reset");
	let data = await serviceGetProductVariationById(variation_id);
	console.log(data)
	let product_variation = data.product_variation;
	document.getElementById('update_product_variation_id').value = product_variation.id;
	document.getElementById('update_variation_product_variation_group').value = product_variation.variation_group_id;
	document.getElementById('update_product_variation_sku').value = product_variation.sku;
	document.getElementById('update_product_variation_name').value = product_variation.name;
	tinymce.get('update_product_variation_description').setContent(product_variation.description);
	document.getElementById('update_product_variation_quantity_stock').value = product_variation.rule.quantity_stock;
	document.getElementById('update_product_variation_quantity_min').value = product_variation.rule.quantity_min;
	document.getElementById('update_product_variation_quantity_step').value = product_variation.rule.quantity_step;
	document.getElementById('update_product_variation_discount_rate').value = product_variation.rule.discount_rate;
	document.getElementById('update_product_variation_tax_rate').value = product_variation.rule.tax_rate;
	document.getElementById('update_product_variation_regular_price').value = product_variation.rule.regular_price;
	document.getElementById('update_product_variation_regular_tax').value = product_variation.rule.regular_tax;
	document.getElementById('update_product_variation_discounted_price').value = product_variation.rule.discounted_price;
	document.getElementById('update_product_variation_discounted_tax').value = product_variation.rule.discounted_tax;
	if (product_variation.rule.is_free_shipping == 1) {
		$('#update_product_variation_is_free_shipping').prop("checked", true);
	}
}
async function updateProductFeaturedVariation(variation_id){
	let product_id = getURLParam('id');
	let formData = JSON.stringify({
		"featured_variation": variation_id
	});
	servicePostUpdateProductFeaturedVariation(product_id, formData);
}
async function initProductVariationImages(){
	$('#product_variation_images .row').remove();
	let product_id = getURLParam('id');
	let data = await serviceGetProductVariationsImageById(product_id);
	console.log(data)
	$.each(data.product_variations, function (i, variation) {
		let gallery = '<div class="row">\n';
		gallery = gallery + '<h5 class="mb-3">'+ variation.name +'</h5>\n';
		gallery = gallery + '<ul class="list-unstyled row lightgallery">\n';
		$.each(variation.images, function (i, image) {
			gallery = gallery + '<li class="col-xs-4 col-sm-3 col-md-2 col-xl-2 mb-5 border-bottom-0" data-responsive="'+ image.image +'" data-src="'+ image.image +'" data-sub-html="<h4>Gallery Image</h4>">\n' +
				'                    <a href="">\n' +
				'                        <img class="img-responsive br-5" src="'+ image.image +'" alt="Thumb-1">\n' +
				'                    </a>\n' +
				'                </li>\n';
		});
		gallery = gallery + '</ul>';
		gallery = gallery + '</div>';

		$('#product_variation_images').append(gallery);
	});

	var elements = document.getElementsByClassName('lightgallery');
	for (let item of elements) {
		lightGallery(item, {
			share:false
		})
		console.log(1)
	}
}
async function initProductSeo(){
	let product_id = getURLParam('id');
	let data = await serviceGetProductSeo(product_id);
	document.getElementById('product_seo_title').value = data.seo.title;
	document.getElementById('product_seo_keywords').value = data.seo.keywords;
	document.getElementById('product_seo_description').value = data.seo.description;
	document.getElementById('product_seo_search').value = data.seo.search_keywords;
}



async function updateProduct(){
	let product_id = getURLParam('id');
	let sku = document.getElementById('product_sku').value;
	let brand = document.getElementById('product_brand').value;
	let type = document.getElementById('product_type').value;
	let name = document.getElementById('product_name').value;
	let description = tinymce.get('product_description').getContent();
	let view_all_images = 0;
	let is_free_shipping = 0;
	if($('#view_all_images').prop('checked') == true){ view_all_images = 1; }
	if($('#is_free_shipping').prop('checked') == true){
		is_free_shipping = 1;
	}

	let formData = JSON.stringify({
		"brand_id": brand,
		"type_id": type,
		"name": name,
		"description": description,
		"sku": sku,
		"view_all_images": view_all_images,
		"is_free_shipping": is_free_shipping
	});
	await servicePostUpdateProduct(product_id, formData);
}
async function addProductDocument(){
	let product_id = getURLParam('id');
	var formData = new FormData();
	formData.append('product_id', product_id);
	formData.append('title', document.getElementById('upload_document_name').value);
	formData.append('file', document.getElementById('upload_document').files[0]);
	console.log(formData)

	servicePostAddProductDocument(formData);
}
async function deleteProductDocument(documentId){
	let returned = await serviceGetDeleteProductDocument(documentId);
	if(returned){
		initProductDocumentList();
	}
}
async function addTab(){
	let tab_title = document.getElementById('add_tab_name').value;
	let formData = JSON.stringify({
		"title": tab_title
	});
	let returned = await servicePostAddTab(formData);
	if(returned){
		$("#add_tab_form").trigger("reset");
		initTabList();
	}
}
async function addProductTab(){
	let product_id = getURLParam('id');
	let formData = JSON.stringify({
		"product_id": product_id,
		"product_tab_id": document.getElementById('add_product_tab_name').value,
		"content_text": tinymce.get('add_product_tab_text').getContent()
	});

	let returned = await servicePostAddProductTab(formData);
	if(returned){
		$("#add_product_tab_form").trigger("reset");
		initSelectProductTabList();
	}
}
async function updateProductTab(){
	let product_id = getURLParam('id');
	let formData = JSON.stringify({
		"product_id": product_id,
		"product_tab_id": document.getElementById('update_product_tab_name').value,
		"content_text": tinymce.get('update_product_tab_text').getContent()
	});

	let returned = await servicePostUpdateProductTab(formData);
	if(returned){
		$("#update_product_tab_form").trigger("reset");
		$('#updateProductTabModal').modal('hide');
	}
}
async function deleteProductTab(){
	let tab_id = document.getElementById('select_product_tab_name').value;
	let returned = await serviceGetDeleteProductTabById(tab_id);
	if(returned){
		initSelectProductTabList();
	}
}
async function addProductVariationGroup(){
	let product_id = getURLParam('id');
	let formData = JSON.stringify({
		"product_id": product_id,
		"group_type_id": document.getElementById('select_product_variation_group').value,
		"order": document.getElementById('order_product_variation_group').value
	});
	console.log(formData)
	let returned = await servicePostAddProductVariationGroup(formData);
	if(returned){
		$("#add_product_variation_group_form").trigger("reset");
		await initProductVariationGroups();
	}
}
async function addProductVariation(){
	let is_free_shipping = 0;
	if($('#product_variation_is_free_shipping').prop('checked') == true){
		is_free_shipping = 1;
	}
	let formData = JSON.stringify({
		"variation_group_id":document.getElementById('add_variation_product_variation_group').value,
		"name":document.getElementById('product_variation_name').value,
		"description":tinymce.get('product_variation_description').getContent(),
		"sku":document.getElementById('product_variation_sku').value,
		"quantity_stock":document.getElementById('product_variation_quantity_stock').value,
		"quantity_min":document.getElementById('product_variation_quantity_min').value,
		"quantity_step":document.getElementById('product_variation_quantity_step').value,
		"is_free_shipping":is_free_shipping,
		"discounted_rate":document.getElementById('product_variation_discount_rate').value,
		"tax_rate":document.getElementById('product_variation_tax_rate').value,
		"regular_price":document.getElementById('product_variation_regular_price').value,
		"regular_tax":document.getElementById('product_variation_regular_tax').value,
		"discounted_price":document.getElementById('product_variation_discounted_price').value,
		"discounted_tax":document.getElementById('product_variation_discounted_tax').value
	});
	console.log(formData)
	let returned = await servicePostAddProductVariation(formData);
	if(returned){
		$("#add_product_variation_form").trigger("reset");
		await initProductVariations();
	}
}
async function updateProductVariation(){
	let variation_id = document.getElementById('update_product_variation_id').value;
	let is_free_shipping = 0;
	if($('#update_product_variation_is_free_shipping').prop('checked') == true){
		is_free_shipping = 1;
	}
	let formData = JSON.stringify({
		"variation_group_id":document.getElementById('update_variation_product_variation_group').value,
		"name":document.getElementById('update_product_variation_name').value,
		"description":tinymce.get('update_product_variation_description').getContent(),
		"sku":document.getElementById('update_product_variation_sku').value,
		"quantity_stock":document.getElementById('update_product_variation_quantity_stock').value,
		"quantity_min":document.getElementById('update_product_variation_quantity_min').value,
		"quantity_step":document.getElementById('update_product_variation_quantity_step').value,
		"is_free_shipping":is_free_shipping,
		"discount_rate":document.getElementById('update_product_variation_discount_rate').value,
		"tax_rate":document.getElementById('update_product_variation_tax_rate').value,
		"regular_price":document.getElementById('update_product_variation_regular_price').value,
		"regular_tax":document.getElementById('update_product_variation_regular_tax').value,
		"discounted_price":document.getElementById('update_product_variation_discounted_price').value,
		"discounted_tax":document.getElementById('update_product_variation_discounted_tax').value
	});
	console.log(formData)
	let returned = await servicePostUpdateProductVariation(variation_id, formData);
	if(returned){
		$("#update_product_variation_form").trigger("reset");
		$('#updateProductVariationModal').modal('hide');
		await initProductVariations();
	}
}
async function deleteProductVariation(){
	let variation_id = document.getElementById('delete_product_variation_id').value;

	let returned = await serviceGetDeleteProductVariation(variation_id);
	if(returned){
		console.log("success")
		$("#delete_product_variation_form").trigger("reset");
		$('#deleteProductVariationModal').modal('hide');
		await initProductVariations();
	}
}
async function addProductVariationImage(){
	let formData = new FormData();
	formData.append('variation_id', document.getElementById('add_variation_image_variation_name').value);
	formData.append('name', '');
	formData.append('order', '1');
	formData.append('product_images[]', document.getElementById('add_variation_image_upload_file').files[0]);
	console.log(formData)

	servicePostAddProductImage(formData);
}
async function updateProductSeo(){
	let product_id = getURLParam('id');
	let formData = JSON.stringify({
		"product_id":product_id,
		"title":document.getElementById('product_seo_title').value,
		"keywords":document.getElementById('product_seo_keywords').value,
		"description":document.getElementById('product_seo_description').value,
		"search_keywords":document.getElementById('product_seo_search').value,
	});
	console.log(formData)
	let returned = await servicePostUpdateProductSeo(formData);
}
