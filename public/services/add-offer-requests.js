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
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            addProductToTable(refcode, product_name, quantity, measurement);
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
    await getAdminsAddSelectId('add_offer_request_authorized_personnel');
    await getCompaniesAddSelectId('add_offer_request_company');
    await getMeasurementsAddSelectId('add_offer_request_product_measurement');
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('add_offer_request_company').value;
    getEmployeesAddSelectId(company_id, 'add_offer_request_company_employee');
}

async function addProductToTable(refcode, product_name, quantity, measurement){
    let count = document.getElementById('add_offer_request_product_count').value;
    count = parseInt(count) + 1;
    document.getElementById('add_offer_request_product_count').value = count;
    let item = '<tr id="productRow'+ count +'">\n' +
        '           <td>'+ refcode +'</td>\n' +
        '           <td>'+ product_name +'</td>\n' +
        '           <td>'+ quantity +'</td>\n' +
        '           <td>'+ measurement +'</td>\n' +
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
            "ref_code": row.cells[0].innerText,
            "product_name": row.cells[1].innerText,
            "quantity": parseInt(row.cells[2].innerText),
            "measurement": parseInt(row.cells[3].innerText)
        }
        products.push(item);
    }

    let personnel = document.getElementById('add_offer_request_authorized_personnel').value;
    if (personnel == 0){personnel = null;}
    let company = document.getElementById('add_offer_request_company').value;
    if (company == 0){company = null;}
    let employee = document.getElementById('add_offer_request_company_employee').value;
    if (employee == 0){employee = null;}

    let formData = JSON.stringify({
        "user_id": parseInt(user_id),
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
