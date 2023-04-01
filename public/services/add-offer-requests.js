(function($) {
    "use strict";

	$(document).ready(function() {

		$('#add_offer_request_form').submit(function (e){
			e.preventDefault();
            let x = document.getElementById("offer-request-products-body").rows.length;
            if (x > 0) {
                addOfferRequest();
            }else{
                alert('Ürün giriniz');
                return false;
            }
		});

        $('#add_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('add_offer_request_product_refcode').value;
            let product_name = document.getElementById('add_offer_request_product_name').value;
            let quantity = document.getElementById('add_offer_request_product_quantity').value;
            let measurement = document.getElementById('add_offer_request_product_measurement').value;
            let brand = document.getElementById('add_offer_request_brand').value;
            let category1 = document.getElementById('add_offer_request_product_category_1').value;
            let category2 = document.getElementById('add_offer_request_product_category_2').value;
            let category3 = document.getElementById('add_offer_request_product_category_3').value;
            let cust_stock = document.getElementById('add_offer_request_customer_stock_code').value;
            let own_stock = document.getElementById('add_offer_request_owner_stock_code').value;
            let note = document.getElementById('add_offer_request_note').value;
            if (quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            console.log(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
            addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note);
        });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initPage();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initPage(){
    await getOwnersAddSelectId('add_offer_request_owner');
    await getAdminsAddSelectId('add_offer_request_authorized_personnel');
    await getAdminsAddSelectId('add_offer_request_purchasing_staff');
    await getCustomersAndPotentialsAddSelectId('add_offer_request_company');
    await getMeasurementsAddSelectId('add_offer_request_product_measurement');
    await getParentCategoriesAddSelectId('add_offer_request_product_category_1');
    let data1 = await serviceGetBrands();
    let brands = [];
    $.each(data1.brands, function (i, brand) {
        let item = {
            "id": brand.id,
            "name": brand.name
        }
        brands.push(item);
    });
    $('#add_offer_request_brand').typeahead({
        source: brands,
        autoSelect: true
    });
    $(".add_offer_request_company_select").select2({
        placeholder: "Müşteri Seçiniz"
    });

}

async function initEmployeeSelect(){
    let company_id = document.getElementById('add_offer_request_company').value;
    getEmployeesAddSelectId(company_id, 'add_offer_request_company_employee');
}

async function initSecondCategory(){
    let parent_id = document.getElementById('add_offer_request_product_category_1').value;
    $('#add_offer_request_product_category_2 option').remove();
    $('#add_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#add_offer_request_product_category_2').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#add_offer_request_product_category_2').append(optionRow);
    });
}

async function initThirdCategory(){
    let parent_id = document.getElementById('add_offer_request_product_category_2').value;
    $('#add_offer_request_product_category_3 option').remove();

    let data = await serviceGetCategoriesByParentId(parent_id);
    $('#add_offer_request_product_category_3').append('<option value="0">Kategori Seçiniz</option>');
    $.each(data.categories, function(i, category){
        let optionRow = '<option value="'+category.id+'">'+category.name+'</option>';
        $('#add_offer_request_product_category_3').append(optionRow);
    });
}

async function addProductToTable(refcode, product_name, quantity, measurement, brand, category1, category2, category3, cust_stock, own_stock, note){

    let count = document.getElementById('add_offer_request_product_count').value;
    count = parseInt(count) + 1;
    document.getElementById('add_offer_request_product_count').value = count;
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
    document.getElementById('add_offer_request_product_refcode').value = "";
    document.getElementById('add_offer_request_product_name').value = "";
    document.getElementById('add_offer_request_product_quantity').value = "1";
    document.getElementById('add_offer_request_product_measurement').value = "adet";
    document.getElementById('add_offer_request_owner_stock_code').value = "";
    document.getElementById('add_offer_request_customer_stock_code').value = "";
    document.getElementById('add_offer_request_brand').value = "";
    document.getElementById('add_offer_request_note').value = "";
}

async function deleteProductRow(item_id){
    $('#productRow' + item_id).remove();
    let count = document.getElementById('add_offer_request_product_count').value;
    count = parseInt(count) - 1;
    document.getElementById('add_offer_request_product_count').value = count;
}
async function updateProductRow(item_id){
    const table = document.getElementById("offer-request-products");
    const row = table.querySelector("#productRow"+item_id);
    const cells = row.cells;
    const cellData = [];
    for (let i = 0; i < cells.length; i++) {
        cellData.push(cells[i].textContent);
    }

    document.getElementById('add_offer_request_product_refcode').value = cellData[3];
    document.getElementById('add_offer_request_product_name').value = cellData[4];
    document.getElementById('add_offer_request_product_quantity').value = cellData[5];
    document.getElementById('add_offer_request_product_measurement').value = cellData[6];
    document.getElementById('add_offer_request_brand').value = cellData[7];
    document.getElementById('add_offer_request_customer_stock_code').value = cellData[2];
    document.getElementById('add_offer_request_owner_stock_code').value = cellData[1];
    document.getElementById('add_offer_request_note').value = cellData[9];


    $('#productRow' + item_id).remove();
    let count = document.getElementById('add_offer_request_product_count').value;
    count = parseInt(count) - 1;
    document.getElementById('add_offer_request_product_count').value = count;
}

async function addOfferRequest(){
    let user_id = localStorage.getItem('userId');

    let products = [];
    let table = document.getElementById("offer-request-products-body");
    for (let i = 0, row; row = table.rows[i]; i++) {
        let item = {
            "owner_stock_code": row.cells[1].innerText,
            "customer_stock_code": row.cells[2].innerText,
            "ref_code": row.cells[3].innerText,
            "product_name": row.cells[4].innerText,
            "quantity": parseInt(row.cells[5].innerText),
            "measurement": row.cells[6].innerText,
            "brand": row.cells[7].innerText,
            "category": row.cells[8].innerText,
            "note": row.cells[9].innerText
        }
        products.push(item);
    }

    let owner = document.getElementById('add_offer_request_owner').value;
    if (owner == 0){owner = null;}
    let personnel = document.getElementById('add_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let purchasing = document.getElementById('add_offer_request_purchasing_staff').value;
    if (purchasing == 0){purchasing = null;}
    let company = document.getElementById('add_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('add_offer_request_company_employee').value;
    if (employee == 0){employee = null;}
    let request_code = document.getElementById('add_offer_request_company_request_code').value;

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "owner_id": owner,
        "authorized_personnel_id": personnel,
        "purchasing_staff_id": purchasing,
        "company_id": company,
        "company_employee_id": employee,
        "company_request_code": request_code,
        "products": products
    });

    console.log(formData);

    let returned = await servicePostAddOfferRequest(formData);
    if (returned){
        window.location = '/sales';
    }else{
        alert("Hata Oluştu");
    }
}
