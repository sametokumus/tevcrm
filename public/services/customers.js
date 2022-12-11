(function($) {
    "use strict";

	$(document).ready(function() {
        $('#add_company_form').submit(function (e){
            e.preventDefault();
            addCompany();
        });

        $('#add_company_is_potential_customer').click(function (e){
            if(document.getElementById('add_company_is_potential_customer').checked){
                document.getElementById('add_company_is_customer').checked = false;
            }
        });

        $('#add_company_is_customer').click(function (e){
            if(document.getElementById('add_company_is_customer').checked){
                document.getElementById('add_company_is_potential_customer').checked = false;
            }
        });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        initCompanies();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initCompanies(){
	let data = await serviceGetCustomers();
	$('#company-grid .grid-item').remove();

	$.each(data.companies, function (i, company) {
        let logo = "img/company/empty.jpg";
        if (company.logo != null){logo = "https://lenis-crm.wimco.com.tr"+company.logo;}
		let item = '<div class="col-md-4 grid-item">\n' +
            '           <div class="card border-theme mb-3">\n' +
            '               <div class="card-body">\n' +
            '                   <div class="row gx-0 align-items-center">\n' +
            '                       <div class="col-md-3">\n' +
            '                           <img src="'+ logo +'" alt="" class="card-img rounded-0" />\n' +
            '                       </div>\n' +
            '                       <div class="col-md-9">\n' +
            '                           <div class="card-body">\n' +
            '                               <h5 class="card-title">'+ company.name +'</h5>\n' +
            '                               <p class="card-text">Eposta: '+ company.email +'</p>\n' +
            '                               <p class="card-text">Telefon: '+ company.phone +'</p>\n' +
            '                               <p class="card-text">Faks: '+ company.fax +'</p>\n' +
            '                           </div>\n' +
            '                       </div>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '               <div class="card-arrow">\n' +
            '                   <div class="card-arrow-top-left"></div>\n' +
            '                   <div class="card-arrow-top-right"></div>\n' +
            '                   <div class="card-arrow-bottom-left"></div>\n' +
            '                   <div class="card-arrow-bottom-right"></div>\n' +
            '               </div>\n' +
            '           </div>\n' +
            '       </div>';
		$('#company-grid').append(item);
	});

}
async function addCompanyCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    $("#add_company_form").trigger("reset");
    $("#addCompanyModal").modal('hide');
    initCompanies();
}
async function addCompany(){
    let isPotential, isCustomer, isSupplier = 0;
    if(document.getElementById('add_company_is_potential_customer').checked){
        isPotential = 1;
    }
    if(document.getElementById('add_company_is_customer').checked){
        isCustomer = 1;
    }
    if(document.getElementById('add_company_is_supplier').checked){
        isSupplier = 1;
    }
    let formData = new FormData();
    formData.append('name', document.getElementById('add_company_name').value);
    formData.append('website', document.getElementById('add_company_email').value);
    formData.append('email', document.getElementById('add_company_website').value);
    formData.append('phone', document.getElementById('add_company_phone').value);
    formData.append('fax', document.getElementById('add_company_fax').value);
    formData.append('address', document.getElementById('add_company_address').value);
    formData.append('tax_office', document.getElementById('add_company_tax_office').value);
    formData.append('tax_number', document.getElementById('add_company_tax_number').value);
    formData.append('is_potential_customer', isPotential);
    formData.append('is_customer', isCustomer);
    formData.append('is_supplier', isSupplier);
    formData.append('logo', document.getElementById('add_company_logo').files[0]);

    await servicePostAddCompany(formData);
}
async function updateCompanyCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    $("#update_brand_form").trigger("reset");
    $('#updateBrandModal').modal('hide');
    initBrandView();
}
