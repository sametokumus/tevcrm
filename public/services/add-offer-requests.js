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
            let category = document.getElementById('add_offer_request_product_category').value;
            let cust_stock = document.getElementById('add_offer_request_customer_stock_code').value;
            let own_stock = document.getElementById('add_offer_request_owner_stock_code').value;
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            console.log(refcode, product_name, quantity, measurement, brand, category, cust_stock, own_stock);
            addProductToTable(refcode, product_name, quantity, measurement, brand, category, cust_stock, own_stock);
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
    await getCompaniesAddSelectId('add_offer_request_company');
    await getMeasurementsAddSelectId('add_offer_request_product_measurement');
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
    let data2 = await serviceGetCategories();
    console.log(data2)
    let categories = [];
    $.each(data2.categories, function (i, category) {
        let item = {
            "id": category.id,
            "name": category.name
        }
        categories.push(item);
        $.each(category.sub_categories, function (i, sub_category) {
            let item = {
                "id": sub_category.id,
                "name": sub_category.name
            }
            categories.push(item);
        });
    });
    console.log(categories)
    $('#add_offer_request_product_category').typeahead({
        source: categories,
        autoSelect: true
    });
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('add_offer_request_company').value;
    getEmployeesAddSelectId(company_id, 'add_offer_request_company_employee');
}

async function addProductToTable(refcode, product_name, quantity, measurement, brand, category, cust_stock, own_stock){

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
        '           <td>'+ category +'</td>\n' +
        '           <td>\n' +
        '               <div class="btn-list">\n' +
        '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="deleteProductRow('+ count +');"><span class="fe fe-trash-2"> Sil</span></button>\n' +
        '               </div>\n' +
        '           </td>\n' +
        '       </tr>';
    $('#offer-request-products tbody').append(item);
    document.getElementById('add_offer_request_product_refcode').value = "";
    document.getElementById('add_offer_request_product_name').value = "";
    document.getElementById('add_offer_request_product_quantity').value = "0";
    document.getElementById('add_offer_request_product_measurement').value = "adet";
}

async function deleteProductRow(item_id){
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
            "category": row.cells[8].innerText
        }
        products.push(item);
    }

    let owner = document.getElementById('add_offer_request_owner').value;
    if (owner == 0){owner = null;}
    let personnel = document.getElementById('add_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let company = document.getElementById('add_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('add_offer_request_company_employee').value;
    if (employee == 0){employee = null;}

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
        "owner_id": owner,
        "authorized_personnel_id": personnel,
        "company_id": company,
        "company_employee_id": employee,
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
