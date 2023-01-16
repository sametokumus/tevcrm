(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_offer_request_form').submit(function (e){
			e.preventDefault();
            updateOfferRequest();
		});

        $('#add_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('add_offer_request_product_refcode').value;
            let product_name = document.getElementById('add_offer_request_product_name').value;
            let quantity = document.getElementById('add_offer_request_product_quantity').value;
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            addProductToTable(refcode, product_name, quantity);
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
    await getAdminsAddSelectId('update_offer_request_authorized_personnel');
    await getCompaniesAddSelectId('update_offer_request_company');
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('update_offer_request_company').value;
    await getEmployeesAddSelectId(company_id, 'update_offer_request_company_employee');
}

async function addProductToTable(refcode, product_name, quantity){

    let request_id = getPathVariable('offer-request');
    let formData = JSON.stringify({
        "ref_code": refcode,
        "product_name": product_name,
        "quantity": quantity
    });

    console.log(formData);

    let data = await servicePostAddProductToOfferRequest(request_id, formData);
    if (data.status == "success") {
        let product_id = data.object.product_id;

        let count = document.getElementById('update_offer_request_product_count').value;
        count = parseInt(count) + 1;
        document.getElementById('update_offer_request_product_count').value = count;
        let item = '<tr id="productRow'+ product_id +'">\n' +
            '           <td>'+ refcode +'</td>\n' +
            '           <td>'+ product_name +'</td>\n' +
            '           <td>'+ quantity +'</td>\n' +
            '           <td>\n' +
            '               <div class="btn-list">\n' +
            '                   <button type="button" class="btn btn-sm btn-outline-theme" onclick="deleteProductRow('+ product_id +');"><span class="fe fe-trash-2"> Sil</span></button>\n' +
            '               </div>\n' +
            '           </td>\n' +
            '       </tr>';
        $('#offer-request-products tbody').append(item);
        document.getElementById('add_offer_request_product_refcode').value = "";
        document.getElementById('add_offer_request_product_name').value = "";
        document.getElementById('add_offer_request_product_quantity').value = "0";

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
    // let count = document.getElementById('add_offer_request_product_count').value;
    // count = parseInt(count) - 1;
    // document.getElementById('add_offer_request_product_count').value = count;
}

async function initOfferRequest(){
    let request_id = getPathVariable('offer-request');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)

    document.getElementById('update_offer_request_id').value = request_id;
    document.getElementById('update_offer_request_authorized_personnel').value = offer_request.authorized_personnel_id;
    document.getElementById('update_offer_request_company').value = offer_request.company_id;
    document.getElementById('update_offer_request_product_count').value = offer_request.products.length;

    if (offer_request.company_id != null && offer_request.company_id != 0) {
        await initEmployeeSelect();
        document.getElementById('update_offer_request_company_employee').value = offer_request.company_employee_id;
    }

    $.each(offer_request.products, function (i, product) {
        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product.product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
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
    let user_id = sessionStorage.getItem('userId');
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
