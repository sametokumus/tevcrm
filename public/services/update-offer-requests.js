(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_offer_request_form').submit(function (e){
			e.preventDefault();
            updateOfferRequest();
		});

        $('#update_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('update_offer_request_product_refcode').value;
            let product_name = document.getElementById('update_offer_request_product_name').value;
            let quantity = document.getElementById('update_offer_request_product_quantity').value;
            let measurement = document.getElementById('update_offer_request_product_measurement').value;
            let brand = document.getElementById('update_offer_request_brand').value;
            let category = document.getElementById('update_offer_request_product_category').value;
            let cust_stock = document.getElementById('update_offer_request_customer_stock_code').value;
            let own_stock = document.getElementById('update_offer_request_owner_stock_code').value;
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            console.log(refcode, product_name, quantity, measurement, brand, category, cust_stock, own_stock);
            addProductToTable(refcode, product_name, quantity, measurement, brand, category, cust_stock, own_stock);
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		await initPage();
        await initOfferRequest();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initPage(){
    await getOwnersAddSelectId('update_offer_request_owner');
    await getAdminsAddSelectId('update_offer_request_authorized_personnel');
    await getCustomersAddSelectId('update_offer_request_company');
    await getMeasurementsAddSelectId('update_offer_request_product_measurement');
    await getParentCategoriesAddSelectId('update_offer_request_product_category_1');
    let data1 = await serviceGetBrands();
    let brands = [];
    $.each(data1.brands, function (i, brand) {
        let item = {
            "id": brand.id,
            "name": brand.name
        }
        brands.push(item);
    });
    $('#update_offer_request_brand').typeahead({
        source: brands,
        autoSelect: true
    });

}

async function initEmployeeSelect(){
    let company_id = document.getElementById('update_offer_request_company').value;
    getEmployeesAddSelectId(company_id, 'update_offer_request_company_employee');
}

async function initSecondCategory(){
    let parent_id = document.getElementById('update_offer_request_product_category_1').value;
    $('#update_offer_request_product_category_2 option').remove();
    $('#update_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#update_offer_request_product_category_2').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#update_offer_request_product_category_2').append(optionRow);
    });
}

async function initThirdCategory(){
    let parent_id = document.getElementById('update_offer_request_product_category_2').value;
    $('#update_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#update_offer_request_product_category_3').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#update_offer_request_product_category_3').append(optionRow);
    });
}

async function addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock){

    let count = document.getElementById('update_offer_request_product_count').value;
    count = parseInt(count) + 1;
    document.getElementById('update_offer_request_product_count').value = count;
    let item = '<tr id="productRow'+ count +'">\n' +
        '           <td>'+ count +'</td>\n' +
        '           <td>'+ own_stock +'</td>\n' +
        '           <td>'+ cust_stock +'</td>\n' +
        '           <td>'+ refcode +'</td>\n' +
        '           <td>'+ product_name +'</td>\n' +
        '           <td>'+ quantity +'</td>\n' +
        '           <td>'+ measurement +'</td>\n' +
        '           <td>'+ brand +'</td>\n' +
        '           <td>'+ category3 +'</td>\n' +
        '           <td>\n' +
        '               <div class="btn-list">\n' +
        '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="deleteProductRow('+ count +');"><span class="fe fe-trash-2"> Sil</span></button>\n' +
        '               </div>\n' +
        '           </td>\n' +
        '       </tr>';
    $('#offer-request-products tbody').append(item);
    document.getElementById('update_offer_request_product_refcode').value = "";
    document.getElementById('update_offer_request_product_name').value = "";
    document.getElementById('update_offer_request_product_quantity').value = "1";
    document.getElementById('update_offer_request_product_measurement').value = "adet";
    document.getElementById('update_offer_request_owner_stock_code').value = "";
    document.getElementById('update_offer_request_customer_stock_code').value = "";
}

async function deleteProductRow(item_id){
    let returned = serviceGetDeleteProductToOfferRequest(item_id);
    if (returned) {
        $('#productRow' + item_id).remove();
    }else{
        alert("Ürün silinemedi");
    }
    // let count = document.getElementById('update_offer_request_product_count').value;
    // count = parseInt(count) - 1;
    // document.getElementById('update_offer_request_product_count').value = count;
}

async function initOfferRequest(){
    let request_id = getPathVariable('offer-request');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)

    document.getElementById('update_offer_request_id').value = request_id;
    document.getElementById('update_offer_request_owner').value = offer_request.owner_id;
    document.getElementById('update_offer_request_authorized_personnel').value = checkNull(offer_request.authorized_personnel_id);
    document.getElementById('update_offer_request_company').value = offer_request.company_id;
    document.getElementById('update_offer_request_product_count').value = offer_request.products.length;

    if (offer_request.company_id != null && offer_request.company_id != 0) {
        await initEmployeeSelect();
        document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    }

    $.each(offer_request.products, function (i, product) {
        let product_name = product.product_name;


        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + (i+1) + '</td>\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
            '           <td>' + product.measurement_name + '</td>\n' +
            '           <td>\n' +
            '               <div class="btn-list">\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="deleteProductRow(' + product.id + ');"><span class="fe fe-trash-2"> Sil</span></button>\n' +
            '               </div>\n' +
            '           </td>\n' +
            '       </tr>';
        $('#offer-request-products tbody').append(item);
    });
}

async function updateOfferRequest(){
    let user_id = localStorage.getItem('userId');
    let request_id = getPathVariable('offer-request');

    let personnel = document.getElementById('update_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let company = document.getElementById('update_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('update_offer_request_company_employee').value;
    if (employee == 0){employee = null;}

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "authorized_personnel_id": personnel,
        "company_id": company,
        "company_employee_id": employee
    });

    console.log(formData);

    let returned = await servicePostUpdateOfferRequest(request_id, formData);
    if (returned){
        window.location.reload();
    }else{
        alert("Hata Oluştu");
    }
}
