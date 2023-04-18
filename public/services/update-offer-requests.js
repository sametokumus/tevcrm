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
            let category1 = document.getElementById('update_offer_request_product_category_1').value;
            let category2 = document.getElementById('update_offer_request_product_category_2').value;
            let category3 = document.getElementById('update_offer_request_product_category_3').value;
            let cust_stock = document.getElementById('update_offer_request_customer_stock_code').value;
            let own_stock = document.getElementById('update_offer_request_owner_stock_code').value;
            let note = document.getElementById('update_offer_request_note').value;
            if (quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            console.log(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
            addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
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
    await getAdminsAddSelectId('update_offer_request_purchasing_staff');
    await getCustomersAndPotentialsAddSelectId('update_offer_request_company');
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

async function addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note){

    let formData = JSON.stringify({
        "owner_stock_code": own_stock,
        "customer_stock_code": cust_stock,
        "ref_code": refcode,
        "product_name": product_name,
        "quantity": quantity,
        "measurement": measurement,
        "brand": brand,
        "category": category3,
        "note": note
    });
    console.log(formData)
    let request_id = getPathVariable('offer-request');

    let returned = await servicePostAddProductToOfferRequest(request_id, formData);
    if (returned){

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
            '           <td>'+ note +'</td>\n' +
            '           <td>\n' +
            '               <div class="btn-list">\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="updateProductRow('+ count +');"><span class="fe fe-trash-2"> Düzenle</span></button>\n' +
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
        document.getElementById('update_offer_request_customer_brand').value = "";
        document.getElementById('update_offer_request_note').value = "";

    }else{
        alert("Hata Oluştu");
    }

}

async function deleteProductRow(item_id){
    let returned = serviceGetDeleteProductToOfferRequest(item_id);
    if (returned) {
        $('#productRow' + item_id).remove();
    }else{
        alert("Ürün silinemedi");
    }
}
async function updateProductRow(item_id){
    let returned = serviceGetDeleteProductToOfferRequest(item_id);
    if (returned) {
        const table = document.getElementById("offer-request-products");
        const row = table.querySelector("#productRow"+item_id);
        const cells = row.cells;
        const cellData = [];
        for (let i = 0; i < cells.length; i++) {
            cellData.push(cells[i].textContent);
        }

        document.getElementById('update_offer_request_product_refcode').value = cellData[3];
        document.getElementById('update_offer_request_product_name').value = cellData[4];
        document.getElementById('update_offer_request_product_quantity').value = cellData[5];
        document.getElementById('update_offer_request_product_measurement').value = cellData[6];
        document.getElementById('update_offer_request_brand').value = cellData[7];
        document.getElementById('update_offer_request_customer_stock_code').value = cellData[2];
        document.getElementById('update_offer_request_owner_stock_code').value = cellData[1];
        document.getElementById('update_offer_request_note').value = cellData[9];
        $('#productRow' + item_id).remove();
    }else{
        alert("Ürün silinemedi");
    }
}

async function initOfferRequest(){
    let request_id = getPathVariable('offer-request');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)

    document.getElementById('update_offer_request_id').value = request_id;
    document.getElementById('update_offer_request_owner').value = offer_request.owner_id;
    document.getElementById('update_offer_request_authorized_personnel').value = checkNull(offer_request.authorized_personnel_id);
    document.getElementById('update_offer_request_purchasing_staff').value = checkNull(offer_request.purchasing_staff_id);
    document.getElementById('update_offer_request_company').value = offer_request.company_id;
    document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    document.getElementById('update_offer_request_company_request_code').value = offer_request.company_request_code;
    // document.getElementById('update_offer_request_note').value = offer_request.note;
    document.getElementById('update_offer_request_product_count').value = offer_request.products.length;

    if (offer_request.company_id != null && offer_request.company_id != 0) {
        await initEmployeeSelect();
        document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    }

    $.each(offer_request.products, function (i, product) {

        let measurement_name = '';
        if (Lang.getLocale() == 'tr'){
            measurement_name = product.measurement_name_tr;
        }else{
            measurement_name = product.measurement_name_en;
        }
        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + (i+1) + '</td>\n' +
            '           <td>' + checkNull(product.product.stock_code) + '</td>\n' +
            '           <td>' + checkNull(product.customer_stock_code) + '</td>\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product.product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
            '           <td>' + checkNull(measurement_name) + '</td>\n' +
            '           <td>' + product.product.brand_id + '</td>\n' +
            '           <td>' + checkNull(product.product.category_id) + '</td>\n' +
            '           <td>' + checkNull(product.note) + '</td>\n' +
            '           <td>\n' +
            '               <div class="btn-list">\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="updateProductRow(' + product.id + ');"><span class="fe fe-trash-2"> Düzenle</span></button>\n' +
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

    let owner = document.getElementById('update_offer_request_owner').value;
    if (owner == 0){owner = null;}
    let personnel = document.getElementById('update_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let purchasing = document.getElementById('update_offer_request_purchasing_staff').value;
    if (purchasing == 0){purchasing = null;}
    let company = document.getElementById('update_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('update_offer_request_company_employee').value;
    if (employee == 0){employee = null;}
    let request_code = document.getElementById('update_offer_request_company_request_code').value;

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "authorized_personnel_id": personnel,
        "purchasing_staff_id": purchasing,
        "company_id": company,
        "company_employee_id": employee,
        "company_request_code": request_code,
        "owner_id": owner
    });

    console.log(formData);

    let returned = await servicePostUpdateOfferRequest(request_id, formData);
    if (returned){
        window.location.reload();
    }else{
        alert("Hata Oluştu");
    }
}
